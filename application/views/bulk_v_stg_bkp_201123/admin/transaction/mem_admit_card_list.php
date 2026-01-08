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

<?php if(!empty($result)) { ?>

<table style="border:1px solid #1287C0;" width="850px" align="center" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td></td>
	</tr>
	<tr height='70'>
		<td align='center'>	
			<table width="80%" border="1" align="center" cellpadding="5" cellspacing="" style="border-collapse:collapse">
				<tr>
	  				<td width="10%" align="center">Sr. No.</td>
                    <td width="25%" align="center">Registration Number</td>
                   
                    		 <td width="25%" align="center">Employee ID</td> 
               
                  	<td width="25%" align="center">Member name</td>
                    <td width="25%" align="center" colspan="2">Operation</td>
				</tr>
                
                <?php $cnt = 0; foreach($result as $row) {$res =array(); ?>
                
				<tr>
					<td width="10%" align="center"><?php echo ++$cnt; ?></td>
                    <td width="20%" align="center"><?php echo $row['regnumber']; ?></td>
                
                    	 <td width="20%" align="center">
                    	 	<?php 
                    	 	if (isset($row['bank_emp_id']) && $row['bank_emp_id']!='') {
                    	 		echo $row['bank_emp_id'];
                    	 		
                    	 	}else{
                    	 		echo "-";
                    	 	}

                    	 	 ?>
                    	 		
                    	 	</td> 
                  	 
					<td width="20%" align="center"><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname']; ?></td>
					<td width="30%" align="center" colspan="2"><?php 
					
				
			
			$this->db->where(array('admitcard_info.mem_mem_no' => $row['regnumber'],'exm_cd'=>101));
		$res =  $this->UserModel->getRecords("admitcard_info");
			
					
					if($row['exam_code']=='101' || $row['exam_code']=='1010' || $row['exam_code']=='10100' || $row['exam_code']=='101000' || $row['exam_code']=='1010000')
					{
						
						
						 if(!empty($res))
			{//echo '<pre>';
			//print_r($res);?>
					<a target="_blank" href="<?php echo base_url().'bulk/BulkTransaction/getadmitcardpdfsp/'.$row['regnumber'].'/'. 101; ?>"> View Admit Card </a>
				<?php }else
				{
					echo 'Admit card not available.';
				}	}else
				{ ?>
				<?php /*?><a target="_blank" href="<?php echo base_url().'dwnletter/naar_institute_profile_admitcard_pdf_single/'.$row['regnumber']; ?>"> View Admit Card </a><?php */?>
                
                
                 <a target="_blank" href="<?php echo base_url().'bulk/BulkTransaction/naar_getadmitcardpdfsp/'.$row['regnumber'].'/'.$row['exam_code'].'/'.$row['exam_period']; ?>"> View Admit Card </a>
                <?php /*?><a target="_blank" href="<?php echo base_url().'uploads/admitcardpdf/'.$row['exam_code'].'_'.$row['exam_period'].'_'.$row['regnumber'].'.pdf'; ?>"> View Admit Card </a><?php */?>
				
			<?php 	}?>
						
					</td>
				</tr>
                
                <?php } ?>
				
		 	</table>
		</td>
	</tr>
	<tr>
    	<td></td>
   	</tr>
</table>

<?php } ?>

</body>
</html>