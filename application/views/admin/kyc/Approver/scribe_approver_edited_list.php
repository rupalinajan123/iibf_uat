<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1> Scribe KYC member list </h1>
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
	</div>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Scribe Allocated  records </h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php /*?>           <form class="form-horizontal" name="checkmember" id="checkmember" action="<?php echo base_url();?>/admin/kyc/Approver/allocation_type/" method="post">
						<?php */?>
						<?php //print_r($result);die;
							if(count($result)>0)
							{?>
							<table id="listitems" class="table table-bordered table-striped dataTables-example">
								<thead>
									<tr>
										<th id="">No</th>
										<th id="">Membership No</th>
										<th id="">Candidate Name</th>
										<th id="">Recommended Fields</th>
										<!--<th id="">Recommended date</th>-->
										<th id="">Action</th>
									</tr>
								</thead>
								<tbody class="no-bd-y" id="list">
									<?php 
										/* Here array key to start from 1 instead of 0 for showing counts functionality */
										$result = array_combine(range(1, count($result)), array_values($result));
										//$totalRecCount = count($result);
										$original_allotted_Arr = explode(',', $original_allotted_member_id);
										$arr = array_slice($original_allotted_Arr, -$totalRecCount);
										$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
										$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
										if(count($result)){
											$row_count = 1;
											foreach($result as  $rKey => $row)
											{  
												$fields=array();
											?>
											<tr>
												<?php /*?>              <td><input type="checkbox" name="checkbox[]" id="checkbox" value="<?php echo $row['regnumber'];?>" ></td>
												<?php */?>
												<td><?php echo $row_count;?></td>
												<td><?php echo $row['regnumber'];?></td>
												<td><?php  
													$username=$row['namesub'].' '.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'];
													echo  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
												?>
												</td>
												<td><?php
													if(isset($row['mem_idproof']) && $row['mem_idproof']==0)
													{
														$fields[]='Idproof';
													}
													if(isset($row['mem_declaration']) && $row['mem_declaration']==0)
													{
														$fields[]='Declaration';
													} 
													if(isset($row['mem_visually']) && $row['mem_visually']==0)
													{
														$fields[]='Visually';
													}
													if(isset($row['mem_orthopedically']) && $row['mem_orthopedically']==0)
													{
														$fields[]='Orthopedically';
													}
													if(isset($row['mem_cerebral']) && $row['mem_cerebral']==0)
													{
														$fields[]='Cerebral';
													}
													if(count($fields) > 0)
													{
														echo implode(' , ',$fields);
													}
													elseif(count($fields) == 0)
													{?>
													<span style="color:green;"><?php echo 'Record found ok'; ?></span>
													<?php 
													}
												?>
												</td>
												<!--<td><?php //if(isset($row['recommended_date']) && $row['recommended_date']!='' && $row['recommended_date']!='0000-00-00'){echo date('d-m-Y',strtotime($row['recommended_date']));}?></td> -->
												<?php
													$memberNo = $row['regnumber'];
													$updated_list_index = array_search($memberNo, $reversedArr_list);
													$srno = $updated_list_index;
												?>
												<td><a href="<?php echo base_url(); ?>admin/kyc/Approver/scribe_approver_edited_member/<?php echo $row['regnumber'];?>/<?php echo $srno; ?>/<?php echo $totalRecCount;?>">Approve/Recommend</a></td>
											</tr>
											<?php 
												$row_count++;				
											} ?>
											<?php 
											}?>
								</tbody>
							</table>
							<?php /*?>   <center> <input type="submit" class="btn btn-info" name="btnSubmitkyc" id="btnSubmitkyc" value="KYC Complete" >  </center>
							</form> <?php */?>
							<?php }else
							{
							?>
							<center>
								<?php 
									if(isset($emptylistmsg))
									{
										echo  $emptylistmsg;
									}
								?>
							</center>
						<?php }?>
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
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="application/javascript">
	$(function () {
		$("#listitems").DataTable();
	});
</script>
<?php /*?> <script type="text/javascript">
	$(document).ready(function(){
	$('#lightgallery_photo,#lightgallery_sign,#lightgallery_proof').lightGallery();
	});
</script><?php */?>
<?php $this->load->view('admin/kyc/includes/footer');?>