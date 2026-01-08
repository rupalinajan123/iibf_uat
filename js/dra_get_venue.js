function valCentre(cCode)
{
	var examCode = document.getElementById('examcode').value;
	$(".loading").show();
	if(cCode!='')
	{
		var datastring_exam='centerCode='+cCode+'&examCode='+examCode;
		//alert(datastring_exam); 
		$.ajax({
				url:site_url+'dra_venue/getVenue/',
				data: datastring_exam,
				type:'POST',
				async: false,
				dataType: 'json',
				success: function(data) {
				//$.parseJSON(data);
				 if(data)
				{
					//alert(data.venue_option);
					var venue_elements=document.getElementsByClassName('venue_cls');
					for (var i=0; i<venue_elements.length; i++) {
							venue_elements[i].innerHTML = data.venue_option;
					}
					
					var date_elements=document.getElementsByClassName('date_cls');
					for (var i=0; i<date_elements.length; i++) {
							date_elements[i].innerHTML = data.date_option;
					}
					
					var time_elements=document.getElementsByClassName('time_cls');
					for (var i=0; i<time_elements.length; i++) {
							time_elements[i].innerHTML = data.time_option;
					}
					}
				}
		});		
	}
	$(".loading").hide();
}
	
function venue(venue_code,date_id,time_id,subject_code,seat_capacity_id)
{
	var selCenterCode= document.getElementById('exam_center').value;
	var examcode= document.getElementById('examcode').value;
	document.getElementById(seat_capacity_id).innerHTML = '-';
	$(".loading").show();
	var datastring='examcode='+examcode+'&subject_code='+subject_code+'&venue_code='+venue_code;
	$.ajax({
			url:site_url+'dra_venue/getDate/',
			data: datastring,
			type:'POST',
			async: false,
			dataType: 'json',
			success: function(data) {
				if(data){
					document.getElementById(date_id).innerHTML = data.date_option;
					document.getElementById(time_id).innerHTML = data.time_option;
	
				}
			}
	});		
	$(".loading").hide();
}
function date(date_code,venue_id,time_id)
{
	var selCenterCode= document.getElementById('exam_center').value;
	var venue_code= document.getElementById(venue_id).value;
	$(".loading").show();
	var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code+'&date_code='+date_code;
	$.ajax({
			url:site_url+'dra_venue/getTime/',
			data: datastring,
			type:'POST',
			async: false,
			dataType: 'json',
			success: function(data) {
				if(data){
					document.getElementById(time_id).innerHTML = data.time_option;
				}
			}
	});		
	$(".loading").hide();
}
function time(time,venue_id,date_id,seat_capcity_id)
{
	var selCenterCode= document.getElementById('exam_center').value;
	var venue_code= document.getElementById(venue_id).value;
	var date_id= document.getElementById(date_id).value;
	$(".loading").show();
	
	var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code+'&date_code='+date_id+'&time='+time;
	$.ajax({
			url:site_url+'dra_venue/getCapacity/',
			data: datastring,
			type:'POST',
			async: false,
			dataType: 'json',
			success: function(data) {
			if(data){
				//alert(seat_capcity_id);
				document.getElementById(seat_capcity_id).innerHTML = data.capacity;
			}
		}
	});		
	$(".loading").hide();
}
