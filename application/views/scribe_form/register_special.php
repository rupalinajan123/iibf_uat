<!DOCTYPE html>
<html>
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
	
	<head>
		<?php $this->load->view('scribe_form/inc_header'); ?>
		
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('scribe_form/inc_navbar'); ?>	
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">Apply for special assistance/extra time</h1><br/>
					</section>
					
					<div class="box box-info" style="padding: 0 10px 0 10px;">
						<?php if($this->session->flashdata('error')!=''){?>
							<div class="alert alert-danger alert-dismissible" id="error_id">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('error'); ?>
							</div>
							
							<?php } if($this->session->flashdata('success')!=''){ ?>
							<div class="alert alert-success alert-dismissible" id="success_id">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('success'); ?>
							</div> 
						<?php } ?> 
  					</div>
  					<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="<?php echo site_url('Scribe_form/getDetails_Special/'); ?>" enctype="multipart/form-data" autocomplete="off">
   					<input type="hidden" class="csrf_iibf_name"  name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" autocomplete='false'> 
   					<?php 		
								if(count($member_details) > 0 && isset($member_details[0]['regnumber'])) 
				 				{ 
									$regnumber = $member_details[0]['regnumber'];  
									$readonly = "readonly='readonly'"; 
								}
								else if($this->session->has_userdata('session_array'))
								{									
									$regnumber = (isset($session_data['member_no']))?$session_data['member_no']:'';
									$readonly = "readonly='readonly'";
								}
								else { $regnumber = ''; }
								
								if($regnumber != "")
								{	 ?>								
									<div class="form-group text-center">
										
										<div class="col-sm-8">
											<input type="hidden" class="form-control " id="member_no" name="member_no" placeholder="Membership/Registration No." value="<?php echo $regnumber; ?>" <?php echo $readonly; ?>>
										</div>
									</div>
									
					<?php } ?>
					<input type="hidden" class="form-control " id="exam_code" name="exam_code" value="<?php echo $exam_name[0]['exam_code']; ?>">
					<!-- <input type="text" class="form-control " id="exam_code" name="exam_code" value="<?php echo $member_details[0]['exam_code']; ?>"> -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          
          <div class="box box-info">

            <div class="box-body">
              <?php //print_r($center_master); ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control" id="sel_namesub" name="sel_namesub" value="<?php if (isset($member_details[0]['namesub'])) { echo $member_details[0]['namesub'];}?>"readonly="readonly" placeholder="Prefix">
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php if (isset($member_details[0]['firstname'])) {echo $member_details[0]['firstname'];}?>" readonly="readonly">
                </div>
              </div>
			  
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Middle Name&nbsp;:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="middlename" id="middlename" value="<?php if (isset($member_details[0]['middlename'])){echo $member_details[0]['middlename'];}?>" readonly="readonly"  placeholder="Middle Name"/>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="lastname" id="lastname" value="<?php if (isset($member_details[0]['lastname'])) {echo $member_details[0]['lastname'];}?>" placeholder="Last Name" readonly="readonly"/>
                </div>
              </div>
			  
			  <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php if (isset($member_details[0]['email'])) { echo $member_details[0]['email'];} ?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout" readonly="readonly">
                    <span class="error"> </span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php if (isset($member_details[0]['mobile'])) {echo $member_details[0]['mobile'];} ?>"  required  data-parsley-trigger-after-failure="focusout" readonly="readonly">
                    <span class="error"></span> </div>
                </div>
				<?php //print_r($center_master); ?>
				
				<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Center Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="tel" class="form-control" id="selCenterName" name="selCenterName"    value="<?php if (isset($member_details[0]['center_name'])) {echo $member_details[0]['center_name'];} ?>"  required  data-parsley-trigger-after-failure="focusout" readonly="readonly">
                    <span class="error"></span> </div>
                </div>
				<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Center Code&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="tel" class="form-control" id="selCenterCode" name="selCenterCode"    value="<?php if (isset($member_details[0]['center_code'])) {echo $member_details[0]['center_code'];} ?>"  required  data-parsley-trigger-after-failure="focusout" readonly="readonly">
                    <span class="error"></span> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Exam Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="exam name"   value="<?php if (isset($exam_name[0]['description'])) { echo $exam_name[0]['description'];} ?>"  readonly="readonly">
                    <span class="error"> </span> </div>
               </div>

               <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Subject Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="subject" name="subject_name" placeholder="subject_name"   value="<?php if (isset($subject_name[0]['subject_description'])) { echo $subject_name[0]['subject_description'];} ?>"  readonly="readonly">
                    <span class="error"> </span>
                    <input type="hidden" id="subject_code" name="subject_code" value="<?php if (isset($subject_name[0]['subject_code'])) { echo $subject_name[0]['subject_code'];} ?>"> 
                  </div>
               </div>

               <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Exam Date&nbsp;:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="exam_date" id="exam_date" value="<?php if (isset($subject_name[0]['exam_date'])){echo $subject_name[0]['exam_date'];}?>" readonly="readonly"  placeholder="Exam Date"/>
                </div>
              </div>

               <!-- Benchmark Disability Code Start -->
			<!-- <div class="box-header with-border header_blue">
			  <h3 class="box-title">Disability</h3>
			</div><br/> -->
			<div class="form-group">
			  <label for="roleid" class="col-sm-4 control-label">Person with Benchmark Disability</label>
			  <div class="col-sm-3">
				<input value="Y" name="benchmark_disability" id="benchmark_disability" type="radio"  <?php echo set_radio('benchmark_disability', 'Y'); ?> class="benchmark_disability_y" checked="checked">
				Yes
				<input value="N" name="benchmark_disability" id="benchmark_disability" type="radio"  <?php echo set_radio('benchmark_disability', 'N'); ?> class="benchmark_disability_n" disabled>
				No <span class="error"></span> 
				<span class="error"></span> 
                <span id="benchmark_disability_err"></span>
			</div>
			</div>
			
			<div id="benchmark_disability_div" style="display:none;">
			
				<div class="form-group">
				  <label for="roleid" class="col-sm-4 control-label">Visually impaired</label>
				  <div class="col-sm-3">
					<input value="Y" name="visually_impaired" id="visually_impaired" type="radio"  <?php echo set_radio('visually_impaired', 'Y'); ?> class="visually_impaired_y">
					Yes
					<input value="N" name="visually_impaired" id="visually_impaired" type="radio"  <?php echo set_radio('visually_impaired', 'N'); ?> class="visually_impaired_n" checked="checked">
					No <span class="error"></span> </div>
				</div>

				<div class="form-group"  id="vis_imp_cert_div" style="display:none;">
                  <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                  <input onchange="readURL(this,'scanned_vis_imp_cert_preview')"  type="file" name="scanned_vis_imp_cert" id="scanned_vis_imp_cert" required  style="word-wrap: break-word;width: 100%;">
                  <input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
                  <div id="error_vis_imp_cert"></div>
                  <br>
                  <div id="error_vis_imp_cert_size"></div>
                  <span class="vis_imp_cert_text" style="display:none;"></span> <span class="error"> </span> 
                  <img class="mem_reg_img_prev" id="scanned_vis_imp_cert_preview" height="100" width="100" src="/assets/images/default1.png"/>
                </div>
                </div>
            
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Orthopedically handicapped</label>
                  <div class="col-sm-3">
                  <input value="Y" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio"  <?php echo set_radio('orthopedically_handicapped', 'Y'); ?> class="orthopedically_handicapped_y">
                  Yes
                  <input value="N" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio"  <?php echo set_radio('orthopedically_handicapped', 'N'); ?> class="orthopedically_handicapped_n" checked="checked">
                  No <span class="error"></span> </div>
                </div>

                <div class="form-group" id="orth_han_cert_div" style="display:none;">
                  <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                  <input onchange="readURL(this,'scanned_orth_han_cert_preview')" type="file" name="scanned_orth_han_cert" id="scanned_orth_han_cert" required  style="word-wrap: break-word;width: 100%;">
                  <input type="hidden" id="hidden_orth_han_cert" name="hidden_orth_han_cert">
                  <div id="error_orth_han_cert"></div>
                  <br>
                  <div id="error_orth_han_cert_size"></div>
                  <span class="orth_han_cert_text" style="display:none;"></span> 
                  <span class="error"> </span> 
                  <img class="mem_reg_img_prev" id="scanned_orth_han_cert_preview" height="100" width="100" src="/assets/images/default1.png"/>
                </div>
                </div>
            
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Cerebral palsy</label>
                  <div class="col-sm-3">
                  <input value="Y" name="cerebral_palsy" id="cerebral_palsy" type="radio"  <?php echo set_radio('cerebral_palsy', 'Y'); ?>  class="cerebral_palsy_y">
                  Yes
                  <input value="N" name="cerebral_palsy" id="cerebral_palsy" type="radio"  <?php echo set_radio('cerebral_palsy', 'N'); ?>  class="cerebral_palsy_n" checked="checked">
                  No <span class="error"></span> </div>
                </div>

                <div class="form-group" id="cer_palsy_cert_div" style="display:none;">
                  <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                  <input onchange="readURL(this,'scanned_cer_palsy_cert_preview')" type="file" value="<?php //echo set_value('scanned_cer_palsy_cert');?>" name="scanned_cer_palsy_cert" id="scanned_cer_palsy_cert" required  style="word-wrap: break-word;width: 100%;">
                  <input type="hidden" id="hidden_cer_palsy_cert" name="hidden_cer_palsy_cert">
                  <div id="error_cer_palsy_cert"></div>
                  <br>
                  <div id="error_cer_palsy_cert_size"></div>
                  <span class="cer_palsy_cert_text" style="display:none;"></span> <span class="error"> </span> 
                  <img class="mem_reg_img" id="scanned_cer_palsy_cert_preview" height="100" width="100" src="/assets/images/default1.png"/> 
                </div>
                </div>
		  	<!-- Benchmark Disability Code End -->

				<div class="box-header with-border header_blue">
					<h3 class="box-title ">Scribe Details</h3>
				  </div> <br/>
				  
	          	<div class="form-group">
	          		<label for="description" class="col-sm-4 control-label">Request Type &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
	          		<div class="col-sm-5">
		          	  <input type="checkbox" id="special_assistance" name="special_assistance" value="special_assistance" <?php echo set_checkbox('special_assistance','special_assistance'); ?>>
					  <label for="special_assistance"> Special Assistance </label>
					  <input type="checkbox" id="extra_time" name="extra_time" value="extra_time" <?php echo set_checkbox('extra_time','extra_time'); ?>>
					  <label for="extra_time"> Extra Time </label><br>
					</div>  
	          	</div>
              

              	<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Request description&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <textarea maxlength="500" rows="4" class="form-control" id="description" name="description" placeholder="description" ></textarea>
                    <span class="error">
                      <?php //echo form_error('description');?>
                    </span>
              	</div>
              	<input type="hidden" id="education_type" value="">
				
				
        	</div>
			<div class="form-group">
				<label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Security Code <span style="color:#F00; ">*</span>
				</label>										
				
				<div class="col-sm-3">
					<input type="text" name="captcha_code" id="captcha_code" required class="form-control" placeholder="Security Code" maxlength="5" value="">
				</div>										
				
				<div class="col-sm-5">
					<div id="captcha_img"><?php echo $captcha_img; ?></div>
					<a href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger">Change Image</a>
				</div>
			</div>
															
			<div class="col-sm-12 text-center">
					<?php //<button type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit">submit </button> &nbsp;&nbsp; ?>
                    
                <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">&nbsp;&nbsp;
					 
                <input type="button" class="btn btn-info" value="Cancel" onclick="window.location='<?php echo base_url('Scribe_form/index');?>'">
			</div>
      </div>
    </section>
    
    
  </form>
</div>

				<?php $this->load->view('scribe_form/inc_footerbar'); ?>
				</section>
			</div>
		</div>		
		
		<?php $this->load->view('scribe_form/inc_footer'); ?>
		
		<script>
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
      
			$(document ).ready( function() 
			{
				$.validator.addMethod("customFileCheck", function(value, element) {
           
	             if ( $("#scanned_vis_imp_cert").get(0).files.length === 0  && $("#scanned_orth_han_cert").get(0).files.length === 0  && $("#scanned_cer_palsy_cert").get(0).files.length === 0 ) {
		            return false;
		           }else{
		            return true;
		           }

		       	 }, "Atleast one disability Certificate must be uploaded");

				$("#usersAddForm").validate(  
				{
					rules:
					{
						<?php if(count($member_info) > 0 && isset($member_info[0]['regnumber'])) { ?> 
						member_no: { required : true },  /* , remote: { url: "<?php echo site_url('ApplyElearning/check_member_no_ajax') ?>", type: "post" } */				
						<?php } ?>
						namesub: { required : true, maxlength:5 },					
						firstname: { required : true, maxlength:50 },				
						//middlename: { maxlength:20 },					
						//lastname: { maxlength:20 },	
						captcha_code: { required : true, remote: { url: "<?php echo site_url('Scribe_form/check_captcha_code_ajax1') ?>", type: "post", data: { "session_name": "LOGIN_SCRIBE_FORM" } } },
            email: 
            { 
              required : true, valid_email: true, maxlength:45, 
              /* <?php if($this->session->has_userdata('session_array')) { } else { ?>
                remote: { url: "<?php echo site_url('ApplyElearning/check_email_exist_ajax') ?>", type: "post", data: { "member_no": function() { return "<?php echo $member_no; ?>" } } } 
              <?php } ?> */ 
            }, 			

			mobile: 
            { 
              required : true, digits:true, minlength: 10, maxlength:10,
             /*  <?php if($this->session->has_userdata('session_array')) { } else { ?>
                remote: { url: "<?php echo site_url('Scribe_form/check_mobile_exist_ajax') ?>", type: "post", data: { "member_no": function() { return "<?php echo $member_no; ?>" } } } 
              <?php } ?>  */
            },				
            /*at least one certificate*/        
            benchmark_disability: 
            { 
              customFileCheck : true,
                     
            },    
			exam_name: { required : true },
			description: { required : true },   		
						  		
					},
			messages:
					{
						member_no: { required : "Please enter Membership/Registration No.", remote : "Please enter valid Membership/Registration No." },
						namesub: { required : "Please select Title" },
						firstname: { required : "Please enter First Name" },
						//middlename: { },
						email: { required : "Please enter Email", maxlength : "Please enter maximum 45 characters in Email", valid_email : "Please enter Valid Email", remote : "Email already exist" },
						mobile: { required : "Please enter Mobile", minlength : "Please enter 10 numbers in Mobile", maxlength : "Please enter 10 numbers in Mobile", remote : "Mobile already exist" },
						exam_name: { required : "Please select Exam" },
						description: { required : "Please Enter Description" },
						captcha_code: { required : "Please enter code", remote:"Please enter valid captcha" }
					},
				submitHandler: function(form) 
	          {
	             form.submit();
	             
	          },
	          errorPlacement: function (error, element)
	            {
	              if(element.attr("name") == "benchmark_disability")
	              {
	                 error.insertAfter("#benchmark_disability_err");
	              }
	             
	            },
	        });
	      });
			
				
		</script>		
		
		<script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
			
		</script>
	</body>
</html>	
<script>

			function refresh_captcha_img()
			{
				$(".loading").show();
				$.ajax(
				{
					type: 'POST',
					url: '<?php echo site_url("Scribe_form/generate_captcha_ajax1/"); ?>',
					data: { "session_name":"LOGIN_SCRIBE_FORM" },
					async: false,
					success: function(res)
					{	
						if(res!='')
						{
							$('#captcha_img').html(res);
							$("#captcha_code").val("");
							$("#captcha_code-error").html("");
						}
						$(".loading").hide();
					}
				});
			}

$(document).ready(function() {
    $("#regnumber").focus();
	var flag = $("#flag").val();
	if(flag == 1){
		$("#regnumber").val('');
		$("#regnumber").prop("readonly", false);
		$("#modify").hide();
		$("#btnGet").show();
	}
});

history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"blended/");
});

$('#Close').click(function(event){
	event.preventDefault();
$("#residential_phone").css("position", "relative");
$("#phone").css("position", "relative");
});


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
	

	 /*$(document).keydown(function(event) {
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
    });*/
	
	$("body").on("contextmenu",function(e){
        return false;
    });
    $(this).scrollTop(0);

});
///////////////////// ID Proof validation //////////////////////

	$( "#idproofphoto" ).change(function() {

		var filesize2=this.files[0].size/1024>300;

		var flag = 1;

		
		var dob_proof_image=document.getElementById('idproofphoto');

		var dob_proof_im=dob_proof_image.value;

		var ext3=dob_proof_im.substring(dob_proof_im.lastIndexOf('.')+1);

		

		if(dob_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG' && ext3!='jpeg' && ext3!='JPEG')

		{

			$('#error_dob').show();

			$('#error_dob').fadeIn(300);	

			document.getElementById('error_dob').innerHTML="Upload JPG or jpeg file only.";

			setTimeout(function(){

			$('#error_dob').css('color','#B94A48');

			 document.getElementById("idproofphoto").value = "";

			 $('#hiddenidproofphoto').val('');

			
			},30);

			flag = 0;

			$(".dob_proof_text").hide();

		}

		

		else if(filesize2)

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
///////// Declaration form validation/////////////

	$( "#declarationform" ).change(function() {
		var filesize2=this.files[0].size/1024>300;
		var flag = 1;
		//$("#p_dob_proof").hide();
		
		var declartion_proof_image=document.getElementById('declarationform');
		var declaration_proof_im=declartion_proof_image.value;
		var ext3=declaration_proof_im.substring(declaration_proof_im.lastIndexOf('.')+1);
		
		if(declartion_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG' && ext3!='jpeg' && ext3!='JPEG')
		{
			$('#error_declaration').show();
			$('#error_declaration').fadeIn(300);	
			document.getElementById('error_declaration').innerHTML="Upload JPG or jpg file only.";
			setTimeout(function(){
			$('#error_declaration').css('color','#B94A48');
			 document.getElementById("declarationform").value = "";
			 $('#hiddendeclarationform').val('');
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".declaration_proof_text").hide();
		}else if(filesize2){
			$('#error_declaration_size').show();
			$('#error_declaration_size').fadeIn(300);	
			document.getElementById('error_declaration_size').innerHTML="File size should be maximum 300KB.";
			setTimeout(function(){
			$('#error_declaration_size').css('color','#B94A48');
			document.getElementById("declarationform").value = "";
			 $('#hiddendeclarationform').val('');
			//$('#error_bussiness_image').fadeOut('slow');
			},30);
			flag = 0;
			$(".declaration_proof_text").hide();
		}

		if(flag=='1')
		{
			$('#error_declaration_size').html('');
			$('#error_declaration').html('');
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
	
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
				reader.onloadend = function(){ // set image data as background of div
				$('#hiddendeclarationform').val(this.result);
				}
			}
			
			 readURL(this,'image_upload_declarationform_preview');
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

</script> 