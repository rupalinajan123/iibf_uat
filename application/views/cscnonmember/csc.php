<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!--<img src="<?php echo base_url()?>assets/images/loading.gif" id="gif" style="display: block; margin: 0 auto; width: 150px;height:50px;">-->



<form method="post" action="https://wallet.csccloud.in/v1/payment/<?php echo $frac;?>" name="billing_submit" id="billing_submit">
					<input type="hidden" name="message" value="<?=$enc_text;?>" />
					<table align='center' width="800" border="0" style="position:relative; font:normal 11px arial; color:#000; border:1px solid #ccc; margin-top:20px;">
     <tr>
       <td style="font-size: 14px"><b>Please Complete The Payment Process. Do not refresh page or click on back button</b></td>
     </tr>
     <tr>
       <td align="center">&nbsp;</td>
     </tr>
      <tr>
       <td align="center">
       <input type="image" src="<?php echo  base_url()?>assets/images/csc-logo.png" />
       <input type="image" src="<?php echo  base_url()?>assets/images/digital-india.png" />
       </td>
     </tr>
     <tr>
       <td align="center"><input type="submit" name="submit" value="PAY NOW"/></td>
     </tr>
   </table>
				</form>



<!--<script type="text/javascript">
setInterval(function() {
        document.forms[0].submit();
    }, 500);

window.onload=function(){ 
    var counter = 5;
    var interval = setInterval(function() {
        counter--;
        $("#seconds").text(counter);
        if (counter == 0) {
            redirect();
            clearInterval(interval);
        }
    }, 300);

};

function redirect() {
 document.billing_submit.submit();
}

</script>-->



           