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

              <form class="form-vertical" name="usersAddForm" id="usersAddForm" method="post" action="<?php echo site_url('case_writing_competition/registration/preview/'); ?>" enctype="multipart/form-data" autocomplete="off">

  <div class="form-vertical">

    <div class="col-md-12">

      <div class="form-group col-md-12">
        <label class="control-label">Title of the Case Study:</label>
        <p><?php echo $case_study_user_info['case_study_title']; ?></p>
      </div>

      <div class="form-group col-md-12">
        <label class="control-label">Area of the Case Study:</label>
        <p><?php echo $case_study_user_info['case_study_area']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Case Study entered in the scheme (I – V):</label>
        <p><?php 
        if($case_study_user_info['case_study_level_id'] != ""){
          $case_study_comp_level = $this->master_model->getRecords('case_study_comp_level',array('id'=>$case_study_user_info['case_study_level_id'], 'is_active'=>1),'level'); 
          if(count($case_study_comp_level) > 0)
          {
             echo $case_study_comp_level[0]['level'];
          }
        } 
        ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Scheme Name:</label>
        <p><?php 
          if($case_study_user_info['case_study_level_desc_id'] != ""){
          $case_study_comp_level_desc = $this->master_model->getRecords('case_study_comp_level_desc',array('id'=>$case_study_user_info['case_study_level_desc_id'], 'is_active'=>1),'desc'); 
          if(count($case_study_comp_level_desc) > 0)
          {
             echo $case_study_comp_level_desc[0]['desc'];
          }
        } 
       ?></p>
      </div>

      <div class="form-group col-md-12">
        <label class="control-label">Name of the Author:</label>
        <p><?php echo $case_study_user_info['name_of_author']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Designation:</label>
        <p><?php echo $case_study_user_info['designation']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Employer:</label>
        <p><?php echo $case_study_user_info['employer']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Mobile No.:</label>
        <p><?php echo $case_study_user_info['mobile_no']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Email ID:</label>
        <p><?php echo $case_study_user_info['email_id']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Qualifications:</label>
        <p><?php echo $case_study_user_info['qualifications']; ?></p>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Uploaded Case Study:</label> 
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

      <div class="form-group col-md-12">
        <label class="control-label">Any other information:</label>
        <p><?php echo $case_study_user_info['other_info']; ?></p>
      </div>

      

    

    </div>

              <div class="form-group col-md-12">
                <p><strong>DECLARATION & COPYRIGHT TRANSFER FORM</strong><br>
                  To be signed by all authors, I/We, the undersigned author(s) of the case study titled 
                  <u><?php echo $case_study_user_info['case_study_title']; ?></u>, hereby declare that:</p>
                  <ol type="a">
                    <li>The above Case Study and Teaching Notes submitted to IIBF, Mumbai, are not under consideration elsewhere.</li>
                    <li>The Case Study and Teaching Notes have not been published already in part or whole in any journal or magazine for private or public circulation.</li>
                    <li>I/We give consent for publication in any media and assign copyright to IIBF in the event of its publication.</li>
                    <li>The Case Study & Teaching Notes may be used by IIBF for educational purposes after modifications, without mentioning the author(s).</li>
                    <li>I/We affirm the case does not violate the intellectual rights of any third party and indemnify IIBF against any claims.</li>
                    <li>I/We do not have any conflict of interest (financial or otherwise).</li>
                    <li>I/We have read the final version and take responsibility for the contents.</li>
                    <li>The work described is my/our own.</li>
                    <li>All contributors have been acknowledged; no one has been denied authorship.</li>
                    <li>If authorship is contested, responsibility lies with the author(s); IIBF will be indemnified.</li>
                    <li>All authors are required to sign this form.</li>
                  </ol> 
              </div>

              <div class="form-group row col-md-6 align-items-center">
                <label for="place_name" class="col-sm-2 col-form-label control-label">Date<span style="color:#F00"></span></label>
                <div class="col-sm-10"><label><?php echo date('d-M-Y'); ?></label></div>
              </div>
              
              <div class="form-group row col-md-6 align-items-center">
                <label for="upload_signature" style="text-align: right;" class="col-sm-6 col-form-label control-label">Signature:</label>
                <div class="col-sm-6">
                  <img src="<?php echo base_url('uploads/case_study_comp_registration/'.date("Ymd").'/'.$case_study_user_info['upload_signature']); ?>" 
                       alt="Uploaded Signature" 
                       width="100" 
                       height="100" 
                       class="img-thumbnail"/>
                </div>
              </div>

              <div class="form-group row col-md-6 align-items-center">
                <label for="place_name" class="col-sm-2 col-form-label control-label">Place<span style="color:#F00">*</span></label>
                <div class="col-sm-10">
                  <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="150" class="form-control custom_input_formfields" required id="place_name" name="place_name" placeholder="Place" value="<?php echo set_value('place_name'); ?>">
                  <?php if (form_error('place_name') != "") { ?><label class="error"><?php echo form_error('place_name'); ?></label> <?php } ?>
                </div>
              </div>

              
 
               
              <div class="form-group col-md-12">
                <div class="checkbox" id="declaration_err">
                  <label class="form-group col-md-12"><input type="checkbox" required name="declaration" id="declaration" class="custom_input_formfields"><strong>I/We agree to the above conditions.</strong></label>
                </div>
              </div> 
     
              <div class="row">
                  <div class="col-sm-12 text-center">
                    <input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="Submit">&nbsp;&nbsp;
                    <input type="button" class="btn btn-warning" value="Back" onclick="window.history.back()">
                  </div>
                </div>

    <!-- <div class="row">
      <div class="col-sm-12 text-center">
        <a href="<?php echo base_url('case_writing_competition/registration/submit'); ?>" class="btn btn-info">Confirm & Submit</a>
        <a href="<?php echo base_url('case_writing_competition/registration/edit'); ?>" class="btn btn-secondary">Back to Edit</a>
      </div>
    </div> -->

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
    function validate_file(input_id) {
      $("#" + input_id).valid();
    }

    $(document).ready(function() {
         
      $("#usersAddForm").validate({
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
          place_name: {
            required: true,
            valid_vendor_name: true,
          }, 
          declaration: {
            required: true
          },
        },
        messages: { 
          place_name: {
            required: "Please enter Place",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          declaration: {
            required: "Please accept the declaration"
          }, 
        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "declaration") {
            error.insertAfter("#declaration_err");
          } else {
            error.insertAfter(element);
          }
        }

      });
    });


    function get_scheme_ajax() {
      var case_study_level_id = $("#case_study_level_id").val();
      if (case_study_level_id) {
        $.ajax({
          type: 'POST',
          url: '<?php echo site_url("case_writing_competition/registration/getCasestudylevel"); ?>',
          data: 'case_study_level_id=' + case_study_level_id,
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