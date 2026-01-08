<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('case_writing_competition/inc_header'); ?>

    <!-- Include FancyBox CSS & JS --> 
    <link href="<?php echo (base_url('assets/ncvet/css/fancybox.css')); ?>" rel="stylesheet"> 
    <script src="<?php echo (base_url('assets/ncvet/js/fancybox.umd.js')); ?>"></script>
    <style type="text/css">
      /* Custom position for FancyBox */
    .custom-fancybox .fancybox__container {
      align-items: flex-start !important;  /* push to top */
      justify-content: center;             /* keep horizontally centered */
    }

    .custom-fancybox .fancybox__content {
      width: 100% !important;
      height: 100% !important;
      /*margin-left: 250px;*/ 
    }

    .fancybox__caption {
      position: absolute;
      top: 93%;
      left: 27%;
      bottom: auto;
      right: auto;
      transform: translateY(-50%);
      text-align: left;
      width: auto;
      /*background: rgba(0,0,0,0.6);*/
      /*color: #fff;*/
      padding: 6px 10px;
      border-radius: 4px;
    }

    .fancybox__image{
      margin-left: 420px;
    }
    </style>
</head>

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
    <?php $this->load->view('case_writing_competition/inc_navbar'); ?>

    <div class="container">
      <section class="content">

        <section class="content-header">
          <h1 class="register">IIBF’s Case Study Writing Competition Form - 2025</h1><br />
          <div style="display:none"><?php echo get_ip_address(); ?></div>
        </section>

        <div class="col-md-12">
          <div class="row">
            <?php if ($this->session->flashdata('error') != '') { ?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
              </div>
            <?php }

            if ($this->session->flashdata('success') != '') { ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
              </div>
            <?php } ?>

            <div class="box box-info">
              <form class="form-vertical" name="usersAddForm" id="usersAddForm" method="post" action="<?php echo site_url('case_writing_competition/registration/index/'); ?>" enctype="multipart/form-data" autocomplete="off">

                
                  <div class="form-group col-md-12">
                    <!-- <note>Note:Please fill Form in BLOCK Letters</note> -->
                    <label for="case_study_title" class="control-label">Title of the Case Study<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="200" class="form-control custom_input_formfields" id="case_study_title" name="case_study_title" placeholder="Title of the Case Study" required value="<?php echo (isset($case_study_user_info['case_study_title']) && $case_study_user_info['case_study_title'] != "" ? $case_study_user_info['case_study_title'] : set_value('case_study_title') ); ?>">
                    <?php if (form_error('case_study_title') != "") { ?><label class="error"><?php echo form_error('case_study_title'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12"> 
                    <label for="case_study_area" class="control-label">Area of the Case Study<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="200" class="form-control custom_input_formfields" id="case_study_area" name="case_study_area" placeholder="Area of the Case Study" required value="<?php echo (isset($case_study_user_info['case_study_area']) && $case_study_user_info['case_study_area'] != "" ? $case_study_user_info['case_study_area'] : set_value('case_study_area') ); ?>">
                    <?php if (form_error('case_study_area') != "") { ?><label class="error"><?php echo form_error('case_study_area'); ?></label> <?php } ?>
                  </div>

                  <?php 
                  $case_study_level_id = (isset($case_study_user_info['case_study_level_id']) && $case_study_user_info['case_study_level_id'] != "" ? $case_study_user_info['case_study_level_id'] : set_value('case_study_level_id') );
                  $case_study_level_desc_id = (isset($case_study_user_info['case_study_level_desc_id']) && $case_study_user_info['case_study_level_desc_id'] != "" ? $case_study_user_info['case_study_level_desc_id'] : set_value('case_study_level_desc_id') );
                  ?>
                  <div class="form-group col-md-6">
                    <label for="case_study_level_id" class="control-label">Case Study entered in the scheme (I – V)<span style="color:#F00">*</span></label>
                    <select class="form-control form2_element custom_input_formfields" id="case_study_level_id" name="case_study_level_id" required onchange="get_scheme_ajax()">
                      <option value="">Select</option>
                      <?php if (count($case_study_comp_level) > 0) { 
                        foreach ($case_study_comp_level as $row1) {  ?>
                          <option value="<?php echo $row1['id']; ?>" <?php echo ($case_study_level_id != "" && $case_study_level_id == $row1['id'] ? 'selected' : ''); ?>><?php echo $row1['level']; ?></option>
                      <?php }
                      } ?>
                    </select> 
											<?php if(form_error('case_study_level_id')!=""){ 
                      ?><label class="error"><?php echo form_error('case_study_level_id'); ?></label> <?php }  ?> 
                  </div>

                  <div class="form-group col-md-6">
                    <label for="case_study_level_desc_id" class="control-label">Scheme Name<span style="color:#F00">*</span></label>
                    <select class="form-control case_study_level_desc_id form2_element custom_input_formfields" id="case_study_level_desc_id" name="case_study_level_desc_id" required>
                      <option value="">Select Scheme Name</option>
                    </select>

                    <?php if (form_error('case_study_level_desc_id') != "") { ?><label class="error"><?php echo form_error('case_study_level_desc_id'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="name_of_author" class="control-label">Name of the Author<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" required class="form-control custom_input_formfields" id="name_of_author" name="name_of_author" maxlength="150" placeholder="Name of the Author" value="<?php echo (isset($case_study_user_info['name_of_author']) && $case_study_user_info['name_of_author'] != "" ? $case_study_user_info['name_of_author'] : set_value('name_of_author') ); ?>">
                    <?php if (form_error('name_of_author') != "") { ?><label class="error"><?php echo form_error('name_of_author'); ?></label> <?php } ?>
                  </div> 

                  <div class="form-group col-md-6">
                    <label for="designation" class="control-label">Designation<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" class="form-control custom_input_formfields" id="designation" name="designation" maxlength="150" placeholder="Designation" value="<?php echo (isset($case_study_user_info['designation']) && $case_study_user_info['designation'] != "" ? $case_study_user_info['designation'] : set_value('designation') ); ?>">
                    <?php if (form_error('designation') != "") { ?><label class="error"><?php echo form_error('designation'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="employer" class="control-label">Employer<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" class="form-control custom_input_formfields" id="employer" name="employer" maxlength="150" placeholder="Employer" value="<?php echo (isset($case_study_user_info['employer']) && $case_study_user_info['employer'] != "" ? $case_study_user_info['employer'] : set_value('employer') ); ?>">
                    <?php if (form_error('employer') != "") { ?><label class="error"><?php echo form_error('employer'); ?></label> <?php } ?>
                  </div>

                   <div class="form-group col-md-6">
                    <label for="mobile_no" class="control-label">Mobile No.<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" onkeypress="return isNumber(event)" required maxlength="10" id="mobile_no" name="mobile_no" placeholder="Mobile No." value="<?php echo (isset($case_study_user_info['mobile_no']) && $case_study_user_info['mobile_no'] != "" ? $case_study_user_info['mobile_no'] : set_value('mobile_no') ); ?>">
                    <?php if (form_error('mobile_no') != "") { ?><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="email_id" class="control-label">Email ID<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" id="email_id" name="email_id" placeholder="Email ID" maxlength="150" value="<?php echo (isset($case_study_user_info['email_id']) && $case_study_user_info['email_id'] != "" ? $case_study_user_info['email_id'] : set_value('email_id') ); ?>">
                    <?php if (form_error('email_id') != "") { ?><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
                  </div>
                 
                  <div class="form-group col-md-6">
                    <label for="qualifications" class="control-label">Qualifications<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" class="form-control custom_input_formfields" id="qualifications" name="qualifications" maxlength="150" placeholder="Qualifications" value="<?php echo (isset($case_study_user_info['qualifications']) && $case_study_user_info['qualifications'] != "" ? $case_study_user_info['qualifications'] : set_value('qualifications') ); ?>">
                    <?php if (form_error('qualifications') != "") { ?><label class="error"><?php echo form_error('qualifications'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="other_info" class="control-label">Any other information<span style="color:#F00"></span></label>
                    <textarea style="resize: none;" maxlength="500" class="form-control custom_input_formfields" id="other_info" name="other_info" placeholder="Any other information"><?php echo (isset($case_study_user_info['other_info']) && $case_study_user_info['other_info'] != "" ? $case_study_user_info['other_info'] : set_value('other_info') ); ?></textarea>
                    <?php if (form_error('other_info') != "") { ?><label class="error"><?php echo form_error('other_info'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="upload_case_study_doc" class="control-label">Upload Case Study (Word/PDF file) - Around 15-20 Pages<span style="color:#F00">*</span></label>
                    <input type="file" class="form-control custom_input_formfields" id="upload_case_study_doc" name="upload_case_study_doc" placeholder="Upload Case Study (Word/PDF file)" value="<?php echo set_value('upload_case_study_doc'); ?>" onchange="validate_file('upload_case_study_doc')" accept=".doc,.docx,.pdf"> 
                    <note>Note: Please upload only .doc, .docx, or .pdf files with a maximum size of 2 MB.</note>
                    <?php if (form_error('upload_case_study_doc') != "") { ?><label class="error"><?php echo form_error('upload_case_study_doc'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="upload_signature" class="control-label">Upload Signature<span style="color:#F00">*</span></label>
                    <input type="file" class="form-control custom_input_formfields" id="upload_signature" name="upload_signature" placeholder="Upload GST No." value="<?php echo set_value('upload_signature'); ?>" onchange="validate_file('upload_signature')" accept=".png,.jpeg,.jpg">
                    <note>Note: Please upload only pdf, jpg, jpeg, png extension files with size 8kb to 200kb </note>
                    <?php if (form_error('upload_signature') != "") { ?><label class="error"><?php echo form_error('upload_signature'); ?></label> <?php } ?>
                  </div> 

                    <div class="form-group col-md-6">  
                    <?php 
                    $upload_case_study_doc_file_exist = '';
                    $upload_signature_file_exist = '';
                    if(isset($case_study_user_info['upload_case_study_doc']) && $case_study_user_info['upload_case_study_doc'] != "") { 
                        $case_study_doc_file = $case_study_user_info['upload_case_study_doc'];
                        $case_study_doc_path = FCPATH . 'uploads/case_study_comp_registration/'.date("Ymd").'/' . $case_study_doc_file; // server path
                        $case_study_doc_url  = base_url('uploads/case_study_comp_registration/'.date("Ymd").'/' . $case_study_doc_file) . '?' . time();

                        if(file_exists($case_study_doc_path)) {

                            $upload_case_study_doc_file_exist = $case_study_user_info['upload_case_study_doc'];
                            ?>
                            <input type="hidden" name="upload_case_study_doc_file_exist" id="upload_case_study_doc_file_exist" value="<?php echo $upload_case_study_doc_file_exist; ?>">
                            <?php
                            $extension = strtolower(pathinfo($case_study_doc_file, PATHINFO_EXTENSION));

                            if($extension === "pdf") {
                                // Preview PDF using PDF.js
                                ?> 
                                <a data-fancybox data-type="iframe" 
                                   data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $case_study_doc_url; ?>" 
                                   href="javascript:;" 
                                   data-caption="Case Study Doc">
                                    <div id="case_study_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                        <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="Case Study Doc"> 
                                    </div>
                                </a>
                                <?php
                            } elseif($extension === "doc" || $extension === "docx") {
                                // Preview Word file using Google Docs Viewer
                                ?>
                                <a data-fancybox data-type="iframe" 
                                   data-src="https://docs.google.com/viewer?url=<?php echo $case_study_doc_url; ?>&embedded=true" 
                                   href="javascript:;" 
                                   data-caption="Case Study Doc">
                                    <div id="case_study_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                        <img src="<?php echo base_url('assets/images/word.png'); ?>" width="80" height="80" alt="Case Study Doc"> 
                                    </div>
                                </a>
                                <?php
                            } else { 
                                // For image or other previewable formats
                                ?>
                                <a data-fancybox="gallery" href="<?php echo $case_study_doc_url; ?>" data-caption="Case Study">
                                    <div id="case_study_preview" class="upload_img_preview" style="margin:0 auto;">
                                        <img src="<?php echo $case_study_doc_url; ?>" width="80" height="80" alt="Case Study">
                                    </div>
                                </a>
                                <?php 
                            }
                        }
                    } 
                    ?> 
                  </div> 

                  <div class="form-group col-md-6">  
                        <?php 
                        if(isset($case_study_user_info['upload_signature']) && $case_study_user_info['upload_signature'] != "") { 
                            $signature_file = $case_study_user_info['upload_signature'];
                            $signature_path = FCPATH . 'uploads/case_study_comp_registration/'.date("Ymd").'/' . $signature_file; // server path for file_exists
                            $signature_url  = base_url('uploads/case_study_comp_registration/'.date("Ymd").'/' . $signature_file) . '?' . time();

                            if(file_exists($signature_path)) {

                              $upload_signature_file_exist = $case_study_user_info['upload_signature'];
                            ?>
                            <input type="hidden" name="upload_signature_file_exist" id="upload_signature_file_exist" value="<?php echo $upload_signature_file_exist; ?>">
                            <?php
                                $extension = strtolower(pathinfo($signature_file, PATHINFO_EXTENSION));

                                if($extension === "pdf") {
                                    ?>
                                    <a data-fancybox data-type="iframe" 
                                       data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $signature_url; ?>" 
                                       href="javascript:;" 
                                       data-caption="Signature">
                                        <div id="signature_preview" class="upload_img_preview" style="margin:0 auto;"> 
                                            <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="80" height="80" alt="PDF"> 
                                        </div>
                                    </a>
                                    <?php
                                } else { 
                                    ?>
                                    <a data-fancybox="gallery" href="<?php echo $signature_url; ?>" data-caption="Signature">
                                        <div id="signature_preview" class="upload_img_preview" style="margin:0 auto;">
                                            <img src="<?php echo $signature_url; ?>" width="80" height="80" alt="Signature">
                                        </div>
                                    </a>
                                    <?php 
                                }
                            }
                        } 
                        ?> 
                </div>

                

                <!-- <div class="col-md-12">
                  <h1 class="heading_class">Other Details</h1> 
                  <div class="form-group col-md-12">
                    <p>
                      DECLARATION & COPYRIGHT TRANSFER FORM To be signed by all authors, I/We, the undersigned author(s) of the case study titled ______ (Fetched from Form-1) ______ hereby declare that:
                    </p>
                    <ol type="a">
                      <li>The above Case Study and Teaching Notes submitted to Indian Institute of Banking and Finance (IIBF), Mumbai, are not under consideration elsewhere.</li>
                      <li>The Case Study and Teaching Notes have not been published already in part or whole in any journal or magazine for private or public circulation.</li>
                      <li>I/We give consent for publication in any media (print, electronic, or any other), and assign copyright to IIBF in the event of its publication.</li>
                      <li>The Case Study & Teaching Notes may be used by IIBF for educational purposes after suitable modifications, without mentioning the author(s).</li>
                      <li>I/We affirm that the case does not violate the intellectual rights of any third party. I/We agree to indemnify and hold IIBF harmless in case of any claims.</li>
                      <li>I/We do not have any conflict of interest (financial or otherwise).</li>
                      <li>I/We have read the final version of the Case Study and Teaching Notes and are responsible for the contents.</li>
                      <li>The work described in the Case Study & Teaching Notes is my/our own.</li>
                      <li>No significant contributor has been denied authorship and all contributors have been duly acknowledged.</li>
                      <li>If authorship is contested at any stage, proving authorship will be the responsibility of the author(s), and IIBF will be indemnified.</li>
                      <li>All authors are required to sign this form.</li>
                    </ol>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="place_name" class="control-label">Place<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="150" class="form-control custom_input_formfields" required id="place_name" name="place_name" placeholder="Place" value="<?php echo set_value('place_name'); ?>">
                    <?php if (form_error('place_name') != "") { ?><label class="error"><?php echo form_error('place_name'); ?></label> <?php } ?>
                  </div> 

                  <div class="form-group col-md-12">
                    <div class="checkbox" id="declaration_err">
                      <label class="form-group col-md-12"><input type="checkbox" required name="declaration" id="declaration" class="custom_input_formfields"><strong>I/We agree to the above conditions.</strong></label>
                    </div>
                  </div> 

                </div> -->

                <div class="row">
                  <div class="col-sm-12 text-center">
                    <?php if($upload_case_study_doc_file_exist != "" || $upload_signature_file_exist != ""){ ?>
                      <input type="button" class="btn btn-info" name="btn_Submit" id="btn_Submit2" value="Next">&nbsp;&nbsp;
                    <?php 
                    }else{
                    ?>
                    <input type="button" class="btn btn-info" name="btn_Submit" id="btn_Submit1" value="Next">&nbsp;&nbsp;
                    <?php } ?>
                    
                    
                    <input type="button" class="btn btn-info" value="Cancel" onclick="window.location='<?php echo base_url('case_writing_competition/registration'); ?>'">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <?php $this->load->view('case_writing_competition/inc_footerbar'); ?>
        </div>
      </section>
    </div>
  </div>

  <?php $this->load->view('case_writing_competition/inc_footer'); ?>

  <?php $this->load->view('case_writing_competition/common_validation_all'); ?>
  <script type="text/javascript">
    <?php 
    if($case_study_level_id != "" && $case_study_level_desc_id != ""){
      ?>
      get_scheme_ajax('<?php echo $case_study_level_id; ?>','<?php echo $case_study_level_desc_id; ?>');
      <?php
    }
    ?>

    

    function validate_file(input_id) {
      $("#" + input_id).valid();
    }

    $(document).ready(function() {
        

         
      //console.log("Existing file value:", $("#upload_case_study_doc_file_exist").val());
      <?php
      if($upload_case_study_doc_file_exist != '' || $upload_signature_file_exist != ''){
      ?>
        $("#btn_Submit2").on("click", function (e) {
          e.preventDefault(); // prevent normal submit

          <?php
      if($upload_case_study_doc_file_exist != ''){
      ?>
      $("#upload_case_study_doc").rules("remove");
      <?php
      }
      if($upload_signature_file_exist != ''){
      ?>
      $("#upload_signature").rules("remove");
      <?php
      }
      ?>  

          if ($("#usersAddForm").valid()) {
            $("#usersAddForm").submit(); // submit only if valid
          }
        }); 
      <?php
      }else{
        ?>
        $("#btn_Submit1").on("click", function (e) {
          e.preventDefault(); // prevent normal submit
          if ($("#usersAddForm").valid()) {
            $("#usersAddForm").submit(); // submit only if valid
          }
        }); 
        <?php
      }
      ?>   

      /*$.validator.addMethod("custom_check_pin_code_ajax", function(value, element) {
        if ($.trim(value).length == 0) {
          return true;
        } else {
          var is_Success = false;
          var datastring = 'statecode=' + $('#case_study_level_id').val() + '&pincode=' + value;
          $.ajax({
            url: '<?php echo site_url("case_writing_competition/registration/checkpin"); ?>',
            data: datastring,
            type: 'POST',
            async: false,
            success: function(data) {
              //alert(data);
              if (data == 'true') {
                is_Success = true;
              } else {
                is_Success = false;
              }

              $.validator.messages.custom_check_pin_code_ajax = data;

            }
          });
          return is_Success;
        }
      }, '');*/

      //$("#usersAddForm").validate({
      var validator = $("#usersAddForm").validate({  
        /*onkeyup: false,
        onclick: false,
        onblur: false,
        onfocusout: false,*/
        onkeyup: function(element) {
          $(element).valid();
          // As sparky mentions, this could cause infinite loops, should be this:
          // this.element(element);
        },
        rules: {
          case_study_title: {
            required: true,
            valid_vendor_name: true,
          },
          case_study_area: {
            required: true,
            valid_vendor_name: true,
          }, 
          case_study_level_id: {
            required: true
          },
          case_study_level_desc_id: {
            required: true
          }, 
          name_of_author: {
            required: true,
            valid_vendor_name: true,
          },
          designation: {
            required: true,
            valid_vendor_name: true,
          },
          employer: {
            required: true,
            valid_vendor_name: true,
          },
          mobile_no: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
          },
          email_id: {
            required: true,
            email: true,
            valid_email_address: true
          },          
          qualifications: {
            required: true,
            valid_vendor_name: true,
          },
          other_info: {
            /*required: true*/
            valid_vendor_name: true,
          },
          //upload_case_study_doc: { required: true, file_extension: "pdf|jpg|jpeg|png" },
          upload_case_study_doc: {
            required: true,
            check_valid_file: true,
            valid_file_format: ".doc,.docx,.pdf",
            filesize_min: 8000,
            filesize_max: 2048000
          }, 
          /*place_name: {
            required: true,
            valid_vendor_name: true,
          },*/
          upload_signature: {
            required: true,
            check_valid_file: true,
            valid_file_format: ".jpg,.jpeg,.png",
            filesize_min: 8000,
            filesize_max: 204800
          }, 
          /*declaration: {
            required: true
          },*/
        },
        messages: {
          case_study_title: {
            required: "Please enter the case study title.",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          case_study_area: {
            required: "Please enter the case study area.",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          }, 
          case_study_level_id: {
            required: "Please select a scheme (I–V)."
          },
          case_study_level_desc_id: {
            required: "Please select scheme name"
          }, 
          name_of_author: {
            required: "Please enter Author Name",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          designation: {
            required: "Please enter Designation",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          employer: {
            required: "Please enter Employer",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          mobile_no: {
            required: "Please enter Mobile No",
            digits: "Please enter only numbers",
            minlength: "Please enter 10 digits Mobile No",
            maxlength: "Please enter 10 digits Mobile No"
          },
          email_id: {
            required: "Please enter Email ID",
            email: "Please enter valid Email ID",
            valid_email_address: "Please enter valid Email ID"
          },   
          qualifications: {
            required: "Please enter Qualifications",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },  
          other_info: {
            /*required: "Please enter other information",*/
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          }, 
          upload_case_study_doc: {
            required: "Please upload Case Study (Word/PDF file).",
            check_valid_file: "Please upload valid file",
            valid_file_format: "Please upload only .doc,.docx,.pdf files",
            filesize_min: "Please upload a file of at least 8 KB",
            filesize_max: "Please upload a file of up to 2 MB" 
          }, 
          /*place_name: {
            required: "Please enter Place",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },*/ 
          upload_signature: {
            required: "Please Upload Signature",
            check_valid_file: "Please upload valid file",
            valid_file_format: "Please upload only .jpg,.jpeg,.png files",
            filesize_min: "Please upload minimum 8kb file",
            filesize_max: "Please upload maximum 200kb file"
          },
          /*declaration: {
            required: "Please accept the declaration"
          },*/ 
        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "declaration") {
            //error.insertAfter("#declaration_err");
          } else {
            error.insertAfter(element);
          }
        }

      });
    });

    
    function get_scheme_ajax(case_study_level_id = '', case_study_level_desc_id = '') {
      var case_study_level_id = $("#case_study_level_id").val();
      if (case_study_level_id) {
        $.ajax({
          type: 'POST',
          url: '<?php echo site_url("case_writing_competition/registration/getCasestudylevel"); ?>', 
          data: { case_study_level_id: case_study_level_id, case_study_level_desc_id: case_study_level_desc_id },
          success: function(html) {
            //alert(html);
            $('#case_study_level_desc_id').show();
            $('#case_study_level_desc_id').html(html);
          }
        });
      } else {
        $('#case_study_level_desc_id').html('<option value="">Select a scheme (I–V) First</option>');
      }
    }

    function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
      }
      return true;
    }

    function onlyAlphabets(key) {
      var keycode = (key.which) ? key.which : key.keyCode;
      //alert(keycode);
      if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 32) {
        return true;
      } else {
        return false;
      }
    }

    function alphanumeric(key) {
      var keycode = (key.which) ? key.which : key.keyCode;

      if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || (keycode >= 48 && keycode <= 57)) {
        return true;
      } else {
        return false;
      }
    } 

    function alphanumeric_custom(key) {
      var keycode = (key.which) ? key.which : key.keyCode;

        try {  
            if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || keycode == 46 || (keycode >= 48 && keycode <= 57) || (keycode > 46 && keycode < 58))
                return true; 
            else
                return false;
        }
        catch (err) {
            //alert(err.Description);
          return false;
        }
    }

    $(document).ready(function() {
      $('.loading').delay(0).fadeOut('slow');
    });
  </script>

  <script>
      Fancybox.bind("[data-fancybox]", {
      mainClass: "custom-fancybox",
      autoFocus: false
    });

    /*Fancybox.bind("[data-fancybox]", {
      iframe: {
        preload: false,
        css: {
          width: "40%",   // set width
          height: "40%"  // set height
          ma
        }
      }
    });*/
    </script>

</body>

</html>