// JavaScript Document
$(document).ready(function() {
	
	
	$('#namesub').on('change',function(){
		var val=$('#namesub').val();
		$('.cls_gender').each(function(){
		$(this).removeAttr('checked');
		});
		if(val=='Mr.')
		{
			 $('#mgender').prop("checked", true);
			//$('#male').attr('checked', true);
		}
		else if(val=='Mrs.')
		{
			$('#fgender').prop("checked", true);
			//$('#female').attr('checked', true);
		}
		else if(val=='Ms.')
		{
			$('#fgender').prop("checked", true);
			//$('#female).attr('checked', true);
		}

	});
	
	var checkval=0;
	var noncheckval=0;
	
	window.Parsley.addValidator('vischeckpin', function (value, requirement) {
		var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
			url:site_url+'vision/checkpin/',
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
	.addMessage('en', 'vischeckpin', 'Please enter Valid Pincode here.');
		
	
	window.Parsley.addValidator('visemailcheck', function (value, requirement) {
		var response = false;
		var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
		var datastring='email='+value;
			$.ajax({
			url:site_url+'vision/emailduplication/',
			data: datastring,
			type:'POST',
			dataType:'json',
			async: false,
			success: function(data) {
				if(data.ans!="exists"){
					response = true;
				}else{
					alert(data.str);
					response = false;
				}
			}
		});
			return response;
	}, 32)
	.addMessage('en', 'visemailcheck', 'The email already exists.');
		
	
	window.Parsley.addValidator('vismobilecheck', function (value, requirement) {
		var response = false;
		var msg='';
			var datastring='mobile='+value;
			$.ajax({
			url:site_url+'vision/mobileduplication/',
			data: datastring,
			type:'POST',
			dataType:'json',
			async: false,
			success: function(data) {
				if(data.ans!="exists"){
					response = true;
				}else{
					alert(data.str);
					response = false;
				}
			}
		});
			return response;
	}, 33)
	.addMessage('en', 'vismobilecheck', 'The mobile number already exists.');

});
	
function vischeckform(){ 
	$('#error_id').html(''); 
	$('#success_id').html(''); 
	$('#error_id').removeClass("alert alert-danger alert-dismissible");
	$('#success_id').removeClass("alert alert-danger alert-dismissible");
	$('#tiitle_error').html(''); 
	$('#captchaid').html('');
	var code=$('#code').val();
	var form_flag=$('#visionForm').parsley().validate();
	if(code != '' && form_flag){ 
		$.ajax({
			url: site_url+'vision/ajax_check_captcha',
			type: 'post',
			data:'code='+code+'&random='+Math.random(),
			success: function(result) {
				if(result=='true'){
					$('#confirm').modal('show');
				}else{
					$('#captchaid').html('Enter valid captcha code.');
				}
			}
		});
	}
}
	
	