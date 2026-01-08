
function refundCheckForm()
	{ 
		$('#error_id').html(''); 
		$('#success_id').html(''); 
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		$('#success_id').removeClass("alert alert-danger alert-dismissible");
		$('#tiitle_error').html(''); 
		var rflag = 1;
		var form_flag=$('#refundrequestForm').parsley().validate();
		//alert(form_flag);
		
		if(form_flag)
		{
			return true;
		}
	}

