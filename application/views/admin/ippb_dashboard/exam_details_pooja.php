<?php $this->load->view('admin/ippb_dashboard/includes/header');?>
<?php $this->load->view('admin/ippb_dashboard/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Registration Details
        <!-- <a href="https://iibf.esdsconnect.com/uploads/admitcardpdf/997_851_802042625.pdf" target="_blank" > TEXT </a> -->
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
    <br />
  <div class="col-md-12">
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
    </div>

    <!-- Search function -->
     <div class="searchfilter">
          <div class="box-header">
            <div class="box-header with-border">
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button type="button" class="mb-2 float-right btn btn-primary" data-toggle="collapse" data-target="#collapseExample">
                     <i class="fa fa-filter"></i></button>
                </div>
                <!-- /. tools -->
                <h3 class="page-title">Search Filter</h3>
            </div>

            

            <div class="collapsee" id="collapseExample">
  
              <form class="form-control" name="searchExamDetails" id="searchExamDetails" action="<?php echo base_url('admin/ippb/IppbDashboard/csv'); ?>" method="post">
                <div class="row">
                  <div class="col-sm-2">
                      <div class="form-group">
                          <label>Search By</label>
                          <select class="custom_filter form-control" name="searchBy" id="searchBy" required>
                              <option value="">Select</option>
                                <option value="01" selected>Exam Code</option>
                                <!--<option value="02">Exam Name</option> -->
                                <option value="03">Center Code</option>
                                <option value="04">Registration No</option>
                                <option value="05">Transaction No</option>
                                <option value="06">Employee/Gent ID</option>
                                <option value="07">ALL</option>
                            </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Search Value</label>
                        <input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" required value="997" >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>From Date</label>
                        <input type="text" class="form-control custom_filter" name="from_date" id="from_date" value="">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>To Date</label>
                        <input type="text" class="form-control custom_filter" name="to_date" id="to_date" value="">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Search Details</label>
                        <input type="button" class="mb-2 float-right btn btn-primary" name="btnSearch" id="btnSearch" value="Search Details" onclick="return searchOnFields();">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Download Data</label>
                        <button type="submit" name="submit" id="download" value="Download" class="mb-2 btn btn-warning" href=" ">Download CSV</button>
                    </div>
                  </div>

                </div>     
               </form>
              </div>
              <!-- <center>
                <div id="loading" class="divLoading" style="display: none;">
                  <p>Loading... <img src="<?php echo base_url(); ?>assets/images/loading-4.gif" width="100" height="100" /></p>
                </div>
            </center>  -->
            </div>
          </div>
    <!-- Search function -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
              
              <div class="pull-left" style="700px;">
                <div class="col-sm-12">
                  
                </div>
              </div>
             
              <div class="pull-right">
                <!-- <a href="javascript:void(0);" class="btn btn-warning" onclick="javascript:printDiv();" id="printBtn" style="display:none;">Print</a> -->
                <!--<a href="<?php echo base_url();?>admin/Report/examReg" class="btn btn-info" id="" >Refresh</a>-->
                <!--<input type="button" class="btn btn-info" value="Refresh">-->
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">       
              <table id="listitems" class="table table-bordered table-striped dataTables-example" role="grid">
                        <thead>
                        <tr>
                          <!-- <th id="srNo">S.No.</th> -->
                          <th id="mem_mem_no">Registration No</th>
                          <th id="emp_id">Employee Id</th>
                          <th id="firstname">Full Name</th>
                          <!-- <th id="gender">Gender</th> -->
                          <!-- <th id="description">Exam Name</th> -->
                          <th id="exam_fee">Exam Fee</th>
                          <!-- <th id="medium_description">Exam Medium </th> -->
                          <th id="center_name">Center Name</th>
                          <th id="exam_date">Exam Date</th>
                          <th id="transaction_no">Transaction<br />No</th>
                          <th id="transaction_details">Payment Status</th>
                          <th id="date">Transaction Time</th>
                        </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
                                            
                        </tbody>
              </table>
              </div>  
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
          </div>
        </div>
      </div>
      
    </section>
   
  </div>
  
<!-- Print Content -->

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
                <table class="table" style="width:100%;">
                    <thead>
                        <tr>
                            <!-- <th id="srNo">S.No.</th> -->
                            <th id="regnumber">Registration No</th>
                            <th id="emp_id">Employee Id</th>
                            <th id="firstname">Full Name</th>
                            <!-- <th id="gender">Gender</th> -->
                            <th id="description">Exam Name</th>
                            <th id="exam_fee">Exam Fee</th>
                            <th id="exam_medium">Exam Medium </th>
                            <th id="center_name">Center Name</th>
                            <th id="exam_date">Exam Date</th>
                            <th id="transaction_no">Bill Desk <br />Tran.No</th>
                            <th id="transaction_details">Payment Status</th>
                            <th id="date">Transaction Time</th>
                        </tr>
                    </thead>
                    <tbody class="no-bd-y" id="print_list">
                    
                    </tbody>
                </table>
            </tr>
        </table>
    </div>
</div>
<!-- Print Content End -->
  
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
<script type="text/javascript">
  //$('#searchExamDetails').parsley('validate');
  //$('#searchReg').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">
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
    
  }) */
  
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
  
});

function searchOnFields()
{
  
  var flag=$('#searchExamDetails').parsley().validate();
  //alert(flag);
  if(flag)
  {
    var perPage = $('#perPage').val();
    var searchkey = $("#SearchVal").val().trim();
    var searchBy = $("#searchBy").val().trim(); 
    var from_date= $('#from_date').val().trim();
    var to_date= $('#to_date').val().trim();
    var searcharr = [];
    searcharr['field'] = searchBy;
    searcharr['value'] = searchkey;
    //alert('searcharr:'+searcharr);
    searcharr['from_date'] = from_date;
    searcharr['to_date'] = to_date;
    paginate('',searcharr,perPage,);
    
    
    printContent(searchBy,searchkey);
  }
}


function printContent(searchBy,searchkey,from_date,to_date)
{
  var base_url = '<?php echo base_url(); ?>';
  $.ajax({
    url: base_url+'admin/ippb/IppbDashboard/getExamDetailsToPrint',
    type: 'POST',
    dataType:"json",
    data: {field : searchBy, value : searchkey, from_date : from_date, to_date : to_date },
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
            content += '<tr><td>'+res.result[i].regnumber+'</td><td>'+res.result[i].emp_id+'</td><td>'+res.result[i].firstname+''+res.result[i].lastname+'</td><td>'+res.result[i].description+'</td><td>'+res.result[i].exam_fee+'</td><td>'+res.result[i].medium_description+'</td><td>'+res.result[i].center_name+'</td><td>'+res.result[i].transaction_no+'</td><td>'+res.result[i].transaction_details+'</td><td>'+res.result[i].date+'</td></tr>';
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
}


$(function () {
  $("#listitems").DataTable();
  var base_url = '<?php echo base_url(); ?>';
  var listing_url = base_url+'admin/ippb/IppbDashboard/getExamReport';
  
  // Pagination function call
  //paginate(listing_url,'','','');
  $("#base_url_val").val(listing_url);
  //$("#base_url_val").val(base_url+'admin/Report/examReg');
  
  $(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf ").hide();
  
  //printContent('','');
  
  //$("#listitems_filter").hide();
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

<?php $this->load->view('admin/ippb_dashboard/includes/footer');?>