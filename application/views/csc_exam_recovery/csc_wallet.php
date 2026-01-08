<?php
$system_date = date("Y-m-d H:i:s");	
if($system_date > '2019-04-05 18:30:00' && $system_date < '2019-04-06 01:30:00'){
?> 
<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
	<meta charset="utf-8">
	<title>Welcome to IIBF</title>
    
</head>
<body style="background-color:#fff; margin:0 auto; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
  <!--main-table-->
</body>
</html>	

<?php }else{?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/subject-pre.css">
<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>

<form action="<?php echo base_url();?>/CSC_connect/User.php" method="post">

  <table align='center' width="800" border="0" style="position:relative; font:normal 11px arial; color:#000; border:1px solid #ccc; margin-top:20px;">

  <tr>

      

    </tr>



    



    <tr>

    <!--  <td align="center"><input type="image" src="<?php echo  base_url()?>assets/images/online_cards.gif" />

      <input type="hidden" name="CSC Payment" value="cscPayment" />

    

      </td>-->

    </tr>



    <tr>

		<td align="center">

			<input type="submit" name="submit" value="CSC Connet" onClick="javascript:return get_loader();"/>

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



<!--<script>

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

</script>-->

<?php }?>

