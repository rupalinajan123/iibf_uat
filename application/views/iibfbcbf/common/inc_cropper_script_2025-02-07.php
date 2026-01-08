<!-- Modals -->
<div class="modal inmodal fade" id="optionsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lgx">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" onclick="remove_custom_class()">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="img_pop_up_title">Select The Option</h4>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" name="current_image_id" id="current_image_id">
        <input type="hidden" name="db_tbl_name" id="db_tbl_name">
        <input type="hidden" name="edit_btn_title" id="edit_btn_title">
        <input type="hidden" name="selected_image_name" id="selected_image_name">
        <input type="hidden" name="selected_image_type" id="selected_image_type">

        <?php 
          $fileChooser_accepted_files = '.png, .jpg, .jpeg'; 
          if(isset($inc_fileChooser_accepted_files) && $inc_fileChooser_accepted_files != '') 
          { 
            $fileChooser_accepted_files = $inc_fileChooser_accepted_files; 
          } ?>
        <input class="sr-only" id="fileChooser" type="file" name="selected_image" accept="<?php echo $fileChooser_accepted_files; ?>">
        
        <?php 
        if(isset($page_name) && in_array($page_name, array('ordinary_edit_profile', 'non_member_edit_profile', 'ordinary_member_registration','non_mem_reg','ordinary_mem_apply_exam','dra_candidate_update_profile','csc_non_mem_reg','csc_non_mem_ippb_reg','bulk_non_mem_reg'))) 
        { ?>
          <label class="btn btn-primary mb-0" onclick="open_guidelines_modal()">Upload</label>
        <?php }
        else if(isset($page_name) && in_array($page_name, array('bcbf_admin_add_candidate', 'bcbf_agency_add_candidate', 'bcbf_candidate_update_profile'))) 
        { ?>
          <label for="fileChooser" class="btn btn-primary mb-0">Upload</label>
          <button id="openWebCamModal" class="btn btn-primary">Camera</button>
        <?php }
        else if(isset($page_name) && in_array($page_name, array('dra_candidate_update_profile', 'img_process_page'))) 
        { ?>
        <label for="fileChooser" class="btn btn-primary mb-0">Upload</label>
        <?php } ?>

        <note class="form_note" id="img_pop_up_note"></note>
        <label class="error" id="img_pop_up_error"></label>

        <?php /*<button id="editProfile" class="btn btn-primary">Edit</button>
        <button id="removeProfile" class="btn btn-primary">Remove</button> */ ?>
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal fade" id="GuidelinesModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" onclick="remove_custom_class()">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="GuidelinesModalTitle">Guidelines</h4>
      </div>
      <div class="modal-body text-centerr">
        <div class="guidelines_style1"><strong>Note: </strong>Kindly go through the guidelines carefully. Images uploaded in the application form which are not in accordance with the guidelines or failure to rectify the images in the edit window/final edit window shall invite rejection of application.</div>
        <div class="guidelines_style1">Before applying online, a candidate will be required to have a scanned (digital) image of his/her photograph, signature, ID proof & declaration as per the specifications given below. Your online application will not be registered unless you upload your photograph, signature, ID proof & declaration as specified</div>
        <div id="GuidelinesModalContent"></div>
        <label for="fileChooser" class="btn btn-primary btn-block accept_btn mt-5">Accept</label>
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal fade" id="webCamModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class=" modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" onclick="remove_custom_class()">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Take a picture</h4>
      </div>
      <div class="modal-body">
        <div id="webCameraArea"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="spanshot">Take a picture</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="remove_custom_class()">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal fade" id="webcamErrormodal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class=" modal-content">
      <div class="modal-header">
        <h2 class="fw-bolder">Camera Permission</h2>
      </div>
      <div class="modal-body">
        <h3 id="webcamErrormodalMessage" class="text-danger text-center mt-6 mb-6"></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="remove_custom_class()">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal fade" id="cropModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lgx">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" onclick="remove_custom_class()">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Make a selection</h4>
      </div>
      <div class="modal-body">
        <div id="cropimage">
          <img id="imageprev" src="" />
        </div>

        <div class="progress mt-4">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <div class="d-flex" id="action_cropper_btn_outer">
          <button type="button" class="btn btn-light-primary btn-sm action_cropper_btn" id="rotateL" title="Rotate Left"><i class="fa fa-undo"></i></button>
          <button type="button" class="ms-2 btn btn-light-primary btn-sm action_cropper_btn" id="rotateR" title="Rotate Right"><i class="fa fa-repeat"></i></button>
          <button type="button" class="ms-2 btn btn-light-primary btn-sm action_cropper_btn" id="scaleX" title="Flip Horizontal"><i class="fa fa-arrows-h"></i></button>
          <button type="button" class="ms-2 btn btn-light-primary btn-sm action_cropper_btn" id="scaleY" title="Flip Vertical"><i class="fa fa-arrows-v"></i></button>
          <button type="button" class="ms-2 btn btn-light-primary btn-sm action_cropper_btn" id="reset" title="Reset"><i class="fa fa-refresh"></i></button>
        </div>
        <div class="d-flex">
          <button type="button" class="ms-2 btn btn-success" id="SaveImage">Save</button>&nbsp;
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="remove_custom_class()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modals -->

<link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/cropper.css')); ?>" rel="stylesheet">
<link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/cropper_style.css')); ?>" rel="stylesheet">

<style>
  #cropimage { width: 300px; height: 300px; }
  body.modal-open-custom { padding-right: inherit !important; }
  .modal-open-custom { overflow: hidden; }
  .modal-open-custom .modal { overflow-x: hidden; overflow-y: auto; }
  .hide_input_file_cropper { width: 0; height: 0; padding: 0; border: none; }
</style>

<script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/webcam.min.js')); ?>"></script>
<script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/cropper.js')); ?>"></script>
<script>
  $(document).ready(function() 
  {
    $('#cropModal').on('hidden.bs.modal', function(e) 
    {
      $('body').removeClass('modal-open-custom');
    });
  });

  (() => 
  {
    const ajax_error = "Error in sending data";

    const disableElement = function(e, x) 
    {
      if (!(e instanceof jQuery)) e = $(e);
      e.attr("disabled", true);
      const html = x ? '<div class="absolute-preloader" ><div class="preloader-message" ><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...</div></div>' : '<div class="absolute-preloader" ><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>';
      e.append(html).css({ position: "relative", opacity: ".5", cursor: "not-allowed", });
    }

    const enableElement = (e) => 
    {
      if (!(e instanceof jQuery)) e = $(e);
      e.attr("disabled", false);
      e.find('.absolute-preloader').remove();
      e.css({ opacity: "1", cursor: "pointer", });
    }

    const fileChooser = $("input[type=file]#fileChooser");
    const cropModal = $("#cropModal");
    const optionsModal = $("#optionsModal");
    const cropArea = $("#cropimage");
    /* const profileImage = $("#profileImage");
    alert(profileImage)	 */

    cropModal.on('hide.bs.modal', () => 
    {
      cropArea.html('<img id="imageprev" src=""/>');
    });

    fileChooser.on("change", (e) => 
    {
      var img_properties = $('#fileChooser')[0].files[0]; /* console.log(img_properties); */
      var img_size_in_kb = parseFloat(img_properties['size']) / parseFloat(1000);
      var img_type_str = img_properties['type'].toLowerCase().replace("image/", "");
      $("#selected_image_name").val(img_properties['name']);
      $("#selected_image_type").val(img_properties['type']);
      
      var valid_extension_string = "<?php echo $fileChooser_accepted_files; ?>";      
      // Convert the valid_extension_string to an array and remove leading dots
      var valid_extensions_arr = valid_extension_string.replace(/\s/g, '').split(',').map(function(ext) { return ext.replace('.', ''); });
      
      var show_error_flag = 0;
      $("#img_pop_up_error").html("");
      if (img_size_in_kb == 0) 
      { 
        show_error_flag = 1;
        $("#img_pop_up_error").html("Please upload valid image") 
      } 
      else if ($.inArray(img_type_str, valid_extensions_arr) === -1) 
      { 
        show_error_flag = 1;
        $("#img_pop_up_error").html("Please upload only "+valid_extension_string+" image") 
      }
      else if (img_size_in_kb > 20000) 
      {
        show_error_flag = 1;         
        $("#img_pop_up_error").html("Please upload image having size less than 20MB") 
      } 
      else 
      {
        show_error_flag = 0;

        optionsModal.modal("hide");
        $("#GuidelinesModal").modal("hide");
        cropModal.modal({ backdrop: 'static', keyboard: false }, "show");
        $("body").addClass("modal-open-custom");

        var image = document.querySelector('#imageprev');
        var files = e.target.files;
        var done = function(url) 
        {
          e.target.value = '';
          image.src = url;
          cropModal.modal({ backdrop: 'static', keyboard: false });
          cropImage();
        };

        var reader;
        var file;
        var url;
        if (files && files.length > 0)
        {
          file = files[0];
          if (URL) 
          {
            done(URL.createObjectURL(file));
          } 
          else if (FileReader) 
          {
            reader = new FileReader();
            reader.onload = function(e) 
            {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
      }

      if(show_error_flag == '1')
      {
        if($("#current_image_id").val() == 'scannedphoto' || $("#current_image_id").val() == 'scannedsignaturephoto' || $("#current_image_id").val() == 'idproofphoto' || $("#current_image_id").val() == 'declaration' || $("#current_image_id").val() == 'empidproofphoto' || $("#current_image_id").val() == 'bank_bc_id_card' || $("#current_image_id").val() == 'declarationform') 
        { 
          $("#GuidelinesModal").modal("hide"); optionsModal.modal("show"); 
        }
      }
    });

    // Webcamera
    const webCamModal = $("#webCamModal");

    $("#openWebCamModal").on('click', function(event) 
    {
      event.preventDefault();

      optionsModal.modal("hide");
      $("#GuidelinesModal").modal("hide");
      $("body").removeClass("modal-open-custom");
      //webCamModal.modal("show");

      let All_mediaDevices = navigator.mediaDevices
      if (!All_mediaDevices || !All_mediaDevices.getUserMedia) 
      {
        //console.log("getUserMedia() not supported.");
        sweet_alert_error("getUserMedia() not supported.");
        return;
      }

      All_mediaDevices.getUserMedia({ audio: true, video: true }).then(function(vidStream) 
      {
        var video = document.getElementById('videoCam');
        if ("srcObject" in video) { video.srcObject = vidStream; } 
        else { video.src = window.URL.createObjectURL(vidStream); }

        video.onloadedmetadata = function(e) { video.play(); };
          
        $("#webCamModal").modal({ backdrop: 'static', keyboard: false }, "show"); 
        configure_webcam_cm();
      })
      .catch(function(e) 
      {
        //configure();
        console.log(e.name + ": " + e.message);

        if (e.name == "NotAllowedError")
        {
          $("#webcamErrormodalMessage").html("To use the camera, please grant permission to your browser.");
          configure_webcam_cm();
          $("#webCamModal").modal("hide");
          $("body").removeClass("modal-open-custom");
          /* //$("#webCamModal").modal("show"); */
          /* //$("#webCameraArea").html('Persmission Denide OR Web camp not found'); */    
        } 
        else if (e.name == "NotFoundError") 
        {
          /* //$("#webcamErrormodalMessage").html("This device does not have a camera."); */
          sweet_alert_error("This device does not have a camera.");
          configure_webcam_cm();
          $("#webCamModal").modal("hide");
          $("body").removeClass("modal-open-custom");
          /* //$("#webCamModal").modal("show"); */
          /* //$("#webCameraArea").html('Persmission Denide OR Web camp not found');  */   
        } 
        else if (e.name == "NotReadableError" || e.name == "Webcam.js Error" || e.name == "AbortError") 
        {
          /* //$("#webcamErrormodalMessage").html("This device does not have a camera."); */
          sweet_alert_error(e.message);
          configure_webcam_cm();
          $("#webCamModal").modal("hide");
          $("body").removeClass("modal-open-custom");
        }
        else 
        {
          $("#webCamModal").modal({ backdrop: 'static', keyboard: false }, "show");
          $("body").addClass("modal-open-custom");
          configure_webcam_cm();
        }
      });
    });

    $("#spanshot").on('click', function(event) 
    {
      event.preventDefault();
      take_snapshot();
      webCamModal.modal("hide");
      //$("body").removeClass("modal-open-custom");
    });

    function configure() 
    {
      Webcam.set({ /* width: 825, height: 615, */ width: 640, height: 480, image_format: 'jpeg', jpeg_quality: 100 });
      Webcam.attach("#webCameraArea");
    }

    function take_snapshot() 
    {
      Webcam.snap(function(data_uri) 
      {
        $("#selected_image_name").val('webcam.png');
        $("#selected_image_type").val('png');

        webCamModal.modal('hide');
        $("body").removeClass("modal-open-custom");
        cropModal.modal({ backdrop: 'static', keyboard: false });
        cropArea.html('<img id="imageprev" src="' + data_uri + '"/>');
        cropImage();
        cropModal.modal({ backdrop: 'static', keyboard: false }, "show"); 
        $("body").addClass("modal-open-custom");
      });
      Webcam.reset();
    }

    /*webCamModal.on('show.bs.modal', function () {
      configure();
    });*/

    function configure_webcam_cm() 
    {
      configure();

      var now = Date.now();
      navigator.mediaDevices.getUserMedia({ audio: true, video: false })
      .then(function(stream) 
      {
        //alert('Allowed :', Date.now() - now);
      })
      .catch(function(err) 
      {
        //alert("Please clear your browser permission to access the camera."); 
        //navigator.mediaDevices.getUserMedia({video: true})
        $("#webCamModal").modal("hide");
        $("body").removeClass("modal-open-custom");
        //$("#webcamErrormodal").modal('show');
      });
    }

    webCamModal.on('hide.bs.modal', function() 
    {
      Webcam.reset();
      //cropModal.modal("show");
      cropArea.html('<img id="imageprev" src=""/>');
    });

    // CROP IMAGE AFTER UPLOAD 
    var cropper;
    function cropImage() 
    {
      var current_image_id = $("#current_image_id").val();
      var db_tbl_name = $("#db_tbl_name").val();
      var aspectRatio_val = '';

      if(db_tbl_name == 'iibfbcbf_batch_candidates') //FOR IIBF BCBF CANDIDATES
      {
        if (current_image_id == 'candidate_photo') 
        {
          aspectRatio_val = 20/23;
        } 
        else if (current_image_id == 'candidate_sign') 
        {
          aspectRatio_val = 7/3; //width/height
        }
      }
      else if(db_tbl_name == 'member_registration') //FOR REGULAR CANDIDATES
      {
        if (current_image_id == 'scannedphoto') 
        {
          aspectRatio_val = 20/23; //width/height
        } 
        else if (current_image_id == 'scannedsignaturephoto') 
        {
          aspectRatio_val = 7/3; //width/height
        }
        else if (current_image_id == 'idproofphoto') 
        {
          //aspectRatio_val = 5 / 3;
        }
        else if (current_image_id == 'declaration' || current_image_id == 'declarationform') 
        {
          //aspectRatio_val = 5 / 3;
        }
        else if (current_image_id == 'empidproofphoto') 
        {
          //aspectRatio_val = 5 / 3;
      }
        else if (current_image_id == 'bank_bc_id_card') 
        {
          //aspectRatio_val = 5 / 3;
        }
      }
      else if(db_tbl_name == 'dra_members') //FOR IIBF BCBF CANDIDATES
      {
        if (current_image_id == 'candidate_photo') 
        {
          aspectRatio_val = 20/23;
        } 
        else if (current_image_id == 'candidate_sign') 
        {
          aspectRatio_val = 7/3; //width/height
        }
      } ////

      var image = document.querySelector('#imageprev');
      // $(image).on("load", () => {
      setTimeout(() => 
      {
        var minAspectRatio = 1;
        var maxAspectRatio = 1;
        cropper = new Cropper(image, 
        {
          /* minCropBoxWidth: 100,
          minCropBoxHeight: 100, 
          //aspectRatio: 1 / 1,
          //viewMode: 3, */

          aspectRatio: aspectRatio_val,
          viewMode: 1, // Restricts the crop box to the size of the canvas
          responsive: true,
          restore: true,
          guides: true,
          highlight: true,          
          dragMode: 'move',
          autoCropArea: 0.8,
          modal: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          ready: function() 
          {
            var cropper = this.cropper;
            var containerData = cropper.getContainerData();
            var cropBoxData = cropper.getCropBoxData();
            var aspectRatio = cropBoxData.width / cropBoxData.height;
            var newCropBoxWidth;
            cropper.setDragMode("move");
            /* if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) 
            {
              newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);
              cropper.setCropBoxData(
              {
                left: (containerData.width - newCropBoxWidth) / 2,
                width: newCropBoxWidth
              });
            } */
          },
        });
      }, 500);

      $('#scaleY').off('click').on('click', function() 
      {
        //alert("scaleY")
        var Yscale = cropper.imageData.scaleY;
        if (Yscale == 1) { cropper.scaleY(-1); } 
        else { cropper.scaleY(1); };
      });

      $('#scaleX').off('click').on('click', function() 
      {
        //alert("scaleX")
        var Xscale = cropper.imageData.scaleX;
        if (Xscale == 1) { cropper.scaleX(-1); } 
        else { cropper.scaleX(1); };
      });

      $('#rotateR').off('click').on('click', function() 
      { 
        //alert("rotateR"); 
        cropper.rotate(45); 
      });

      $('#rotateL').off('click').on('click', function() 
      { 
        //alert("rotateL"); 
        cropper.rotate(-45); 
      });

      $('#reset').off('click').on('click', function() 
      { 
        //alert("reset"); 
        cropper.reset(); 
      });

      $('#imageprev').on('error', function() 
      {
        setTimeout(function() 
        {
          $("#cropModal").modal("hide");
          $("body").removeClass("modal-open-custom");
        }, 500);

        swal(
          {
          title: "Error",
          text: "Please select valid image",
          type: "error",
          closeOnConfirm: true
        }, function()
        {
          $("#cropModal").modal("hide");
          $("body").removeClass("modal-open-custom");
        });
      })
    };

    $(document).on('click', '#SaveImage', function(event)
    {
      const t = $(this);
      event.preventDefault();
      const progress = $('.progress');
      const progressBar = $('.progress-bar');
      canvas = cropper.getCroppedCanvas(
      {
        /* width: 300,
        height: 300, */
      });

      let percent = '0';
      let percentage = '0%';
      progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);

      progress.show();
      canvas.toBlob(function(blob) 
      {
        const formData = new FormData();
        formData.append('selected_image', blob, $("#selected_image_name").val());
        formData.append('current_image_id', $("#current_image_id").val());
        formData.append('db_tbl_name', $("#db_tbl_name").val());

        if($("#db_tbl_name").val() == 'iibfbcbf_batch_candidates') //FOR IIBF BCBF CANDIDATES
        {
          formData.append('enc_batch_id', '<?php if(isset($enc_batch_id)) { echo $enc_batch_id; } ?>');
          formData.append('enc_candidate_id', '<?php if(isset($enc_candidate_id)) { echo $enc_candidate_id; } ?>');
        }

        $("#page_loader").show();
        $.ajax('<?php echo site_url('iibfbcbf/save_cropper_image/save_image'); ?>', 
        {
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'JSON',
          beforeSend: function() { disableElement(t); },
          xhr: function() 
          {
            const xhr = new XMLHttpRequest();
            xhr.upload.onprogress = function(e) 
            {
              percent = '0';
              percentage = '0%';
              if (e.lengthComputable) 
              {
                percent = Math.round((e.loaded / e.total) * 100);
                percentage = percent + '%';
                progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
              }
            };
            return xhr;
          },
          success: function(data) 
          {
            try 
            {
              if ($.trim(data.flag) == 'success') 
              {
                cropModal.modal('hide');
                $("body").removeClass("modal-open-custom");

                progress.hide();

                var db_tbl_name = $("#db_tbl_name").val();
                var current_image_id = $("#current_image_id").val();
                var edit_btn_title = $("#edit_btn_title").val();

                var preview_id = current_image_id + "_preview";

                var data_lightbox_hidden = $("#data_lightbox_hidden").val();
                var data_lightbox_title_hidden = $("#data_lightbox_title_hidden").val();
                
                if (typeof data_lightbox_hidden === "undefined") { data_lightbox_hidden = 'candidate_images'; } 
                if (typeof data_lightbox_title_hidden === "undefined") { data_lightbox_title_hidden = ''; } 

                var currentTime = new Date().getTime();

                var lightbox_data_title = '';
                if(current_image_id == 'id_proof_file') { lightbox_data_title = 'Proof of Identity - '; }
                else if(current_image_id == 'qualification_certificate_file') { lightbox_data_title = 'Qualification Certificate - '; }
                else if(current_image_id == 'candidate_photo') { lightbox_data_title = 'Passport-size Photo of the Candidate - '; }
                else if(current_image_id == 'candidate_sign') { lightbox_data_title = 'Signature of the Candidate - '; }
                else if(current_image_id == 'scannedphoto') { lightbox_data_title = 'Scanned Photograph - '; }
                else if(current_image_id == 'scannedsignaturephoto') { lightbox_data_title = 'Scanned Signature - '; }
                else if(current_image_id == 'idproofphoto') { lightbox_data_title = 'ID Proof - '; }
                else if(current_image_id == 'declaration' || current_image_id == 'declarationform') { lightbox_data_title = 'Declaration - '; }
                else if(current_image_id == 'empidproofphoto') { lightbox_data_title = 'Employment Proof - '; }
                else if(current_image_id == 'bank_bc_id_card') { lightbox_data_title = 'Bank BC ID Card - '; }////

                $("#" + preview_id).html('<a href="' + data.response_msg+ '?' + currentTime + '" class="example-image-link" data-lightbox="'+data_lightbox_hidden+'" data-title="'+lightbox_data_title+data_lightbox_title_hidden+'"><img src="' + data.response_msg+ '?' + currentTime + '"></a><button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="'+current_image_id+'" data-db_tbl_name="'+db_tbl_name+'" data-title="'+edit_btn_title+'" title="'+edit_btn_title+'" alt="'+edit_btn_title+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>');

                var cropper_image_id = current_image_id + "_cropper";
                $("#" + cropper_image_id).val(data.response_msg);

                var error_image_id = current_image_id + "-error";
                $("#" + error_image_id).remove();

                $('.edit_existing_image').off('click').on('click', function(e) 
                { 
                  var current_image_id = $(this).data('current_image_id');
                  var db_tbl_name = $(this).data('db_tbl_name');
                  var edit_btn_title = $(this).data('title');
                  inc_edit_img(current_image_id, db_tbl_name, edit_btn_title);
                });

                $("#page_loader").hide();
              } 
              else 
              {
                progressBar.width(0).attr('aria-valuenow', 0).text(0);
                $(".progress").hide();
                if (data.response_msg != "") 
                {
                  sweet_alert_error(data.response_msg);
                } 
                else 
                {
                  sweet_alert_error("Error Occurred. Please try again.");
                }
                $("#page_loader").hide();
              }
            } 
            catch (e) 
            {
              //sweetAlert("error", e);
              //alert("error"+e);
              progressBar.width(0).attr('aria-valuenow', 0).text(0);
              $(".progress").hide();
              sweet_alert_error("Error Occurred. Please try again.");
              //sweet_alert_error("Error Occurred. Please try again."+e);
              $("#page_loader").hide();
            }
          },
          error: function() 
          {
            //sweetAlert("error", ajax_error);
            //alert("error"+ajax_error);
            progressBar.width(0).attr('aria-valuenow', 0).text(0);
            $(".progress").hide();
            sweet_alert_error("Error Occurred. Please try again.");
            //sweet_alert_error("Error Occurred. Please try again."+ajax_error);
            $("#page_loader").hide();
          },
          complete: function() { enableElement(t); }
        });
      }, $("#selected_image_type").val());
    })

    $('.edit_existing_image').off('click').on('click', function(e)
    { 
      var current_image_id = $(this).data('current_image_id');
      var db_tbl_name = $(this).data('db_tbl_name');
      var edit_btn_title = $(this).data('title');
      inc_edit_img(current_image_id, db_tbl_name, edit_btn_title); 
    });

    function inc_edit_img(current_image_id, db_tbl_name, edit_btn_title)
    {
      $("#current_image_id").val(current_image_id);
      $("#db_tbl_name").val(db_tbl_name);
      $("#edit_btn_title").val(edit_btn_title);
      
      inc_show_title_messages(current_image_id, db_tbl_name);

      var current_image_link = $("#" + current_image_id + "_preview img").attr('src').split('?');
      var current_image_full_path = current_image_link[0];

      var img = new Image();
      img.src = current_image_full_path;

      img.onload = function() 
      {
        var urlParts = current_image_full_path.split('/');
        var selected_imageName = urlParts[urlParts.length - 1];

        var imageType = selected_imageName.split('.').pop();
        if (imageType == 'jpg') { imageType = 'jpeg'; }
        var selected_image_type = 'image/' + imageType;

        $("#selected_image_name").val(selected_imageName);
        $("#selected_image_type").val(selected_image_type);
      };

      var currentTime = new Date().getTime();
      const url = current_image_full_path+'?'+currentTime;
      $("#cropimage").html('<img id="imageprev" src="' + url + '" />');
      $("#optionsModal").modal("hide");
      $("#GuidelinesModal").modal("hide");      
      $('#cropModal').modal("show");
      $('#cropModal').modal({ backdrop: "static" });
      cropImage();
    }
    
  })();

  function open_img_upload_modal(current_image_id, db_tbl_name, edit_btn_title) 
  {
    $("#current_image_id").val(current_image_id);
    $("#db_tbl_name").val(db_tbl_name);
    $("#edit_btn_title").val(edit_btn_title);
    
    inc_show_title_messages(current_image_id, db_tbl_name);

    $("#optionsModal").modal({ backdrop: 'static', keyboard: false }, 'show')
    $("body").addClass("modal-open-custom");
  }

  function open_guidelines_modal() 
  {
    var current_image_id = $("#current_image_id").val();
    if(current_image_id == 'scannedphoto')
    {
      $("#GuidelinesModalTitle").html('Photo Upload Guidelines');
      
      var content = '';
      content +='<div class="guidelines_style2 mt-5">';
      content +='<h4 class="guidelines_header mt-4 mb-3 pb-2 text-center">Dos</h4>';
      
      content +='<ul>';
      content +='<li>Photograph must be a recent passport style color picture.</li>';
      content +='<li>Picture should be taken against a light-colored, preferably white background and there is adequate light.</li>';
      content +='<li>Look straight at the camera with a relaxed face.</li>';
      content +='<li>If you have to use flash, ensure theres no "red-eye".</li>';
      content +='<li>If you wear glasses make sure that there are no reflections and your eyes can be clearly seen.</li>';
      content +='<li>Caps, hats, dark glasses and tinted spectacles or hair across are not acceptable. Religious headwear is allowed but it must not cover your face.</li>';
      content +='<li>Dimensions : 200(width) x 230(height) pixels (preferred).</li>';
      content +='<li>Photo uploaded should be appropriate size and clearly visible.</li>';
      content +='<li>Do not upload side turned or upside down photo.</li>';
      content +='<li>Photo should be strictly uploaded under photo upload section.</li>';
      content +='<li>There should not be any blurred photo.</li>';
      content +='<li>There should not be any stamp/name etc. on photograph.</li>';    
      content +='</ul>';

      content +='<h4 class="guidelines_header mt-4 mb-3 pb-2 text-center">Don&apos;ts</h4>';
      content +='<ul>';
      content +='<li>Small size photograph is clicked/uploaded.</li>';    
      content +='<li>Wearing colored glasses or sunglasses/ cap.</li>';    
      content +='<li>Shadow on face/ not facing the camera/ distorted face/ face covered with mask/ blurred image.</li>';    
      content +='<li>Photo taken in dark/ improper background.</li>';    
      content +='<li>Do not upload more than 3-month-old pre saved photograph of yours.</li>';    
      content +='<li>Uploading a photograph which is not a recent one shall invite rejection of application.</li>';    
      content +='<li>Avoid selfies, selfies shall be rejected.</li>';    
      content +='<li>No object should be in the background. The image must not include other objects or additional people. Ensure that you are only one person in picture. Images having any object in the background shall be rejected.</li>';    
      content +='</ul>';  

      content +='<h4 class="guidelines_header mt-5 mb-3 pb-2 text-center">Acceptable Photograph Guide</h4>';
      content +='<ul>';
      content +='<li>Photo should have full face (from top of hair to bottom on chin), The image should have face prominently visible (80% of the image) with both ears visible.</li>';    
      content +='<li>Able to fit into the template given below, with the eyes positioned in the shaded area.';    
      content +='<a class="example-image-link" data-lightbox="Photo_guidelines" href="<?php echo base_url(); ?>assets/images/photo_upload_guidelines.jpg" target="_blank"><img src="<?php echo base_url(); ?>assets/images/photo_upload_guidelines.jpg"></a></li>';    
      content +='<li>Eyes open, center head within frame whereby the head faces the camera directly, looking straight at camera, clearly visible and close to camera.</li>';    
      content +='<li>The background should be a plain white or off-white background.</li>';    
      content +='<li>Photo should not be Blur/with dim/improper light/over exposed/shadows on face.</li>';    
      content +='</ul>';
      content +='<div>';
      $("#GuidelinesModalContent").html(content);
    }
    else if(current_image_id == 'scannedsignaturephoto')
    {
      $("#GuidelinesModalTitle").html('Signature Upload Guidelines');

      var content = '';
      content +='<div class="guidelines_style2 mt-5">';
      content +='<ul>';
      content +='<li>Dimensions : 140(width) x 60(height) pixels (preferred).</li>';
      content +='<li>Signature uploaded should be of appropriate size and clearly visible.</li>';
      content +='<li>Signature should be strictly upload under signature upload section.</li>';
      content +='<li>Photo and Signature should not be merged and upload together.</li>';
      content +='<li>Use a black or dark blue ink pen to sign within this box.</li>';
      content +='<li>Click the signature within the box in bright light conditions using any digital device (preferably with > 5-megapixel resolution). Avoid using flash.</li>';
      content +='<li>Check the shadow of your hands/camera/smartphone etc. does not fall on the sheet.</li>';
      content +='<li>Signature done on the blank white page without lines only will be accepted.</li>';
      content +='<li>Crop only box area and not the complete white page.</li>';    
      content +='</ul>';

      content +='<h4 class="guidelines_header mt-5 mb-3 pb-2 text-center">Acceptable Signature Guide</h4>';
      content +='<ul>';
      content +='<li>The applicant has to sign on white paper and with Black Ink pen and scan it as image.</li>';    
      content +='<li>Sign image should be horizontal and clearly visible.</li>';    
      content +='</ul>';
      content +='</div>';
      $("#GuidelinesModalContent").html(content);
    }
    else if(current_image_id == 'idproofphoto')
    {
      $("#GuidelinesModalTitle").html('ID Proof Upload Guidelines');  
      
      var content = '';
      content +='<div class="guidelines_style2 mt-5">';
      content +='<ul>';
      content +='<li>ID Proof uploaded should be of clearly visible.</li>';    
      content +='<li>Incorrect size or cropped ID proof shall not be accepted.</li>';    
      content +='</ul>';

      content +='<h4 class="guidelines_header mt-5 mb-3 pb-2 text-center">Acceptable Photo ID Guide</h4>';
      content +='<ul>';
      content +='<li>ID proof to be submitted should be clear / readable and verifiable.</li>';    
      content +='<li>Photo ID image should be clearly visible.</li>';    
      content +='</ul>';
      content +='</div>';  
      $("#GuidelinesModalContent").html(content);  
    }
    else if(current_image_id == 'declaration' || current_image_id == 'declarationform')
    {
      $("#GuidelinesModalTitle").html('Declaration Upload Guidelines');    
      
      var content = '';
      content +='<div class="guidelines_style2 mt-5">';
      content +='<ul>';
      content +='<li>Declaration uploaded should be clearly visible.</li>';    
      content +='<li>Incorrect size or cropped declaration shall not be accepted.</li>';    
      content +='</ul>';
      content +='</div>';  
      $("#GuidelinesModalContent").html(content);    
    }
    else if(current_image_id == 'empidproofphoto')
    {
      $("#GuidelinesModalTitle").html('Employment Proof Upload Guidelines');    
      
      var content = '';
      content +='<div class="guidelines_style2 mt-5">';
      content +='<ul>';
      content +='<li>Employment Proof uploaded should be clearly visible.</li>';    
      content +='<li>Incorrect size or cropped Employment Proof shall not be accepted.</li>';    
      content +='</ul>';
      content +='</div>';  
      $("#GuidelinesModalContent").html(content);    
    }
    else if(current_image_id == 'bank_bc_id_card')
    {
      $("#GuidelinesModalTitle").html('Bank BC ID Card Upload Guidelines');    
      
      var content = '';
      content +='<div class="guidelines_style2 mt-5">';
      content +='<ul>';
      content +='<li>Bank BC ID Card uploaded should be clearly visible.</li>';    
      content +='<li>Incorrect size or cropped Bank BC ID Card shall not be accepted.</li>';    
      content +='</ul>';
      content +='</div>';  
      $("#GuidelinesModalContent").html(content);    
    }
    else
    {
      $("#GuidelinesModalTitle").html('Guidelines');
      $("#GuidelinesModalContent").html(''); 
    }
    
    $("#optionsModal").modal("hide");
    $("#GuidelinesModal").modal({ backdrop: 'static', keyboard: false }, 'show')
    $("body").addClass("modal-open-custom");
  }

  function inc_show_title_messages(current_image_id, db_tbl_name)////
  {
    if (db_tbl_name == 'iibfbcbf_batch_candidates') //FOR IIBF BCBF MODULE
    {
      if (current_image_id == 'candidate_photo') 
      {
        $("#img_pop_up_title").html('Upload Passport Photograph of the Candidate');
        $("#cropModal .modal-title").html('Passport Photograph of the Candidate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      } 
      else if (current_image_id == 'candidate_sign') 
      {
        $("#img_pop_up_title").html('Upload Signature of the Candidate');
        $("#cropModal .modal-title").html('Signature of the Candidate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      } 
      else if (current_image_id == 'id_proof_file') 
      {
        $("#img_pop_up_title").html('Upload Proof of Identity');
        $("#cropModal .modal-title").html('Proof of Identity');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      } 
      else if (current_image_id == 'qualification_certificate_file') 
      {
        $("#img_pop_up_title").html('Upload Qualification Certificate');
        $("#cropModal .modal-title").html('Qualification Certificate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      }
    }
    else if (db_tbl_name == 'member_registration') //FOR MEMBER / NON MEMBER
    {
      if (current_image_id == 'scannedphoto') 
      {
        $("#img_pop_up_title").html('Upload your scanned Photograph');
        $("#cropModal .modal-title").html('Upload your scanned Photograph');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg file upto 20MB');
      } 
      else if (current_image_id == 'scannedsignaturephoto') 
      {
        $("#img_pop_up_title").html('Upload your scanned Signature');
        $("#cropModal .modal-title").html('Upload your scanned Signature');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg file upto 20MB');
      }
      else if (current_image_id == 'idproofphoto') 
      {
        $("#img_pop_up_title").html('Upload your ID Proof');
        $("#cropModal .modal-title").html('Upload your ID Proof');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg file upto 20MB');
      }
      else if (current_image_id == 'declaration' || current_image_id == 'declarationform') 
      {
        $("#img_pop_up_title").html('Upload your declaration');
        $("#cropModal .modal-title").html('Upload your declaration');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg file upto 20MB');
      }
      else if (current_image_id == 'empidproofphoto') 
      {
        $("#img_pop_up_title").html('Upload your employment proof');
        $("#cropModal .modal-title").html('Upload your employment proof');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg file upto 20MB');
      }
      else if (current_image_id == 'bank_bc_id_card') 
      {
        $("#img_pop_up_title").html('Upload your Bank BC ID');
        $("#cropModal .modal-title").html('Upload your Bank BC ID');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg file upto 20MB');
      }
    }
    else if (db_tbl_name == 'dra_members') //FOR IIBF DRA MODULE
    {
      if (current_image_id == 'candidate_photo') 
      {
        $("#img_pop_up_title").html('Upload Passport Photograph of the Candidate');
        $("#cropModal .modal-title").html('Passport Photograph of the Candidate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      } 
      else if (current_image_id == 'candidate_sign') 
      {
        $("#img_pop_up_title").html('Upload Signature of the Candidate');
        $("#cropModal .modal-title").html('Signature of the Candidate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      } 
      else if (current_image_id == 'id_proof_file') 
      {
        $("#img_pop_up_title").html('Upload Proof of Identity');
        $("#cropModal .modal-title").html('Proof of Identity');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 20MB');
      } 
    }
  }

  function remove_custom_class() 
  {
    $("body").removeClass("modal-open-custom");
  }
</script>