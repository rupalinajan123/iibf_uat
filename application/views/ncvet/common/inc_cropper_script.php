<!-- Modals -->
<div class="modal inmodal fade" id="optionsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lgx">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close closeoptionsModal" data-dismiss="modal"><span aria-hidden="true" onclick="remove_custom_class()">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="img_pop_up_title">Select The Option</h4>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" name="current_image_id" id="current_image_id">
        <input type="hidden" name="db_tbl_name" id="db_tbl_name">
        <input type="hidden" name="edit_btn_title" id="edit_btn_title">
        <input type="hidden" name="selected_image_name" id="selected_image_name">
        <input type="hidden" name="selected_image_type" id="selected_image_type">

        <?php
        $fileChooser_accepted_files = '.png, .jpg, .jpeg, .pdf ';
        if (isset($inc_fileChooser_accepted_files) && $inc_fileChooser_accepted_files != '') {
          $fileChooser_accepted_files = $inc_fileChooser_accepted_files;
        } ?>
        <input class="sr-only" id="fileChooser" type="file" name="selected_image" accept="<?php echo $fileChooser_accepted_files; ?>">


        <?php

        if (isset($page_name) && in_array($page_name, array('ncvet_admin_add_candidate', 'candidate_enrollment', 'ncvet_candidate_update_profile'))) { ?>
          <label class="btn btn-primary mb-0" onclick="open_guidelines_modal()">Upload</label>
          <!--<button id="openWebCamModal" class="btn btn-primary">Camera</button> -->
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
        <div class="guidelines_style1"></div>
        <div id="GuidelinesModalContent"></div>
        <label for="fileChooser" class="btn btn-primary btn-block accept_btn mt-5">Upload</label>
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

<link href="<?php echo auto_version(base_url('assets/ncvet/css/cropper.css')); ?>" rel="stylesheet">
<link href="<?php echo auto_version(base_url('assets/ncvet/css/cropper_style.css')); ?>" rel="stylesheet">

<style>
  #cropimage {
    width: 300px;
    height: 300px;
  }

  body.modal-open-custom {
    padding-right: inherit !important;
  }

  .modal-open-custom {
    overflow: hidden;
  }

  .modal-open-custom .modal {
    overflow-x: hidden;
    overflow-y: auto;
  }

  .hide_input_file_cropper {
    width: 0;
    height: 0;
    padding: 0;
    border: none;
  }
</style>

<script src="<?php echo auto_version(base_url('assets/ncvet/js/webcam.min.js')); ?>"></script>
<script src="<?php echo auto_version(base_url('assets/ncvet/js/cropper.js')); ?>"></script>
<script>
  $(document).ready(function() {
    $('#cropModal').on('hidden.bs.modal', function(e) {
      $('body').removeClass('modal-open-custom');
    });
  });

  (() => {
    const ajax_error = "Error in sending data";

    const disableElement = function(e, x) {
      if (!(e instanceof jQuery)) e = $(e);
      e.attr("disabled", true);
      const html = x ? '<div class="absolute-preloader" ><div class="preloader-message" ><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...</div></div>' : '<div class="absolute-preloader" ><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>';
      e.append(html).css({
        position: "relative",
        opacity: ".5",
        cursor: "not-allowed",
      });
    }

    const enableElement = (e) => {
      if (!(e instanceof jQuery)) e = $(e);
      e.attr("disabled", false);
      e.find('.absolute-preloader').remove();
      e.css({
        opacity: "1",
        cursor: "pointer",
      });
    }

    const fileChooser = $("input[type=file]#fileChooser");
    const cropModal = $("#cropModal");
    const optionsModal = $("#optionsModal");
    const cropArea = $("#cropimage");
    /* const profileImage = $("#profileImage");
    alert(profileImage)	 */

    cropModal.on('hide.bs.modal', () => {
      cropArea.html('<img id="imageprev" src=""/>');
    });

    fileChooser.on("change", (e) => {
      var img_properties = $('#fileChooser')[0].files[0]; /* console.log(img_properties); */
      var img_size_in_kb = parseFloat(img_properties['size']) / parseFloat(1000);
      var img_type_str = img_properties['type'].toLowerCase().replace("image/", "");
      $("#selected_image_name").val(img_properties['name']);
      $("#selected_image_type").val(img_properties['type']);

      var show_error_flag = 0;

      var current_image_id = $('#current_image_id').val(); //allowpdf

      if (current_image_id == 'candidate_photo' || current_image_id == 'candidate_sign') {
        if (img_type_str == 'jpg' || img_type_str == 'jpeg' || img_type_str == 'png') {} else show_error_flag = 1;
      } else {
        //alert(img_type_str);
        if (img_type_str == 'jpg' || img_type_str == 'jpeg' || img_type_str == 'png') {} else if (img_type_str == 'application/pdf') {
          const progress = $('.progress');
          const progressBar = $('.progress-bar');

          let percent = '0';
          let percentage = '0%';
          progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);

          saveImage($('#SaveImage'), progress, progressBar);
        } else
          show_error_flag = 1;
      }

      var valid_extension_string = "<?php echo $fileChooser_accepted_files; ?>";
      // Convert the valid_extension_string to an array and remove leading dots
      var valid_extensions_arr = valid_extension_string.replace(/\s/g, '').split(',').map(function(ext) {
        return ext.replace('.', '');
      });


      $("#img_pop_up_error").html("");
      if (img_size_in_kb == 0) {
        show_error_flag = 1;
        $("#img_pop_up_error").html("Please upload valid image")
      } else if ($.inArray(img_type_str, valid_extensions_arr) === -1) {
        show_error_flag = 1;
        $("#img_pop_up_error").html("Please upload only " + valid_extension_string + " image")
      } else if (img_size_in_kb > 20000) {
        show_error_flag = 1;
        $("#img_pop_up_error").html("Please upload image having size less than 5MB")
      } else {
        show_error_flag = 0;

        optionsModal.modal("hide");
        $("#GuidelinesModal").modal("hide");
        cropModal.modal({
          backdrop: 'static',
          keyboard: false
        }, "show");
        $("body").addClass("modal-open-custom");

        var image = document.querySelector('#imageprev');
        var files = e.target.files;
        var done = function(url) {
          e.target.value = '';
          image.src = url;
          cropModal.modal({
            backdrop: 'static',
            keyboard: false
          });
          cropImage();
        };

        var reader;
        var file;
        var url;
        if (files && files.length > 0) {
          file = files[0];
          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function(e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
      }


    });

    // Webcamera
    const webCamModal = $("#webCamModal");

    $("#openWebCamModal").on('click', function(event) {
      event.preventDefault();

      optionsModal.modal("hide");
      $("#GuidelinesModal").modal("hide");
      $("body").removeClass("modal-open-custom");
      //webCamModal.modal("show");

      let All_mediaDevices = navigator.mediaDevices
      if (!All_mediaDevices || !All_mediaDevices.getUserMedia) {
        //console.log("getUserMedia() not supported.");
        sweet_alert_error("getUserMedia() not supported.");
        return;
      }

      All_mediaDevices.getUserMedia({
          audio: true,
          video: true
        }).then(function(vidStream) {
          var video = document.getElementById('videoCam');
          if ("srcObject" in video) {
            video.srcObject = vidStream;
          } else {
            video.src = window.URL.createObjectURL(vidStream);
          }

          video.onloadedmetadata = function(e) {
            video.play();
          };

          $("#webCamModal").modal({
            backdrop: 'static',
            keyboard: false
          }, "show");
          configure_webcam_cm();
        })
        .catch(function(e) {
          //configure();
          console.log(e.name + ": " + e.message);

          if (e.name == "NotAllowedError") {
            $("#webcamErrormodalMessage").html("To use the camera, please grant permission to your browser.");
            configure_webcam_cm();
            $("#webCamModal").modal("hide");
            $("body").removeClass("modal-open-custom");
            /* //$("#webCamModal").modal("show"); */
            /* //$("#webCameraArea").html('Persmission Denide OR Web camp not found'); */
          } else if (e.name == "NotFoundError") {
            /* //$("#webcamErrormodalMessage").html("This device does not have a camera."); */
            sweet_alert_error("This device does not have a camera.");
            configure_webcam_cm();
            $("#webCamModal").modal("hide");
            $("body").removeClass("modal-open-custom");
            /* //$("#webCamModal").modal("show"); */
            /* //$("#webCameraArea").html('Persmission Denide OR Web camp not found');  */
          } else if (e.name == "NotReadableError" || e.name == "Webcam.js Error" || e.name == "AbortError") {
            /* //$("#webcamErrormodalMessage").html("This device does not have a camera."); */
            sweet_alert_error(e.message);
            configure_webcam_cm();
            $("#webCamModal").modal("hide");
            $("body").removeClass("modal-open-custom");
          } else {
            $("#webCamModal").modal({
              backdrop: 'static',
              keyboard: false
            }, "show");
            $("body").addClass("modal-open-custom");
            configure_webcam_cm();
          }
        });
    });

    $("#spanshot").on('click', function(event) {
      event.preventDefault();
      take_snapshot();
      webCamModal.modal("hide");
      //$("body").removeClass("modal-open-custom");
    });

    function configure() {
      Webcam.set({
        /* width: 825, height: 615, */
        width: 640,
        height: 480,
        image_format: 'jpeg',
        jpeg_quality: 100
      });
      Webcam.attach("#webCameraArea");
    }

    function take_snapshot() {
      Webcam.snap(function(data_uri) {
        $("#selected_image_name").val('webcam.png');
        $("#selected_image_type").val('png');

        webCamModal.modal('hide');
        $("body").removeClass("modal-open-custom");
        cropModal.modal({
          backdrop: 'static',
          keyboard: false
        });
        cropArea.html('<img id="imageprev" src="' + data_uri + '"/>');
        cropImage();
        cropModal.modal({
          backdrop: 'static',
          keyboard: false
        }, "show");
        $("body").addClass("modal-open-custom");
      });
      Webcam.reset();
    }

    /*webCamModal.on('show.bs.modal', function () {
      configure();
    });*/

    function configure_webcam_cm() {
      configure();

      var now = Date.now();
      navigator.mediaDevices.getUserMedia({
          audio: true,
          video: false
        })
        .then(function(stream) {
          //alert('Allowed :', Date.now() - now);
        })
        .catch(function(err) {
          //alert("Please clear your browser permission to access the camera."); 
          //navigator.mediaDevices.getUserMedia({video: true})
          $("#webCamModal").modal("hide");
          $("body").removeClass("modal-open-custom");
          //$("#webcamErrormodal").modal('show');
        });
    }

    webCamModal.on('hide.bs.modal', function() {
      Webcam.reset();
      //cropModal.modal("show");
      cropArea.html('<img id="imageprev" src=""/>');
    });

    // CROP IMAGE AFTER UPLOAD 
    var cropper;

    function cropImage() {
      var current_image_id = $("#current_image_id").val();
      var db_tbl_name = $("#db_tbl_name").val();
      var aspectRatio_val = '';

      if (db_tbl_name == 'ncvet_candidates') //FOR IIBF NCVET CANDIDATES
      {
        if (current_image_id == 'candidate_photo') {
          aspectRatio_val = 20 / 23;
        } else if (current_image_id == 'candidate_sign') {
          aspectRatio_val = 7 / 3; //width/height
        }
      }



      var image = document.querySelector('#imageprev');
      // $(image).on("load", () => {
      setTimeout(() => {
        var minAspectRatio = 1;
        var maxAspectRatio = 1;
        cropper = new Cropper(image, {
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
          ready: function() {
            var cropper = this.cropper;
            var containerData = cropper.getContainerData();
            var cropBoxData = cropper.getCropBoxData();
            var aspectRatio = cropBoxData.width / cropBoxData.height;
            var newCropBoxWidth;
            cropper.setDragMode("move");

          },
        });
      }, 500);

      $('#scaleY').off('click').on('click', function() {
        //alert("scaleY")
        var Yscale = cropper.imageData.scaleY;
        if (Yscale == 1) {
          cropper.scaleY(-1);
        } else {
          cropper.scaleY(1);
        };
      });

      $('#scaleX').off('click').on('click', function() {
        //alert("scaleX")
        var Xscale = cropper.imageData.scaleX;
        if (Xscale == 1) {
          cropper.scaleX(-1);
        } else {
          cropper.scaleX(1);
        };
      });

      $('#rotateR').off('click').on('click', function() {
        //alert("rotateR"); 
        cropper.rotate(45);
      });

      $('#rotateL').off('click').on('click', function() {
        //alert("rotateL"); 
        cropper.rotate(-45);
      });

      $('#reset').off('click').on('click', function() {
        //alert("reset"); 
        cropper.reset();
      });

      $('#imageprev').on('error', function() {
        setTimeout(function() {
          $("#cropModal").modal("hide");
          $("body").removeClass("modal-open-custom");
        }, 500);

        swal({
          title: "Error",
          text: "Please select valid image",
          type: "error",
          closeOnConfirm: true
        }, function() {
          $("#cropModal").modal("hide");
          $("body").removeClass("modal-open-custom");
        });
      })
    };

    $(document).on('click', '#SaveImage', function(event) {
      const t = $(this);
      event.preventDefault();
      const progress = $('.progress');
      const progressBar = $('.progress-bar');
      canvas = cropper.getCroppedCanvas({
        /* width: 300,
        height: 300, */
      });

      let percent = '0';
      let percentage = '0%';
      progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);

      progress.show();
      canvas.toBlob(function(blob) {
        saveImage(t, progress, progressBar, blob);
      }, $("#selected_image_type").val());
    })

    $('.edit_existing_image').off('click').on('click', function(e) {
      var current_image_id = $(this).data('current_image_id');
      var db_tbl_name = $(this).data('db_tbl_name');
      var edit_btn_title = $(this).data('title');
      inc_edit_img(current_image_id, db_tbl_name, edit_btn_title);
    });

    function saveImage(t, progress, progressBar, blob = '') {
      const formData = new FormData();
      if (blob != '') {
        formData.append('selected_image', blob, $("#selected_image_name").val());
      } else {

        const fileInput = document.getElementById('fileChooser');
        const file = fileInput.files[0]; // File object

        formData.append('selected_image', file, $("#selected_image_name").val());
      }
      //alert('here');
      formData.append('current_image_id', $("#current_image_id").val());
      formData.append('db_tbl_name', $("#db_tbl_name").val());


      $("#page_loader").show();
      $.ajax('<?php echo site_url('ncvet/save_cropper_image/save_image'); ?>', {
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        beforeSend: function() {
          disableElement(t);
        },
        xhr: function() {
          const xhr = new XMLHttpRequest();
          xhr.upload.onprogress = function(e) {
            percent = '0';
            percentage = '0%';
            if (e.lengthComputable) {
              percent = Math.round((e.loaded / e.total) * 100);
              percentage = percent + '%';
              progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
            }
          };
          return xhr;
        },
        success: function(data) {
          try {
            
            if ($.trim(data.flag) == 'success') {
              cropModal.modal('hide');
              $('#GuidelinesModal').modal('hide');
              $("body").removeClass("modal-open-custom");

              progress.hide();

              var db_tbl_name = $("#db_tbl_name").val();
              var current_image_id = $("#current_image_id").val();
              var edit_btn_title = $("#edit_btn_title").val();

              var preview_id = current_image_id + "_preview";

              var data_lightbox_hidden = $("#data_lightbox_hidden").val();
              var data_lightbox_title_hidden = $("#data_lightbox_title_hidden").val();

              if (typeof data_lightbox_hidden === "undefined") {
                data_lightbox_hidden = 'candidate_images';
              }
              if (typeof data_lightbox_title_hidden === "undefined") {
                data_lightbox_title_hidden = '';
              }

              var currentTime = new Date().getTime();

              var lightbox_data_title = '';
              if (current_image_id == 'id_proof_file') {
                lightbox_data_title = 'Proof of Identity - ';
              } else if (current_image_id == 'aadhar_file') {
                lightbox_data_title = 'Aadhar File - ';
              } else if (current_image_id == 'qualification_certificate_file') {
                lightbox_data_title = 'Qualification Certificate - ';
              } else if (current_image_id == 'candidate_photo') {
                lightbox_data_title = 'Passport-size Photo of the Candidate - ';
              } else if (current_image_id == 'candidate_sign') {
                lightbox_data_title = 'Signature of the Candidate - ';
              } else if (current_image_id == 'exp_certificate') {
                lightbox_data_title = 'Experience Certificate - ';
              } else if (current_image_id == 'institute_idproof') {
                lightbox_data_title = 'Institutional IDproof - ';
              } else if (current_image_id == 'scanned_vis_imp_cert') {
                lightbox_data_title = 'PWD certificate - ';
              } else if (current_image_id == 'scanned_orth_han_cert') {
                lightbox_data_title = 'PWD certificate - ';
              } else if (current_image_id == 'scanned_cer_palsy_cert') {
                lightbox_data_title = 'PWD certificate - ';
              } else if (current_image_id == 'declaration' || current_image_id == 'declarationform') {
                lightbox_data_title = 'Declaration - ';
              }
              console.log(preview_id);
              if (blob != '') {
                $("#" + preview_id).html('<a href="' + data.response_msg + '?' + currentTime + '" class="example-image-link" data-lightbox="' + data_lightbox_hidden + '" data-title="' + lightbox_data_title + data_lightbox_title_hidden + '"><img src="' + data.response_msg + '?' + currentTime + '"></a><button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="' + current_image_id + '" data-db_tbl_name="' + db_tbl_name + '" data-title="' + edit_btn_title + '" title="' + edit_btn_title + '" alt="' + edit_btn_title + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>');
              } else {
                $("#" + preview_id).html("Selected");
                $("#" + preview_id).html('<a target="_blank" href="' + data.response_msg + '?' + currentTime + '"  data-title="' + lightbox_data_title + data_lightbox_title_hidden + '">View File</a>');
              }
              var cropper_image_id = current_image_id + "_cropper";
              $("#" + cropper_image_id).val(data.response_msg);

              var error_image_id = current_image_id + "-error";
              $("#" + error_image_id).remove();

              $('.edit_existing_image').off('click').on('click', function(e) {
                var current_image_id = $(this).data('current_image_id');
                var db_tbl_name = $(this).data('db_tbl_name');
                var edit_btn_title = $(this).data('title');
                inc_edit_img(current_image_id, db_tbl_name, edit_btn_title);
              });

              $("#page_loader").hide();
            } else {
              progressBar.width(0).attr('aria-valuenow', 0).text(0);
              $(".progress").hide();
              if (data.response_msg != "") {
                sweet_alert_error(data.response_msg);
              } else {
                sweet_alert_error("Error Occurred. Please try again.");
              }
              $("#page_loader").hide();
            }
          } catch (e) {
            //sweetAlert("error", e);
            alert("error" + e);
            progressBar.width(0).attr('aria-valuenow', 0).text(0);
            $(".progress").hide();
            sweet_alert_error("Error Occurred. Please try again..");
            //sweet_alert_error("Error Occurred. Please try again."+e);
            $("#page_loader").hide();
          }
        },
        error: function() {
          //sweetAlert("error", ajax_error);
          //alert("error"+ajax_error);
          progressBar.width(0).attr('aria-valuenow', 0).text(0);
          $(".progress").hide();
          sweet_alert_error("Error Occurred. Please try again...");
          //sweet_alert_error("Error Occurred. Please try again."+ajax_error);
          $("#page_loader").hide();
        },
        complete: function() {
          enableElement(t);
        }
      });
    }

    function inc_edit_img(current_image_id, db_tbl_name, edit_btn_title) {
      $("#current_image_id").val(current_image_id);
      $("#db_tbl_name").val(db_tbl_name);
      $("#edit_btn_title").val(edit_btn_title);

      inc_show_title_messages(current_image_id, db_tbl_name);

      var current_image_link = $("#" + current_image_id + "_preview img").attr('src').split('?');
      var current_image_full_path = current_image_link[0];

      var img = new Image();
      img.src = current_image_full_path;

      img.onload = function() {
        var urlParts = current_image_full_path.split('/');
        var selected_imageName = urlParts[urlParts.length - 1];

        var imageType = selected_imageName.split('.').pop();
        if (imageType == 'jpg') {
          imageType = 'jpeg';
        }
        var selected_image_type = 'image/' + imageType;

        $("#selected_image_name").val(selected_imageName);
        $("#selected_image_type").val(selected_image_type);
      };

      var currentTime = new Date().getTime();
      const url = current_image_full_path + '?' + currentTime;
      $("#cropimage").html('<img id="imageprev" src="' + url + '" />');
      $("#optionsModal").modal("hide");
      $("#GuidelinesModal").modal("hide");
      $('#cropModal').modal("show");
      $('#cropModal').modal({
        backdrop: "static"
      });
      cropImage();
    }

  })();

  function open_img_upload_modal(current_image_id, db_tbl_name, edit_btn_title) {

    $("#current_image_id").val(current_image_id);
    $("#db_tbl_name").val(db_tbl_name);
    $("#edit_btn_title").val(edit_btn_title);

    inc_show_title_messages(current_image_id, db_tbl_name);

    //$("#optionsModal").modal({ backdrop: 'static', keyboard: false }, 'show')
    $("body").addClass("modal-open-custom");
    open_guidelines_modal();
  }

  function open_guidelines_modal() {
    //$(".closeoptionsModal").trigger('click');
    var current_image_id = $("#current_image_id").val();

    if (current_image_id == 'candidate_photo') {
      $("#GuidelinesModalTitle").html('Guidelines for Uploading Photographs');

      var content = '';
      content += '<div class="guidelines_style2 ">';
      /*content +='<h4 class="guidelines_header mt-4 mb-3 pb-2 text-center">Acceptable Photograph Guide: </h4>';
      
      content +='<ul>';
      content +='<li>Photo should have full face (from top of hair to bottom on chin), The image should have face prominently visible (80% of the image) with both ears visible. </li>';
      content +='<li>Able to fit into the template given below, with the eyes positioned in the shaded area. <br>';
      content +='<img style="height: 120px;" src="<?php echo base_url(); ?>/uploads/ncvet/photo-g-1.JPG">';
      content +='</li>';
      content +='<li>Eyes open, center head within frame whereby the head races the camera directly. looking straight at camera. clearly visible and close to camera</li>';
      content +='<li>The background should be a plain white or off-white background </li>';
      content +='<li>Photo should not be Blur/with dim/improper light/over exposed/shadows on face</li>';
      content +='<li>The photo-print should be clear undamaged (not torn, creased or marked) 5) Photo with Gaps/Hats, Mask and dark glasses not permitted. </li>';
      content +='<li>No sunglasses or tinted spectacles, and no hair across the eyes';

              content +='Format: - jpg 8 bit. ';
              content +='Size: - Minimum 8 KB and maximum 50KB  ';
              content +='Dimensions: - I 00(Width) x I 20(Height) pixel Only} . ';

      content +='</li>';
        
      content +='</ul>';

      content +='<h4 class="guidelines_header mt-4 mb-3 pb-2 text-center">Sample of Phots/Signature/Photo ID </h4>';*/
      content += '<img style="width:100%;" src="<?php echo base_url(); ?>/uploads/ncvet/photo-guide-1.jpg">';
      content += '<img style="width:100%;" src="<?php echo base_url(); ?>/uploads/ncvet/photo-guide-2.jpg">';
      content += '<div>';
      $("#GuidelinesModalContent").html(content);
    } else if (current_image_id == 'candidate_sign') {
      $("#GuidelinesModalTitle").html('Guidelines for Uploading Signature');

      var content = '';
      content += '<div class="guidelines_style2">';
      content += '<img style="width:100%;" src="<?php echo base_url(); ?>/uploads/ncvet/sign-guide.jpg">';
      content += '</div>';

      $("#GuidelinesModalContent").html(content);
    } else if (current_image_id == 'aadhar_file' || current_image_id == 'id_proof_file') {
      $("#GuidelinesModalTitle").html('Guidelines for Uploading ID');

      var content = '';
      content += '<div class="guidelines_style2 ">';
      content += '<img style="width:100%;" src="<?php echo base_url(); ?>/uploads/ncvet/id-guide.jpg">';
      content += '</div>';
      $("#GuidelinesModalContent").html(content);
    } else {
      $("#GuidelinesModalTitle").html('Guidelines for uploading Documents/Certificates');

      var content = '';
      content += '<div class="guidelines_style2 ">To upload certificates successfully, Candidates must check the specific file formats (PDF, JPG, JPEG, PNG), file size limits (e.g., 5MB), and colour requirements. Ensure the documents are clear, legible, and in colour, and upload them in the specified file format and size. ';
      content += '<br><br><b>The Upload Process </b><br><br><ul>';
      content += '<li><b>Scan Documents: </b><br>Scan your documents using a colour scanner to produce clear, legible, and high-quality images. </li>';
      content += '<li><b>Format Multiple Documents: </b><br>If multiple documents (like mark sheets) are required, combine them into a single PDF file in chronological order to prevent overwriting.</li>';
      content += '<li><b>Name Files Correctly:  </b><br>Use appropriate filenames without any special characters</li>';
      content += '<li><b>Upload: </b><br>Follow the on-screen instructions to select and upload your files.</li>';
      content += '<li><b>Verify the document before uploading: </b><br>Ensure the uploaded documents are correct, readable, and match the required specifications. </li>';
      content += '<li><b>Submit: </b><br>Complete the upload process by submitting the application, as simply saving the uploaded images/documents may not get uploaded. </li>';
      content += '</ul>';
      content += '</div>';
      $("#GuidelinesModalContent").html(content);
    }


    $("#optionsModal").modal("hide");
    $("#GuidelinesModal").modal({
      backdrop: 'static',
      keyboard: false
    }, 'show')
    $("body").addClass("modal-open-custom");
  }

  function inc_show_title_messages(current_image_id, db_tbl_name) ////
  {
    if (db_tbl_name == 'ncvet_candidates') //FOR IIBF NCVET MODULE
    {
      if (current_image_id == 'candidate_photo') {
        $("#img_pop_up_title").html('Upload Passport Photograph of the Candidate');
        $("#cropModal .modal-title").html('Passport Photograph of the Candidate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 5MB');
      } else if (current_image_id == 'candidate_sign') {
        $("#img_pop_up_title").html('Upload Signature of the Candidate');
        $("#cropModal .modal-title").html('Signature of the Candidate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 5MB');
      } else if (current_image_id == 'id_proof_file') {
        $("#img_pop_up_title").html('Upload Proof of Identity');
        $("#cropModal .modal-title").html('Proof of Identity');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB');
      } else if (current_image_id == 'aadhar_file') {
        $("#img_pop_up_title").html('Upload Aadhar File');
        $("#cropModal .modal-title").html('Aadhar File');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB');
      } else if (current_image_id == 'qualification_certificate_file') {
        $("#img_pop_up_title").html('Upload Qualification Certificate');
        $("#cropModal .modal-title").html('Qualification Certificate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png , .pdf file upto 5MB');
      } else if (current_image_id == 'exp_certificate') {
        $("#img_pop_up_title").html('Upload Experience Certificate');
        $("#cropModal .modal-title").html('Experience Certificate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png , .pdf file upto 5MB');
      } else if (current_image_id == 'institute_idproof') {
        $("#img_pop_up_title").html('Upload Institutional ID Proof');
        $("#cropModal .modal-title").html('Institutional ID Proof');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png, .pdf  file upto 5MB');
      } else if (current_image_id == 'declarationform') {
        $("#img_pop_up_title").html('Upload Declaration');
        $("#cropModal .modal-title").html('Declaration');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png , .pdf file upto 5MB');
      } else if (current_image_id == 'scanned_vis_imp_cert' || current_image_id == 'scanned_orth_han_cert' || current_image_id == 'scanned_cer_palsy_cert') {
        $("#img_pop_up_title").html('Upload PWD certificate');
        $("#cropModal .modal-title").html('PWD certificate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB');
      }


    } else if (db_tbl_name == 'image_process_global') //FOR GLOBAL IMAGE CROPPING MODULE
    {
      if (current_image_id == 'candidate_photo') {
        $("#img_pop_up_title").html('Upload Photo');
        $("#cropModal .modal-title").html('Photo');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 5MB');
      } else if (current_image_id == 'candidate_sign') {
        $("#img_pop_up_title").html('Upload Signature');
        $("#cropModal .modal-title").html('Signature');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png file upto 5MB');
      } else if (current_image_id == 'id_proof_file') {
        $("#img_pop_up_title").html('Upload Proof of Identity');
        $("#cropModal .modal-title").html('Proof of Identity');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB');
      } else if (current_image_id == 'aadhar_file') {
        $("#img_pop_up_title").html('Upload Aadhar File');
        $("#cropModal .modal-title").html('Aadhar File');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB');
      } else if (current_image_id == 'qualification_certificate_file') {
        $("#img_pop_up_title").html('Upload Qualification Certificate');
        $("#cropModal .modal-title").html('Qualification Certificate');
        $("#img_pop_up_note").html('Note: Please select only .jpg, .jpeg, .png , .pdf file upto 5MB');
      }
    }
  }

  function remove_custom_class() {
    $("body").removeClass("modal-open-custom");
  }
</script>