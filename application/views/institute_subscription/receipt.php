<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>IIBF - Institute Subscription Receipt</title>		
		<SCRIPT type="text/javascript">
			window.history.forward();
			function noBack() { window.history.forward(); }
		</SCRIPT>		
	</head>
	
	<body onselectstart="return false" ondragstart="return false" oncontextmenu="return false;" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">		
		<table style="border:1px solid #1287C0;" width="850px" align="center" border="0" cellpadding="5" cellspacing="0">
			<tr><td></td></tr>
			
			<tr height='70'>
				<td align='center'>	
					<table border='1' cellspacing='0' cellpadding='5' width='65%' bgcolor='#FFFFCC' style="border-collapse:collapse">
						<tr>
							<td width='100%' colspan='2'>
								<p>Dear Institute <span style="font-size: 14px; text-transform: capitalize; ">(<?php echo strtolower($payment_data[0]['institute_name']); ?>)</span>, <br /><br /> We acknowledge with thanks the receipt of the payment for Institute Subscription as per the details given below:</p>
							</td>
						</tr>
						<tr>
							<td width='100%' colspan='2' style="text-align:center;"><b style="padding: 5px 0; display: block; ">Payment details</b></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Institute No : </strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['institute_no']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Invoice No : </strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['invoice_no']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Institute Name : </strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['institute_name']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Subscription Year : </strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['subscription_year']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Payment Type : </strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['gateway']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Transaction ID:</strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['transaction_no']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Amount:</strong></p></td>
							<td width='64%'><?php echo $payment_data[0]['amount']; ?></td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Transaction Status: </strong></p></td>
							<td width='64%'>
								<p>
									<?php if($payment_data[0]['PaymentStatus'] == 0 || $payment_data[0]['PaymentStatus'] == 7) { echo 'Fail'; }
										else if($payment_data[0]['PaymentStatus'] == 1) { echo 'Success'; }
										else if($payment_data[0]['PaymentStatus'] == 2) { echo 'Pending'; }
									else if($payment_data[0]['PaymentStatus'] == 3) { echo 'Refund'; } ?>
								</p>
							</td>
						</tr>
						<tr>
							<td width='35%'><p><strong>Transaction Date :</strong> </p></td>
							<td width='64%'><?php echo $payment_data[0]['date']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr><td></td></tr>			
		</table>		
	</body>
</html>		