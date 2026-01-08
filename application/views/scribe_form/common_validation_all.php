<script>
	$(".allowd_only_numbers").keydown(function (e) 
	{
		// Allow: backspace, delete, tab, escape, enter
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
		// Allow: Ctrl+A
		(e.keyCode == 65 && e.ctrlKey === true) || 
		// Allow: home, end, left, right
		(e.keyCode >= 35 && e.keyCode <= 39)) 
		{
			// let it happen, don't do anything
			return;
		}
		
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
	
	$(document).ready(function() 
	{
		jQuery.validator.addMethod("notEqual", function(value, element, param) 
		{
			return this.optional(element) || value != $("#"+param).val();
		});
		
		jQuery.validator.addMethod("filesize_max", function(value, element, param) 
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
		}, "");//filesize_max:1000000 //for 1MB
    
		$.validator.addMethod("valid_img_format", function(value, element) 
		{ 
			if(value != "")
			{
				var validExts = new Array(".png", ".jpeg", ".jpg", ".gif");
				var fileExt = value.toLowerCase();
				fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
				if (validExts.indexOf(fileExt) < 0)  { return false; } else return true;
			}else return true;
		});
		
		$.validator.addMethod("valid_file_format", function(value, element, param) 
		{ 
			if(value != "" && param != "")
			{
				//var validExts = new Array(".png", ".jpeg", ".jpg", ".gif");
				var validExts = param.split(',');;
									
				var fileExt = value.toLowerCase();
				fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
				if (validExts.indexOf(fileExt) < 0)  { return false; } else return true;
			}else return true;
		}, "Invalid file selection"); //valid_file_format:'.pdf,.jpg,.jpeg'
		
		$.validator.addMethod("valid_email", function(value, element) 
		{
			if ($.trim(value) != '')
			{
				var pattern = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
				return pattern.test(value);
			} else { return true; }
		});
		
		$.validator.addMethod("FirstLetterString", function(value, element) 
		{
			if(value != '')
			{
				var first_letter = value.charAt(0);
				return first_letter.match(/[a-z]/i);            
			}          
		},); //First letter must be string
		
		$.validator.addMethod("ValidAddress", function(value, element) 
		{
			return this.optional(element) || /^[a-zA-Z0-9\/:;,-\s]+$/i.test(value);
		}, ); //"must contain only letters, numbers
		
		$.validator.addMethod("LetterNumberDash", function(value, element) 
		{
			return this.optional(element) || /^[a-z0-9\-_@#$&*!?]+$/i.test(value);
		}, ); //"must contain only letters, numbers or -_@#$&*!?"
		
		$.validator.addMethod("ValidUsername", function(value, element) 
		{
			return this.optional(element) || /^[a-z0-9\_@#$&*!?.]+$/i.test(value);
		}, ); //"must contain only letters, numbers or _@#$&*!.?"
		
		$.validator.addMethod("lettersonly", function(value, element) { return this.optional(element) || /^[a-z]+$/i.test(value); }),
		
		$.validator.addMethod("letterswithspace", function(value, element) { return this.optional(element) || /^[a-zA-Z\s]+$/.test(value); }),
		
		$.validator.addMethod("required", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
		
		$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })// For chosen validation
		
		$.validator.addMethod("pwcheck", function(value) 
		{
			return /[A-Z]/.test(value) // has a uppercase letter
			&& /[a-z]/.test(value) // has a lowercase letter
			&& /[0-9]/.test(value) // has a digit
			&& /[~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<]/.test(value) // has a special character
		});
	})
</script>