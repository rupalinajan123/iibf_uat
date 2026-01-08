$(document).ready(function() {
  var checkval=0;
	var noncheckval=0;
  //##------------ check valid pin for address--------------------##
		window.Parsley.addValidator('checkpin', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'JBIMS/checkpin/',
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
		
		//##------------ check valid pin for bank address--------------------##
		window.Parsley.addValidator('bank_checkpin', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#bank_state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'JBIMS/checkpin/',
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
		.addMessage('en', 'bank_checkpin', 'Please enter Valid Pincode.');
		
	   	
		});

function checkform(){
	var form_flag=$('#ampForm').parsley().validate();
	if(form_flag){
		return true;
	}else{
		return false;
	}
		
}


$( "#photograph" ).change(function() {
	
	var filesize2=parseInt(this.files[0].size/1024);
	
	var flag = 1;
	 var file, img;
	$('#p_photograph').hide();
	var photograph_image=document.getElementById('photograph');
	var photograph_im=photograph_image.value;
	var ext1=photograph_im.substring(photograph_im.lastIndexOf('.')+1);
	if(photograph_image.value!=""&&  ext1!='jpg' && ext1!='JPG' && ext1!='jpeg' && ext1!='JPEG')
	{
			$('#error_photo').show();
			$('#error_photo').fadeIn(3000);	
			document.getElementById('error_photo').innerHTML="Upload JPG or jpeg file only.";
			setTimeout(function(){
			$('#error_photo').css('color','#B94A48');
			document.getElementById("photograph").value = "";
			$('#hiddenphoto').val('');
			
			},30);
			flag = 0;
			$(".photo_text").hide();
			
		}
	else if(filesize2 < 20 || filesize2 > 50)
	{	
		$('#error_photo_size').show();
		$('#error_photo_size').fadeIn(3000);	
		document.getElementById('error_photo_size').innerHTML="File size should be between 20KB to 50KB.";
		setTimeout(function(){
		$('#error_photo_size').css('color','#B94A48');
		//$('#error_bussiness_image').fadeOut('slow');
		document.getElementById("photograph").value = "";
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
			 readURL(this,'image_upload_photograph_preview');
			return true;
		}
		else
		{
			return  false;
		}
		
	
	
	});
	
	$( "#idproof" ).change(function() {
	
	var filesize2=parseInt(this.files[0].size/1024);
	
	var flag = 1;
	 var file, img;
	$('#p_idproof').hide();
	var idproof_image=document.getElementById('idproof');
	var idproof_im=idproof_image.value;
	var ext1=idproof_im.substring(idproof_im.lastIndexOf('.')+1);
	if(idproof_image.value!=""&&  ext1!='jpg' && ext1!='JPG' && ext1!='jpeg' && ext1!='JPEG')
	{
			$('#error_idproof').show();
			$('#error_idproof').fadeIn(3000);	
			document.getElementById('error_idproof').innerHTML="Upload JPG or jpeg file only.";
			setTimeout(function(){
			$('#error_idproof').css('color','#B94A48');
			document.getElementById("idproof").value = "";
			$('#hiddenscanidproof').val('');
			
			},30);
			flag = 0;
			$(".idproof_text").hide();
			
		}
	else if(filesize2 < 20 || filesize2 > 50)
	{	
		$('#error_idproof_size').show();
		$('#error_idproof_size').fadeIn(3000);	
		document.getElementById('error_idproof_size').innerHTML="File size should be between 20KB to 50KB.";
		setTimeout(function(){
		$('#error_idproof_size').css('color','#B94A48');
		//$('#error_bussiness_image').fadeOut('slow');
		document.getElementById("idproof").value = "";
		$('#hiddenscanidproof').val('');
		},30);
		flag = 0;
		$(".idproof_text").hide();
		}
			
		if(flag==1)
		{
			$('#error_idproof').html('');
			$('#error_idproof_size').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
					$('#hiddenscanidproof').val(this.result);
				}
			}
			 readURL(this,'image_upload_idproof_preview');
			return true;
		}
		else
		{
			return  false;
		}
		
	
	
	});
	
$( "#signature" ).change(function() {
		var filesize2=this.files[0].size/1024;
		var flag = 1;
		//$('#p_signature').hide();
		
		var signature_image=document.getElementById('signature');
		var signature_im=signature_image.value;
		var ext2=signature_im.substring(signature_im.lastIndexOf('.')+1);
		
		if(signature_image.value!=""&&  ext2!='jpg' && ext2!='JPG' && ext2!='jpeg' && ext2!='JPEG')
		{
			$('#error_signature').show();
			$('#error_signature').fadeIn(3000);	
			document.getElementById('error_signature').innerHTML="Upload JPG or jpg file only.";
			setTimeout(function(){
			$('#error_signature').css('color','#B94A48');
			document.getElementById("signature").value = "";
			$('#hiddenscansignature').val('');
			//document.getElementById("uploadedSignature").value = "";
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".signature_text").hide();
		}
		
		else if(filesize2 < 10 || filesize2 > 20)
		 {
			$('#error_signature_size').show();
				$('#error_signature_size').fadeIn(3000);	
				document.getElementById('error_signature_size').innerHTML="File size should be between 10KB to 20KB.";
				setTimeout(function(){
				$('#error_signature_size').css('color','#B94A48');
					document.getElementById("signature").value = "";
					$('#hiddenscansignature').val('');
				},30);
				flag = 0;
				$(".signature_text").hide();
		 }
		
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
			readURL(this,'image_upload_signature_preview');
			 return true; 
		}
		else
		 {
			return false;
		 }
	
	});