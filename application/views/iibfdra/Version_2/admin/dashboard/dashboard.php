<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Dashboard </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <form class="form-horizontal" name="searchmemregform" id="searchmemregform" action="" method="post">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Search</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
             
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } ?> 
             
                <div class="form-group">
                	<label for="roleid" class="col-sm-2 control-label">Exam Period <span class="red"> *</span></label>
                	<div class="col-sm-3">
                      <select class="form-control" id="exam_period" name="exam_period" required>
                      	<option value="">Select</option>
                        <?php if(count($exam_period_list)){
                                foreach($exam_period_list as $row){ ?>
                        <option value="<?php echo $row['exam_period']; ?>" <?php //if($searchData['exam_period'] == $row['exam_period']){echo "selected='selected'"; } ?>><?php echo $row['exam_period']; ?></option>
                        <?php } } ?>
                      </select>
                      <span class="error" id="exam_period_error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Institute Name</label>
                    <div class="col-sm-3">
                      	<select class="form-control" id="inst_code" name="inst_code" required>
                        	<option value="">Select</option>
                        	<?php if(count($institute_list)){
                                foreach($institute_list as $row){ 	?>
                        	<option value="<?php echo $row['institute_code']; ?>" <?php //if($searchData['institute_code'] == $row['institute_code']){echo "selected='selected'"; } ?>><?php echo $row['institute_name']; ?></option>
                        	<?php } } ?>
                     	</select>
                       	<span class="error"><?php echo form_error('institute_code');?></span>
                    </div>
                </div>
              </div>
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <input type="button" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="return getSearchResult();">
                  </div>
              </div>
           </div>
        </div>
      </div>
    </form>
    
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Exam Registration Details</h3>
              <div class="pull-right">
                <!--<input type="button" class="btn btn-info" onclick="" value="Print Report">-->
                <a href="javascript:void(0)"><input type="button" class="btn btn-info" value="Print" onclick="printData();"></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">S.No.</th>
                  <th id="DRA">Type</th>
                  <th id="exam_code">Exam Code</th>
                  <th id="description">Exam Name</th>
                  <th id="member_count">Member Count</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                                    
                </tbody>
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers">
               
              </div>
              
              <h4 class="text-center" style="display:none" id="total_mem_count">Total : 0</h4>
              
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
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  $('#searchmemregform').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>

$(function () {
	$("#listitems").DataTable();
	
	$("#listitems_filter").hide();
});

// function to get search result -
function getSearchResult()
{
	$("#exam_period_error").text("");
	
	$('#total_mem_count').hide();
	
	var perPage = $('#perPage').val();
	var exam_period = $("#exam_period").val();
	var inst_code = $("#inst_code").val();
	var searcharr = [];
	
	if(exam_period == "")
	{
		$("#exam_period_error").text("Please select Exam period to fetch data.");
		return false;
	}
	
	searcharr['field'] = 'exam_period';
	searcharr['value'] = exam_period+'~'+inst_code;
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'iibfdra/Version_2/admin/dashboard/getSearchResult';
	
	// Pagination function call
	paginate(listing_url,searcharr,perPage);
	$("#base_url_val").val(listing_url);
	
	/*var sum = 0;
    // iteration through all td's in the column
    $('#listitems > tbody > tr > td:last').each( function(){
		alert($(this).text());
		//sum += parseInt($(this).text()) || 0;
    });
	alert(sum);*/
	
	//alert($('#listitems').html());
}

// function ti print result table -
function printData()
{
	/*var divToPrint=document.getElementById("listitems");
	newWin= window.open("");
	newWin.document.write(divToPrint.outerHTML);
	newWin.print();
	newWin.close();*/
   
	var divToPrint = document.getElementById('listitems');
	var htmlToPrint = '' +
		'<style type="text/css">' +
		'table th, table td {' +
		'border:1px solid #000;' +
		'padding;1.0em;' +
		'}' +
		'</style>';
	htmlToPrint += divToPrint.outerHTML;
	newWin = window.open("");
	newWin.document.write(htmlToPrint);
	newWin.print();
	newWin.close();
}
</script>

<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>