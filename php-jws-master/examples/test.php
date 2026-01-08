  <?php 
  header('OToken:48D5AAEEF20C818C4B10F78013AD7E5AE96C45528E9EBA837C85CFE94970C48FABED0F35C2C56EAAE63C1035A7D08B3DA911D5EA185475215677B60A330F1FC8BBA660E32CBB569F06A15C9CDDDCC03BBCF1291A9F427FFBD89EF300FD5BCF47220C1CC5CF3DBBD5871134F24A7C9298785FE2B6182DA25819353D7CF80A68A89C6F0B5D84E4284EE6B5FE1787447B47716279003037463BB304.4145535F55415431');
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  	<title>Test Billdesk</title>
  	<script type="module"
src="https://uat.billdesk.com/jssdk/v1/dist/billdesksdk/billdesksdk.esm.js"></script>
<script nomodule="" src="https://uat.billdesk.com/jssdk/v1/dist/billdesksdk.js
"></script>
<link href="https://uat.billdesk.com/jssdk/v1/dist/billdesksdk/billdesksdk.css"
rel="stylesheet">
  </head>
  <body>
  <form method="POST" action="https://pguatweb.billdesk.io/pgtxnsimulator/v1_2/transactions/orderform">
  <input type="hidden" name="mercid" value="UATIIBFV2">
  <input type="hidden" name="bdorderid" value="OAR919XTKAPQU9">
    <input type="submit" name="submit" value="Pay Now">
  </form>
  </body>
  </html> 