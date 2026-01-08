$(document).ready(function() {

	//alert("test");

	// mobile duplication validation for dra exam applicant

	//if( regno == '' || ( regno != '' && membertype == 'normal_member' ) ) {

		window.Parsley.addValidator('dramobilecheck', function (value, requirement) {

			var membertype = $("#membertype").val();	

			var regno = $("#reg_no").val();

			var response = true;

			if( regno == '' || ( regno != '' && membertype == 'normal_member' ) ) {

				var response = false;

				var msg='';

				var datastring= 'mobile='+value;

				$.ajax({

					url:site_url+'iibfdra/DraExam/mobileduplication/',

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

			} 

			return response;

		}, 33).addMessage('en', 'dramobilecheck', 'The mobile number already exists.');

		// check email duplication for member user

		window.Parsley.addValidator('draemailcheck', function (value, requirement) {

			var membertype = $("#membertype").val();	

			var regno = $("#reg_no").val();

			var response = true;

			if( regno == '' || ( regno != '' && membertype == 'normal_member' ) ) {

				var response = false;

				var datastring='email='+value;

				$.ajax({

					url:site_url+'iibfdra/DraExam/emailduplication/',

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

					}, error: function(jqXHR, textStatus, errorThrown) {

							console.log(textStatus, errorThrown);

					}

				});

			}

			return response;

		}, 32).addMessage('en', 'draemailcheck', 'The email already exists.');

	//}

$("#pincode").on("keyup", function() {

	return false;	

});

//pin code validation

window.Parsley.addValidator('dracheckpin', function (value, requirement) {

	var response = false;

	var datastring='statecode='+$('#ccstate').val()+'&pincode='+value;
	

	$.ajax({

		url:site_url+'iibfdra/DraExam/checkpin/',

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

.addMessage('en', 'dracheckpin', 'Please enter Valid Pincode.');

/* Minimum date validation */

window.Parsley.addValidator('mindate', function (value, requirement) {

	// is valid date?

	var timestamp = Date.parse(value),

	minTs = Date.parse(requirement);

	return isNaN(timestamp) ? false : timestamp < minTs;    

}, 32)

.addMessage('en', 'mindate', 'This date should be less than today');

/* Training period validation */

$('#dateofbirth').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}); 

$('#training_from').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){

	$('#training_to').datepicker('setStartDate', new Date($(this).val()));

}); 

$('#training_to').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){

	$('#training_from').datepicker('setEndDate', new Date($(this).val()));

});	

window.Parsley.addValidator('trainingtoval', function (value, requirement) {

	// is valid date?

	//alert(value+" "+requirement); return false;

	//alert(requirement);

	var arrips = requirement.split("*");

	var trglimit = 0;

	var examdt = '';

	if( arrips instanceof Array ) {

		trglimit = arrips[1];

		examdt = arrips[0];

	}

	//alert(trglimit+" "+examdt);

	if( trglimit != 0 && examdt != '' ) {

		var date = new Date(value);

		var newdate = new Date(date);

		newdate.setDate(newdate.getDate() + parseInt(trglimit));

		var dd = newdate.getDate();

		var mm = newdate.getMonth() + 1;

		var y = newdate.getFullYear();

		var datetillvalid = y + '-' + mm + '-' + dd;

		//alert(datetillvalid);

		var timestamp = Date.parse(datetillvalid),

		minTs = Date.parse(examdt);

		return isNaN(timestamp) ? false : minTs < timestamp; 

	} else {

		return false;	

	}

}, 32)

.addMessage('en', 'trainingtoval', '<b>Your training date is expired, kindly apply again with new application.</b>');

/* On page load: made fields enabled or disabled if get details is fired  */

if( $("#reg_no").length > 0 ) {

	var regno = $("#reg_no").val();

	if( regno != '' ) {	

		var sdata = {'regno':regno}

		$.ajax({

			type: "POST",  

			url: site_url+'iibfdra/DraExam/get_memdetails/',

			data: sdata, 

			success: function(data){

				/* Keep files required in case of re-attempt also - 23-01-2017 */  

			    $("input[type='file']").removeAttr("required");

				$(".required-spn").text("");

			//	alert('amol');

				/*$("input").removeAttr("required");

				$("select").removeAttr("required");

				$(".required-spn").text("");

				

				$("#exam_medium").attr("required", true);

				$("#exam_center").attr("required", true);

				

				$("#pincode").removeAttr("data-parsley-dracheckpin");*/

				

				/*var obj = jQuery.parseJSON( data );

				var memtype = obj['membertype'];

				$.each( obj, function( key, value ) {

					if( memtype == 'dra_member' ) {

						if( key == 'exam_mode' ) {

							$("#"+value).prop('checked', true);

						}

						if( key == 'edu_quali' ) {

							$("#"+value).prop('checked', true);

						}

					}

					if( key == 'gender' ) {

						$("#"+value).prop('checked', true);

					}

					if( key == 'idproof' ) {

						$(".idproof-wrap").find("input[value='"+value+"']").prop('checked', true);

					}

					if( key == 'dateofbirth' ) {

						var dob_arr = value.split('-');

						var dyear = dob_arr[0];

						var dmnth = dob_arr[1];

						var dday = dob_arr[2];

						

						$("#dateofbirth").val(value);

						

						$(".day").val(dday);

						$(".month").val(dmnth);

						$(".year").val(dyear);

						

						$(".day").attr('disabled', true);

						$(".month").attr('disabled', true);

						$(".year").attr('disabled', true);

					}

					if( $("#"+key).length > 0 ) {

						 $("#"+key).val(value);

					}

				});*/

				

				$(".day").attr('disabled', true);

				$(".month").attr('disabled', true);

				$(".year").attr('disabled', true);

				

				var memtype = $("#membertype").val();

				if( memtype == 'normal_member' ) {

					//$("input[name='exam_mode']").prop('checked', false);

					//$("input[name='edu_quali']").prop('checked', false);

				} else {

					//$("input[name='exam_mode']:not(:checked)").attr('disabled', true);

					

					$('#training_from').datepicker('remove').attr("readonly","readonly");

					$('#training_to').datepicker('remove').attr("readonly","readonly");

				}

				if( $("input[name='gender']:checked").length > 0) {

					$("input[name='gender']").removeAttr("disabled");

				}

				

				if( $("input[name='idproof']:checked").length > 0) {

					$("input[name='idproof']").removeAttr("disabled");

				}

				

				$("input[name='gender']:not(:checked)").attr('disabled', true);

				$("input[name='idproof']:not(:checked)").attr('disabled', true);

				

				// code to view existinf images, Added by Bhagwan Sahane, 25-01-2017 -

				var obj = jQuery.parseJSON( data );

				$.each( obj, function( key, value ) {

					if( key == 'idproofphoto' && value != '' ) {

						$("#exist_draidproofphoto").html('<img src="'+value+'" id="idproof_preview" height="100" width="100"/>');

					}

					else if( key == 'idproofphoto' && value == '' )

					{

						$("#exist_draidproofphoto").html('<span class="error">Your Identity Proof is not available, kindly apply again with new application.</span>');	

					}

					if( key == 'scannedphoto' && value != '' ) {

						$("#exist_drascannedphoto").html('<img src="'+value+'" id="scanphoto_preview" height="100" width="100"/>');

					}

					else if( key == 'scannedphoto' && value == '' )

					{

						$("#exist_drascannedphoto").html('<span class="error">Your Scanned Photograph is not available, kindly apply again with new application.</span>');	

					}

					if( key == 'scannedsignaturephoto' && value != '' ) {

						$("#exist_drascannedsignature").html('<img src="'+value+'" id="signature_preview" height="100" width="100"/>');

					}

					else if( key == 'scannedsignaturephoto' && value == '' )

					{

						$("#exist_drascannedsignature").html('<span class="error">Your Scanned Signature is not available, kindly apply again with new application.</span>');	

					}

					if( key == 'quali_certificate' && value != '' ) {

						$("#exist_qualicertificate").html('<img src="'+value+'" id="qualicertificate_preview" height="100" width="100"/>');

					}

					else if( key == 'quali_certificate' && value == '' )

					{

						$("#exist_qualicertificate").html('<span class="error">Your Qualification Certificate is not available, kindly apply again with new application.</span>');	

					}

					if( key == 'training_certificate' && value != '' ) {

						$("#exist_trainingcertificate").html('<img src="'+value+'" id="trcertificate_preview" height="100" width="100"/>');

					}

					else if( key == 'training_certificate' && value == '' )

					{

						$("#exist_trainingcertificate").html('<span class="error">Your Training Certificate is not available, kindly apply again with new application.</span>');	

					}

				});

				// eof code

				

				/*keep pincode, district, city, address editable - change made on 19-01-2017*/

				/*$("#pincode").attr("readonly","readonly");

				$("#district").attr("readonly","readonly");

				$("#city").attr("readonly","readonly");

				$("#addressline1").attr("readonly","readonly");

				$("#addressline2").attr("readonly","readonly");

				

				// keep it editable - 20-01-2017

				$("#sel_namesub").attr('disabled', true);

				$("#firstname").attr("readonly","readonly");

				$("#mobile").attr("readonly","readonly");*/

				

				$("#state").attr('disabled', true);

				

				$("#pincode").attr("readonly","readonly");

				$("#district").attr("readonly","readonly");

				$("#city").attr("readonly","readonly");

				$("#addressline1").attr("readonly","readonly");

				$("#addressline2").attr("readonly","readonly");

				

				$("#sel_namesub").attr('disabled', true);

				

				/* keep it editable - 20-01-2017 */

				$("#firstname").attr("readonly","readonly");

				$("#middlename").attr("readonly","readonly");

				$("#lastname").attr("readonly","readonly");

				

				$("#stdcode").attr("readonly","readonly");

				$("#phone").attr("readonly","readonly");

				

				$("#mobile").attr("readonly","readonly");

				$("#email").attr("readonly","readonly");

				

				$("#aadhar_no").attr("readonly","readonly"); // added by Bhagwan Sahane, on 06-05-2017

				

				$("#reg_no").attr("readonly","readonly");

				

				$("input[name='edu_quali']:not(:checked)").attr('disabled', true);

			},

			error: function(XMLHttpRequest, textStatus, errorThrown) { 

				alert("Status: " + textStatus); alert("Error: " + errorThrown); 

			}       

		});

	}

}

//added

$(".dra-get-memdetails").on("click", function(e) {

	e.preventDefault();

});

//commented as button click and focus out of "registration number" field are fired at the same time

/*$(".dra-get-memdetails").on("click", function(e) {

	e.preventDefault();

	var regno = $("#reg_no").val();	

	var attr = $("#reg_no").attr('readonly'); //check if get_details is already fired

	if( regno == '' ) {

		alert("Please enter registration number");

		return false;	

	} else if (typeof attr == typeof undefined && attr !== "false") {

		

		// validate reg no -

		var letterNumber = /^[0-9]+$/; 

		if(regno.match(letterNumber))   

		{  

			//return true;  

		}  

		else  

		{   

			alert("Please enter numeric registration number only");

			$("#reg_no").val('').focus();

			return false;   

		}

		// eof code

		

		var sdata = {'regno':regno}

		$.ajax({

			type: "POST",  

			url: site_url+'iibfdra/DraExam/get_memdetails/',

			data: sdata, 

			success: function(data){

				//alert("s");  

				//console.log(data);

				var obj = jQuery.parseJSON( data );

				var memtype = obj['membertype'];

				//console.log(obj);

				if( Object.keys(obj).length > 0 ) {

					var flg = 0;

					$.each( obj, function( key, value ) {

						//if( key == 'error' && value == 1 ) {

							//alert("Invalid registration number");

							//flg = 1;

							//return false;

						//}

						if( memtype == 'dra_member' ) {

							if( key == 'exam_mode' ) {

								$("#"+value).prop('checked', true);

							}

							if( key == 'edu_quali' ) {

								$("#"+value).prop('checked', true);

							}

						}

						if( key == 'gender' ) {

							$("#"+value).prop('checked', true);

						}

						if( key == 'idproof' ) {

							$(".idproof-wrap").find("input[value='"+value+"']").prop('checked', true);

						}

						if( $("#"+key).length > 0 ) {

							 $("#"+key).val(value);

						}

						

						if( key == 'dateofbirth' ) {

							var dob_arr = value.split('-');

							var dyear = dob_arr[0];

							var dmnth = dob_arr[1];

							var dday = dob_arr[2];

							

							$("#dateofbirth").val(value);

							

							$(".day").val(dday);

							$(".month").val(dmnth);

							$(".year").val(dyear);

							

							$(".day").attr('disabled', true);

							$(".month").attr('disabled', true);

							$(".year").attr('disabled', true);

						}

					});

					if( flg == 1 ) {

						return false;	

					}

					if( memtype == 'normal_member' ) {

						$("input[name='exam_mode']").prop('checked', false);

						$("input[name='edu_quali']").prop('checked', false);

					} else {

						//$("input[name='exam_mode']").attr("readonly","readonly");

						$("input[name='exam_mode']:not(:checked)").attr('disabled', true);

						$('#training_from').datepicker('remove').attr("readonly","readonly");

						$('#training_to').datepicker('remove').attr("readonly","readonly");

					}

					

					$("input[type='file']").removeAttr("required");

					$("#mobile").attr("readonly","readonly");

					if( $("input[name='gender']:checked").length > 0) {

						$("input[name='gender']").removeAttr("disabled");

						$("input[name='gender']:not(:checked)").attr('disabled', true);

					}

					$("#pincode").attr("readonly","readonly");

					//$("#dateofbirth").datepicker('remove').attr("readonly","readonly");

					$("#district").attr("readonly","readonly");

					$("#city").attr("readonly","readonly");

					$("#addressline1").attr("readonly","readonly");

					$("#addressline2").attr("readonly","readonly");

					//$("#sel_namesub").attr("readonly","readonly");

					$("#firstname").attr("readonly","readonly");

					$("#reg_no").attr("readonly","readonly");

					$(".required-spn").text("");

				} else {

					alert("Invalid registration number");

					$("#reg_no").val("");	

				}

			},

			error: function(XMLHttpRequest, textStatus, errorThrown) { 

				alert("Status: " + textStatus); alert("Error: " + errorThrown); 

			}       

		});

	}

});*/

/* Populate member details on focus out of registration number field */

$("#draExamAddFrm #reg_no").on("focusout", function() {

	var regno = $(this).val();
	var batch_id = $('#batchid').val();

	var attr = $(this).attr('readonly');

	//alert(batch_id);

	if( regno != '' && typeof attr == typeof undefined && attr !== "false" ) {

	// validate reg no -

		var letterNumber = /^[0-9]+$/; 

		if(regno.match(letterNumber))   

		{  

			//return true;  

		}  

		else  

		{   

			alert("Please enter numeric registration number only");

			$("#reg_no").val('').focus();

			return false;   

		}

		// eof code

		

		var sdata = {'regno':regno,'batch_id':batch_id}

		$.ajax({

			type: "POST",  

			url: site_url+'iibfdra/TrainingBatches/get_memdetails/',

			data: sdata, 

			success: function(data){

				//alert(data);  

				//console.log(data);

				

				/*$("input").removeAttr("required");

				$("select").removeAttr("required");

				$(".required-spn").text("");

				

				$("#exam_medium").attr("required", true);

				$("#exam_center").attr("required", true);

				

				$("#pincode").removeAttr("data-parsley-dracheckpin");*/

				//alert('swati');
				
            //   if(data == 2){
                   
            //       alert('Number you have enterd is not your agency member.please enter valid Number');
            //       $("#reg_no").val('').focus();
            //       return false;
                   
            //   }else if(data == 3){
                   
            //       alert('Number you have enterd is not member of this batch.please enter valid Number');
            //       $("#reg_no").val('').focus();
            //       return false;
                   
            //   }else if(data == 4){
            //       alert('You have already applied to exam');
            //       $("#reg_no").val('').focus();
            //       return false;
            //   }else if(data == 5){
            //       alert('You have already passed this exam');
            //       $("#reg_no").val('').focus();
            //       return false;
            //   }else if(data == 6){
            //       alert('You have already exist in exam menu.Please follow exam procedure');
            //       $("#reg_no").val('').focus();
            //       return false;
            //   }
               
				var obj = jQuery.parseJSON( data );

				var memtype = obj['membertype'];

				//console.log(obj);

				if( Object.keys(obj).length > 0 ) {

					var flg = 0;

					$.each( obj, function( key, value ) {
                     
                        	if( key == 'error_message') {

							alert(value);
                            $("#reg_no").val('').focus();
							flg = 1;
							return false;

						}

                        
						if( key == 'error' && value == 1 ) {

							alert("Invalid registration number");

							flg = 1;

							return false;

						}

					

							if( key == 'exam_mode' ) {

								$("#"+value).prop('checked', true);

							}

							if( key == 'edu_quali' ) {
							    

								$("#"+value).prop('checked', true);

							}

					
						if( key == 'gender' ) {

							$("#"+value).prop('checked', true);

						}

						if( key == 'idproof' ) {

							$(".idproof-wrap").find("input[value='"+value+"']").prop('checked', true);

						}
                        	if( key == 'state' ) {

						$("#ccstate").val(value);

						}
						
						if( key == 'city' ) {
						    $('#city').html('<option value="'+value+'">'+value+'</option>');

						//$("#city").val(value);

						}
						
						if( $("#"+key).length > 0 ) {

							 $("#"+key).val(value);

						}

						

						//alert("Key : " + key);

						//alert("Value : " + value);

						

					

						// code to view existinf images, Added by Bhagwan Sahane, 25-01-2017 -

						if( key == 'idproofphoto' && value != '' ) {

							$("#exist_draidproofphoto").html('<img src="'+value+'" id="idproof_preview" height="100" width="100"/>');

						}

						else if( key == 'idproofphoto' && value == '' )

						{

							$("#exist_draidproofphoto").html('<span class="error">Your Identity Proof is not available, kindly apply again with new application.</span>');	

						}

						if( key == 'scannedphoto' && value != '' ) {

							$("#exist_drascannedphoto").html('<img src="'+value+'" id="scanphoto_preview" height="100" width="100"/>');

						}

						else if( key == 'scannedphoto' && value == '' )

						{

							$("#exist_drascannedphoto").html('<span class="error">Your Scanned Photograph is not available, kindly apply again with new application.</span>');	

						}

						if( key == 'scannedsignaturephoto' && value != '' ) {

							$("#exist_drascannedsignature").html('<img src="'+value+'" id="signature_preview" height="100" width="100"/>');

						}

						else if( key == 'scannedsignaturephoto' && value == '' )

						{

							$("#exist_drascannedsignature").html('<span class="error">Your Scanned Signature is not available, kindly apply again with new application.</span>');	

						}

						if( key == 'quali_certificate' && value != '' ) {

							$("#exist_qualicertificate").html('<img src="'+value+'" id="qualicertificate_preview" height="100" width="100"/>');

						}

						else if( key == 'quali_certificate' && value == '' )

						{

							$("#exist_qualicertificate").html('<span class="error">Your Qualification Certificate is not available, kindly apply again with new application.</span>');	

						}

						if( key == 'training_certificate' && value != '' ) {

							$("#exist_trainingcertificate").html('<img src="'+value+'" id="trcertificate_preview" height="100" width="100"/>');

						}

						else if( key == 'training_certificate' && value == '' )

						{

							$("#exist_trainingcertificate").html('<span class="error">Your Training Certificate is not available, kindly apply again with new application.</span>');	

						}

						// eof code

						

						if( key == 'dateofbirth' ) {

							var dob_arr = value.split('-');

							var dyear = dob_arr[0];

							var dmnth = dob_arr[1];

							var dday = dob_arr[2];

							

							$("#dateofbirth").val(value);

							

							$(".day").val(dday);

							$(".month").val(dmnth);

							$(".year").val(dyear);

							

							/* not to keep it editable - 08-03-2017 */

							$(".day").attr('disabled', true);

							$(".month").attr('disabled', true);

							$(".year").attr('disabled', true);

						}

					});

					if( flg == 1 ) {

						return false;	

					}

					if( memtype == 'normal_member' ) {

						$("input[name='exam_mode']").prop('checked', false);

					//	$("input[name='edu_quali']").prop('checked', false);

					} else {

						//$("input[name='exam_mode']").attr("readonly","readonly");
						$("input[name='edu_quali']:not(:checked)").attr('disabled', true);

						$("input[name='idproof']:not(:checked)").attr('disabled', true);

			
                     /* Keep files required in case of re-attempt also - 23-01-2017 */

					$("input[type='file']").removeAttr("required");

					$(".required-spn").text("");

					

					if( $("input[name='gender']:checked").length > 0) {

						$("input[name='gender']").removeAttr("disabled");

					}

					

					$('#training_from').datepicker('remove').attr("readonly","readonly");

					$('#training_to').datepicker('remove').attr("readonly","readonly");

					

					$("input[name='gender']:not(:checked)").attr('disabled', true);

					$("input[name='exam_mode']:not(:checked)").attr('disabled', true);

					

					/*keep pincode, district, city, address editable - change made on 19-01-2017*/

					$("#ccstate").attr('disabled', 'true');

					$("#pincode").attr("readonly","readonly");

					$("#district").attr("readonly","readonly");

					//$("#city").attr("disabled","true");

					$("#addressline1").attr("readonly","readonly");

					$("#addressline2").attr("readonly","readonly");
					$("#addressline3").attr("readonly","readonly");

					$("#addressline4").attr("readonly","readonly");

					

					$("#sel_namesub").attr('disabled', true);

					

					/* keep it editable - 20-01-2017 */

					$("#firstname").attr("readonly","readonly");

					$("#middlename").attr("readonly","readonly");

					$("#lastname").attr("readonly","readonly");

					

					$("#stdcode").attr("readonly","readonly");

					$("#phone").attr("readonly","readonly");

					

					$("#mobile").attr("readonly","readonly");

					$("#email").attr("readonly","readonly");

					

					$("#aadhar_no").attr("readonly","readonly"); // added by Bhagwan Sahane, on 06-05-2017

					

					$("#reg_no").attr("readonly","readonly");
						

					}

				

					

				} else {

					alert("Invalid registration number");

					$("#reg_no").val("");	

				}

			},

			error: function(XMLHttpRequest, textStatus, errorThrown) { 

				alert("Status: " + textStatus); alert("Error: " + errorThrown); 

			}       

		});

		

	 }

});

	

	/* DRA ID Proof validation */

	$( "#draidproofphoto" ).change(function() {

		//var filesize1=this.files[0].size/1024<75;

		//var filesize2=this.files[0].size/1024>200;

		

		// updated by Bhagwan Sahane, on 28-04-2017

		var filesize1=this.files[0].size/1024<10;

		var filesize2=this.files[0].size/1024>25;

		

		var flag = 1;

		var dob_proof_image = document.getElementById('draidproofphoto');

		var dob_proof_im = dob_proof_image.value;

		var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG' && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_dob').show();

			$('#error_dob').fadeIn(300);	

			document.getElementById('error_dob').innerHTML="Upload JPG or jpg or jpeg or JPEG file only.";

			setTimeout(function(){

			$('#error_dob').css('color','#B94A48');

			 document.getElementById("draidproofphoto").value = "";

			 $('#hiddenidproofphoto').val('');

			},30);

			flag = 0;

			$(".dob_proof_text").hide();

		}

		

		else  if(filesize1 || filesize2)

		 {

			$('#error_dob_size').show();

			$('#error_dob_size').fadeIn(300);	

			document.getElementById('error_dob_size').innerHTML="File size should be minimum 10KB and maximum 25KB.";

			setTimeout(function(){

				$('#error_dob_size').css('color','#B94A48');

				document.getElementById("draidproofphoto").value = "";

				$('#hiddenidproofphoto').val('');

			},30);

			flag = 0;

			$(".dob_proof_text").hide();

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

			//show preview of uploaded image

			readURL(this,'idproof_preview');

			return true;

		}

		else

		{

			 return false;

		 }

	});

	

	/* DRA qualification certificate validation */

	$( "#qualicertificate" ).change(function() {

		//var filesize1=this.files[0].size/1024<75;

		//var filesize2=this.files[0].size/1024>200;

		

		// updated by Bhagwan Sahane, on 28-04-2017

		var filesize1=this.files[0].size/1024<50;

		var filesize2=this.files[0].size/1024>200;

		

		var flag = 1;

		var dob_proof_image = document.getElementById('qualicertificate');

		var dob_proof_im = dob_proof_image.value;

		var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG'  && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_qualicert').show();

			$('#error_qualicert').fadeIn(300);	

			document.getElementById('error_qualicert').innerHTML="Upload JPG or jpg or jpeg or JPEG file only.";

			setTimeout(function(){

			$('#error_qualicert').css('color','#B94A48');

			 document.getElementById("qualicertificate").value = "";

			 $('#hiddenqualicertificate').val('');

			},30);

			flag = 0;

			$(".qualicert_text").hide();

		}

		

		else  if(filesize1 || filesize2)

		 {

			$('#error_qualicert_size').show();

			$('#error_qualicert_size').fadeIn(300);	

			document.getElementById('error_qualicert_size').innerHTML="File size should be minimum 50KB and maximum 200KB.";

			setTimeout(function(){

				$('#error_qualicert_size').css('color','#B94A48');

				document.getElementById("qualicertificate").value = "";

				$('#hiddenqualicertificate').val('');

			},30);

			flag = 0;

			$(".qualicert_text").hide();

		}

			

		if(flag=='1')

		{

			$('#error_qualicert_size').html('');

			$('#error_qualicert').html('');

			var files = !!this.files ? this.files : [];

			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

	

			if (/^image/.test( files[0].type)){ // only image file

				var reader = new FileReader(); // instance of the FileReader

				reader.readAsDataURL(files[0]); // read the local file

				reader.onloadend = function(){ // set image data as background of div

				$('#hiddenqualicertificate').val(this.result);

				}

			}

			//show preview of uploaded image

			readURL(this,'qualicertificate_preview');

			return true;

		}

		else

		{

			 return false;

		 }

	});

	

	/* DRA training certificate validation */

	$( "#trainingcertificate" ).change(function() {

		//var filesize1=this.files[0].size/1024<75;

		//var filesize2=this.files[0].size/1024>200;

		

		// updated by Bhagwan Sahane, on 28-04-2017

		var filesize1=this.files[0].size/1024<50;

		var filesize2=this.files[0].size/1024>100;

		

		var flag = 1;

		var dob_proof_image = document.getElementById('trainingcertificate');

		var dob_proof_im = dob_proof_image.value;

		var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG'  && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_trainingcert').show();

			$('#error_trainingcert').fadeIn(300);	

			document.getElementById('error_trainingcert').innerHTML="Upload JPG or jpg or jpg or JPEG file only.";

			setTimeout(function(){

			$('#error_trainingcert').css('color','#B94A48');

			 document.getElementById("trainingcertificate").value = "";

			 $('#hiddentrainingcertificate').val('');

			},30);

			flag = 0;

			$(".trainingcert_text").hide();

		}

		

		else  if(filesize1 || filesize2)

		 {

			$('#error_trainingcert_size').show();

			$('#error_trainingcert_size').fadeIn(300);	

			document.getElementById('error_trainingcert_size').innerHTML="File size should be minimum 50KB and maximum 100KB.";

			setTimeout(function(){

				$('#error_trainingcert_size').css('color','#B94A48');

				document.getElementById("trainingcertificate").value = "";

				$('#hiddentrainingcertificate').val('');

			},30);

			flag = 0;

			$(".trainingcert_text").hide();

		}

			

		if(flag=='1')

		{

			$('#error_trainingcert_size').html('');

			$('#error_trainingcert').html('');

			var files = !!this.files ? this.files : [];

			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

	

			if (/^image/.test( files[0].type)){ // only image file

				var reader = new FileReader(); // instance of the FileReader

				reader.readAsDataURL(files[0]); // read the local file

				reader.onloadend = function(){ // set image data as background of div

				$('#hiddentrainingcertificate').val(this.result);

				}

			}

			//show preview of uploaded image

			readURL(this,'trcertificate_preview');

			return true;

		}

		else

		{

			 return false;

		 }

	});

	

	/* DRA candidate photo validation */

	$( "#drascannedphoto" ).change(function() {

		//var filesize1=this.files[0].size/1024<20;

		//var filesize2=this.files[0].size/1024>50;

		

		// updated by Bhagwan Sahane, on 28-04-2017

		var filesize1=this.files[0].size/1024<10;

		var filesize2=this.files[0].size/1024>20;

		

		var flag = 1;

		var dob_proof_image = document.getElementById('drascannedphoto');

		var dob_proof_im = dob_proof_image.value;

		var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG'  && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_photo').show();

			$('#error_photo').fadeIn(300);	

			document.getElementById('error_photo').innerHTML="Upload JPG or jpg or jpeg or JPEG file only.";

			setTimeout(function(){

			$('#error_photo').css('color','#B94A48');

			 document.getElementById("drascannedphoto").value = "";

			 $('#hiddenphoto').val('');

			},30);

			flag = 0;

			$(".photo_text").hide();

		}

		

		else  if(filesize1 || filesize2)

		 {

			$('#error_photo_size').show();

			$('#error_photo_size').fadeIn(300);	

			document.getElementById('error_photo_size').innerHTML="File size should be minimum 10KB and maximum 20KB.";

			setTimeout(function(){

				$('#error_photo_size').css('color','#B94A48');

				document.getElementById("drascannedphoto").value = "";

				$('#hiddenphoto').val('');

			},30);

			flag = 0;

			$(".photo_text").hide();

		}

			

		if(flag=='1')

		{

			$('#error_photo_size').html('');

			$('#error_photo').html('');

			var files = !!this.files ? this.files : [];

			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

	

			if (/^image/.test( files[0].type)){ // only image file

				var reader = new FileReader(); // instance of the FileReader

				reader.readAsDataURL(files[0]); // read the local file

				reader.onloadend = function(){ // set image data as background of div

				$('#hiddenphoto').val(this.result);

				}

			}

			//show preview of uploaded image

			readURL(this,'scanphoto_preview');

			return true;

		}

		else

		{

			 return false;

		 }

	});

	

	/* DRA candidate signature validation */

	$( "#drascannedsignature" ).change(function() {

		//var filesize1=this.files[0].size/1024<10;

		//var filesize2=this.files[0].size/1024>20;

		

		// updated by Bhagwan Sahane, on 28-04-2017

		var filesize1=this.files[0].size/1024<10;

		var filesize2=this.files[0].size/1024>20;

		

		var flag = 1;

		var dob_proof_image = document.getElementById('drascannedsignature');

		var dob_proof_im = dob_proof_image.value;

		var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG'  && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_signature').show();

			$('#error_signature').fadeIn(300);	

			document.getElementById('error_signature').innerHTML="Upload JPG or jpg or jpeg or JPEG file only.";

			setTimeout(function(){

			$('#error_signature').css('color','#B94A48');

			 document.getElementById("drascannedsignature").value = "";

			 $('#hiddenscansignature').val('');

			},30);

			flag = 0;

			$(".signature_text").hide();

		}

		

		else  if(filesize1 || filesize2)

		 {

			$('#error_signature_size').show();

			$('#error_signature_size').fadeIn(300);	

			document.getElementById('error_signature_size').innerHTML="File size should be minimum 10KB and maximum 20KB.";

			setTimeout(function(){

				$('#error_signature_size').css('color','#B94A48');

				document.getElementById("drascannedsignature").value = "";

				$('#hiddenscansignature').val('');

			},30);

			flag = 0;

			$(".signature_text").hide();

		}

			

		if(flag=='1')

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

			//show preview of uploaded image

			readURL(this,'signature_preview');

			return true;

		}

		else

		{

			 return false;

		 }

	});

	

	function readURL(input,div) {

        if (input.files && input.files[0]) {

            var reader = new FileReader();



            reader.onload = function (e) {

                $('#'+div).attr('src', e.target.result);

            }



            reader.readAsDataURL(input.files[0]);

        }

    }

	

	/* DRA UTR slip validation */

	$( "#utr_slip" ).change(function() {

		var filesize1=this.files[0].size/1024<50;

		var filesize2=this.files[0].size/1024>100;

		var flag = 1;

		var dob_proof_image = document.getElementById('utr_slip');

		var dob_proof_im = dob_proof_image.value;

		var ext3 = dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG'  && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_utrslip').show();

			$('#error_utrslip').fadeIn(300);	

			document.getElementById('error_utrslip').innerHTML="Upload JPG or jpg or jpegor JPEG file only.";

			setTimeout(function(){

			$('#error_utrslip').css('color','#B94A48');

			 document.getElementById("utr_slip").value = "";

			 $('#hiddenutrslip').val('');

			},30);

			flag = 0;

			$(".utrslip_text").hide();

		}

		

		else  if(filesize1 || filesize2)

		 {

			$('#error_utrslip_size').show();

			$('#error_utrslip_size').fadeIn(300);	

			document.getElementById('error_utrslip_size').innerHTML="File size should be minimum 50KB and maximum 100KB.";

			setTimeout(function(){

				$('#error_utrslip_size').css('color','#B94A48');

				document.getElementById("utr_slip").value = "";

				$('#hiddenutrslip').val('');

			},30);

			flag = 0;

			$(".utrslip_text").hide();

		}

			

		if(flag=='1')

		{

			$('#error_utrslip_size').html('');

			$('#error_utrslip').html('');

			var files = !!this.files ? this.files : [];

			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

	

			if (/^image/.test( files[0].type)){ // only image file

				var reader = new FileReader(); // instance of the FileReader

				reader.readAsDataURL(files[0]); // read the local file

				reader.onloadend = function(){ // set image data as background of div

				$('#hiddenutrslip').val(this.result);

				}

			}

			return true;

		}

		else

		{

			 return false;

		 }

	});

	

	/* DRA exam apply: populate center code on change of center name */

	$("#exam_center").on("change", function() {

		$("#center_code").val($(this).val());

		var datastring='center_code='+$(this).val();

		$.ajax({

			url:site_url+'iibfdra/DraExam/getexam_mode/',

			data: datastring,

			type:'POST',

			success: function(exammode) {

				$("input[name='exam_mode']").removeAttr("disabled");

				if( exammode != '' ) {

					if( exammode.trim() == 'ON' ) {

						$(".exam-mode-wrap #ON").prop('checked', true);

						$(".exam-mode-wrap #OF").removeAttr("checked").attr('disabled', true);

					} else { //OF

						$(".exam-mode-wrap #OF").prop('checked', true);

						$(".exam-mode-wrap #ON").removeAttr("checked").attr('disabled', true);

					}

				} 

			}

		});

	});

	

});



// function to validate dob -

function chkage(day,month,year)

{

	var flag = 0;

	if(day!='' && month!='' && year!='')

	{

		var dday  = day;

		var dmnth = month;

		var dyear = year;

		var dob_date = new Date(dyear, dmnth-1 , dday);

		

		//alert(dob_date);

		

		var today = new Date();



		if (dob_date > today) {

			//alert("Entered date is greater than today's date ");

			$("#dob_error").html('Date of birth must be less than today');

			//$("#dateofbirth").val("");

			flag = 0;

		}

		else {

			//alert("Entered date is less than today's date ");

			$("#dob_error").html('');

		}

	}

	else

	{

		$("#dob_error").html('Please select valid date');

		//$("#dateofbirth").val("");

		flag = 0;

	}

	if(flag==1)

		return true;

	else

		return false;

}



// function to validate dra exam form -

function dravalidateForm()

{

	var sel_dob = $("#dateofbirth").val();

	

	if(sel_dob == "")

	{

		//alert("Please select Date Of Birth");

		$("#dob_error").html('Please select Date Of Birth');		

	}

	else {

		var dob_arr = sel_dob.split('-');

		

		var dyear = dob_arr[0];

		var dmnth = dob_arr[1];

		var dday = dob_arr[2];

		

		var dob_date = new Date(dyear, dmnth-1 , dday);

		

		var today = new Date();

		

		if (dob_date > today) {

			//alert("Entered date is greater than today's date ");

			$("#dob_error").html('Date of birth must be less than today');

			//$("#dateofbirth").val("");

			flag = 0;

		}

		else {

			//alert("Entered date is less than today's date ");

			$("#dob_error").html('');

			return true;

		}

	}

	return false;

}