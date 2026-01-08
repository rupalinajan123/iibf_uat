// JavaScript Document
$(document).ready(function() {
	
		window.Parsley.addValidator('checkpin', function (value, requirement) {
			var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
				url:site_url+'register/checkpin/',
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
		}, 32)
		.addMessage('en', 'checkpin', 'Please enter Valid Pincode.');
		
		window.Parsley.addValidator('emailcheck', function (value, requirement) {
			var response = false;
			var datastring='email='+value;
			$.ajax({
				url:site_url+'register/emailduplication/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				 if(data=="exists")
				{
					response = false;
				}
				else
				{
					response = true;
				}
				}
			});
			return response;
		}, 31)
		.addMessage('en', 'emailcheck', 'The email already exists.');
		
		
		window.Parsley.addValidator('editemailcheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='email='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'admin/Report/editemailduplication/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) {
				 if(data=="exists")
				{
					response = false;
				}
				else
				{
					response = true;
				}
				}
			});
			return response;
		}, 31)
		.addMessage('en', 'editemailcheck', 'The email already exists.');
		
		 $(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
            if(event.which == '67')
			{
				alert('Key combination CTRL + C has been disabled.');
			}
			if(event.which == '86')
			{
				alert('Key combination CTRL + V has been disabled.');
			}
			event.preventDefault();
         }
    });
	
	$("body").on("contextmenu",function(e){
        return false;
    });
	
	
	$('#selCenterName').on('change',function(){
		var centeeval=$(this).val();
		alert(centeeval);
		
		$('#txtCenterCode').val(centeeval)
		});
		
});
	//for member user checkform
 	function checkform()
 	{
	$('#captchaid').html('');
	var code=$('#code').val();
  	var flag=$('#usersAddForm').parsley().validate();
  	if(code!='' && flag)
	{
		$.ajax({
				url: site_url+'register/ajax_check_captcha',
				type: 'post',
				data:'code='+code,
				success: function(result) {
				if(result=='true')
				{
					preview();
				}
				else
				{
					$('#captchaid').html('Enter valid captcha code.');
				}
			
				//alert(result);
				//window.open(site_url+'register/preview', '_blank')
				//	return false
				}/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			});
	}
			
	
 	}
	//for member user preview
	function preview()
	{
			$.ajax({
				url: site_url+'register/setsession',
				type: 'post',
				data:$("form").serialize(),
				success: function(result) {
				//alert(result);
				window.open(site_url+'register/preview', '_blank')
				//	return false
				}/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			});
		
	}
	
	
	
	//for non-member user checkform
 	function non_mem_checkform()
 	{
	$('#non_mem_captchaid').html('');
	var code=$('#code').val();
  	var flag=$('#nonmemAddForm').parsley().validate();
  	if(code!='' && flag)
	{
		$.ajax({
				url: site_url+'nonreg/ajax_check_captcha',
				type: 'post',
				data:'code='+code,
				success: function(result) {
				if(result=='true')
				{
					non_mem_preview();
				}
				else
				{
					$('#non_mem_captchaid').html('Enter valid captcha code.');
				}
			
				//alert(result);
				//window.open(site_url+'register/preview', '_blank')
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
			$.ajax({
				url: site_url+'nonreg/setsession',
				type: 'post',
				data:$("form").serialize(),
				success: function(result) {
				//alert(result);
				window.open(site_url+'nonreg/preview', '_blank')
				//	return false
				}/*,
				error: function(e) {
					alert('Error occured: ' + JSON.stringify(e));
				}*/
			});
		
	}
	
	/*function readURL(input1,id) {
		var imgurl='';
		 var myarray = new Array();
		var input = document.getElementById(input1);
		var file = $("#"+input1).val(); 
				if (typeof (FileReader) != "undefined") {
					var reader = new FileReader();
					reader.onload = function (e) {
					$('#'+id).val(e.target.result);
					//imgurl=e.target.result;
					//return this.result
					}
				//reader.readAsDataURL(input.files[0]);
				reader.readAsDataURL(input.files[0]);
				} else {
					return false;
				}
			
			}*/
	
	$(function() {
		
		///////////////////// scanphoto validation //////////////////////
$( "#scannedphoto" ).change(function() {
	var filesize1=this.files[0].size/1024<8;
	var filesize2=this.files[0].size/1024>20;
	
var flag = 1;
 var file, img;
$('#p_photograph').hide();
var photograph_image=document.getElementById('scannedphoto');
//fileUpload[appKey]['photo'] = photograph_image;
var photograph_im=photograph_image.value;
var ext1=photograph_im.substring(photograph_im.lastIndexOf('.')+1);
if(photograph_image.value!=""&&  ext1!='jpg' && ext1!='JPG')
{
		$('#error_photo').show();
		$('#error_photo').fadeIn(3000);	
		document.getElementById('error_photo').innerHTML="Upload JPG or jpg file only.";
		setTimeout(function(){
		$('#error_photo').css('color','#B94A48');
		document.getElementById("scannedphoto").value = "";
		//$('#error_bussiness_image').fadeOut('slow');
		},30);
		flag = 0;
		$(".photo_text").hide();
	}
else if(filesize1 || filesize2)
{
	$('#error_photo_size').show();
	$('#error_photo_size').fadeIn(3000);	
	document.getElementById('error_photo_size').innerHTML="File size should be minimum 8KB and maximum 20KB.";
	setTimeout(function(){
	$('#error_photo_size').css('color','#B94A48');
	//$('#error_bussiness_image').fadeOut('slow');
	document.getElementById("scannedphoto").value = "";
	},30);
	flag = 0;
	$(".photo_text").hide();
	}
 if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
		//   var file_size = this.files[0].size;  
		   if(this.width!=100 || this.height!=120) 
		   {
			   $('#error_photo_size').fadeIn('slow');
				document.getElementById('error_photo_size').innerHTML='Upload valid image of size 100(Width) * 120(Height).';
				setTimeout(function(){
				$('#error_photo_size').css('color','#B94A48');
				//$('#error_bussiness_image').fadeOut('slow');
				document.getElementById("scannedphoto").value = "";
				},30);
				//$('#err_banner_image').fadeOut(2000);
			flag = 0;
			$(".photo_text").hide();
		   }
		
        };
       img.src = URL.createObjectURL(file);
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
    	return true;
	}
	else
	{
		return  false;
	}
	


});


///////////////////// scan Signature validation //////////////////////
$( "#scannedsignaturephoto" ).change(function() {
	var filesize1=this.files[0].size/1024<8;
	var filesize2=this.files[0].size/1024>20;
	var flag = 1;
	//$('#p_signature').hide();
	
	var signature_image=document.getElementById('scannedsignaturephoto');
	var signature_im=signature_image.value;
	var ext2=signature_im.substring(signature_im.lastIndexOf('.')+1);
	
	if(signature_image.value!=""&&  ext2!='jpg' && ext2!='JPG')
	{
		$('#error_signature').show();
		$('#error_signature').fadeIn(3000);	
		document.getElementById('error_signature').innerHTML="Upload JPG or jpg file only.";
		setTimeout(function(){
		$('#error_signature').css('color','#B94A48');
		document.getElementById("scannedsignaturephoto").value = "";
		//document.getElementById("uploadedSignature").value = "";
		//$('#error_bussiness_image').fadeOut('slow');
		},30);
		flag = 0;
		$(".signature_text").hide();
	}
	
	else if(filesize1 || filesize2)
     {
        $('#error_signature_size').show();
			$('#error_signature_size').fadeIn(3000);	
			document.getElementById('error_signature_size').innerHTML="File size should be minimum 8KB and maximum 20KB.";
			setTimeout(function(){
			$('#error_signature_size').css('color','#B94A48');
				document.getElementById("scannedsignaturephoto").value = "";
				//document.getElementById("uploadedSignature").value = "";
				
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".signature_text").hide();
	 }
	
	else if ((file = this.files[0])) {
	    img = new Image();
        img.onload = function () {
		//   var file_size = this.files[0].size;  
		  
		   if(this.width!=140 || this.height!=60) 
		   {
			   $('#error_signature').fadeIn('slow');
				document.getElementById('error_signature_size').innerHTML='Upload valid image of size 140(Width) * 60(Height).';
				setTimeout(function(){
				$('#error_signature_size').css('color','#B94A48');
				//$('#error_bussiness_image').fadeOut('slow');
				document.getElementById("scannedsignaturephoto").value = "";
				},30);
				//$('#err_banner_image').fadeOut(2000);
			flag = 0;
			$(".photo_text").hide();
		   }
        };
        img.src = URL.createObjectURL(file);
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
    	 return true; 
	}
	else
	 {
		return false;
	 }

});

///////////////////// ID Proof validation //////////////////////
$( "#idproofphoto" ).change(function() {
	var filesize1=this.files[0].size/1024<8;
	var filesize2=this.files[0].size/1024>25;
	var flag = 1;
	//$("#p_dob_proof").hide();
	
	var dob_proof_image=document.getElementById('idproofphoto');
	var dob_proof_im=dob_proof_image.value;
	var ext3=dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);
	
	if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG')
	{
		$('#error_dob').show();
		$('#error_dob').fadeIn(300);	
		document.getElementById('error_dob').innerHTML="Upload JPG or jpg file only.";
		setTimeout(function(){
		$('#error_dob').css('color','#B94A48');
		 document.getElementById("idproofphoto").value = "";
		//$('#error_bussiness_image').fadeOut('slow');
		},30);
		flag = 0;
	   	$(".dob_proof_text").hide();
	}
	
	else  if(filesize1 || filesize2)
     {
		$('#error_dob_size').show();
		$('#error_dob_size').fadeIn(300);	
		document.getElementById('error_dob_size').innerHTML="File size should be minimum 8KB and maximum 25KB..";
		setTimeout(function(){
		$('#error_dob_size').css('color','#B94A48');
		document.getElementById("idproofphoto").value = "";
		//$('#error_bussiness_image').fadeOut('slow');
		},30);
		flag = 0;
		$(".dob_proof_text").hide();
	}
	
	else if ((file = this.files[0])) 
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
				},30);
				//$('#err_banner_image').fadeOut(2000);
			flag = 0;
			$(".dob_proof_text").hide();
		   }
        };
        img.src = URL.createObjectURL(file);
    }
	
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
			}
        }
    
	
		return true;
	}
	else
	{
		 return false;
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
	
});