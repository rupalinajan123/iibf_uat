<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Downloads
        
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="addForm" id="addForm" action="" method="post"> 
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $title; ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php //echo validation_errors(); ?>
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
                
                <div class="form-group">
                	<label for="from_date" class="col-sm-2 control-label">From Date *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>" readonly>
                             <span class="error"><?php echo form_error('from_date');?></span>
                        </div>
                    
                  
                    <label for="to_date" class="col-sm-2 control-label">To Date *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" required value="<?php echo set_value('to_date');?>" readonly>
                             <span class="error"><?php echo form_error('to_date');?></span>
                        </div>  
                </div>
                
                <div class="form-group">
                	  <label for="report_type" class="col-sm-2 control-label">Report Type *</label>
                      <div class="col-sm-3" style="padding-top:5px;">
                      	<label>
                      		<input type="radio" name="report_type" id="report_type1" value="CSV" checked="checked" <?php echo set_checkbox('report_type', 'CSV', false); ?>> CSV Format
                      		<input type="radio" name="report_type" id="report_type2" value="TEXT" <?php echo set_checkbox('report_type', 'TEXT', false); ?>> TEXT Format
                    	</label>
                   </div>
                
                    <label for="record_no" class="col-sm-2 control-label">No.Of Records *</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control input-selector" id="record_no" name="record_no" placeholder="No.Of Records" required value="<?php echo set_value('record_no');?>" data-parsley-type="number" data-parsley-pattern="/^[1-9]/" data-parsley-trigger-after-failure="focusout">
                         <span class="error"><?php echo form_error('record_no');?></span>
                    </div>
                </div>
                
                
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit"> 
                   <a href="<?php echo base_url();?>admin/MainController" class="btn btn-default pull-right">Back</a>
                    </div>
              </div>
              
           <?php if($result_text!=''){?>
              <div class="box-footer">
                 <div class="box-body">
           		
					<?php echo $result_text; ?>
                    <?php //echo $links; ?>
                    
                  </div>
              </div>
         <?php } ?>
              
           </div>
        </div>
      </div>
     
    </section>
    </form>
  </div>
  
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">

$('#addForm').parsley('validate');

$(document).ready(function() 
{
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});	
   
   
   $('.input-selector').on('keypress', function(e){
	  return e.metaKey || // cmd/ctrl
		e.which <= 0 || // arrow keys
		e.which == 8 || // delete key
		/[0-9]/.test(String.fromCharCode(e.which)); // numbers
	});

});

function download_file(no, format, optval, regno, regid, records)
{  
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+"admin/Downloads/download_file";
	/*var width = 100;
	var height = 100;	
	mywindow=window.open(url,"mywindow11","location=0,status=0,scrollbars=1,resizable=1,menubar=0,width="+width+",height="+height);*/
	
	//alert("no :"+no+", format : "+format+", dateInput : "+optval+", regno : "+regno+", regid : "+regid+", records : "+records);
	
	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {no : no, format : format, dateInput : optval, regno : regno, regid : regid, records : records },
		success: function(res)
		{
			
		}
	});
}
		
</script>


</script>
 
<?php $this->load->view('admin/includes/footer');?>