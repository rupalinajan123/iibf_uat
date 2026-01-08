<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

	function sbirefundapi($MerchantOrderNo, $refundRequestId, $atrn, $refundAmount)
	{
		$CI = & get_instance();
		//$MerchantOrderNo = "19181";
		$merchIdVal = $CI->config->item('sbi_merchIdVal'); //"1000169";
		$AggregatorId = $CI->config->item('sbi_AggregatorId'); //"SBIEPAY";
	
		//$refundRequestId = "RID12346";
		//$atrn   = "8058619007214";
		//$refundAmount  = "1";
		$refundAmountCurrency = "INR";
	
		$refundRequest = $AggregatorId."|".$merchIdVal."|".$refundRequestId."|".$atrn."|".$refundAmount."|".$refundAmountCurrency."|".$MerchantOrderNo;
		
		$service_url = "https://www.sbiepay.com/payagg/orderRefundCancellation/bookRefundCancellation";
		//$service_url = $CI->config->item('sbi_refund_canc_api');
		
		//echo "<BR>param = ".$post_param = "refundRequest=".$refundRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
		$post_param = "refundRequest=".$refundRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
						
		$ch = curl_init();       
		curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		$result = curl_exec($ch);
		
		if($result)
		{
			$response_array = explode("|", $result);
			
			return $response_array;
		}
		else
		{
			return 0;
		}
		
		//var_dump($result);   
		curl_close($ch);	
	}
	
	/*function sbiqueryapi($MerchantOrderNo) //  "DP123369121"
	{
		$merchIdVal = $CI->config->item('sbi_merchIdVal'); //"1000169";
		$AggregatorId = $CI->config->item('sbi_AggregatorId'); // "SBIEPAY"; 
	
		$atrn  = "";
	
		$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
		
		$service_url = "https://www.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery"; //$CI->config->item('sbi_status_query_api');
		$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
	
		$ch = curl_init();       
		curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		$result = curl_exec($ch);
		curl_close($ch);
		
		if($result)
		{
			$response_array = explode("|", $result);
			
			return $response_array;
		}
		else
		{
			return 0;
		}
	
		//print_r($response_array);
		//var_dump($result);   
	}*/