<?php $this->load->view('admin/MonthlyCountDashboard/includes/header');?>
<?php //$this->load->view('admin/MonthlyCountDashboard/includes/sidebar');?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('error'); ?> </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Date wise Links</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            
              <?php 
			  
			  		$flg = $this->uri->segment(5);
			  		if(isset($flg) && $flg == 'old' ){
						
						$month_ini = new DateTime("last day of last month");
						$today = $month_ini->format('Y-m-d');
						$ptoday = $month_ini->format('Y-M-d');
						$parr = explode("-",$ptoday);
						$month =  $parr[1];
						$first_year_month = date("M");
						if($first_year_month == 'Jan'){
							$lastyear =  date("Y");
							$year = $lastyear - 1;
						}else{
							$year =  date("Y");
						}
						$start_date = "01-".$month."-".$year;
						
						$all_old_first_date = new DateTime("last day of last month");
						$old_first_date = $all_old_first_date->format('Y-m-d');
						
						
					}else{
						$today = date("Y-m-d");
						$month =  date("M");
						$year =  date("Y");
						$start_date = "01-".$month."-".$year;
						$old_first_date = date("Y-m-t", strtotime($today));
					}
			  
			   		$new_date ='';
			 		/*$today = date("Y-m-d");
					$month =  date("M");
					$year =  date("Y");
					$start_date = "01-".$month."-".$year;*/
					$start_time = strtotime($start_date);
					$end_time = strtotime("+1 month", $start_time);
					
					$end_time2 = strtotime($today);
					for($i=$start_time; $i<=$end_time2; $i+=86400){		
						if(strtotime($today) <  strtotime($new_date) )
							{
								break;
							}
							$new_date = $list[] = date('Y-m-d', $i);
					}
					//print_r($list);
					for($i=0; $i<count($list); $i++)
					{?><a href="<?php echo base_url();?>admin/MonthlyCount/monthlycount/date_count/<?php echo $list[$i]; ?>">
					  <?php echo date("jS F , Y", strtotime($list[$i]));echo '</br></br>';?>
                      </a>
					  
					<?php }?>
                    <?php if(isset($flg) && $flg != 'old' ){?>
                    <a href="<?php echo base_url();?>admin/MonthlyCount/monthlycount/oldmonth/old"><< Old</a>
                    <?php }?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admin/MonthlyCount/monthlycount/all_count/<?php echo $old_first_date;?>"> All</a>
                
          </div>
        </div>
        <!-- /.box-body --> 
      </div>
      <!-- /.box --> 
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
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script> 
<script type="text/javascript">
  $('#search').parsley('validate');
</script> 

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>--> 
<script type="application/javascript">
$(document).ready(function() 
{
/*	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});*/
	
	/*$(".chk").on('click', function(e){
		alert('in');
		
			var status = this.checked; // "select all" checked status
			alert(status);
			$('.chk').each(function(){ //iterate all listed checkbox items
				this.checked = status; //change ".checkbox" checked status
			});
		
	})*/
});

$(function () {
	$("#listitems").DataTable();
	/*var base_url = '<?php // echo base_url(); ?>';
	var listing_url = base_url+'admin/kyc/Kyc/recommended_list/';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		

		
</script>
<?php $this->load->view('admin/MonthlyCountDashboard/includes/footer');?>
