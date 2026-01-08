<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<img src="<?php echo base_url()?>assets/images/loading.gif" id="gif" style="display: block; margin: 0 auto; width: 150px;height:50px;">



<form method="post" action="https://payuat.csccloud.in/v1/payment/<?php echo $frac;?>" name="billing_submit" id="billing_submit">
					<input type="hidden" name="message" value="<?=$enc_text;?>" />
					
				</form>



<script type="text/javascript">
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

</script>



           