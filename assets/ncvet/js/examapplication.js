function valMedium(medium) {
	if(medium!='') {
		$('.exam_medium_error').text('');
	}
}
function valCentre(cCode)
	{
		 var subject_array=new Array();  
		
		var examCode = document.getElementById('examcode').value;
		
		var eprid = document.getElementById('eprid').value;
		var grp_code = document.getElementById('grp_code').value;
		
		var mtype= document.getElementById('mtype').value;
		
		var Eval = 'N'; 
		
	
		
		$(".loading").show();
		$('#btnPreviewSubmit').val('Please Wait..');

		if(cCode!='')
		{ 
			$('.exam_center_error').text('');
				var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+examCode+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
				$.ajax({
								url:site_url+'ncvet/candidate/applyexam/getFee/',
								data: datastring,
								type:'POST',
								async: false,
								success: function(data) {
								 if(data)
								{
									document.getElementById('exam_fee').value = data ;
								}
							}
						});
						
						
			
			}
			
		if(document.getElementById("optmode1")){
			document.getElementById("optmode1").style.display = "none";
		}
		if(document.getElementById("optmode2")){
			document.getElementById("optmode2").style.display = "none";
		}
		
		$(".loading").hide();
	}
	