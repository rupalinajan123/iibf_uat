<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//$system_date='2018-08-06 21:45:01'; 

$system_date = date("H:i:s");

$pa_flag = 1;

if($system_date > '23:50:00' && $system_date < '23:59:59'){ 

	$pa_flag = 0;

}

if($system_date > '00:00:01' && $system_date < '00:10:00'){

	$pa_flag = 0;

}




/*$pa_flag = 1;
$system_date= date("Y-m-d H:i:s");
if($system_date > '2021-06-11 00:00:01' && $system_date < '2021-06-11 03:00:00'){ 

	 $pa_flag = 0;

}*/

## Code for daily maintenance activity
$pa_flag_daily = 0; 


/*
below code to control pg from db
$query = $this->db->query("SELECT * FROM `payment_gateway_activation`");
$pg_data = $query->row_array();
  if (count($pg_data) && $pg_data['status']==0) {
    $pa_flag_daily = 1; 
}
*/



$system_date = date("H:i:s");
 if($system_date > '12:00:00' && $system_date < '13:00:00'){ 

	 $pa_flag_daily = 1; 

}

// if($system_date > '2022-08-06 20:00:00' && $system_date < '2022-08-09 13:00:00') 
// { 
// 	$pa_flag_daily = 1; 
// }


$pa_flag_daily = 0; 

 function get_client_ip_1() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

	//  if(get_client_ip_1()=='115.124.115.69' || get_client_ip_1()=='182.73.101.70'){
	// 	 $pa_flag_daily = 0; 
	// } 
  

$pa_flag = '0';
$pa_flag_daily = '1';
if($pa_flag == $pa_flag_daily){                         

     

?> 




<!DOCTYPE html>

<html lang="en"> 

<head>
	<?php $this->load->view('google_analytics_script_common'); ?>
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

                  <td height="5"><h3>
                     ONLINE PAYMENT services are not available due to maintenance activity from 12 PM to 01 PM. 
                  </h3>
                </td> 
				
				<!--<td height="5"><h3>
          Due to sudden Technical glitch, the JAIIB/DB&F/SOB registrations have been stopped till 5.00 PM today. </h3></td>-->
		
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

<?php }else{

defined('BASEPATH') OR exit('No direct script access allowed');	

?>

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

    <td style="font-size: 14px"><b>Please select billdesk to complete your payment.</b></td>

  </tr>
   
    <tr>
     

        <?php if(isset($show_billdesk_option_flag) && $show_billdesk_option_flag == 1)
        {   ?>
            <td>  
              <?php  /*if($this->router->fetch_class()=='GstRecovery' && (get_client_ip()=='115.124.115.69' || get_client_ip()=='182.73.101.70')) { ?>
              <label class="radio-inline">
                  <input type="radio" name="pg_name" value="sbi" checked>SBI
                </label>
              <?php } */?>

                <label class="radio-inline">
                  <input type="radio" name="pg_name" value="billdesk" checked>Bill Desk
                </label>
            
            </td>
<?php   }
        else
        {   ?>
      
<?php   } ?>
    
    </tr>
    

    <tr>

      <td align="center"><input type="image" src="<?php echo  base_url()?>assets/images/online_cards.gif" />

      <input type="hidden" name="processPayment" value="processPayment" />

    

      </td>

    </tr>

    <tr>

		<td align="center">

			<input type="submit" name="submit" value="PAY NOW" onClick="javascript:return get_loader();" id = "paynowid"/>

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

	$("#paynowid").hide();

	alert('If there is no immediate acknowledgement, please try after four hours as there could be technical snag.');

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

<?php }?>