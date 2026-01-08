
  function validateFile(event, error_id, show_img_id, size, img_width, img_height)
  {
    var srcid = event.srcElement.id;    
    if( document.getElementById(srcid).files.length != 0 )
    {
      var file = document.getElementById(srcid).files[0];

      if(file.size == 0)
      {
        $('#'+error_id).text('Please select valid file');
        $('#'+document.getElementById(srcid).id).val('')
        $('#'+show_img_id).attr('src', "/assets/images/default1.png");
      }  
      else
      {
        var file_size = document.getElementById(srcid).files[0].size/1024;
        var mimeType=document.getElementById(srcid).files[0].type;

        var allowedFiles = [".jpg", ".jpeg"];
        if($('#'+document.getElementById(srcid).id+'_allowedFilesTypes').text() != "")
        {
          var allowedFiles = $('#'+document.getElementById(srcid).id+'_allowedFilesTypes').text().split(",");
        }
        var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

        var reader = new FileReader();

        var check_size = '';
      
        if(size.indexOf('kb') !== -1){
          var check_size = size.split('k');
        }
        if(size.indexOf('mb') !== -1){
          var check_size = size.split('m');
        }

        reader.onload = function(e) {
          var img = new Image();      
          img.src = e.target.result;

          if (reader.result == 'data:') {
            $('#'+error_id).text('This file is corrupted');
            //$('.btn_submit').attr('disabled',true);
            //$('#'+show_img_id).removeAttr('src');
            $('#'+document.getElementById(srcid).id).val('')
            $('#'+show_img_id).attr('src', "/assets/images/default1.png");
          } 
          else {
            //$('#'+error_id).text('This file can be uploaded');
            if (!regex.test(file.name.toLowerCase())) {
              $('#'+error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
              //$('.btn_submit').attr('disabled',true);
              //$('#'+show_img_id).removeAttr('src');
              $('#'+document.getElementById(srcid).id).val('')
              $('#'+show_img_id).attr('src', "/assets/images/default1.png");
            }
            else{
              if(file_size > check_size[0]) 
              { 
                // alert(size);
                //console.log('if');
                $('#'+error_id).text("Please upload file less than "+size.toUpperCase());
                //$('.btn_submit').attr('disabled',true);
                //$('#'+show_img_id).removeAttr('src');
                $('#'+document.getElementById(srcid).id).val('')
                $('#'+show_img_id).attr('src', "/assets/images/default1.png");
              } 
              else if(file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
              {
                $('#'+error_id).text("Please upload file having size more than 8KB");
                $('#'+document.getElementById(srcid).id).val('')
                $('#'+show_img_id).attr('src', "/assets/images/default1.png");
              }
              else{
                img.onload = function () {
                  var width = this.width;
                  var height = this.height;

                  //console.log(width+'----'+height);
                  
                  if(width > img_width && height > img_height){
                    $('#'+error_id).text(' Uploaded File dimensions are '+width+'*'+height+' pixel. Please Upload file dimensions between '+img_width+'*'+img_height+' pixel');
                    //$('.btn_submit').attr('disabled',true);
                    //$('#'+show_img_id).removeAttr('src');
                    $('#'+document.getElementById(srcid).id).val('')
                    $('#'+show_img_id).attr('src', "/assets/images/default1.png");
                  }
                  else{
                    //console.log('else');
                    $('#'+error_id).text("");
                    $('.btn_submit').attr('disabled',false);
                    $('#'+show_img_id).attr('src', '');
                    $('#'+show_img_id).removeAttr('src');                  
                    $('#'+show_img_id).attr('src', reader.result);

                    var img = new Image();
                    img.src = reader.result;

                    //$('.'+show_img_id+'_zoom').zoom();
                  }
                }
              
              }
            }
          } 
        }
      
        reader.readAsDataURL(event.target.files[0]);
      }
    }
    else
    {
      $('#'+error_id).text('Please select file');
      //$('.btn_submit').attr('disabled',true);
      //$('#'+show_img_id).removeAttr('src');
      $('#'+document.getElementById(srcid).id).val('')
      $('#'+show_img_id).attr('src', "/assets/images/default1.png");
    }
  }


