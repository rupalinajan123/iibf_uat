
	//###---------------------------Start of Blended Member validation------------------------####
	//For Blended Member User CheckForm
 	
//###---------------------------End of Blended Member validation------------------------####
/* Rest All Drop Down And Get Traning Types */
function gettrainingtype(program_code)
{ 
	//console.log(program_code); return false;
	document.getElementById('capacitymsg').style.display = 'none';
		//$("#preview").prop('disabled', false);
		document.getElementById('preview').style.pointerEvents = 'auto';
	var regnumberHidden = $('#regnumberHidden').val();
	$("#TrainingTypeLoading").hide();
	$("#training_type").val('');
	$("#training_date_div").hide();
	$("#training_date").val('');
	$("#showVirtualCenter").hide();
	$("#showCenter").hide();
	$("#center").val('');
	$("#vmsgDiv").hide();
	$("#venue").text('');
	$("#showVenue").hide();
	$("#showFees").hide();
	$("#feesInput").val('');
	if(program_code != "")
	{ 
		$("#TrainingTypeLoading").show();
		//alert(site_url+"Examtraining/getTrainingType");
		$.ajax({
			type:"POST",
			url: site_url+"Examtraining/getTrainingType", 
			data:{program_code:program_code},
			success:function(data){
				//console.log(data); return false;
				if(data != "" && data != '1'){  
					$("#TrainingTypeLoading").hide();
					$("#showTraining_type").show();
					$("#textTraining_type").text('');
					$("#textTraining_type").append(data);
				}
				else if(data == '1')
				{
					window.location.assign(site_url+"Examtraining/member")
				}
			}
		},"json");
	}
	else{
		$("#showTraining_type").hide();
		//alert('Please select Course..!');
	}
}
/*function resetFields(program_code)
{
	document.getElementById('capacitymsg').style.display = 'none';
		$("#preview").prop('disabled', false);
	var regnumberHidden = $('#regnumberHidden').val();
	$("#TrainingTypeLoading").hide();
	$("#training_type").val('');
	$("#training_date_div").hide();
	$("#training_date").val('');
	$("#showVirtualCenter").hide();
	$("#showCenter").hide();
	$("#center").val('');
	$("#vmsgDiv").hide();
	$("#venue").text('');
	$("#showVenue").hide();
	$("#showFees").hide();
	$("#feesInput").val('');
	if(program_code != "")
	{
		$("#TrainingTypeLoading").show();
		$.ajax({
			type:"POST",
			url: site_url+"Examtraining/getTrainingType",
			data:{program_code:program_code},
			success:function(data){
				console.log(data);
				if(data != "" && data != '1'){  
					$("#TrainingTypeLoading").hide();
					$("#showTraining_type").show();
					$("#textTraining_type").text('');
					$("#textTraining_type").append(data);
				}
				else if(data == '1')
				{
					window.location.assign(site_url+"Examtraining/")
				}
			}
		},"json");
	}
	else{
		$("#showTraining_type").hide();
		//alert('Please select Course..!');
	}
}*/

/* Get Dates */
function getDates(training_type)
{ 

	//document.getElementById('capacitymsg').style.display = 'none';
		$("#preview").prop('disabled', false);
	
	var program_code = $("#program").val();
	$("#showVirtualCenter").hide();
	$("#DatesLoading").show(); 
	$.ajax({
		type:"POST",
		url: site_url+"Examtraining/getdates",
		data:{training_type:training_type, program_code:program_code},
		success:function(data){
			if(data != "" && data != '1')
			{  
				$("#showVirtualCenter").hide();
				$("#DatesLoading").show();
				if(training_type == 'VC'){
					$("#showVirtualCenter").show();
					$("#training_date_div").show();
					$("#dateDiv").text('');
				    $("#dateDiv").append(data);
					$("#center").val('');
					$("#venue").text('');
					$("#showVenue").hide();
					$("#showCenter").hide();
					$("#vmsgDiv").hide();
					$("#DatesLoading").hide();
				}
				else if(training_type == 'PC'){
					$("#showVirtualCenter").hide();
					$("#training_date_div").show();
					$("#dateDiv").text('');
				    $("#dateDiv").append(data);
					$("#DatesLoading").hide();
				}
			}
			else if(data == '1')
			{
				window.location.assign(site_url+"Examtraining/member")
			}
			else
			{
				$("#DatesLoading").hide();
				$("#showVirtualCenter").hide();
				$("#training_type").val('');
				$("#showFees").hide();
				$("#feesInput").val('');
				$("#showTraining_type").hide();
				$("#showCenter").hide();
				$("#vmsgDiv").hide();
				$("#program").val('');
				$("#center").val('');
				$("#venue").text('');
				$("#showVenue").hide();
				$("#training_date_div").hide();
				$("#showVirtualCenter").hide();
				$("#training_date").val('');
			}
		}	
	},"json");
}

/* Get Fees */
function getFees()
{
	document.getElementById('capacitymsg').style.display = 'none';
	//var regnumberHidden = $('#regnumberHidden').val();
	var program_code = $("#program").val();
	var batch_code = $("#batch_code").val();
	var training_type = $("#training_type").val();
	$("#FeeCenterLoading").show();
	$.ajax({
		type:"POST",
		url: site_url+"Examtraining/getFees",
		data:{training_type:training_type, program_code:program_code,batch_code:batch_code},
		success:function(data){
			if(data != "")
			{   $("#FeeCenterLoading").hide();
			    $("#fees").text('');
				$("#fees").append(data);
				$("#showFees").show();
				$("#center").val('');
				$("#venue").text('');
				$("#showVenue").hide();
				
				if(training_type == 'VC'){
					$("#FeeCenterLoading").hide();
					$("#showVirtualCenter").show();
					$("#vmsgDiv").hide();
				}
				else if(training_type == 'PC'){
					$("#FeeCenterLoading").hide();
					$("#showCenter").show();
				}
			}
			else{
				$("#FeeCenterLoading").hide();
				$("#showFees").hide();
				$("#feesInput").val('');
				$("#showTraining_type").hide();
				$("#showCenter").hide();
				$("#training_type").val('');
				$("#vmsgDiv").hide();
				$("#program").val('');
				$("#center").val('');
				$("#venue").text('');
				$("#showVenue").hide();
				$("#training_date_div").hide();
				$("#showVirtualCenter").hide();
				$("#training_date").val('');
			}
		}	
	},"json");
}	

/* Get Centers */
function getCenters()
{
	document.getElementById('capacitymsg').style.display = 'none';
		//$("#preview").prop('disabled', false);
		document.getElementById('preview').style.pointerEvents = 'auto';
	//var regnumberHidden = $('#regnumberHidden').val();
	var batch_code = $('#batch_code').val();
	var program_code = $('#program').val();
	var training_type = $('#training_type').val();
	$("#FeeCenterLoading").show();
	if(program_code != "" && batch_code != "")
	{	
		$.ajax({
		type:"POST",
		url: site_url+"Examtraining/getCenters",
		data:{ program_code:program_code,batch_code:batch_code},
		success:function(data){
			if(data != "")
			{
				if(data=='Capacity Full')
				{
					
					 document.getElementById('capacitymsg').style.display = 'block';
					 //$("#preview").prop('disabled', true);
					 
					 document.getElementById('preview').style.pointerEvents = 'none';
					 $("#centerDiv").text('');
					$("#centerDiv").append(data);
			     	//location.reload();
				}else{
				$("#vmsgDiv").hide();
				if(training_type == 'PC'){
					 //$("#preview").prop('disabled', false);
					 document.getElementById('preview').style.pointerEvents = 'auto';
					$("#FeeCenterLoading").hide();
					$("#showCenter").show();
					$("#centerDiv").text('');
					$("#centerDiv").append(data);
					$("#showVirtualCenter").hide();
				}
				else if(training_type == 'VC'){
					$("#FeeCenterLoading").hide();
					$("#showVirtualCenter").show();
				}
				$("#venue").text('');
				$("#showVenue").hide();
				}
			}
			else
			{
				if(data=='capacity full')
				{
					  document.getElementById('capacitymsg').style.display = 'block';
					  //$("#preview").prop('disabled', true);
					   document.getElementById('preview').style.pointerEvents = 'none';
					  	$("#centerDiv").text('');
				 $("#showCenter").hide();
					//location.reload();
				}else
				{
				//$("#preview").prop('disabled', false);
				document.getElementById('preview').style.pointerEvents = 'auto';
				$("#FeeCenterLoading").hide();
				$("#vmsgDiv").hide();
				$("#centerDiv").text('');
				$("#showCenter").hide();
				$("#fees").text('');
				$("#showFees").hide();
				$("#venue").text('');
				$("#showVenue").hide();
				}
			}
		}
	},"json");
	}
}
/* Get Venue Details */
function getVenue(center_code)
{ //alert(center_code);
	document.getElementById('capacitymsg').style.display = 'none';
		//$("#preview").prop('disabled', false);
		document.getElementById('preview').style.pointerEvents = 'auto';
		
	//var regnumberHidden = $('#regnumberHidden').val();
	var training_type = $('#training_type').val();
	var program_code = $("#program").val();
	var batch_code = $("#batch_code").val();
	$("#Venueloding").show();
	if(center_code != '' && batch_code != '')
	{	
			$.ajax({
			type:"POST",
			url: site_url+"Examtraining/getVenue",
			data:{program_code:program_code,center_code:center_code,training_type:training_type,batch_code:batch_code},
			success:function(data){
				if(data != "" && data != '1')
				{ 
					$("#Venueloding").hide();
					$("#vmsgDiv").hide();
					$("#showVenue").show();
					$("#venue").text('');
					$("#venue").append(data);
				}
				else if(data == '1')
				{
					$("#Venueloding").hide();
					window.location.assign(site_url+"Examtraining/member/")
				}
				else
				{ 
					$("#Venueloding").hide();
					$("#vmsgDiv").show();
					$("#vmsg").text('Venue is not available for above selected center.');
					$("#venue").text('');
					$("#showVenue").hide();
				}
			}	
		},"json");
	}
	else
	{
		$("#Venueloding").hide();
		$("#venue").text('');
		$("#showVenue").hide();
		$("#vmsgDiv").hide();
	}
}	
/* get Batch */
function get_batch()
{
	$('#batch_code').val($('#training_date').find(':selected').attr('data-id'));
}
