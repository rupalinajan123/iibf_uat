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
				<section class="content-header">
					<h1>
					</h1>
				</section>
				<section class="content">
					<div class="row">
						
						<div class="col-md-12">								
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Transaction Success</h3>
								</div>
								<div class="box-body">	
									<table class="table table-bordered" style="max-width: 600px; margin: 20px auto; ">
										<tbody>
											<tr><td><b>Transaction Number</b></td><td><?php echo $payment_info[0]['transaction_no'];?></td></tr>
											<tr><td><b>Transaction Status</b></td><td><?php if($payment_info[0]['status']=='1'){echo 'Success';}else{echo 'Fail';}?></td></tr>
											<tr><td><b>Transaction Date</b></td><td><?php echo $payment_info[0]['date'];?></td></tr>
											<tr><td><b>Receipt</b></td><td><a href="<?php echo site_url('institute_subscription/receipt/'.base64_encode($payment_info[0]['receipt_no'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">View</a></td></tr>
										</tbody>
									</table>									
								</div>
							</div> 
						</div>
					</div>
				</section>
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