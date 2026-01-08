<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<link href="<?php echo base_url('assets/css/popup.css')?>" rel="stylesheet">	
<link href="<?php echo base_url('assets/dist/css/lightgallery.css')?>" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<style>
	.min-height{ min-height:650px;}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Benchmark KYC Verification 
		</h1>
	</section>
    <br />
	<div class="col-md-12">
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
		<?php if($success!=''){ ?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
				<?php echo $success ?>
			</div>
		<?php } ?>
	</div>
    <!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box min-height">
					<div class="box-header">
						<h3 class="box-title">Selected Member</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php 
							$selected_srno=$this->uri->segment(6);
							if($this->uri->segment(6) < $this->uri->segment(7))
							{
								$selected_srno+=1;
								if($selected_srno==$this->uri->segment(7))
								{
									$selected_srno=$this->uri->segment(6);
								}
							}
							else
							{	
								$selected_srno=$this->uri->segment(7);
							}
							$totalCount = $totalRecCount;
						?>
						<form class="form-horizontal" name="checkmember" id="checkmember" action="<?php echo base_url();?>admin/kyc/Kyc/benchmark_member/<?php echo $reg_no?>/<?php echo $selected_srno;?>/<?php echo $totalCount; ?>" method="post">
							<table id="listitems" class="table table-bordered table-striped dataTables-example">
								<thead>
									<tr>
										<th id="">Membership No</th>
										<th id="">Visually impaired Certificate</th>
										<th id="">Orthopedically handicapped Certificate</th>
										<th id="">Cerebral palsy Certificate</th>
									</tr>
								</thead>
								<tbody class="no-bd-y" id="list">
									<?php $regtype='';
										if(count($result))
										{
											foreach($result as $row)
											{  
											?>
											<tr>
												<td><?php echo $row['regnumber']; ?></td>
												<td>
													<?php $actual_visually = "uploads/disability/v_".$row['regnumber'].".jpg"; 
														if($row['vis_imp_cert_img'] != ''){
														?>
														<a href="javascript:void(0)" onclick="open_modal('<?php echo base_url().$actual_visually.'?'.time(); ?>')">
															<img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_visually; ?><?php echo '?'.time(); ?>"  name="visually" id="visually" style="height:150px; width:auto;"  />
														</a>
														<?php
														} 
														else
														{
															echo "Not Applicable";
														}
													?>   
												</td>
												<td>
													<?php $actual_orthopedically = "uploads/disability/o_".$row['regnumber'].".jpg";
														if($row['orth_han_cert_img'] != ''){
														?>
														<a href="javascript:void(0)" onclick="open_modal('<?php echo base_url().$actual_orthopedically.'?'.time(); ?>')">
															<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_orthopedically;?><?php echo '?'.time(); ?>"  name="orthopedically" id="orthopedically" style="height:150px; width:auto;" />
														</a>
														<?php
														} 
														else
														{
															echo "Not Applicable";
														}
													?>
												</td>
												<td>
													<?php $actual_cerebral = "uploads/disability/c_".$row['regnumber'].".jpg"; 
														if($row['cer_palsy_cert_img'] != ''){
														?>
														<a href="javascript:void(0)" onclick="open_modal('<?php echo base_url().$actual_cerebral.'?'.time(); ?>')">
															<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_cerebral;?><?php echo '?'.time(); ?>"  name="cerebral" id="cerebral" style="height:150px; width:auto;" />
														</a>
														<?php
														} 
														else
														{
															echo "Not Applicable";
														}
													?>
												</td>
											</tr>
											<tr>
												<td></td>
												<td>
													<?php if($row['vis_imp_cert_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="visually_checkbox" <?php  if($row['vis_imp_cert_img'] != ''){echo 'checked="checked"'; }   ?>>
														<?php
														}
													?>
												</td>
												<td>
													<?php if($row['orth_han_cert_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="orthopedically_checkbox" <?php if($row['orth_han_cert_img'] != ''){echo 'checked="checked"';}?>>
														<?php
														}
													?>
												</td>
												<td>
													<?php if($row['cer_palsy_cert_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="cerebral_checkbox" <?php if($row['cer_palsy_cert_img'] != ''){echo 'checked="checked"';} ?>>
														<?php
														}
													?>
												</td>
											</tr>
											<?php }
											}else{
											echo "No Recode Found..............!!!!"; 
										}
									?>                  
								</tbody>
							</table>
							<?php 
								if($totalCount != "" && $totalCount != 0)
								{
									echo "Showing ".$this->uri->segment(6)." of ".$totalCount." Records"; 
								}
							?>  
							<center>
								<a href="<?php echo base_url()?>admin/kyc/Kyc/benchmark_recommender/"  class="btn btn-info"  >Back</a>
								<?php 
									$members = $this->master_model->getRecords("benchmark_member_kyc", array('regnumber'=>$this->uri->segment(5),'recommended_by'=>$this->session->userdata('kyc_id'),'record_source '=>'New'));
									// echo $this->db->last_query();exit;
									if(count($members) > 0)
									{
									?>
									<a href="javascript:void(0)"  class="btn btn-info" onclick="check_submit()"  >Submit</a>
									<?php 
									}
									else
									{?>
									<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" ><br /> <br /> 
									<?php 
									}?>
									<!--<input type="submit"  class="btn btn-info"   onclick="<?php echo base_url()?>Kyc/next_recode" name="btnExit" id="btnExit" value="Next" >-->
									<div style="color:#F00; display:none" id="next_id"> You need to submit the record  to proceed for next </div>
									<div style="color:#F00; display:none" id="next_id_sub">Benchmark Kyc for  this record is already submitted</div>
							</center> 
						</form> 
					</table>
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
</section>
<?php  $actual_visually = "uploads/disability/v_".$row['regnumber'].".jpg"; 
	if($row['vis_imp_cert_img'] != ''){
	?>
	<div id="openModalvisually" class="modalDialog">
		<div>	<a href="#close" title="Close" class="close">X</a>
			<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_visually;?><?php echo '?'.time(); ?>"  name="visually" id="visually" height="500" width="500"  />
		</div>
	</div>
	<?php 	
	}
	$actual_orthopedically= "uploads/disability/o_".$row['regnumber'].".jpg"; 
	if($row['orth_han_cert_img'] != ''){
	?>
	<div id="openModalorthopedically" class="modalDialog">
		<div>	<a href="#close" title="Close" class="close">X</a>
			<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_orthopedically;?><?php echo '?'.time(); ?>"  name="orthopedically" id="orthopedically" height="500" width="500"  />
		</div>
	</div>
	<?php 
	}
	$actual_cerebral = "uploads/disability/c_".$row['regnumber'].".jpg"; 
	if($row['cer_palsy_cert_img'] != ''){
	?>
	<div id="openModalcerebral" class="modalDialog">
		<div>	<a href="#close" title="Close" class="close">X</a>   
			<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_cerebral?><?php echo '?'.time(); ?>"  name="cerebral" id="cerebral" height="500" width="500"  />
		</div>
	</div>
<?php } ?>
</div>
</div>

<script>
function open_modal(img_name)
{
	$("#modal_body_outer").html('<img src="'+img_name+'" style="border:5px solid #ccc; min-height:400px; max-width:800px; max-width:800px;">')
	$("#ImagePopUpModal").modal('show');
}
</script>

<div class="modal fade" id="ImagePopUpModal" tabindex="-1" role="dialog" aria-labelledby="ImagePopUpModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: transparent;box-shadow: 0 0 0 !important;">
      <div class="modal-body text-center">
				<div id="modal_body_outer"></div>
				<button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-top:5px;">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- DAta Tables -->
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
		$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
			$('#to_date').datepicker('setStartDate', new Date($(this).val()));
		}); 
		$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
			$('#from_date').datepicker('setEndDate', new Date($(this).val()));
		});
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
		//$("#listitems").DataTable();
		/*var base_url = '<?php //echo base_url(); ?>';
			var listing_url = base_url+'admin/Report/getList';
			// Pagination function call
			paginate(listing_url,'','','');
		$("#base_url_val").val(listing_url);*/
	});
	function check_next()
	{
		$('#next_id').show();

	}
	function check_submit()
	{
		$('#next_id_sub').show();
	}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#lightgallery_photo,#lightgallery_sign,#lightgallery_proof').lightGallery();
	});
</script>
<script src="<?php echo base_url('assets/dist/js/picturefill.min.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lightgallery.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-zoom.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-hash.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/jquery.mousewheel.min.js')?>"></script>
<?php $this->load->view('admin/kyc/includes/footer');?>