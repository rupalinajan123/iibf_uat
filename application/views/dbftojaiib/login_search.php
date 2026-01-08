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

	.content-header>h1 {
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

	/*
	* OTP SECTION CSS start (by sagar walzade)
	*/
	.otp_wrapper {
		margin-bottom: 20px;
		border: 1px solid #00c0ef3b;
		border-radius: 4px;
		width: 100%;
		float: left;
	}

	.mb20 {
		margin-bottom: 20px;
	}

	.otp_wrapper .form-group {
		background-color: unset !important;
	}

	.otp_wrapper_header {
		background-color: #dcf1fc;
		padding: 15px;
	}

	.otp_wrapper_header>h3 {
		margin: 0;
	}

	.otp_wrapper_header>p {
		margin-bottom: 0;
	}

	.otp_wrapper_body {
		padding: 15px;
		width: 100%;
		float: left;
	}

	.otp_wrapper_body>.form-group {
		border-bottom: 1px solid #efefef;
		margin-bottom: 15px;
	}

	.verify_otp_btn {
		margin-right: 15px;
	}

	.ajax_loader {
		border: 3px solid #fff;
		border-top: 3px solid #fff0;
		border-radius: 50%;
		width: 17px;
		height: 17px;
		animation: spin 2s linear infinite;
		float: right;
		margin-left: 9px;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	.otp_wrapper {
		display: none;
	}

	.hide_loader {
		display: none;
	}

	.bottom_note {
		float: left;
		width: 100%;
		padding: 5px;
		display: block;
		margin-top: 15px;
		background-color: #f2f2f2;
	}

	/*
	* OTP SECTION CSS end (by sagar walzade)
	*/
</style>
<div class="container">
	<section class="content-header">
		<h1 class="register">DBF to JAIIB Conversion</h1><br />
	</section>
	<span class="error">
		<?php
		echo validation_errors();
		?>
	</span>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" method="post">
					<!-- Search form -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Login</h3>
						</div>
						<div class="box-body">
							<?php if ($this->session->flashdata('error')) { ?>
								<div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<?php echo $this->session->flashdata('error') ?>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Enter Ordinary membership<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="searchStr" name="searchStr" value="<?php echo set_value('searchStr'); ?>" required maxlength="15">
								</div>
								<div class="col-sm-3">
									<input type="hidden" name="form_type" value="search_form" />
									<input type="submit" name="submit" value="Search" />
								</div>
							</div>
						</div>
					</div>
					<!-- Search form Box close -->
				</form>

				<!-- Candidate Details -->
				<?php if (!empty($aCandidate)) { ?>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Candidate Details</h3>
						</div>

						<table class="table table-bordered">
							<thead>
								<tr>
								
									<?php if(isset($aCandidate['regnumber'])){echo '<th>Registration No</th>';} ?>
									<th>Name</th>
									<th>Email Id</th>
									<th>Mobile No</th>
								</tr>
							</thead>
							
								<tbody>
									<tr>
										
										<?php if(isset($aCandidate['regnumber'])){echo '<td>'.$aCandidate['regnumber'].'</td>';} ?>
										<td><?php echo $aCandidate['firstname']; ?></td>
										<td><?php echo $aCandidate['email']; ?></td>
										<td><?php echo $aCandidate['mobile']; ?></td>
									</tr>
								</tbody>
							
						</table>
						<div class="col-md-12 text-center mb20">
							<button type="button" class="btn btn-info send_otp" name="send_otp" id="send_otp" data-firstname="<?php echo $aCandidate['firstname']; ?>" data-regnumber="<?php echo $aCandidate['regnumber']; ?>" data-email="<?php echo $aCandidate['email']; ?>" data-mobile="<?php echo $aCandidate['mobile']; ?>">Send OTP <div class="ajax_loader hide_loader"></div></button>
						</div>
						<div class="col-md-12">
							<div class="otp_wrapper">
								<div class="otp_wrapper_header text-center">
									<h3>Verify OTP</h3>
								</div>
								<div class="otp_wrapper_body">
									<div class="ajax_message"></div>
									<div class="form-group ">
										<label for="pwd">Email / Mobile OTP:</label>
										<input style="width:97%;" type="password" class="" id="verify_otp_input" placeholder="Enter the OTP Received on Registered Email / Mobile Number" autocomplete="new-password">
										<i class="fa fa-eye"></i>
										<span class="help-block ">Otp will expire after 30 minutes. (Please do not share OTP with anyone)</span>
									</div>
									<input type="hidden" class="otp_id" name="otp_id" value="">
									<input type="hidden" class="resend_regnumber" name="resend_regnumber" value="<?php echo $aCandidate['regnumber']; ?>">
									<input type="hidden" class="resend_firstname" name="resend_firstname" value="<?php echo $aCandidate['firstname']; ?>">
									<input type="hidden" class="resend_email" name="resend_email" value="<?php echo $aCandidate['email']; ?>">
									<input type="hidden" class="resend_mobile" name="resend_mobile" value="<?php echo $aCandidate['mobile']; ?>">
									<button type="button" class="btn btn-info verify_otp_btn">Submit OTP</button> <span id="mobile_timer"></span> <a class="resend_otp resend_hide" href="javascript:void(0);"> Click here to resend OTP</a>
									<!-- <span class="bottom_note">Note : please use <b>111111</b> otp until we integrate sms gateway.</span> -->
								</div>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">No Candidate available</h3>
						</div>
					</div>
				<?php } ?>
				<!-- Candidate Details Box close -->
			</div>
		</div>
	</section>
</div>
<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>

<script>
	$(document).ready(function() {
		const passwordField = document.getElementById("verify_otp_input");
		const togglePassword = document.querySelector("i.fa-eye");

		togglePassword.addEventListener("click", function () {
		if (passwordField.type === "password") {
			passwordField.type = "text";
			togglePassword.classList.remove("fa-eye");
			togglePassword.classList.add("fa-eye-slash");
		} else {
			passwordField.type = "password";
			togglePassword.classList.remove("fa-eye-slash");
			togglePassword.classList.add("fa-eye");
		}
		});
		$('input[name="searchStr"]').keyup(function(e)
                                {
			if (/\D/g.test(this.value))
			{
			// Filter non-digits from input value.
			this.value = this.value.replace(/\D/g, '');
			}
			});
		$('.send_otp').on('click', function() {
			$(".ajax_message").html("");
			$('.send_otp.ajax_loader').removeClass('hide_loader');
			var regnumber = $(this).data('regnumber');
			var firstname = $(this).data('firstname');
			var email = $(this).data('email');
			var mobile = $(this).data('mobile');
			$.ajax({
				url: '<?php echo base_url(); ?>Dbftojaiib/send_otp',
				type: 'post',
				dataType: "json",
				data: {
					regnumber: regnumber,
					firstname: firstname,
					email: email,
					mobile: mobile,
				},
				success: function(data) {
					if (data.status == 'success') {
						$('.send_otp').hide();
						$('.otp_wrapper').show();
						$('.otp_id').val(data.inserted_id);
						resend_countdown(data.sec);
					} else {
						$('.ajax_message').html('<div class="alert alert-danger">Request failed</div>');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					var errorMsg = 'Ajax request failed: ' + xhr.responseText;
					// console.log('ddd' + errorMsg);
				}
			});
		});

		$('.resend_otp').on('click', function() {
			$(".ajax_message").html("");
			$('#verify_otp_input').val("");
			var otp_id = $('.otp_id').val();
			var regnumber = $('.resend_regnumber').val();
			var firstname = $('.resend_firstname').val();
			var email = $('.resend_email').val();
			var mobile = $('.resend_mobile').val();
			$.ajax({
				url: '<?php echo base_url(); ?>Dbftojaiib/resend_otp',
				type: 'post',
				dataType: "json",
				data: {
					otp_id: otp_id,
					regnumber: regnumber,
					firstname: firstname,
					email: email,
					mobile: mobile,
				},
				success: function(data) {
					if (data.status == 'success') {
						console.log(data);
						resend_countdown(data.sec);
					} else {
						$('.ajax_message').html('<div class="alert alert-danger">Request failed</div>');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					var errorMsg = 'Ajax request failed: ' + xhr.responseText;
					// console.log('ddd' + errorMsg);
				}
			});
		});

		$('.verify_otp_btn').on('click', function() {
			$(".ajax_message").html("");
			var verify_otp_input = $('#verify_otp_input').val();
			if (verify_otp_input == '') {
				$('.ajax_message').html('<div class="alert alert-danger">Please enter otp!!!</div>');
				return false;
			}
			var otp_id = $('.otp_id').val();
			$.ajax({
				url: '<?php echo base_url(); ?>Dbftojaiib/verify_otp',
				type: 'post',
				dataType: "json",
				data: {
					submitted_otp: verify_otp_input,
					otp_id: otp_id
				},
				success: function(data) {
					if (data.status == 'success') {
						console.log(data);
						$('.ajax_message').html('<div class="alert alert-success">' + data.msg + '</div>');
						setTimeout(function() {
							window.location.href = "<?php echo base_url(); ?>Dbftojaiib/candidate_details/<?php echo base64_encode($aCandidate['regnumber']); ?>";
						}, 1000);
					} else {
						$('.ajax_message').html('<div class="alert alert-danger">' + data.msg + '</div>');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					var errorMsg = 'Ajax request failed: ' + xhr.responseText;
					// console.log('ddd' + errorMsg);
				}
			});
		});
	});

	function convert(value) {
		return Math.floor(value / 60) + ":" + (value % 60 ? value % 60 : '00')
	}

	function resend_countdown(timer2) {
		var timer2 = convert(timer2);
		// console.log('timer2-1: ' + timer2);
		var interval2 = setInterval(function() {
			// console.log('timer2-2: ' + timer2);
			var timer = timer2.split(':');
			//by parsing integer, I avoid all extra string processing
			var minutes = parseInt(timer, 10);
			var seconds = parseInt(timer[1], 10);
			--seconds;
			minutes = (seconds < 0) ? --minutes : minutes;
			if (minutes < 0) {
				clearInterval(interval2);
				$('#mobile_timer').html("");
				$('.resend_otp').show();
			} else {
				$('.resend_otp').hide();
				seconds = (seconds < 0) ? 59 : seconds;
				seconds = (seconds < 10) ? '0' + seconds : seconds;
				minutes = (minutes < 10) ? minutes : minutes;
				$('#mobile_timer').html("Resend OTP in " + minutes + ':' + seconds);
				timer2 = minutes + ':' + seconds;
			}
		}, 1000);
	}

	//var timer2 = "00:05";
	// var timer2 = convert(<?php echo $resend_time; ?>);
	// var interval = setInterval(function() {
	// 	var timer = timer2.split(':');
	// 	//by parsing integer, I avoid all extra string processing
	// 	var minutes = parseInt(timer, 10);
	// 	var seconds = parseInt(timer[1], 10);
	// 	--seconds;
	// 	minutes = (seconds < 0) ? --minutes : minutes;
	// 	if (minutes < 0) {
	// 		clearInterval(interval);
	// 		$('#mobile_timer').html("");
	// 		$('.resend_otp').show();
	// 	} else {
	// 		$('.resend_otp').hide();
	// 		seconds = (seconds < 0) ? 59 : seconds;
	// 		seconds = (seconds < 10) ? '0' + seconds : seconds;
	// 		//minutes = (minutes < 10) ?  minutes : minutes;
	// 		$('#mobile_timer').html("Resend OTP in " + minutes + ':' + seconds);
	// 		timer2 = minutes + ':' + seconds;
	// 	}
	// }, 1000);
</script>