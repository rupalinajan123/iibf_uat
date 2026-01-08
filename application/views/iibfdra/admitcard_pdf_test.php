<style>
#example1_wrapper {
	max-width: 96%;
	margin: 20px auto;
}
</style>
	
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
      Admitcard PDF
		</h1>
		<?php echo $breadcrumb; ?>
	</section>
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
	<!-- Main content -->
	<section class="content">
    
		
		<div class="row">
			<div class="col-xs-12">				
        <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        <input type="hidden" name="base_url_val" id="base_url_val" value="" />
				
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Admitcard pdfs</h3>
						<div class="pull-right">
							<!--<a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('iibfdra/Admitcard/download_pdfs'); ?>"> Download All </a>-->
						</div> 
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						
						<?php if(!empty($pdf_listing)){
							$cnt = 1;
						?>
						
						<div style="text-align:center">   
							<!--  <div style="padding: 10px 80px 10px 80px;">
								<p >
								<b>
								Due to unavailability of appropriate venue at below 6 cities, the upcoming DRA exam on 12th January, 2019 has been rescheduled. Revised date for the said exam will be communicated soon.
								</b>
								<div style="padding: 10px 80px 0px 325px;">
								<table class="table table-bordered" style="width: 1%;padding-left:50px">
								<thead>
								<tr style="background-color: #f39c12;
								border-color: #e08e0b;" >
								<th style="text-align: center;">Sr.No</th>
								<th style="text-align: center;">Resion</th>
								<th style="text-align: center;">City</th>
								</tr>
								</thead>
								<tbody>
								<tr>
								<td>1</td>
								<td>BIHAR</td>
								<td>BEGUSARAI</td>
								</tr>
								<tr>
								<td>2</td>
								<td>BIHAR</td>
								<td>PATNA</td>
								</tr>
								<tr>
								<td>3</td>
								<td>BIHAR</td>
								<td>PURNEA</td>
								</tr>
								<tr>
								<td>4</td>
								<td>JHARKHAND</td>
								<td>RANCHI</td>
								</tr>
								<tr>
								<td>5</td>
								<td>ORISSA </td>
								<td>BHUBNESHWAR</td>
								</tr>
								<tr>
								<td>6</td>
								<td>MAHARASHTRA</td>
								<td>MUMBAI</td>
								</tr>
								</tbody>
								</table>
								</div>
								</p>
							</div> -->
							<h5><strong>ADMIT LETTER FOR DRA/DRA-TC FOR 7th March,2020</strong></h5>
							<a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('iibfdra/Admitcard/download_pdfs'); ?>"> Download All </a>
						</div>
						<!--<table id="example1" class="table table-bordered table-striped">
							<thead>
							<tr>
							<th>Sr No.</th>
							<th>Admitcard PDF</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach($pdf_listing as $key=>$row){ 
							//print_r($row);?>
							<tr>
							<td><?php echo $cnt  ?></td>
							<td><a href="<?php echo base_url().$row['pdf'];?>" target="_blank"><?php echo $row['admitcard_name']?></a></td>
							</tr>
							
							<?php  $cnt++;
							} ?>
							
							</tbody>
							
						</table>-->
						<?php }
						else
						{?>
						<div style="text-align:center; color:#F00">Record not found!!</div>
						<?} ?>
					</div>
					<!-- /.box-body -->
				</div>
				
				
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Admitcard pdfs for exam code 45 and 57 - 777</h3>
					</div>
					
					<div class="box-body">
						<h5 class="text-center" style="margin:5px 0 0 0;"><strong>Admitcard pdfs for exam code 45 and 57 - 777</strong></h5>
						<div class="table-responsive">
							<table id="example1" class="table table-bordered">
								<thead>
									<tr>
										<th class="text-center">Sr No</th>
										<th class="text-center">Exam Code</th>
										<th class="text-center">Exam Period</th>
										<th class="text-center">UTR No</th>
										<th class="text-center">Member Count</th>
										<th class="text-center">Transaction Date</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $cnt = 1; 
										if(count($download_admit_card_data) > 0)
										{		
											foreach($download_admit_card_data as $res)
											{	
												$file_date = date("Ymd", strtotime($res['modified_on']));	?>
											<tr>
												<td class="text-center"><?php echo $cnt; ?></td>
												<td class="text-center"><?php echo $res['exam_code']; ?></td>
												<td class="text-center"><?php echo $res['exam_period']; ?></td>
												<td><?php echo $res['transaction_no']; ?></td>
												<td class="text-center"><?php echo $res['qty']; ?></td>
												<td class="text-right"><?php echo date("d M, Y", strtotime($res['modified_on'])); ?></td>
												<td class="text-center"><a class="btn btn-success btn-sm" href="<?php echo base_url('uploads/dra_admitcardpdf_zip/'.$file_date."/DRA_".$file_date."_".$res['transaction_no']."_".$res['qty'].".zip"); ?>"> Download</a></td>
											</tr>
											<?php	$cnt++;
											}
										} ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				
			</div>
		</div>
	</section>
</div>
<!-- DataTables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<!-- Data Tables -->
<script type="text/javascript">
	$(document).ready(function() {
    $('#example1').DataTable({
			responsive: true
		});
	});
</script>