$(document).ready(function() {

    var checkval=0;
	var noncheckval=0;
	     //##------------ check valid pin for main office address--------------------##
		window.Parsley.addValidator('checkpin_main_addr', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#main_state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'DraRegister/checkpin_main_addr/',
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
		.addMessage('en', 'checkpin_main_addr', 'Please enter Valid Pincode.');
		
		  //##------------ check valid pin for main office address--------------------##
		  
		  
		window.Parsley.addValidator('checkpincode', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'DraRegister/checkpin/',
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
		.addMessage('en', 'checkpincode', 'Please enter Valid Pincode.');
		
	    //##------------ Institute Telephone No validation -----------------------##
		window.Parsley.addValidator('checkinst_phone', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_phone='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_phoneduplication/',
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
		.addMessage('en', 'checkinst_phone', 'The institute phone number already exists.');
		
		//##------------ Institute Telephone mobile number validation -----------------------##
		window.Parsley.addValidator('checkinst_mobile_no', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_head_contact_no='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_head_mobile_noduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(checkval==0)
					{
						//alert(data.output);
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
		.addMessage('en', 'checkinst_mobile_no', 'The institute mobile number already exists.');


		//##------------ Institute Alternate mobile number validation -----------------------##
		window.Parsley.addValidator('checkinst_altrmobile_no', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_alter_contact_no='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_altr_mobile_noduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(checkval==0)
					{
						//alert(data.output);
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
		.addMessage('en', 'checkinst_altrmobile_no', 'The Mobile number is already exists.');
		
        //##------------ Institute FAX number validation -----------------------##
		window.Parsley.addValidator('checkinst_fax_no', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_fax_no='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_fax_noduplication/',
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
		.addMessage('en', 'checkinst_fax_no', 'The institute fax number already exists.');
		
		//##------------ Institute Email validation -----------------------##
		window.Parsley.addValidator('checkinst_head_email', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_head_email='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_head_emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(checkval==0)
					{
						//alert(data.output);
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
		.addMessage('en', 'checkinst_head_email', 'The Email ID already exists.');


		//##------------ Institute Alternate Contact Person Email validation -----------------------##
		window.Parsley.addValidator('checkinst_altr_email', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_altr_email='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_altr_emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(checkval==0)
					{
						//alert(data.output);
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
		.addMessage('en', 'checkinst_altr_email', 'The Email ID already exists.');

		
		   //##------------ Institute Telephone No validation -----------------------##
		window.Parsley.addValidator('check_cpmobile', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='contact_person_mobile='+value;   
				$.ajax({
				url:site_url+'DraRegister/cpmobile_duplication/',
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
		.addMessage('en','check_cpmobile','The Mobile number already exists.');
		
		//##------------ Institute Email validation -----------------------##
		window.Parsley.addValidator('checkinst_email', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='inst_email='+value;   
				$.ajax({
				url:site_url+'DraRegister/inst_emailduplication/',
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
		.addMessage('en', 'checkinst_email', 'The institute Email already exists.');
		
		//##------------ Institute Email validation -----------------------##
		window.Parsley.addValidator('check_cpemail', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='email_id='+value;   
				$.ajax({
				url:site_url+'DraRegister/cp_emailduplication/',
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
		.addMessage('en', 'check_cpemail', 'The Email ID already exists.');
		
		//##------------ check valid pincoe for institute register bulk--------------------##
		window.Parsley.addValidator('checkpin_inst_addr', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#ste_code').val()+'&pincode='+value;
			    $.ajax({
				url:site_url+'bulk/admin/InstitutionMaster/checkpin_inst_addr/',
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
		.addMessage('en', 'checkpin_inst_addr', 'Please enter Valid Pincode.');
		
		//##------------ Check institute code duplication --------------------##
		window.Parsley.addValidator('checkcode_inst', function (value, requirement) {
			var response = false;
				var datastring='institute_code='+value;
			    $.ajax({
				url:site_url+'bulk/admin/InstitutionMaster/checkcode_inst/',
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
		.addMessage('en', 'checkcode_inst', 'Institute code is already present.');
		
		$('.due_diligence').click(function(){
			var inst_type_val = $('#Regular').val();
			//var due_diligence_val = $('input[name=due_diligence]:checked', '#due_diligence').val();
			var due_diligence_val = $(this).attr("value");
			//alert(inst_type_val);
			//alert(due_diligence_val);
			if(inst_type_val == 'R' && due_diligence_val == 'No')
			{
				//$("#preview").hide();
				$("#btn_preview").show();
				$("#preview").attr("disabled", "disabled");
				alert("Please carry out Due Diligence and resubmit application");
			}
			else
			{
			    $("#preview").show();
				$("#btn_preview").hide();
				$('#preview').attr('disabled', false);
			}
		});
		
		

});




function dracheckform(){
	$('#error_id').html(''); 
	$('#success_id').html(''); 
	$('#error_id').removeClass("alert alert-danger alert-dismissible");
	$('#success_id').removeClass("alert alert-danger alert-dismissible");
	$('#tiitle_error').html(''); 
	$('#captchaid').html('');
	var code=$('#code').val();
	var form_flag=$('#frmDrA').parsley().validate();
	if(code != '' && form_flag){
		$.ajax({
			url: site_url+'DraRegister/ajax_check_captcha',
			type: 'post',
			data:'code='+code+'&random='+Math.random(),
			success: function(result) {
				if(result=='true'){
					//$('#confirm').modal('show');
					//$('#frmDrA').submit();
					$('#frmDrA').submit(); //
					// return true;
					
				}else{
					$('#captchaid').html('Enter valid captcha code.');
					// return false;
				}
			}
		});
	}
}