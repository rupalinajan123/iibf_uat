
  <script type="text/javascript" src="<?php echo auto_version(base_url('assets/iibfbcbf/jquery_validation/jquery.validate.js')); ?>"></script>
  <script type="text/javascript" src="<?php echo auto_version(base_url('assets/iibfbcbf/jquery_validation/jquery.validate_additional.js')); ?>"></script>
  <script type="text/javascript">
    function inc_custom_input(this_val)// Check for and remove the first space
    {
      var inputValue = this_val.val();
      if (inputValue[0] === ' ') { this_val.val(inputValue.trim()); }
    }

    function inc_custom_input_blur(this_val) // Trim leading and trailing spaces
    {
      this_val.val(this_val.val().trim());
    }

    function restrict_input(e,type)
    {
      var key = e.key;
      var keyCode = e.keyCode;

      if($.inArray(keyCode, [46, 8, 9, 27, 13]) !== -1 || // Allow: backspace, delete, tab, escape, enter
      (keyCode == 65 && e.ctrlKey === true) || // Allow: Ctrl+A
      (keyCode == 67 && e.ctrlKey === true) || // Allow: Ctrl+C
      (keyCode == 86 && e.ctrlKey === true) || // Allow: Ctrl+V
      (keyCode == 88 && e.ctrlKey === true) || // Allow: Ctrl+X
      (e.keyCode >= 35 && e.keyCode <= 39)) // Allow: home, end, left, right
      {
        return;// let it happen, don't do anything
      }

      if(type == 'allow_only_alphabets')
      {
        if (!/^[a-zA-Z]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_alphabets_and_space') //Allow only alphabet + space 
      {
        if (!/^[a-zA-Z\s]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_alphabets_and_numbers') //Allow only alphabet + numbers
      {
        if (!/^[a-zA-Z0-9]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_alphabets_and_numbers_and_space') //Allow only alphabet + numbers + space
      {
        if (!/^[a-zA-Z0-9\s]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_alphabets_and_floats') //Allow only alphabet + numbers + floats
      {
        if (!/^[a-zA-Z0-9.]+$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_alphabets_and_floats_and_space') //Allow only alphabet + numbers + floats + space
      {
        if (!/^[a-zA-Z0-9.\s]+$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_numbers') //Allow only numbers
      {
        if (!/^[0-9]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_numbers_and_space') //Allow only numbers + space
      {
        if (!/^[0-9\s]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_numbers_and_floats') //Allow only numbers + floats
      {
        if (!/^[0-9.]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'allow_only_numbers_and_floats_and_space') //Allow only numbers + floats + space
      {
        if (!/^[0-9.\s]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }
      else if(type == 'validAddress') //Allow only alphabets + numbers + spaces + , - / #
      {
        if (!/^[a-zA-Z0-9.,-/#\s]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }      
      else if(type == 'validCustomInput') //Allow only alphabets + numbers + spaces + , - / # ()
      {
        if (!/^[a-zA-Z0-9.,-/#()\s]$/.test(key) && key !== 'Backspace' && key !== 'Delete') { e.preventDefault(); }
      }      
      else if(type == 'blockCharacters') //Block ' & " quotes
      {
        if (key === '"' || key === "'") { e.preventDefault(); }
      }      
    }   

    $(document).ready(function () 
    {
      $('.custom_input').on('input', function () { inc_custom_input($(this)) });// Check for and remove the first space
      $('.custom_input').on('blur', function () { inc_custom_input_blur($(this)) }); // Trim leading and trailing spaces
      
      $('.allow_only_alphabets').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets'); }); //Allow only alphabet
      
      $('.allow_only_alphabets_and_space').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_space'); }); //Allow only alphabet + space 
      
      $('.allow_only_alphabets_and_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers'); });//Allow only alphabet + numbers
      
      $('.allow_only_alphabets_and_numbers_and_space').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers_and_space'); });//Allow only alphabet + numbers + space
      
      $('.allow_only_alphabets_and_floats').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_floats'); });//Allow only alphabet + numbers + floats
      
      $('.allow_only_alphabets_and_floats_and_space').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_floats_and_space'); });//Allow only alphabet + numbers + floats + space
      
      $('.allow_only_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_numbers'); }); //Allow only numbers
      
      $('.allow_only_numbers_and_space').on('keydown', function (e) { restrict_input(e,'allow_only_numbers_and_space'); });//Allow only numbers + space
      
      $('.allow_only_numbers_and_floats').on('keydown', function (e) { restrict_input(e,'allow_only_numbers_and_floats'); });//Allow only numbers + floats
      
      $('.allow_only_numbers_and_floats_and_space').on('keydown', function (e) { restrict_input(e,'allow_only_numbers_and_floats_and_space'); });//Allow only numbers + floats + space
      
      $('.validAddress').on('keydown', function (e) { restrict_input(e,'validAddress'); }); //Allow only alphabets + numbers + spaces + , - / #
      
      $('.validCustomInput').on('keydown', function (e) { restrict_input(e,'validCustomInput'); }); //Allow only alphabets + numbers + spaces + , - / # ()
      
      $('.blockCharacters').on('keydown', function (e) { restrict_input(e,'blockCharacters'); }); //Block ' & " quotes - / #
    });

    <?php /*
    $(".allowd_only_numbers").keydown(function (e) //***** Input allow only numbers 
    {      
      if($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 || // Allow: backspace, delete, tab, escape, enter
      (e.keyCode == 65 && e.ctrlKey === true) || // Allow: Ctrl+A
      (e.keyCode >= 35 && e.keyCode <= 39)) // Allow: home, end, left, right
      {
        // let it happen, don't do anything
        return;
      }      
      
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) // Ensure that it is a number and stop the keypress
      {
        e.preventDefault();
      }
    });
    
    $(".allowd_only_float").keypress(function (e) //***** Input allow only number and float
    {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
      // Allow: Ctrl+A
      (e.keyCode == 65 && e.ctrlKey === true) || 
      // Allow: home, end, left, right
      (e.keyCode >= 35 && e.keyCode <= 39)) 
      {
        // let it happen, don't do anything
        return;
      }
      
      // Ensure that it is a number and stop the keypress
      if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
        e.preventDefault();
      }
    });
    
    $('.allowed_only_text_with_space').keydown(function (e) //***** Input allow only text with space 
    {
      if (e.shiftKey || e.ctrlKey || e.altKey) {
        e.preventDefault();
        } else {
        var key = e.keyCode;
        if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
          e.preventDefault();
        }
      }
    });
    
    $('.allowed_only_text_without_space').keydown(function (e) //***** Input allow only text without space 
    {
      if (e.shiftKey || e.ctrlKey || e.altKey) {
        e.preventDefault();
        } else {
        var key = e.keyCode;
        if (!((key == 8) || (key == 46) || (key == 9) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
          e.preventDefault();
        }
      }
    });

    $(".allowd_only_numbers_with_text").keydown(function (e) //***** Input allow only numbers and alphabet without space 
    {
      if (e.shiftKey || e.ctrlKey || e.altKey) 
      {
        e.preventDefault();
      }
      else
      {
        // Allow only numbers and alphabets
        var key = e.key;
        if (!/^[a-zA-Z0-9]$/.test(key) && key !== 'Backspace' && key !== 'Delete') 
        {
            e.preventDefault();
        }
      }
    });

    $(".allowd_only_numbers_with_text_and_space").keydown(function (e) //***** Input allow only numbers and alphabet without space 
    {
      if (e.shiftKey || e.ctrlKey || e.altKey) 
      {
        e.preventDefault();
      }
      else
      {
        // Allow only numbers and alphabets with space
        var key = e.key;
        var keyCode = e.keyCode;
        if (!/^[a-zA-Z0-9]$/.test(key) && key !== 'Backspace' && key !== 'Delete' && keyCode !== 32 && keyCode !== 46) 
        {
          e.preventDefault();
        }
      }
    }); */ ?>
    
		//<input type="file" class="form-control file_upload" name="banner_img" id="banner_img" value="" accept=".png,.jpeg,.jpg, .gif" onchange="return CheckDimension()" >
		//<span id='banner_img_width' class='d-none'></span>
		//<span id='banner_img_height' class='d-none'></span>
		var _URL = window.URL || window.webkitURL;
		function CheckDimension()
		{
			$("#banner_img_width").html('');
			$("#banner_img_height").html('');
			var file, img;
			if ((file = $('#banner_img')[0].files[0]))
			{
				img = new Image();
				var objectUrl = _URL.createObjectURL(file);
				img.onload = function ()
				{
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
      jQuery.validator.addMethod("notEqual", function(value, element, param) { return this.optional(element) || value != $("#"+param).val(); });

      $.validator.addMethod("blockCharacters", function(value, element) { return !/[\'\"]/.test(value); }, "Please avoid using single quote (') and double quote (\") characters.");
      
      jQuery.validator.addMethod("check_valid_file", function(value, element, param)//for size 0 files
      {
        var isOptional = this.optional(element), file;
        if(isOptional) { return isOptional; }
        
        if ($(element).attr("type") === "file") 
        {
          if (element.files && element.files.length) 
          {
            file = element.files[0];             
            if(file.size > 0)
            {
              return true;              
            }
          }
        }
        return false;
      }, "Please select valid file");

      jQuery.validator.addMethod("filesize_max", function(value, element, param) //use size in bytes //filesize_max: 1MB : 1000000
      {
        var isOptional = this.optional(element), file;
        if(isOptional) { return isOptional; }
        
        if ($(element).attr("type") === "file") 
        {
          if (element.files && element.files.length) 
          {
            file = element.files[0]; 
            //console.log(file.size+" < "+param);
            return ( file.size && file.size <= param ); 
          }
        }
        return false;
      }, "File size must be less than {0} bytes."); 
      
      jQuery.validator.addMethod("filesize_min", function(value, element, param) //use size in bytes //filesize_min: 1MB : 1000000
      {
        var isOptional = this.optional(element), file;
        if(isOptional) { return isOptional; }
        
        if ($(element).attr("type") === "file") 
        {
          if (element.files && element.files.length) 
          {
            file = element.files[0]; 
            //console.log(file.size +'>='+ param);
            return ( file.size && file.size >= param ); 
          }
        }
        return false;
      }, "File size must be greater than {0} bytes.");
      
      //valid_file_format: '.png',
      //valid_file_format: '.png,.pdf',
      $.validator.addMethod("valid_file_format", function(value, element, param) 
      { 
        if(value != "" && param != "")
        {
          var validExts = param.split(',');
          										
          var fileExt = value.toLowerCase();
          fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
          if (validExts.indexOf(fileExt) < 0)  { return false; } else return true;
        }else return true;
      }, "Invalid file selection");
      
      $.validator.addMethod("valid_email", function(value, element) 
      {
        if ($.trim(value) != '')
        {
          var pattern = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
          return pattern.test(value);
        } else { return true; }
      });
      
      $.validator.addMethod("valid_pan_no", function(value, element) 
      {
        if ($.trim(value) != '')
        {
          var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
          if (regex.test(value)) { return true; } 
          else { return false; }
        } else { return true; }
      });

      $.validator.addMethod("valid_gst_no", function(value, element) 
      {
        if ($.trim(value) != '')
        {
          var gstNumber = $.trim(value);
          var gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/;
          
          if (gstRegex.test(gstNumber)) { return true; } 
          else { return false; }
        } else { return true; }
      });
      
      jQuery.validator.addMethod("chk_img_height_width", function(value, element)
			{
				var banner_img_width = $("#banner_img_width").html();
				var banner_img_height = $("#banner_img_height").html();
				if(banner_img_width != "" && banner_img_height != "")
				{
					if(banner_img_width == 1920 && banner_img_height == 838)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else { return true; }
			}, "");

      /* $.validator.addMethod("valid_email_address", function(value, element) 
      {
        if ($.trim(value) != '')
        {
          var pattern = new RegExp(/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/);
          return pattern.test(value);
        } else { return true; }
      });

      $.validator.addMethod("valid_youtube_url", function(value, element) 
      {
        if ($.trim(value) != '')
        {
          var pattern = new RegExp(/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/);
          return pattern.test(value);
        } else { return true; }
      }); */      
      
      $.validator.addMethod("FirstLetterString", function(value, element) 
      {
        if(value != '')
        {
          var first_letter = value.charAt(0);
          return first_letter.match(/[a-z]/i);            
        }          
      }); //First letter must be string
      
      $.validator.addMethod("LetterNumberDash", function(value, element) 
      {
        return this.optional(element) || /^[a-z0-9\-_@#$&*!?]+$/i.test(value);
      }); //"must contain only letters, numbers or -_@#$&*!?"
      
      $.validator.addMethod("ValidUsername", function(value, element) 
      {
        return this.optional(element) || /^[a-z0-9\_@#$&*!?.]+$/i.test(value);
      }); //"must contain only letters, numbers or _@#$&*!.?"
      
      $.validator.addMethod("validAddress", function(value, element) 
      {
        return this.optional(element) || /^[a-zA-Z0-9\s#.,-/]+$/i.test(value); 
      },"Only alphabets, numbers, spaces and . , - / # are allowed" ); //"must contain only alphabets, numbers, spaces and , - / # "
      
      $.validator.addMethod("validCustomInput", function(value, element) 
      {
        return this.optional(element) || /^[a-zA-Z0-9\s()#.,-/]+$/i.test(value); 
      },"Only alphabets, numbers, spaces and . , - / # () are allowed" ); //"must contain only alphabets, numbers, spaces and , - / # ()"
      
      $.validator.addMethod("validInput", function(value, element) 
      {
        return this.optional(element) || /^[a-zA-Z0-9\s&-_#=:/]+$/i.test(value);
      },"Only alphabets, numbers, spaces and ' & - _ # = : / ' are allowed" ); //"must contain only letters, numbers, space or &-_#=:/"

      $.validator.addMethod("allow_only_alphabets", function(value, element) { return this.optional(element) || /^[a-zA-Z]+$/i.test(value); },'Only alphabets are allowed') //Allow only alphabet

      $.validator.addMethod("allow_only_alphabets_and_space", function(value, element) { return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value); },'Only alphabets and spaces are allowed') //Allow only alphabet + space 

      $.validator.addMethod("allow_only_alphabets_and_numbers", function(value, element) { return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value); },'Only alphabets and numbers are allowed') //Allow only alphabet + numbers
      
      $.validator.addMethod("allow_only_alphabets_and_numbers_and_space", function(value, element) { return this.optional(element) || /^[a-zA-Z0-9\s]+$/i.test(value); },'Only alphabets, numbers and spaces are allowed') //Allow only alphabet + numbers + space
      
      $.validator.addMethod("allow_only_alphabets_and_floats", function(value, element) { return this.optional(element) || /^[a-zA-Z0-9.]+$/i.test(value); },'Only alphabets and numbers are allowed') //Allow only alphabet + numbers + floats
      
      $.validator.addMethod("allow_only_alphabets_and_floats_and_space", function(value, element) { return this.optional(element) || /^[a-zA-Z0-9.\s]+$/i.test(value); },'Only alphabets, numbers and spaces are allowed') //Allow only alphabet + numbers + floats + space
      
      $.validator.addMethod("allow_only_numbers", function(value, element) { return this.optional(element) || /^[0-9]+$/i.test(value); },'Only numbers are allowed') //Allow only numbers
      
      $.validator.addMethod("allow_only_numbers_and_space", function(value, element) { return this.optional(element) || /^[0-9\s]+$/i.test(value); },'Only numbers and spaces are allowed') //Allow only numbers + space
      
      $.validator.addMethod("allow_only_numbers_and_floats", function(value, element) { return this.optional(element) || /^[0-9.]+$/i.test(value); },'Only numbers are allowed') //Allow only numbers + floats
     
      $.validator.addMethod("allow_only_numbers_and_floats_and_space", function(value, element) { return this.optional(element) || /^[0-9.\s]+$/i.test(value); },'Only numbers and space are allowed') //Allow only numbers + floats + space      
      
      $.validator.addMethod("required", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
      
      $.validator.addMethod("first_zero_not_allowed", function(value, element) { return this.optional(element) || /^[1-9][0-9]*$/i.test(value); },'Please enter first number between 1 to 9 only') //Allow only numbers + floats + space  
      
      $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })// For chosen validation
      
      $.validator.addMethod("pwcheck", function(value) 
      {
        return /[A-Z]/.test(value) // has a uppercase letter
        && /[a-z]/.test(value) // has a lowercase letter
        && /[0-9]/.test(value) // has a digit
        && /[~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<]/.test(value) // has a special character
      });
      
      $.validator.addMethod("dateFormat", function(value, element, param) 
      {
        if(param == '' || param == 'Y-m-d')
        {
          return this.optional(element) || value.match(/^\d{4}-((0\d)|(1[012]))-(([012]\d)|3[01])$/);
        }
        /* else if(param == '' || param == 'd-m-Y')
        {
          return this.optional(element) || value.match(/^\d(([012]\d)|3[01])-((0\d)|(1[012]))-{4}$/);
        } */
        else if(param == 'Y')
        {
          return this.optional(element) || value.match(/^\d{4}$/);
        }
        else if(param == 'Y-m')
        {
          return this.optional(element) || value.match(/^\d{4}-((0\d)|(1[012]))$/);
        }
      },"Please select valid date");
    })

    //allowed_max_size = Provide size in kb or mb only (10kb, 20kb, 1mb, 2mb etc)
    //img_width = max allowed width = Provide in number only (100,200,300,400 etc)
    //img_height = max allowed height = Provide in number only (100,200,300,400 etc)
    function validateFileAll(event, error_id, preview_img_id, allowed_max_size = '', img_width = '', img_height = '') 
    {
      var srcid = event.srcElement.id;
      var input_file_id = document.getElementById(srcid).id;

      //console.log(document.getElementById(srcid).files[0]);
      if (document.getElementById(srcid).files.length != 0) //WHEN FILE IS SELECTED
      {
        var file_arr = document.getElementById(srcid).files[0];

        if (file_arr.size == 0) //WHEN FILE SIZE IS ZERO(0), SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
        {
          $('#' + error_id).text('Please select valid file');
          ////$('#' + input_file_id).val('')
          $('#' + preview_img_id).html('<img src="<?php echo base_url('assets/iibfbcbf/images/no_image_bcbf.png'); ?>" />');
          return false;
        } 
        else //WHEN SELECTED FILE SIZE IS GREATER THAN ZERO(0)
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
            $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " extensions files only.");
            ////$('#' + input_file_id).val('')
            $('#' + preview_img_id).html('<img src="<?php echo base_url('assets/iibfbcbf/images/no_image_bcbf.png'); ?>" />');
            return false;
          } 
          else //WHEN CORRECT EXTENSION FILE SELECTED
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

            var reader = new FileReader();
            reader.onload = function(e) {
              var img = new Image();
              img.src = e.target.result;

              //console.log("reader.result : "+reader.result)
              if (reader.result == 'data:') //WHEN FILE IS CORRUPTED, SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
              {
                $('#' + error_id).text('This file is corrupted');
                ////$('#' + input_file_id).val('')
                $('#' + preview_img_id).html('<img src="<?php echo base_url('assets/iibfbcbf/images/no_image_bcbf.png'); ?>" />');
                return false;
              } 
              else
               {
                if (check_size != "" && file_size > check_size[0]) //WHEN LARGE FILE IS SELECTED 
                {
                  $('#' + error_id).text("Please upload file less than " + allowed_max_size);
                  ////$('#' + input_file_id).val('')
                  $('#' + preview_img_id).html('<img src="<?php echo base_url('assets/iibfbcbf/images/no_image_bcbf.png'); ?>" />');
                  return false;
                } 
                else 
                {
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

                        ////$('#' + input_file_id).val('')
                        $('#' + preview_img_id).html('<img src="<?php echo base_url('assets/iibfbcbf/images/no_image_bcbf.png'); ?>" />');
                        return false;
                      } 
                      else
                      {
                        $('#' + error_id).text("");
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
      } 
      else //WHEN FILE IS NOT SELECTED, SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
      {
        $('#' + error_id).text('Please select file');
        ////$('#' + input_file_id).val('')
        $('#' + preview_img_id).html('<img src="<?php echo base_url('assets/iibfbcbf/images/no_image_bcbf.png'); ?>" />');
        return false;
      }
    }

    function show_preview(event, preview_img_id) 
    {
      var srcid = event.srcElement.id;
      var input_file_id = document.getElementById(srcid).id;      

      if (document.getElementById(srcid).files.length != 0) //WHEN FILE IS SELECTED
      {
        var file_arr = document.getElementById(srcid).files[0];
        
        if (file_arr.size == 0) //WHEN FILE SIZE IS ZERO(0), SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
        {
          $('#' + preview_img_id).html('<i class="fa fa-picture-o" aria-hidden="true"></i>" />');
        } 
        else  //WHEN SELECTED FILE SIZE IS GREATER THAN ZERO(0)
        {
          var fileNameExt = "." + file_arr.name.substr(file_arr.name.lastIndexOf('.') + 1).toLowerCase();     

          var reader = new FileReader();
          reader.onload = function(e) 
          {
            var img = new Image();
            img.src = e.target.result;
            
            if (reader.result == 'data:') //WHEN FILE IS CORRUPTED, SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
            {
              $('#' + preview_img_id).html('<i class="fa fa-picture-o" aria-hidden="true"></i>" />');
            } 
            else
            {         
              if (fileNameExt == ".jpg" || fileNameExt == ".jpeg" || fileNameExt == ".png" || fileNameExt == ".gif")
              { 
                img.onload = function() 
                {                   
                  $('#' + preview_img_id).html('<a href="'+ reader.result +'" class="example-image-link" data-lightbox="candidate_file" data-title=""><img src="' + reader.result + '" /></a>');
                  var img = new Image();
                  img.src = reader.result;
                }
                $('#' + preview_img_id).html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
              }
              else 
              { 
                $('#' + preview_img_id).html('<h4>' + fileNameExt + '</h4>');              
              }            
            }                    
          }
          reader.readAsDataURL(event.target.files[0]); 
        }
      }
      else //WHEN FILE IS NOT SELECTED, SHOW ERROR MESSAGE + REMOVE INPUT VALUE + SET DEFAULT IMAGE TO PREVIEW 
      {
        $('#' + preview_img_id).html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
      }
    }
  </script>