<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'Image Process Global Link'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
    <br><br>
		<div id="wrapper">
      <div id="page-wrapperxx" class="container gray-bg">	
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12 text-center"><h2>Image Process Global Link</h2></div>
        </div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="post" action="javascript:void(0)" id="img_process_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="row">                      
                      <input type="hidden" id="data_lightbox_hidden" value="image_process_global">
                      <input type="hidden" id="data_lightbox_title_hidden" value="">
                      
                      <div class="col-xl-6 col-lg-6"><?php // Upload Photo ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="candidate_photo" class="form_label">Upload Photo <sup class="text-danger">*</sup></label>
                            <input type="file" name="candidate_photo" id="candidate_photo" class="form-control hide_input_file_cropper" required />

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_photo', 'image_process_global', 'Edit Photo')">Upload Photo</button>
                            </div>
                            <note class="form_note" id="candidate_photo_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="candidate_photo_cropper" id="candidate_photo_cropper" value="<?php echo set_value('candidate_photo_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                          </div>
                          
                          <div id="candidate_photo_preview" class="upload_img_preview pull-right">
                          <?php echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';?>
                          </div><div class="clearfix"></div>
                        </div>
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php // Upload Signature ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="candidate_sign" class="form_label">Upload Signature <sup class="text-danger">*</sup></label>
                            <input type="file" name="candidate_sign" id="candidate_sign" class="form-control hide_input_file_cropper" required />

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_sign', 'image_process_global', 'Edit Signature')">Upload Signature</button>
                            </div>
                            <note class="form_note" id="candidate_sign_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="candidate_sign_cropper" id="candidate_sign_cropper" value="<?php echo set_value('candidate_sign_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                          </div>
                          
                          <div id="candidate_sign_preview" class="upload_img_preview pull-right">
                          <?php echo '<i class="fa fa-picture-o" aria-hidden="true"></i>'; ?>
                          </div><div class="clearfix"></div>
                        </div>
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php // Upload Proof of Identity ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="id_proof_file" class="form_label">Upload Proof of Identity <sup class="text-danger">*</sup></label>
                            <input type="file" name="id_proof_file" id="id_proof_file" class="form-control hide_input_file_cropper" required />
                            
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('id_proof_file', 'image_process_global', 'Edit Proof of Identity')">Upload Proof of Identity</button>
                            </div>
                            <note class="form_note" id="id_proof_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="id_proof_file_cropper" id="id_proof_file_cropper" value="<?php echo set_value('id_proof_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?> 
                          </div>
                          
                          <div id="id_proof_file_preview" class="upload_img_preview pull-right">
                          <?php echo '<i class="fa fa-picture-o" aria-hidden="true"></i>'; ?>
                          </div><div class="clearfix"></div>
                        </div>
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php // Upload Qualification Certificate ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="qualification_certificate_file" class="form_label">Upload Qualification Certificate <sup class="text-danger">*</sup></label>                                                          
                            <input type="file" name="qualification_certificate_file" id="qualification_certificate_file" class="form-control hide_input_file_cropper" required />

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('qualification_certificate_file', 'image_process_global', 'Edit Qualification Certificate')">Upload Qualification Certificate</button>
                            </div>
                            <note class="form_note" id="qualification_certificate_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="qualification_certificate_file_cropper" id="qualification_certificate_file_cropper" value="<?php echo set_value('qualification_certificate_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                          </div>
                          
                          <div id="qualification_certificate_file_preview" class="upload_img_preview pull-right">
                          <?php echo '<i class="fa fa-picture-o" aria-hidden="true"></i>'; ?>
                          </div><div class="clearfix"></div>
                        </div>
                      </div>
                    </div>

                    <div class="hr-line-dashed"></div>										
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center mt-2" id="submit_btn_outer2">
                        <a href="<?php echo site_url('iibfbcbf/image_process_global'); ?>" class="btn btn-danger custom_right_add_new_btn">Reset</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
        <?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
  
    <?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
  	<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('page_name'=>'img_process_page')); ?>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>