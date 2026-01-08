<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

<style>
 .borderNone  {
	border: none !important;
}
</style>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Refund List</h1>
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
            <h3 class="box-title">Search By</h3>
            <div class="pull-right"> 
              <!--<a href="<?php echo base_url();?>admin/Search/search_success" class="btn btn-info">Refresh</a>--> 
            </div>
          </div>
          <div class="box-body">
          
            <form class="form-horizontal" name="search" id="search" action="<?php echo base_url();?>admin/Refund_stats/dashboard" method="post">
              <div class="form-group">
                <div class="col-sm-10 col-sm-offset-1">
                  <center> 
                    
				<div class="form-group">
				<label for="" class="col-sm-4 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					    <div class="col-sm-6"  style="display:block" ><select id="exam_code" name="exam_code" class="form-control" required>
							 <option value="">--Select--</option>
							 <?php if(count($exams))
								{
									foreach($exams as $exams_row)
									{ 	?>
										<option value="<?php echo $exams_row['exam_code'];?>" <?php echo  set_select('exam_code', $exams_row['exam_code']); ?>><?php echo $exams_row['description'];?></option>
									<?php 
									}
								}?>
							</select>
					
							
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
				</div>
                <center>
                      <button type="submit"  name="btnSubmit" id="btnSubmit" class="btn btn-info">SUBMIT</button>
                </center>
                 </center>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Result</h3>
          </div>
          <!-- /.box-header  
            S.No. 	Exam Code 	Exam Name 	Member Count
            -->
            <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
			  <?php if(!empty($total_count)) { ?>
			  <tr><center><h4> Total Count = <?php echo $total_count;?></h4></center></tr>
			  <?php } ?>
                <tr>
                    <th>S.No.</th>
					<th>Member Number</th>
					<th>Member Name</th>
					<th>Member Email-ID</th>
                    <th>Venue</th>
                    <th>Exam Date</th>
                    <th>Exam Time</th>
                  <!--<th>Total Capacity</th>-->
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">   
                <?php 
				 $count=1;
			    if(isset($result) )
			    { 
				    foreach($result as $crow)
				    {
					
			    ?>
                <tr>
                    <td><?php echo $count ;?></td>
					<td><?php echo $crow['member_no']; ?></td>
					<td><?php echo $crow['mem_name']; ?></td>
					<td><?php echo $crow['email']; ?></td>
                    <td><?php echo $crow['venue_name'].' '. $crow['venueadd1'].' '. $crow['venueadd2'].' '. $crow['venueadd3'].' '. $crow['venueadd4'].' '. $crow['venueadd5'];?></td>
                    <td><?php echo $crow['exam_date'];?></td>
                    <td><?php echo $crow['time'];?></td> 
                <!--<td><?php //echo  $crow['session_capacity']; ?></td>-->
                </tr>
             
                <?php 
				   $count++;
				   }  
			    }
				else
			    {?>
                <tr align="center">
                 <td colspan="9" style="color:#F00;"><?php echo $msg ;?></td>
                </tr>
                <?php  } ?>
				<tr align="center">  <td colspan="9" style="color:#F00;"><?php echo $msg;?></td>
                </tr> <?php  ?>
              </tbody>
            </table>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
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
<script src="<?php echo base_url()?>js/exam_count.js?<?php echo time(); ?>"></script> 
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
	
	<?php if(isset($_POST['exam_code']) && $_POST['exam_code']!='')
	{ ?>
		GetExamPeriod('<?php echo $_POST['exam_code'];?>');
		
		
	<?php }
	
	if(isset($_POST['exam_period']) && $_POST['exam_period']!=''){ ?>
	//alert('<?php //echo $_POST['exam_period'] ?>');
		setTimeout(  function(){ $("#exam_period").val('<?php echo $_POST['exam_period'];?>');  }, 500);
	<?php } ?>
	
	
	
	/*$(".chk").on('click', function(e){
		alert('in');
		
			var status = this.checked; // "select all" checked status
			alert(status);
			$('.chk').each(function(){ //iterate all listed checkbox items
				this.checked = status; //change ".checkbox" checked status
			});
		
	})*/
	
	/*function printContent(searchBy,searchkey)
	{
		var base_url = '<?php echo base_url(); ?>';
		$.ajax({
			url: base_url+'admin/Report/getExamDetailsToPrint',
			type: 'POST',
			dataType:"json",
			data: {field : searchBy, value : searchkey },
			success: function(res) {
				if(res)
				{
					if(res.success == 'Success')
					{
						var content = '';
						for(i=0;i<res.result.length;i++)
						{
							var resultrow = res.result[i].firstname;
							//alert(resultrow);
							var index = i+1;
							content += '<tr><td>'+index+'</td><td>'+res.result[i].regnumber+'</td><td>'+res.result[i].firstname+'</td><td>'+res.result[i].gender+'</td><td>'+res.result[i].description+'</td><td>'+res.result[i].exam_fee+'</td><td>'+res.result[i].medium_description+'</td><td>'+res.result[i].center_name+'</td><td>'+res.result[i].transaction_no+'</td><td>'+res.result[i].transaction_details+'</td><td>'+res.result[i].date+'</td></tr>';
						}
						$("#print_list").html(content);
						$("#printBtn").show();
					}
					else
						$("#printBtn").hide();
				}
				else
					$("#printBtn").hide();
			}
		});
	}*/
});

function GetExamPeriod(ex_code)
{
	var site_url = '<?php echo base_url(); ?>';
	//alert(ex_code);
	if(ex_code)
	{
		$.ajax({
			url:site_url+'admin/Report/GetExamPeriod/'+ex_code,
			dataType:"text",	
			type:'GET',
			success: function(data) {
				//alert(data);
				if(data != '')
				{
					$("#exam_period").html(data);	
				}
			}
		});
	}
}

$(function () {
	//$("#listitems").DataTable();
	/*var base_url = '<?php //echo base_url(); ?>';
	var listing_url = base_url+'admin/Report/getList';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		

		
</script> 
<script>
function printDiv(divName) {
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
<?php $this->load->view('admin/includes/footer');?>
