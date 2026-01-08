<?php
//require_once __DIR__ . '/../vendor/autoload.php';
require_once('../vendor/autoload.php');
$headers = array(
    'alg' => 'HS256', //alg is required
    'clientid' => 'uatiibfv2'
);

// anything that json serializable
$payload = [
"mercid" => "UATIIBFV2",
"orderid" => "TSSGF43214S",
"amount" => "300.00",
"order_date" => date("c"),
"currency" => "356",
"ru" => "https://iibf.teamgrowth.net/payment/billdesk",
"additional_info" => [
"additional_info1" => "Details1",
"additional_info2" => "Details2"
],
"itemcode" => "DIRECT",
"invoice" => [
"invoice_number" => "MEINVU111111221133",
"invoice_display_number" => "11221133",
"customer_name" => "Tejas",
"invoice_date" => "2021-09-03T13:21:5+05:30",
"gst_details" => [
"cgst" => "8.00",
"sgst" => "8.00",
"igst" => "0.00",
"gst" => "16.00",
"cess" => "0.00",
"gstincentive" => "5.00",
"gstpct" => "16.00",
"gstin" => "12344567"
]
],
"device" => [
"init_channel" => "internet",
"ip" => "202.149.208.92",
"mac" => "11-AC-58-21-1B-AA",
"imei" => "990000112233445",
"user_agent" => "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0",
"accept_header" => "text/html",
"fingerprintid" => "61b12c18b5d0cf901be34a23ca64bb19"
]
];
$key = 'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';

$jws = new \Gamegos\JWS\JWS();

// ENCODE
$jwsString = $jws->encode($headers, $payload, $key);
//printf("encode:\n%s\n\n", $jwsString); //eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJzb21lb25lQGV4YW1wbGUuY29tIiwiaWF0IjoiMTQwMjk5MzUzMSJ9.0lgcQRnj_Jour8MLdIc71hPjjLVcQAOtagKVD9soaqU%

## curl call
// Prepare new cURL resource
  $crl = curl_init('https://pguat.billdesk.io/payments/ve1_2/orders/create');
  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($crl, CURLINFO_HEADER_OUT, true);
  curl_setopt($crl, CURLOPT_POST, true);
  curl_setopt($crl, CURLOPT_POSTFIELDS, $jwsString);
    
  // Set HTTP Header for POST request 
  curl_setopt($crl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/jose',
      'accept: application/jose',
      'bd-traceid: '.time().'ABD1K',
      'bd-timestamp: '.time())	
  );
    
  // Submit the POST request
  $result = curl_exec($crl);
    
  //print_r($result);
  // Close cURL session handle
  curl_close($crl);
  
// VERIFY
//printf("verify: \n");
/*$jwsString = 'eyJhbGciOiJIUzI1NiIsImNsaWVudGlkIjoidWF0aWliZnYyIiwia2lkIjoiSE1BQyJ9.eyJzdGF0dXMiOjQyMiwiZXJyb3JfdHlwZSI6ImludmFsaWRfZGF0YV9lcnJvciIsImVycm9yX2NvZGUiOiJPUklERTAwMDMiLCJtZXNzYWdlIjoiSW52YWxpZCBvcmRlcl9kYXRlIn0.f5I8F0EFcv_Fe6po0Zi-CIz1Gg_kJa_fo5o7wGXzuN4';*/
echo '<pre>';
print_r($jws->verify($result, $key));
