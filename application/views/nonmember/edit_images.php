<style>
  .note { color: blue; font-size: small; }
  .note-error { color: rgb(185, 74, 72); font-size: small; }
</style>

<?php $fedai_array = array(1009);?>
<div class="content-wrapper"><!-- Content Wrapper. Contains page content -->  
  <section class="content-header"><!-- Content Header (Page header) -->
    <h1>Non-Membership Registration Edit Page</h1>
    <!--<ol class="breadcrumb">
			<li><a href="<?php //echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class()); ?></a></li>
			<li class="active">Manage Users</li>
		</ol>-->
	</section>
  
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>NonMember/editimages/">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <div style="float:right;"><a href="<?php echo base_url(); ?>NonMember/profile/" class="btn btn-info">Back</a></div>
						</div>
						
            <div class="box-body">
              <?php //echo validation_errors();
								if ($this->session->flashdata('error') != '') 
								{ ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo $this->session->flashdata('error'); ?>
								</div>
								<?php }
								
								if ($this->session->flashdata('success') != '') 
								{ ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                  <?php echo $this->session->flashdata('success'); ?>
								</div>
								<?php }
								
								if (validation_errors() != '') 
								{ ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo validation_errors(); ?>
								</div>
							<?php } ?>
							
              <div class="form-group">
                <label class="col-sm-2 control-labelx text-center"><?php /* Photo */ ?>
                  <?php if (is_file(get_img_name($this->session->userdata('nmregnumber'), 'p'))) 
										{ ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('nmregnumber'), 'p'); ?><?php echo '?' . time(); ?>" height="100" width="100">
										<?php
										}
										else 
										{ ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
										<?php
										} ?>
										<p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Photo</p>
								</label>
                <input type="hidden" name="scannedphoto1_hidd" id="scannedphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'), 'p'); ?>">
								
                <label class="col-sm-2 control-labelx text-center"><?php /* Signature */ ?>
                  <?php if (is_file(get_img_name($this->session->userdata('nmregnumber'), 's'))) 
										{ ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('nmregnumber'), 's'); ?><?php echo '?' . time(); ?>" height="100" width="100">
										<?php
										} 
										else 
										{ ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
										<?php
										} ?>
										<p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Signature</p>
								</label>
                <input type="hidden" name="scannedsignaturephoto1_hidd" id="scannedsignaturephoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'), 's'); ?>">
                
                <label class="col-sm-2 control-labelx text-center"><?php /* ID Proof */ ?>
                  <?php if (is_file(get_img_name($this->session->userdata('nmregnumber'), 'pr'))) 
										{ ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('nmregnumber'), 'pr'); ?><?php echo '?' . time(); ?>" height="100" width="100">
										<?php
										} 
										else 
										{ ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
										<?php
										} ?>
										<p style="margin:10px 0 0 0;line-height: 18px;">Uploaded ID Proof</p>
								</label>
                <input type="hidden" name="idproofphoto1_hidd" id="idproofphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'), 'pr'); ?>">
                
                <!--code added for fedai by pooja mane 2024-08-08-->
                <?php if(in_array($member_info[0]['excode'], $fedai_array)) /* Employment Proof */ 
									{ ?>
                  <label class="col-sm-2 control-labelx text-center">
                    <?php
											if (is_file(get_img_name($this->session->userdata('nmregnumber'), 'empr'))) 
                      { ?>
											<img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('nmregnumber'), 'empr'); ?><?php echo '?' . time(); ?>" height="100" width="100">
											<?php } 
                      else 
                      { ?>
											<img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
										<?php } ?>
										<p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Employment Proof</p>
									</label>
                  <input type="hidden" name="empidproofphoto1_hidd" id="empidproofphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'), 'empr'); ?>">
                  <!--code added by pooja mane end 2024-08-08-->
								<?php  } ?>
								
                <!--code added for fedai by pooja mane 2024-08-14-->
                <?php if(in_array($member_info[0]['excode'], $fedai_array)) /* Declaration */ 
									{ ?>
                  <label class="col-sm-2 control-labelx text-center">
                    <?php if (is_file(get_img_name($this->session->userdata('nmregnumber'), 'declaration'))) 
											{ ?>
                      <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('nmregnumber'), 'declaration'); ?><?php echo '?' . time(); ?>" height="100" width="100">
											<?php } 
											else 
											{ ?>
                      <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
										<?php } ?>
                    <p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Declaration Form</p>
									</label>
                  <input type="hidden" name="declaration1_hidd" id="declaration1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'), 'declaration'); ?>">
                  <!--code added by pooja mane end 2024-08-14-->
								<?php } ?>
								
                <?php if($member_info[0]['date_of_commenc_bc'] != '' && $member_info[0]['date_of_commenc_bc'] != '0000-00-00') /* Bank BC ID Card */ 
									{ ?>
                  <label class="col-sm-2 control-labelx text-center">
                    <?php
											if (is_file(get_img_name($this->session->userdata('nmregnumber'), 'bank_bc_id_card'))) 
                      { ?>
											<img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('nmregnumber'), 'bank_bc_id_card'); ?><?php echo '?' . time(); ?>" height="100" width="100">
											<?php } 
                      else 
                      { ?>
											<img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
										<?php } ?>
										<p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Bank BC ID Card</p>
									</label>
                  <input type="hidden" name="bank_bc_id_card1_hidd" id="bank_bc_id_card1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'), 'bank_bc_id_card'); ?>">
								<?php  } ?>
							</div><br>
              
              <?php $data_lightbox_title_common = $member_info[0]['namesub'] != "" ? $member_info[0]['namesub'] . " " : ""; 
								$data_lightbox_title_common .= $member_info[0]['firstname'] != "" ? $member_info[0]['firstname'] . " " : ""; 
								$data_lightbox_title_common .= $member_info[0]['middlename'] != "" ? $member_info[0]['middlename'] . " " : ""; 
							$data_lightbox_title_common .= $member_info[0]['lastname'] != "" ? $member_info[0]['lastname'] . " " : ""; ?>
              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
							
              <?php $show_submit_btn_flag = '0';
								if (!is_file(get_img_name($this->session->userdata('nmregnumber'), 'p'))) /* Photo */  // priyanka d - 28-dec-22 - added this condition to skip if image already uploaded
								{ 
									$show_submit_btn_flag = '1';
									/* <div class="form-group">
										<label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#F00">**</span></label>
										<div class="col-sm-5">
                    <input type="file" class="form-control" name="scannedphoto" id="scannedphoto" autocomplete="off" onchange="validateFile(event, 'error_photo_size', 'image_upload_scanphoto_preview', '50kb')">
                    <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                    <span class="note">Please Upload only .jpg, .jpeg Files having size less than 50KB</span></br>
                    <span class="note-error" id="error_photo_size" class="note-error"></span>
                    <br>
                    <span class="photo_text" style="display:none;"></span>
                    <div id="error_photo"></div>
                    <span class="error"><?php //echo form_error('scannedphoto'); ?></span>
										</div>
										<img class="mem_reg_img" id="image_upload_scanphoto_preview" height="100" width="100" src="/assets/images/default1.png" />
									</div> */ ?>
									
									<div class="form-group mt-5 pt-3"><?php // Upload your scanned Photograph  ?>
										<label for="scannedphoto" class="col-sm-4 control-label">Upload your scanned Photograph <span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<div class="img_preview_input_outer pull-left">
												<input type="file" name="scannedphoto" id="scannedphoto" class="form-controlx hide_input_file_cropper" <?php if ($member_info[0]['scannedphoto'] == "") { echo 'required'; } ?> />
												
												<div class="image-input image-input-outline image-input-circle image-input-empty">
													<div class="profile-progress"></div>
													<button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('scannedphoto', 'member_registration', 'Edit Photo')">Upload Scanned Photograph</button>
												</div>
												<note class="form_note" id="scannedphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
												
												<input type="hidden" name="scannedphoto_cropper" id="scannedphoto_cropper" value="<?php echo set_value('scannedphoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
												
												<?php if (form_error('scannedphoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scannedphoto'); ?></label> <?php } ?>
											</div>
											
											<div id="scannedphoto_preview" class="upload_img_preview pull-right">
												<?php
													$preview_scannedphoto = '';
													if (set_value('scannedphoto_cropper') != "")
													{
														$preview_scannedphoto = set_value('scannedphoto_cropper');
													}
													else if ($member_info[0]['scannedphoto'] != "")
													{
														$preview_scannedphoto = $member_info[0]['scannedphoto'];
														$preview_scannedphoto = base_url($scannedphoto_path . '/' . $preview_scannedphoto);
													}
													
													if ($preview_scannedphoto != "")
													{ ?>
													<a href="<?php echo $preview_scannedphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Photograph - '; echo $data_lightbox_title_common; ?>">
														<img src="<?php echo $preview_scannedphoto . "?" . time(); ?>">
													</a>
													
													<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scannedphoto" data-db_tbl_name="member_registration" data-title="Edit Photo" title="Edit Photo" alt="Edit Photo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
													<?php }
													else
													{
														echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
													} ?>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
									<?php }
									
									if (!is_file(get_img_name($this->session->userdata('nmregnumber'), 's'))) /* Signature */ // priyanka d - 28-dec-22 - added this condition to skip if image already uploaded
									{ 
										$show_submit_btn_flag = '1';
										/* <div class="form-group">
											<label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen <span style="color:#F00">**</span></label>
											<div class="col-sm-5">
											<input type="file" class="form-control" name="scannedsignaturephoto" id="scannedsignaturephoto" autocomplete="off" onchange="validateFile(event, 'error_signature', 'image_upload_sign_preview', '50kb')">
											<input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
											<span class="note">Please Upload only .jpg, .jpeg Files having size less than 50KB</span></br>
											<span id="error_signature" class="note-error"></span>
											<br>
											<div id="error_signature_size"></div>
											<span class="signature_text" style="display:none;"></span>
											<span class="error"><?php //echo form_error('scannedsignaturephoto'); ?></span>
											</div>
											<img class="mem_reg_img" id="image_upload_sign_preview" height="100" width="100" src="/assets/images/default1.png" />
										</div> */ ?>
										
										<div class="form-group mt-5 pt-3"><?php // Upload your scanned Signature Specimen ?>
											<label for="scannedsignaturephoto" class="col-sm-4 control-label">Upload your scanned Signature Specimen <span style="color:#F00">*</span></label>
											<div class="col-sm-6">
												<div class="img_preview_input_outer pull-left">
													<input type="file" name="scannedsignaturephoto" id="scannedsignaturephoto" class="form-controlx hide_input_file_cropper" <?php if ($member_info[0]['scannedsignaturephoto'] == "") { echo 'required'; } ?> />
													
													<div class="image-input image-input-outline image-input-circle image-input-empty">
														<div class="profile-progress"></div>
														<button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('scannedsignaturephoto', 'member_registration', 'Edit Signature')">Upload Scanned Signatute</button>
													</div>
													<note class="form_note" id="scannedsignaturephoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
													
													<input type="hidden" name="scannedsignaturephoto_cropper" id="scannedsignaturephoto_cropper" value="<?php echo set_value('scannedsignaturephoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
													
													<?php if (form_error('scannedsignaturephoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scannedsignaturephoto'); ?></label> <?php } ?>
												</div>
												
												<div id="scannedsignaturephoto_preview" class="upload_img_preview pull-right">
													<?php
														$preview_scannedsignaturephoto = '';
														if (set_value('scannedsignaturephoto_cropper') != "")
														{
															$preview_scannedsignaturephoto = set_value('scannedsignaturephoto_cropper');
														}
														else if ($member_info[0]['scannedsignaturephoto'] != "")
														{
															$preview_scannedsignaturephoto = $member_info[0]['scannedsignaturephoto'];
															$preview_scannedsignaturephoto = base_url($scannedsignaturephoto_path . '/' . $preview_scannedsignaturephoto);
														}
														
														if ($preview_scannedsignaturephoto != "")
														{ ?>
														<a href="<?php echo $preview_scannedsignaturephoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Signature - '; echo $data_lightbox_title_common; ?>">
															<img src="<?php echo $preview_scannedsignaturephoto . "?" . time(); ?>">
														</a>
														
														<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scannedsignaturephoto" data-db_tbl_name="member_registration" data-title="Edit Signature" title="Edit Signature" alt="Edit Signature"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
														<?php }
														else
														{
															echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
														} ?>
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
										<?php }
										
										if (!is_file(get_img_name($this->session->userdata('nmregnumber'), 'pr'))) /* ID Proof */ // priyanka d - 28-dec-22 - added this condition to skip if image already uploaded
										{ 
											$show_submit_btn_flag = '1';
											/* <div class="form-group">
												<label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#F00">**</span></label>
												<div class="col-sm-5">
												<input type="file" class="form-control" name="idproofphoto" id="idproofphoto" onchange="validateFile(event, 'error_idproof_size', 'image_upload_idproof_preview', '300kb')">
												<input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
												<span class="note">Please Upload only .jpg, .jpeg Files having size less than 300KB</span></br>
												<span id="error_idproof_size" class="note-error"></span>
												<span id="error_dob"></span>
												<br>
												<div id="error_dob_size"></div>
												<span class="dob_proof_text" style="display:none;"></span>
												<span class="error"><?php //echo form_error('idproofphoto'); ?></span>
												</div>
												<img class="mem_reg_img" id="image_upload_idproof_preview" height="100" width="100" src="/assets/images/default1.png" />
											</div> */ ?>
											
											<div class="form-group mt-5 pt-3"><?php // Upload your id proof ?>
												<label for="idproofphoto" class="col-sm-4 control-label">Upload your ID proof <span style="color:#F00">*</span></label>
												<div class="col-sm-6">
													<div class="img_preview_input_outer pull-left">
														<input type="file" name="idproofphoto" id="idproofphoto" class="form-controlx hide_input_file_cropper" <?php if ($member_info[0]['idproofphoto'] == "") { echo 'required'; } ?> />
														
														<div class="image-input image-input-outline image-input-circle image-input-empty">
															<div class="profile-progress"></div>
															<button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('idproofphoto', 'member_registration', 'Edit ID Proof')">Upload ID proof</button>
														</div>
														<note class="form_note" id="idproofphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
														
														<input type="hidden" name="idproofphoto_cropper" id="idproofphoto_cropper" value="<?php echo set_value('idproofphoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
														
														<?php if (form_error('idproofphoto') != "")
														{ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('idproofphoto'); ?></label> <?php } ?>
													</div>
													
													<div id="idproofphoto_preview" class="upload_img_preview pull-right">
														<?php
															$preview_idproofphoto = '';
															if (set_value('idproofphoto_cropper') != "")
															{
																$preview_idproofphoto = set_value('idproofphoto_cropper');
															}
															else if ($member_info[0]['idproofphoto'] != "")
															{
																$preview_idproofphoto = $member_info[0]['idproofphoto'];
																$preview_idproofphoto = base_url($idproofphoto_path . '/' . $preview_idproofphoto);
															}
															
															if ($preview_idproofphoto != "")
															{ ?>
															<a href="<?php echo $preview_idproofphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'ID Proof - '; echo $data_lightbox_title_common; ?>">
																<img src="<?php echo $preview_idproofphoto . "?" . time(); ?>">
															</a>
															
															<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="idproofphoto" data-db_tbl_name="member_registration" data-title="Edit ID Proof" title="Edit ID Proof" alt="Edit ID Proof"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
															<?php }
															else
															{
																echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
															} ?>
													</div>
													<div class="clearfix"></div>
												</div>
											</div>
											<?php }
											
											if(in_array($member_info[0]['excode'], $fedai_array)) /* Employment Proof */  /* START : Code Added by pooja mane 2024-08-06 */
											{
												if (!is_file(get_img_name($this->session->userdata('nmregnumber'), 'empr'))) 
												{ 
													$show_submit_btn_flag = '1';
													/* <div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">Upload your employment proof <span style="color:#F00">**</span></label>
														<div class="col-sm-5">
														<input type="file" class="form-control" name="empidproofphoto" id="empidproofphoto" onchange="validateFile(event, 'error_empidproof_size', 'image_upload_empidproof_preview', '300kb')">
														<input type="hidden" id="hiddenempidproofphoto" name="hiddenempidproofphoto">
														<span class="note">Please Upload only .jpg, .jpeg Files having size less than 300KB</span></br>
														<span id="error_empidproof_size" class="note-error"></span>
														<span id="error_dob"></span>
														<br>
														<div id="error_dob_size"></div>
														<span class="dob_proof_text" style="display:none;"></span>
														<span class="error"><?php //echo form_error('empidproofphoto'); ?></span>
														</div>
														<img class="mem_reg_img" id="image_upload_empidproof_preview" height="100" width="100" src="/assets/images/default1.png" />
													</div> */ ?>
													
													<div class="form-group mt-5 pt-3"><?php // Upload your employment proof ?>
														<label for="empidproofphoto" class="col-sm-4 control-label">Upload your employment proof <span style="color:#F00">*</span></label>
														<div class="col-sm-6">
															<div class="img_preview_input_outer pull-left">
																<input type="file" name="empidproofphoto" id="empidproofphoto" class="form-controlx hide_input_file_cropper" <?php if ($member_info[0]['empidproofphoto'] == "") { echo 'required'; } ?> />
																
																<div class="image-input image-input-outline image-input-circle image-input-empty">
																	<div class="profile-progress"></div>
																	<button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('empidproofphoto', 'member_registration', 'Edit Employment Proof')">Upload employment proof</button>
																</div>
																<note class="form_note" id="empidproofphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
																
																<input type="hidden" name="empidproofphoto_cropper" id="empidproofphoto_cropper" value="<?php echo set_value('empidproofphoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
																
																<?php if (form_error('empidproofphoto') != "")
																{ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('empidproofphoto'); ?></label> <?php } ?>
															</div>
															
															<div id="empidproofphoto_preview" class="upload_img_preview pull-right">
																<?php
																	$preview_empidproofphoto = '';
																	if (set_value('empidproofphoto_cropper') != "")
																	{
																		$preview_empidproofphoto = set_value('empidproofphoto_cropper');
																	}
																	else if ($member_info[0]['empidproofphoto'] != "")
																	{
																		$preview_empidproofphoto = $member_info[0]['empidproofphoto'];
																		$preview_empidproofphoto = base_url($empidproofphoto_path . '/' . $preview_empidproofphoto);
																	}
																	
																	if ($preview_empidproofphoto != "")
																	{ ?>
																	<a href="<?php echo $preview_empidproofphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Employment Proof - '; echo $data_lightbox_title_common; ?>">
																		<img src="<?php echo $preview_empidproofphoto . "?" . time(); ?>">
																	</a>
																	
																	<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="empidproofphoto" data-db_tbl_name="member_registration" data-title="Edit Employment Proof" title="Edit Employment Proof" alt="Edit Employment Proof"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
																	<?php }
																	else
																	{
																		echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
																	} ?>
															</div>
															<div class="clearfix"></div>
														</div>
													</div>
													<?php } 
											} /* END : Code Added by pooja mane 2024-08-06 */
											
											if(in_array($member_info[0]['excode'], $fedai_array)) /* Declaration */ /* START : Code Added by pooja mane 2024-08-14 */
											{
												if (!is_file(get_img_name($this->session->userdata('nmregnumber'), 'declaration'))) //// priyanka d - 28-dec-22 - added this condition to skip if image already uploaded
												{
													$show_submit_btn_flag = '1';
													/* <div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">Upload your employment proof <span style="color:#F00">**</span></label>
														<div class="col-sm-5">
														<input type="file" class="form-control" name="declaration" id="declaration" onchange="validateFile(event, 'error_declaration_size', 'image_upload_declaration_preview', '300kb')">
														<input type="hidden" id="hiddendeclaration" name="hiddendeclaration">
														<span class="note">Please Upload only .jpg, .jpeg Files having size less than 300KB</span></br>
														<span id="error_declaration_size" class="note-error"></span>
														<span id="error_dob"></span>
														<br>
														<div id="error_dob_size"></div>
														<span class="dob_proof_text" style="display:none;"></span>
														<span class="error"><?php //echo form_error('declarationphoto'); ?></span>
														</div>
														<img class="mem_reg_img" id="image_upload_declaration_preview" height="100" width="100" src="/assets/images/default1.png" />
													</div>  */?>
													
													<div class="form-group mt-5 pt-3"><?php // Upload your Declaration ?>
														<label for="declaration" class="col-sm-4 control-label">Upload your Declaration <span style="color:#F00">*</span></label>
														<div class="col-sm-6">
															<div class="img_preview_input_outer pull-left">
																<input type="file" name="declaration" id="declaration" class="form-controlx hide_input_file_cropper" <?php if ($member_info[0]['declaration'] == "") { echo 'required'; } ?> />
																
																<div class="image-input image-input-outline image-input-circle image-input-empty">
																	<div class="profile-progress"></div>
																	<button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declaration', 'member_registration', 'Edit Declaration')">Upload Declaration</button>
																</div>
																<note class="form_note" id="declaration_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
																
																<input type="hidden" name="declaration_cropper" id="declaration_cropper" value="<?php echo set_value('declaration_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
																
																<?php if (form_error('declaration') != "")
																{ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declaration'); ?></label> <?php } ?>
															</div>
															
															<div id="declaration_preview" class="upload_img_preview pull-right">
																<?php
																	$preview_declaration = '';
																	if (set_value('declaration_cropper') != "")
																	{
																		$preview_declaration = set_value('declaration_cropper');
																	}
																	else if ($member_info[0]['declaration'] != "")
																	{
																		$preview_declaration = $member_info[0]['declaration'];
																		$preview_declaration = base_url($declaration_path . '/' . $preview_declaration);
																	}
																	
																	if ($preview_declaration != "")
																	{ ?>
																	<a href="<?php echo $preview_declaration . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - '; echo $data_lightbox_title_common; ?>">
																		<img src="<?php echo $preview_declaration . "?" . time(); ?>">
																	</a>
																	
																	<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="declaration" data-db_tbl_name="member_registration" data-title="Edit declaration" title="Edit declaration" alt="Edit declaration"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
																	<?php }
																	else
																	{
																		echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
																	} ?>
															</div>
															<div class="clearfix"></div>
														</div>
													</div>
													<?php } 
											} /* END : Code Added by pooja mane 2024-08-14  */
											
											if($member_info[0]['date_of_commenc_bc'] != '' && $member_info[0]['date_of_commenc_bc'] != '0000-00-00') /* Bank BC ID Card */
											{
												if (!is_file(get_img_name($this->session->userdata('nmregnumber'), 'bank_bc_id_card'))) 
												{ 
												$show_submit_btn_flag = '1'; ?>
												
												<div class="form-group mt-5 pt-3"><?php // Upload Bank BC ID Card ?>
													<label for="bank_bc_id_card" class="col-sm-4 control-label">Upload your Bank BC ID Card <span style="color:#F00">*</span></label>
													<div class="col-sm-6">
														<div class="img_preview_input_outer pull-left">
															<input type="file" name="bank_bc_id_card" id="bank_bc_id_card" class="form-controlx hide_input_file_cropper" <?php if ($member_info[0]['bank_bc_id_card'] == "") { echo 'required'; } ?> />
															
															<div class="image-input image-input-outline image-input-circle image-input-empty">
																<div class="profile-progress"></div>
																<button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('bank_bc_id_card', 'member_registration', 'Edit Bank BC ID Card')">Upload Bank BC ID Card</button>
															</div>
															<note class="form_note" id="bank_bc_id_card_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
															
															<input type="hidden" name="bank_bc_id_card_cropper" id="bank_bc_id_card_cropper" value="<?php echo set_value('bank_bc_id_card_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
															
															<?php if (form_error('bank_bc_id_card') != "")
															{ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_bc_id_card'); ?></label> <?php } ?>
														</div>
														
														<div id="bank_bc_id_card_preview" class="upload_img_preview pull-right">
															<?php
																$preview_bank_bc_id_card = '';
																if (set_value('bank_bc_id_card_cropper') != "")
																{
																	$preview_bank_bc_id_card = set_value('bank_bc_id_card_cropper');
																}
																else if ($member_info[0]['bank_bc_id_card'] != "")
																{
																	$preview_bank_bc_id_card = $member_info[0]['bank_bc_id_card'];
																	$preview_bank_bc_id_card = base_url($bank_bc_id_card_path . '/' . $preview_bank_bc_id_card);
																}
																
																if ($preview_bank_bc_id_card != "")
																{ ?>
																<a href="<?php echo $preview_bank_bc_id_card . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Bank BC ID Card - '; echo $data_lightbox_title_common; ?>">
																	<img src="<?php echo $preview_bank_bc_id_card . "?" . time(); ?>">
																</a>
																
																<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="bank_bc_id_card" data-db_tbl_name="member_registration" data-title="Edit Bank BC ID Card" title="Edit Bank BC ID Card" alt="Edit Bank BC ID Card"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
																<?php }
																else
																{
																	echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
																} ?>
														</div>
														<div class="clearfix"></div>
													</div>
												</div>
												<?php } 
											} ?>
											
											<div class="form-group">
												<label for="roleid" class="col-sm-2 control-label"> Note <span style="color:#F00">**</span></label>
												<div class="col-sm-9">
													1. Images format should be in JPG, JPEG.</br>
													2. Preferred Image Dimension of Photograph should be 200(Width) * 230(Height) Pixel</br>
													3. Preferred Image Dimension of Signature should be 140(Width) * 60(Height) Pixel</br>
													4. Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum 8KB and maximum 300KB.</br>
												</div>
											</div>
						</div>
            
            <?php if ($show_submit_btn_flag == '1')
							{ ?>
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="validate_form()">
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>

<style>
  .pad_0 {
	padding-top: 0 !important;
  }
</style>

<?php $this->load->view('iibfbcbf/common/inc_lightbox_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_sweet_alert_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'non_member_edit_profile')); ?>

<script type="text/javascript">
  //START : JQUERY VALIDATION SCRIPT 
  function validate_input(input_id) { $("#" + input_id).valid(); }
  $(document).ready(function() 
  {
    var form = $("#usersAddForm").validate(
    {
      onkeyup: function(element) { $(element).valid(); },
      rules: 
      {
        scannedphoto: { required: function(element) { return $("#scannedphoto_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
        scannedsignaturephoto: { required: function(element) { return $("#scannedsignaturephoto_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
        idproofphoto: { required: function(element) { return $("#idproofphoto_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
        empidproofphoto: { required: function(element) { return $("#empidproofphoto_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
        declaration: { required: function(element) { return $("#declaration_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
        bank_bc_id_card: { required: function(element) { return $("#bank_bc_id_card_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
			},
      messages: 
      { 
        scannedphoto: { required: "Please upload your scanned photograph", valid_file_format: "Please upload only .jpg, .jpeg files" },
        scannedsignaturephoto: { required: "Please upload your scanned signature", valid_file_format: "Please upload only .jpg, .jpeg files" },
        idproofphoto: { required: "Please upload your ID proof", valid_file_format: "Please upload only .jpg, .jpeg files" },
        empidproofphoto: { required: "Please upload your employment proof", valid_file_format: "Please upload only .jpg, .jpeg files" },
        declaration: { required: "Please upload your declaration", valid_file_format: "Please upload only .jpg, .jpeg files" },
        bank_bc_id_card: { required: "Please upload your bank bc id card", valid_file_format: "Please upload only .jpg, .jpeg files" },
			},
      errorPlacement: function(error, element) // For replace error 
      {
        if (element.attr("name") == "scannedphoto") { error.insertAfter("#scannedphoto_err"); } 
        else if (element.attr("name") == "scannedsignaturephoto") { error.insertAfter("#scannedsignaturephoto_err"); } 
        else if (element.attr("name") == "idproofphoto") { error.insertAfter("#idproofphoto_err"); } 
        else if (element.attr("name") == "empidproofphoto") { error.insertAfter("#empidproofphoto_err"); } 
        else if (element.attr("name") == "declaration") { error.insertAfter("#declaration_err"); } 
        else if (element.attr("name") == "bank_bc_id_card") { error.insertAfter("#bank_bc_id_card_err"); } 
        else { error.insertAfter(element); }
			},
      submitHandler: function(form) 
      {
        $("#page_loader").hide();
        swal({ title: "Please confirm", text: "If your uploaded documents are not in order, your request may be rejected.", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, I confirm", closeOnConfirm: true }, function() 
        {
          $("#page_loader").show();
          form.submit();
				});
			}
		});
	});
  //END : JQUERY VALIDATION SCRIPT  
	
  function validate_form() 
  {
    $("#page_loader").show();
    
    var scannedphoto_required_flag = true;
    var form_scannedphoto = '<?php echo $member_info[0]['scannedphoto'] ?>';
    if ($("#scannedphoto_cropper").val() != "" || form_scannedphoto != "") { scannedphoto_required_flag = false; }  
    $("#scannedphoto").rules("add", { required: scannedphoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var scannedsignaturephoto_required_flag = true;
    var form_scannedsignaturephoto = '<?php echo $member_info[0]['scannedsignaturephoto'] ?>';
    if ($("#scannedsignaturephoto_cropper").val() != "" || form_scannedsignaturephoto != "") { scannedsignaturephoto_required_flag = false; }    
    $("#scannedsignaturephoto").rules("add", { required: scannedsignaturephoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var idproofphoto_required_flag = true;
    var form_idproofphoto = '<?php echo $member_info[0]['idproofphoto'] ?>';
    if ($("#idproofphoto_cropper").val() != "" || form_idproofphoto != "") { idproofphoto_required_flag = false; } 
    $("#idproofphoto").rules("add", { required: idproofphoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var empidproofphoto_required_flag = true;
    var form_empidproofphoto = '<?php echo $member_info[0]['empidproofphoto'] ?>';
    if ($("#empidproofphoto_cropper").val() != "" || form_empidproofphoto != "") { empidproofphoto_required_flag = false; }   
    $("#empidproofphoto").rules("add", { required: empidproofphoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var declaration_required_flag = true;
    var form_declaration = '<?php echo $member_info[0]['declaration'] ?>';
    if ($("#declaration_cropper").val() != "" || form_declaration != "") { declaration_required_flag = false; }   
    $("#declaration").rules("add", { required: declaration_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var bank_bc_id_card_required_flag = true;
    var form_bank_bc_id_card = '<?php echo $member_info[0]['bank_bc_id_card'] ?>';
    if ($("#bank_bc_id_card_cropper").val() != "" || form_bank_bc_id_card != "") { bank_bc_id_card_required_flag = false; }
    $("#bank_bc_id_card").rules("add", { required: bank_bc_id_card_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    $("#page_loader").hide();
	}
</script>