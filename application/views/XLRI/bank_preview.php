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
<form class="form-horizontal" name=""  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>XLRI/bank_addmember">

<!-- data-parsley-validate="parsley"-->
	<div class="container">
		<section class="content-header">
			<h1 class="register">LEADERSHIP DEVELOPMENT PROGRAMME FOR BANKS/FIs IIBF -XLRI July 2021
                                       <br> Bank Sponsored candidates</h1><br/>
		</section>
		<!--<span class="error right">* Mandatory Field</span>-->
	
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
								<?php echo $this->session->userdata['insertdata']['sponsor_bank_name'];?>
								</div> 
							</div>
						</div>
						
						<!----------------------Bank Address---------------------------------->
						<div class="box-body">
							<div class="form-group">
								<!--<h6 class="box-title-hd">Bank Address Details</h6>-->
								<label class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['bank_address1'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line2 </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['bank_address2'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line3 </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['bank_address3'];?>
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
									<?php echo $this->session->userdata['insertdata']['bank_city'];?>
								</div>   
						</div>
						<div class="box-body">
						<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State<span style="color:#F00">*</span></label>
									<div class="col-sm-3">
									<?php echo $this->session->userdata['insertdata']['bank_state'];?>
					  
									</div> 
									 <label for="roleid" class="col-sm-2 control-label">Pincode<span style="color:#F00">*</span></label>
								     
									 <div class="col-sm-3">
										<?php echo $this->session->userdata['insertdata']['bank_pincode'];?>
					  
									</div>
                    
						</div>
						</div>
						<!-------------------------------------------------------------------->
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Department Email <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['sponsor_email'];?>
					  
							</div>
							
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person name <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
								<?php echo $this->session->userdata['insertdata']['sponsor_contact_person'];?>
					  
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Designation <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['sponsor_contact_designation'];?>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person STD code </label>
								<div class="col-sm-2">
								<?php echo $this->session->userdata['insertdata']['sponsor_contact_std'];?>
								</div>
								<label class="col-sm-3 control-label">Contact person Phone No. </label>
								<div class="col-sm-3">
									<?php echo $this->session->userdata['insertdata']['sponsor_contact_phone'];?> 
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
								<label class="col-sm-4 control-label">Contact person Mobile number </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['sponsor_contact_mobile'];?>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Email id <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
								<?php echo $this->session->userdata['insertdata']['sponsor_contact_email'];?>	
									<!--<input type="text" class="form-control" id="sponsorcontactemailtext" name="sponsorcontactemailtext" value="<?php echo set_value('sponsorcontactemailtext');?>" data-parsley-maxlength="50" maxlength="50" required  onkeyup="sponsor_contact_change_email()" >-->
								</div>
								
							</div>
							
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
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['name'];?>	</div> 
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">IIBF Membership no (if available)</label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['iibf_membership_no'];?>
								</div>
							</div>
						</div>
						
						<style>
							select.day, select.month, select.year {width:100%;}
						</style>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['dob'];?>
								</div>
								
								
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<h6 class="box-title-hd">&nbsp;&nbsp;&nbsp;&nbsp;Office/Residential Address for communication</h6>
								<label class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['address1'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line2 </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['address2'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line3 </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['address3'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line4 </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['address4'];?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-6">
									<?php echo $this->session->userdata['insertdata']['city'];?>
								</div>   
						</div>
						<div class="box-body">
						<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State<span style="color:#F00">*</span></label>
									<div class="col-sm-3">
									<?php echo $this->session->userdata['insertdata']['state'];?>
									</div> 
									 <label for="roleid" class="col-sm-2 control-label">Pincode<span style="color:#F00">*</span></label>
								     
									 <div class="col-sm-3">
										<?php echo $this->session->userdata['insertdata']['pincode_address'];?>
									</div>
                    
						</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">STD code </label>
								<div class="col-sm-3">
									<?php echo $this->session->userdata['insertdata']['std_code'];?>
								</div>
								<label class="col-sm-2 control-label">Phone No. </label>
								<div class="col-sm-3">
									<?php echo $this->session->userdata['insertdata']['phone_no'];?>
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
									<?php echo $this->session->userdata['insertdata']['mobile_no'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Email ID <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['email_id'];?>
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
								
							</div>
							
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Alternate Email ID </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['alt_email_id'];?>
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
								
							</div>
						
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
								<?php echo $this->session->userdata['insertdata']['graduation'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Post Graduation </label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['post_graduation'];?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Special Qualification</label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['special_qualification'];?>
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
								<?php echo $this->session->userdata['insertdata']['name_employer'];?>
									</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Position <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['position'];?>
								</div>
							</div>
						</div>
						
					
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Till Present</label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['till_present'];?>	</div>
							</div>
						
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Total Experience in month</label>
								<div class="col-sm-5">
									<?php echo $this->session->userdata['insertdata']['work_experiance'];?>
								</div>
							</div>
						</div>
						
					</div>
					<!-- Work experience details (present Employer) Box close -->
					
					<!-- Photograph and Signature -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Photograph, ID Proof and Signature</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Photograph of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									
									<img id="image_upload_photograph_preview" height="100" width="100" src='<?php echo base_url();?>/uploads/XLRI/photograph/<?php echo $this->session->userdata['insertdata']['photograph'];?>' />
									
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Signature of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									
									<img id="image_upload_signature_preview" height="100" width="100" src='<?php echo base_url();?>/uploads/XLRI/signature/<?php echo $this->session->userdata['insertdata']['signature'];?>'/>
									
								</div>
							</div>
						</div>

						<div class="box-body">
              <div class="form-group">
                <label class="col-sm-4 control-label">ID Proof of the Candidate <span style="color:#F00">*</span><br>VoterID/Adharcard/Organization Employee ID</label>
                <div class="col-sm-5">
                  
                  <img id="image_upload_idproof_preview" height="100" width="100" src='<?php echo base_url();?>/uploads/XLRI/idproof/<?php echo $this->session->userdata['insertdata']['idproof'];?>' />
                  
                </div>
              </div>
            </div>
					</div>
					<!-- Photograph and Signature box close -->
					
				
					
					
					
					
					
					<div class="box box-info">
						<div class="form-group">								
							<label class="col-sm-5 control-label">&nbsp;</label>
							<div class="col-sm-4">
								
								<input type="submit" name="submit" class="btn btn-info" value="Submit"  />
								<a href='<?php echo base_url();?>XLRI/bank' type="button" name="back" class="btn btn-info" value="Back" />Back</a>
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
 		url: site_url+'XLRI/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});
});

</script>