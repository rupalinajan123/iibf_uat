<?php $this->load->view('careers_admin/admin/includes/header');?>
<?php $this->load->view('careers_admin/admin/includes/sidebar');?>

<head>
  <style>
.dropbtn {
  background-color: #66d9ff;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
  float:right; 
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #66d9ff;
}
</style>
</head>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Summary Report
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
    <br />
   
    <div>
       <a href='<?= base_url() ?>careers_admin/admin/Careers_position/pdf'><large><button class="dropbtn btn bg-info margin">Export PDF</button></large></a>
      </div> 
    <div>
       <a href='<?= base_url() ?>careers_admin/admin/Careers_position/exportData/<?php if(isset($_GET["position"])){ echo $_GET["position"];} ?>' ><large><button class="dropbtn btn bg-info margin">Export CSV</button></large></a>
      </div>

       
    <?php 
	 if($this->session->flashdata('success')!='')
    { ?>
    <div class="alert alert-success alert-dismissible" id="success_id">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('success'); ?> 
    </div>
    <?php }?>    

    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">          	
            <div class="box-body">
              <form method="get" action="<?php echo base_url(); ?>careers_admin/admin/Careers_position/career_position_list">
        <div class="dropdown">
          <select name="position" id="position" class="">
                    <option value="">Select Position</option>
                    <?php
                     // print_r($career_position);
                      if(count($career_position) > 0)
                      {
                        foreach($career_position as $row)
                        { ?>
                         
                          <option value="<?php echo $row['id']; ?>"><?php echo $row['position'];?></option>   
                         
                        <?php }
                      }
                    ?>
                  </select>
          <button type="submit" id="submit" name="submit" class="">Submit</button>
        </div>  
      </form> 
            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	 <input type="hidden" name="base_url_val" id="base_url_val" value="" />
           
            <div class="table-responsive">

			<table id="listitems2" class="table table-bordered table-striped dataTables-example" width="400">
                <thead>
                <tr>
                  <th id="srNo" style="text-align: center">Sr.No.</th>
                  <th id="can_name" style="text-align: center">Candidate Name</th>
                  <th id="email" style="text-align: center">Email</th> 
                  <th id="contact" style="text-align: center">Contact</th> 
                  <th id="position" style="text-align: center">Career Position</th> 
                  <th id="photo" style="text-align: center">Photo</th>   
                  <th id="signature" style="text-align: center">Signature</th>
                  <th id="date" style="text-align: center">Application Date</th>
                  <th id="pdf" style="text-align: center">Pdf</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list2">
                <?php 
				$k = 1;
				if(count($reuest_list) > 0)
        {
					foreach($reuest_list as $res)
          {
  					echo '<tr><td>'.$k.' </td>';
            echo '<td>'.$res['firstname']." ".$res['middlename']." ".$res['lastname'].' </td>';
  					echo '<td>'.$res['email'].' </td>';
  					echo '<td>'.$res['mobile']." , ".$res['alternate_mobile'].' </td>';
            echo '<td>'.$res['position'].' </td>';
            echo '<td><img width="70px" height="70px" src="'.base_url().'uploads/photograph/'.$res['scannedphoto'].'"></img></td>';
            echo '<td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$res['scannedsignaturephoto'].'"></img></td>';
            echo '<td>'.$res['submit_date'].' </td>';
            echo '<td><a href="'.base_url().'careers_admin/admin/Career_admin/pdf/'.$res['careers_id'].'"class="btn btn" id= "pdf">
              <span  class="glyphicon glyphicon-file"></span>Pdf</a><br><br></td>';
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
 
<?php $this->load->view('careers_admin/admin/includes/footer');?>