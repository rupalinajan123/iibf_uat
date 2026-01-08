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

<table style="border:1px solid #1287C0;" width="850px" align="center" border="0" cellpadding="5" cellspacing="0">
	<tr> 
		<td style="text-align: center;">
		    <form method = "post"> 
            	<input type = "submit" name = "submit_excel" Value = "Export To Excel">
            </form>
        </td>
	</tr>
	<tr height='70'>
		<td align='center'>	
			<table width="80%" border="1" align="center" cellpadding="5" cellspacing="" style="border-collapse:collapse">
				<tr>
	  				<td width="10%" align="center">Sr. No.</td>
                    <td width="25%" align="center">Member Name</td>
                    <td width="25%" align="center">Member Number</td>
                    <td width="25%" align="center">Employee ID </td>
                    <td width="25%" align="center" colspan="2">Exam Code</td>
                    <td width="25%" align="center" colspan="2">Exam Period</td>
                    <td width="25%" align="center" colspan="2">Reapeter</td>
				</tr>
                <?php
                	if(sizeof($result) > 0){
						$i = 1;
						foreach($result as $record){
								$member_reg_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$record['mem_mem_no']),'bank_emp_id');
				?> 
				<tr>
					<td width="10%" align="center"><?php echo $i;?></td>
                    <td width="20%" align="center"><?php echo $record['mam_nam_1'];?></td>
                    <td width="20%" align="center"><?php echo $record['mem_mem_no'];?></td>
                     <td width="20%" align="center"><?php echo $member_reg_data[0]['bank_emp_id'];?></td>
					<td width="30%" align="center" colspan="2"><?php echo $record['exm_cd'];?></td>
                    <td width="30%" align="center" colspan="2"><?php echo $record['exm_prd'];?></td>
                    <td width="30%" align="center" colspan="2">
					<?php
						if($record['reapeter_flag'] == 'Y'){
							echo 'Yes';
						}else{
							echo 'No';
						}
					?>
                    </td>
				</tr>
                <?php $i++; } }else{?>
                <tr>
					<td width="10%" align="center" colspan="5">No recordds Found</td>
				</tr>
                <?php }?>
		 	</table>
		</td>
	</tr>
	<tr> 
		<td style="text-align: center;">
		    <form method = "post"> 
            	<input type = "submit" name = "submit_excel" Value = "Export To Excel">
            </form>
        </td>
	</tr>
</table>

</body>
</html>