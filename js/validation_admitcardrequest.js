// JavaScript Document

function bnqcheckform(){
	$('#error_id').html(''); 
	$('#success_id').html(''); 
	$('#error_id').removeClass("alert alert-danger alert-dismissible");
	$('#success_id').removeClass("alert alert-danger alert-dismissible");
	$('#tiitle_error').html(''); 
	var form_flag=$('#requestForm').parsley().validate();
	
	if(form_flag == true){
		$("#requestForm").submit(); // Submit the form
	}
}

	

	
	