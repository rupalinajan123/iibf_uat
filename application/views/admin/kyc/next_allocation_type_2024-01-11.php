<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>  New member list </h1>
  </section>
  <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('error'); ?> </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            
            
            <div class="col-sm-12">
              <form class="form-horizontal" name="Select By" id="Select By" action="<?php echo base_url();?>admin/kyc/Kyc/next_allocated_list" method="post">      
                <center>   <label>  Select member type to get  next 100 records ...</label></center><br>
                <label for="to_date" class="col-sm-2">Member type</label>
                <div class="col-sm-2">
                  <select class="form-control" name="selectby" id="selectby" required>
                    <option value="">Select</option>
                    <option value="O" >O</option>
                    <option value="F" >F</option>
                    <option value="A" >A</option>
                    <option value="NM" >NM</option>
                    <option value="DB" >DB</option>
                  </select>
                </div>
                
                <div class="col-sm-2">
                  <input type="submit" class="btn btn-info" name="btnselect" id="btnselect" value="Submit">
                  
                </div> 
              </form> 
            </div>
            <center> 
              
            </center>
            
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>
  
</div>

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
    $('.dataTables_empty').html('');
    $("#listitems").DataTable({
      "language": {
        "infoEmpty": "No records available - Got it?",
      }
    });
    var base_url = '<?php echo base_url(); ?>';
    var listing_url = base_url+'admin/kyc/Kyc/allocated_list/';
    
    
    // Pagination function call
    //paginate(listing_url,'','','');
    //$("#base_url_val").val(listing_url);
    
  });
  
  
  
  
</script>
<?php $this->load->view('admin/kyc/includes/footer');?>