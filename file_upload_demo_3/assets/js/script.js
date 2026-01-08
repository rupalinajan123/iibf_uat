(() => {
		
  const ajax_error = "Error in sending data";
  
  const sweetAlert = function (type, title) {
    /* if (type == "success") {
      iziToast.success({
              message: title
      });
      } else if (type == "error") {
      iziToast.error({
              message: title
      });
      } else if (type == "waring") {
      iziToast.warning({
              message: title
      });
      } else if (type == "info") {
      iziToast.info({
              message: title
      });
    } */
    alert(message);
  }
  
  const disableElement = function (e, x) {
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
  
  $("#loginForm").on("submit", (e) => {
    const form = $(e.target);
    e.originalEvent.preventDefault();
    const formData = form.serialize();
    $.ajax({
      url: "process.php",
      data: formData + "&case=login",
      method: "POST",
      beforeSend: function () {
        disableElement(form);
      },
      success: function (res) {
        if (res == "success") location.reload();
        else sweetAlert("error", res);
      },
      error: function () {
        sweetAlert("error", ajax_error);
      },
      complete: function () {
        enableElement(form);
      }
    });
  });
  
  
  
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
    optionsModal.modal("hide");
    cropModal.modal("show");
    var image = document.querySelector('#imageprev');
    var files = e.target.files;
    var done = function (url) {
      e.target.value = '';
      image.src = url;
      cropModal.modal({
        backdrop: "static"
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
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });
  
  $(document).on('click', '#saveAvatar', function (event) {
    const t = $(this);
    event.preventDefault();
    const progress = $('.progress');
    const progressBar = $('.progress-bar');
    canvas = cropper.getCroppedCanvas({
      width: 400,
      height: 400,
    });
    progress.show();
    canvas.toBlob(function (blob) {
      const formData = new FormData();
      formData.append('avatar', blob, 'avatar.jpg');
      formData.append('case', 'uploadAvatar');
      formData.append('db_col_name', $("#db_col_name").val());
      
      $.ajax('process.php', {
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          disableElement(t);
        },
        xhr: function () {
          const xhr = new XMLHttpRequest();
          xhr.upload.onprogress = function (e) {
            let percent = '0';
            let percentage = '0%';
            if (e.lengthComputable) {
              percent = Math.round((e.loaded / e.total) * 100);
              percentage = percent + '%';
              progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
            }
          };
          return xhr;
        },
        success: function (res) {
          try {
            res = JSON.parse(res);
            const url = res.url;
            cropModal.modal('hide');
            progress.hide();
            
            const profileImage = $("#"+$("#current_image_id").val());												
            profileImage.attr('src', url);
            } catch (e) {
            //sweetAlert("error", e);
            alert("error"+e);
          }
        },
        error: function () {
          //sweetAlert("error", ajax_error);
          alert("error"+ajax_error);
        },
        complete: function () {
          enableElement(t);
        }
      });
      
    });
  });
  
  $("#editProfile").on("click", (e) => {
    $.ajax({
      url: "process.php",
      data: {
        case: "fetch_profile",
        db_col_name : $("#db_col_name").val(),
      },
      method: "POST",
      beforeSend: function () {
        disableElement(e.currentTarget);
      },
      success: function (res) {
        try {
          res = JSON.parse(res);
          const url = res.url;
          cropArea.html('<img id="imageprev" src="' + url + '" />');
          optionsModal.modal("hide");
          cropModal.modal("show");
          cropModal.modal({
            backdrop: "static"
          });
          cropImage();
          } catch (e) {
          sweetAlert("error", e);
        }
      },
      error: function () {
        sweetAlert("error", ajax_error);
      },
      complete: function () {
        enableElement(e.currentTarget);
      }
    })
  });
  
  $("#removeProfile").on("click", (e) => {
    $.ajax({
      url: "process.php",
      method: "POST",
      data: {
        case: "remove_profile",
        db_col_name : $("#db_col_name").val(),
      },
      beforeSend: function () {
        disableElement(e.target);
      },
      success: function (res) {
        try {
          res = JSON.parse(res);
          const url = res.url;
          optionsModal.modal("hide");
          
          const profileImage = $("#"+$("#current_image_id").val());
          profileImage.attr("src", url);
          } catch (e) {
          sweetAlert("error", e);
        }
      },
      error: function () {
        sweetAlert("error", ajax_error);
      },
      complete: function () {
        enableElement(e.target);
      }
    })
  });
  
  // Webcamera
  const webCamModal = $("#webCamModal");
  
  $("#openWebCamModal").on('click', function (event)
  {    
    event.preventDefault();
    
    optionsModal.modal("hide");
    //webCamModal.modal("show");
    
    let All_mediaDevices=navigator.mediaDevices
    if (!All_mediaDevices || !All_mediaDevices.getUserMedia)
    {
      console.log("getUserMedia() not supported.");
      return;
    }

    All_mediaDevices.getUserMedia(
    {
      audio: true,
      video: true
    })
    .then(function(vidStream) 
    {
      var video = document.getElementById('videoCam');
      if ("srcObject" in video)
      {
        video.srcObject = vidStream;
      }
      else
      {
        video.src = window.URL.createObjectURL(vidStream);
      }

      video.onloadedmetadata = function (e)
      {
        video.play();
      };
      $("#webCamModal").modal("show");
      configure_webcam_cm();  
    })
    .catch(function (e)
    {
      //configure();
      console.log(e.name + ": " + e.message);
      
      if (e.name == "NotAllowedError")
      {
        $("#webcamErrormodalMessage").html("To use the camera, please grant permission to your browser.");
        configure_webcam_cm();
        $("#webCamModal").modal("hide");
        //$("#webCamModal").modal("show");
        //$("#webCameraArea").html('Persmission Denide OR Web camp not found');    
      }
      else if (e.name == "NotFoundError")
      {
        $("#webcamErrormodalMessage").html("This device does not have a camera.");
        configure_webcam_cm();
        $("#webCamModal").modal("hide");
        //$("#webCamModal").modal("show");
        //$("#webCameraArea").html('Persmission Denide OR Web camp not found');    
      }
      else
      {
        $("#webCamModal").modal("show");
        configure_webcam_cm();
      }
    });    
  });
  
  $("#spanshot").on('click', function (event) {
    event.preventDefault();
    take_snapshot();
    webCamModal.modal("hide");
  });   
  
  function configure() {
    Webcam.set({
      width: 640,
      height: 480,
      image_format: 'jpeg',
      jpeg_quality: 100
    });
    Webcam.attach("#webCameraArea");
  }
  
  function take_snapshot() {
    Webcam.snap(function (data_uri) {
      //alert("===="+data_uri);
      webCamModal.modal('hide');
      cropModal.modal({
        backdrop: "static"
      });
      cropArea.html('<img id="imageprev" src="' + data_uri + '"/>');
      cropImage();
      cropModal.modal("show");
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
    navigator.mediaDevices.getUserMedia({audio: true, video: false})
    .then(function (stream)
    {
      //alert('Allowed :', Date.now() - now);
    })
    .catch(function (err)
    {
      //alert("Please clear your browser permission to access the camera!."); 
      //navigator.mediaDevices.getUserMedia({video: true})
      $("#webCamModal").modal("hide");
      $("#webcamErrormodal").modal('show');
    });    
  }
  
  //
  webCamModal.on('hide.bs.modal', function () {
    Webcam.reset();
    //cropModal.modal("show");
    cropArea.html('<img id="imageprev" src=""/>');
  });
  
  
  // CROP IMAGE AFTER UPLOAD 
  var cropper;  
  function cropImage()
  {    
    var image = document.querySelector('#imageprev');
    // $(image).on("load", () => {
    setTimeout(() => {
      var minAspectRatio = 1;
      var maxAspectRatio = 1;
      cropper = new Cropper(image, {
        aspectRatio: 1,
        autoCropArea: 1,
        minCropBoxWidth: 150,
        minCropBoxHeight: 150,
        ready: function () {
          var cropper = this.cropper;
          var containerData = cropper.getContainerData();
          var cropBoxData = cropper.getCropBoxData();
          var aspectRatio = cropBoxData.width / cropBoxData.height;
          var newCropBoxWidth;
          cropper.setDragMode("move");
          if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
            newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);
            console.log(newCropBoxWidth)
            cropper.setCropBoxData({
              left: (containerData.width - newCropBoxWidth) / 2,
              width: newCropBoxWidth
            });
          }
        },
      });
    }, 500);
    
    
    
    $("#scaleY").click(function () {
      var Yscale = cropper.imageData.scaleY;
      if (Yscale == 1) {
        cropper.scaleY(-1);
        } else {
        cropper.scaleY(1);
      };
    });
    $("#scaleX").click(function () {
      var Xscale = cropper.imageData.scaleX;
      if (Xscale == 1) {
        cropper.scaleX(-1);
        } else {
        cropper.scaleX(1);
      };
    });
    $("#rotateR").click(function () {
      cropper.rotate(45);
    });
    $("#rotateL").click(function () {
      cropper.rotate(-45);
    });
    $("#reset").click(function () {
      cropper.reset();
    });
  };
  
})();	