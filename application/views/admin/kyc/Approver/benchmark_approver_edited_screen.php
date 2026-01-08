<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
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
		<h1> Benchmark KYC Verification </h1>
	</section>
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
		<?php if($error!=''){?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
			<?php echo $error; ?> </div>
			<?php } if($success!=''){ ?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
			<?php echo $success; ?> </div>
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
						<form class="form-horizontal" name="checkmember" id="checkmember" action="" method="post">
							<input type="hidden" name="regnumber" id="regnumber" value="<?php echo $this->uri->segment(5)?>">
							<input type="hidden" name="srno" id="srno" value="<?php echo $this->uri->segment(6)?>">
							<input type="hidden" name="totalRecCount" id="totalRecCount" value="<?php echo $totalCount; ?>">
							<table id="listitems" class="table table-bordered table-striped dataTables-example">
								<thead>
									<tr>
											<th id="">Membership No</th>
											<th id="">Candidate Name</th>
									  	<th id="">Visually impaired Certificate</th>
									  	<th id="">Orthopedically handicapped Certificate</th>
									  	<th id="">Cerebral palsy Certificate</th>
									</tr>
								</thead>
								<tbody class="no-bd-y" id="list">
									<?php 
										if(count($result))
										{
											foreach($result as $row)
											{	
											?>
											<tr>
												<td><?php echo $row['regnumber'];?></td>
												<!--NAME ADDED BY POOJA MANE 2024-03-13 -->
												<td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname']; ?></td>
												<td>
													<?php $actual_visually = "uploads/disability/v_".$row['regnumber'].".jpg"; 
														if($row['vis_imp_cert_img'] != ''){
														?>
														<a href="javascript:void(0)" onclick="open_modal('<?php echo base_url().$actual_visually.'?'.time(); ?>')">
															<img class="img-responsive" src="<?php echo base_url(); ?><?php echo $actual_visually; ?><?php echo '?'.time(); ?>"  name="visually" id="visually" style="height:150px; width:auto;" />
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
										<td></td>
										<td></td>
												<td>
													<?php if($row['vis_imp_cert_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="visually_checkbox" <?php  if($row['vis_imp_cert_img'] != ''){echo 'checked="checked"'; }   ?>>
														<?php
														}else
														{?>
															<input style="display:none" type="checkbox" name="cbox[]" id="cbox" value="0" checked="checked">
														<?php }
													?>
													</td>
													<td>
													<?php if($row['orth_han_cert_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="orthopedically_checkbox" <?php if($row['orth_han_cert_img'] != ''){echo 'checked="checked"';}?>>
														<?php
														}
														else
														{?>
															<input style="display:none" type="checkbox" name="cbox[]" id="cbox" value="0" checked="checked">
														<?php }
													?>
													</td>
													<td>
													<?php if($row['cer_palsy_cert_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="cerebral_checkbox" <?php if($row['cer_palsy_cert_img'] != ''){echo 'checked="checked"';} ?>>
														<?php
														}
														else
														{?>
															<input style="display:none" type="checkbox" name="cbox[]" id="cbox" value="0" checked="checked">
														<?php }
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
							<a href="<?php echo base_url()?>admin/kyc/Approver/benchmark_allocation_type/"  class="btn btn-info"  >Back</a>
							<?php 
								$where2 = ("(user_edited_date = '0000-00-00 00:00:00' OR user_edited_date > '".date('Y-m-d H:i:s')."')");
								$where3 = ("(approved_by = ".$this->session->userdata('kyc_id')." OR recommended_by = ".$this->session->userdata('kyc_id').")");
								$this->db->where($where2);
								$this->db->where($where3);
								// $check_kyc_status = $this->master_model->getRecords("member_kyc", array('regnumber'=>$this->uri->segment(5)));
								$check_kyc_status = $this->master_model->getRecords("benchmark_member_kyc", array('regnumber'=>$this->uri->segment(5)),'',array('kyc_id'=>'DESC'),'','1');
							?>
							<input type="submit" class="btn btn-info" name="btnSubmitkyc" id="btnSubmitkyc" value="KYC Complete" >
							<input type="submit" class="btn btn-info" name="btnSubmitRecmd" id="btnSubmitRecmd" value="Recommend" >
							<!--  <a href="<?php echo base_url()?>admin/kyc/Approver/kyc_complete/<?php echo $reg_no?>"  class="btn btn-info" name='btncom' >KYC Complete</a>-->
							<div style="color:#F00; display:none" id="next_id"> You need to complete the KYC or  Recommend  the current record  to proceed for next record</div>
							<div style="color:#F00; display:none" id="next_id_com"> This record is already  been submitted by you</div>
							<div style="color:#F00; display:none" id="next_id_rec"> This record is already been submitted by you </div>
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
	$("#modal_body_outer").html('<img src="'+img_name+'" style="border: 5px solid #ccc;min-height: 400px;max-width: 100% !important; max-height: 900px;">');
	$("#ImagePopUpModal").modal('show');
}
</script>

<div class="modal fade" id="ImagePopUpModal" tabindex="-1" role="dialog" aria-labelledby="ImagePopUpModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="background: transparent;box-shadow: 0 0 0 !important;">
      <div class="modal-body text-center">
				<div id="modal_body_outer"></div>
				<button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-top:5px;">Close</button>
      </div>
    </div>
  </div>
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
		$('#next_id_com').hide();
		$('#next_id_rec').hide();
	}
	function check_complete()
	{
		$('#next_id_com').show();
		$('#next_id').hide();
		$('#next_id_rec').hide();
	}
	function check_recommend()
	{
		$('#next_id_rec').show();
		$('#next_id_com').hide();
		$('#next_id').hide();
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