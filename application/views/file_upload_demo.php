<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Upload Demo</title>

  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/bootstrap/css/bootstrap.min.css">
  <script src="<?php echo base_url() ?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>

  <style>
    .file_upload_form {
      max-width: 600px;
      margin: 50px auto;
      padding: 0;
      border: 1px solid #ccc;
    }

    .file_upload_form h3 {
      font-weight: 600;
      text-align: center;
      font-size: 22px;
      border-bottom: 1px solid #ccc;
      margin: 0;
      padding: 25px 0 20px 0;
      background: rgba(0, 0, 0, 0.03);
    }

    .main_form_outer {
      padding: 40px 20px 20px;
    }

    .submit_btn {
      text-align: center;
      border-top: 1px solid #ccc;
      margin: 30px 0 0 0;
      padding: 15px 0 0 0;
    }

    .disp_img_outer {
      width: 100px;
      height: 100px;
      overflow: hidden;
      border: 1px solid #ccc;
      padding: 2px;
      display: inline-block;
      vertical-align: middle;
      line-height: 90px;
      text-align: center;
    }

    .disp_img_outer img {
      max-width: 94px;
      max-height: 94px;
    }

    .disp_img_outer h4 {
      margin: 0;
      padding: 0;
      font-size: 20px;
      text-transform: uppercase;
      vertical-align: middle;
      display: inline-block;
    }

    .form_note {
      line-height: 16px !important;
      font-size: 11px;
      font-weight: 600;
      display: block;
      margin: 3px 0 3px 0;
    }
  </style>
</head>

<body>
  <div id="wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <form class="form-horizontal file_upload_form" name="file_upload_form" id="file_upload_form" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="upload_form_hidden" value="1">
            <h3>File Upload Demo</h3>
            
            <div class="main_form_outer">
              <?php if ($this->session->flashdata('error') != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php }

              if ($this->session->flashdata('success') != '') { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('success'); ?>
                </div>
              <?php } ?>

              <div class="form-group">
                <label for="" class="col-sm-3 control-label">Select File<sup class="text-danger">*</sup></label>
                <div class="col-sm-6">
                  <input type="file" class="form-control" name="upload_file" id="upload_file" accept="<?php echo $upload_file_ext; ?>" data-accept="<?php echo $upload_file_ext; ?>" onchange="validateFileAll(event, 'upload_file_error', 'upload_file_preview','<?php echo $upload_file_max_size; ?>')" required>
                  <small class="text-primary form_note">Note : Please Upload only <?php echo str_replace(",", ", ", $upload_file_ext); ?> Files upto size <?php echo $upload_file_max_size; ?></small>
                  <small class="error" id="upload_file_error"></small>
                  <?php if(form_error('upload_file')!=""){ ?> <div class="clearfix"></div><small class="error"><?php echo form_error('upload_file'); ?></small> <?php } ?>
                </div>
                <div class="col-sm-3">
                  <div class="disp_img_outer" id="upload_file_preview">
                    <img src="/assets/images/default1.png" />
                  </div>
                </div>
              </div>

              <div class="submit_btn">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url() ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
  <!--script src="<?php echo base_url('js/validateFile.js') ?>"></script-->
  <script>
    //allowed_max_size = Provide size in kb or mb only (10kb, 20kb, 1mb, 2mb etc)
    //img_width = max allowed width = Provide in number only (100,200,300,400 etc)
    //img_height = max allowed height = Provide in number only (100,200,300,400 etc)
    function validateFileAll(event, error_id, preview_img_id, allowed_max_size = '', img_width = '', img_height = '') {
      var srcid = event.srcElement.id;
      var input_file_id = document.getElementById(srcid).id;

      //console.log(document.getElementById(srcid).files[0]);
      if (document.getElementById(srcid).files.length != 0) //WHEN FILE IS SELECTED
      {
        var file_arr = document.getElementById(srcid).files[0];

        if (file_arr.size == 0) //WHEN FILE SIZE IS ZERO(0), SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
        {
          //console.log("2");          
          $('#' + error_id).text('Please select valid file');
          $('#' + input_file_id).val('')
          //$('#' + preview_img_id ).attr('src', "/assets/images/default1.png");
          $('#' + preview_img_id).html('<img src="/assets/images/default1.png" />');
          return false;
        } else //WHEN SELECTED FILE SIZE IS GREATER THAN ZERO(0)
        {
          var file_size = file_arr.size / 1024;
          var mimeType = file_arr.type;

          //START : SET ALLOWED TYPE ARRAY
          var allowedFiles = [".jpg", ".jpeg", ".png", ".gif"];
          if (typeof $("#" + input_file_id).attr("data-accept") !== 'undefined' && $("#" + input_file_id).attr("data-accept") != "") {
            var allowedFiles = $("#" + input_file_id).attr("data-accept").toLowerCase().split(",");
          } else if ($('#' + input_file_id + '_allowedFilesTypes').text() != "") {
            var allowedFiles = $('#' + input_file_id + '_allowedFilesTypes').text().toLowerCase().split(",");
          }
          //END : SET ALLOWED TYPE ARRAY          

          var fileNameExt = "." + file_arr.name.substr(file_arr.name.lastIndexOf('.') + 1).toLowerCase();
          if (allowedFiles != '*' && $.inArray(fileNameExt, allowedFiles) == -1) //WHEN WRONG EXTENSION FILE SELECTED
          {
            //console.log("3");
            $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " extensions files only.");
            $('#' + input_file_id).val('')
            //$('#' + preview_img_id ).attr('src', "/assets/images/default1.png");
            $('#' + preview_img_id).html('<img src="/assets/images/default1.png" />');
            return false;
          } else //WHEN CORRECT EXTENSION FILE SELECTED
          {
            if (allowed_max_size == '') {
              allowed_max_size = '2mb'; //set default allowed_max_size = 2MB
            }

            if (allowed_max_size != "") {
              if (allowed_max_size.toLowerCase().indexOf('kb') !== -1) {
                var check_size = allowed_max_size.toLowerCase().split('k');
              } else if (allowed_max_size.toLowerCase().indexOf('mb') !== -1) {
                var check_size = allowed_max_size.toLowerCase().split('m');
                check_size[0] = check_size[0] * 1024;
              }
            }
            ////console.log(check_size);
            ////console.log(file_size);

            var reader = new FileReader();
            reader.onload = function(e) {
              var img = new Image();
              img.src = e.target.result;

              //console.log("reader.result : "+reader.result)
              if (reader.result == 'data:') //WHEN FILE IS CORRUPTED, SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
              {
                //console.log("4");
                $('#' + error_id).text('This file is corrupted');
                $('#' + input_file_id).val('')
                //$('#' + preview_img_id ).attr('src', "/assets/images/default1.png");
                $('#' + preview_img_id).html('<img src="/assets/images/default1.png" />');
                return false;
              } else {
                if (check_size != "" && file_size > check_size[0]) //WHEN LARGE FILE IS SELECTED 
                {
                  //console.log("5");
                  $('#' + error_id).text("Please upload file less than " + allowed_max_size);
                  $('#' + input_file_id).val('')
                  //$('#' + preview_img_id ).attr('src', "/assets/images/default1.png");
                  $('#' + preview_img_id).html('<img src="/assets/images/default1.png" />');
                  return false;
                } 
                else 
                {
                  //console.log("6");

                  if (fileNameExt == ".jpg" || fileNameExt == ".jpeg" || fileNameExt == ".png" || fileNameExt == ".gif")
                  {
                    img.onload = function() 
                    {
                      //console.log("7");
                      var width = this.width;
                      var height = this.height;

                      if (img_width == '') 
                      {
                        img_width = '1500'; //set default img_width = 1500px
                      }

                      if (img_height == '') 
                      {
                        img_height = '1500'; //set default img_height = 1500px
                      }

                      if ((img_width != "" && width > img_width) || (img_height != "" && height > img_height)) 
                      {
                        //console.log("8");
                        var err_msg = 'Uploaded File dimensions are ' + width + '*' + height + ' pixel. Please Upload file having ';

                        if (img_width != '') 
                        {
                          err_msg += 'width less than ' + img_width + ' pixel ';
                          if (img_height != '') {
                            err_msg += ' & ';
                          }
                        }

                        if (img_height != '') 
                        {
                          err_msg += 'height less than ' + img_height + ' pixel';
                        }

                        $('#' + error_id).text(err_msg);

                        $('#' + input_file_id).val('')
                        //$('#' + preview_img_id ).attr('src', "/assets/images/default1.png");
                        $('#' + preview_img_id).html('<img src="/assets/images/default1.png" />');
                        return false;
                      } 
                      else
                      {
                        //console.log("9");
                        ////console.log(reader.result);
                        $('#' + error_id).text("");
                        //$('#' + preview_img_id+" img").attr('src', reader.result);
                        $('#' + preview_img_id).html('<img src="' + reader.result + '" />');

                        var img = new Image();
                        img.src = reader.result;
                      }
                    }
                    $('#' + error_id).text('');
                  }
                  else 
                  {
                    $('#' + preview_img_id).html('<h4>' + fileNameExt + '</h4>');
                    $('#' + error_id).text('');
                  }
                }
              }
            }

            reader.readAsDataURL(event.target.files[0]);
          }
        }
      } else //WHEN FILE IS NOT SELECTED, SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
      {
        ////console.log("1");
        $('#' + error_id).text('Please select file');
        $('#' + input_file_id).val('')
        //$('#' + preview_img_id ).attr('src', "/assets/images/default1.png");
        $('#' + preview_img_id).html('<img src="/assets/images/default1.png" />');
        return false;
      }
    }
  </script>
</body>

</html>