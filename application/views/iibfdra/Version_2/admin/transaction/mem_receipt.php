<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Welcome to Indian Institute of Banking &amp; Finance</title>

<SCRIPT type="text/javascript">
    window.history.forward();
    function noBack() { window.history.forward(); }
</SCRIPT>

</head>

<body onselectstart="return false" ondragstart="return false" oncontextmenu="return false;" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

<?php if(!empty($txn_details) && !empty($mem_details)) { ?>

<table style="border:1px solid #1287C0;" width="850px" align="center" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td></td>
	</tr>

	<tr height='70'>
		<td align='center'>	
			<table border='1' cellspacing='0' cellpadding='5' width='65%' bgcolor='#FFFFCC' style="border-collapse:collapse">
    			<tr>
    				<td width='100%' colspan='2'>
                    	<p>Dear Sir/Madam, <br /><br /> We acknowledge with thanks the receipt of the payment for DRA Application as per the details given below:</p>
                    </td>
  				</tr>
  				<tr>
                	<td width='100%' colspan='2'><b>Payment details :</b></td>
                </tr>
  				<tr>
    				<td width='35%'><p><strong>Registration Number: </strong></p></td>
    				<td width='64%'><?php echo $mem_details['regnumber']; ?></td>
  				</tr>
  				<tr>
    				<td width='35%'><p><strong>Member Name : </strong></p></td>
    				<td width='64%'><?php echo $mem_details['firstname'] . " " . $mem_details['lastname']; ?></td>
  				</tr>
                <tr>
                	<td width='35%'><p><strong>Email Id : </strong></p></td>
                  	<td width='64%'><?php echo $mem_details['email']; ?></td>
                </tr>
                <tr>
                	<td width='35%'><p><strong>Transaction ID:</strong></p></td>
                	<td width='64%'><?php if($txn_details['gateway'] == 1) { echo $txn_details['UTR_no']; } else { echo $txn_details['transaction_no']; } ?></td>
              	</tr>
                <tr>
                	<td width='35%'><p><strong>Amount:</strong></p></td>
                  	<td width='64%'><?php echo $mem_details['exam_fee']; ?></td>
                </tr>
                <tr>
                	<td width='35%'><p><strong>Transaction Status: </strong></p></td>
                  	<td width='64%'><p><?php if($txn_details['status'] == 1) { echo "Success"; } else { echo "Failure"; } ?></p></td>
                </tr>
                <tr>
                	<td width='35%'><p><strong>Transaction Date :</strong> </p></td>
                	<td width='64%'><?php echo $txn_details['date']; ?></td>
                </tr>

   				<tr>
    				<td width='100%' colspan='2'><b>Kindly note,<b><br />
                        <ul>
                            <li>The fees once paid will not be refunded.</li>
                            <li>IIBF reserves the right to accept or reject your Enrollment in DRA Application without assigning any reason what so ever.</li>
                            <li>IIBF will process  application based on the information provided and in case, any of the information found false or incorrect your Enrollment in DRA Application is liable for cancellation.</li>
                        </ul>
					</td>
  				</tr>
  
			</table>
			<p>IIBF Team</p>  
		</td>
	</tr>

	<tr>
    	<td></td>
    </tr>

</table>

<?php } ?>

</body>
</html>