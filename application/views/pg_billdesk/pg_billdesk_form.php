<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="favicon.ico">
		<title>Billdesk SDK</title>
  </head>
	
	<body>
		<div class="container">
			<div class="jumbotron mt-3">
				<h1>BillDesk SDK</h1> 
				<!--     <a class="btn btn-lg btn-primary" href="#" onclick="loadXMLDoc()" role="button">Launch SDK »</a>
        </div> -->
				<div id="spinner" style="display: none;" class="mt-3 text-center">
					<div class="spinner-border" role="status" style="width: 5rem; height: 5rem;">
						<span class="sr-only">Loading...</span>
          </div>
        </div>
				<div id="result" class="jumbotron mt-3">
        </div>
      </div>
    </div>
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
		integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
		integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
		
    <?php if($this->config->item('bd_payment_mode_sm') == 'production')
      { ?>
      <script type="module" src="https://pay.billdesk.com/jssdk/v1/dist/billdesksdk/billdesksdk.esm.js"></script>
      <script nomodule src="https://pay.billdesk.com/jssdk/v1/dist/billdesksdk.js"></script>
      <link href="https://pay.billdesk.com/jssdk/v1/dist/billdesksdk/billdesksdk.css" rel="stylesheet">
      <?php }
      else if($this->config->item('bd_payment_mode_sm') == 'sandbox')
      { ?>
      <script type="module" src="https://uat.billdesk.com/jssdk/v1/dist/billdesksdk/billdesksdk.esm.js"></script>
      <script nomodule="" src="https://uat.billdesk.com/jssdk/v1/dist/billdesksdk.js"></script>
      <link href="https://uat.billdesk.com/jssdk/v1/dist/billdesksdk/billdesksdk.css" rel="stylesheet">      
    <?php } ?>
    
    
		<script type="text/javascript">
			window.onload = loadXMLDoc;
			var bdorderid = '<?php echo $bdorderid ?>';
			var token  = '<?php echo $token ?>';
			// alert(bdorderid+"__"+token)
			
			function hideSpinner() 
			{
				document.getElementById('spinner').style.display = 'none';
      }
			
			function showSpinner() 
			{
				document.getElementById('spinner').style.display = 'block';
      }
			
			var responseHandler = function(txn) 
			{   
				var responseXHR = new XMLHttpRequest();
				responseXHR.onreadystatechange = function() 
				{
					if(responseXHR.readyState == XMLHttpRequest.DONE) 
					{ // XMLHttpRequest.DONE == 4
						if(responseXHR.status == 200) 
						{
							var jsonStr = responseXHR.responseText;
							//console.log("jsonStr : "+jsonStr);
							jsonObj = JSON.parse(jsonStr);
							//console.log("jsonObj : "+jsonObj);
							//console.log("jsonObj.orderid : "+jsonObj.orderid);
							var htm = "";
							// htm += "<div>Transaction Id: " + jsonObj.transactionid + " </div>";
							// htm += "<div>Auth Status: " + jsonObj.auth_status + " </div>";
							// htm += "<div>Transaction Date: " + jsonObj.transaction_date + " </div>";
							// htm += "<div>Payment Method Type: " + jsonObj.payment_method_type + " </div>";
							// htm += "<div>Amount: " + jsonObj.charge_amount + " </div>";
							// htm += "<div>Bank Ref No.: " + jsonObj.bank_ref_no + " </div>";
							// htm += "<div>Error code: " + jsonObj.transaction_error_code + " </div>";
							// htm += "<div>Error desc: " + jsonObj.transaction_error_desc + " </div>";
							document.getElementById("result").innerHTML = htm;
							hideSpinner();
            } 
						else if(responseXHR.status == 400) 
						{
							//alert('There was an error 400');
            } 
						else 
						{
							//alert('something else other than 200 was returned');
            }
          }
        };				
				//console.log("txn : "+txn);
				
				if (txn.txnResponse)  
				{     
          <?php if($this->config->item('bd_payment_mode_sm') == 'production')
          { ?>
					  responseXHR.open("POST", "https://api.billdesk.com/pgi/MerchantPayment/", true);  
          <?php }
          else if($this->config->item('bd_payment_mode_sm') == 'sandbox')
          { ?>
            responseXHR.open("POST", "https://pguatweb.billdesk.io/pgtxnsimulator/v1_2/txnresponse", true);
          <?php } ?>
					responseXHR.send(txn.txnResponse.transaction_response);
        } 
				else 
				{
					hideSpinner();
        }
      }
			
			var flow_config = {
				merchantId: "<?php 
          if(isset($ins_subscription_pg_flag) && $ins_subscription_pg_flag == 'IIBF_INST_SUB') { echo 'IIBFBOB'; } 
          else if(isset($bulk_payment_flag) && in_array($bulk_payment_flag, array('IIBF_BULK_BCBF', 'IIBF_BULK_DRA'))) { echo $this->config->item('BD_MERCID_BULK'); } 
          else { echo $this->config->item('BD_MERCID'); } ?>", //UATIIBFV2
				bdOrderId: bdorderid,
				authToken: token,
				childWindow: false,
				returnUrl: "<?php echo $returnUrl; ?>",		
				retryCount: 0,		
      };
			
			var config = {
				responseHandler: responseHandler,
				merchantLogo: "",
				flowConfig: flow_config,
				flowType: "payments"
      };
			
			function loadXMLDoc()
			{ 
				showSpinner();
				document.getElementById("result").innerHTML = "";
				var xmlhttp = new XMLHttpRequest();
				var jsonObj = "";
				{
					{
						flow_config.bdOrderId = bdorderid;
						flow_config.authToken = token;
						//window.loadBillDeskSdk(config);

            if (typeof window.loadBillDeskSdk == "function") 
            {
              console.log("Loading SDK");
              window.loadBillDeskSdk(config);
            } 
            else 
            {
              console.log("Reloading SDK");
              setTimeout(function ()
              {
                loadXMLDoc();
              }, 500);
            }
          }
        };
      }

      /* loadSdk();
      function loadSdk()
      {
        if (typeof window.loadBillDeskSdk == "function") 
        {
          window.loadBillDeskSdk(config);
        } 
        else 
        {
          console.log("Reloading SDK");
          setTimeout(function ()
          {
            loadSdk();
          }, 500);
        }
      } */
    </script>
  </body>
</html>