<style>
.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
}
#confirm .modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 420px;
	min-width: 400px;
}
.skin-blue .main-header .navbar {
	background-color: #fff;
}
body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
}
.container {
	position: relative;
}
.box-header.with-border {
	background-color: #7fd1ea;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	margin-bottom: 10px;
}
.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
	margin-bottom: 0 !important;
}
.box {
	border: none;
	box-shadow: none;
	border-radius: 0;
	margin-bottom: 0;
}
.nobg {
	background: none !important;
	border: none !important;
}
.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
	margin: 0;
}
.blue_bg {
	background-color: #e7f3ff;
}
.m_t_15 {
	margin-top: 15px;
}
.main-footer {
	padding-left: 160px;
	padding-right: 160px;
}
.content-header > h1 {
	font-size: 22px;
	font-weight: 600;
}
h4 {
	margin-top: 5px;
	margin-bottom: 10px !important;
	font-size: 14px;
	line-height: 18px;
	padding: 0 5px;
	font-weight: 600;
	text-align: justify;
}
.form-horizontal .control-label {
	padding-top: 4px;
}
.pad_top_2 {
	padding-top: 2px !important;
}
.pad_top_0 {
	padding-top: 0px !important;
}
div.form-group:nth-child(odd) {
	background-color: #dcf1fc;
	padding: 5px 0;
}
#confirmBox {
	display: none;
	background-color: #eee;
	border-radius: 5px;
	border: 1px solid #aaa;
	position: fixed;
	width: 300px;
	left: 50%;
	margin-left: -150px;
	padding: 6px 8px 8px;
	box-sizing: border-box;
	text-align: center;
	z-index: 1;
	box-shadow: 0 1px 3px #000;
}
#confirmBox .button {
	background-color: #ccc;
	display: inline-block;
	border-radius: 3px;
	border: 1px solid #aaa;
	padding: 2px;
	text-align: center;
	width: 80px;
	cursor: pointer;
}
#confirmBox .button:hover {
	background-color: #ddd;
}
#confirmBox .message {
	text-align: left;
	margin-bottom: 8px;
}
.form-group {
	margin-bottom: 10px;
}
.form-horizontal .form-group {
	margin-left: 0;
	margin-right: 0;
}
.form-control {
	border-color: #888;
}
.form-horizontal .control-label {
	font-weight: normal;
}
a.forget {
	color: #9d0000;
}
a.forget:hover {
	color: #9d0000;
	text-decoration: underline;
}
ol li {
	line-height: 18px;
}
.example {
	text-align: left !important;
	padding: 0 10px;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

  <div class="container">
    <section class="content-header">
           <h1 class="register"> 
       Application For Duplicate Certificate 
       </h1><br/>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
           <form class="form-horizontal" name="dupcert" id="dupcert" action="<?php echo base_url();?>DupCert/reg" method="post">
          <!-- Basic Details box Start-->
          <div class="box box-info"><center> 
                   <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Membership/registration no.<span style="color:#F00">*</span></label>
             <div class="col-sm-4">
                <input type="text" class="form-control " id="mem_no" data-parsley-type="number" data-parsley-maxlength="4" name="mem_no" placeholder="Membership/registration no."  value="<?php echo set_value('stdcode');?>" data-parsley-trigger-after-failure="focusout" >
                <span class="error">
                <?php //echo form_error('stdcode');?>
                </span> </div>
              <div class="col-sm-3">
                 
             	<input type="submit" class="btn btn-info" name="getdetails" id="getdetails" value="Get Details" >   
                <span class="error"><?php //echo form_error('phone');?></span>
                </div>
                   </div> 
                   </center> <br /><br />
            </form >
               <form class="form-horizontal" name="dupcert" id="dupcert" action="" method="post">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
         
            <div class="box-body">
            
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <div class="form-group">
              
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name Of Candidate"  value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                    <span class="error">
                    <?php //echo form_error('nameOfBank');?>
                    </span> </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Examination (select the correct name) <span style="color:#f00">*</span></label>
                	<div class="col-sm-5">
                    <select name="sel_exam" id="sel_exam" class="form-control">
                    <option value="" >Select</option>
                    <option value="" <?php echo  set_select('sel_namesub', 'Mr.'); ?>>.</option>
                    <option value="" <?php echo  set_select('sel_namesub', 'Mrs.'); ?>>.</option>
                    <option value="" <?php echo  set_select('sel_namesub', 'Ms.'); ?>>.</option>
                    <option value="" <?php echo  set_select('sel_namesub', 'Dr.'); ?>>.</option>
                    <option value="" <?php echo  set_select('sel_namesub', 'Prof.'); ?>>.</option>
                    </select>
                     <span class="error" id="tiitle_error"><?php //echo form_error('firstname');?></span> 
                    </div>
             </div>
             <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo set_value('email');?>"  data-parsley-maxlength="45" required  data-parsley-emailcheck data-parsley-trigger-after-failure="focusout" >(Enter valid and correct email ID to receive communication) <span class="error"><?php //echo form_error('email');?></span>
                </div>
            </div> 
            
              <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Mobile </label>
              <div class="col-sm-5">
                <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo set_value('mobile');?>"  required  data-parsley-mobilecheck  data-parsley-trigger-after-failure="focusout" >
                <span class="error"> <?php //echo form_error('mobile');?></span>
                </div>
            </div>
            
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 <span style="color:#f00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="30" >
                      <span class="error"><?php //echo form_error('addressline1');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    


                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="30" >
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo set_value('addressline3');?>"  data-parsley-maxlength="30" >
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo set_value('addressline4');?>" data-parsley-maxlength="30" >
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#f00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                    (Max 30 Characters) 
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#f00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                    (Max 30 Characters) 
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#f00">*</span></label>
                	<div class="col-sm-3">
                    <select class="form-control" id="state" name="state" required >
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                        <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                      </select>
                    
                    <input hidden="statepincode" id="statepincode" value="">
      
                    </div> 
                     <label for="roleid" class="col-sm-3 control-label">Pincode/Zipcode <span style="color:#f00">*</span></label>
                   
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dbfcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout"  > (Max 6 digits)
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                </div>
          
            <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fees (including GST)</label>
                <div class="col-sm-3">
               <?php echo '118';?>
                  <span class="error">
                   
                  </span> </div>
               
              </div>
          </div>
         
                   •	Please note that the above address will be used only for sending the certificate. In case if you want to change your address and other details in the institute's record please use your <a href=<?php echo base_url();?>><span style="color:#F00">edit profile</span></a> or inform: Zonal office of IIBF<br />
                    •	Your duplicate certificate will be dispatched within one month
                    <br/>
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                     <img id="image_upload_sign_preview" height="100" width="100"/>
               
          <!-- Invoice Address Details box Closed--> 
              
          
          
          <!-- Communication Address Details box Closed-->
          
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php if(set_value('declaration1'))
			  {
				  echo set_radio('declaration1', '1');
				 }?>>
                &nbsp; I Accept</h3>
            </div>
            <div class="form-group m_t_15">
              <label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
              <div class="col-sm-2">
                <input type="text" name="code" id="code" required class="form-control " >
                <span class="error" id="captchaid" style="color:#B94A48;"></span> </div>
              <div class="col-sm-3">
                <div id="captcha_img">
                  <?php echo $image;?>
                </div>
                <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
              <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a> <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3"> <a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return checkform();" id="preview">Preview and Proceed for Payment</a> 
                <!--<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Preview and Proceed for Payment">-->
                <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>
              </div>
            </div>
          </div>
         </form> 
      		
        </div>
        </div>
    </section>
  </div>

 <!--Nature of present assignment 
contact person in case of emargency telephone/mobile

Are you a JAIIB/CAIIB?
If yes, in waht way it has contributed to your career (Work Area)-->
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/validation.js?<?php echo time(); ?>"></script> 
<script>
	$(function() {
		$("#dob1").dateDropdowns({
			submitFieldName: 'dob1',
			minAge: 0,
			maxAge:59
		});
		// Set all hidden fields to type text for the demo
		//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
	});
	$(function() {
		$("#doj1").dateDropdowns({
			submitFieldName: 'doj1',
			minAge: 0,
			maxAge:59
		});
	});
	
	$(document).ready(function() 
	{
		var dtable = $('.dataTables-example').DataTable();
		$("#dob1").change(function(){
			var sel_dob = $("#dob1").val();
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					chkage(dob_arr[2],dob_arr[1],dob_arr[0]);	
				}
				else
				{	alert('Select valid date');	}
			}
		});
		
		$("#doj1").change(function(){
			var sel_doj = $("#doj1").val();
			if(sel_doj!='')
			{
				var doj_arr = sel_doj.split('-');
				if(doj_arr.length == 3)
				{
					CompareToday(doj_arr[2],doj_arr[1],doj_arr[0]);	
				}
				else
				{	alert('Select valid date');	}
			}
		});
	
		var dt = new Date();
		dt.setFullYear(new Date().getFullYear()-18);
	
	if($('#hiddenphoto').val()!='')
	{
		   $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
	}
	if($('#hiddenscansignature').val()!='')
	{
		   $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
	}
	if($('#hiddenidproofphoto').val()!='')
	{
		   $('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());
	}
	
	
	statecode=$("#state option:selected").val();
	
	if(statecode!='')
	{
		if(statecode=='ASS' || statecode=='JAM' || statecode=='MEG')
		{
			document.getElementById('mendatory_state').style.display = "none";
			//document.getElementById('non_mendatory_state').style.display = "block";
			$("#aadhar_card").removeAttr("required");
		}
		else
		{
			document.getElementById('mendatory_state').style.display = "block";
			document.getElementById('mendatory_state').innerHTML = "*";
			//document.getElementById('non_mendatory_state').style.display = "none";
			$("#aadhar_card").attr("required","true");
		}
	}
	
	});
		
	function editUser(id,roleid,Name,Username,Email){
		$('#id').val(id);
		$('#roleid').val(roleid);
		$('#name').val(Name);
		$('#username').val(Username);
		$('#emailid').val(Email);
		$('#btnSubmit').val('Update');
		$('#roleid').focus();
		$('#password').removeAttr('required');
		$('#confirmPassword').removeAttr('required');
		
	}
	
	function changedu(dval)
	{
	
	$('#education_type').val(dval)
	var UGid = document.getElementById('UG');
	var GRid = document.getElementById('GR');
	var PGid = document.getElementById('PG');
	var EDUid = document.getElementById('edu');

	if(dval == 'U')
	{
		$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
	//	$('#noOptEdu').hide();
		
		if(UGid != null) {
		//	alert('UG');
			document.getElementById('UG').style.display = "block";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else if(dval == 'G')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
		//$('#noOptEdu').hide();
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "block";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	
	}
	else if(dval == 'P')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');
		//$('#noOptEdu').hide();
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "block";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else
	{
		//$('#noOptEdu').show();	
	}
}
	
</script> 
<script>
 function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

$(function(){
	function readCookie(name) {
		var nameEQ = encodeURIComponent(name) + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
		}
		return null;
	}

	if(readCookie('member_register_form'))
	{
		$('#error_id').html(''); 
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		createCookie('member_register_form', "", -1);	
	}
	

    $('#new_captcha').click(function(event){
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Register/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});

	 $(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
            if(event.which == '67')
			{
				alert('Key combination CTRL + C has been disabled.');
			}
			if(event.which == '86')
			{
				alert('Key combination CTRL + V has been disabled.');
			}
			event.preventDefault();
         }
    });
	
	$("body").on("contextmenu",function(e){
        return false;
    });
	
    $(this).scrollTop(0);
	
	var dval = $('#education_type').val();
	if(dval!='')
	{
		var UGid = document.getElementById('UG');
		var GRid = document.getElementById('GR');
		var PGid = document.getElementById('PG');
		var EDUid = document.getElementById('edu');
	
		if(dval == 'U')
		{
			$('#eduqual1').attr('required','required');
			$('#eduqual2').removeAttr('required');
			$('#eduqual3').removeAttr('required');
			$('#eduqual').removeAttr('required');
			
			if(UGid != null) {
			//	alert('UG');
				document.getElementById('UG').style.display = "block";
			}
			if(GRid != null) {
				document.getElementById('GR').style.display = "none";
			}
			if(PGid != null) {
				document.getElementById('PG').style.display = "none";	
			}
			if(EDUid != null) {
				document.getElementById('edu').style.display = "none";	
			}
		}
		else if(dval == 'G')
		{
			$('#eduqual1').removeAttr('required');;
			$('#eduqual2').attr('required','required');
			$('#eduqual3').removeAttr('required');
			$('#eduqual').removeAttr('required');
				
			if(UGid != null) {
				document.getElementById('UG').style.display = "none";
			}
			if(GRid != null) {
				document.getElementById('GR').style.display = "block";
			}
			if(PGid != null) {
				document.getElementById('PG').style.display = "none";	
			}
			if(EDUid != null) {
				document.getElementById('edu').style.display = "none";	
			}
		
		}
		else if(dval == 'P')
		{
			$('#eduqual1').removeAttr('required');;
			$('#eduqual2').removeAttr('required');
			$('#eduqual3').attr('required','required');
			$('#eduqual').removeAttr('required');
				
			if(UGid != null) {
				document.getElementById('UG').style.display = "none";
			}
			if(GRid != null) {
				document.getElementById('GR').style.display = "none";
			}
			if(PGid != null) {
				document.getElementById('PG').style.display = "block";	
			}
			if(EDUid != null) {
				document.getElementById('edu').style.display = "none";	
			}
		}
	
	}

});

	function sameAsAbove(fill) 
	{
	  
	/*var addressline1 = fill.addressline1.value;
	  var district = fill.district.value;
	  var city = fill.city.value;
	  var state = fill.state.value;
	  var pincode = fill.pincode.value;
	  
	  var r = confirm("Please fill contact details first!");
	  if (addressline1 == '' && district == '' && city == '' && state == '' && pincode == '' ) {
		alert('please fill contact details first..');
	  } 
	  else
	  { }*/
		  if(fill.same_as_above.checked == true) 
		  {
			fill.addressline1_pr.value = fill.addressline1.value;
			fill.addressline2_pr.value = fill.addressline2.value;
			fill.addressline3_pr.value = fill.addressline3.value;
			fill.addressline4_pr.value = fill.addressline4.value;
			fill.district_pr.value = fill.district.value;
			fill.city_pr.value = fill.city.value;
			fill.state_pr.value = fill.state.value;
			fill.pincode_pr.value = fill.pincode.value;
		  }
		  else
		  {
			fill.addressline1_pr.value = '';
			fill.addressline2_pr.value = '';
			fill.addressline3_pr.value = '';
			fill.addressline4_pr.value = '';
			fill.district_pr.value = '';
			fill.city_pr.value = '';
			fill.state_pr.value = '';
			fill.pincode_pr.value = '';
		  }
	 
	  /*else {
		txt = "You pressed Cancel!";
	  } */
	  
	}
</script> 
 
