<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
function RefundEnquiryStatus()
{
$service_url = "https://www.sbiepay.sbi/payagg/RefundMISReport/refundEnquiryAPI";
$merchIdVal = "1000169";
$AggregatorId = "SBIEPAY";
$atrn  = "8461191092835";
$acrn   = "1966024713361";
$arrn = "2310576333361";
$queryRequest  = $acrn."|".$atrn."|".$merchIdVal;
$queryRequest33 = http_build_query(array('queryRequest' => $queryRequest,"aggregatorId"=>"SBIEPAY","merchantId"=>$merchIdVal));
$ch = curl_init($service_url);      
//curl_setopt($ch, CURLOPT_SSLVERSION, true);
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $queryRequest33);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$response = curl_exec ($ch);
if (curl_errno($ch)) {
echo $error_msg = curl_error($ch);
}
curl_close ($ch);
echo $response;
}
RefundEnquiryStatus();
?>