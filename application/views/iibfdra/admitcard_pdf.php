<style>
#example1_wrapper {
	max-width: 96%;
	margin: 20px auto;
}

tfoot {
    display: table-header-group;
}

.pp0 , .pp5 , .pp1 , .pp3, .pp4 {
 display:none;	
}
</style>			 
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Admitcard PDF <?php echo $download_admitcard_data[0]['date']; ?>
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

          <div class="box" style='display:none'>
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
<h5><strong>ADMIT LETTER FOR DRA/DRA-TC FOR 8th October,2021</strong></h5>
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
						<h3 class="box-title">Admitcard pdfs for exam code 45 and 1036 - exam period <?php echo $download_admitcard_data[0]['exam_period']; ?> </h3>
					</div>
					
					<div class="box-body">
						
						<div class="table-responsive">
<table id="listitems2" class="table table-bordered table-striped dataTables-example">
								<thead>
									<tr>
										<th class="text-center">Sr No</th>
										<th class="text-center">Center Name</th>
										<th class="text-center">Exam Code</th>
										<th class="text-center">Exam Period</th>
										<th class="text-center">No of candidates</th>
									
										<th class="text-center">Action</th>
									</tr>
								</thead><tfoot>
									<tr>
										<th class="text-center">Sr No</th>
										<th class="text-center">Center Name</th>
										<th class="text-center">Exam Code</th>
										<th class="text-center">Exam Period</th>
										<th class="text-center">No of candidates</th>
									
										<th class="text-center">Action</th>
									</tr>
								</tfoot>
								<tbody>
									<?php $cnt = 1; 
										if(count($download_admitcard_data) > 0)
										{		
											foreach($download_admitcard_data as $res)
											{	
												//$file_date = date("Ymd", strtotime($res['updated_date']));	?>
											<tr>
												<td class="text-center"><?php echo $cnt; ?></td>
												<td class="text-center"><a  href="<?php echo base_url().'/iibfdra/Admitcard/centerwise_member_list/'.$res['center_code'].'/'.$res['exm_cd'].'/'.$res['exam_period'] ; ?>"><?php echo $res['center_name']; ?></a></td>
												<td class="text-center"><?php if($res['exm_cd'] == 45) {echo 'DEBT RECOVERY AGENT(Old Syllabus)';}elseif($res['exm_cd'] == 57){echo 'DEBT RECOVERY AGENT - TELE CALLERS';}elseif($res['exm_cd'] == 1036){echo 'DEBT RECOVERY AGENT(Revised Syllabus)';} ?></td>
												<td class="text-center"><?php echo $res['exam_period']; ?></td>
												
												<td class="text-center"><?php echo $res['no_of_candidates']; ?></td>
												
												<td class="text-center">
																								
													<a class="" href="<?php echo base_url().'/iibfdra/Admitcard/download_centerwise_pdf/'.$res['center_code'].'/'.$res['exm_cd'].'/'.$res['exam_period'] ; ?>"> Download</a>
												</td>
											</tr>
											<?php	$cnt++;
											}
										} ?>
								</tbody>
								
							</table>
        </div>
					</div>
					
      </div>
          
				
				
				<!-- <div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Admitcard pdfs for exam code 45 and 57 - 777 </h3>
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
												$file_date = date("Ymd", strtotime($res['updated_date']));	?>
											<tr>
												<td class="text-center"><?php echo $cnt; ?></td>
												<td class="text-center"><?php echo $res['exam_code']; ?></td>
												<td class="text-center"><?php echo $res['exam_period']; ?></td>
												<td><?php echo $res['UTR_no']; ?></td>
												<td class="text-center"><?php echo $res['pay_count']; ?></td>
												<td class="text-right"><?php echo date("d M, Y", strtotime($res['updated_date'])); ?></td>
												<td class="text-center">
													<?php 
														if($res['updated_date'] < "2020-11-24 11:04:00")
														{
															$downloadLink = base_url('uploads/dra_admitcardpdf_zip/'.$file_date."/DRA_".$file_date."_".$res['UTR_no']."_".$res['pay_count'].".zip"); 
														}
														else
														{
															$downloadLink = base_url('uploads/dra_admitcardpdf_zip/'.$file_date."/DRA_".$file_date."_".$res['UTR_no']."_".$res['pay_count']."_".$res['id'].".zip");
														}	?>													
													<a class="btn btn-success btn-sm" href="<?php echo $downloadLink; ?>"> Download</a>
												</td>
											</tr>
											<?php	$cnt++;
											}
										} ?>
								</tbody>
							</table>
        </div>
					</div>
					
      </div>
      
			</div> -->
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