<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('institute_subscription/inc_header'); ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php $this->load->view('institute_subscription/inc_navbar'); ?>			
			
			<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>				
			<div class="content-wrapper">
				<section class="content-header"><h1> </h1></section>
				<form>
					<section class="content">
						<div class="row">
							<div class="col-md-12">  
								<!-- Horizontal Form -->
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title"></h3>
									</div>
									<!-- /.box-header --> 
									<!-- form start -->
									<div class="box-body">
										<ul>
											<li>On successful completion of transaction confirmation SMS/email will be sent to the Institute intimating the receipt of examination forms. </li>
										</ul>
									</div>
								</div>
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">Application fail.</h3>
									</div>
									<!-- /.box-header --> 
									<!-- form start -->
									<div class="box-body">
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Application Fail:</label>
											<div class="col-sm-9"> <strong style="color:#000">Application for Institute Subscription has been fail.</strong> </div>
										</div><br />
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Transaction Number</label>
											<div class="col-sm-3"><?php echo $payment_info[0]['transaction_no']?> </div>
										</div><br />
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Transaction Status:</label>
											<div class="col-sm-1"><?php if($payment_info[0]['status']=='1'){echo 'Success';}else{echo 'Unsuccess';}?> </div>
										</div><br />
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Transaction Date:</label>
											<div class="col-sm-5"><?php echo $payment_info[0]['date'];?> </div>
										</div><br />
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Note:</label>
											<div class="col-sm-5">   <div style="color:#F00">Refund for this application  will be initiated within 7 to 10 days!</div> </div>
										</div><br />
									</div>
								</div>
								<!-- Basic Details box closed--> 
							</div>
						</div>
					</section>
				</form>
			</div>
			
			<?php $this->load->view('institute_subscription/inc_footer_text'); ?>
		</div>
		<?php $this->load->view('institute_subscription/inc_footer'); ?>
		
		<script>
			(function (global) {
				
				if(typeof (global) === "undefined")
				{
					throw new Error("window is undefined");
				}
				
				var _hash = "!";
				var noBackPlease = function () {
					global.location.href += "#";
					
					// making sure we have the fruit available for juice....
					// 50 milliseconds for just once do not cost much (^__^)
					global.setTimeout(function () {
            global.location.href += "!";
					}, 50);
				};
				
				// Earlier we had setInerval here....
				global.onhashchange = function () {
					if (global.location.hash !== _hash) {
            global.location.hash = _hash;
					}
				};
				
				global.onload = function () {
					
					noBackPlease();
					
					// disables backspace on page except on input fields and textarea..
					document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
							e.preventDefault();
						}
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
					};					
				};				
			})(window);
		</script>
	</body>
</html>