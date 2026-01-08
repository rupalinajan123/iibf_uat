  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Exam Application Details
      </h1>
    </section>
	<form class="form-horizontal" name="roleAddForm" id="roleAddForm" action="" method="post">
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Exam List</h3>
              <div class="pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
            
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
              
                			<tr>
                  <th id="srNo">S.No</th>
                  <th id="exam_code">Exam Name</th>
              <!--    <th id="description">Exam Period</th>-->
                  <th id="qualifying_exam1">Medium</th>
                  <th id="qualifying_part1">Mode</th>
                  <!--<th id="qualifying_exam2">Center</th>-->
                  <th id="qualifying_part2">Exam Fee</th>
                  <th id="qualifying_exam3">Date</th>
                </tr>
                
                </thead>
                <tbody class="no-bd-y" id="list">
               	  <?php 
				if(count($exam_history) > 0)
				{$i=1;
					foreach($exam_history as $examrow)
					{?>
              			<tr>
                        	<td><?php echo $i;?></td>
                        		<td><?php echo $examrow['description'];?></td>
                        			<!--<td><?php 
										/*$Year = substr($examrow['exam_month'], 0, -2);  // returns "abcde"
										$month = substr($examrow['exam_month'], 4);  // returns "cde"
										 $date=$Year.'-'.$month;
										echo date('M Y',strtotime($date));*/
										?>
                                        
                                        </td>-->
                        				<td>
                                        <?php
											if($examrow['exam_medium'] == 'E'){
												echo "English";		
											}elseif($examrow['exam_medium'] == 'H'){
												echo "HINDI";		
											}elseif($examrow['exam_medium'] == 'T'){
												echo "TAMIL";		
											}elseif($examrow['exam_medium'] == 'S'){
												echo "ASSAMESE";		
											}elseif($examrow['exam_medium'] == 'O'){
												echo "ORIYA";		
											}elseif($examrow['exam_medium'] == 'N'){
												echo "BENGALI";		
											}elseif($examrow['exam_medium'] == 'M'){
												echo "MARATHI";		
											}elseif($examrow['exam_medium'] == 'L'){
												echo "TELEGU";		
											}elseif($examrow['exam_medium'] == 'K'){
												echo "KANADDA";		
											}elseif($examrow['exam_medium'] == 'G'){	
												echo "GUJRATHI";		
											}elseif($examrow['exam_medium'] == 'A'){
												echo "MALAYALAM";		
											}
											
										?>
                                        </td>
                        					<td><?php if($examrow['exam_mode']=='ON'){echo 'Online';}else if($examrow['exam_mode']=='OF'){echo 'Offline';};?></td>
                        						<?php /*?><td><?php echo $examrow['center_name'];?></td><?php */?>
                        							<td><?php echo $examrow['exam_fee'];?></td>
                       									 <td><?php echo date('d-M-Y',strtotime($examrow['created_on']));?></td>
            	   		</tr>                     
                <?php 
					$i++;}
				}?>
                </tbody>
              </table>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>

    </section>
    </form>
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
<script>
$(function () {
	$("#listitems").DataTable();
	$('.pull-right').html('');
});
		
</script>
