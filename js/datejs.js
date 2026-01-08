// JavaScript Document
function chksubmit()
{
	var minjoinyear = parseInt(document.reg_frm.seldobyear.value) + parseInt(18) ;
	if(document.reg_frm.seldobday.value =='')
	{
		alert("Please select your Birth Date");
		document.reg_frm.seldobday.focus();
		return false;
	}
	else if(document.reg_frm.seldobmon.value =='')
	{
		alert("Please select your Birth Month");
		document.reg_frm.seldobmon.focus();
		return false;
	}
	else if(document.reg_frm.seldobyear.value =='')
	{
		alert("Please select your Birth Year");
		document.reg_frm.seldobyear.focus();
		return false;
	}
	else if(document.reg_frm.seldobday.value!='' && document.reg_frm.seldobmon.value!= '' && document.reg_frm.seldobyear.value!='' && isDate('seldobday', 'seldobmon', 'seldobyear') == false  )
	{
		return false;				
	}
	else if(document.reg_frm.seldobday.value!='' && document.reg_frm.seldobmon.value!= '' && document.reg_frm.seldobyear.value!='' && chkage('seldobday', 'seldobmon', 'seldobyear') == false  )
	{
		alert("Your Age should be between 18 and 80");
		document.reg_frm.seldobyear.focus();
		return false;			
	}
	
	
	

	else if(document.reg_frm.seli_dojday.value =='')
	{
		alert("Please select Date of Joining");
		document.reg_frm.seli_dojday.focus();
		return false;
	}
	else if(document.reg_frm.seli_dojmon.value =='')
	{
		alert("Please select Month of Joining");
		document.reg_frm.seli_dojmon.focus();
		return false;
	}
	else if(document.reg_frm.seli_dojyear.value =='')
	{
		alert("Please select Year of Joining");
		document.reg_frm.seli_dojyear.focus();
		return false;
	}
	else if(document.reg_frm.seli_dojyear.value !='' && document.reg_frm.seli_dojyear.value < minjoinyear )
	{
		alert("Please select Proper Year of Joining");
		document.reg_frm.seli_dojyear.focus();
		return false;
	}
	else if(document.reg_frm.seli_dojyear.value!='' && document.reg_frm.seli_dojmon.value!= '' && document.reg_frm.seli_dojyear.value!='' && isDate('seli_dojday','seli_dojmon', 'seli_dojyear') == false  )
	{
		return false;				
	}
	else if(document.reg_frm.seli_dojyear.value!='' && document.reg_frm.seli_dojmon.value!= '' && document.reg_frm.seli_dojyear.value!='' && CompareToday() == false )
	{
		alert('Date of joining should not be greater than today!.');
		document.reg_frm.seli_dojyear.focus();
		return false;
	}
	


}