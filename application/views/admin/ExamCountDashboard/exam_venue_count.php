<?php $this->load->view('admin/ExamCountDashboard/header');?>
<?php $this->load->view('admin/ExamCountDashboard/sidebar');?>
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 30%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Examination Counts
      </h1>
     <?php //echo $breadcrumb; ?>
    </section>
       <section class="content">
	<form class="form-horizontal" name="addForm" id="addForm" action="<?php echo base_url();?>admin/ExamCountDashboard/ExamCount/dowanload" method="post"> 
    <!-- Main content -->
 
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php // echo $title; ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php //echo validation_errors(); ?>
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
                
                <div class="row">
  <div class="column" >
     <div class="control-group" id="div_authority_CC">
            <label for="textfield" class="control-label"><strong>Exam period</strong></label>
                <!-- Multiple Board Dropdown-->
                <div class="controls" id="board_dd_CC">
                  <select class="input-large form-control" id="authority" name="exm_prd[]" multiple required size="10" width="5">
                    <option value=""> <?php echo '-Select-'; ?> </option>
					<?php 
					$C_date =date('Y-m-d');
					$date =  date('Y-m-d', strtotime($C_date .' -15 day'));
					$exam_activation = $this->Master_model->getRecords("exam_activation_master", array(
					'exam_to_date >=' => $date,
					));
					$spexam=$nospexam=array();
					if(!empty($exam_activation))
					{
					
					$i=1;
					foreach($exam_activation as $val)
					{
						$exam_name = $this->Master_model->getRecords("exam_master", array(	'exam_code' => $val['exam_code']), 'description,exam_category', array(
					'description' => 'ASC'
					));
					
					if(!empty($exam_name))
					{
						if($exam_name[0]['exam_category']==1)
						{
						$this->db->distinct();
						$exam_prd = $this->Master_model->getRecords("special_exam_dates", '','period');
						foreach ($exam_prd as $childArray) 
						{ 
					
						foreach ($childArray as $value) 
						{ 
					
							$spexam[] = $value; 
						} 
					}
						
					}else
					{
						$this->db->distinct();
						$exam_prd1 = $this->Master_model->getRecords("exam_activation_master", array(
					'exam_to_date >=' => $date,'exam_code'=>$val['exam_code']
					),'exam_period');
					if(!empty($exam_prd1))
					{
						foreach($exam_prd1  as $prd)
						{
					
						$nospexam[]=$prd['exam_period'];
					}
					
					}
					
					}
					
					}
					}
					}
										
					$final_exam_prd=array_merge($spexam,$nospexam);	
					$uniquefinal_exam_prd=array_unique($final_exam_prd);
					
					if(!empty($uniquefinal_exam_prd))
					{
					
					foreach($uniquefinal_exam_prd  as $val)
					{
					
					?>
					<option value="<?php echo $val;?>"> <?php echo $val;?> </option>
					<?php
					
					}
					
					}
					?>
                  </select>
                </div>
              </div>
  </div>
  <div class="column" >
    <div class="control-group" id="div_department_CC">
              <label for="department" class="control-label"><strong>Exam Name</strong></label>
                <!--Multiple Department Dropdown-->
                <div class="controls" id="dept_dd_optoin_CC">
                  <select class="input-large form-control" id="department_CC" name="exm_cd[]" multiple size="10" required style="width:auto">
                    <option value="select"> <?php echo '- Select -'; ?> </option>
                  </select>
                </div>
                <div class="controls form-control" id="other_dept_option_CC" style="display:none;"></div>
              </div>
  </div>
</div>
                
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit"> 
                   <a href="<?php echo base_url();?>admin/ExamCountDashboard/ExamCount/dowanload" class="btn btn-default pull-right">Refresh</a>
                    </div>
              </div>
              
           <?php //if($result_text!=''){?>
              <div class="box-footer">
                 <div class="box-body">
           		
					<?php //echo $result_text; ?>
                    <?php //echo $links; ?>
                    
                  </div>
              </div>
         <?php // } ?>
              
           </div>
        </div>
      </div>


    </form>
  
  <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Result</h3>
          </div>
          <!-- /.box-header 
            S.No. 	Exam Code 	Exam Name 	Member Count
            -->
            
             
           
          <div class="box-body">  <?php  if(!empty($Total_registration) )
			 {?>
              <a href="<?php echo base_url();?>admin/ExamVenueCount/download_CSV/<?php echo $exm_prd; ?>/<?php echo $exm_cd; ?>" >
                 <button type="button" class="btn btn-warning pull-right">Downaload CSV</button><br />
              
       </a><br />
		    <?php }?>
            <div class="table-responsive">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                
                  <th nowrap="nowrap">Vendor Code</th>
                  <th  nowrap="nowrap" >Center Code</th>
                     <th  nowrap="nowrap" >Center Name</th>
                  <th nowrap="nowrap">Venue Code</th>
                  <th   nowrap="nowrap">Venue Name</th>
                  <th  nowrap="nowrap" >Exam Date</th>
                  <th  nowrap="nowrap" >Exam Time</th>
                  <th   nowrap="nowrap">Total<br /> Capacity</th>
                  <th  nowrap="nowrap" >Registered <br />Count</th>
                  <th  nowrap="nowrap" >Balance <br />Capacity</th>
                  <th  nowrap="nowrap" > Occupied%</th>
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">
              <?php 
				 	$count=1;
			 if(!empty($Total_registration))
			 {
			
				
				  foreach($Total_registration  as $val)
				{?>
					
					<tr>
               
                  <td><?php   
					 echo $val['vendor_code'] ;
				  ?></td>
                  <td><?php   echo $val['center_code'] ;
				  ?></td>
                  <td><?php
					  $center_name = $this->Master_model->getRecords("center_master", array(
												'center_code' => $val['center_code'],
												), 'center_name', array(
												'center_name' => 'ASC'
												));
									if(!empty($center_name))
									{
										echo $center_name[0]['center_name'];
									}
												?></td>
                     <td><?php   echo $val['venue_code'] ;
				  ?></td>
                   <td><?php   echo $val['venue_name'] ;
				  ?></td>
                     <td><?php  
						 echo $val['exam_date'] ;
					 ?></td>
                     <td><?php
						 echo $val['session_time'] ;
					 ?></td>
                      <td><?php  
						  echo $val['session_capacity'] ;
					 ?></td>
                            <td><?php 
							echo $val['registered_count'] ;
					 ?></td>
                            <td><?php 
							echo $val['balance_capacity'] ;
					 ?></td>
                            <td> <?php 
							 $min_cap=$val['session_capacity']-5; ?> 
						    <?php if($min_cap<=$val['registered_count'])
										{?>
                    <span style="color:#F00;">
                 <?php   echo  round($val['occupied'],2) .'%'  ;?>
                    </span>
                    <?php }else
										{?>
                <?php  echo  round($val['occupied'],2) .'%'  ;?>   
                    <?php 
						}	
					 ?>
                            
                  </td>   
                  </tr>
				<?php  $count++;
				}
				 
			 }else
			 {
				
				 ?>
				  
                  <tr>
                  <td  nowrap="nowrap" style="color:#F00;"> No Record found ...! </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                <td></td>
                    </tr>
			 <?php }?>
         
              </tbody>
            </table>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
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
<script type="text/javascript">
  $('#search').parsley('validate');
$('#addForm').parsley('validate');

$(document).ready(function() 
{

 var base_url = window.location.origin;
                //get departments for Outward form
                $('#authority').on('change', function() {
                    var period = $(this).val();
                    //alert(base_url);
                    if (period) {
                        $.ajax({
                            type: 'POST',
                            url: base_url + "/admin/ExamCountDashboard/ExamCount/get_multiple_exam",
                            //url: 'admin/ExamVenueCount/get_multiple_exam',
                            data: 'period=' + period,
                            success: function(html) {
                                if (period) {
                                    $('#other_dept_option_CC').hide();
                                    $('#dept_dd_optoin_CC').show();
                                    $('#department_CC').html(html);
                                } else {
                                    $('#dept_dd_optoin_CC').hide();
                                    $('#other_dept_option_CC').show();
                                    $('#other_dept_option_CC').html(html);
                                }
                            }
                        });
                    } else {
                        $('#department_CC').html('<option value="">Select Board First</option>');
                    }
                });
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});	
   
   
   $('.input-selector').on('keypress', function(e){
	  return e.metaKey || // cmd/ctrl
		e.which <= 0 || // arrow keys
		e.which == 8 || // delete key
		/[0-9]/.test(String.fromCharCode(e.which)); // numbers
	});

});

function download_file(no, format, optval, regno, regid, records)
{  
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+"admin/Downloads/download_file";
	/*var width = 100;
	var height = 100;	
	mywindow=window.open(url,"mywindow11","location=0,status=0,scrollbars=1,resizable=1,menubar=0,width="+width+",height="+height);*/
	
	//alert("no :"+no+", format : "+format+", dateInput : "+optval+", regno : "+regno+", regid : "+regid+", records : "+records);
	
	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {no : no, format : format, dateInput : optval, regno : regno, regid : regid, records : records },
		success: function(res)
		{
			
		}
	});
}
		
		
$(function () {
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'admin/ExamVenueCount/dowanload';
	
	// Pagination function call
	//paginate(listing_url,'','','');
	//$("#base_url_val").val(listing_url);
});
function get_loader()
{
	$(".loading").show();
}


</script>


</script>
 
<?php $this->load->view('admin/ExamCountDashboard/footer');?>