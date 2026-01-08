<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<link href="<?php echo base_url('assets/css/popup.css')?>" rel="stylesheet">	
<link href="<?php echo base_url('assets/dist/css/lightgallery.css')?>" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<script>var site_url="<?php echo base_url();?>";</script>
<style>
	.min-height{ min-height:650px;}
	 .form-group .wysihtml5-toolbar li::before {content: unset;}
	 .form-group .wysihtml5-toolbar li {padding-left: 0;}
	 .wysihtml5-sandbox{float:left!important;width:100% !important;}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Scribe KYC Verification 
		</h1>
	</section>
    <br />
	<div class="col-md-12">
		<div id="message"></div>
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
							//print_r($result);
							//print_r($result[0]['mobile_scribe']);
							//print_r($result['mobile_scribe']);die;
						?>
						
						<form class="form-horizontal" name="checkmember" id="checkmember" action="<?php echo base_url();?>admin/kyc/Kyc/scribe_member/<?php echo $reg_no?>/<?php echo $selected_srno;?>/<?php echo $totalCount; ?>" method="post">
							<table id="listitems" class="table table-bordered table-striped dataTables-example">
								<thead>
									<tr>
										<th id="">Membership No</th>
										<th id="">Scribe URN</th>
										<th id="">ID Proof <br><?php if ($result[0]['mobile_scribe']!=0) { ?>
										<i style="color:red;font-size:11px;">Required</i>
										<?php } ?></th>
										<th id="">Declaration<?php if ($result[0]['mobile_scribe']!=0) { ?>
										<i style="color:red;font-size:11px;">Required</i>
										<?php } ?></th></th>
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
												<td><?php /*echo $row;die;*/ echo $row['regnumber']; ?></td>
												<td><?php /*echo $row;die;*/ echo $row['scribe_uid']; ?>
													<input type="hidden" name="scribe_uid" id="scribe_uid" value="<?php echo $row['scribe_uid'];?>">
												</td>
												<td>
													<?php $actual_photoid = "uploads/scribe/idproof/".$row['idproofphoto']; 
														if($row['idproofphoto'] != ''){
														?>
														<a href="<?php echo base_url().$actual_photoid; ?>" target="_blank">
                                    <img src="<?php echo base_url().$actual_photoid; ?>" style="height:100px; width:100px;">
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
													<?php $actual_declaration = "uploads/scribe/declaration/".$row['declaration_img']; 
														if($row['declaration_img'] != ''){
														?>
														<a href="<?php echo base_url().$actual_declaration; ?>" target="_blank">
                                    <img src="<?php echo base_url().$actual_declaration; ?>" style="height:100px; width:100px;">
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
													<?php $actual_visually = "uploads/scribe/disability/".$row['vis_imp_cert_img']; 
														if($row['vis_imp_cert_img'] != ''){
														?>
														<a href="<?php echo base_url().$actual_visually; ?>" target="_blank">
                                    <img src="<?php echo base_url().$actual_visually; ?>" style="height:100px; width:100px;">
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
													<?php $actual_orthopedically = "uploads/scribe/disability/".$row['orth_han_cert_img'];

														if($row['orth_han_cert_img'] != ''){
														?>
														<a href="<?php echo base_url().$actual_orthopedically; ?>" target="_blank">
                                    <img src="<?php echo base_url().$actual_orthopedically; ?>" style="height:100px; width:100px;">
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
													<?php $actual_cerebral = "uploads/scribe/disability/".$row['cer_palsy_cert_img'];
														if($row['cer_palsy_cert_img'] != ''){
														?>
														<a href="<?php echo base_url().$actual_cerebral; ?>" target="_blank">
                                    <img src="<?php echo base_url().$actual_cerebral; ?>" style="height:100px; width:100px;">
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
												<td></td>
												<td>
													<?php if($row['idproofphoto'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="idproof_checkbox" <?php  if($row['idproofphoto'] != ''){echo 'checked="checked"'; }   ?>>
														<?php
														}
													?>
												</td>
												<td>
													<?php if($row['declaration_img'] != ''){ ?>
														<input type="checkbox" name="cbox[]" id="cbox" value="declaration_checkbox" <?php  if($row['declaration_img'] != ''){echo 'checked="checked"'; }   ?>>
														<?php
														}
													?>
												</td>
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
											echo "No Records Found..............!!!!"; 
										}
									?>                  
								</tbody>
							</table>
							<?php 
								/*if($totalCount != "" && $totalCount != 0)
								{
									echo "Showing ".$this->uri->segment(6)." of ".$totalCount." Records"; 
								}*/
							?>  
							<center>
								<a href="<?php echo base_url()?>admin/kyc/Kyc/scribe_recommender/"  class="btn btn-info"  >Back</a>
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
									<!-- <button type="button" class="btn btn-info btn-sm" id="button_id" data-toggle="modal" data-target="#myModal">Send Mail</button> -->
			
				<div class="modal fade" id="modal_id" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
				<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
				
				 <div class="alert alert-danger alert-dismissible" id="error" style="display:none;">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
				</div>
				  
				  <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
				</div>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="sd_master_label">Send Mail to Member<small></small> </h4>
				</div>
				<form role="form" method="post" id="frm_sd_master">
				<div class="box box-primary">
				<div class="row">
				<!-- left column -->
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="box-body">
				<div class="form-group">
				
				<label  class="col-sm-4 control-label" for="effective_from">Member Mail Id</label>
				<div class="col-sm-5">
				<input type="text" class="form-control" id="email" name="email" readonly="readonly" required value="<?php $email = $result[0]['email'];  echo $email;?>">
				<input type="hidden" name="form_type" id="form_type">
				
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-4 control-label" for="st">Subject</label>
				<div class="col-sm-5">
				<input type="text" class="form-control" id="subject" name="subject" value="IIBF:- Member Profile.<?php echo $row['regnumber']; ?>">
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-4 control-label" for="st">Mail Content</label>
				<div class="col-sm-6">
				<textarea id="mailtext" class="textarea" name="mailtext"  >Hello <?php echo $row['firstname']." ".$row['lastname'];?>,
  					
 			    </textarea>
				
				</div> 
				</div> 
				<input type="hidden" name="member_no" id="member_no" value="<?php echo $member_no=$this->uri->segment(5);?>">
				</div>
				<!-- /.box-body -->
				</div>
				</div>
				<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-3">
				<div class="box-footer">
				<button type="button" tabindex="14" class="btn btn-info" onclick="send_mail();">Submit</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				</div>
				</div>
				</div>
				</form>
				</div>
				</div>
				</div>
 	
									<!--<input type="submit"  class="btn btn-info"   onclick="<?php echo base_url()?>Kyc/next_recode" name="btnExit" id="btnExit" value="Next" >-->
									<div style="color:#F00; display:none" id="next_id"> You need to submit the record  to proceed for next </div>
									<div style="color:#F00; display:none" id="next_id_sub">Benchmark Kyc for  this record is already submitted</div>
							</center> 
						</form> 
					<!-- </table> -->
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
</section>
<?php  $actual_visually = "uploads/scribe/disability/".$row['vis_imp_cert_img']; 
	if($row['vis_imp_cert_img'] != ''){
	?>
	<div id="openModalvisually" class="modalDialog">
		<div>	<a href="#close" title="Close" class="close">X</a>
			<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_visually;?><?php echo '?'.time(); ?>"  name="visually" id="visually" height="500" width="500"  />
		</div>
	</div>
	<?php 	
	}
	$actual_orthopedically= "uploads/scribe/disability/".$row['orth_han_cert_img']; 
	if($row['orth_han_cert_img'] != ''){
	?>
	<div id="openModalorthopedically" class="modalDialog">
		<div>	<a href="#close" title="Close" class="close">X</a>
			<img class="img-responsive" src="<?php echo base_url();?><?php echo $actual_orthopedically;?><?php echo '?'.time(); ?>"  name="orthopedically" id="orthopedically" height="500" width="500"  />
		</div>
	</div>
	<?php 
	}
	$actual_cerebral = "uploads/scribe/disability/".$row['cer_palsy_cert_img']; 
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
	$("#modal_body_outer").html('<img src="'+img_name+'" style="border: 5px solid #ccc;min-height: 400px;max-width: 800px !important;max-height: 600px;">')
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
	/*Show modal */
function show_modal(modal_id) {
  $(modal_id).modal({
    backdrop: 'static',
    keyboard: false,
    show: true
  });
}
/*show toastr message */
function show_message(type, content) {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  toastr[type](content);
}

$(document).on('click', '#button_id', function(e) {
   e.preventDefault();
   var button_id = $(this).data('value');
   //var button_id = $(this).data('id');
   show_modal('#modal_id');
   return false;
});

function send_mail(member_no,email,subject,mailtext)
{
	//alert('***');
	//document.getElementById('capacitymsg').style.display = 'none';
		//$("#btnSubmit").prop('disabled', false);
	var member_no = $('#member_no').val();
	var email = $('#email').val(); 
	var subject = $('#subject').val(); 
	var mailtext = divide();
	$.ajax({
		type:"POST",
		url: site_url+"admin/kyc/Kyc/send_mail",
		data:{'member_no':member_no,'email':email,'subject':subject,'mailtext':mailtext},
		dataType: 'JSON',
		success:function(data){
			$('#message').html('');
			if(data != "")
			{  
				$('#success').css("display", "block");
				$('#success').html(data.success);
				$('#success').fadeOut(5000);
				$('#modal_id').modal('toggle');
				window.location.reload();
			}
			
		}	
	},"json");
	$(".loading").hide();
}
 function divide() { 
           // var txt; 
            txt = document.getElementById('mailtext').value;
            var text = txt.split("."); 
            var str = text.join('.\n'); 
            //document.write(str); 
						return str;
        } 
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
<script type="text/javascript">
	$(document).ready(function(){
		$(".textarea").wysihtml5();
	});
</script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/picturefill.min.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lightgallery.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-zoom.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/lg-hash.js')?>"></script>
<script src="<?php echo base_url('assets/dist/js/jquery.mousewheel.min.js')?>"></script>
     
<?php $this->load->view('admin/kyc/includes/footer');?>