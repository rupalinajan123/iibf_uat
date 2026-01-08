<?php $this->load->view('mempass/includes/header');?>
<?php $this->load->view('mempass/includes/sidebar');?>

<!-- Content Wrapper. Contains page content -->
<script>var site_url="<?php echo base_url();?>";</script>
<div class="content-wrapper"> 
  <!-- Content Header (Page header) --> 
  <!--<section class="content-header">
    <h1> Blended Course Registrations List </h1>
  </section>--> 
  <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible" id="success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('error'); ?> </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible" id="error">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
    
    <div class="alert alert-danger alert-dismissible" id="error" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
    </div>
	  
	  <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
    </div>
	  
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-12">
              <h3 class="box-title">Search Filters</h3>
            </div>
          </div>
          <div class="box-body">
            <form class="form-horizontal" name="btnSearch" id="btnSearch" action="<?php echo base_url();?>MemPass/"  method="post">
              <!--<div class="col-sm-2">
                <input type="text" class="form-control" id="member_no" name="member_no" placeholder="Membership No."/>
              </div>-->
			   <div for="roleid" class="col-sm-12 control-label" style="text-align: right; width:35%;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
            <div class="col-sm-12 col-md-3">
           <input type="text" class="form-control" name="member_no" id="member_no"></div>
             
			   
                
             
              <div class="col-sm-4">	<input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search"></div>
              
              
              
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Member Password</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="" nowrap="nowrap">Sr</th>
                  <th id="10%" nowrap="nowrap">Member No.</th>
                  <th id="10%" nowrap="nowrap">Password</th>
                  
                </tr>
              </thead>
              <?php 
                $row_count=1;
                if(count($res)){
                  
                  foreach($res as $row)
                  {?>
                  
                  <tr>
                    <td align="center"><?php echo $row_count;?></td>
                    <td align="center"><?php echo $row['regnumber'];?></td>
                    <td align="center"><?php echo $row['usrpassword'];?></td>
                    
                  </tr>
                <?php  $row_count++;}} ?>
                
                
            </tbody>
            
          </table>
        </div>
      </div>
      <!-- /.box-body --> 
    </div>
    <!-- /.box --> 
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
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script> 
<script type="text/javascript">
  $('#search').parsley('validate');
</script> 

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>--> 
<script type="application/javascript">
  $(document).ready(function() 
  {
    /*	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
      $('#to_date').datepicker('setStartDate', new Date($(this).val()));
      }); 
      
      $('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
      $('#from_date').datepicker('setEndDate', new Date($(this).val()));
    });*/
    
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
    $("#listitems").DataTable();
    /*var base_url = '<?php // echo base_url(); ?>';
      var listing_url = base_url+'admin/kyc/Kyc/recommended_list/';
      
      // Pagination function call
      paginate(listing_url,'','','');
    $("#base_url_val").val(listing_url);*/
  });
  
  function snedMail(member_no,batch_id,batch_type)
  {
    if(member_no!='' && batch_id!='' && batch_type!='')
    {
      $(".loading").show();
			var datastring='member_no='+member_no+'&batch_id='+batch_id+'&batch_type='+batch_type;
			$.ajax({
        url:site_url+'TrainingStat/send_mail/',
        data: datastring,
        type:'POST',
        async: false,
        dataType: 'json',
        success: function(data) {
          //$.parseJSON(data);
          if(data)
          {
            if(data.ans==1)
            {
              $('#success').css("display", "block");
              $('#success').html(data.success);
              $('#success').fadeOut(5000);
            }
            else if(data.ans==0)
            {
              $('#error').html(data.error);
            }
          }
        }
      });		
			$(".loading").hide();
    }
  }
  
  
  
</script>
<?php $this->load->view('mempass/includes/footer');?>
