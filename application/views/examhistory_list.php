	<div class="content-wrapper">
	<section class="content-header"><h1>Exam Application Details</h1></section>
	<form class="form-horizontal" name="roleAddForm" id="roleAddForm" action="" method="post">
	<section class="content">			
	<div class="row">
	<div class="col-xs-12">					
	<div class="box">
	<div class="box-header">
	<h3 class="box-title">Exam List</h3>
	<div class="pull-right"></div>
	</div>

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

	<?php // echo "<pre>"; print_r($exam_history); echo "</pre>"; ?>

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
	<th id="qualifying_exam3">Result</th>
	</tr>

	</thead>
	<tbody class="no-bd-y" id="list">
	<?php 
	if(count($exam_history) > 0)
	{$i=1; $dup_cert_count=0;
	$exam_appear_arr=array();	
	foreach($exam_history as $examrow)
	{?>
	<tr>
	<td><?php echo $i;?></td>
	<td><?php 

	$explode_ename = explode("-",$examrow['description']);
	//echo $explode_ename[0];

	$arr = array('JAN','DEC','2019','2020');

	foreach ($arr as $char) {
	$pos = 0;
	while ($pos = strpos($examrow['description'], $char, $pos)) {
	$positions[$char][] = $pos;
	$pos += strlen($char);
	}
	}

	$ex_name =  str_replace($arr, '', $examrow['description']);
	echo preg_replace("/\([^)]+\)/","",$ex_name);

	//echo $examrow['description'];

	?></td>
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
	<td>
	<?php 
	$mem_exam_code = $examrow['exam_code'];
	if(in_array($mem_exam_code, array(340,3400))) { $mem_exam_code = '34'; }
	else if(in_array($mem_exam_code, array(1770,17700))) { $mem_exam_code = '177'; }
	else if(in_array($mem_exam_code, array(580,5800))) { $mem_exam_code = '58'; }
	else if(in_array($mem_exam_code, array(1600,16000))) { $mem_exam_code = '160'; }
	else if(in_array($mem_exam_code, array(590))) { $mem_exam_code = '59'; }
	else if(in_array($mem_exam_code, array(10100,101000))) { $mem_exam_code = '101'; }
	else if(in_array($mem_exam_code, array(200))) { $mem_exam_code = '20'; }
	else if(in_array($mem_exam_code, array(2027))) { $mem_exam_code = '1017'; }
	else if(in_array($mem_exam_code, array(1750))) { $mem_exam_code = '175'; }

	$mem_exam_period = $examrow['exam_period'];
	$result = '--';
	$count_result = 0;

	//echo $mem_exam_code." >> ".$mem_exam_period."<br>";

	if(in_array($mem_exam_code, array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'))))
	{
	$tbl_name = 'jaiib_marks';																
	if($mem_exam_code == $this->config->item('examCodeJaiib') && in_array($mem_exam_period, array(116,117,118,216,217,218,220))) 
	{ 
	$tbl_name = 'marks_'.$mem_exam_code.'_'.$mem_exam_period; 
	}
	else if($mem_exam_code == $this->config->item('examCodeDBF') && in_array($mem_exam_period, array(116,117,118,216,217,218))) 
	{ 
	$tbl_name = 'marks_'.$mem_exam_code.'_'.$mem_exam_period; 
	}

	if ($this->db->table_exists($tbl_name))
	{
	$result_data = $this->master_model->getRecords($tbl_name,array('exam_id'=>$mem_exam_code,'exam_period'=>$mem_exam_period, 'regnumber'=>$this->session->userdata('regnumber')),'');
	//echo $this->db->last_query().'<br><br>';
	$count_result = count($result_data);  
	}																
	}
	else if(in_array($mem_exam_code, array($this->config->item('examCodeCaiib'),62,$this->config->item('examCodeCaiibElective63'),64,65,66,67,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),72)))
	{
	$tbl_name = 'caiib_marks';																
	if(in_array($mem_exam_code, array($this->config->item('examCodeCaiib'),62,$this->config->item('examCodeCaiibElective63'),64,65,66,67,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),72)) && in_array($mem_exam_period, array(116,117,118,216,217,218))) 
	{ 
	$tbl_name = 'marks_'.$mem_exam_code.'_'.$mem_exam_period; 
	}																

	if ($this->db->table_exists($tbl_name))
	{
	$result_data = $this->master_model->getRecords($tbl_name,array('exam_id'=>$mem_exam_code,'exam_period'=>$mem_exam_period, 'regnumber'=>$this->session->userdata('regnumber')),'');
	//echo $this->db->last_query().'<br><br>';
	$count_result = count($result_data);
	}
	}
	if(in_array($mem_exam_code, array(101)))
	{
	$tbl_name = 'bcbf_marks';	

	if ($this->db->table_exists($tbl_name))
	{
	$result_data = $this->master_model->getRecords($tbl_name,array('exam_id'=>$mem_exam_code,'exam_period'=>$mem_exam_period, 'regnumber'=>$this->session->userdata('regnumber')),'');
	//echo $this->db->last_query().'<br><br>';
	$count_result = count($result_data);
	}
	}															
	else if(in_array($mem_exam_code, array(7,8,11,18,19,20,24,25,26,34,58,59,74,78,79,81,135,148,149,151,153,156,158,160,161,162,163,164,165,166,175,177,$this->config->item('examCodeSOB'),1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1017,1019,1020)))
	{
	$tbl_name = 'dipcert_mark';

	if ($this->db->table_exists($tbl_name))
	{
		if(count($exam_appear_arr) >0)
		{	
			foreach($exam_appear_arr as $k=>$v)
			{
					if(($mem_exam_code==$k))
				{
					$exam_appear_arr[$mem_exam_code]=$v+1;
				}
				else{
						$exam_appear_arr[$mem_exam_code]=0;
				}
			}
		}
		else{
			$exam_appear_arr[$mem_exam_code]=0;
		}
		$j=$exam_appear_arr[$mem_exam_code]+1;
		$this->db->limit($j,$exam_appear_arr[$mem_exam_code]);
		$this->db->order_by('result_date','DESC');
		$result_data = $this->master_model->getRecords($tbl_name,array('exam_id'=>$mem_exam_code,'exam_period'=>$mem_exam_period, 'regnumber'=>$this->session->userdata('regnumber')),'');
		//echo $this->db->last_query().'<br><br>';
		$count_result = count($result_data);

		if($count_result == 0)
		{
			$tbl_name = 'marks_'.$mem_exam_code.'_'.$mem_exam_period; 
			if ($this->db->table_exists($tbl_name))
			{
				$result_data = $this->master_model->getRecords($tbl_name,array('exam_id'=>$mem_exam_code,'exam_period'=>$mem_exam_period, 'regnumber'=>$this->session->userdata('regnumber')),'');
				//echo $this->db->last_query().'<br><br>';
				$count_result = count($result_data);
			}
		}
		else{
			$dup_cert_count++;
		}
	}
	}
	//echo $this->db->last_query();
	//echo "<br>".$count_result."<br>"; 
	if($count_result > 0)
	{
	$F_cnt = $A_cnt = $P_cnt = 0;
	foreach($result_data as $res)
	{
	if($res['status'] == 'F') { $F_cnt++; }
	else if($res['status'] == 'P') { $P_cnt++; }
	else if($res['status'] == 'A') { $A_cnt++; }
	}

	if($P_cnt == $count_result) { $result = 'PASS'; }
	else if($A_cnt == $count_result) { $result = 'ABSENT'; }
	else if($F_cnt > 0) { $result = 'FAIL'; }
	}

	echo $result;
	?>
	</td>
	</tr>                     
	<?php 
	$i++;}
	}?>
	</tbody>
	</table>
	<!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
	<div id="links" class="dataTables_paginate paging_simple_numbers"></div>

	</div>
	</div>
	</div>
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
