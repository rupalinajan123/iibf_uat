<script type="text/javascript" src="<?php echo base_url('js/jquery_validation/jquery.validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery_validation/jquery.validate_additional.js'); ?>"></script>
<script type="text/javascript">
  //Allow only alphabet                           =>  allow_only_alphabets
  //Allow only numbers                            =>  allow_only_numbers
  //Allow only number + float                     =>  allow_only_numbersFloat
  //Allow only alphabet + numbers                 =>  allow_only_alphabetsNumbers
  //Allow only alphabet + numbers + float         =>  allow_only_alphabetsNumbersFloat
  //Allow only alphabet + space                   =>  allow_only_alphabetsSpace
  //Allow only alphabet + numbers + space         =>  allow_only_alphabetsNumbersSpace
  //Allow omly alphabet + numbers + float + space =>  allow_only_alphabetsNumbersFloatSpace

  $(".allowd_only_numbers").keydown(function(e) /***** Input allow only numbers ***********/ {
    // Allow: backspace, delete, tab, escape, enter
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
      // Allow: Ctrl+A
      (e.keyCode == 65 && e.ctrlKey === true) ||
      // Allow: home, end, left, right
      (e.keyCode >= 35 && e.keyCode <= 39)) {
      // let it happen, don't do anything
      return;
    }

    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      e.preventDefault();
    }
  });

  $(".allowd_only_float").keypress(function(e) /***** Input allow only number and float ***********/ {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
      // Allow: Ctrl+A
      (e.keyCode == 65 && e.ctrlKey === true) ||
      // Allow: home, end, left, right
      (e.keyCode >= 35 && e.keyCode <= 39)) {
      // let it happen, don't do anything
      return;
    }

    // Ensure that it is a number and stop the keypress
    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
      e.preventDefault();
    }
  });

  $('.allowed_only_text_with_space').keydown(function(e) /***** Input allow only text with space ***********/ {
    if (e.shiftKey || e.ctrlKey || e.altKey) {
      e.preventDefault();
    } else {
      var key = e.keyCode;
      if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
        e.preventDefault();
      }
    }
  });

  $('.allowed_only_text_without_space').keydown(function(e) /***** Input allow only text without space ***********/ {
    if (e.shiftKey || e.ctrlKey || e.altKey) {
      e.preventDefault();
    } else {
      var key = e.keyCode;
      if (!((key == 8) || (key == 46) || (key == 9) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
        e.preventDefault();
      }
    }
  });

  $('.custom_input_formfields').keyup(function() {
    //$(this).val($(this).val().toUpperCase());
  });

  //<input type="file" class="form-control file_upload" name="banner_img" id="banner_img" value="" accept=".png,.jpeg,.jpg, .gif" onchange="return CheckDimension()" >
  //<span id='banner_img_width' class='d-none'></span>
  //<span id='banner_img_height' class='d-none'></span>
  var _URL = window.URL || window.webkitURL;

  function CheckDimension() {
    $("#banner_img_width").html('');
    $("#banner_img_height").html('');
    var file, img;
    if ((file = $('#banner_img')[0].files[0])) {
      img = new Image();
      var objectUrl = _URL.createObjectURL(file);
      img.onload = function() {
        //alert(this.width + " " + this.height);
        $("#banner_img_width").html(this.width);
        $("#banner_img_height").html(this.height);
        _URL.revokeObjectURL(objectUrl);
      };
      img.src = objectUrl;
    }
  }

  $(document).ready(function() 
  {
    jQuery.validator.addMethod("notEqual", function(value, element, param) {
      return this.optional(element) || value != $("#" + param).val();
    });

    jQuery.validator.addMethod("check_valid_file", function(value, element, param) //for size 0 files
      {
        var isOptional = this.optional(element),
          file;
        if (isOptional) {
          return isOptional;
        }

        if ($(element).attr("type") === "file") {
          if (element.files && element.files.length) {
            file = element.files[0];
            if (file.size > 0) {
              return true;
            }
          }
        }
        return false;
      }, "Please select valid file");
    
    jQuery.validator.addMethod("filesize_min", function(value, element, param) //use size in bytes //filesize_min: 1MB : 1000000
    {
      var isOptional = this.optional(element),
        file;
      if (isOptional) {
        return isOptional;
      }

      if ($(element).attr("type") === "file") {
        if (element.files && element.files.length) {
          file = element.files[0];
          //console.log(file.size+" < "+param);
          return (file.size && file.size >= param);
        }
      }
      return false;
    }, "");

    jQuery.validator.addMethod("filesize_max", function(value, element, param) //use size in bytes //filesize_max: 1MB : 1000000
    {
      var isOptional = this.optional(element),
        file;
      if (isOptional) {
        return isOptional;
      }

      if ($(element).attr("type") === "file") {
        if (element.files && element.files.length) {
          file = element.files[0];
          //console.log(file.size+" < "+param);
          return (file.size && file.size <= param);
        }
      }
      return false;
    }, "");

    
    /* $.validator.addMethod("valid_img_format", function(value, element) 
    {
      if (value != "") {
        var validExts = new Array(".png", ".jpeg", ".jpg", ".gif");
        var fileExt = value.toLowerCase();
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
          return false;
        } else return true;
      } else return true;
    }); */

    //valid_file_format: '.png',
    //valid_file_format: '.png,.pdf',
    $.validator.addMethod("valid_file_format", function(value, element, param) {
      if (value != "" && param != "") {
        var validExts = param.split(',');

        var fileExt = value.toLowerCase();
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
          return false;
        } else return true;
      } else return true;
    }, "Invalid file selection");

    $.validator.addMethod("valid_email", function(value, element) {
      if ($.trim(value) != '') {
        var pattern = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
        return pattern.test(value);
      } else {
        return true;
      }
    });

    $.validator.addMethod("valid_pan_no", function(value, element) {
      if ($.trim(value) != '') {
        var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        if (regex.test(value)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });

    $.validator.addMethod("valid_gst_no", function(value, element) {
      if ($.trim(value) != '') {
        //var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        var regex = new RegExp(/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/);
        if (regex.test(value)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });

    $.validator.addMethod("valid_ifsc_code", function(value, element) {
      if ($.trim(value) != '') {
        //var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        var regex = new RegExp(/^[A-Z]{4}0[A-Z0-9]{6}$/); 
        if (regex.test(value)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });

    jQuery.validator.addMethod("chk_img_height_width", function(value, element) {
      var banner_img_width = $("#banner_img_width").html();
      var banner_img_height = $("#banner_img_height").html();
      if (banner_img_width != "" && banner_img_height != "") {
        if (banner_img_width == 1920 && banner_img_height == 838) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }, "");

    $.validator.addMethod("valid_email_address", function(value, element) 
    {
      if ($.trim(value) != '')
      {
        var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/);
        return pattern.test(value);
      } else { return true; }
    });

    $.validator.addMethod("valid_vendor_name", function(value, element) {
      if ($.trim(value) != '') {
        //var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        var regex = /^[a-zA-Z.0-9\/ ]+$/gi;
        if (regex.test(value)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });

    $.validator.addMethod("valid_any_reg_no", function(value, element) {
      if ($.trim(value) != '') {
        //var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        var regex = /^[a-zA-Z.0-9\-_ ]+$/gi;
        if (regex.test(value)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });

    $.validator.addMethod("valid_url", function(value, element) {
      if ($.trim(value) != '') {
        //var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        var regex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
        if (regex.test(value)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });

    /* $.validator.addMethod("valid_youtube_url", function(value, element) 
    {
      if ($.trim(value) != '')
      {
        var pattern = new RegExp(/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/);
        return pattern.test(value);
      } else { return true; }
    }); */

    $.validator.addMethod("FirstLetterString", function(value, element) {
      if (value != '') {
        var first_letter = value.charAt(0);
        return first_letter.match(/[a-z]/i);
      }
    }, ); //First letter must be string

    $.validator.addMethod("LetterNumberDash", function(value, element) {
      return this.optional(element) || /^[a-z0-9\-_@#$&*!?]+$/i.test(value);
    }, ); //"must contain only letters, numbers or -_@#$&*!?"

    $.validator.addMethod("ValidUsername", function(value, element) {
      return this.optional(element) || /^[a-z0-9\_@#$&*!?.]+$/i.test(value);
    }, ); //"must contain only letters, numbers or _@#$&*!.?"

    $.validator.addMethod("validAddress", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9\s,-/]+$/i.test(value);
    }, 'Please enter valid value'); //"must contain only letters, numbers, space or ,-/"

    $.validator.addMethod("validInput", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9\s&-_#=:/]+$/i.test(value);
    }, 'Please enter valid value'); //"must contain only letters, numbers, space or &-_#=:/"

    $.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z]+$/i.test(value);
    }, 'Only alphabets are allowed')

    $.validator.addMethod("letterandnumber", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
    }, 'Only alphabets and number allowed')

    $.validator.addMethod("letterswithspace", function(value, element) {
      return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, 'Only alphabets and space allowed')

    $.validator.addMethod("required", function(value, element) {
      if ($.trim(value).length == 0) {
        return false;
      } else {
        return true;
      }
    });

    $.validator.setDefaults({
      ignore: ":hidden:not(.chosen-select)"
    }) // For chosen validation

    $.validator.addMethod("pwcheck", function(value) {
      return /[A-Z]/.test(value) // has a uppercase letter
        &&
        /[a-z]/.test(value) // has a lowercase letter
        &&
        /[0-9]/.test(value) // has a digit
        &&
        /[~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<]/.test(value) // has a special character
    });

    $.validator.addMethod("dateFormat", function(value, element, param) {
      if (param == '' || param == 'Y-m-d') {
        return this.optional(element) || value.match(/^\d{4}-((0\d)|(1[012]))-(([012]\d)|3[01])$/);
      }
      /* else if(param == '' || param == 'd-m-Y')
      {
        return this.optional(element) || value.match(/^\d(([012]\d)|3[01])-((0\d)|(1[012]))-{4}$/);
      } */
      else if (param == 'Y') {
        return this.optional(element) || value.match(/^\d{4}$/);
      } else if (param == 'Y-m') {
        return this.optional(element) || value.match(/^\d{4}-((0\d)|(1[012]))$/);
      }
    }, "Please select valid date");
  })
</script>