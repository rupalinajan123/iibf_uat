// JavaScript Document
	$(document).ready(function() {
	var checkval=0;
	var noncheckval=0;
	
    //###---------------------------Start Renewal Member validation------------------------####
    // Check Valid Pin For Renewal Member User	
	window.Parsley.addValidator('rnwcheckpin', function (value, requirement) {
		var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
			url:site_url+'renewal/checkpin/',
			data: datastring,
			type:'POST',
			async: false,
			success: function(data) {
				if(data=='true'){
					response = true;
				}else{
				response = false;
				}
			}
		});
			return response;
	}, 31)
	.addMessage('en', 'rnwcheckpin', 'Please enter Valid Pincode.');
		
	// Check Valid Permanan Address Pin for Renewal Member User	
	window.Parsley.addValidator('rnwpermanant_checkpin', function (value, requirement) {
		var response = false;
			var datastring='statecode='+$('#state_pr').val()+'&pincode='+value;
			$.ajax({
			url:site_url+'renewal/checkpin/',
			data: datastring,
			type:'POST',
			async: false,
			success: function(data) {
				if(data=='true'){
					response = true;
				}else{
					response = false;
				}
			}
		});
			return response;
	}, 31)
	.addMessage('en', 'rnwpermanant_checkpin', 'Please enter Valid Pincode.');
		
	// Check Email Duplication For Renewal Member User
	window.Parsley.addValidator('rnwemailcheck', function (value, requirement) {
		var response = false;
		var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
		var datastring='email='+value;
		if(filter.test(value))
		{
			$.ajax({
			url:site_url+'renewal/emailduplication/',
			data: datastring,
			type:'POST',
			dataType:'json',
			async: false,
			success: function(data) {
				if(data.ans=="exists"){
					checkval=1;
					alert(data.output);
					response = false;
				}else{
					checkval=0;
					response = true;
				}
			}
		});
			return response;
		}
	}, 32)
	.addMessage('en', 'rnwemailcheck', 'The email already exists.');
		
	// Check Mobile Duplication For Renewal Member User
	window.Parsley.addValidator('rnwmobilecheck', function (value, requirement) {
		var response = false;
		var msg='';
			var datastring='mobile='+value;
			$.ajax({
			url:site_url+'renewal/mobileduplication/',
			data: datastring,
			type:'POST',
			dataType:'json',
			async: false,
			success: function(data) {
				if(data.ans=="exists"){
					if(checkval==0){
						alert(data.output);
					}
					response = false;
				}else{
					response = true;
				}
			}
		});
			return response;
	}, 33)
	.addMessage('en', 'rnwmobilecheck', 'The mobile number already exists.');
	
	
	// Check Adhar Card Number Duplication For Renewal Member User
	window.Parsley.addValidator('rnwadharcheck', function (value, requirement) {
		var response = false;
		var msg='';
			var datastring='aadhar_card='+value;
			$.ajax({
			url:site_url+'renewal/adharNoDuplication/',
			data: datastring,
			type:'POST',
			dataType:'json',
			async: false,
			success: function(data) {
				if(data.ans=="exists"){
					if(checkval==0){
						alert(data.output);
					}
					response = false;
				}else{
					response = true;
				}
			}
		});
			return response;
	}, 33)
	.addMessage('en', 'rnwadharcheck', 'The adhar card number number already exists.');
	
	//###---------------------------End of Renewal Member validation------------------------####
});
	//###---------------------------Start of Renewal Member validation------------------------####
	//For Renewal Member User CheckForm
 	function rnwcheckform(){
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
		if(dob==''){
			$("#dob_error").html('Please select Date Of Birth');
			flag = false;	
		}
		if(doj==''){
			$("#doj_error").html('Please select Date Of Joining');
			flag = false;
		}
		var form_flag=$('#usersAddForm').parsley().validate();
		if(code!='' && flag && form_flag){
			$.ajax({
				url: site_url+'renewal/ajax_check_captcha',
				type: 'post',
			    data:'code='+code+'&random='+Math.random(),
				success: function(result) {
					if(result=='true'){
					/* doConfirm(function yes() {
						   preview();
					  }, function no() {
					  // do nothing
					});*/
					$('#confirm').modal('show');
					}else{
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
	
	/*// Renewal Member Profile
	function Renewal_profile_preview()
	{
		alert('Renewal_profile_preview');
		window.open(site_url+'Renewal/printUser', '_blank');
	}*/
//###---------------------------End of Renewal Member validation------------------------####
	