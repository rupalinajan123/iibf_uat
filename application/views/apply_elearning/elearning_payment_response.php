<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('apply_elearning/inc_header'); ?>
		<style>
			input, select { padding: 4px 5px 4px 5px !important; } 
			.form-horizontal .control-label { line-height:18px; } 
			.content { min-height:auto; }	
			.custom_disp_table { max-width: 400px; margin: 0 auto 20px; font-weight: 600; }
			.custom_disp_table tr td { border: 1px solid #ccc !important; }
		</style>
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('apply_elearning/inc_navbar'); ?>	
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">Transaction <?php echo $transaction_status; ?></h1><br/>
					</section>
					
					<div class="box box-info" style="padding: 0 10px 0 10px;">
						<?php if($transaction_status == 'success') { ?> <h4 class='text-center'>Your transaction details are forwarded to your registered e-mail id.</h4> <?php } ?>
						
						<form class="form-horizontal" autocomplete="off">
							<input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>" autocomplete='false'>
							
							<table class='table table-bordered custom_disp_table'>
								<tbody>
									<?php if($disp_member_no != "") { ?><tr><td>Membership / Registration No.</td><td><?php echo $disp_member_no; ?></td></tr><?php } ?>
									<tr><td>Transaction Status</td><td><?php echo $transaction_status; ?></td></tr>
									<tr><td>Transaction Number</td><td><?php if(isset($payment_info[0]['transaction_no'])) { echo $payment_info[0]['transaction_no']; } ?></td></tr>
									<tr><td>Transaction Date</td><td><?php if(isset($payment_info[0]['date'])) { echo $payment_info[0]['date']; } ?></td></tr>
								</tbody>
							</table>
							<?php 
								if ($login_type == 'sbi') {
									$backUrl = site_url('ApplyElearning/applyExam');	
								}
								else {
								 	$backUrl = site_url('ApplyElearning');	
								} 
							?>
							<div class='text-center'><a class="btn btn-info" href="<?php echo $backUrl; ?>">Back to Home</a></div>							
						</form>
					</div>				
					
					<?php $this->load->view('apply_elearning/inc_footerbar'); ?>
				</section>
			</div>
		</div>		
		
		<?php $this->load->view('apply_elearning/inc_footer'); ?>
		<script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
			/* $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); }); */
		</script>
		
	</body>
</html>	