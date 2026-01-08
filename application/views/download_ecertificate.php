<?php $this->load->view('common_header'); ?>
<style>
	.main-footer { padding-left: 0; padding-right: 0; }
	.error { color: red; font-weight: 500; line-height: 16px; display: block; margin: 0; font-size: 13px; }
	.main-header {  max-height: unset; }
	.wrapper { width: 100% !important; } 
	.loading { background-color: rgba(0, 0, 0, 0.1); height: 100%; min-height: 100%; position: fixed; text-align: center; top: 0; width: 100%; z-index: 9999; }
	.loading > img { position: absolute; top: 50%; vertical-align: middle; width: 130px; }
</style>
<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
<div class="container">
	<section class="content">
		<section class="content-header">
			<h1 class="register">Download E-Certificate</h1><br />
		</section>

		<?php /*
		<marquee style="color:#F00; font-weight: bold;">Candidates are requested to download the e-certificate using the Microsoft Edge browser.</marquee> */ ?>

		<div class="col-md-12">
			<!-- Horizontal Form -->
			<div class="row">
				<?php //echo validation_errors(); 
				?>
				<?php if ($this->session->flashdata('error') != '') { ?>
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
						<?php echo $this->session->flashdata('error'); ?>
					</div>
				<?php }

				if ($this->session->flashdata('success') != '') { ?>
					<div class="alert alert-success alert-dismissible" id="success_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
						<?php echo $this->session->flashdata('success'); ?>
					</div>
				<?php }

				if ($this->session->flashdata('success_final') != '') 
				{ 
					$explode_msg = explode("###",$this->session->flashdata('success_final'))
					?>
					<div class="alert alert-success alert-dismissible" id="success_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
						<?php echo $explode_msg[0]; ?>
					</div>
				<?php }

				if (validation_errors() != '') { ?>
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
						<?php echo validation_errors(); ?>
					</div>
				<?php }

				if (@$var_errors != '') { ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
						<?php echo $var_errors; ?>
					</div>
				<?php } ?>

				<div id="disp_msg_block"></div>

				<div class="box box-info">
					<form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" action="<?php echo base_url(); ?>Download_ecertificate/getMemberDetails">
						<div class="form-group">
							<label for="roleid" class="col-sm-4 control-label">Membership/Registration no <span style="color:#F00">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control " id="member_no" name="member_no" data-parsley-type="number" placeholder="Membership/Registration no *" value="<?php echo @$result[0]['regnumber']; ?>" <?php if (!empty($result[0]['regnumber'])) { echo "disabled='disabled'"; } ?> required data-parsley-trigger-after-failure="focusout" autofocus="autofocus">
								<label class="error" id="member_no_error"></label>
								<span class="error"><?php //echo form_error('member_no'); ?></span>
							</div>

							<!--<div class="form-group"></div>-->
							<?php if (!empty($result[0]['regnumber'])) { ?><input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber']; ?>"> <?php } ?>
						</div>
						<div class="form-group m_t_15 hide_for_otp_cls">
							<label for="roleid" class="col-sm-4 control-label">Security Code<span style="color:#F00">*</span></label>
							<div class="col-sm-4">
								<input type="text" name="captcha_code" id="captcha_code" required class="form-control " placeholder="Security Code *" autocomplete="off">
								<label class="error" id="captcha_code_error"></label>
								<span class="error" id="captchaid" style="color:#B94A48;"></span>
							</div>
							
							<div class="col-sm-4">
								<div id="captcha_img"><?php echo $captcha_img; ?></div>
								<a href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger">Change Image</a>
							</div>
						</div>

						<div id="otp_wrapper"></div>

						<div class="box-footer hide_for_otp_cls">
							<div class="form-group">
								<div class="col-sm-4"></div>
								<div class="col-sm-4">
									<button type="button" class="btn btn-info" onclick="send_otp_download_certificate()">Get Details</button>							
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12 text-center"><span style="color:#F00;">Please insert your membership/registration no and click on get details. All required details will get filled in automatically.</span></div>
						</div>
					</form>
				</div>

				<div class="box box-info">
					<div class="box-header with-border " style="background-color: #1287c0;">
						<h3 class="box-title">Note:</h3>
					</div>
					<div class="box-body blue_bg">
						<div class="form-group">
							<div class="col-sm-12">
								<ol style="list-style-type: none;">
									<li> • Candidate can download copy of e-certificate only after the e-certificate is processed, digitally signed and emailed by the Institute. </li>
									<li> • E-certificate wont be available for download in case of any discrepancy found in processing (e.g. photo/signature etc.).</li>
									<li> • For any query click on the link <a href='https://iibf.esdsconnect.com/CmsComplaint' target="_blank">https://iibf.esdsconnect.com/CmsComplaint</a> and submit your Query.<br />(Query Category: 'Examination' , Query Sub-Category: 'Non receipt of Final Certificate').</li>
									<li> • After download candidate are advised to preserve the e-certificate file.</li>
									<li> • Candidate can not download e-certificate more than 3 times.</li>
									<li> • Kindly refer to the below link on steps to verify this digitally signed certificate. <br /><a href="http://iibf.org.in/documents/steps_to_open_and_validate_digitally_signed_certificate.pdf" target="_blank">http://iibf.org.in/documents/steps_to_open_and_validate_digitally_signed_certificate.pdf</a>
									</li>
								</ol>
							</div>
						</div>
					</div>
				</div><br>

				<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet">
				<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
				<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
				<script src="<?php echo base_url(); ?>js/validation.js?<?php echo time(); ?>"></script>

				<script src="<?php echo base_url() ?>js/jquery.validate.min.js"></script>
				<?php $this->load->view('apply_elearning/common_validation_all'); ?>

				<script>
					function refresh_captcha_img() 
					{
						$(".loading").show();
						$.ajax({
							type: 'POST',
							url: '<?php echo site_url("Download_ecertificate/generatecaptchaajax/"); ?>',
							data: { },
							async: false,
							success: function(res) {
								if (res != '') 
								{
									$('#captcha_img').html(res);
									$("#captcha_code").val("");
									$("#verify_otp").val("");
									$("#captcha_code-error").html("");
								}
								$(".loading").hide();
							}
						});
					}					

					function send_otp_download_certificate(resend_flag) 
					{
						$(".loading").show();
						$("#disp_msg_block").html('');
						$("#success_id").remove();
						$("#error_id").remove();
						$("#member_no_error").html('');
						$("#captcha_code_error").html('');

						member_no = $.trim($("#member_no").val());
						captcha_code = $.trim($("#captcha_code").val());						
						
						var parameters = { "member_no": member_no, "captcha_code": captcha_code, "resend_flag" : resend_flag }

						$.ajax(
							{
							type: "POST",
							url: "<?php echo site_url('Download_ecertificate/send_otp_ajax'); ?>",
							data: parameters,
							cache: false,
							dataType: 'JSON',
							success: function(data) 
							{
								if (data.flag == "success") 
								{
									$("#member_no").attr('readonly',true);
									$("#disp_msg_block").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.response + '</div>');
									scroll_to_msg();
									$(".hide_for_otp_cls").remove();
									$("#otp_wrapper").html(data.verify_otp_block);
									$("#verify_otp").focus();
									resend_countdown(data.sec);
								} 
								else 
								{
									response_member_no = data.response_member_no
									response_captcha_code = data.response_captcha_code
									if(response_member_no == "" && response_captcha_code == "")
									{
										$("#disp_msg_block").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.response + '</div>');
										refresh_captcha_img();
										scroll_to_msg();
									}
									else
									{
										$("#disp_msg_block").html('');
										$("#member_no_error").html(response_member_no);
										$("#captcha_code_error").html(response_captcha_code);

										if(response_captcha_code != "") { $("#captcha_code").focus(); }
										if(response_member_no != "") { $("#member_no").focus(); }
									}
								}

								$(".loading").hide();
							},
							error: function() 
							{
								$("#disp_msg_block").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error occurred.</div>');
								$(".loading").hide();
								scroll_to_msg();
							},
						});
					}

					function validate_otp_certificate_ajax()
					{
						$(".loading").show();
						$("#disp_msg_block").html('');
						$("#success_id").remove();
						$("#error_id").remove();
						$("#member_no_error").html('');
						
						member_no = $.trim($("#member_no").val());
						otp_code = $.trim($("#verify_otp").val());						
						
						var parameters = { "member_no": member_no, "otp_code": otp_code }

						$.ajax(
							{
							type: "POST",
							url: "<?php echo site_url('Download_ecertificate/validate_otp_certificate_ajax'); ?>",
							data: parameters,
							cache: false,
							dataType: 'JSON',
							success: function(data) 
							{
								if (data.flag == "success") 
								{
									$("#verify_otp_error").html('');
									$("#usersAddForm").submit();
								} 
								else 
								{
									$("#verify_otp_error").html(data.response);
								}

								$(".loading").hide();
							},
							error: function() 
							{
								$("#disp_msg_block").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error occurred.</div>');
								$(".loading").hide();
								scroll_to_msg();
							},
						});
					}
					

					function scroll_to_msg() 
					{
						$('html, body').animate({
							scrollTop: $("body").offset().top
						}, 500);
					}

					function convert(value) 
					{
						return Math.floor(value / 60) + ":" + (value % 60 ? value % 60 : '00')
					}

					function resend_countdown(timer2) 
					{
						var timer2 = convert(timer2);
						// console.log('timer2-1: ' + timer2);
						var interval2 = setInterval(function() 
						{
							// console.log('timer2-2: ' + timer2);
							var timer = timer2.split(':');
							//by parsing integer, I avoid all extra string processing
							var minutes = parseInt(timer, 10);
							var seconds = parseInt(timer[1], 10);
							--seconds;
							minutes = (seconds < 0) ? --minutes : minutes;
							if (minutes < 0) 
							{
								clearInterval(interval2);
								$('#mobile_timer').html("");
								$('.resend_otp').show();
							} 
							else 
							{
								$('.resend_otp').hide();
								seconds = (seconds < 0) ? 59 : seconds;
								seconds = (seconds < 10) ? '0' + seconds : seconds;
								minutes = (minutes < 10) ? minutes : minutes;
								$('#mobile_timer').html("Resend OTP in " + minutes + ':' + seconds);
								timer2 = minutes + ':' + seconds;
							}
						}, 1000);
					}
					
					/* $(document).ready(function() {
						$('#new_captcha').click(function(event) {
							event.preventDefault();
							$.ajax({
								type: 'POST',
								url: site_url + 'Download_ecertificate/generatecaptchaajax/',
								success: function(res) {
									if (res != '') {
										$('#captcha_img').html(res);
									}
								}
							});
						});

					}); */

					function validateForm(form) {
						//var 
						var member_no = document.getElementById('member_no').value;
						//alert(member_no);
						if (member_no == "") {
							alert('First get details of member and then submit');
							document.getElementById("member_no").focus();
							return false;
						}
					}
				</script>

				<?php if ($this->session->flashdata('success_final') != '')
				{	?>
				<script type="text/javascript">
					DownloadFile('<?php echo $explode_msg[1]; ?>')
					
					function DownloadFile(fileName) {
						//Set the File URL.
						var url = "uploads/ecertificate/" + fileName;
			
						$.ajax({
							url: url,
							cache: false,
							xhr: function () {
								var xhr = new XMLHttpRequest();
								xhr.onreadystatechange = function () {
									if (xhr.readyState == 2) {
										if (xhr.status == 200) {
											xhr.responseType = "blob";
										} else {
											xhr.responseType = "text";
										}
									}
								};
								return xhr;
							},
							success: function (data) {
								//Convert the Byte Data to BLOB object.
								var blob = new Blob([data], { type: "application/octetstream" });
			
								//Check the Browser type and download the File.
								var isIE = false || !!document.documentMode;
								if (isIE) {
									window.navigator.msSaveBlob(blob, fileName);
								} else {
									var url = window.URL || window.webkitURL;
									link = url.createObjectURL(blob);
									var a = $("<a />");
									a.attr("download", fileName);
									a.attr("href", link);
									$("body").append(a);
									a[0].click();
									$("body").remove(a);
								}
							}
						});
					};
				</script>
				<?php } ?>
				<?php $this->load->view('common_footer'); ?>