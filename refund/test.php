<?php
	echo "<br/> Start ".date("Y-m-d H:is");
	$q_result = sbiqueryapi('902624652');
	echo '<pre>';
	print_r($q_result);
	echo "<br/> END ". date("Y-m-d H:is");
	function sbiqueryapi($MerchantOrderNo = "DP123369121")
{
	$merchIdVal = "1000169"; //$this->config->item('sbi_merchIdVal');
	$AggregatorId = "SBIEPAY"; //$this->config->item('sbi_AggregatorId');

	$atrn  = "";

	$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
	
	$service_url = "https://www.sbiepay.sbi/payagg/orderStatusQuery/getOrderStatusQuery"; //$this->config->item('sbi_status_query_api');
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

}
?>