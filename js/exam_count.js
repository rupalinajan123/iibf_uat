// JavaScript Document
	var site_url = 'https://'+window.location.hostname+'/'; 
	
	$(document).ready(function() {
	var checkval=0;
	var noncheckval=0;
       //###---------------------------Member validation------------------------####
	   // check valid pin for member user	
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
		}, 31)
		.addMessage('en', 'checkpin', 'Please enter Valid Pincode.');
		
		
		 // check valid pin for member user	
		window.Parsley.addValidator('permanant_checkpin', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#state_pr').val()+'&pincode='+value;
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
		}, 31)
		.addMessage('en', 'permanant_checkpin', 'Please enter Valid Pincode.');
		
		//check pincode for edit pprofile
		window.Parsley.addValidator('editcheckpin', function (value, requirement) {
			var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
				url:site_url+'Home/checkpin/',
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
		}, 36)
		.addMessage('en', 'editcheckpin', 'Please enter Valid Pincode.');
		
		// check email duplication for member user
		window.Parsley.addValidator('emailcheck', function (value, requirement) {
			var response = false;
			var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
			var datastring='email='+value;
		
			if(filter.test(value))
			{
				$.ajax({
				url:site_url+'Register/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					checkval=1;
					alert(data.output);
					response = false;
				}
				else
				{
					checkval=0;
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
				var datastring='mobile='+value;
				$.ajax({
				url:site_url+'Register/mobileduplication/',
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
		.addMessage('en', 'mobilecheck', 'The mobile number already exists.');
		
			//check email duplication on edit for member
			window.Parsley.addValidator('editemailcheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='email='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'Home/editemailduplication/',
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
		}, 34)
		.addMessage('en', 'editemailcheck', 'The email already exists.');
		
		//check mobile duplication on edit for member
		window.Parsley.addValidator('editmobilecheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
				var datastring='mobile='+value+'&regid='+regid;
				$.ajax({
				url:site_url+'Home/editmobile/',
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
			}, 35)
			.addMessage('en', 'editmobilecheck', 'The mobile number already exists.');
		
	//###---------------------------End of Member validation------------------------####
		
		
	//###---------------------------Non-Member validation------------------------####
		// check valid pin for member user	
		window.Parsley.addValidator('nonmemcheckpin', function (value, requirement) {
			var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
				url:site_url+'nonreg/checkpin/',
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
			
		}, 36)
		.addMessage('en', 'nonmemcheckpin', 'Please enter Valid Pincode.');
		
		// check email duplication for non member user
		window.Parsley.addValidator('nonmememailcheck', function (value, requirement) {
			var response = false;
			var datastring='email='+value;
			$.ajax({
				url:site_url+'nonreg/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					noncheckval=1;
					alert(data.output);
					response = false;
				}
				else
				{
					noncheckval=0;
					response = true;
				}
				}
			});
			return response;
		}, 37)
		.addMessage('en', 'nonmememailcheck', 'The email already exists.');
		
		
		// Spe Exam NM check email duplication for non member user
		window.Parsley.addValidator('splnonmememailcheck', function (value, requirement) {
			var response = false;
			var datastring='email='+value;
			$.ajax({
				url:site_url+'SplexamNM/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					noncheckval=1;
					alert(data.output);
					response = false;
				}
				else
				{
					noncheckval=0;
					response = true;
				}
				}
			});
			return response;
		}, 37)
		.addMessage('en', 'splnonmememailcheck', 'The email already exists.');
				
				// Special exam NM external apply check mobile duplication for Non_member user
		window.Parsley.addValidator('splnonmobilecheck', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='mobile='+value;
				$.ajax({
				url:site_url+'SplexamNM/mobileduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(noncheckval==0)
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
			
		}, 38)
		.addMessage('en', 'splnonmobilecheck', 'The mobile number already exists.');
		
		// check mobile duplication for Non_member user
		window.Parsley.addValidator('nonmobilecheck', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='mobile='+value;
				$.ajax({
				url:site_url+'Nonreg/mobileduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				 if(data.ans=="exists")
				{
					if(noncheckval==0)
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
			
		}, 38)
		.addMessage('en', 'nonmobilecheck', 'The mobile number already exists.');
		
		//check mobile duplication on edit for member
		window.Parsley.addValidator('nmeditmobilecheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='mobile='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'NonMember/editmobile/',
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
		}, 39)
		.addMessage('en', 'nmeditmobilecheck', 'The mobile number already exists.');
		
		
		//check email duplication on edit for member
		window.Parsley.addValidator('nmeditemailcheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='email='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'NonMember/editemailduplication/',
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
		}, 40)
		.addMessage('en', 'nmeditemailcheck', 'The email already exists.');
		
		
		
		
	//###---------------------------Member  During direct exam apply validation------------------------####
	//check mobile duplication on edit for member
		window.Parsley.addValidator('editmobilecheckexamapply', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='mobile='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'Applyexam/editmobile/',
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
		}, 35)
		.addMessage('en', 'editmobilecheckexamapply', 'The mobile number already exists.');
		
		
		//check email duplication on edit for member
		window.Parsley.addValidator('editemailcheckexamapply', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='email='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'Applyexam/editemailduplication/',
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
		}, 34)
		.addMessage('en', 'editemailcheckexamapply', 'The email already exists.');
		
	// check valid pin for member user	
		window.Parsley.addValidator('editcheckpinexamapply', function (value, requirement) {
			var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
				url:site_url+'Applyexam/checkpin/',
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
			
		}, 36)
		.addMessage('en', 'editcheckpinexamapply', 'Please enter Valid Pincode.');
		
	
	 //###---------------------------DBF validation------------------------####
	   // check valid pin for dbf user	
		window.Parsley.addValidator('dbfcheckpin', function (value, requirement) {
			var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
				url:site_url+'Dbfuser/checkpin/',
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
		.addMessage('en', 'dbfcheckpin', 'Please enter Valid Pincode.');
		
		//check pincode for edit pprofile
		window.Parsley.addValidator('dbfeditcheckpin', function (value, requirement) {
			var response = false;
			var datastring='statecode='+$('#state').val()+'&pincode='+value;
			$.ajax({
				url:site_url+'Dbf/checkpin/',
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
		}, 36)
		.addMessage('en', 'dbfeditcheckpin', 'Please enter Valid Pincode.');
		
		// check email duplication for member user
		window.Parsley.addValidator('dbfemailcheck', function (value, requirement) {
			var response = false;
			var datastring='email='+value;
			$.ajax({
				url:site_url+'Dbfuser/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					checkval=1;
					alert(data.output);
					response = false;
				}
				else
				{
					checkval=0;
					response = true;
				}
				}
			});
			return response;
		}, 32)
		.addMessage('en', 'dbfemailcheck', 'The email already exists.');
		
		// check mobile duplication for member user
		window.Parsley.addValidator('dbfmobilecheck', function (value, requirement) {
			var response = false;
			var msg='';
			var datastring='mobile='+value;
			$.ajax({
				url:site_url+'Dbfuser/mobileduplication/',
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
		.addMessage('en', 'dbfmobilecheck', 'The mobile number already exists.');
		
		//check email duplication on edit for member
		window.Parsley.addValidator('dbfeditemailcheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='email='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'Dbf/editemailduplication/',
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
		}, 34)
		.addMessage('en', 'dbfeditemailcheck', 'The email already exists.');
		
		
		//check mobile duplication on edit for member
		window.Parsley.addValidator('dbfeditmobilecheck', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var datastring='mobile='+value+'&regid='+regid;
			$.ajax({
				url:site_url+'Dbf/editmobile/',
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
		}, 35)
		.addMessage('en', 'dbfeditmobilecheck', 'The mobile number already exists.');
		
	//###---------------------------End of DBF------------------------####
	
	//###--------------------------CPD Validation-----------------------###
		// check email duplication for member user
		window.Parsley.addValidator('cpdemailcheck', function (value, requirement) {
			var response = false;
			var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
			var datastring='email='+value;
		
			if(filter.test(value))
			{
				$.ajax({
				url:site_url+'Cpd/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
				if(data.ans=="exists")
				{
					checkval=1;
					alert(data.output);
					response = false;
				}
				else
				{
					checkval=0;
					response = true;
				}
				}
			});
				return response;
			}
		}, 32)
		
		.addMessage('en', 'cpdemailcheck', 'The email already exists.');
		
		// check mobile duplication for member user
		window.Parsley.addValidator('cpdmobilecheck', function (value, requirement) {
			var response = false;
			var msg='';
				var datastring='contact_no='+value;
				$.ajax({
				url:site_url+'Cpd/mobileduplication/',
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
		.addMessage('en', 'cpdmobilecheck','The mobile number already exists.');
		
		// check valid pin for member user	
		window.Parsley.addValidator('cpdcheckpin', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'Cpd/checkpin/',
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
		.addMessage('en', 'cpdcheckpin', 'Please enter Valid Pincode.');
	//###--------------------------End of CPD validaton-----------------###
	
	//###--------------------------Admin Edit Validation----------------------------####
	
	//check pincode on edit for member
	window.Parsley.addValidator('admincheckpin', function (value, requirement) {
			var response = false;
				var datastring='statecode='+$('#state').val()+'&pincode='+value;
				$.ajax({
				url:site_url+'admin/Report/checkpin/',
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
		.addMessage('en', 'admincheckpin', 'Please enter Valid Pincode.');
		
	//check email duplication on edit for member
			window.Parsley.addValidator('editemailcheckadmin', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
			var regtype = $("#regtype").val();
			var datastring='email='+value+'&regid='+regid+'&regtype='+regtype;
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
		}, 34)
		.addMessage('en', 'editemailcheckadmin', 'The email already exists.');
		
		//check mobile duplication on edit for member
		window.Parsley.addValidator('editmobilecheckadmin', function (value, requirement) {
			var response = false;
			var regid = $("#regid").val();
				var regtype = $("#regtype").val(); 
				var datastring='mobile='+value+'&regid='+regid+'&regtype='+regtype;
				$.ajax({
				url:site_url+'admin/Report/editmobile/',
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
			}, 35)
			.addMessage('en', 'editmobilecheckadmin', 'The mobile number already exists.');
	
	//###--------------------------Admin Edit Validation----------------------------####
	
		
		
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
		alert('You are selected '+$('#selSubName option:selected').text()+' as a elective subject.');
		//document.getElementById('selSubcode').value = $('#selSubName option:selected').val();
		//document.getElementById('selSubName1').value = $('#selSubName option:selected').text();
		
		// get selected elective subject code
		var sel1 = document.getElementById("selSubName");
		var subCode = sel1.options[sel1.selectedIndex].value;
		//alert(subCode);
		document.getElementById('selSubcode').value = subCode;
		// get selected elective subject name
		var sel2 = document.getElementById("selSubName");
		var subName = sel2.options[sel2.selectedIndex].text;
		//alert(subName);
		document.getElementById('selSubName1').value = subName;
		$('#selSubName').attr("disabled", true);
		//$('#selSubName').attr("readonly", true);
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
		
	$("#doj1").change(function(){
		var sel_doj = $("#doj1").val();
		if(sel_doj!='')
		{
			var doj_arr = sel_doj.split('-');
			if(doj_arr.length == 3)
			{
				$("#doj_error").html('');
				CompareToday(doj_arr[2],doj_arr[1],doj_arr[0]);	
				//checkDoj();
			}
			else
			{
				$("#doj_error").html('Select valid date');
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
	
	
	$( "#splexamdate" ).change(function() 
	{
		$("#selCenterName option").each(function(event){
		   $(this).attr("disabled", false); // Element(s) are now enabled.
		});
 	  var examination_date=$( "#splexamdate" ).val();
	  if(examination_date!='')
	  {
		  $.ajax({
						url: site_url+'SplexamM/checkcenter/',
						type: 'post',
						data:'examination_date='+examination_date,
						success: function(result) {
						$.each($.parseJSON(result), function(k, v) {
							if(v >=54)
							{
								$("#selCenterName option").each(function() {
								if($(this).val()==k)
								{
										$('#selCenterName option[value="'+k+'"]').attr("disabled", true);
								}
							});
						}	
					});
				}
			});
		  }
	})
	
	//for register user
	$( "#splexamdateNM" ).change(function() 
	{
		$("#selCenterName option").each(function(event){
		   $(this).attr("disabled", false); // Element(s) are now enabled.
		});
 	  var examination_date=$( "#splexamdateNM" ).val();
	  if(examination_date!='')
	  {
		  $.ajax({
						url: site_url+'SplexamNM/checkcenter/',
						type: 'post',
						data:'examination_date='+examination_date+'&excd='+$('#excd').val(),
						success: function(result) {
						$.each($.parseJSON(result), function(k, v) {
							if(v >=54)
							{
								$("#selCenterName option").each(function() {
								if($(this).val()==k)
								{
										$('#selCenterName option[value="'+k+'"]').attr("disabled", true);
								}
							});
						}	
					});
				}
			});
	  	}
	});
	
	//for logged in users
	$( "#splexamdateNMapply" ).change(function() 
	{
		$("#selCenterName option").each(function(event){
		   $(this).attr("disabled", false); // Element(s) are now enabled.
		});
 	  var examination_date=$( "#splexamdateNMapply" ).val();
	  if(examination_date!='')
	  {
		  $.ajax({
						url: site_url+'SpecialExamNm/checkcenter/',
						type: 'post',
						data:'examination_date='+examination_date+'&excd='+$('#excd').val(),
						success: function(result) {
						$.each($.parseJSON(result), function(k, v) {
							if(v >=54)
							{
								$("#selCenterName option").each(function() {
								if($(this).val()==k)
								{
										$('#selCenterName option[value="'+k+'"]').attr("disabled", true);
								}
							});
						}	
					});
				}
			});
	  	}
	});
	
	//for member with captcha user
	$( "#splexamdateM" ).change(function() 
	{
		$("#selCenterName option").each(function(event){
		   $(this).attr("disabled", false); // Element(s) are now enabled.
		});
 	  var examination_date=$( "#splexamdateM" ).val();
	  if(examination_date!='')
	  {
		  $.ajax({
						url: site_url+'ApplySplexamM/checkcenter/',
						type: 'post',
						data:'examination_date='+examination_date,
						success: function(result) {
						$.each($.parseJSON(result), function(k, v) {
							if(v >=54)
							{
								$("#selCenterName option").each(function() {
								if($(this).val()==k)
								{
										$('#selCenterName option[value="'+k+'"]').attr("disabled", true);
								}
							});
						}	
					});
				}
			});
		  }
	})
	
	
});

	$(function() {
	///////////////////// scanphoto validation //////////////////////
	$( "#scannedphoto" ).change(function() {
		//var filesize1=this.files[0].size/1024<8;
		var filesize2=this.files[0].size/1024>20;
		
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
			document.getElementById('error_photo').innerHTML="Upload JPG or jpg file only.";
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
		document.getElementById('error_photo_size').innerHTML="File size should be maximum 20KB.";
		setTimeout(function(){
		$('#error_photo_size').css('color','#B94A48');
		//$('#error_bussiness_image').fadeOut('slow');
		document.getElementById("scannedphoto").value = "";
		$('#hiddenphoto').val('');
		},30);
		flag = 0;
		$(".photo_text").hide();
		}
	 
	/* if ((file = this.files[0])) {
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
					$('#hiddenphoto').val('');
					},30);
					//$('#err_banner_image').fadeOut(2000);
				flag = 0;
				$(".photo_text").hide();
			   }
			
			};
		   img.src = URL.createObjectURL(file);
	  }*/
	 
		
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
		var filesize2=this.files[0].size/1024>20;
		var flag = 1;
		//$('#p_signature').hide();
		
		var signature_image=document.getElementById('scannedsignaturephoto');
		var signature_im=signature_image.value;
		var ext2=signature_im.substring(signature_im.lastIndexOf('.')+1);
		
		if(signature_image.value!=""&&  ext2!='jpg' && ext2!='JPG' && ext2!='jpeg' && ext2!='JPEG')
		{
			$('#error_signature').show();
			$('#error_signature').fadeIn(3000);	
			document.getElementById('error_signature').innerHTML="Upload JPG or jpg file only.";
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
				document.getElementById('error_signature_size').innerHTML="File size should be maximum 20KB.";
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
			$("#dob_error").html('Please select Date Of Birth');
			flag = false;	
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
				url: site_url+'Register/ajax_check_captcha',
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
				$('#confirm').modal('show');
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
	
	//for register member user preview
	function preview()
	{
		var formData = new FormData( $("#usersAddForm")[0] );
		
		$.ajax({
				url: site_url+'register/setsession',
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
					window.open(site_url+'register/preview', '_self')
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
						//window.open(site_url+'register/preview', '_blank')
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
					 (eduqual1==specify_q || eduqual2==specify_q || eduqual3==specify_q) && idproof == $('#idproof_hidd').val() 
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
	
	//member register profile
	function Register_profile_preview()
	{
		window.open(site_url+'Register/printUser', '_blank');
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
			
			var date_max = new Date(max_year, '05', '01');
			var date_min = new Date(min_year, '05', '01');
			
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
			var date_min = new Date(min_year, '05', '01');
			
			
			if( date_max > dob_date || date_min < dob_date )
			{
				$("#dob_error").html('Your Age should be between 18 and 60');
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
	
function getexam(cExam)
{

		var examcode=document.getElementById('txtexamCode').value = cExam
		if(examcode!='')
		{
			var datastring='examcode='+examcode;
			var htm_date = '';
			var htm_center = '';
				var htm_sub = '';
						$.ajax({
							type:'POST',
								url: site_url+'admin/ExamVenueCount/getCenter/',
								data: datastring,
								dataType: 'json',
        						 success: function(data) //we're calling the response json array 'cities'
								{
									  htm_center+= '<option value = "">--SELECT--</option>';
									$.each( data.record, function( key, value ) {
										 htm_center+= '<option value = "'+value.ccode+'">'+value.cname+'</option>';
									});
							
			
									$("#center_code").html(htm_center);
									
								
									//show the drop down
								
							
									
								
								} //end success
         }); //end AJAX
    } else {
        $('#center_code').empty();
        $('#center_code, #center_code_label').hide();
	
		
    }//end if
} //end change

function getvenue(ccode)
{
	if(ccode!='')
	{
			var datastring='centercode='+ccode;
			var htm = '';
		
						$.ajax({
							type:'POST',
								url: site_url+'admin/ExamVenueCount/getcenter_venue/',
								data: datastring,
								dataType: 'json',
        						 success: function(data) //we're calling the response json array 'cities'
								{
									 htm+= '<option value = "">--SELECT--</option>';
									$.each( data.venue, function( key, value ) {
										 htm+= '<option value = "'+value.vcode+'">'+value.vname+'</option>';
									});
							
									$("#venue").html(htm);
								
								
								} //end success
         }); //end AJAX
    } else {
		  $('#venue').empty();
        $('#venue, #venue_label').hide();

		    }//end if
} //end change



function getdate(vcode)
{
	if(vcode!='')
		{
			var datastring='vcode='+vcode;
			var htm = '';
		
						$.ajax({
							type:'POST',
								url: site_url+'admin/ExamVenueCount/getDate/',
								data: datastring,
								dataType: 'json',
        						 success: function(data) //we're calling the response json array 'cities'
								{
									 htm+= '<option value = "">--SELECT--</option>';
									
									$.each( data.examDate, function( key, value ) {
										 htm+= '<option value = "'+value.date+'">'+value.date+'</option>';
									});
									
								
									$("#date").html(htm);
								
								
								} //end success
         }); //end AJAX
    } else {
			 htm+= '<option value = "">ALL</option>';
       		$("#date").html(htm);
 
		    }//end if
} //end change
					
function setcenter(Ccode)
{
	if(Ccode!='')
		{
			var datastring='Ccode='+Ccode;
			var htm = '';
		
						$.ajax({
							type:'POST',
								url: site_url+'admin/ExamVenueCount/set_center_code/',
								data: datastring,
								dataType: 'json',
								 success: function(data) //we're calling the response json array 'cities'
								{
									 htm+= '<option value = "">--SELECT--</option>';
									
									$.each( data.examDate, function( key, value ) {
										 htm+= '<option value = "'+value.date+'">'+value.date+'</option>';
									});
									
								
									$("#date").html(htm);
								
								
								} //end success
         }); //end AJAX
    } else {
			 htm+= '<option value = "">ALL</option>';
       		$("#date").html(htm);
 
		    }//end if
  
} //end change

					
function setdate(date)
{


	if(date!='')
		{
			var datastring='date='+date;
			var htm = '';
		
						$.ajax({
							type:'POST',
								url: site_url+'admin/ExamVenueCount/set_date',
								data: datastring,
								dataType: 'json',
        						
         }); //end AJAX
    } 
} //end change
					
						

	
