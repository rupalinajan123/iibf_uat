<table style="border: 1px solid rgb(18, 135, 192);" width="850" cellspacing="0" cellpadding="5" border="0" align="center">
<tbody>
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
    	<td valign="top" colspan='2'><a href="<?php echo base_url();?>admin"><img src="<?php echo base_url()?>assets/images/logo1.png" border="0"></a></td>
    </tr>    
</table>
</td>
</tr>

<tr>
<td>
<?php 
//print_r($payment);
if(count($payment)){
	?>  	
<table align="center" border="1" cellpadding="0" cellspacing="0" width="80%" style="background-color:#FFFFCC;">
    <tbody>
        <tr class="tableheader">
            <td colspan="9" align="center" style="background-color:#1287C0;padding:15px;color:#fff;" valign="middle"><b>Payment Details</b>
            </td>
        </tr>
         <tr>  
            <td width='35%' height="35"><p><strong>Membership No. : </strong></p></td>
            <td width='64%'>  <?php echo $payment['regnumber'];?></td>
        </tr>
        <tr>
            <td width='35%' height="35"><p><strong>Member Name : </strong></p></td>
            <td width='64%'><?php echo $payment['firstname']." ".$payment['middlename']." ".$payment['lastname'];?></td>
        </tr>
        <tr>
        <tr>
            <td width='35%' height="35"><p><strong>Amount:</strong></p></td>
            <td width='64%'><?php echo $payment['amount'];?></td>
        </tr>
            <td width='35%' height="35"><p><strong>Address : </strong></p></td>
            <td width='64%'>
                <?php echo $payment['address1'];?>
                <?php if($payment['address2']!=''){ echo ",".$payment['address2'];}?>
                <?php if($payment['address3']!='' || $payment['address4']!=''){ echo "<br>";}?>
                <?php if($payment['address3']!=''){ echo ",".$payment['address3'];}?>
                <?php if($payment['address4']!=''){ echo ",".$payment['address4'];}?>
                <?php echo ",<br>".$payment['district'].",".$payment['city'];?>
                <?php echo ",<br>".$payment['state_name'].",".$payment['pincode'];?>
            </td>
        </tr>  
        <tr>
            <td width='35%' height="35"><p><strong>Email Id : </strong></p></td>
            <td width='64%'><?php echo $payment['email'];?></td>
        </tr>
        <tr>
            <td width='35%' height="35"><p><strong>Transaction No:</strong></p></td>
            <td width='64%'><?php echo $payment['transaction_no'];?></td>
        </tr>  
        <tr>
            <td width='35%' height="35"><p><strong>Transaction Status: </strong></p></td>
            <td width='64%'><p>
				<?php 	
						if($payment['status'] == 0)
							echo "Failure";
						else if($payment['status'] == 1)
							echo "Success";
						else if($payment['status'] == 2)
						 	echo "Pending";
						else
							echo "--"; 
				?>
                
            </p></td>
        </tr>
        <tr>
            <td width='35%' height="35"><p><strong>Transaction Date :</strong> </p></td>
            <td width='64%'><?php echo date('d-m-Y h:i:s A',strtotime($payment['date']));?></td>
        </tr>
	</tbody>
</table>
<?php } ?>
</td>
</tr>
</tbody>
</table>