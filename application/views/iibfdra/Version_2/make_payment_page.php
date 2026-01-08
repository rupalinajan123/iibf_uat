<form action="<?php echo base_url() ?>iibfdra/Version_2/DraExam/make_payment" method="post">
  <table align='center' width="800" border="0" style="position:relative; font:normal 11px arial; color:#000; border:1px solid #ccc; margin-top:20px;">
  <tr>
  	<td style="font-size: 14px"><b>Please Complete The Payment Process Before Logging in</b></td>
  </tr>

  <tr>
  	<td align="left" style="padding-left:8px;line-height:20px;color: #990000;font-size: 12px"><b><b>NOTE:</b></b></td>
  </tr>
  <tr>
      <td align="left" style="padding-left:20px;line-height:20px;color: #990000;font-size: 12px"><ul>
                    <li class="style2">After submitting the page, please wait for the intimation from the server, <b>DO NOT press back or Refresh button in order to avoid double charge.</b> </li>
                  <li class="style2">For Credit Card users: All prices are listed in Indian Rupee.</li>
                  <li class="style2">Security Advisory: To ensure the security of your data, please close the browser window once your transaction is completed. </li>
                </ul>
      </td>
    </tr>

    <tr>
      <td align="center">&nbsp;</td>
    </tr>

    <tr>
      <td align="center"><input type="image" src="<?php echo  base_url()?>assets/images/online_cards.gif" />
      <input type="hidden" name="processPayment" value="processPayment" />
      <input type="hidden" name="regNosToPay" value="<?php echo $regNosToPay ?>" />
	  <input type="hidden" name="tot_fee" value="<?php echo $tot_fee ?>" />
      
	  <input type='hidden' name='exam_code' id='exam_code' value="<?php echo $exam_code;?>" /> <!-- passing telecall const to page to identify dra or tele cands payment -->
      <input type='hidden' name='exam_period' id='exam_period' value="<?php echo $exam_period;?>" />

      </td>
    </tr>

    <tr>
		<td align="center">
			<input type="submit" name="submit" value="PAY NOW" />
		</td>
	</tr>
  </table>
</form>

<script>
  history.pushState(null, null, document.title);
  window.addEventListener('popstate', function () {
      history.pushState(null, null, document.title);
  });
</script>