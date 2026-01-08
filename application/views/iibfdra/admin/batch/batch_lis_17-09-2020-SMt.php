<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Training Batch List
      </h1>
      <?php echo $breadcrumb; ?>
    </section>	
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
                  <th>sr No</th>
                   		<th>Agency Name</th>
                        <th>Center location</th>
                        <th>Batch Code</th>                       
                        <th>Batch Name</th>
                        <th>From Date - To date</th>
                        <th>Training Timing</th>  
                        <th>Batch create date</th> 
                        <th>Batch Status</th>                              
                        <th>Operations</th>
                </tr>
                </thead>
                  <tfoot>
                    <tr>         
                        <th>sr No</th>
                        <th>Agency Name</th>
                        <th>Center location</th>
                        <th>Batch Code</th>                      
                        <th>Batch name</th>
                        <th>From Date - To date</th>
                        <th>Training Timing</th>
                        <th>Batch create date</th>
                         <th>Batch Status</th>                                   
                        <th>Operations</th>
                    </tr>
       			</tfoot>
                <tbody class="no-bd-y" id="list2">
                <?php 
				$k = 1;
				if(count($batch_list) > 0){
					foreach($batch_list as $res){
					echo '<tr><td>'.$k.' </td>';
					echo '<td>'.$res['inst_name'].' </td>'; ?>
                    <?php if( $res['city_name'] !='' ){
						$city_name = $res['city_name'];
						}else{
						$city_name = $res['location_name'];	
						}
					echo '<td>'.$city_name.' </td>';						
					echo '<td>'.$res['batch_code'].' </td>';	?>					
					<td><?php echo $res['batch_name']; if($res['batch_online_offline_flag'] == 1) { echo " (Online)"; } ?></td>
					
					<?php
					echo '<td>';
					
					 // echo $result['center_validity_to'];
					  if( $res['batch_from_date'] != ''  && $res['batch_to_date'] != '')	{?>
						   FROM <strong><?php echo date_format(date_create($res['batch_from_date']),"d-M-Y"); ?> </strong> TO <strong><?php echo date_format(date_create($res['batch_to_date']),"d-M-Y"); ?></strong> 
					 <?php  }else{ ?>
                     <strong>-batch date not added-</strong>						
						<?php   }
					  				
					echo '</td>';
					
					echo '<td>';					
					
					  if( $res['timing_from'] != ''  && $res['timing_to'] != '')	{?>
						   FROM <strong><?php echo $res['timing_from']; ?> </strong> TO <strong><?php echo $res['timing_to']; ?></strong> 
					 <?php  }else{ ?>
                     <strong>-Batch Timing not added-</strong>						
						<?php   }				
					echo '</td>';
					//echo '<td>'.$res['total_candidates'].' </td>';	
					echo '<td>'.$res['created_on'].' </td>'; ?>
                    
                    <?php //A,R,C,IR					
					 if($res['batch_status'] == 'A' ){ 
					   		$status_text =  'Approved'; 									
							$div_class = '#d4edda';
							$div_class2 = '#270';
					   }elseif($res['batch_status'] == 'IR' ){ 
					   		$status_text =  'In Review'; 							
							$div_class = '#f8d7da';
							$div_class2 = '#9F6000';
					   }elseif($res['batch_status'] == 'C' ){ 
					   		$status_text =  'Cancelled'; 
							$div_class = '#f8d7da';
							$div_class2 = '#D8000C';
					   }else { 
					   		$status_text =  'Rejected'; 
							$div_class = '#f8d7da';
							$div_class2 = '#D8000C';
					   }
					echo '<td><span style="font-weight:800;color:'.$div_class2.'">'.$status_text.'</td>';
					echo '<td><a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/batch/batch_detail/'.$res['id'].'">View</a </td></tr>';
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
.input_search_data{
 width:100%;	
}

tfoot {
    display: table-header-group;
}

.pp0 , .pp5, .pp6 , .pp7, .pp8 , .pp9 {
 display:none;	
}

.vbtn{
padding: 3px 21px;
font-weight: 900;
}
.#listitems2{
width:100%;
max-width:100%;	
}
.moption{
width:100%;	
}
</style>
</style>

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
  //$('#searchDate').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
	//$('#listitems2').DataTable();
	$("#listitems2_filter").show();
	
	 // DataTable
   
 
 /*setTimeout(function(){  
 
  var table = $('#listitems2').DataTable(
	 "columnDefs": [
      { "width": "7%", "targets": 0 },
      { "width": "25%", "targets": 1 },
      { "width": "13%", "targets": 2 },
	  { "width": "15%", "targets": 3 },
      { "width": "10%", "targets": 4 },
	  { "width": "15%", "targets": 5 } 
	  { "width": "10%", "targets": 6 }
	  { "width": "10%", "targets": 7 }     
    ]);
        
	}, 3000);*/
	
	var table = $('#listitems2').DataTable();
	 	$("#listitems2 tfoot th").each( function ( i ) {
        var select = $('<select  class="moption pp'+i+'" ><option value="">All</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();
            } );
 
        table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        });
    });

		
});
</script> 
<?php $this->load->view('iibfdra/admin/includes/footer');?>