<?php if($count >= '0' || (isset($refund_count) && $refund_count >= '0')) { ?>
	<div class="col-lg-12">		
		<div class="form-group" style="display:inline-block;">
			<div style="background: #fff; padding: 5px 15px; text-align: center; font-weight: 600; border: 1px solid #ccc; font-size: 15px; height:40px;min-width: 150px; white-space: nowrap;">
				<?php if($count >= '0' && !isset($paid_count)) { echo "Count : ".$count; } 
					
					if(isset($refund_count) && $refund_count >= '0') 
					{ 
						echo "Paid : ".$paid_count." &nbsp;&nbsp;+&nbsp;&nbsp; Free : ".$free_count." &nbsp;&nbsp;+&nbsp;&nbsp; Refund : ".$refund_count." &nbsp;&nbsp;=&nbsp;&nbsp; Total : ".($paid_count + $free_count + $refund_count);
					} ?> 
			</div>
		</div>
	</div>
	<?php } ?>