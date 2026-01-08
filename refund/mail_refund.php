<?php
/*
 * Module Name	:	SBIEPAY Auto mail Refund
 * Author Name	:	Bhushan Amrutkar
 * Created Date	:	17-12-2020
*/

// https://iibf.esdsconnect.com/refund/mail_refund.php
// /usr/local/bin/php /home/supp0rttest/public_html/refund/mail_refund.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

try
{
	/* MySQL DB Connection */
	//$connect = mysql_connect('10.11.38.98','supp0rttest_iifbportal','Hdz&BRo00PL1');
	$connect = mysql_connect('10.11.38.120','supp0rttest_iifbportal','Hdz&BRo00PL1');
	if (!$connect){
		die('Could not connect to MySQL: ' . mysql_error());
	}
	$cid = mysql_select_db('supp0rttest_iibf_portal',$connect);

	$yesterday = date('Y-m-d', strtotime("- 1 day"));
	
	$get_details = mysql_query("SELECT receipt_no,member_regnumber,date,amount,status FROM payment_transaction WHERE gateway = 'sbiepay' and pay_type != 5 and status IN ('2','0') and DATE(date)  = '".$yesterday."'");
	
	while($get_details_row = mysql_fetch_array($get_details))
	{	
		$member_regnumber = $get_details_row['member_regnumber'];
		$receipt_no = $get_details_row['receipt_no'];
		$trn_date = $get_details_row['date'];
		$amount = $get_details_row['amount'];
		$status = $get_details_row['status'];
		
		$q_result = sbiqueryapi($receipt_no); 
		//echo "<pre>";
		//print_r($q_result);
		implode("|", $q_result);
		//echo "<BR><BR> Query status = ".$q_result[8];
		$trn_no = $q_result[1];
		$tnx_status = $q_result[2];
		//$q_result[8] == "Transaction Paid Out"
		
		if ($q_result[2] == "SUCCESS") 
		{
			$insert_sql = "INSERT INTO `payment_refund_mail`(`member_regnumber`, `receipt_no`, `trn_no`, `amount`, `trn_date`, `status`) VALUES ('".$member_regnumber."','".$receipt_no."','".$trn_no."','".$amount."','".$trn_date."','".$status."')";
			mysql_query($insert_sql);
		}
	}
} catch(Exception $e){ 
	echo $e->getMessage();
}

/* sbiqueryapi funcation for SBI */
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

	//print_r($response_array);
	//var_dump($result);   
}
/* sbirefundapi funcation for SBI */
function sbirefundapi($MerchantOrderNo, $refundRequestId, $atrn, $refundAmount)
{
	//$MerchantOrderNo = "19181";
	$merchIdVal = "1000169"; //$this->config->item('sbi_merchIdVal');
	$AggregatorId = "SBIEPAY"; //$this->config->item('sbi_AggregatorId');

	//$refundRequestId = "RID12346";
	//$atrn   = "8058619007214";
	//$refundAmount  = "1";
	$refundAmountCurrency = "INR";

	$refundRequest = $AggregatorId."|".$merchIdVal."|".$refundRequestId."|".$atrn."|".$refundAmount."|".$refundAmountCurrency."|".$MerchantOrderNo;
	
	$service_url = "https://www.sbiepay.sbi/payagg/orderRefundCancellation/bookRefundCancellation";
	//$service_url = $this->config->item('sbi_refund_canc_api');
	
	echo "<BR>param = ".$post_param = "refundRequest=".$refundRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
					
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

/* Funcation for generate refund request id */
function generate_refund_request_id($field_name)
{
	echo "ERR000123";
	exit;

	$sc_query = mysql_query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	$refund_request_id = "";

	if (mysql_num_rows($sc_query) > 0)
	{
		$sc_row = mysql_fetch_array($sc_query);
		$refund_request_id = $sc_row["value"];
		
		$refund_request_id = $refund_request_id+1;
		
		$update_sc_sql = "UPDATE site_config SET value = '".$refund_request_id."' WHERE name = '".$field_name."'";
		mysql_query($update_sc_sql);
		
		return $refund_request_id;
	}
	return $refund_request_id;
}

/* Funcation for refund request id */
function sbi_refund_request_id($transaction_no = NULL, $receipt_no = NULL)
{
	$last_id='';
	
	if($transaction_no  != NULL && $receipt_no  != NULL)
	{
		$insert_sql = "INSERT INTO `config_sbi_refund_request_id`(`transaction_no`, `receipt_no`) VALUES ('".$transaction_no."','".$receipt_no."')";
		
		mysql_query($insert_sql);
		$last_id = mysql_insert_id();
	}
	return $last_id;
}
?>