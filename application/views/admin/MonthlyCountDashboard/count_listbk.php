<?php $this->load->view('admin/MonthlyCountDashboard/includes/header');?>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<style>
table.dataTable.cell-border tbody td{border:1px solid #ddd;}
table.dataTable thead > tr > th {border: 1px solid #ddd !important;border-collapse: unset !important;border-top: 1px solid #ddd !important;}
table.table-bordered.dataTable {border-collapse: collapse !important;}
.dataTables_filter {display: none;}
</style>

<?php $currentdate =  $this->uri->segment(5);?>
<div class="content-wrapper"> 
  <br />
  <div class="col-md-12 msg">
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
            <h3 class="box-title">Datewise Count  <?php //echo date("jS F , Y", strtotime($currentdate)); ?> </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table id="listitems" class="table table-bordered table-striped dataTables-example cell-border">
              <thead>
                <tr>
                  <th >Module Name</th>
                  <th >Pay Type</th>
                  <th >ESDS Invoice Count</th>
				  <th >ESDS App Count</th>
                  <th >Invoice Hist Count</th>
                  <th >Invoice Upload Count</th>
				  <th >App Hist Count</th>
                  <th >App Upload Count</th>
                  <?php if($this->session->userdata('user_ses') == 'pallavi' && $this->uri->segment(4) != 'all_count' ){?>
                  <th >Edit Count</th>
				  <th >Save Count</th>
                  <?php }?>
                </tr>
              </thead>
              	<?php
					echo '<pre>';
					print_r($result);
					exit;
					if(count($result) > 0) {
						foreach($result as $record){
				?>
              	<tr>
                	<td><?php echo $record['module_name'];?></td>
                    <td><?php echo $record['pay_type'];?></td>
                    <td><?php echo $record['invoice_cnt'];?></td>
                    <td><?php echo $record['app_cnt'];?></td>
                    <td><input type="text" name="textfield1"  id="textfield1<?php echo $record['pay_type']; ?>" disabled="disabled" value="<?php echo $record['invoice_hist'];?>" ></td>
                    
                    <td><input type="text" name="textfield2"  id="textfield2<?php echo $record['pay_type']; ?>" disabled="disabled" value= "<?php echo $record['invoice_upload'];?>" ></td>
                    <td><input type="text" name="textfield3"  id="textfield3<?php echo $record['pay_type']; ?>" disabled="disabled" value= "<?php echo $record['app_hist'];?>" ></td>
                    <td><input type="text" name="textfield4"  id="textfield4<?php echo $record['pay_type']; ?>" disabled="disabled" value= "<?php echo $record['app_upload'];?>" ></td>
                   
                    <?php if($this->session->userdata('user_ses') == 'pallavi' && $this->uri->segment(4) != 'all_count'){?>
                    <td><a class = "btn btn-info edit_row" data-key="" id = "<?php echo $record['pay_type']; ?>" >Edit</a></td>
                    <td><a class = "btn btn-info save_row" name = "btnSubmit" onclick="save_row('<?php echo $record['pay_type']; ?>','<?php echo $record['module_name']; ?>','<?php echo $record['pay_type']; ?>','<?php echo $record['invoice_cnt']; ?>','<?php echo $record['app_cnt']; ?>','<?php echo $currentdate; ?>')" id="btnSubmit"> Save</a></td>
                    <?php }?>
                </tr>
                <?php } }?>
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
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 


<script type="application/javascript">
$(document).ready(function() {
    $('#listitems').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false
    } );
} );
$(document).ready(function(){
	 $(".edit_row").click(function(){ 
		 var key = $(this).attr('id');
		 $('#textfield1'+key).prop("disabled", false);
		 $('#textfield2'+key).prop("disabled", false);
		 $('#textfield3'+key).prop("disabled", false);
		 $('#textfield4'+key).prop("disabled", false);
	 });
})

function save_row(key,module_name,pay_type,invoice_counts,esds_app_cnt,currentdate){
  	 var textfield1 = $( "#textfield1"+key).val();
	 var textfield2 = $( "#textfield2"+key ).val();
	 var textfield3 = $( "#textfield3"+key ).val();
	 var textfield4 = $( "#textfield4"+key ).val(); 
	
	var url = '<?php echo base_url(); ?>'+'admin/MonthlyCount/monthlycount/add_count';
    $.ajax({ 
			url : url,
			type : "POST",
			dataType: 'JSON',
			data : {module_name:module_name,pay_type:pay_type,invoice_counts:invoice_counts,esds_app_cnt:esds_app_cnt,textfield1:textfield1,textfield2:textfield2,textfield3:textfield3,textfield4:textfield4 ,currentdate:currentdate},
		 
			  success:function(data){ 
				if(data.status == 'success'){ 
					$('.msg').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.userMsg+'</div>');
					$('.msg').fadeOut(5000); 
					$('#textfield1'+key).prop("disabled", true);
					$('#textfield2'+key).prop("disabled", true);
					$('#textfield3'+key).prop("disabled", true);
					$('#textfield4'+key).prop("disabled", true);                
				}else{
				   $('.msg').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.userMsg+'</div>');                                                    
				}
			   },
			 error:function(data){
				  $("#btnSubmit").html('Loading...');
			  }
      	});
	$(".loading").hide();
  }

$(function () {
	$("#listitems").DataTable();
});
		
</script>
<?php $this->load->view('admin/MonthlyCountDashboard/includes/footer');?>
