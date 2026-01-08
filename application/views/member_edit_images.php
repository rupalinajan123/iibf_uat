<div class="content-wrapper"><!-- Content Wrapper. Contains page content -->
  <section class="content-header"><!-- Content Header (Page header) -->
    <h1>Ordinary Membership Registration Edit Page</h1>
    <!--<ol class="breadcrumb">
			<li><a href="<?php //echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class()); ?></a></li>
			<li class="active">Manage Users</li>
		</ol>-->
  </section>

  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data"
    action="<?php echo base_url() ?>home/editimages/">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info"><!-- Horizontal Form -->
            <div class="box-header with-border">
              <div style="float:right;">
                <a href="<?php echo base_url(); ?>home/profile/" class="btn btn-info">Back</a>
              </div>
            </div>

            <div class="box-body">
              <?php /* echo '123'.validation_errors(); */ ?>

              <?php if ($this->session->flashdata('error') != '')
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
              <?php }

              if ($custom_error != '')
              { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo $custom_error; ?>
                </div>
              <?php } ?>

              <div class="form-group">
                <label class="col-sm-3 control-labelx text-center">
                  <?php
                  if (is_file(get_img_name($this->session->userdata('regnumber'), 'p')))
                  { ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('regnumber'), 'p'); ?><?php echo '?' . time(); ?>" style="max-width:100%; max-height:100px;">
                  <?php }
                  else
                  { ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                  <?php } ?>
                  <p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Photo</p>
                </label>
                <input type="hidden" name="scannedphoto1_hidd" id="scannedphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'), 'p'); ?>">

                <label class="col-sm-3 control-labelx text-center">
                  <?php
                  if (is_file(get_img_name($this->session->userdata('regnumber'), 's')))
                  { ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('regnumber'), 's'); ?><?php echo '?' . time(); ?>" style="max-width:100%; max-height:100px;">
                  <?php }
                  else
                  { ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                  <?php
                  } ?>
                  <p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Signature</p>
                </label>
                <input type="hidden" name="scannedsignaturephoto1_hidd" id="scannedsignaturephoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'), 's'); ?>">

                <label class="col-sm-3 control-labelx text-center">
                  <?php
                  if (is_file(get_img_name($this->session->userdata('regnumber'), 'pr')))
                  { ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('regnumber'), 'pr'); ?><?php echo '?' . time(); ?>" style="max-width:100%; max-height:100px;">
                  <?php }
                  else
                  { ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                  <?php
                  } ?>
                  <p style="margin:10px 0 0 0;line-height: 18px;">Uploaded ID Proof</p>
                </label>
                <input type="hidden" name="idproofphoto1_hidd" id="idproofphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'), 'pr'); ?>">

                <label class="col-sm-3 control-labelx text-center">
                  <?php
                  if (is_file(get_img_name($this->session->userdata('regnumber'), 'declaration')))
                  { ?>
                    <img src="<?php echo base_url(); ?><?php echo get_img_name($this->session->userdata('regnumber'), 'declaration'); ?><?php echo '?' . time(); ?>" style="max-width:100%; max-height:100px;">
                  <?php
                  }
                  else
                  { ?>
                    <img src="<?php echo base_url(); ?>assets/images/default1.png<?php echo '?' . time(); ?>" height="100" width="100">
                  <?php
                  } ?>
                  <p style="margin:10px 0 0 0;line-height: 18px;">Uploaded Declaration</p>
                </label>
                <input type="hidden" name="declaration_hidd" id="declaration_hidd" value="<?php echo get_actual_img_name($this->session->userdata('regnumber'), 'declaration'); ?>">
              </div>
              
              <?php $data_lightbox_title_common = $member_info[0]['namesub'] != "" ? $member_info[0]['namesub'] . " " : ""; 
              $data_lightbox_title_common .= $member_info[0]['firstname'] != "" ? $member_info[0]['firstname'] . " " : ""; 
              $data_lightbox_title_common .= $member_info[0]['middlename'] != "" ? $member_info[0]['middlename'] . " " : ""; 
              $data_lightbox_title_common .= $member_info[0]['lastname'] != "" ? $member_info[0]['lastname'] . " " : ""; ?>

              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">

              <?php
              $show_submit_btn_flag = '0';
              if (!is_file(get_img_name($this->session->userdata('regnumber'), 'p'))) //Check if images are empty : pooja mane 28-12-2022  -->
              {
                $show_submit_btn_flag = '1';
              
                /* <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Upload your scanned Photograph <span style="color:#F00">**</span></label>
                  <div class="col-sm-5">
									<input type="file" class="form-control" name="scannedphoto" id="scannedphoto" autocomplete="off" onchange="validateFile(event, 'error_photo_size', 'image_upload_scanphoto_preview', '50kb')">
									<input type="hidden" id="hiddenphoto" name="hiddenphoto">
									<span class="note">Please Upload only .jpg, .jpeg Files upto 50KB</span></br>
									<span class="note-error" id="error_photo_size" class="note-error"></span>
									<br>
									<span class="photo_text" style="display:none;"></span>
									<span class="error"><?php //echo form_error('scannedphoto'); ?></span>
                  </div>
                  <img class="mem_reg_img" id="image_upload_scanphoto_preview" height="100" width="100" src="/assets/images/default1.png" />
								</div> */ ?>

                <div class="form-group mt-5 pt-3"><?php // Upload your scanned Photograph  ?>
                  <label for="scannedphoto" class="col-sm-4 control-label">Upload your scanned Photograph <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <div class="img_preview_input_outer pull-left">
                      <input type="file" name="scannedphoto" id="scannedphoto" class="form-control hide_input_file_cropper" <?php if ($member_info[0]['scannedphoto'] == "") { echo 'required'; } ?> />

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
              <?php } //END

              if (!is_file(get_img_name($this->session->userdata('regnumber'), 's'))) //Check if images are empty : pooja mane 28-12-2022  -->
              {
                $show_submit_btn_flag = '1';
                /* <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label"> Upload your scanned Signature Specimen <span style="color:#F00">**</span></label>
                  <div class="col-sm-5">
									<input type="file" class="form-control" name="scannedsignaturephoto" id="scannedsignaturephoto" autocomplete="off" onchange="validateFile(event, 'error_signature', 'image_upload_sign_preview', '50kb')">
									<input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
									<span class="note">Please Upload only .jpg, .jpeg Files upto 50KB</span></br>
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
                      <input type="file" name="scannedsignaturephoto" id="scannedsignaturephoto" class="form-control hide_input_file_cropper" <?php if ($member_info[0]['scannedsignaturephoto'] == "") { echo 'required'; } ?> />

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
              <?php } //END

              if (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr'))) //Check if images are empty : pooja mane 28-12-2022  -->
              {
                $show_submit_btn_flag = '1';
                /* <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Upload your id proof <span style="color:#F00">**</span></label>
                  <div class="col-sm-5">
									<input type="file" class="form-control" name="idproofphoto" id="idproofphoto" onchange="validateFile(event, 'error_idproof_size', 'image_upload_idproof_preview', '300kb')">
									<input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
									<span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span></br>
									<span id="error_idproof_size" class="note-error"></span>
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
                      <input type="file" name="idproofphoto" id="idproofphoto" class="form-control hide_input_file_cropper" <?php if ($member_info[0]['idproofphoto'] == "") { echo 'required'; } ?> />

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
              <?php } //END

              if (!is_file(get_img_name($this->session->userdata('regnumber'), 'declaration'))) //Check if images are empty : pooja mane 28-12-2022  -->              
              {
                $show_submit_btn_flag = '1';
                /* <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Upload declaration form <span style="color:#F00">**</span></label>
                  <div class="col-sm-5">
									<input type="file" class="form-control" name="declaration" id="declaration" onchange="validateFile(event, 'error_declaration', 'image_upload_declarationform_preview', '300kb')">
									<input type="hidden" id="hiddendeclaration" name="hiddendeclaration">
									<span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span></br>
									<span class="note-error" id="error_declaration"></span>
									<br>
									<div id="declaration_size"></div>
									<span class="declaration_proof_text" style="display:none;"></span>
									<span class="error"></span>
                  </div>
                  <img class="mem_reg_img" id="image_upload_declarationform_preview" height="100" width="100" src="/assets/images/default1.png" />
								</div> */ ?>

                <div class="form-group mt-5 pt-3"><?php // Upload declaration form ?>
                  <label for="declaration" class="col-sm-4 control-label">Upload declaration form <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <div class="img_preview_input_outer pull-left">
                      <input type="file" name="declaration" id="declaration" class="form-control hide_input_file_cropper" <?php if ($member_info[0]['declaration'] == "") { echo 'required'; } ?> />

                      <div class="image-input image-input-outline image-input-circle image-input-empty">
                        <div class="profile-progress"></div>
                        <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declaration', 'member_registration', 'Edit declaration')">Upload declaration</button>
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

                        <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="declaration" data-db_tbl_name="member_registration" data-title="Edit Declaration" title="Edit Declaration" alt="Edit Declaration"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                      <?php }
                      else
                      {
                        echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                      } ?>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              <?php } //END 
              ?>

              <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label pad_0"> Note <span style="color:#F00">**</span></label>
                <div class="col-sm-9">
                  1. Images format should be in JPG, JPEG.</br>
                  2. Preferred Image Dimension of Photograph should be 200(Width) * 230(Height) Pixel</br>
                  3. Preferred Image Dimension of Signature should be 140(Width) * 60(Height) Pixel</br>
                  4. Mandatorily upload the Declaration form signed(with stamped) by Branch Manager/HOD. <a style='color:#FF0000;' href=" <?php echo base_url() ?>uploads/declaration/DECLARATION.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Please click here to PRINT.</strong></a></br>
                </div>
              </div>
            </div>

            <?php if ($show_submit_btn_flag == '1')
            { ?>
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-4 text-center">
                  <input type="submit" class="btn btn-info btn_submit" name="btnSubmit" id="btnSubmit" value="Submit" onclick="validate_form()">
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
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'ordinary_edit_profile')); ?>

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
        declaration: { required: function(element) { return $("#declaration_cropper").val() == ''; }, check_valid_file: true, valid_file_format: '.jpg,.jpeg' },
			},
      messages: 
      { 
        scannedphoto: { required: "Please upload your scanned photograph", valid_file_format: "Please upload only .jpg, .jpeg files" },
        scannedsignaturephoto: { required: "Please upload your scanned signature", valid_file_format: "Please upload only .jpg, .jpeg files" },
        idproofphoto: { required: "Please upload your ID proof", valid_file_format: "Please upload only .jpg, .jpeg files" },
        declaration: { required: "Please upload your declaration", valid_file_format: "Please upload only .jpg, .jpeg files" },
			},
      errorPlacement: function(error, element) // For replace error 
      {
        if (element.attr("name") == "scannedphoto") { error.insertAfter("#scannedphoto_err"); } 
        else if (element.attr("name") == "scannedsignaturephoto") { error.insertAfter("#scannedsignaturephoto_err"); } 
        else if (element.attr("name") == "idproofphoto") { error.insertAfter("#idproofphoto_err"); } 
        else if (element.attr("name") == "declaration") { error.insertAfter("#declaration_err"); } 
        else { error.insertAfter(element); }
			},
      submitHandler: function(form) 
      {
        $("#page_loader").hide();
        swal({ title: "Please confirm", text: "If your uploaded documents are not in order, your request may be rejected.", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, I confirm", closeOnConfirm: true }, function() 
        {
          $("#page_loader").show();
					
          /* if(form_action == '1')
						{
            $("#submit_btn_outer1").html('<input type="button" class="btn btn-primary" id="submitFirst" name="submitFirst" value="Submit I " style="cursor:wait">');
						}
						else if(form_action == '2')
						{
					$("#submit_btn_outer2").html('<input type="button" class="btn btn-primary" id="submitAll" name="submitAll" value="<?php /* if($mode == 'Add') { echo "Submit II"; } else { echo "Update Candidate"; } ?>" style="cursor:wait"> <a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($batch_data[0]['batch_id'])); */ ?>">Back</a>');
				  } */
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
    <?php /* if($mode == 'Add') { ?> if($("#scannedphoto_cropper").val() != "") { scannedphoto_required_flag = false; } <?php }
    else if($mode == 'Update')  */
    { ?>
      var form_scannedphoto = '<?php echo $member_info[0]['scannedphoto'] ?>';
      if ($("#scannedphoto_cropper").val() != "" || form_scannedphoto != "") { scannedphoto_required_flag = false; }
    <?php } ?>
    
    $("#scannedphoto").rules("add", { required: scannedphoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var scannedsignaturephoto_required_flag = true;
    <?php /* if($mode == 'Add') { ?> if($("#scannedphoto_cropper").val() != "") { scannedphoto_required_flag = false; } <?php }
    else if($mode == 'Update')  */
    { ?>
      var form_scannedsignaturephoto = '<?php echo $member_info[0]['scannedsignaturephoto'] ?>';
      if ($("#scannedsignaturephoto_cropper").val() != "" || form_scannedsignaturephoto != "") { scannedsignaturephoto_required_flag = false; }
    <?php } ?>
    
    $("#scannedsignaturephoto").rules("add", { required: scannedsignaturephoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var idproofphoto_required_flag = true;
    <?php /* if($mode == 'Add') { ?> if($("#scannedphoto_cropper").val() != "") { scannedphoto_required_flag = false; } <?php }
    else if($mode == 'Update')  */
    { ?>
      var form_idproofphoto = '<?php echo $member_info[0]['idproofphoto'] ?>';
      if ($("#idproofphoto_cropper").val() != "" || form_idproofphoto != "") { idproofphoto_required_flag = false; }
    <?php } ?>
    
    $("#idproofphoto").rules("add", { required: idproofphoto_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    
    var declaration_required_flag = true;
    <?php /* if($mode == 'Add') { ?> if($("#scannedphoto_cropper").val() != "") { scannedphoto_required_flag = false; } <?php }
    else if($mode == 'Update')  */
    { ?>
      var form_declaration = '<?php echo $member_info[0]['declaration'] ?>';
      if ($("#declaration_cropper").val() != "" || form_declaration != "") { declaration_required_flag = false; }
    <?php } ?>
    
    $("#declaration").rules("add", { required: declaration_required_flag, check_valid_file: true, valid_file_format: '.jpg,.jpeg', });
    $("#page_loader").hide();
  }
</script>