<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/subject-pre.css">
<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
<form action="" method="post">

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

    

      </td>

    </tr>



    <tr>

		<td align="center">

			<input type="submit" name="submit" value="PAY NOW" onclick="javascript:return get_loader();"/>

		</td>

	</tr>

  </table>

</form>






<script>

function get_loader()
{
	$(".loading").show();
}


(function (global) {


	if(typeof (global) === "undefined")

	{

		throw new Error("window is undefined");

	}



    var _hash = "!";

    var noBackPlease = function () {

        global.location.href += "#";



		// making sure we have the fruit available for juice....

		// 50 milliseconds for just once do not cost much (^__^)

        global.setTimeout(function () {

            global.location.href += "!";

        }, 50);

    };

	

	// Earlier we had setInerval here....

    global.onhashchange = function () {

        if (global.location.hash !== _hash) {

            global.location.hash = _hash;

        }

    };



    global.onload = function () {

        

		noBackPlease();



		// disables backspace on page except on input fields and textarea..

		document.body.onkeydown = function (e) {

            var elm = e.target.nodeName.toLowerCase();

            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {

                e.preventDefault();

            }

            // stopping event bubbling up the DOM tree..

            e.stopPropagation();

        };

		

    };



})(window);

</script>

