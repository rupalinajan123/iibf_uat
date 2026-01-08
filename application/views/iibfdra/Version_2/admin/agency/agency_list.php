<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
<style>
.modal-dialog {
  width: 500px;
  margin: 30px auto;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Agency List
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
    <br />    
    <?php
    if ($this->session->flashdata('success') != '')
    { ?>
      <div class="alert alert-success alert-dismissible" id="success_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php }
    else if ($_SESSION['custom_success'] != '')
    { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $_SESSION['custom_success']; ?>
      </div>
    <?php }

    if ($this->session->flashdata('error') != '')
    { ?>
      <div class="alert alert-danger alert-dismissible" id="error_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php } ?>    

    <?php if (is_array($validation_errors) && count($validation_errors) > 0) {
                foreach ($validation_errors as $key => $value) { ?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $value; ?>
                  </div>

              <?php }
            } ?>

    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">          	
            <div class="box-body">
            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
			<table id="listitems2" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">S.No.</th>
                  <th id="id">Agency Code</th>     
                  <th id="inst_name">Agency Name</th>                     
                  <th id="regular_center">Regular Accreditation</th> 
                  <th id="temp_center">Temporary Accreditation</th>
                  <th id="created_on">Registration Date</th>
                  <th id="inst_head_name">Contact Person</th>
                  <th id="contact_person_mobile">Contact Mobile</th> 
                  <th> Agency status </th>                              
                  <th id="action">Operations</th>
                </tr>
                </thead>
                  
                <tbody class="no-bd-y" id="list2">
                <?php 
				$k = 1;
				if(count($agency_list) > 0){
					foreach($agency_list as $res)
          {

            $arr_batch_limit = [];

            $arr_batch_limit['from_date']             = $res['from_date'];
            $arr_batch_limit['to_date']               = $res['to_date'];
            $arr_batch_limit['batch_creation_limit']  = $res['batch_creation_limit'];
            $arr_batch_limit['batch_type']            = $res['batch_type'];

            $arr_json_data =  json_encode($arr_batch_limit);

  					echo '<tr><td>'.$k.' </td>';
  					echo '<td>'.$res['institute_code'].' </td>';
  					echo '<td>'.$res['inst_name'].' </td>';
  					echo '<td>'.$res['regular_center'].' </td>';
  					echo '<td>'.$res['temp_center'].' </td>';					
  					echo '<td>'.$res['created_on'].' </td>';
  					echo '<td>'.$res['inst_head_name'].' </td>';	
  					echo '<td>'.$res['inst_phone'].' </td>';
  					
  					if($res['status'] == 1){
  					$agency_status = '<span class="active_batch">ACTIVE </span>' ;
  					}else{
  					$agency_status = '<span class="deactive_batch">DEACTIVE </span>';	
  					}
  					echo '<td>'.$agency_status.' </td>';
  					echo '<td>
            <a class="btn btn-success btn-xs vbtn" href="javascript:void(0)" onclick="modal_show('.$res['id'].', \''.addslashes($res['inst_name']).'\', '.htmlspecialchars($arr_json_data, ENT_QUOTES, 'UTF-8').')">Batch Limit</a><br><br> 
            <a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/Version_2/agency/agency_detail/'.$res['id'].'">View</a>';
  					// Renew button HIDE as per disucss with Sonal on 2 APR 2019 : by Manoj
  					echo '|<a class="btn btn-primary btn-xs vbtn" href="'.base_url().'iibfdra/Version_2/agency/agency_renew/'.$res['id'].'">Renew</a>';
  					
  					echo '</td></tr>';
  					$k++;	
  					}
  				}
  				?>                 
                </tbody>
              
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers">
             
              </div>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
    </section>

  <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modal_hide()"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Candidate Hold</h4>
      </div>
      <form method="POST" action="<?php echo base_url("iibfdra/Version_2/Agency/change_batch_limit"); ?>">  
        <div class="modal-body">
          <input type="hidden" name="agency_id" id="agency_id" value="">

          <div class="form-group row">
            <div class="col-md-12">
              <label class="col-sm-6">From Date</label>
              <div class="col-md-6">
                <input type="date" class="form-control" name="from_date" id="from_date" value="" required>
              </div> 
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-12">     
              <label class="col-sm-6">To Date</label>
              <div class="col-md-6">
                <input type="date" class="form-control" name="to_date" id="to_date" value="" required>    
              </div>
            </div>  
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <label class="col-sm-6">Batch Creation Limit</label>
              <div class="col-md-6">
                <input type="number" class="form-control" name="batch_creation_limit" id="batch_creation_limit" value="0" required>
              </div>
            </div>  
          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <label class="col-sm-6">Permitted Batch Type</label>
              <div class="col-md-6">
                <select class="form-control" name="batch_type" id="batch_type">
                  <option value="">Select Batch Type</option>
                  <option value="50">50 Hours</option>
                  <option value="100">100 Hours</option>
                </select>
              </div>
            </div>  
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="modal_hide()">Close</button>
          <button type="submit" name="agency_submit" class="btn btn-primary" value="true">Save changes</button>
          <a id="clear_btn" href="javascript::void(0)" class="btn btn-primary" onclick="if(confirm('Are you sure you want to clear the data?')) { clear_data(); }">Clear</a>

        </div>
      </form>  
    </div>
  </div>
</div>  


  </div>
  
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
.active_batch{
color:#00a65a;	
font-weight:600;
}

.deactive_batch{
color:#930;	
font-weight:600;
}
.input_search_data{
 width:100%;	
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>

$('#to_date').on('change', function() {
  var fromDate = $('#from_date').val();
  var toDate = $(this).val();

  if (fromDate && toDate) {
    if (new Date(toDate) <= new Date(fromDate)) {
      alert('To Date must be greater than From Date');
      $(this).val(''); // Clear the to_date field if invalid
    }
  }
});

$('#from_date').on('change', function() {
  $('#to_date').val('');
});

function modal_show(agency_id,institute_name,arr_json_data) 
{ 
  var clear_batch_limit_url = '<?php echo base_url().'iibfdra/Version_2/agency/clear_data/'; ?>'+agency_id;

  $('#clear_btn').attr('href',clear_batch_limit_url);

  $('#from_date').val(arr_json_data.from_date); 
  $('#to_date').val(arr_json_data.to_date);
  if (arr_json_data.batch_creation_limit != null && arr_json_data.batch_creation_limit != '') {
    $('#batch_creation_limit').val(arr_json_data.batch_creation_limit);  
  } else {
    $('#batch_creation_limit').val(0);
  }
  
  $('#batch_type').val(arr_json_data.batch_type);

  $('#agency_id').val(agency_id); 
  $('.modal-title').text(institute_name);  
  $('#myModal').css('display','block');
  $('.modal').removeClass('fade');
}

function modal_hide() {
  $('#candidate_id').val('');  
  $('#myModal').css('display','none');
  $('.modal').addClass('fade');
}

$(function () {
	$('#listitems2').DataTable();
	$("#listitems_filter").show();
});
</script>
 
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>