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

<div class="container">
  <section class="content-header box-header with-border" style="background-color: #1287C0; padding:16px 10px;">
    <h1 class="register" style="padding:0;">Request registration form.<span style='display: block;font-size: 16px;line-height: 18px; margin: 6px 0 0 0; '>Candidates affected with COVID-19 during the exam time and could not take the exam(JAIIB/DB&F/SOB/CAIIB/CAIIB Electives examinations)</span></h1>    
	</section>
  <div> 
    <!-- Start Get Details -->
		<?php
			if (!empty($row)) {
				if (isset($row['msg']) && $row['msg'] != '') {
					echo '<div class="alert alert-danger alert-dismissible">' . $row['msg'] . '</div>';
				}
			}
			
		?>
    
	</div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style="">
        <?php if ($this->session->flashdata('flsh_msg') != '') {?>
					<div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
				<?php }?>
        <?php
					if ($this->session->flashdata('error') != '') {?>
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('error');?> </div>
					<?php } if ($this->session->flashdata('success') != '') {?>
					<div class="alert alert-success alert-dismissible" id="success_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('success');?> </div>
					<?php } if (validation_errors() != '') { ?>
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo validation_errors(); ?> </div>
					<?php } if ($var_errors != '') { ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $var_errors; ?> </div>
				<?php } ?>
        <form name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>Reschedule">
          <br />
          <?php if(validation_errors() != ''){ ?>
          	<input type="hidden" id="flag" value="1" />
					<?php  }?>
          <div class="">
            <div for="roleid" class="col-sm-5 control-label" style="text-align: right; width:35%;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
            
            <div class="col-sm-4" style="width: 32%;text-align: left;">
              <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No." required value="<?php if (isset($row['regnumber'])) { echo $row['regnumber'];} else { echo set_value('regnumber'); }
							?>" <?php if (isset($row['regnumber'])) { echo "readonly='readonly'";} elseif (set_value('regnumber')) { echo "readonly='readonly'"; } ?> style="border-color:#000;" title="Membership No.">
						</div>
            
            <div class="col-sm-3" style="padding-bottom: 10px">
              <?php if (isset($row['regnumber']) || set_value('regnumber')) { ?>
								<a href="<?php echo base_url();?>Reschedule" class="btn btn-info" id="modify" style="height: 32px; width: 150px">Modify</a>
								<input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGet" value="Get Details" style="height: 32px; width: 150px; font-size:15px; display:none;">
								<?php } else{ ?>
								<input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGetDetails" value="Get Details" style="height: 32px; width: 150px; font-size:15px;">
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
  <br />
  <!-- Close Get Details-->
  
  <?php 
		
		if(isset($result[0]['mem_mem_no']) && $result[0]['mem_mem_no'] != ''){?>
		
		<form class="form-horizontal" name="RescheduleForm" id="RescheduleForm"  method="post"  enctype="multipart/form-data">
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						
						
						<!-- Horizontal Form -->
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Basic Details</h3>
							</div>
							<div class="box-body">
								
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Candidate Name:</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="candidate_name" name="candidate_name"  required value="<?php if(isset($result[0]['mam_nam_1'])){echo $result[0]['mam_nam_1'];} ?>" readonly="readonly" style="max-width:300px;">
										<input type="hidden" name="mem_mem_no" value="<?php if(isset($result[0]['mem_mem_no'])){echo $result[0]['mem_mem_no'];} ?>"> 
									</div>
								</div>
								
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Exam Name:</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="exam_name" name="exam_name"  required value="<?php if (isset($exam_name)) {echo $exam_name;}?>" readonly="readonly" style="max-width:300px;">
										<input type="hidden" name="exm_cd" value="<?php if(isset($result[0]['exm_cd'])){echo $result[0]['exm_cd'];} ?>">
									</div>
								</div>
								
								<?php 
									if(isset($compulsory_subjects) && count($compulsory_subjects) > 0 && $compulsory_subjects!='')
									{
										$cnt=1;
										foreach($compulsory_subjects as $el_subject)
										{
											$this->db->where('subject_code',$el_subject['sub_cd']);
											$sql = $this->master_model->getRecords('subject_master','','subject_description,exam_date');
										?>									
										<div class="form-group show_el_subject" >
											<label class="col-sm-4 control-label"><?php echo $el_subject['sub_dsc']?><span style="color:#F00">*</span></label>
											<div class="col-sm-8">
												<input type="checkbox" name="el_subject[<?php echo $el_subject['sub_cd']?>]" value="Y" class="el_sub_prop" data-parsley-mincheck="1" />
												<?php echo "( ". $el_subject['exam_date']." )";?>
											</div>
											<?php if($cnt == count($compulsory_subjects)) { echo '<div class="clearfix"></div><p style="text-align: center;	font-weight: 600;	margin: 0;	color: red;"><small>Tick [√] on the exam/subject which you could not take/appear due to COVID infection.</small></p>'; } ?>
										</div>						
										<?php $cnt++;
										} 
									}	?>
									
									
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Center Name:</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="center_name" name="center_name"  required value="<?php if (isset($result[0]['center_name'])) {echo $result[0]['center_name'];}?>" readonly="readonly" style="max-width:300px;">
											<input type="hidden" name="center_code" value="<?php if(isset($result[0]['center_code'])){echo $result[0]['center_code'];} ?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Contact Number:<span style="color:#F00">*</span></label>
										<div class="col-sm-8">
											<input type="tel" class="form-control" id="contact_no" name="contact_no"  required value="" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  maxlength="10" size="10" data-parsley-bnqmobilecheck style="max-width:300px;">
											
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Email ID:<span style="color:#F00">*</span></label>
										<div class="col-sm-8">
											<input type="email" class="form-control" id="candidate_email" name="candidate_email"  required value="" data-parsley-bnqemailcheck style="max-width:300px;">
										</div>
									</div>
									
									
									<div class="form-group" >
										<label class="col-sm-4 control-label">PwD Disability<span style="color:#F00">*</span></label>
										<div class="col-sm-8">
											<select class="form-control" id="disability" name="disability" required="" style="max-width:300px;">
                    		<option value="">Select</option>
												<option value="No">No</option>
												<option value="Yes">Yes</option>
											</select>
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Covid Certificate:<span style="color:#F00">*</span></label>
										<div class="col-sm-8">
											<input type="file" class="form-control" id="covid_certificate" name="covid_certificate"  required  style="max-width:300px;">
											<input type="hidden" id="hiddenphoto" name="hiddenphoto" />
											<div id="error_photo"></div>
											<div id="error_photo_size"></div>
											<span class="photo_text" style="display:none;"></span> <span class="error"></span>
											<img id="image_upload_scanphoto_preview" height="100" width="100" style="display:none"/>
											<p style="line-height: 16px;margin: 3px 0 0 0;color: red;font-weight: 600;"><small>Upload the relevant COVID medical certificate duly attested by your Branch/Bank Manager with Bank seal/stamp affixed on it. DB&amp;F Candidates should attest the relevant COVID medical certificate by Gazetted officer.<span style="display:block; margin-top:10px;">Note : Upload only pdf file having size less than 1MB</span></small></p>
										</div>
									</div>
							</div>
							<!-- Basic Details box closed-->
						</div>
						<div class="box box-info">
							<div class="box-header with-border"> &nbsp; </div>
							<div class="form-group m_t_15">
								<label for="roleid" class="col-sm-4 control-label">Security Code&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
								<div class="col-sm-3"> 
									<!-- <input type="text" name="code" id="code" required class="form-control" <?php if (!isset($row['regnumber'])) { echo "readonly='readonly'";}  ?>> -->
									<input type="text" name="code" id="code" required class="form-control" >
									<span class="error" id="captchaid" style="color:#B94A48;"></span> 
								</div>
								<div class="col-sm-5">
									<div id="captcha_img"> <?php echo $image; ?></div>
									<span class="error"> </span>
									<a href="javascript:void(0);" id="reload_captcha" class="forget" >Change Image</a> <span class="error"></span> 
								</div>
							</div>
							<div class="box-footer">
								<div class="col-sm-6 col-sm-offset-4">
									<!--<input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit">-->
									<a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return checkform();" id="preview">Submit</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			
			<div class="modal fade" id="confirm"  role="dialog" >
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"></h4>
						</div>
						<div class="modal-body">
							<p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
							I confirm that all the detail entered are correct as per my knowledge.</p>
						</div>
						<div class="modal-footer"> 
							<!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
							<input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm" >
						</div>
					</div>
					<!-- /.modal-content --> 
				</div>
				<!-- /.modal-dialog --> 
			</div>
			
			<!-- /.modal-dialog -->
			
		</form>
		
	<?php }?>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script>
	$(document).ready(function() {
    $("#regnumber").focus();
		var flag = $("#flag").val();
		if(flag == 1){
			$("#regnumber").val('');
			$("#regnumber").prop("readonly", false);
			$("#modify").hide();
			$("#btnGet").show();
		}
		
		
		
		$( "#covid_certificate" ).change(function() 
		{
			var filesize2=this.files[0].size/1024>20;
			
			var flag = 1;
			var file, img;
			$('#error_photo').hide();
			//$('#p_photograph').hide();
			var photograph_image=document.getElementById('covid_certificate');
			//fileUpload[appKey]['photo'] = photograph_image;
			var photograph_im=photograph_image.value;
			var ext1=photograph_im.substring(photograph_im.lastIndexOf('.')+1);
			//if(photograph_image.value!=""&&  ext1!='jpg' && ext1!='JPG' && ext1!='jpeg' && ext1!='JPEG')
			if(photograph_image.value!=""&&  ext1.toLowerCase()!='pdf')
			{
				$('#error_photo').show();
				$('#error_photo').fadeIn(3000);	
				document.getElementById('error_photo').innerHTML="Upload pdf file only.";
				setTimeout(function(){
					$('#error_photo').css('color','#B94A48');
					document.getElementById("covid_certificate").value = "";
					$('#hiddenphoto').val('');
					
					//$('#error_bussiness_image').fadeOut('slow');
				},30);
				flag = 0;
				$(".photo_text").hide();
			}
			
			if((this.files[0].size/1024)>1000)
			{
				$('#error_photo').show();
				$('#error_photo').fadeIn(3000);	
				document.getElementById('error_photo').innerHTML="Upload pdf file having size less than 1MB.";
				setTimeout(function()
				{
					$('#error_photo').css('color','#B94A48');
					document.getElementById("covid_certificate").value = "";
					$('#hiddenphoto').val('');
					
					//$('#error_bussiness_image').fadeOut('slow');
				},30);
				flag = 0;
				$(".photo_text").hide();
			}
			
			if(flag==1)
			{
				var files = !!this.files ? this.files : [];
				//if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
				if (/^image/.test( files[0].type)){ // only image file
					var reader = new FileReader(); // instance of the FileReader
					reader.readAsDataURL(files[0]); // read the local file
					reader.onloadend = function(){ // set image data as background of div
						$('#hiddenphoto').val(this.result);
						$("#image_upload_scanphoto_preview").attr('src',$("#hiddenphoto").val());
					}
				}
			}
			else
			{
				return  false;
			}
		});
		
		
		/*$('input[type=file]').change(function () {
			
			var files = !!this.files ? this.files : [];
			//if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
			if (/^image/.test( files[0].type)){ // only image file
			var reader = new FileReader(); // instance of the FileReader
			reader.readAsDataURL(files[0]); // read the local file
			reader.onloadend = function(){ // set image data as background of div
			$('#hiddenphoto').val(this.result);
			$("#image_upload_scanphoto_preview").attr('src',$("#hiddenphoto").val());
			}
			}
			
		});*/
		
		
		window.Parsley.addValidator('bnqmobilecheck', function (value, requirement) {
			var response = false;
			var msg='';
			var datastring='mobile='+value;
			$.ajax({
				url:site_url+'Reschedule/mobileduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
					if(data.ans!="exists"){
						response = true;
						}else{
						response = false;
					}
				}
			});
			return response;
		}, 33)
		.addMessage('en', 'bnqmobilecheck', 'The mobile number already exists.');
		
		window.Parsley.addValidator('bnqemailcheck', function (value, requirement) {
			//.focus()
			//$("#email_id").focusout();
			var response = false;
			var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
			var datastring='email='+value;
			$.ajax({
				url:site_url+'Reschedule/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
				success: function(data) {
					if(data.ans!="exists"){
						response = true;
						}else{
						return false;
					}
				}
			});
			
			return response;
		}, 32)
		.addMessage('en', 'bnqemailcheck', 'The email id already exists');
		
	});
	
	function checkform(){
		
		
		$('#error_id').html(''); 
		$('#success_id').html(''); 
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		$('#success_id').removeClass("alert alert-danger alert-dismissible");
		$('#tiitle_error').html(''); 
		$('#captchaid').html('');
		
		var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
		
		if(el_subject_cnt == 0){
			alert("Please select atleast one subject");
			return false;
		}
		
		var code=$('#code').val();
		var form_flag=$('#RescheduleForm').parsley().validate();
		
		
		if(code != '' && form_flag){
			$.ajax({
				url: site_url+'Reschedule/ajax_check_captcha',
				type: 'post',
				data:'code='+code+'&random='+Math.random(),
				success: function(result) {
					if(result=='true'){
						$('#confirm').modal('show');
						}else{
						$('#captchaid').html('Enter valid captcha code.');
					}
				}
			});
		}
		
		
		
	}
	
	history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
	window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"blended/");
	});
	
	$('#Close').click(function(event){
		event.preventDefault();
		$("#residential_phone").css("position", "relative");
		$("#phone").css("position", "relative");
	});
	
	$('#reload_captcha').click(function(event){
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: site_url+'blended/generatecaptchaajax/',
			success: function(res)
			{ 
				if(res!='')
				{$('#captcha_img').html(res);
				}
			}
		});
	});
	
	
	$(function(){
		$("body").on("contextmenu",function(e){
			return false;
		});
    $(this).scrollTop(0);
		
	});
	
</script> 