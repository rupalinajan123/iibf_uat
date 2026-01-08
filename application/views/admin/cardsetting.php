<?php $this->load->view('admin/includes/header');?>

<?php $this->load->view('admin/includes/sidebar');?>

<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Card setting </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Card setting</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <!-- Info boxes -->
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Application Settings - Admit card:</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              
              <div class="table-responsive col-sm-4">
                   <form name="form" id="form" method="post" action="<?php echo base_url();?>admin/admitcard/add">
                      <div class="form-group">
                        <label for="Start Date">Start Date:</label>
                        <input type="text" class="form-control" id="from_date" name="from_date" value="">
                      </div>
                      <div class="form-group">
                        <label for="End Date">End Date:</label>
                        <input type="text" class="form-control" id="to_date" name="to_date" value="">
                      </div>
                      <div class="form-group">
                        <label for="Exam Code">Select Exam:</label>
                        <select name="exam_code" id="exam_code" class="form-control">
                        	<option value="">-Select Exam-</option>
                            <?php foreach($examinfo as $examinfo){?>
                            <option value="<?php echo $examinfo->exam_code ?>"><?php echo $examinfo->description?></option>
                            <?php }?>
                        </select>
                      </div>
                      <input type="submit" class="btn btn-default" value="Submit" name="submit">
                   </form>
              </div>
              
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
      </div>
    
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->


<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<script>
$(document).ready(function() 
{
	$('#from_date').datepicker({format: 'yyyy-mm-dd',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
	
	jQuery.validator.addMethod("chkdate", function(value, element) {
	  var from_date = $("#from_date").val();
	  var to_date   = $("#to_date").val();
		if(from_date > to_date) {
			return false;
		}else{
			return true;	
		}
	}, "From date must be less than to date");
	
	var validator = $("#form").validate({
		errorElement: 'div',
		rules: {
			from_date:{required: true},
			to_date:{required: true,chkdate:true},
			exam_code:{required: true}
			
		},
		messages: {
			
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent() );
		},
		submitHandler: function(form) { 
			form.submit();
		}
	});
});
</script>
<?php $this->load->view('admin/includes/footer');?>