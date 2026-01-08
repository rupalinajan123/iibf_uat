<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

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
	 if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
     <?php }?>    
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
					foreach($agency_list as $res){
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
					echo '<td><a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/agency/agency_detail/'.$res['id'].'">View</a>';
					// Renew button HIDE as per disucss with Sonal on 2 APR 2019 : by Manoj
					echo '|<a class="btn btn-primary btn-xs vbtn" href="'.base_url().'iibfdra/agency/agency_renew/'.$res['id'].'">Renew</a>';
					
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
$(function () {
	$('#listitems2').DataTable();
	$("#listitems_filter").show();
});
</script>
 
<?php $this->load->view('iibfdra/admin/includes/footer');?>