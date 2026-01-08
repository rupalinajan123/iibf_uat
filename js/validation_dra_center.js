function dracentercheckform(){
	$('#error_id').html(''); 
	$('#success_id').html(''); 
	$('#error_id').removeClass("alert alert-danger alert-dismissible");
	$('#success_id').removeClass("alert alert-danger alert-dismissible");
	
	//var pincode_chcek = check_pincode_call()
	/*if(pincode_chcek)
	{
		return true;
	}
	else
	{
		$('#pincode').val('');
		$('#pincode').addClass('err');
		return false;
	}*/
	
	window.Parsley.addValidator('check_center_pincode', function (value, requirement) {
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
		.addMessage('en', 'check_center_pincode', 'Please enter Valid Pincode.');
	
	var form_flag=$('#frmDrACenter').parsley().validate();

	if(form_flag)
	{
		return true;
	}
	else
	{
		return false;
	}
}


$(document).ready(function() {
//Temporary center	
$('#Temporary').click(function(){
$('#faculty_name1').attr("required","true");
$('#faculty_qualification1').attr("required","true");
$('#upload_file1').attr("required","true");
$(".divbox").show();
$('#file1').show();

//$('#upload_file2').attr("required","true");
});

///Regular center
$('#Regular').click(function(){
$('#faculty_name1').removeAttr("required");
$('#faculty_qualification1').removeAttr("required");
$('#upload_file1').removeAttr("required");
$(".divbox").hide(); 
$('#file1').hide();
//$('#upload_file2').removeAttr("required");
});

});