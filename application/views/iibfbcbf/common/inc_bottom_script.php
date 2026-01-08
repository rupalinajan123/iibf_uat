		<?php echo "<div style='text-align:right; display:none;'>SERVER_ADDR IP Address: ".$_SERVER['SERVER_ADDR']; echo "&nbsp;&nbsp;&nbsp;&nbsp;<br></div>"; ?>
		
		<script>	
			$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });
			$(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); });
		</script>
