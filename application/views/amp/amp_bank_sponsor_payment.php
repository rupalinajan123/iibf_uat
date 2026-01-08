<style>
.modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
    width: 920px;
    min-width: 300px;   
}

#confirm .modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
  	width: 420px;
    min-width: 400px;   
}
.skin-blue .main-header .navbar {
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
}
.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}

div.form-group:nth-child(odd) {
	background-color:#dcf1fc;
	padding:5px 0;
}

#confirmBox
{
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
	z-index:1;
	box-shadow:0 1px 3px #000;
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
#confirmBox .button:hover
{
    background-color: #ddd;
}
#confirmBox .message
{
    text-align: left;
    margin-bottom: 8px;
}
.form-group {
	margin-bottom:10px;
}
.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;

}
.form-control {
	border-color:#888;
}
.form-horizontal .control-label {
	font-weight:normal;
}
a.forget  {color:#9d0000;}
a.forget:hover {color:#9d0000; text-decoration:underline;}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}

.right {
	color: #b94a48;
	display: block;
	padding-bottom: 2px;
	text-align: right;
}
</style>
<?php
$days = array('Day',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$months = array('January','February','March','April','May','June','July','August','September','October','November','December');
$aDomain = array('aol.in','gmail.com','hotmail.com','in.com','live.com','rediffmail.com','sify.com','yahoo.co.in','yahoo.com');
?>
<form class="form-horizontal" name="ampForm" id="ampForm"  method="post"  enctype="multipart/form-data" >
<!-- data-parsley-validate="parsley"-->
	<div class="container">
		<section class="content-header">
			<h1 class="register">AMP XIV (2025-26) - Registration Form (For Bank Sponsored/Nominated - Self payment)</h1><br/>
		</section>
		<span class="error right">* Mandatory Field</span> 
		<span class="error">
			<?php
				echo validation_errors();
				if($photo_error!='')
					echo '<br/>'.$photo_error;
				if($sign_error!='')
					echo '<br/>'.$sign_error;
			?>
		</span>
		<section class="content">
			<div class="row">                                                                           
				<div class="col-md-12">
					<?php //if($sponsor=='Bank'){ ?>
					<!-- Sponsor Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Sponsor Details</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Name of Sponsoring Bank <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="sponsor_bank_name" name="sponsor_bank_name" value="<?php echo set_value('sponsor_bank_name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required >
								</div>(Maximum 30 Characters) 
							</div>
						</div>
						
						<!----------------------Bank Address---------------------------------->
						<div class="box-body">
							<div class="form-group">
								<!--<h6 class="box-title-hd">Bank Address Details</h6>-->
								<label class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="bank_address1" name="bank_address1" value="<?php echo set_value('bank_address1'); ?>" required >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line2 </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="bank_address2" name="bank_address2" value="<?php echo set_value('bank_address2');?>" >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line3 </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="bank_address3" name="bank_address3" value="<?php echo set_value('bank_address3');?>" >
								</div>
							</div>
						</div>
						
						<!-- <div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line4 </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="bank_address4" name="bank_address4" value="<?php echo set_value('bank_address4');?>" >
								</div>
							</div>
						</div> -->
						<div class="form-group">
							<label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="bank_city" name="bank_city" placeholder="" required value="<?php echo set_value('bank_city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
								  <span class="error"><?php //echo form_error('city');?></span>
								</div>   
						</div>
						<div class="box-body">
						<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State<span style="color:#F00">*</span></label>
									<div class="col-sm-3">
									<select class="form-control" id="bank_state" name="bank_state" required >
										 <option value="">Select</option>
									<?php if(count($states) > 0){
												foreach($states as $row1){ 	?>
									<option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('bank_state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
									<?php } } ?>
									</select>
									
									<input hidden="statepincode" id="statepincode" value="">
					  
									</div> 
									 <label for="roleid" class="col-sm-2 control-label">Pincode<span style="color:#F00">*</span></label>
								     
									 <div class="col-sm-3">
										<input type="text" class="form-control" id="bank_pincode" name="bank_pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('bank_pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-bank_checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" onkeypress="return isNumber(event)"> (Max 6 digits)
										 <span class="error"><?php //data-parsley-checkpin //echo form_error('pincode');?></span>
									</div>
                    
						</div>
						</div>
						<!-------------------------------------------------------------------->
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Department Email <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="sponsor_email" name="sponsor_email" value="<?php echo set_value('sponsor_email');?>" data-parsley-type="email" data-parsley-maxlength="50" maxlength="50" required >
									
									<!--<input type="text" class="form-control" id="sponsor_emailtext" name="sponsor_emailtext" value="<?php echo set_value('sponsor_emailtext');?>" data-parsley-maxlength="50" maxlength="50" required  onkeyup="sponsor_change_email()" >-->
								</div>
								<!--<div class="col-sm-2">
									<select name="sponsordomainname" id="sponsordomainname" onchange="sponsor_change_email()" required >
										<option value="" >Domain</option>
										<?php foreach($aDomain as $domain){ 
											$selected = '';
											if(isset($_POST['sponsordomainname']) && $_POST['sponsordomainname']==$domain)
												$selected = 'selected';
										?>
										<option value="<?php echo $domain; ?>" <?php echo $selected; ?> >@<?php echo $domain; ?></option>
										<?php } ?>
									</select>
								</div>-->
								(Maximum 50 Characters)
							</div>
							<script>
								function sponsor_change_email(){
									  var email = $('#sponsor_emailtext').val()+'@'+jQuery("#sponsordomainname option:selected").val();
									  $('#sponsor_email').val(email);
									}
							</script>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person name <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="sponsor_contact_person" name="sponsor_contact_person" value="<?php echo set_value('sponsor_contact_person');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="40" maxlength="40" required >
								</div>(Maximum 40 Characters) 
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Designation <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="sponsor_contact_designation" name="sponsor_contact_designation" value="<?php echo set_value('sponsor_contact_designation');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="50" maxlength="50" required >
								</div>(Maximum 50 Characters) 
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person STD code </label>
								<div class="col-sm-2">
									<input type="text" class="form-control" id="sponsor_contact_std" name="sponsor_contact_std" value="<?php echo set_value('sponsor_contact_std');?>" data-parsley-type="number" data-parsley-maxlength="5" maxlength="5" onkeypress="return isNumber(event)">
									(Maximum 5 digits)
								</div>
								<label class="col-sm-3 control-label">Contact person Phone No. </label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="sponsor_contact_phone" name="sponsor_contact_phone" value="<?php echo set_value('sponsor_contact_phone');?>" data-parsley-type="number" data-parsley-maxlength="8" maxlength="8" onkeypress="return isNumber(event)">
									(Maximum 8 digits) 
								</div>
							</div>
						</div>
						
						<!--<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Phone No. </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="sponsor_contact_phone" name="sponsor_contact_phone" value="<?php echo set_value('sponsor_contact_phone');?>" data-parsley-type="number" data-parsley-maxlength="8" maxlength="8" >
								</div>(Maximum 8 Characters) 
							</div>
						</div>-->
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Mobile number <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" required class="form-control" id="sponsor_contact_mobile" name="sponsor_contact_mobile" value="<?php echo set_value('sponsor_contact_mobile');?>" data-parsley-type="number" data-parsley-maxlength="10" data-parsley-minlength="10" maxlength="10" onkeypress="return isNumber(event)">
								</div>(10 digits)
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Email id <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="sponsor_contact_email" name="sponsor_contact_email" value="<?php echo set_value('sponsor_contact_email');?>" data-parsley-type="email" data-parsley-maxlength="50" maxlength="50" required >
									
									<!--<input type="text" class="form-control" id="sponsorcontactemailtext" name="sponsorcontactemailtext" value="<?php echo set_value('sponsorcontactemailtext');?>" data-parsley-maxlength="50" maxlength="50" required  onkeyup="sponsor_contact_change_email()" >-->
								</div>
								<!--<div class="col-sm-2">
									<select name="sponsorcontactdomainname" id="sponsorcontactdomainname" onchange="sponsor_contact_change_email()" required >
										<option value="" >Domain</option>
										<?php foreach($aDomain as $domain){ 
											$selected = '';
											if(isset($_POST['sponsorcontactdomainname']) && $_POST['sponsorcontactdomainname']==$domain)
												$selected = 'selected';
										?>
										<option value="<?php echo $domain; ?>" <?php echo $selected; ?> >@<?php echo $domain; ?></option>
										<?php } ?>
									</select>
								</div>-->
								(Maximum 50 Characters)
							</div>
							<script>
								function sponsor_contact_change_email(){
									var email = $('#sponsorcontactemailtext').val()+'@'+jQuery("#sponsorcontactdomainname option:selected").val();
									  $('#sponsor_contact_email').val(email);
									}
							</script>
						</div>
						
					</div>
					<!-- Sponsor Details Box close -->
					<?php //} ?>
					
					<!-- Basic Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Basic Details</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Name <span style="color:#F00">*</span></label>
								<div class="col-sm-2">
                                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                                    <option value="" <?php echo  set_select('sel_namesub', '', ($examRes["namesub"] == '') ); ?>>Select</option>
                                    <option value="Mr." <?php echo  set_select('sel_namesub', 'Mr.', ($examRes["namesub"] == 'Mr.') ); ?>>Mr.</option>
                                    <option value="Mrs." <?php echo  set_select('sel_namesub', 'Mrs.', ($examRes["namesub"] == 'Mrs.' ) ); ?>>Mrs.</option>
                                    <option value="Ms." <?php echo  set_select('sel_namesub', 'Ms.', ($examRes["namesub"]) == 'Ms.' ); ?>>Ms.</option>
                                    <option value="Dr." <?php echo  set_select('sel_namesub', 'Dr.', ($examRes["namesub"]) == 'Dr.' ); ?>>Dr.</option>
                                    <option value="Prof." <?php echo  set_select('sel_namesub', 'Prof.', ($examRes["namesub"]) == 'Prof.' ); ?>>Prof.</option>
                                    
                                   </select>
                                </div>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required >
								</div>(Maximum 30 Characters) 
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">IIBF Registration no (if available)</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="iibf_membership_no" name="iibf_membership_no" value="<?php echo set_value('iibf_membership_no');?>" data-parsley-maxlength="11" maxlength="11" data-parsley-type="number" >
								</div>(Max 11 Characters) 
							</div>
						</div>
						
						<style>
							select.day, select.month, select.year {width:100%;}
						</style>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
								<div class="col-sm-2">
									<input type="hidden" class="form-control" id="dob" name="dob" value="<?php echo set_value('dob');?>" required >
									
									<select class="day" name="bday" id="bday" required onchange="updatedob()" >
										<option value="">Day</option>
										<?php for($day=1;$day<=31;$day++){ 
											$selected = '';
											if(isset($_POST['bday']) && $_POST['bday'] == $day)
												$selected = 'selected';
										?>
										<option value="<?php echo $day; ?>" <?php echo $selected; ?> ><?php echo $day; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-2">
									<select class="month" name="bmonth" id="bmonth" required onchange="updatedob()" >
										<option value="">Month</option>
										<?php $i=0; foreach($months as $month){ $i++;
											$selected = '';
											if(isset($_POST['bmonth']) && $_POST['bmonth'] == $i)
												$selected = 'selected';
										?>
										<option value="<?php echo $i; ?>" <?php echo $selected; ?> ><?php echo $month; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-2">
									<select class="year" name="byear" id="byear" required onchange="updatedob()" >
										<option value="">Year</option>
										<?php for($year=1956;$year<=1997;$year++){ 
											$selected = '';
											if(isset($_POST['byear']) && $_POST['byear'] == $year)
												$selected = 'selected';
										?>
										<option value="<?php echo $year; ?>" <?php echo $selected; ?> ><?php echo $year; ?></option>
										<?php } ?>
									</select>
									
									<script>
										function updatedob(){
											if($('#bday').val()!='' && $('#bmonth').val()!=''  && $('#byear').val()!=''){
												$('#dob').val($('#byear').val()+'-'+$('#bmonth').val()+'-'+$('#bday').val());
											}else{
												$('#dob').val('');
											}
										}
									</script>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<h6 class="box-title-hd">&nbsp;&nbsp;&nbsp;&nbsp;Correspondence postal address</h6>
								<label class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="address1" name="address1" value="<?php echo set_value('address1'); ?>" required >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line2 </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="address2" name="address2" value="<?php echo set_value('address2');?>" >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line3 </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="address3" name="address3" value="<?php echo set_value('address3');?>" >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line4 </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="address4" name="address4" value="<?php echo set_value('address4');?>" >
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="city" name="city" placeholder="" required value="<?php echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
								  <span class="error"><?php //echo form_error('city');?></span>
								</div>   
						</div>
						<div class="box-body">
						<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State<span style="color:#F00">*</span></label>
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
									 <label for="roleid" class="col-sm-2 control-label">Pincode<span style="color:#F00">*</span></label>
								     
									 <div class="col-sm-3">
										<input type="text" class="form-control" id="pincode_address" name="pincode_address" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode_address');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" onkeypress="return isNumber(event)" > (Max 6 digits)
										 <span class="error" ><?php //data-parsley-checkpin //echo form_error('pincode');?></span>
									</div>
                    
						</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">STD code </label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="std_code" name="std_code" value="<?php echo set_value('std_code');?>" data-parsley-maxlength="5" maxlength="5" data-parsley-type="number" onkeypress="return isNumber(event)">(Maximum 5 digits)
								</div>
								<label class="col-sm-2 control-label">Phone No. </label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo set_value('phone_no');?>"  data-parsley-maxlength="8" maxlength="8" data-parsley-type="number" onkeypress="return isNumber(event)">(Maximum 8 digits)
								</div>
							</div>
						</div>
						
						<!--<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Phone No. </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo set_value('phone_no');?>"  data-parsley-maxlength="8" maxlength="8" data-parsley-type="number" >
								</div>(Maximum 8 digits)
							</div>
						</div>-->
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Mobile No. <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="mobile_no" name="mobile_no" value="<?php echo set_value('mobile_no');?>" data-parsley-maxlength="10" maxlength="10" data-parsley-type="number" data-parsley-minlength="10" data-parsley-check_mobile required onkeypress="return isNumber(event)">
								</div>(10 digits)
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Email ID <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="email_id" name="email_id" value="<?php echo set_value('email_id');?>" data-parsley-maxlength="50" maxlength="50" data-parsley-type="email" data-parsley-check_email required >
									
									<!--<input type="text" class="form-control" id="canemailtext" name="canemailtext" value="<?php echo set_value('canemailtext');?>" data-parsley-maxlength="50" maxlength="50" required  onkeyup="can_change_email()" >-->
								</div>
								<!--<div class="col-sm-2">
									<select name="candomainname"  id="candomainname" onchange="can_change_email()" required >
										<option value="" >Domain</option>
										<?php foreach($aDomain as $domain){ 
											$selected = '';
											if(isset($_POST['candomainname']) && $_POST['candomainname']==$domain)
												$selected = 'selected';
										?>
										<option value="<?php echo $domain; ?>" <?php echo $selected; ?> >@<?php echo $domain; ?></option>
										<?php } ?>
									</select>
								</div>-->
								(Maximum 50 Characters)
							</div>
							<script>
								function can_change_email(){
									  var email = $('#canemailtext').val()+'@'+jQuery("#candomainname option:selected").val();
									  $('#email_id').val(email);
									}
							</script>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Alternate Email ID </label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="alt_email_id" name="alt_email_id" value="<?php echo set_value('alt_email_id');?>" data-parsley-maxlength="50" maxlength="50" data-parsley-type="email" >
									
									<!--<input type="text" class="form-control" id="canaltemailtext" name="canaltemailtext" value="<?php echo set_value('canaltemailtext');?>" data-parsley-maxlength="50" maxlength="50"  onkeyup="can_alt_change_email()" >-->
								</div>
								<!--<div class="col-sm-2">
									<select name="canaltdomainname"  id="canaltdomainname" onchange="can_alt_change_email()" >
										<option value="" >Domain</option>
										<?php foreach($aDomain as $domain){ 
											$selected = '';
											if(isset($_POST['canaltdomainname']) && $_POST['canaltdomainname']==$domain)
												$selected = 'selected';
										?>
										<option value="<?php echo $domain; ?>" <?php echo $selected; ?> >@<?php echo $domain; ?></option>
										<?php } ?>
									</select>
								</div>-->
								(Maximum 50 Characters)
							</div>
							<script>
								function can_alt_change_email(){
									  var email = $('#canaltemailtext').val()+'@'+jQuery("#canaltdomainname option:selected").val();
									  if(email=='@')
										  $('#alt_email_id').val('');
									  else
										  $('#alt_email_id').val(email);
									}
							</script>
						</div>
						
					</div> 
					<!-- Basic Details box closed-->
					
					<!-- Educational Qualification -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Educational Qualification</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Graduation <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" required id="graduation" name="graduation" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/"  value="<?php echo set_value('graduation');?>" >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Post Graduation </label>
								<div class="col-sm-5">
									<input type="text" class="form-control"  id="post_graduation" name="post_graduation" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/"  value="<?php echo set_value('post_graduation');?>" >
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Special Qualification</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="special_qualification" name="special_qualification" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/"  value="<?php echo set_value('special_qualification');?>" >
								</div>
							</div>
						</div>
					</div> 
					<!-- Educational Qualification Box close -->
					
					<!-- Work experience details (present Employer) -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Work experience details (present Employer)</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Name of the Employer <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="name_employer" name="name_employer" value="<?php echo set_value('name_employer');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Position <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="position" name="position" value="<?php echo set_value('position');?>"  data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" required>
								</div>
							</div>
						</div>
						
						<!-- <div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Period From</label>
								<div class="col-sm-5">
									<select name="work_from_month" id="work_from_month" >
										<option value="">Month</option>
										<?php foreach($months as $month){ 
											$selected = '';
											if(isset($_POST['work_from_month']) && $_POST['work_from_month'] == $month)
												$selected = 'selected';
										?>
										<option value="<?php echo $month; ?>" <?php echo $selected; ?> ><?php echo $month; ?></option>
										<?php } ?>
									</select>
									
									<select name="work_from_year" id="work_from_year" >
										<option value="">Year</option>
										<?php for($year=1983;$year<=date('Y',strtotime('now'));$year++){
											$selected = '';
											if(isset($_POST['work_from_year']) && $_POST['work_from_year'] == $year)
												$selected = 'selected';
										?>
										<option value="<?php echo $year; ?>" <?php echo $selected; ?> ><?php echo $year; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Period to</label>
								<div class="col-sm-5">
									<select name="work_to_month" id="work_to_month" >
										<option value="">Month</option>
										<?php foreach($months as $month){
											$selected = '';
											if(isset($_POST['work_to_month']) && $_POST['work_to_month'] == $month)
												$selected = 'selected';
										?>
										<option value="<?php echo $month ?>" <?php echo $selected; ?> ><?php echo $month ?></option>
										<?php } ?>
									</select>
									
									<select name="work_to_year" id="work_to_year" >
										<option value="">Year</option>
										<?php for($year=1983;$year<=date('Y',strtotime('now'));$year++){
											$selected = '';
											if(isset($_POST['work_to_year']) && $_POST['work_to_year'] == $year)
												$selected = 'selected';
										?>
										<option value="<?php echo $year; ?>" <?php echo $selected; ?> ><?php echo $year; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						 -->
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Till Present</label>
								<div class="col-sm-5">
									<input type="checkbox" onclick="workdate()" id="till_present" name="till_present" value="1" <?php if(isset($_POST['till_present']) && $_POST['till_present'] == 1){ echo 'checked'; } ?> >Yes
								</div>
							</div>
							<script>
							function workdate(){
								var y = <?php echo date('Y',strtotime('now')); ?>;
								var m = '<?php echo date('F',strtotime('now')); ?>';
								$("#work_to_year").val(y);
								$("#work_to_month").val(m);
							}
							</script>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Total Experience in month <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="work_experiance" name="work_experiance" value="<?php echo set_value('work_experiance');?>" min="60" data-parsley-type="number" data-parsley-maxlength="3"  maxlength="3" data-parsley-minlength="2"  minlength="2" required onkeypress="return isNumber(event)">
								</div>(Maximum 3 digits)
							</div>
						</div>
						
					</div>
					<!-- Work experience details (present Employer) Box close -->
					
					<!-- Photograph and Signature -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Photograph, ID Proof and Signature</h3>
						</div>
						<span class="error">If the Photo/ ID Poof / Signature is not loaded in appropriate place, your application is liable to get rejected
						<br>Allowed Photo Size - 50KB (Only JPG or jpeg or png files)
						<br>Allowed Signature Size - 50KB (Only JPG or jpeg or png files)
						<br>Allowed ID Size - 300KB (Only JPG or jpeg or png files)</span>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Photograph of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="file" class="form-control" id="photograph" name="photograph" onchange="validateFile(event, 'error_photo_size', 'image_upload_photograph_preview', '50kb')"  required >
									<span class="note">Please Upload only .jpg, .jpeg, .png  Files upto 50KB</span></br>
									<input type="hidden" id="hiddenphoto" name="hiddenphoto">
									<div id="error_photo"></div>
									<span class="note-error" id="error_photo_size"></span>
									<span class="photo_text" style="display:none;"></span>
									<img id="image_upload_photograph_preview" height="100" width="100"/>
									
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Signature of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="file" class="form-control" id="signature" name="signature" required onchange="validateFile(event, 'error_signature', 'image_upload_signature_preview', '50kb')">
									<span class="note">Please Upload only .jpg, .jpeg, .png  Files upto 50KB</span></br>
									<input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
									<span class="note-error" id="error_signature"></span>
									<!-- <span class="signature_text" style="display:none;"></span> -->
									<img id="image_upload_signature_preview" height="100" width="100"/>
									
								</div>
							</div>
						</div>

						<div class="box-body">
			              <div class="form-group">
			                <label class="col-sm-4 control-label">ID Proof of the Candidate <span style="color:#F00">*</span><br>VoterID/Adharcard/Organization Employee ID</label>
			                <div class="col-sm-5">
			                  <input type="file" class="form-control" id="idproof" name="idproof"  onchange="validateFile(event, 'error_idproof_size', 'image_upload_idproof_preview', '300kb')" required >
			                  <span class="note">Please Upload only .jpg, .jpeg, .png  Files upto 300KB</span></br>
			                  <input type="hidden" id="hiddenscanidproof" name="hiddenscanidproof">
			                  <div id="error_idproof"></div>
			                  <span class="note-error" id="error_idproof_size"></span>
			                  <!-- <span class="idproof_text" style="display:none;"></span> -->
			                  <img id="image_upload_idproof_preview" height="100" width="100"/>
			                  
			                </div>
			              </div>
			            </div>
					</div>
					<!-- Photograph and Signature box close -->
					
					<!-- Payment Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Payment Details</h3>
						</div>
						<style>
						ul#parsley-id-multiple-payment {
							float: left;
						}
						</style>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Payment Option <span style="color:#F00">*</span></label>
								<div class="col-sm-8">
									<input type="radio" id="payment" name="payment" required value="full" <?php if(isset($_POST['payment']) && $_POST['payment'] == 'full'){ echo 'checked'; } ?> >Full payment Rs. <?php echo $this->config->item('amp_full_cs_total'); ?>/-
									<input type="radio" id="payment" name="payment" required value="first" <?php if(isset($_POST['payment']) && $_POST['payment'] == 'first'){ echo 'checked'; } ?>>1st Installment Rs. 70800/-
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">GSTIN No <span style="color:#F00">*</span></label>
									<div class="col-sm-6">
									   <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="Enter GSTN No"  value=""  data-parsley-maxlength="15" required maxlength="15" data-parsley-pattern="\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}"><?php //data-parsley-check_gstin ?>
									   <span class="note">Note: Please Enter 15 alphanumeric GST No. like 09AAACH7409R1ZZ.</span>
									</div> 
							</div> 
						</div>
					</div>
					<!-- Payment Details box close -->
					
					<!-- Declaration -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Declaration</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">								
								<div class="col-sm-12">
									1. I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect, my enrollment in AMP is liable to be cancelled/ terminated.
								</div>
								<div class="col-sm-12">
									2. I confirm having read and understood the rules and regulations of the Institute and I hereby agree to abide by the same. In case of any legal proceeding against the Institute I hereby agree that such legal proceedings shall be only in courts at Mumbai.
								</div>
								<div class="col-sm-12">
									3. If your application rejected by the Institute for enrolment in AMP then fee will be refunded.
								</div>
								<div class="col-sm-12">
									4. I understand that IIBF reserves the right to accept or reject my enrolment in AMP without assigning any reason what so ever. 
								</div>
							</div>
						</div>
					</div>
					<!-- Declaration box close -->
					
					<div class="box box-info">
						<div class="box-header with-border">
							 <input type="checkbox" class="" id="agree" name="agree" value="1" required <?php if(isset($_POST['agree']) && $_POST['agree'] == 1){ echo 'checked'; } ?> > I Agree
						</div>
					</div>
					
					<!-- captcha -->
					<div class="box box-info">
						<div class="box-body">
							<div class="form-group">								
								<label class="col-sm-3 control-label">&nbsp;</label>
								<div class="col-sm-6">
									<span style="color:#F00">Enter the Word in the textbox as in the image</span> 
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">								
								<label class="col-sm-4 control-label">Security Code <span style="color:#F00">*</span></label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="captcha" name="captcha" required >
								</div>
								<div class="col-sm-3">
									<div id="captcha_img"><?php echo $image;?></div>
								</div>
								<div class="col-sm-2">
									<a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
								</div>
							</div>
						</div>
					</div>
					<!-- captcha box close -->
					
					<div class="box box-info">
						<div class="form-group">								
							<label class="col-sm-5 control-label">&nbsp;</label>
							<div class="col-sm-4">
								<input type="hidden" name="form_type" value="amp_form" />
								<input type="submit" name="submit" value="Submit" onclick="javascript:return checkform();" />
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section>
	</div>
</form>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url();?>js/amp_validation.js"></script>
<script>
$(document).ready(function() {
	$('#new_captcha').click(function(event){
      event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Amp/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
  }

</script>