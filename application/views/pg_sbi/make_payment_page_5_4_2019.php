<?php
//old by only date
//$str_date = '2018-07-15';
//$live_date = date("Y-m-d");
//do this condition in if if($live_date>=$str_date)


//new with date and time
//$str_date='2018-08-06 21:45:00';
//$live_date=date("Y-m-d H:i:s");


//$str_date = '2018-07-15';
//$live_date = date("Y-m-d");

$str_date='2018-11-01 19:30:00';
//$str_date='2019-12-31 23:59:59';
$live_date=date("Y-m-d H:i:s");
//$live_date='2018-08-06 21:45:01';

	
if($live_date>=$str_date)
{?>
	
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

			<input type="submit" name="submit" value="PAY NOW" onClick="javascript:return get_loader();"/>

		</td>

	</tr>

  </table>

</form>



<!--<script>

  history.pushState(null, null, document.title);

  window.addEventListener('popstate', function () {

      history.pushState(null, null, document.title);

  });

</script>-->



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
<?php 
}
else
{?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to IIBF</title>
    
</head>
<body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
  <table cellpadding="0" cellspacing="0" width="800" border="0" align="center">
    <tr>
      <td style="background-color:#fff;">
        <!--table-1-->
        <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
          <tr>
            <td style="border:1px solid #1287c0; padding:5px;">
              <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
                <tr>
                  <td align="center"><img src="<?php echo base_url();?>assets/images/logo.jpg" width="400" height="66" /></td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
				<tr>
                  <td height="5"><h3>Online Registrations will not be available on 5th April 2019 11:40 AM to   12:05 PM since payment gateway is closed for some upgradation work. Hence candidates are requested not to try to register during the above said date and time. </h3></td> 
                </tr>
				<tr>
                  <td height="5"><h3>Inconvenience caused is regretted</h3></td>
                </tr>
               
              </table><!--table-2-->
            </td>
          </tr>
        </table><!--table-1-->
      </td>
    </tr>
  </table><!--main-table-->
</body>
</html>
<?php 
}?>

