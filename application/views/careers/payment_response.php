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
						<?php if ($transaction_status=='success'): ?>
						<div class="row">
            <div class="col-md-12" align="center">
              <h4>Dear Candidate</h4>
              <h4>Thank you for applying at IIBF <br />Your Job Application has been successfully submitted.</h4>
            </div>
          </div>
          <?php endif ?>
						<?php if($transaction_status == 'success') { ?> <h4 class='text-center'>Your transaction details are forwarded to your registered e-mail id.</h4> <?php } ?>

						
						<form class="form-horizontal" autocomplete="off">
							<input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>" autocomplete='false'>
							
							<table class='table table-bordered custom_disp_table'>
								<tbody>
						
									<tr><td>Transaction Status</td><td><?php echo $transaction_status; ?></td></tr>
									<?php if ($transaction_status=='success' && $reg_id!=''): ?>
										<tr><td>Registration Number</td><td><?php if(isset($reg_id)) { echo $reg_id; } ?></td></tr>
									<?php endif ?>
									<?php if ($transaction_status!='pending'): ?>
										<tr><td>Transaction Number</td><td><?php if(isset($payment_info[0]['transaction_no'])) { echo $payment_info[0]['transaction_no']; } ?></td></tr>
									<?php endif ?>
									

									<tr><td>Transaction Date</td><td><?php if(isset($payment_info[0]['date'])) { echo $payment_info[0]['date']; } ?></td></tr>
								</tbody>
							</table>
							
							<div class='text-center'><a class="btn btn-info" href='https://iibf.org.in/career.asp'>Back to Home</a></div>							
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