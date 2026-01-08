<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<!--fancybox-->



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Approval KYC Verification
    </h1>
    <?php echo $breadcrumb; ?>
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
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        
        <div class="box">
          <div class="box-header">
            <div class="col-md-12">
              <h3 class="box-title">Select List:</h3>
              <div class="form-group">
                <form class="form-horizontal" name="searchDate" id="searchDate" action="" method="post">
                  
                  
                  <label for="list" class="col-sm-2">Checklist For:</label>
                  <div class="col-sm-2">
                    <select name="memberlist" id="memberlist" class="form-control">
                      <option value="">Select</option>
                      <option value="new_m">New Member</option>
                      <option value="edit_m">Edit Member</option>
                    </select>
                    
                  </div>
                  
                  <label for="to_date" class="col-sm-2">Member Type:</label>
                  <div class="col-sm-2">
                    <select name="registrationtype" id="registrationtype" class="form-control">
                      <option value="">Select</option>
                      <?php if(count($mem_type)){
                        foreach($mem_type as $row){	
                        ?>
                        <option value="<?php echo $row['registrationtype']; ?>" <?php if($this->session->userdata('registrationtype')!=''){ echo "selected='selected'";}?> ><?php echo $row['registrationtype']; ?></option>
                      <?php } }?>
                    </select>
                    <span class="error"><?php echo form_error('regnumber');?></span>
                  </div>
                  <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="View" > 
                  <input type="reset" class="btn btn-info" name="btnReset" id="btnReset" value="Reset" > 
                </form>
              </div>
              
            </div>
            
          </div>
          <!--<div class="box-body">
            
          </div>-->
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12">
        
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Member selected</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="">Membership No</th>
                  <th id="">Candidate Name</th>
                  <th id="">D.O.B</th>
                  <th id="">Email</th>
                  <th id="">registrationtype</th>
                  <th id="">Action</th>
                  
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">
                <?php if(count($result)){
                  foreach($result as $row){  
                  ?>
                  <tr>
                    <td><?php echo $row['regnumber'];?></td>
                    <td><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
                    <td><?php echo date('d-m-Y',strtotime($row['dateofbirth']));?></td>
                    <td><?php echo $row['mobile'];?></td>
                    <td><?php echo date('d-m-Y',strtotime($row['createdon']));?></td>
                    <td>
                      <a href="<?php echo base_url(); ?>admin/Kyc/details/<?php echo base64_encode($row['regid']); ?>/<?php echo base64_encode($row['regnumber']); ?>">View Details</a>
                    </td>
                  </tr>
                <?php }} ?>                  
              </tbody>
            </table>
            <!--<div style="width:30%; float:left;">
              <?php //echo $info; ?>
            </div>-->
            <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
            <!--<div id="links" class="dataTables_paginate paging_simple_numbers">
              
            </div>-->
            
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col 
        
        
        
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
          
          /*$(".chk").on('click', function(e){
            alert('in');
            
            var status = this.checked; // "select all" checked status
            alert(status);
            $('.chk').each(function(){ //iterate all listed checkbox items
            this.checked = status; //change ".checkbox" checked status
            });
            
          })*/
        });
        
        $(function () {
          //$("#listitems").DataTable();
          /*var base_url = '<?php //echo base_url(); ?>';
            var listing_url = base_url+'admin/Report/getList';
            
            // Pagination function call
            paginate(listing_url,'','','');
          $("#base_url_val").val(listing_url);*/
        });
        
        
        
      </script>
      
    <?php $this->load->view('admin/kyc/includes/footer');?>    