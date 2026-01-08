// JavaScript Document
	$(document).ready(function() {
	var checkval=0;
	var emailcheckval=0;
	var mobilecheckval=0;
	var noncheckval=0;
       //###---------------------------Member validation------------------------####
	   // check valid pin for member user	
		window.Parsley.addValidator('checkpin', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'Careers/checkpin/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				 if(data=='true')
				{
					response = true;
				}
				else
				{
					response = false;
				}
				}
			});
				return response;
		}, 31)
		.addMessage('en', 'checkpin', 'Please enter Valid Pincode.');
		
		
		window.Parsley.addValidator('checkpin_pr', function (value, requirement) {
			var response = false;
				var datastring='statecode_pr='+$('#state_pr').val()+'&pincode_pr='+value;
				$.ajax({
				url:site_url+'Careers/checkpin_pr/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				 if(data=='true')
				{
					response = true;
				}
				else
				{
					response = false;
				}
				}
			});
				return response;
		}, 31)
		.addMessage('en', 'checkpin_pr', 'Please enter Valid Pincode.');
		
		// check email duplication for member user
		window.Parsley.addValidator('emailcheck', function (value, requirement) {
			var response = false;
			var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
			var position_id = $('#position_id').val();
			var datastring='email='+value+'&position_id='+position_id;
		
			if(filter.test(value))
			{
				$.ajax({
				url:site_url+'Careers/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					if(emailcheckval==0)
					{
				   	alert(data.output);
					}
					emailcheckval=1;	
					response = false;
				}
				else
				{
					emailcheckval=0;
					response = true;
				}
				}
			});
				return response;
			}
		}, 32)
		
		.addMessage('en', 'emailcheck', 'The email already exists.');
		
		// check mobile duplication for member user
		window.Parsley.addValidator('mobilecheck', function (value, requirement) {
			var response = false;
			var msg='';
			var position_id = $('#position_id').val();
				var datastring='mobile='+value+'&position_id='+position_id;
				$.ajax({
				url:site_url+'Careers/mobileduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					if(mobilecheckval==0)
					{
						alert(data.output);
					}
					mobilecheckval = 1;
					response = false;
				}
				else
				{
					mobilecheckval = 0;
					response = true;
				}
				}
			});
				return response;
		}, 33)
		.addMessage('en', 'mobilecheck', 'The mobile number already exists.');
		
			
			
			
		// check mobile duplication for member user
		window.Parsley.addValidator('pannocheck', function (value, requirement) {
			var response = false;
			var msg='';
			var position_id = $('#position_id').val();
				var datastring='pan_no='+value+'&position_id='+position_id;
				$.ajax({
				url:site_url+'Careers/pannoduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(checkval==0)
					{
						alert(data.output);
					}
					response = false;
				}
				else
				{
					response = true;
				}
				}
			});
				return response;
		}, 33)
		.addMessage('en', 'pannocheck', 'The PAN No number already exists.');	
			
			
		
		$('#sel_namesub').on('change',function(){
			var val=$('#sel_namesub').val();
			$('.cls_gender').each(function(){
			$(this).removeAttr('checked');
			});
			if(val=='Mr.')
			{
				 $('#male').prop("checked", true);
				//$('#male').attr('checked', true);
			}
			else if(val=='Mrs.')
			{
				$('#female').prop("checked", true);
				//$('#female').attr('checked', true);
			}
			else if(val=='Ms.')
			{
				$('#female').prop("checked", true);
				//$('#female).attr('checked', true);
			}
	
		});
		
		
		
		
	$('#selSubName').change(function(){
		var cCode= document.getElementById('selCenterName').value;
		var examCode = document.getElementById('examcode').value;
		alert('You are selected '+$('#selSubName option:selected').text()+' as a elective subject.');
		
		// get selected elective subject code
		var sel1 = document.getElementById("selSubName");
		
		var subCode = sel1.options[sel1.selectedIndex].value;
	
		document.getElementById('selSubcode').value = subCode;
		// get selected elective subject name
		var sel2 = document.getElementById("selSubName");
		var subName = sel2.options[sel2.selectedIndex].text;
		//alert(subName);
		document.getElementById('selSubName1').value = subName;
		$('#selSubName').attr("disabled", true);
		//$('#selSubName').attr("readonly", true);
		
		
		var datastring_exam='centerCode='+cCode+'&examCode='+examCode+'&elective_subcode='+subCode;
		$.ajax({
								url:site_url+'Venue/getElectiveVenue/',
								data: datastring_exam,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									//alert(data.venue_option);
									//var venue_elements=document.getElementsByClassName('venue_cls');
									document.getElementById('venue_id').innerHTML=data.venue_option;
									document.getElementById('date_id').innerHTML=data.date_option;
									document.getElementById('time_id').innerHTML=data.time_option;
									//for (var i=0; i<venue_elements.length; i++) {
										//	venue_elements[i].innerHTML = data.venue_option;
 									//}
									
									/*var date_elements=document.getElementsByClassName('date_cls');
									for (var i=0; i<date_elements.length; i++) {
											date_elements[i].innerHTML = data.date_option;
 									}*/
									
									/*var time_elements=document.getElementsByClassName('time_cls');
									for (var i=0; i<time_elements.length; i++) {
											time_elements[i].innerHTML = data.time_option;
 									}*/
									}
								}
						});		
			
			
	});
	
	$('#enab-elect-subj').click(function(){
		//$('#selSubName').attr("readonly", false);
		$('#selSubName').attr("disabled", false);
	});
		
		
	$("#dob1").change(function(){
		$('#doj1').change();
		var sel_dob = $("#dob1").val();
		if(sel_dob!='')
		{
			var dob_arr = sel_dob.split('-');
			if(dob_arr.length == 3)
			{
				$("#dob_error").html('');
				chkage(dob_arr[2],dob_arr[1],dob_arr[0]);
				//checkDoj();	
			}
			else
			{
				$("#dob_error").html('Select valid date');
				return false;
			}
		}
	});
	
	
	
	
	
	$('#firstname').keyup(function (){
	$('#parsley-id-5 li').first().remove();
    if(($("#sel_namesub :selected").val() == ''))
    {  
        $('#tiitle_error').html('This value is required.');
    }
    else
    {
       $('#tiitle_error').html(''); 
    }
    return false;
	});
	
	$('#sel_namesub').change(function()
	{
		if($('#sel_namesub').val()!='')
		{
			$('#tiitle_error').html(''); 
		}	
	});
	

	
	
	
	
});

	$(function() {
	///////////////////// scanphoto validation //////////////////////
	$( "#scannedphoto" ).change(function() {
		//var filesize1=this.files[0].size/1024<8;
		var MainPhotofilesize = this.files[0].size/1024;
		
		var filesize2 = false;

		if ( MainPhotofilesize < 25 || MainPhotofilesize > 100 ) {
			filesize2 = true;
		}
		
		// var filesize2 = this.files[0].size/1024 > 50;
		var flag = 1;
	 	var file, img;
	
	$('#p_photograph').hide();
	var photograph_image=document.getElementById('scannedphoto');
	//fileUpload[appKey]['photo'] = photograph_image;
	var photograph_im=photograph_image.value;
	var ext1=photograph_im.substring(photograph_im.lastIndexOf('.')+1);
	if(photograph_image.value!=""&&  ext1!='jpg' && ext1!='JPG' && ext1!='jpeg' && ext1!='JPEG')
	{
			$('#error_photo').show();
			$('#error_photo').fadeIn(3000);	
			document.getElementById('error_photo').innerHTML="Upload jpg, JPG, jpeg, JPEG file only.";
			setTimeout(function(){
			$('#error_photo').css('color','#B94A48');
			document.getElementById("scannedphoto").value = "";
			$('#hiddenphoto').val('');
			
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".photo_text").hide();
		}
	else if(filesize2)
	{
		$('#error_photo_size').show();
		$('#error_photo_size').fadeIn(3000);	
		document.getElementById('error_photo_size').innerHTML="File size should be minimum 25KB and maximum 100KB .";
		setTimeout(function(){
		$('#error_photo_size').css('color','#B94A48');
		//$('#error_bussiness_image').fadeOut('slow');
		document.getElementById("scannedphoto").value = "";
		$('#hiddenphoto').val('');
		},30);
		flag = 0;
		$(".photo_text").hide();
		}
	 
	
	 
		
		if(flag==1)
		{
			$('#error_photo').html('');
			$('#error_photo_size').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
					$('#hiddenphoto').val(this.result);
				}
			}
			 readURL(this,'image_upload_scanphoto_preview');
			return true;
		}
		else
		{
			return  false;
		}
		
	
	
	});

  ///////////////////// scan Signature validation //////////////////////
	$( "#scannedsignaturephoto" ).change(function() {
												  
												  
		// var filesize2=this.files[0].size/1024>20;
		
		var MainPhotofilesize = this.files[0].size/1024;
		
		var filesize2 = false;

		if ( MainPhotofilesize < 25 || MainPhotofilesize > 100 ) {
			filesize2 = true;
		}

		var flag = 1;
		//$('#p_signature').hide();
		
		var signature_image=document.getElementById('scannedsignaturephoto');
		var signature_im=signature_image.value;
		var ext2=signature_im.substring(signature_im.lastIndexOf('.')+1);
		
		if(signature_image.value!=""&&  ext2!='jpg' && ext2!='JPG' && ext2!='jpeg' && ext2!='JPEG')
		{
			$('#error_signature').show();
			$('#error_signature').fadeIn(3000);	
			document.getElementById('error_signature').innerHTML="Upload jpg, JPG, jpeg, JPEG file only.";
			setTimeout(function(){
			$('#error_signature').css('color','#B94A48');
			document.getElementById("scannedsignaturephoto").value = "";
			$('#hiddenscansignature').val('');
			//document.getElementById("uploadedSignature").value = "";
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".signature_text").hide();
		}
		
		else if(filesize2)
		 {
			$('#error_signature_size').show();
				$('#error_signature_size').fadeIn(3000);	
				document.getElementById('error_signature_size').innerHTML="File size should be minimum 25KB and maximum 100KB.";
				setTimeout(function(){
				$('#error_signature_size').css('color','#B94A48');
					document.getElementById("scannedsignaturephoto").value = "";
					$('#hiddenscansignature').val('');
				},30);
				flag = 0;
				$(".signature_text").hide();
		 }
		
		/*else if ((file = this.files[0])) {
			img = new Image();
			img.onload = function () {
			//   var file_size = this.files[0].size;  
			  
			   if(this.width!=140 || this.height!=60) 
			   {
				   $('#error_signature').fadeIn('slow');
					document.getElementById('error_signature_size').innerHTML='Upload valid image of size 140(Width) * 60(Height).';
					setTimeout(function(){
					$('#error_signature_size').css('color','#B94A48');
					document.getElementById("scannedsignaturephoto").value = "";
					$('#hiddenscansignature').val('');
					},30);
				flag = 0;
				$(".photo_text").hide();
			   }
			};
			img.src = URL.createObjectURL(file);
		}*/
		
		if(flag==1)
		{
			 $('#error_signature_size').html('');
			 $('#error_signature').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
				$('#hiddenscansignature').val(this.result);
				}
			}
			readURL(this,'image_upload_sign_preview');
			 return true; 
		}
		else
		 {
			return false;
		 }
	
	});

	///////////////////// ID Proof validation //////////////////////
	$( "#idproofphoto" ).change(function() {
		//var filesize1=this.files[0].size/1024<8;
		var filesize2=this.files[0].size/1024>300;
		var flag = 1;
		//$("#p_dob_proof").hide();
		
		var dob_proof_image=document.getElementById('idproofphoto');
		var dob_proof_im=dob_proof_image.value;
		var ext3=dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);
		
		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG' && ext3!='jpeg' && ext3!='JPEG')
		{
			$('#error_dob').show();
			$('#error_dob').fadeIn(300);	
			document.getElementById('error_dob').innerHTML="Upload JPG or jpg file only.";
			setTimeout(function(){
			$('#error_dob').css('color','#B94A48');
			 document.getElementById("idproofphoto").value = "";
			 $('#hiddenidproofphoto').val('');
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".dob_proof_text").hide();
		}
		
		else  if(filesize2)
		 {
			$('#error_dob_size').show();
			$('#error_dob_size').fadeIn(300);	
			document.getElementById('error_dob_size').innerHTML="File size should be maximum 300KB.";
			setTimeout(function(){
			$('#error_dob_size').css('color','#B94A48');
			document.getElementById("idproofphoto").value = "";
			 $('#hiddenidproofphoto').val('');
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".dob_proof_text").hide();
		}
		
		/*else if ((file = this.files[0])) 
		{
			img = new Image();
			img.onload = function () {
			//   var file_size = this.files[0].size;  
			   if(this.width!=400 || this.height!=420) 
			   {
				   $('#error_dob_size').fadeIn('slow');
					document.getElementById('error_dob_size').innerHTML='Upload valid image of size 400(Width) * 420(Height).';
					setTimeout(function(){
					$('#error_dob_size').css('color','#B94A48');
					//$('#error_bussiness_image').fadeOut('slow');
					document.getElementById("idproofphoto").value = "";
					 $('#hiddenidproofphoto').val('');
					},30);
					//$('#err_banner_image').fadeOut(2000);
				flag = 0;
				$(".dob_proof_text").hide();
			   }
			};
			img.src = URL.createObjectURL(file);
		}*/
		
		if(flag=='1')
		{
			$('#error_dob_size').html('');
			$('#error_dob').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
	
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
				$('#hiddenidproofphoto').val(this.result);
				$('#declaration_id').hide();
				}
			}
			
			 readURL(this,'image_upload_idproof_preview');
			return true;
		}
		else
		{
			 return false;
		 }
	});
	
	
	///////////////////// scanphoto validation //////////////////////
	$( "#uploadcv" ).change(function() {
								 
		//var filesize1=this.files[0].size/1024<8;

		var filesize2=this.files[0].size/1024>2000;
			
	var flag = 1;
	 var file, img;
	$('#p_photograph').hide();
	var photograph_image=document.getElementById('uploadcv');
	//fileUpload[appKey]['photo'] = photograph_image;
	var photograph_im=photograph_image.value;
	var ext1=photograph_im.substring(photograph_im.lastIndexOf('.')+1);
	if(photograph_image.value!=""&&  ext1!='pdf' && ext1!='PDF' && ext1!='docx' && ext1!='DOCX')
	{
			$('#error_uploadcv').show();
			$('#error_uploadcv').fadeIn(3000);	
			document.getElementById('error_uploadcv').innerHTML="Upload PDF or docx file only.";
			setTimeout(function(){
			$('#error_uploadcv').css('color','#B94A48');
			document.getElementById("uploadcv").value = "";
			$('#hiddenuploadcv').val('');
			
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".uploadcv_text").hide();
		}
	else if(filesize2)
	{
		$('#error_uploadcv_size').show();
		$('#error_uploadcv_size').fadeIn(3000);	
		document.getElementById('error_uploadcv_size').innerHTML="File size should be maximum 2 MB.";
		setTimeout(function(){
		$('#error_uploadcv_size').css('color','#B94A48');
		//$('#error_bussiness_image').fadeOut('slow');
		document.getElementById("uploadcv").value = "";
		$('#hiddenuploadcv').val('');
		},30);
		flag = 0;
		$(".uploadcv_text").hide();
		}
		if(flag==1)
		{
			$('#error_uploadcv').html('');
			$('#error_uploadcv_size').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
					$('#hiddenuploadcv').val(this.result);
				}
			}
			 readURL(this,'image_upload_uploadcv_preview');
			return true;
		}
		else
		{
			return  false;
		}
		
	
	
	});
	
   /* $("#scannedphoto").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
			reader.onloadend = function(){ // set image data as background of div
			//alert(this.result);
			//	var image_holder = $("#tempphoto");
			//#tempphoto=$('#tempphoto').val(this.result);
			// $("<img />", {
			//        "src": this.result,
			//         
			//     }).appendTo(image_holder);
				
				$('#hiddenphoto').val(this.result);
			//	$("#tempphoto").css("background-image", "url("+this.result+")");
            }
        }
    });*/
	
/*	$("#scannedsignaturephoto").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
			reader.onloadend = function(){ // set image data as background of div
			//alert(this.result);
			//	var image_holder = $("#tempphoto");
			//#tempphoto=$('#tempphoto').val(this.result);
			// $("<img />", {
			//        "src": this.result,
			//         
			//     }).appendTo(image_holder);
				
				$('#hiddenscansignature').val(this.result);
			//	$("#tempphoto").css("background-image", "url("+this.result+")");
            }
        }
    });*/
	
	/*$("#idproofphoto").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
			reader.onloadend = function(){ // set image data as background of div
			//alert(this.result);
			//	var image_holder = $("#tempphoto");
			//#tempphoto=$('#tempphoto').val(this.result);
			// $("<img />", {
			//        "src": this.result,
			//         
			//     }).appendTo(image_holder);
				
				$('#hiddenidproofphoto').val(this.result);
			//	$("#tempphoto").css("background-image", "url("+this.result+")");
            }
        }
    });*/
	
	
	
	/*$('#usersAddForm input').on('change', function() {
		alert('in');
	   if($('input[name="idproof"]:checked', '#usersAddForm').val()==8 && $('#hiddenidproofphoto').val()=='')
		{
			
			$('#declaration_id').css('display','block');
		}
		else
		{
			$('#declaration_id').css('display','none');
		}
});*/


	});
	
	function handleClick(myRadio) {
		 if(myRadio.value==8 && hiddenidproofphoto.value=='')
		{
			document.getElementById("declaration_id").style.display = "block"; 
		}
		else
		{
		document.getElementById("declaration_id").style.display = "none"; 
		}
}

 function doConfirm(yesFn, noFn) {
    var confirmBox = $("#confirmBox");
    confirmBox.find(".yes,.no").unbind().click(function () {
        confirmBox.hide();
    });
    confirmBox.find(".yes").click(yesFn);
    confirmBox.find(".no").click(noFn);
 	confirmBox.show();
}
	//for member user checkform
 	function checkform()
 	{
		$('#error_id').html(''); 
		$('#success_id').html(''); 
		
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		$('#success_id').removeClass("alert alert-danger alert-dismissible");
		
		$('#tiitle_error').html(''); 
		var flag = true;
		$('#captchaid').html('');
		var code=$('#code').val();
		
		var dob = $('#dob1').val();
		var doj = $('#doj1').val();
		
		if(dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(dob < "1987-01-31")
			{
				$("#dob_error").html('The maximum age limit is 35 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else if(dob > "2001-01-31")
			{
				$("#dob_error").html('The minimum age limit is 21 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else 
			{ 
				$("#dob_error").html('');
			}
		}
		
		var assistant_director_it_dob = $('#assistant_director_it_dob').val();
		if(assistant_director_it_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#assistant_director_it_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(assistant_director_it_dob < "1987-01-31")
			{
				$("#assistant_director_it_dob_error").html('The maximum age limit is 35 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else if(assistant_director_it_dob > "2004-01-31")
			{
				$("#assistant_director_it_dob_error").html('The minimum age limit is 18 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else 
			{ 
				$("#assistant_director_it_dob_error").html('');
			}
		}
		
		var post_of_director_operation_dob = $('#post_of_director_operation_dob').val();
		if(post_of_director_operation_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#post_of_director_operation_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(post_of_director_operation_dob < "1961-01-31")
			{
				$("#post_of_director_operation_dob_error").html('The maximum age limit is 61 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else if(post_of_director_operation_dob > "1967-01-31")
			{
				$("#post_of_director_operation_dob_error").html('The minimum age limit is 55 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else 
			{ 
				$("#post_of_director_operation_dob_error").html('');
			}
		}
		
		
		var deputy_director_dob = $('#deputy_director_dob').val();
		if(deputy_director_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#deputy_director_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(deputy_director_dob < "1977-01-31")
			{
				$("#deputy_director_dob_error").html('The maximum age limit is 45 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else if(deputy_director_dob > "2001-01-31")
			{
				$("#deputy_director_dob_error").html('The minimum age limit is 21 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else 
			{ 
				$("#deputy_director_dob_error").html('');
			}
			
			/* if(deputy_director_dob < '1971-07-31')
			{
				//alert('The maximum age limit to apply is 28 years as on 01-07-2021');
				$("#deputy_director_dob_error").html('The maximum age limit to apply is 50 years as on 31.07.2021');
				flag = false;
			}	
			else { $("#deputy_director_dob_error").html(''); } */
		}
		
		var deputy_director_it_dob = $('#deputy_director_it_dob').val();
		if(deputy_director_it_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#deputy_director_it_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(deputy_director_it_dob < "1977-01-31")
			{
				$("#deputy_director_it_dob_error").html('The maximum age limit is 45 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else if(deputy_director_it_dob > "2001-01-31")
			{
				$("#deputy_director_it_dob_error").html('The minimum age limit is 21 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else 
			{ 
				$("#deputy_director_it_dob_error").html('');
			}
		}
		
		var faculty_member_dob = $('#faculty_member_dob').val();
		if(faculty_member_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#faculty_member_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			/*if(faculty_member_dob < '1961-04-01')  
			{
				$("#faculty_member_dob_error").html('The minimum age limit to apply is 55 years as on 01.04.2023');
				flag = false;
			}
			else if(faculty_member_dob > '1968-04-01') 
			{
				$("#faculty_member_dob_error").html('The maximum age limit to apply is 62 years as on 01.04.2023');
				flag = false;
			}	
			else { 
				$("#faculty_member_dob_error").html(''); 
			}*/
			$("#faculty_member_dob_error").html(''); 
		}
    // Resident Engineer

		var rec_dob = $('#rec_dob').val();

if(rec_dob=='')
{
    $(".year").val('');
    $(".day").val('');
    $(".month").val('');
    $("#rec_dob_error").html('Please select Date Of Birth');
    flag = false;
}
else
{
    // Age calculation as on 01-11-2025
    var min_dob = '1960-11-01';  // 65 years max age
    var max_dob = '1970-11-01';  // 55 years min age

    if(rec_dob < min_dob)
    {
        $("#rec_dob_error").html('The minimum age limit to apply is 55 years as on 01-11-2025');
        flag = false;
    }
    else if(rec_dob > max_dob)
    {
        $("#rec_dob_error").html('The maximum age limit to apply is 65 years as on 01-11-2025');
        flag = false;
    }
    else
    { 
        $("#rec_dob_error").html(''); 
    }
}
		

		//  FOR CEO POSITION
		var head_pdc_nz_dob = $('#head_pdc_nz_dob').val();
		if(head_pdc_nz_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#head_pdc_nz_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(typeof(smevalidation) != "undefined" && smevalidation !== null) 
			{
				$("#head_pdc_nz_dob_error").html('');
			}	
			else
			{
				if(head_pdc_nz_dob < '1964-02-01')  
				// if(head_pdc_nz_dob < '1963-11-01')  
				{
					$("#head_pdc_nz_dob_error").html('Your age criteria does not match.');
					flag = false;
				}
				else if(head_pdc_nz_dob > '1971-01-31') 
				// else if(head_pdc_nz_dob > '1970-11-01')
				{
					$("#head_pdc_nz_dob_error").html('Your age criteria does not match.');
					flag = false;
				}
				else 
				{ 
					$("#head_pdc_nz_dob_error").html(''); 
				}
			}
		}

		// FOR HEAD PDC POSITION
		var head_pdc_nz_dob_new = $('#head_pdc_nz_dob_new').val();
		if(head_pdc_nz_dob_new=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#head_pdc_nz_dob_new_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(typeof(smevalidation) != "undefined" && smevalidation !== null) 
			{
				$("#head_pdc_nz_dob_new_error").html('');
			}	
			else
			{
				// if(head_pdc_nz_dob_new < '1964-02-01')  
				if(head_pdc_nz_dob_new < '1963-11-01')  
				{
					$("#head_pdc_nz_dob_new_error").html('Your age criteria does not match.');
					flag = false;
				}
				// else if(head_pdc_nz_dob_new > '1971-01-31') 
				else if(head_pdc_nz_dob_new > '1970-11-01')
				{
					$("#head_pdc_nz_dob_new_error").html('Your age criteria does not match.');
					flag = false;
				}
				else 
				{ 
					$("#head_pdc_nz_dob_new_error").html(''); 
				}
			}
		}
		
		var corporate_development_officer_dob = $('#corporate_development_officer_dob').val();
		if(corporate_development_officer_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#corporate_development_officer_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(corporate_development_officer_dob < '1959-07-31') 
			{
				$("#corporate_development_officer_dob_error").html('The minimum age limit to apply is 50 years as on 31.07.2021');
				flag = false;
			}
			else if(corporate_development_officer_dob > '1971-07-31') 
			{
				$("#corporate_development_officer_dob_error").html('The maximum age limit to apply is 62 years as on 31.07.2021');
				flag = false;
			}	
			else { $("#corporate_development_officer_dob_error").html(''); }
		}
		
		var research_associate_dob = $('#research_associate_dob').val();
		if(research_associate_dob=='')
		{
			$(".year").val('');
			$(".day").val('');
			$(".month").val('');
			//alert('Please select Date Of Birth');
			$("#research_associate_dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		else
		{
			if(research_associate_dob < "1992-01-31")
			{
				$("#research_associate_dob_error").html('The maximum age limit is 30 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else if(research_associate_dob > "2004-01-31")
			{
				$("#research_associate_dob_error").html('The minimum age limit is 18 years as on 31-01-2022 to apply.');
				flag = false;
			}
			else 
			{ 
				$("#research_associate_dob_error").html('');
			}
		}
				
		if(doj=='')
		{
			$("#doj_error").html('Please select Date Of Joining');
			flag = false;
		}
		var form_flag=$('#usersAddForm').parsley().validate();
		
		if(code!='' && flag && form_flag)
		{
			$.ajax({
				url: site_url+'Careers/ajax_check_captcha',
				type: 'post',
			    data:'code='+code+'&random='+Math.random(),
				success: function(result) {
				if(result=='true')
				{
				/* doConfirm(function yes() {
        			   preview();
				  }, function no() {
       	  	 	  // do nothing
      		  	});*/
      		  	
      		  	if(typeof(smevalidation) != "undefined" && smevalidation !== null) 
					{
						$("#usersAddForm").submit();
					}
					else
					{
						$('#confirm').modal('show')
					}	
				}
				else
				{
					$('#captchaid').html('Enter valid captcha code.');
				}
					//alert(result);
					//window.open(site_url+'Careers/preview', '_blank')
					//	return false
				}/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			});
		}
		
	}
	
	//for Careers member user preview
	function preview()
	{
		var formData = new FormData( $("#usersAddForm")[0] );
		
		$.ajax({
				url: site_url+'Careers/setsession',
				type: 'post',
				data:formData,
				async : false,
				cache : false,
				contentType : false,
				processData : false,
				success: function(result) {
				//alert(result);
				if(result!=false)
				{
					window.open(site_url+'Careers/preview', '_self')
				//	return false
				}
				else
				{
					alert('Please upload valid files');
					return false;
				}
				}/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			});
	}
	
	//for non-member user checkform
 	function non_mem_checkform()
 	{
		var flag=true;
		var sub_flag=1;
		$('#error_id').html(''); 
		$('#success_id').html(''); 
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		$('#success_id').removeClass("alert alert-danger alert-dismissible");
		
		$('#non_mem_captchaid').html('');
		var code=$('#code').val();
		var flag=$('#nonmemAddForm').parsley().validate();
		var dob = $('#dob1').val();
		if(dob=='')
		{
			$("#dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		
		
		
			if(code!='' && flag)
			{
				var date_elements= document.getElementsByClassName('venue_cls');
				for (var i=1; i<=date_elements.length; i++) 
				{
					if(document.getElementById("venue_"+i).value!='' && document.getElementById("date_"+i).value!='' && document.getElementById("time_"+i).value!='')
					{	
						for (var j=1; j<=date_elements.length; j++) 
						{
								if(i!=j)
								{
									//if(document.getElementById("venue_"+i).value==document.getElementById("venue_"+j).value && document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
									if( document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
									{
										sub_flag=0;
									}
								}
							}
						}
				}
				if(sub_flag==1)
				{
					$.ajax({
						url: site_url+'nonreg/ajax_check_captcha',
						type: 'post',
						data:'code='+code,
						success: function(result) {
						if(result=='true')
						{
							$('#confirm').modal('show');
							//non_mem_preview();
						}
						else
						{
							$('#non_mem_captchaid').html('Enter valid captcha code.');
						}
					
						//alert(result);
						//window.open(site_url+'Careers/preview', '_blank')
						//	return false
						}/*,
						error: function(e) {
							alert('Error occured: ' + JSON.stringify(e));
						}*/
					});
				}
				else
				{
					alert('Date and Time for Venue can not be same!');
					return false;
				}
			}
	}
	//for DB&F member user checkform
 	function dbf_mem_checkform()
 	{
		$('#non_mem_captchaid').html('');
		var code=$('#code').val();
		var flag=$('#nonmemAddForm').parsley().validate();
		if(code!='' && flag)
		{
			$.ajax({
					url: site_url+'Dbfuser/ajax_check_captcha',
					type: 'post',
					data:'code='+code,
					success: function(result) {
					if(result=='true')
					{
						$('#confirm').modal('show');
						//non_mem_preview();
					}
					else
					{
						$('#non_mem_captchaid').html('Enter valid captcha code.');
					}
				
					//alert(result);
					//window.open(site_url+'Careers/preview', '_blank')
					//	return false
					}/*,
					error: function(e) {
						alert('Error occured: ' + JSON.stringify(e));
					}*/
				});
			
			}
 		}
	//for member non-mem  preview
	function non_mem_preview()
	{
			var formData = new FormData( $("#nonmemAddForm")[0] );
			$.ajax({
				url: site_url+'nonreg/setsession',
				type: 'post',
				data:formData,
				async : false,
				cache : false,
				contentType : false,
				processData : false,
				success: function(result) {
				//alert(result);
				if(result!=false)
				{
					window.open(site_url+'nonreg/preview', '_self')
				
				}
				else
				{
					alert('Please upload valid files');
					return false;
				}//return false
				}/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			});
		
	}
	
	//for member non-mem  preview
	function Special_exam_preview()
	{
				var formData = new FormData( $("#nonmemAddForm")[0] );
				/*$.ajax({
				url: site_url+'SplexamNM/setsession',
				type: 'post',
				data:formData,
				async : false,
				cache : false,
				contentType : false,
				processData : false,
				success: function(result) {*/
				//alert(result);
	/*change byt pooj@ godse */			
				//window.open(site_url+'SplexamNM/preview', '_self')
				return true;
				//}
				/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			//});
		
	}

//for member non-mem  preview
	function dbf_mem_preview()
	{
			/*$.ajax({
				url: site_url+'Dbfuser/setsession',
				type: 'post',
				data:$("form").serialize(),
				success: function(result) {*/
				//alert(result);
/*changes done by pooj@ godse*/		
				//window.open(site_url+'Dbfuser/preview', '_self')
				return true;
				//}
				/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			//});
		
	}

	function checkEdit()
	{	
		var flag =true;
		var optedu = $('input[name=optedu]:checked').val();
		//var idproof = $('input[name=idproof]:checked').val();
		var idproof = $('#idproof').val();
		//var optnletter= $('input[name=optnletter]:checked').val();
		var optnletter= $('#optnletter').val();
		
		var specify_q=$('#specify_q').val();
		var eduqual1=$('#eduqual1').val();
		var eduqual2=$('#eduqual2').val();
		var eduqual3=$('#eduqual3').val();
		
		var statecode=document.getElementById("state").value;
		
		if($('#doj1').val()=='')
		{
			alert('Please select Date of joining Bank/Institution');
			 flag =false;
			 return false;
		}
		else if($('#doj1').val()=='')
		{
			var flag =false;
		}
		else if($('#dob1').val()=='')
		{
			var flag =false;
		}
		
		else if($('#sel_namesub').val()=='')
		{
			var flag =false;
		}
		else if($('#firstname').val()=='')
		{
			var flag =false;
		}
		
		else if($('#nameoncard').val()=='')
		{
			var flag =false;
		}
		else if($('#addressline1').val()=='')
		{
			var flag =false;
		}
		else if($('#district').val()=='')
		{
			var flag =false;
		}
		else if($('#city').val()=='')
		{
			var flag =false;
		}
		else if($('#state').val()=='')
		{
			var flag =false;
		}
		else if($('#pincode').val()=='')
		{
			var flag =false;
		}
		else if($('#optedu').val()=='')
		{
			var flag =false;
		}
		else if($('#institutionworking').val()=='')
		{
			var flag =false;
		}		
		
		else if($('#office').val()=='')
		{
			var flag =false;
		}	
		else if($('#designation').val()=='')
		{
			var flag =false;
		}	
		else if($('#email').val()=='')
		{
			var flag =false;
		}
		else if($('#mobile').val()=='')
		{
			var flag =false;
		}
		else if(idproof=='')
		{
			var flag =false;
		}
				
		else if($('#gender').val()=='')
		{
			var flag =false;
		}
		
		if(statecode=='ASS' || statecode=='JAM' || statecode=='MEG')
		{
			var flag=true;
		}
		else
		{		
			  if($('#aadhar_card').val()=='')
				{
					var flag =false;
				}
		}
		
		var sel_doj = $("#doj1").val();
			if(sel_doj!='')
			{
				var doj_arr = sel_doj.split('-');
				if(doj_arr.length == 3)
				{
					$("#doj_error").html('');
					var flg1 = CompareToday(doj_arr[2],doj_arr[1],doj_arr[0]);	
				}
				else
				{
					$("#doj_error").html('Select valid date');
					var flg1 =  false;
					return false;
				}
			}
			else
			{
				$("#doj_error").html('Select valid date');
				var flg1 =  false;
				return false;
			}
			
			var sel_dob = $("#dob1").val();
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					$("#dob_error").html('');
					var flg2 = chkage(dob_arr[2],dob_arr[1],dob_arr[0]);
					if(!flg2)
					{
						flg2=false;
						flag=false;
					}
					//checkDoj();	
				}
				else
				{
					$("#dob_error").html('Select valid date');
					var flg2 = false;
					return false;
				}
			}
			else
			{
				$("#dob_error").html('Select valid date');
				var flg2 = false;
				return false;
			}
			
		//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
		if(flag)
		{
			if($('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && $('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && $('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && $('#pincode').val() == $('#pincode_hidd').val()  
			&& optedu == $('#optedu_hidd').val() && $('#institutionworking').val() == $('#institutionworking_hidd').val() &&
			$('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && idproof == $('#idproof_hidd').val() && $('#aadhar_card').val() == $('#aadhar_card_hidd').val() 
			&& optnletter == $('#optnletter_hidd').val() && $('#designation').val().trim() == $('#designation_hidd').val().trim() && $('#doj1').val()==$('#doj_hidd_validate').val() && (eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q)  && $('#dob_hidd').val()==$("#dob1").val() && $('#hidd_nameoncard').val()==$('#nameoncard').val() && $('#firstname').val()==$('#hidd_firstname').val() && $('#sel_namesub').val()==$('#hidd_sel_namesub').val() && $('#gender').val()==$('#hidd_gender').val() && $('#middlename').val()==$('#hidd_middlename').val()  && $('#lastname').val()==$('#hidd_lastname').val() )
			{
					alert("Please Change atleast One Value");
					return false;
			}
			else
			{
				if(flg1 && flg2)
				{
					return flag=$('#edit_profile').parsley().validate();
				}
				else
				{
					return false;
				}
			}       
		}
		else if(flg2)
		{
			return flag=$('#edit_profile').parsley().validate();
		}
		else
		{
			return false;
		}
	}
	
	function checkEditNM()
	{
			var flag=true;
			//var gender = $('input[name=gender]:checked').val();
			var checkblank=$('#checkblank').val();
			var optedu = $('input[name=optedu]:checked').val();
			//var idproof = $('input[name=idproof]:checked').val();
			var idproof = $('#idproof').val();
			var specify_q=$('#specify_q').val();
			var eduqual1=$('#eduqual1').val();
			var eduqual2=$('#eduqual2').val();
			var eduqual3=$('#eduqual3').val();
			var idNo=$('#idNo').val();
			var aadhar_card=$('#aadhar_card').val();
			
			
			
			if(idproof=='')
			{
				flag=false;
			}
			else if($('#mobile').val()=='')
			{
				flag=false;
			}
			else if($('#email').val()=='')
			{
				flag=false;
			}
			else if(optedu=='')
			{
				flag=false;
			}
			
			else if($('#dob1').val()=='')
			{
				alert('Please select Date of Birth');
				flag=false;
				return false;
			}		
			
			else if($('#state').val()=='')
			{
				flag=false;
			}	
			
			else if($('#pincode').val()=='')
			{
				flag=false;
			}	
			
			else if($('#city').val()=='')
			{
				flag=false;
			}	
			else if($('#district').val()=='')
			{
				flag=false;
			}	
			
			else if($('#addressline1').val()=='')
			{
				flag=false;
			}	
			
			else if($('#sel_namesub').val()=='')
			{
				flag=false;
			}
			
			else if($('#firstname').val()=='')
			{
				flag=false;
			}
			
			else if($('#gender').val()=='')
			{
				var flag =false;
			}
			
			else if($('#idNo').val()=='')
			{
				var flag =false;
			}
			
			else if($('#aadhar_card').val()=='')
			{
				var flag =false;
			}
					
					
			
			
			
			
			/*alert(specify_q);
			alert(eduqual1);
			alert(eduqual2);
			alert(eduqual3);
			
			if(eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q)
			{
				alert('return true');
			}
			else
			{
				alert('return false');
			}
			 return false;*/
			
			//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
			
			/*var sel_dob = $("#dob1").val();
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					$("#dob_error").html('');
					var flg1 = chkagenonmem(dob_arr[2],dob_arr[1],dob_arr[0]);
					if(!flg1)
					{
						flg1=false;
						return false;
					}
					//checkDoj();	
				}
				else
				{
					$("#dob_error").html('Select valid date');
					var flg1 = false;
					return false;
				}
			}
			else
			{
				$("#dob_error").html('Select valid date');
				var flg1 = false;
				return false;
			}*/
			
		
			if(flag)
			{
				
			if($('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && $('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && $('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && $('#dob1').val() == $('#dob_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && optedu == $('#optedu_hidd').val() && $('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && (eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q) && idproof == $('#idproof_hidd').val() && $('#firstname').val()==$('#hidd_firstname').val() && $('#sel_namesub').val()==$('#hidd_sel_namesub').val() 	&& $('#gender').val()==$('#hidd_gender').val() && $('#aadhar_card').val() == $('#aadhar_card_hidd').val() && $('#idNo').val() == $('#idNo_hidd').val() && $('#middlename').val()==$('#hidd_middlename').val()  && $('#lastname').val()==$('#hidd_lastname').val() )
					{
						alert("Please Change atleast One Value");
						return false;
					}
					else
					{
						return flag=$('#NMProfile').parsley().validate();
					}        
				
			}
			else
			{
				return flag=$('#NMProfile').parsley().validate();
			}
	}
	
	
	/*function checkEditDBF()
	{
			//var gender = $('input[name=gender]:checked').val();
			var optedu = $('input[name=optedu]:checked').val();
			var idproof = $('input[name=idproof]:checked').val();
			var optnletter= $('input[name=optnletter]:checked').val();
			var specify_q=$('#specify_q').val();
			var eduqual1=$('#eduqual1').val();
			var eduqual2=$('#eduqual2').val();
			var eduqual3=$('#eduqual3').val();
			//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
			if($('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && $('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && $('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && $('#doj').val() == $('#doj_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && optedu == $('#optedu_hidd').val() && $('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && optnletter == $('#optnletter_hidd').val() && (eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q))
			{
					alert("Please Change atleast One Value");
					return false;
			}
			else
			{
					return flag=$('#NMProfile').parsley().validate();
			}        
	}*/


	function checkEditDBF()
	{
			var flag=true;
			//var gender = $('input[name=gender]:checked').val();
			var checkblank=$('#checkblank').val();
			var optedu = $('input[name=optedu]:checked').val();
			//var idproof = $('input[name=idproof]:checked').val();
			var idproof = $('#idproof').val();
			var specify_q=$('#specify_q').val();
			var eduqual1=$('#eduqual1').val();
			var eduqual2=$('#eduqual2').val();
			var eduqual3=$('#eduqual3').val();
			var idNo=$('#idNo').val();
			var aadhar_card=$('#aadhar_card').val();
			
			
			
			if(idproof=='')
			{
				flag=false;
			}
			else if($('#mobile').val()=='')
			{
				flag=false;
			}
			else if($('#email').val()=='')
			{
				flag=false;
			}
			else if(optedu=='')
			{
				flag=false;
			}
			
			else if($('#dob1').val()=='')
			{
				alert('Please select Date of Birth');
				flag=false;
				return false;
			}		
			
			else if($('#state').val()=='')
			{
				flag=false;
			}	
			
			else if($('#pincode').val()=='')
			{
				flag=false;
			}	
			
			else if($('#city').val()=='')
			{
				flag=false;
			}	
			else if($('#district').val()=='')
			{
				flag=false;
			}	
			
			else if($('#addressline1').val()=='')
			{
				flag=false;
			}	
			
			else if($('#sel_namesub').val()=='')
			{
				flag=false;
			}
			
			else if($('#firstname').val()=='')
			{
				flag=false;
			}
			/*else if($('#nameoncard').val()=='')
			{
				var flag =false;
			}*/
			else if($('#gender').val()=='')
			{
				var flag =false;
			}
			
			else if($('#idNo').val()=='')
			{
				var flag =false;
			}
			
			else if($('#aadhar_card').val()=='')
			{
				var flag =false;
			}
			else if(eduqual1=='' && eduqual2=='' && eduqual3=='')
			{
				var flag =false;
			}
			/*alert(specify_q);
			alert(eduqual1);
			alert(eduqual2);
			alert(eduqual3);
			
			if(eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q)
			{
				alert('return true');
			}
			else
			{
				alert('return false');
			}
			 return false;*/
			
			//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
			
			/*var sel_dob = $("#dob1").val();
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					$("#dob_error").html('');
					var flg1 = chkagenonmem(dob_arr[2],dob_arr[1],dob_arr[0]);
					if(!flg1)
					{
						flg1=false;
						return false;
					}
					//checkDoj();	
				}
				else
				{
					$("#dob_error").html('Select valid date');
					var flg1 = false;
					return false;
				}
			}
			else
			{
				$("#dob_error").html('Select valid date');
				var flg1 = false;
				return false;
			}*/
			
		
			if(flag)
			{
					/*if($('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && 
					$('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && 
					$('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && 
					$('#dob1').val() == $('#dob_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && optedu == $('#optedu_hidd').val() &&
					 $('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && 
					 $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && 
					 (eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q) && idproof == $('#idproof_hidd').val() 
					 && $('#firstname').val()==$('#hidd_firstname').val() && $('#sel_namesub').val()==$('#hidd_sel_namesub').val() 
					 && $('#gender').val()==$('#gender_hidd').val() && $('#aadhar_card').val() == $('#aadhar_card_hidd').val() 
					 && $('#idNo').val() == $('#idNo_hidd').val() && $('#hidd_nameoncard').val()==$('#nameoncard').val() && $('#middlename').val()==$('#hidd_middlename').val()  && $('#lastname').val()==$('#hidd_lastname').val() )*/
					 if($('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && 
					$('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && 
					$('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && 
					$('#dob1').val() == $('#dob_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && optedu == $('#optedu_hidd').val() &&
					 $('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && 
					 $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && 
					 (eduqual1==specify_q && eduqual2==specify_q && eduqual3==specify_q) && idproof == $('#idproof_hidd').val() 
					 && $('#firstname').val()==$('#hidd_firstname').val() && $('#sel_namesub').val()==$('#hidd_sel_namesub').val() 
					 && $('#gender').val()==$('#gender_hidd').val() && $('#aadhar_card').val() == $('#aadhar_card_hidd').val() 
					 && $('#idNo').val() == $('#idNo_hidd').val() &&  $('#middlename').val()==$('#hidd_middlename').val()  && $('#lastname').val()==$('#hidd_lastname').val() )
					{
						alert("Please Change atleast One Value");
						return false;
					}
					else
					{
						return flag=$('#NMProfile').parsley().validate();
					}        
				
			}
			else
			{
				return flag=$('#NMProfile').parsley().validate();
			}
	}
	
	function checkEditImage()
	{
		if($('#scannedphoto').val()=='' && $('#scannedsignaturephoto').val()=='' && $('#idproofphoto').val()=='')
		{
			alert("Please Change atleast One Value");
			return false;	
		}
		else
		{
			return true;
		}
	}
	
	//for logged in member  checkform
 	function loginusercheckform()
 	{
		var sub_flag=1;
		var date_elements= document.getElementsByClassName('venue_cls');
		if($('#member_conApplication').parsley().validate())
		{
			for (var i=1; i<=date_elements.length; i++) 
			{
			if(document.getElementById("venue_"+i).value!='' && document.getElementById("date_"+i).value!='' && document.getElementById("time_"+i).value!='')
			{	
				for (var j=1; j<=date_elements.length; j++) 
				{
						if(i!=j)
						{
							//if(document.getElementById("venue_"+i).value==document.getElementById("venue_"+j).value && document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
							if(document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
							{
								sub_flag=0;
							}
						}
					}
				}
		}
		}
		if(sub_flag==1)
		{
			var flag=$('#member_conApplication').parsley().validate();
			if(flag)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			alert('Date and Time for Venue can not be same!');
			return false;
		}
 	}
	
	
	//for logged in member  checkform during special exam apply
 	function splexamapply()
 	{
		var flag=$('#member_conApplication').parsley().validate();
		if(flag)
		{
			return true;
		}
		else
		{
			return false;
		}
	
 	}
	
	
	//Member can apply exam directly
 	function member_apply_exam()
 	{
		var sub_flag=1;
		var date_elements= document.getElementsByClassName('venue_cls');
		if($('#member_conApplication').parsley().validate())
		{
		for (var i=1; i<=date_elements.length; i++) 
		{
			if(document.getElementById("venue_"+i).value!='' && document.getElementById("date_"+i).value!='' && document.getElementById("time_"+i).value!='')
			{	
				for (var j=1; j<=date_elements.length; j++) 
				{
						if(i!=j)
						{
							//if(document.getElementById("venue_"+i).value==document.getElementById("venue_"+j).value && document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
							if(document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
							{
								sub_flag=0;
							}
						}
					}
				}
		}
		}
		
		if(sub_flag==1)
		{
		var flag=$('#member_conApplication').parsley().validate();
		if(flag)
		{
	 		return true;
		}
		else
		{
			return false;
		}
		}
		else
		{
			alert('Date and Time for Venue can not be same!');
			return false;
		}
 	}
	
	//Member can apply for special exam directly 
 	function spl_member_apply_exam()
 	{
		var flag=$('#member_conApplication').parsley().validate();
		if(flag)
		{
			return true;
		}
		else
		{
			return false;
		}
	
 	}
	
	//for logged in non-member  checkform
 	function login_nm_checkform()
 	{
		var sub_flag=1;
		var date_elements= document.getElementsByClassName('venue_cls');
		for (var i=1; i<=date_elements.length; i++) 
		{
			if(document.getElementById("venue_"+i).value!='' && document.getElementById("date_"+i).value!='' && document.getElementById("time_"+i).value!='')
			{	
				for (var j=1; j<=date_elements.length; j++) 
				{
						if(i!=j)
						{
							//if(document.getElementById("venue_"+i).value==document.getElementById("venue_"+j).value && document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
							if(document.getElementById("date_"+i).value==document.getElementById("date_"+j).value && document.getElementById("time_"+i).value==document.getElementById("time_"+j).value)
							{
								sub_flag=0;
							}
						}
			  	  }
			 }
		}
		if(sub_flag==1)
		{
			var flag=$('#member_conApplication').parsley().validate();
			if(flag)
			{
					 return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			alert('Date and Time for Venue can not be same!');
			return false;
		}
	
 	}
	
	
	//loggedin non-member  Special Exam
 	function login_specialexam_checkform()
 	{
		var flag=$('#member_conApplication').parsley().validate();
		if(flag)
		{
			return true;
		}
		else
		{
			return false;
		}
	
 	}
	
	
	//for logged in dbf checkform
 	function login_dbf_checkform()
 	{
		var flag=$('#member_conApplication').parsley().validate();
		if(flag)
		{
			return true;
		}
		else
		{
			return false;
		}
	
 	}
	
	
	//validate logged in user before payment gate-way
	function validate()
	{
		if(!confirm("You will be redirected to Billdesk Payment Gateway for Making Payment. Do you want to proceed.")){
			return false;
		}
		document.member_exam_comApplication.submit();
	}
	
	//check for duplicate I-card form
 	function checkDuplicateIcard()
 	{
		var flag=$('#member_dupliatecard').parsley().validate();
		if(flag)
		{
				return true;
		}
		else
		{
			return false;
		}
	
 	}
	
	function alphanumberctrl(e)
	{	
		var key;
		var keychar;
		var isCtrl;
		var forbiddenKeys = new Array('a', 'n', 'c', 'x', 'v', 'j');
		if (window.event){
			key = window.event.keyCode;	
			if(window.event.ctrlKey)
				isCtrl = true;
			else
				isCtrl = false;
		}else if (e){
			key = e.which;
			if(e.ctrlKey)
				isCtrl = true;
			else
				isCtrl = false;
		}
		else
			return true;
		//alert(key);
		if(isCtrl)
		{
			for(i=0; i<forbiddenKeys.length; i++)
			{
					//case-insensitive comparation				
				if(forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
				{
						alert('Key combination CTRL + '
								+String.fromCharCode(key)
								+' has been disabled.');
						return false;
				}
			}
		}
	
		if((key == 8) || (key == 0))
			return true;
	}

	function alphanumber(e)
	{	
		var key;
		var keychar;
		if (window.event){
			key = window.event.keyCode;		
		}else if (e){
			key = e.which;		
		}
		else
			return true;
		//alert(key);
		if((key == 8) || (key == 0))
			return true;
		
		
		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();
		//alert(keychar);
		
		if(key!=32)
		{ 
			
			invalids = "`~@#$%^*-()_+=\|{}[].;:'\"<>&?/!,\\";
			  for(i=0; i<invalids.length; i++) {
				if(keychar != 0)
				{
					if(keychar.indexOf(invalids.charAt(i)) >= 0 || keychar==false) {				           			  
						return false;               
					}
				}
			  }
		}
		return true;		
	}
	
	function number(e)
	{	
		var key;
		var keychar;
		if (window.event){
			key = window.event.keyCode;		
		}else if (e){
			key = e.which;		
		}
		else
			return true;
		
		if((key == 8) || (key == 0))
			return true;
			
		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();
		if((key > 47) && (key < 58)){				
			return true;
		}else
			return false;	
	}
	
	//member login edit profile
	function edit_profile_preview()
	{
		window.open(site_url+'Home/printUser', '_blank');
	}
	//member login exam details
	function print_exam_preview()
	{
		window.open(site_url+'Home/printexamdetails', '_blank');
	}
	
	
	//member login exam details
	function specl_apply_print_exam_preview()
	{
		window.open(site_url+'ApplySplexamM/printexamdetails', '_blank');
	}
	
	//member login exam details for special exam
	function print_exam_preview_splexam()
	{
		window.open(site_url+'SplexamM/printexamdetails', '_blank');
	}
	
	//non-member exam details
	function print_exam_non_mem_preview()
	{
		window.open(site_url+'NonMember/printexamdetails', '_blank');
	}
	
	//non-member exam apply for special exam
	function print_special_exam_non_mem_preview()
	{
		window.open(site_url+'SpecialExamNm/printexamdetails', '_blank');
	}
	
	//Dbf member  exam details
	function print_exam_dbf_preview()
	{
		window.open(site_url+'Dbf/printexamdetails', '_blank');
	}
	
	//member login duplicate details
	function print_duplicatecard_preview()
	{
		window.open(site_url+'Duplicate/print_duplicate_icard', '_blank');
	}
	
	//member Careers profile
	function Careers_profile_preview()
	{
		window.open(site_url+'Careers/printUser', '_blank');
	}
	

	//Non-member login edit profile
	function edit_profile_Nonmemb_preview()
	{
		window.open(site_url+'NonMember/printUser', '_blank');
	}
	
	//DBF-member login edit profile
	function edit_profile_DBF_preview()
	{
		window.open(site_url+'Dbf/printUser', '_blank');
	}
	
	// Renewal Member Profile
	function Renewal_profile_preview()
	{
		window.open(site_url+'Renewal/printUser', '_blank');
	}
	
	
	
	function chkage(day,month,year)
	{
		var flag = 0;
		if(day!='' && month!='' && year!='')
		{
			var dday  = day;
			var dmnth = month;
			var dyear = year;
			var dob_date = new Date(dyear, dmnth-1 , dday);
			var min_year = $("#minyear").val();
			var max_year = $("#maxyear").val();
			
			var date_max = new Date(max_year, '10', '01');
			var date_min = new Date(min_year, '10', '01');
			if($("#doj1").length > 0) {
				var sel_doj = $("#doj1").val();
			
				var dojYear = 0;
				
				if(sel_doj!='')
				{
					var doj_arr = sel_doj.split('-');
					if(doj_arr.length == 3)
					{
						dojYear = doj_arr[0];
					}
				}
			}
			
			var minjoinyear = parseInt(dyear) + parseInt(18);
			
			if( date_max > dob_date || date_min < dob_date )
			{
				$("#dob_error").html('Your Age should be between 18 and 80');
				flag = 0;
				return false;
			}
			else
			{
				$("#dob_error").html('');
				flag = 1;
			}
			
			if(dojYear !='' && dojYear < minjoinyear )
			{
				$("#doj_error").html("Please select Proper Year of Joining");
				$("#doj_error").focus();
				flag = 0;
				return false;
			}
			else
			{
				$("#doj_error").html("");
				flag = 1;
			}
		}
		else
		{
			$("#dob_error").html('Please select valid date');
			$("#dob_error").focus();
			//return false;
			flag = 0;
		}
		if(flag==1)
			return true;
		else
			return false;
	}
	
	
	function chkagenonmem(day,month,year)
	{
		var flag = 0;
		if(day!='' && month!='' && year!='')
		{
			var dday  = day;
			var dmnth = month;
			var dyear = year;
			var dob_date = new Date(dyear, dmnth-1 , dday);
			var min_year = $("#minyear").val();
			var max_year = $("#maxyear").val();
			
			var date_max = new Date(max_year, '05', '01');
			var date_min = new Date(min_year, '12', '01');
			
			
			if( date_max > dob_date || date_min < dob_date )
			{
				$("#dob_error").html('Your Age should be between 18 and 80');
				flag = 0;
				return false;
			}
			else
			{
				$("#dob_error").html('');
				flag = 1;
			}
			
			
			
		}
		else
		{
			$("#dob_error").html('Please select valid date');
			$("#dob_error").focus();
			//return false;
			flag = 0;
		}
		if(flag==1)
			return true;
		else
			return false;
	}

	function CompareToday(day,month,year)
	{
		var flag = 0;
		if(day!='' && month!='' && year!='')
		{
			var today = new Date();
			var dd = today.getDate();
			//var mm = today.getMonth()+1; //January is 0!
			var mm = today.getMonth();
		
			var yyyy = today.getFullYear();
		
			if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} 
				var today = new Date(yyyy, mm, dd);
		
			var jday  = day;
			var jmnth = month;
			var jyear = year;
			var jdate = new Date(jyear, jmnth-1, jday);
			
			var sel_dob = $("#dob1").val();
			var dobYear = 0;
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					dobYear = dob_arr[0];
				}
			}
			var minjoinyear = parseInt(dobYear) + parseInt(18);
			
			if( jdate > today )
			{
				$("#doj_error").html('Date of joining should not be greater than today');
				flag = 0;
				return false;
			}
			else
			{
				$("#doj_error").html('');
				flag = 1;
			}
			
			if(jyear!='' && jyear < minjoinyear )
			{
				//alert("Please select Proper Year of Joining");
				$("#doj_error").html("Please select Proper Year of Joining");
				$("#doj_error").focus();
				flag = 0;
				return false;
			}
			else
			{
				$("#doj_error").html('');
				flag = 1;
			}
		}
		else
		{
			$("#doj_error").html('Please select valid date');
			$("#doj_error").focus();
			flag = 0;
		}
		if(flag==1)
			return true;
		else
			return false;
	}
	
	
	function validateSearch() 
	{
		var searchtxt=/^[0-9 \,]*$/;
		var searchalphatext=/^[0-9A-Za-z\.\- \,]*$/;
		var searchemail=/^[0-9A-Za-z\.\- \,\@,\_]*$/;
	
		if(get_radio_value(document.search.searchBy) == '')
		{
			alert("Please select Search By");
			document.getElementById('searchBy').focus();
			return false;	
		}	
		
		// This if works only for searching by registration number
		if(get_radio_value(document.search.searchBy)=='regnumber')
		{

			if(trim(document.search.searchText.value).indexOf(' ')>-1){
				alert("Please delete white spaces in search nos.");
				document.search.searchText.focus();
				return false;
				}

			if (trim(document.search.searchText.value)==""){
			alert("Please enter Search Membership Numbers");
				document.search.searchText.focus();
				return false;
			}		

			if (!searchtxt.test(trim(document.search.searchText.value))){
				alert("Invalid chars in Search Box. \n Only Numbers and comma (,) is allowed.");
				document.search.searchText.select();
				return false;
			}

			var search_content=document.search.searchText.value;
			var search_contents_values=search_content.split(",");
			if(search_contents_values.length > 50)
			{
				alert("Maximum 50 numbers/names can be search with comma (,) separated.");
				document.search.searchText.focus();
				return false;
			}
			
			document.search.submit();
			return true;
		}
		else if(get_radio_value(document.search.searchBy)=='mobile')// This if works only for searching by mobile number
		{
			if(trim(document.search.searchText.value).indexOf(' ')>-1){
				alert("Please delete white spaces in search nos.");
				document.search.searchText.focus();
				return false;
				}

			if (trim(document.search.searchText.value)==""){
			alert("Please enter Search Mobile Numbers");
				document.search.searchText.focus();
				return false;
			}		

			if (!searchtxt.test(trim(document.search.searchText.value))){
				alert("Invalid chars in Search Box. \n Only Numbers and comma (,) is allowed.");
				document.search.searchText.select();
				return false;
			}

			var search_content=document.search.searchText.value;
			var search_contents_values=search_content.split(",");
			if(search_contents_values.length > 50)
			{
				alert("Maximum 50 numbers/names can be search with comma (,) separated.");
				document.search.searchText.focus();
				return false;
			}
			
			document.search.submit();
			return true;
		}
		else if(get_radio_value(document.search.searchBy)=='transaction_no')// This if works only for searching by transaction number
		{
			if(trim(document.search.searchText.value).indexOf(' ')>-1){
				alert("Please delete white spaces in search nos.");
				document.search.searchText.focus();
				return false;
			}

			if (trim(document.search.searchText.value)==""){
			alert("Please enter Search SBI ePay Numbers");
				document.search.searchText.focus();
				return false;
			}		

			/*if (!searchtxt.test(trim(document.search.searchText.value))){
				alert("Invalid chars in Search Box. \n Only Numbers and comma (,) is allowed.");
				document.search.searchText.select();
				return false;
			}*/

			var search_content=document.search.searchText.value;
			var search_contents_values=search_content.split(",");
			if(search_contents_values.length > 50)
			{
				alert("Maximum 50 numbers/names can be search with comma (,) separated.");
				document.search.searchText.focus();
				return false;
			}
			
			document.search.submit();
			return true;
		}
		else if(get_radio_value(document.search.searchBy)=='receipt_no')// This if works only for searching by Order No.
		{

			if(trim(document.search.searchText.value).indexOf(' ')>-1){
				alert("Please delete white spaces in search nos.");
				document.search.searchText.focus();
				return false;
				}

			if (trim(document.search.searchText.value)==""){
			alert("Please enter Search Order Numbers");
				document.search.searchText.focus();
				return false;
			}		

			if (!searchtxt.test(trim(document.search.searchText.value))){
				alert("Invalid chars in Search Box. \n Only Numbers and comma (,) is allowed.");
				document.search.searchText.select();
				return false;
			}

			var search_content=document.search.searchText.value;
			var search_contents_values=search_content.split(",");
			if(search_contents_values.length > 50)
			{
				alert("Maximum 50 numbers/names can be search with comma (,) separated.");
				document.search.searchText.focus();
				return false;
			}
			
			document.search.submit();
			return true;
		}
		else if(get_radio_value(document.search.searchBy)=='email')// This if works only for searching by Email ID
		{
			if(document.search.searchText.value=="")
			{
				alert("Please enter email.");
				document.search.searchText.focus();
				return false;
			}
			var search_content=document.search.searchText.value;
			var search_contents_values=search_content.split(",");
			if(search_contents_values.length > 50)
			{
				alert("Maximum 50 numbers/names can be search with comma (,) separated.");
				document.search.searchText.focus();
				return false;
			}
			if (!searchemail.test(trim(document.search.searchText.value))){
				alert("Invalid chars in Search Box. \n Only Numbers , comma (,) ,dot(.) ,hypen(-), at sign(@), underscore(_) and Alphabets are allowed.");
				document.search.searchText.select();
				return false;
			}
			document.search.submit();
			return true;
		}
		else // This if works only for searching by Name
		{
			if(document.search.searchText.value=="")
			{
				alert("Please enter name.");
				document.search.searchText.focus();
				return false;
			}
			var search_content=document.search.searchText.value;
			var search_contents_values=search_content.split(",");
			if(search_contents_values.length > 50)
			{
				alert("Maximum 50 numbers/names can be search with comma (,) separated.");
				document.search.searchText.focus();
				return false;
			}
			if (!searchalphatext.test(trim(document.search.searchText.value))){
				alert("Invalid chars in Search Box. \n Only Numbers , comma (,) ,dot(.) ,hypen(-) and Alphabets are allowed.");
				document.search.searchText.select();
				return false;
			}
			document.search.submit();
			return true;
		}
	}		

	function trim(value) {
	   var temp = value;
	   var obj = /^(\s*)([\W\w]*)(\b\s*$)/;
	   if (obj.test(temp)) { temp = temp.replace(obj, '$2'); }
	   var obj = / +/g;
	   temp = temp.replace(obj, " ");
	   if (temp == " ") { temp = ""; }
	   return temp;
	}
	
	function get_radio_value(obj)
	{	
		var rad_val='';
		for (var i=0; i < obj.length; i++)
		{
		   if (obj[i].checked)
		   {
			  var rad_val = obj[i].value;
		   }
		}
	 return  rad_val;
	}
	
	function clearSearch(){
		document.search.searchText.value="";
		document.search.searchBy[0].checked=false;
		document.search.searchBy[1].checked=false;
		document.search.searchBy[2].checked=false;
		document.search.searchBy[3].checked=false;
		document.search.searchBy[1].focus();
	}
	
	function clearsearch()
	{
		document.search.searchText.value="";
		document.search.searchText.focus();
	}
	
	
/*	function valCentre(cCode)
	{
	document.getElementById('txtCenterCode').value = cCode ;
	var examType = document.getElementById('extype').value;
	var examCode = document.getElementById('examcode').value;
	var temp = document.getElementById("selCenterName").selectedIndex;
	selected_month = document.getElementById("selCenterName").options[temp].className;

	if(examType=='1' && (examCode=='32' || examCode=='47' || examCode=='52')) {
		$("#electivemode").css("display", "block");
		$("#electiverealmode").css("display", "none");		
		document.getElementById("optsex1").disabled=false;
		document.getElementById("optsex1").checked=false;
		document.getElementById("optsex2").disabled=true;
		document.getElementById("optsex2").checked=false;
	}
	else if(examType=='2') {
		$("#electivemode").css("display", "block");
		$("#electiverealmode").css("display", "none");
		if(examCode=='5') {
			document.getElementById("optsex1").disabled=true;
			document.getElementById("optsex1").checked=false;
			document.getElementById("optsex2").disabled=false;
			document.getElementById("optsex2").checked=false;
		}else {
			document.getElementById("optsex1").disabled=false;
			document.getElementById("optsex1").checked=false;
			document.getElementById("optsex2").disabled=true;	
			document.getElementById("optsex2").checked=false;
		}
	}
	if((examType=='4' || examType=='3' || examType=='1') && (examCode=='60' || examCode=='21'|| examCode=='62'|| examCode=='63'|| examCode=='64'|| examCode=='65'|| examCode=='66'|| examCode=='67'|| examCode=='68'|| examCode=='69'|| examCode=='70'|| examCode=='71'|| examCode=='72' || examCode=='8' || examCode=='11' || examCode=='33' || examCode=='51'))
	{
		$("#electivemode").css("display", "block");
		$("#electiverealmode").css("display", "none");
		if(selected_month == 'ON')
		{
			if(document.getElementById("optmode1")){
				document.getElementById("optmode1").style.display = "block";
				document.getElementById('optmode').value= 'ON';
			}
				
			if(document.getElementById("optmode2"))
			{
				document.getElementById("optmode2").style.display = "none";	
			}
			
		}	
		else if(selected_month == 'OF')
		{
			if(document.getElementById("optmode2")){
				document.getElementById("optmode2").style.display = "block";
				document.getElementById('optmode').value= 'OF';
			}
			if(document.getElementById("optmode1")){
				document.getElementById("optmode1").style.display = "none";
			}	
		}
		else{
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "none";
				}
				if(document.getElementById("optmode2")){
					document.getElementById("optmode2").style.display = "none";
				}
		}
	}

	else {
		$("#electivemode").css("display", "none");
		$("#electiverealmode").css("display", "block");
		if(selected_month=='ON') {
		
			document.getElementById("optsex1").disabled=false;
			document.getElementById("optsex1").checked=false;
			document.getElementById("optsex2").disabled=true;
			document.getElementById("optsex2").checked=false;
		}else {
		
			document.getElementById("optsex1").disabled=true;
			document.getElementById("optsex1").checked=false;
			document.getElementById("optsex2").disabled=false;
			document.getElementById("optsex2").checked=false;
		}
	}

	if(document.getElementById("selCenterName").value == '') {
			document.getElementById("optsex1").disabled=false;
			document.getElementById("optsex1").checked=false;
			document.getElementById("optsex2").disabled=false;
			document.getElementById("optsex2").checked=false;
	}
	}
	*/
	function valCentre(cCode)
	{
		 var subject_array=new Array();  
		document.getElementById('txtCenterCode').value = cCode ;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;
		
		var venue_elements=document.getElementsByClassName('venue_cls');
		for (var i=0; i<venue_elements.length; i++) {
			if(venue_elements[i].getAttribute("attr-data")!='' && venue_elements[i].getAttribute("attr-data")!=null)
			{
				subject_array[i]=venue_elements[i].getAttribute("attr-data");
			}
		}
		
		$(".loading").show();
		if(cCode!='')
		{
				var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype;
				$.ajax({
								url:site_url+'Fee/getFee/',
								data: datastring,
								type:'POST',
								async: false,
								success: function(data) {
								 if(data)
								{
									document.getElementById('fee').value = data ;
									document.getElementById('html_fee_id').innerHTML =data;
									//response = true;
								}
							}
						});
						
						
				var datastring_exam='centerCode='+cCode+'&examCode='+examCode+'&subject_array='+subject_array;
				$.ajax({
								url:site_url+'Venue/getVenue/',
								data: datastring_exam,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									
									var venue_elements=document.getElementsByClassName('venue_cls');
									
									/*for (var i=0; i<venue_elements.length; i++) {
											venue_elements[i].innerHTML = data.venue_option;
 									}*/
									for (var i=1; i<=venue_elements.length; i++) {
										
											//$('#venue_'+i).html(data.venue_option_1);
											 document.getElementById("venue_"+i).innerHTML = data["venue_option_" + i]; 
											//venue_elements[i].innerHTML = data.venue_option_1;
 									}
									
									var date_elements=document.getElementsByClassName('date_cls');
									for (var i=0; i<date_elements.length; i++) {
											date_elements[i].innerHTML = data.date_option;
 									}
									
									var time_elements=document.getElementsByClassName('time_cls');
									for (var i=0; i<time_elements.length; i++) {
											time_elements[i].innerHTML = data.time_option;
 									}
									}
								}
						});		
			}
			
		if(selected_month == 'ON')
		{
			if(document.getElementById("optmode1")){
				document.getElementById("optmode1").style.display = "block";
				document.getElementById('optmode').value= 'ON';
			}
				
			if(document.getElementById("optmode2"))
			{
				document.getElementById("optmode2").style.display = "none";	
			}
			
		}	
		else if(selected_month == 'OF')
		{
			if(document.getElementById("optmode2")){
				document.getElementById("optmode2").style.display = "block";
				document.getElementById('optmode').value= 'OF';
			}
			if(document.getElementById("optmode1")){
				document.getElementById("optmode1").style.display = "none";
			}	
		}
		else{
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "none";
				}
				if(document.getElementById("optmode2")){
					document.getElementById("optmode2").style.display = "none";
				}
		}
	$(".loading").hide();
	}
	
	
	function splvalCentre(cCode)
	{
		 var subject_array=new Array();  
		document.getElementById('txtCenterCode').value = cCode ;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;
		
		var venue_elements=document.getElementsByClassName('venue_cls');
		for (var i=0; i<venue_elements.length; i++) {
			if(venue_elements[i].getAttribute("attr-data")!='' && venue_elements[i].getAttribute("attr-data")!=null)
			{
				subject_array[i]=venue_elements[i].getAttribute("attr-data");
			}
		}
		
		$(".loading").show();
		if(cCode!='')
		{
				var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype;
				$.ajax({
								url:site_url+'Fee/splgetFee/',
								data: datastring,
								type:'POST',
								async: false,
								success: function(data) {
								 if(data)
								{
									document.getElementById('fee').value = data ;
									document.getElementById('html_fee_id').innerHTML =data;
									//response = true;
								}
							}
						});
						
						
				var datastring_exam='centerCode='+cCode+'&examCode='+examCode+'&subject_array='+subject_array;
				$.ajax({
								url:site_url+'Venue/getVenue/',
								data: datastring_exam,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									
									var venue_elements=document.getElementsByClassName('venue_cls');
									
									/*for (var i=0; i<venue_elements.length; i++) {
											venue_elements[i].innerHTML = data.venue_option;
 									}*/
									for (var i=1; i<=venue_elements.length; i++) {
										
											//$('#venue_'+i).html(data.venue_option_1);
											 document.getElementById("venue_"+i).innerHTML = data["venue_option_" + i]; 
											//venue_elements[i].innerHTML = data.venue_option_1;
 									}
									
									var date_elements=document.getElementsByClassName('date_cls');
									for (var i=0; i<date_elements.length; i++) {
											date_elements[i].innerHTML = data.date_option;
 									}
									
									var time_elements=document.getElementsByClassName('time_cls');
									for (var i=0; i<time_elements.length; i++) {
											time_elements[i].innerHTML = data.time_option;
 									}
									}
								}
						});		
			}
			
		if(selected_month == 'ON')
		{
			if(document.getElementById("optmode1")){
				document.getElementById("optmode1").style.display = "block";
				document.getElementById('optmode').value= 'ON';
			}
				
			if(document.getElementById("optmode2"))
			{
				document.getElementById("optmode2").style.display = "none";	
			}
			
		}	
		else if(selected_month == 'OF')
		{
			if(document.getElementById("optmode2")){
				document.getElementById("optmode2").style.display = "block";
				document.getElementById('optmode').value= 'OF';
			}
			if(document.getElementById("optmode1")){
				document.getElementById("optmode1").style.display = "none";
			}	
		}
		else{
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "none";
				}
				if(document.getElementById("optmode2")){
					document.getElementById("optmode2").style.display = "none";
				}
		}
	$(".loading").hide();
	}
	
	
	function venue(venue_code,date_id,time_id,subject_code,seat_capacity_id)
	{
		var selCenterCode= document.getElementById('selCenterName').value;
		var eprid= document.getElementById('eprid').value;
		var examcode= document.getElementById('examcode').value;
		document.getElementById(seat_capacity_id).innerHTML = '-';
		
		//	if(venue_code!='' && selCenterCode!='')
		//{
			$(".loading").show();
				//var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code;
				var datastring='eprid='+eprid+'&examcode='+examcode+'&subject_code='+subject_code+'&venue_code='+venue_code;
				$.ajax({
								url:site_url+'Venue/getDate/',
								data: datastring,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									//by jquery
									//$('#'+date_id).html(data.date_option);
									//by javascript
									document.getElementById(date_id).innerHTML = data.date_option;
									document.getElementById(time_id).innerHTML = data.time_option;
									/*var time_elements=document.getElementsByClassName('time_cls');
									for (var i=0; i<time_elements.length; i++) {
											time_elements[i].innerHTML = data.time_option;
 									}*/
									//by class
									/*var date_elements=document.getElementsByClassName('date_cls');
									for (var i=0; i<date_elements.length; i++) 
									{
											date_elements[i].innerHTML = data.date_option;
 									}*/
								}
							}
					});		
				//}	
			$(".loading").hide();
		}
		
		
	function caiib_venue(venue_code,date_id,time_id,seat_capacity_id)
	{
		var subject_code=document.getElementById('selSubcode').value;
		var selCenterCode= document.getElementById('selCenterName').value;
		var eprid= document.getElementById('eprid').value;
		var examcode= document.getElementById('examcode').value;
		
		
		document.getElementById(seat_capacity_id).innerHTML = '-';
		
		//	if(venue_code!='' && selCenterCode!='')
		//{
			$(".loading").show();
				//var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code;
				var datastring='eprid='+eprid+'&examcode='+examcode+'&subject_code='+subject_code+'&venue_code='+venue_code;
				$.ajax({
								url:site_url+'Venue/getDate/',
								data: datastring,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									//by jquery
									//$('#'+date_id).html(data.date_option);
									//by javascript
									document.getElementById(date_id).innerHTML = data.date_option;
									document.getElementById(time_id).innerHTML = data.time_option;
									/*var time_elements=document.getElementsByClassName('time_cls');
									for (var i=0; i<time_elements.length; i++) {
											time_elements[i].innerHTML = data.time_option;
 									}*/
									//by class
									/*var date_elements=document.getElementsByClassName('date_cls');
									for (var i=0; i<date_elements.length; i++) 
									{
											date_elements[i].innerHTML = data.date_option;
 									}*/
								}
							}
					});		
				//}	
			$(".loading").hide();
		}		
		
	function date(date_code,venue_id,time_id)
	{
		var selCenterCode= document.getElementById('selCenterName').value;
		var venue_code= document.getElementById(venue_id).value;
		
		//if(date_code!='' && venue_code!='' && selCenterCode!='')
		//{
		$(".loading").show();
			var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code+'&date_code='+date_code;
			$.ajax({
								url:site_url+'Venue/getTime/',
								data: datastring,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									//by jquery
									//$('#'+date_id).html(data.time_option);
									//by javascript
									document.getElementById(time_id).innerHTML = data.time_option;
									//by class
									/*var date_elements=document.getElementsByClassName('date_cls');
									for (var i=0; i<date_elements.length; i++) 
									{
											date_elements[i].innerHTML = data.time_option;
 									}*/
								}
							}
					});		
				//}	
		$(".loading").hide();
		}
		
	
		
	function time(time,venue_id,date_id,seat_capcity_id)
	{
		var selCenterCode= document.getElementById('selCenterName').value;
		var venue_code= document.getElementById(venue_id).value;
		var date_id= document.getElementById(date_id).value;
		//if(date_code!='' && venue_code!='' && selCenterCode!='')
		//{
		$(".loading").show();
			var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code+'&date_code='+date_id+'&time='+time;
			$.ajax({
							url:site_url+'Venue/getCapacity/',
							data: datastring,
							type:'POST',
							async: false,
							dataType: 'json',
							success: function(data) {
							//$.parseJSON(data);
							 if(data)
							{
								document.getElementById(seat_capcity_id).innerHTML = data.capacity;
							}
						}
					});		
			$(".loading").hide();
		}
	
	
		
   function readURL(input,div) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#'+div).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
/*CMS page validation*/
$(document).ready(function() {
	/*$("#cmsfrm").submit(function() {
		if( $("#cms_memberno").val() == '' && !$("#remember_memno").prop('checked') ) {
			alert("Please enter Membership/ Registration No (or) Please check Don't Remember Membership/Registration No");
			return false;	
		}	
		return true;
	});*/
	$(".complaint-submit").on("click", function() {
		if( $("#cms_memberno").val() == '' && !$("#remember_memno").prop('checked') ) {
			alert("Please enter Membership/ Registration No (or) Please check Don't Remember Membership/Registration No");
			return false;
		}
	});
	if( $(".extra-info-cms").length > 0 ) 
		$(".extra-info-cms").hide();
	$("#remember_memno").on("change", function() {
		if($(this).prop('checked')) {
			$(".extra-info-cms").show();
			$("#cms_memberno").val("").attr('readonly','true');
			$("#name").val("").attr("required","true").removeAttr("readonly");
			$("#dateofbirth").val("").attr("required","true").removeAttr("readonly");
			$("#employer_name").val("").attr("required","true").removeAttr("readonly");
			$("#email").val("").attr("required","true").removeAttr("readonly");
			$("#mobileno").val("").attr("required","true").removeAttr("readonly");
			$(".day").val("").removeAttr('disabled');
			$(".month").val("").removeAttr('disabled');
			$(".year").val("").removeAttr('disabled');
		} else {
			$(".extra-info-cms").hide();
			$("#cms_memberno").removeAttr('readonly');
			$("#name").removeAttr("required");
			$("#dateofbirth").removeAttr("required");
			$("#employer_name").removeAttr("required");
			//$("#email").removeAttr("required");
			//$("#mobileno").removeAttr("required");
		}
	});
	$("#querycat").on("change", function() {
		var qsubcat = $("#querysubcat").val();
		var sdata = { catcode : $(this).val(), subcat : qsubcat }
		$.ajax({
			url:site_url+'CmsComplaint/get_query_subcat/',
			data: sdata,
			type:'POST',
			success: function(data) {
				var obj = jQuery.parseJSON( data );
				$.each( obj, function( key, value ) {
					if( key == 'subcathtml' ) {
						$("#querysubcat").html(value);
					} else if( key == 'is_membershipno' ) {
						if( value == 0 ) {
							$("#remember_memno").attr("disabled", true).prop('checked', false);
							$("#remember_memno").trigger("change");
						} else {
							$("#remember_memno").removeAttr("disabled");
						}	
					} else if( key == 'is_examselect' ) {
						if( value == 0 ) {
							$("#examname").attr("disabled", "true").removeAttr("required");
						} else {
							$("#examname").attr("required", "true").removeAttr("disabled");
						}	
					} else if( key == 'examhtml' ) {
						if( value != '' ) {
							$("#examname").html(value);
						}
					}
				});
				//$("#querysubcat").html(data);
			}
		});
	});
	$("#querysubcat").on("change", function() {
		var qsubcat = $(this).val();
		var sdata = { subcat : qsubcat }
		$.ajax({
			url:site_url+'CmsComplaint/get_exams/',
			data: sdata,
			type:'POST',
			success: function(data) {
				if( data != '' ) {
					$("#examname").html(data);
				}
			}
		});
	});
	$("#cms_memberno").on("focusout", function() {
		var memno = $(this).val();
		if( memno != '' ) {
			var sdata = { memno : memno }
			$.ajax({
				url:site_url+'CmsComplaint/validate_memregno/',
				data: sdata,
				type:'POST',
				success: function(data) {
					var obj = jQuery.parseJSON( data );
					$("#cmsfrm-error").html("");
					$.each( obj, function( key, value ) {
						if( key == 'erroflg' ) {
							if( value == 1 ) {
								$(".cms-error-memno").show();
								$("#cms_memberno").val('');
								/* Remove set values of member and hide extra information */
								if( !$("#remember_memno").prop('checked') ) {
									$(".extra-info-cms").hide();	
								}
								$("#name").val("").removeAttr("required");
								$("#dateofbirth").val("").removeAttr("required");
								$("#employer_name").val("").removeAttr("required");
								//$("#email").val("").removeAttr("required");
								$("#email").val("");
								//$("#mobileno").val("").removeAttr("required");
								$("#mobileno").val("");
								return false;
							}
						} else if( key == 'errorprofileflg' ) {
							$("#cmsfrm-error").html(obj['errormsg']);
							//return false;
						}else {
							$(".extra-info-cms").show();
							$(".cms-error-memno").hide();
							if( key != 'dateofbirth' ) {
								$("#"+key).val(value);
								if( key != 'employer_name' ) {
									$("#"+key).attr('readonly', 'readonly');
								}
							} else {
								$("#"+key).val(value);
								var birtharr = value.split('-');
								$(".day").val(birtharr[2]).attr('disabled', 'true');
								$(".month").val(birtharr[1]).attr('disabled', 'true');
								$(".year").val(birtharr[0]).attr('disabled', 'true');
							}	
						}
						 
					});
				}
			});	
		}
	});
	if( $("#cms_memberno").length > 0 && $("#cms_memberno").val() != '' ) {
		$("#cms_memberno").trigger('focusout');	
	}
	$( "#queryfile" ).change(function() {
		var flag = 1;
		var queryfile_image = document.getElementById('queryfile');
		var queryfile_im = queryfile_image.value;
		
		// code updated by Bhagwan Sahane, on 24-05-2017
		var filesize1 = this.files[0].size/1024<10;
		var filesize2 = this.files[0].size/1024>90;
		
		var ext3 = queryfile_im.substring(queryfile_im.lastIndexOf('.')+1);
		if(queryfile_image.value !="" && ext3!='jpg' && ext3!='JPG' && ext3!='JPEG' && ext3!='jpeg' && ext3!='png' && ext3!='PNG' && ext3!='gif' && ext3!='GIF' && ext3!='PDF' && ext3!='pdf' && ext3!='doc' && ext3!='DOC' && ext3!='docx' && ext3!='DOCX' && ext3!='txt' && ext3!='TXT' )
		{
			$('#error_queryfile').show();
			$('#error_queryfile').fadeIn(300);	
			document.getElementById('error_queryfile').innerHTML="Upload image, pdf or doc files only";
			setTimeout(function(){
			$('#error_queryfile').css('color','#B94A48');
			 document.getElementById("queryfile").value = "";
			 $('#hiddenqueryfile').val('');
			},30);
			flag = 0;
			$(".queryfile_text").hide();
		}
		else if(filesize1 || filesize2)	// code updated by Bhagwan Sahane, on 24-05-2017
		{
			$('#error_queryfile').show();
			$('#error_queryfile').fadeIn(300);	
			document.getElementById('error_queryfile').innerHTML = "File size should be minimum 10KB and maximum 90KB.";
			setTimeout(function(){
			$('#error_queryfile').css('color','#B94A48');
			 document.getElementById("queryfile").value = "";
			 $('#hiddenqueryfile').val('');
			},30);
			flag = 0;
			$(".queryfile_text").hide();
		}
		// eof code updated by Bhagwan Sahane, on 24-05-2017
		
		if(flag=='1') {
			$('#error_queryfile').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
	
			//if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
				$('#hiddenqueryfile').val(this.result);
				}
			//}
			return true;
		}
		else {
			 return false;
		}
	});
	$(".publication").on("click", function(){
			 var member_no = $(this).attr("data-attr");
				var datastring='member_no='+member_no;
				$.ajax({
				url:site_url+'Publication/publicationCount/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				}
			});
		});
});


function checksate(statecode)
{
	if(statecode!='')
	{
		if(statecode=='ASS' || statecode=='JAM' || statecode=='MEG')
		{
			//document.getElementById('mendatory_state').style.display = "none";
			//document.getElementById('non_mendatory_state').style.display = "block";
			//$("#aadhar_card").removeAttr("required");
		}
		else
		{
			//document.getElementById('mendatory_state').style.display = "block";
			//document.getElementById('mendatory_state').innerHTML = "*";
			//document.getElementById('non_mendatory_state').style.display = "none";
			//$("#aadhar_card").attr("required","true");
		}
	}
}