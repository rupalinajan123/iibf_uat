<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard - Exam Registration List
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
                <form class="form-horizontal" name="search_form" id="search_form" action="<?php echo base_url();?>admin/Report/getExamDashboardList" method="post">
                    <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                    <input type="hidden" name="form_action" id="form_action" value="">

                    <div class="form-group">
                    	<label for="from_date" class="col-sm-1">Exam Code</label>
                        <div class="col-md-2">
                        	<select class="form-control search_opt" id="exam_code" name="exam_code"  onchange="return GetExamPeriod(this.value);">
                                <option value="">Select</option>
                                <?php if(count($exam_code)){
                                        foreach($exam_code as $row){ 	?>
                                <option value="<?php echo $row['exam_code'];?>" <?php if(isset($_POST['exam_code']) && $_POST['exam_code']==$row['exam_code']){ echo "selected='selected'"; }?>><?php echo $row['exam_code'];?></option>
                                <?php } } ?>
                          </select>
                        </div>
                        
                        <label for="from_date" class="col-sm-1">Exam Period</label>
                        <div class="col-md-2">
                        	<select class="form-control search_opt" id="exam_period" name="exam_period" required onchange="return GetExamDate(this.value);" >
                                <option value="">Select</option>
                                <?php if(count($exam_period)){
                                        foreach($exam_period as $row1){ 	?>
                                <option value="<?php echo $row1['exam_period'];?>" <?php if(isset($_POST['exam_period']) && $_POST['exam_period']==$row1['exam_period']){ echo "selected='selected'"; }?>><?php echo $row1['exam_period'];?></option>
                                <?php } } ?>
                            </select>
                        </div> 
                        
                        <div class="col-md-2">  
                           <select class="form-control search_opt" id="search_for" name="search_for" required>
                                <option value="">Select</option>
                                <option value="NM" <?php if(isset($_POST['search_for']) && $_POST['search_for']=="NM"){ echo "selected='selected'"; } ?>>Non Member</option>
                                <option value="O" <?php if(isset($_POST['search_for']) && $_POST['search_for']=="O"){ echo "selected='selected'"; } ?>>Ordinary Member</option>
                                <option value="DB" <?php if(isset($_POST['search_for']) && $_POST['search_for']=="DB"){ echo "selected='selected'"; } ?>>DBF Member</option>
                                <option value="All" <?php if(isset($_POST['search_for']) && $_POST['search_for']=="All"){ echo "selected='selected'"; } ?>>All</option>
                              </select>
                         </div>
                         
                         <label for="exam_date" class="col-sm-1">Exam Date</label>
                        <div class="col-md-2">
                        	<select class="form-control search_opt" id="exam_date" name="exam_date" >
                                <option value="">Select</option>
                                <?php if(count($exam_date)){ //print_r($exam_date);
                                        foreach($exam_date as $row2){ 	?>
                                <option value="<?php echo $row2['exam_date'];?>" <?php if(isset($_POST['exam_date']) && $_POST['exam_date']==$row1['exam_date']){ echo "selected='selected'"; }?>><?php echo $row2['exam_date'];?></option>
                                <?php } } ?>
                            </select>
                        </div>
                         
                         <!-- <div class="col-md-1">  
                          	<input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="">
                         </div> -->  
                           
      
                   </div>
                   <div class="form-group text-center" style="width:auto;">
                          <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                          <button type="button" class="btn btn-success" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                          <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
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
                  <th id="regnumber">Registration No</th>
                  <th id="firstname">First Name</th>
                  <th id="gender">Gender</th>
                  <th id="description">Exam Name</th>
                  <th id="exam_fee">Exam Fee</th>
                  <th id="medium_description">Exam Medium </th>
                  <th id="center_name">Center Name</th>
                  <th id="transaction_no">Transaction<br />No</th>
                  <th id="transaction_details">Payment Status</th>
                  <th id="date">Transaction Date</th>
                  <th id="exm_date">Exam Date</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list"></tbody>
              </table> 
      
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
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  $('#search_form').parsley('validate');
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
    /*$(document).ready(function () {
        var base_url = '<?php //echo base_url(); ?>';
        var listing_url = base_url+'admin/Report/getExamDashboardList';

        $("#listitems").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": listing_url,
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "email" }
            ]
        });
    });*/
</script>
 
<script language="javascript">
  /*$('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true });*/

        $(document).ready(function()
        { 
            var table = $('.dataTables-example').DataTable(
            {
                searching: true,
                "processing": false,
                "serverSide": true,
                "ajax": 
                {
                    "url": '<?php echo site_url("admin/Report/getExamDashboardList"); ?>',
                    "type": "POST", 
                    "data": function ( d ) 
                    {
                        d.form_action = $("#form_action").val();
                        d.exam_code = $("#exam_code").val();
                        d.exam_period = $("#exam_period").val();
                        d.search_for = $("#search_for").val();
                        d.exam_date = $("#exam_date").val(); 
                    },
                    beforeSend: function() { $("#page_loader").show(); },
                    complete: function() { $("#page_loader").hide(); },
                },
                "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
                "language": 
                {
                    "lengthMenu": "_MENU_",
                },
                //"dom": '<"top"lf><"clear"><i>rt<"bottom row"<"col-sm-12 col-md-5" and i><"col-sm-12 col-md-7" and p>><"clear">',
                pageLength: 10,
                responsive: true,
                rowReorder: false,
                "columnDefs": 
                [
                    {"targets": 'no-sort', "orderable": false, },
                    {"targets": [0], "className": "text-center"},  
                    {"targets": [3], "className": "text-center"},
                    {"targets": [4], "className": "text-center"},
                    {"targets": [5], "className": "text-center"}, 
                ],
                "aaSorting": [],
                "stateSave": false,                         
            });
        });  

        function clear_search() 
        { 
            $("#form_action").val("");
            $('.s_datepicker').val("").datepicker("update");
            $(".search_opt").val(''); 
            $('.dataTables-example').DataTable().draw(); 
        }
      
        function apply_search() 
        {
            $("#form_action").val(""); 
            $('.dataTables-example').DataTable().draw(); 
        } 

        function apply_filter_with_export_to_excel(export_type = 'export') 
        { 
            $("#tbl_search_value").val($('input[type="search"]').val());
            $("#form_action").val(export_type);
            $("#page_loader").show();
            $("#search_form").submit();
            setTimeout(function()
            {
              apply_search();
            },1000); 
        } 
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