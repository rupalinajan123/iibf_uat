<?php $this->load->view('admin/venue_master/includes/header');?>
<?php $this->load->view('admin/venue_master/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 30%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Upload Venue </h1>
    <?php //echo $breadcrumb; ?>
  </section>
  <section class="content">
    <form class="form-horizontal" name="addForm" id="addForm" action="<?php echo base_url();?>admin/venue_master/ExamVenueDashboard/upload_csv" method="post" enctype="multipart/form-data" >
      <!-- Main content -->
      <div class="row">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <?php // echo $title; ?>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <?php //echo validation_errors(); ?>
              <?php if($error!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                <?php echo $error; ?> </div>
              <?php } if($success!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $success; ?> </div>
              <?php } ?>
			  
			  
			  <div class="row">
	<div class="column" >
   <div class="control-group">
                <div class="col-md-12"><input type="file" name="userfile" onclick = "return delete_table_data();" size="20"></div>   
                <br><br>
                <br><br>
<div class="col-md-12">
                <input type="submit" class="btn btn-info" name="btnAdd" id="btnAdd" value="Add Venue">
                <input type="submit" class="btn btn-info" name="btnUpdate" id="btnUpdate" value="Update Venue">
 </div>
</div>
<div class="control-group">
                  
                <br><br>
                <br><br>
				<!--
<div class="col-md-12">
<a data-toggle="tooltip" class="btn btn-warning" href="http://iibf.teamgrowth.net/admin/venue_master/ExamVenueDashboard/error_report" data-original-title="" title=""> Download Error Report </a> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo 'Total Count:- '.$total_count_time; ?>

				
</div>
<br><br>
<br><br> -->
<div class="col-md-12">
<a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url();?>admin/venue_master/ExamVenueDashboard/duplicate_record" data-original-title="" title=""> Download Dup Record </a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo 'Total Count:- '.$total_count_dup; ?>
                
 </div>
 <br><br>
<br><br>
<div class="col-md-12">
<a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url();?>admin/venue_master/ExamVenueDashboard/capacity_error" data-original-title="" title=""> Download Cappacity Error </a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo 'Total Count:- '.$total_count_capacity; ?>
                
 </div>
</div>
				  </div>
				</div>
		 

            
			   
			   
              
			  
            </div>
          </div>
         
          <?php //if($result_text!=''){?>
          <div class="box-footer">
            <div class="box-body">
              <?php //echo $result_text; ?>
              <?php //echo $links; ?>
            </div>
          </div>
          <?php // } ?>
        </div>
      </div>
    </form>
   
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
<script>
function delete_table_data()
{
	$(".loading").show();
				$.ajax(
				{
					type: 'POST',
					//url: '<?php echo site_url("admin/venue_master/ExamVenueDashboard/delete_data/"); ?>',
					url: site_url+"admin/venue_master/ExamVenueDashboard/delete_data/",
					async: false,
					success: function(data)
					{	
						if(data!='')
						{
							//$('#captcha_img2').html(res);
							$('#userfile').html(data);
							//$("#captcha_code").val("");
							//$("#captcha_code-error").html("");
						}
						$(".loading").hide();
					}
				});
}
</script>
<?php $this->load->view('admin/venue_master/includes/footer');?>

