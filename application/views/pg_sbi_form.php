<?php
##########commented on 6-dec-2017(Prafull)
//$log_title ="Payment Request";
//$log_message = $EncryptTrans;
//$rId = '';
//$regNo = '';
//storedUserActivity($log_title, $log_message, $rId, $regNo);	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> New Document </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>
 <body>
 <div>Please Wait...<img src="indicator.gif" border="0" /></div>
  <form name="frmPG" id="frmPG" method="post" action="<?php echo $pg_form_url ?>">
	<input type="hidden" name="EncryptTrans" id="EncryptTrans" value="<?php echo $EncryptTrans ?>">
	<input type="hidden" name="merchIdVal" value ="<?php echo $merchIdVal ?>"/>
  </form>
 </body>
</html>
<script language="javascript">
	document.frmPG.submit();
</script>