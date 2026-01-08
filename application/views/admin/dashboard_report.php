<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard - Exam Registration Details
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
              <h3 class="box-title">Search By</h3>
              <div class="pull-right">
              	<!--<a href="<?php echo base_url();?>admin/Search/search_success" class="btn btn-info">Refresh</a>-->
              </div>
            </div>
           <div class="box-body">
            
                <div class="col-sm-12">
                <form class="form-horizontal" name="search" id="search" 
                  action="<?php echo base_url();?>admin/Report/dashboard" method="post">
                    <div class="form-group">
                    	<label for="from_date" class="col-sm-1">Exam Code</label>
                        <div class="col-md-2">
                        	<select class="form-control" id="exam_code" name="exam_code"  onchange="return GetExamPeriod(this.value);">
                                <option value="">Select</option>
                                <?php if(count($exam_code)){
                                        foreach($exam_code as $row){ 	?>
                                <option value="<?php echo $row['exam_code'];?>" <?php if(isset($_POST['exam_code']) && $_POST['exam_code']==$row['exam_code']){ echo "selected='selected'"; }?>><?php echo $row['exam_code'];?></option>
                                <?php } } ?>
                          </select>
                        </div>
                        
                        <label for="from_date" class="col-sm-1">Exam Period</label>
                        <div class="col-md-2">
                        	<select class="form-control" id="exam_period" name="exam_period" required onchange="return GetExamDate(this.value);" >
                                <option value="">Select</option>
                                <?php if(count($exam_period)){
                                        foreach($exam_period as $row1){ 	?>
                                <option value="<?php echo $row1['exam_period'];?>" <?php if(isset($_POST['exam_period']) && $_POST['exam_period']==$row1['exam_period']){ echo "selected='selected'"; }?>><?php echo $row1['exam_period'];?></option>
                                <?php } } ?>
                            </select>
                        </div>
                        
                        <?php /*if(count($exam_period)){
                                        foreach($exam_period as $row1){ 	?>
                                <option value="<?php echo $row1['exam_period'];?>" <?php if(isset($_POST['exam_period']) && $_POST['exam_period']==$row1['exam_period']){ echo "selected='selected'"; }?>><?php echo $row1['exam_period'];?></option>
                                <?php } }*/ ?>
                        
                        <div class="col-md-2">  
                           <select class="form-control" id="search_for" name="search_for" required>
                                <option value="">Select</option>
                                <option value="01" <?php if(isset($_POST['search_for']) && $_POST['search_for']=="01"){ echo "selected='selected'"; }?>>Non Member</option>
                                <option value="02" <?php if(isset($_POST['search_for']) && $_POST['search_for']=="02"){ echo "selected='selected'"; }?>>All</option>
                              </select>
                         </div>
                         
                         <label for="exam_date" class="col-sm-1">Exam Date</label>
                        <div class="col-md-2">
                        	<select class="form-control" id="exam_date" name="exam_date" >
                                <option value="">Select</option>
                                <?php if(count($exam_date)){ //print_r($exam_date);
                                        foreach($exam_date as $row2){ 	?>
                                <option value="<?php echo $row2['exam_date'];?>" <?php if(isset($_POST['exam_date']) && $_POST['exam_date']==$row1['exam_date']){ echo "selected='selected'"; }?>><?php echo $row2['exam_date'];?></option>
                                <?php } } ?>
                            </select>
                        </div>
                         
                         <div class="col-md-1">  
                          	<input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="">
                         </div>     
                         <div class="col-md-1" style="float:right;margin-top:8px;">  
                          	<a href="javascript:void(0);" class="btn btn-warning" onclick="javascript:printDiv();" id="printBtn" style="display:<?php if(count($result)){ echo "block";} else {echo "none";} ?>">Print</a>
                         </div>      
                   </div>
                  
                </form>
                 </div>
              
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
                <tr>
                  <th id="">S.No.</th>
                  <th id="">Exam Code</th>
                  <th id="">Exam Name</th>
                  <th id="">Member Count</th>
                  <th id="">Action</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if(count($result)){
					  	$i = 1;
						$total = 0;
						foreach($result as $row){  
				  ?>
                    <tr>
                    	<td><?php echo $i;?></td>
                        <td><?php echo $row['exam_code'];?></td>
                        <td><?php echo $row['description'];?><?php if($row['exam_code']==1003){echo '(With Payment)';}?></td>
                        <td><?php echo $row['mem_cnt'];?></td>
                        <td><?php echo $row['mem_cnt'];?></td>
                    </tr>
                  <?php 
				  		$i++;
						$total += $row['mem_cnt'];
				  		}	?>
                        
                       <tr>
                       		<td align="center" colspan="3"><strong>Total</strong></td>
                            <td><strong><?php echo $total; ?></strong></td>
                       </tr>
                        
                        <?php }else{ ?>                  
                  	<tr>
                    	<td colspan="4" align="center">No records found...</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
				
				  <?php
					if(count($withot_pay) > 0)
					{?>
				<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="">S.No.</th>
                  <th id="">Exam Code</th>
                  <th id="">Exam Name</th>
                  <th id="">Member Count</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if(count($withot_pay)){
					  	$i = 1;
						$total_without_pay = 0;
						foreach($withot_pay as $row){  
				  ?>
                    <tr>
                    	<td><?php echo $i;?></td>
                        <td><?php echo $row['exam_code'];?></td>
                        <td><?php echo $row['description'];?> (Without Payment)</td>
                        <td><?php echo $row['mem_cnt'];?></td>
                    </tr>
                  <?php 
				  		$i++;
						$total_without_pay += $row['mem_cnt'];
				  		}	?>
                        
                       <tr>
                       		<td align="center" colspan="3"><strong>Total</strong></td>
                            <td><strong><?php echo $total_without_pay; ?></strong></td>
                       </tr>
					
					<tr>
                       		<td align="center" colspan="3"><strong>Final Total</strong></td>
                            <td><strong><?php echo $total+$total_without_pay; ?></strong></td>
                       </tr>
                        
                        <?php }else{ ?>                  
                  	<tr>
                    	<td colspan="4" align="center">No records found...</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
               <?php
					}?>
      
    </section>
   
  </div>
  
  <div class="content-wrapper" id="print_div" style="display: none;">
<!-- Content Header (Page header) -->
    <div  style=" background: #fff;border: 1px solid #000; padding:10px; width:100%;">
        <table width="90%" cellspacing="0" cellpadding="10" border="0" align="center" >         
        	<tr> <td colspan="4" align="left">&nbsp;</td> </tr>
            <tr>
            
                <td colspan="4" align="center" height="25">
                    <span id="1001a1" class="alert">
                    </span>
                </td>
            </tr>
        
            <tr> 
                <td colspan="4"  height="1"><img src="<?php echo base_url()?>assets/images/logo1.png" class="img"></td>
            </tr>
            <tr> 
                <td colspan="4"  height="1" align="center">Master Report ­ Exam Registration Details</td>
            </tr>
            
            <tr colspan="4">
                <table class="table" style="width:90%;margin-left: 35px;">
                    <thead>
                        <tr>
                          <th id="">S.No.</th>
                          <th id="">Exam Code</th>
                          <th id="">Exam Name</th>
                          <th id="">Member Count</th>
                        </tr>
                    </thead>
                    <tbody class="no-bd-y" id="print_list">
						<?php if(count($result)){
                            $i = 1;
                            foreach($result as $row){  
                      ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['exam_code'];?></td>
                            <td><?php echo $row['description'];?></td>
                            <td><?php echo $row['mem_cnt'];?></td>
                        </tr>
                      <?php 
                            $i++;	
                            }}else{ ?>                  
                        <tr>
                            <td colspan="4" align="center">No records found...</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </tr>
        </table>
    </div>
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
	
	<?php if(isset($_POST['exam_code']) && $_POST['exam_code']!='')
	{ ?>
		GetExamPeriod('<?php echo $_POST['exam_code'];?>');
		
		
	<?php }
	
	//GET CODE AND PERIOD and CALL GETDATE Pooja Mane:06-12-2022
  if(isset($_POST['exam_code']) && $_POST['exam_code']!=''&& isset($_POST['exam_period']) && $_POST['exam_period']!='')
	{ ?>
		GetExamDate('<?php echo $_POST['exam_code']; echo $_POST['exam_period'];?>');
		
		
	<?php } ?>
	//GET CODE AND PERIOD and CALL GETDATE end  Pooja Mane:06-12-2022
	
	
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

//Get dates Pooja Mane 05-12-2022
function GetExamDate(exm_prd)
{
	var site_url = '<?php echo base_url(); ?>';
  var exm_cd = $("#exam_code").val();
  var exm_prd = $("#exam_period").val();
	
	if(exm_cd && exm_prd)
	{
		$.ajax({
      type:'POST',
			url:site_url+'admin/Report/GetExamDate',
      data: { exm_cd : $("#exam_code").val(), exm_prd: $("#exam_period").val()  },
			success: function(data) {
				
				if(data != '')
				{
					$("#exam_date").html(data);	
				}
			}
		});
	}
}
// get dates Pooja Mane 05-12-2022

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