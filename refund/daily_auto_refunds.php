<?phpexit;
/*
 * Module Name	:	SBIEPAY Seat Full Refund
 * Author Name	:	Bhushan Amrutkar
 * Created Date	:	23-12-2020
*/

// https://iibf.esdsconnect.com/refund/daily_auto_refunds.php
// /usr/local/bin/php /home/supp0rttest/public_html/refund/daily_auto_refunds.php

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

	/* Get Details from `exam_invoice_settlement` DB Table */
	$get_details = mysql_query("SELECT trn_no,receipt_no FROM `payment_refund_mail` WHERE refund_status = '0' LIMIT 5");
	
	while($get_details_row = mysql_fetch_array($get_details))
	{
		$transaction_no = $get_details_row['trn_no'];
		$receipt_no = $get_details_row['receipt_no'];
		
		/* Get Details from `payment_transaction` DB Table */
		$get_receipt_no = mysql_query("SELECT receipt_no FROM `payment_transaction` WHERE receipt_no = '".$receipt_no."'");
		
	
		while($receipt_no_row = mysql_fetch_array($get_receipt_no))
		{
			$receipt_no = $receipt_no_row['receipt_no'];
			
			/* Get Details from `payment_transaction` DB Table */
			$sql = mysql_query("SELECT * FROM `payment_transaction` WHERE gateway = 'sbiepay' AND receipt_no = '".$receipt_no."'");
			
			while($row = mysql_fetch_array($sql))
			{ 
				$member_regnumber = $row['member_regnumber'];
				$refundAmount = $amount = $row['amount'];
				$date = $row['date'];
				//$atrn = $transaction_no = $row['transaction_no'];
				$MerchantOrderNo = $receipt_no = $row['receipt_no'];
				$gateway = $row['gateway'];
				
				$q_result = sbiqueryapi($receipt_no); //print_r($q_result);
				
				$atrn = $transaction_no = $trn_no = $q_result[1];
				
				echo "<BR><BR> Query result = ". implode("|", $q_result);
				echo "<BR><BR> Query status = ".$q_result[8];

				if ($q_result[8] == "Transaction Paid Out") 
				{
					
					$refund_request_id = sbi_refund_request_id($transaction_no, $receipt_no);
					
					/* Insert Details in `payment_refund` DB Table */	
					$insert_sql = "INSERT INTO `payment_refund`(`member_regnumber`, `gateway`, `amount`, `date`, `refund_request_id`, `transaction_no`, `receipt_no`, `ARRN`, `description`, `refund_details`, `status`, `admin_user_id`) VALUES ('".$member_regnumber."','".$gateway."','".$amount."','".date('Y-m-d H:i:s')."','".$refund_request_id."','".$transaction_no."','".$receipt_no."','','only succuss at sbi - auto found and auto refund (".$member_regnumber.")','',2,'')";
					
					mysql_query($insert_sql);
					
					$pr_result = sbirefundapi($MerchantOrderNo, $refund_request_id, $atrn, $refundAmount);
					
					//print_r($pr_result); echo "<BR>";
					
					if (!$pr_result)
					{
						die("Error in Refund request API response");
					}
					
					$pr_result_str = implode("|",$pr_result);
					$pt_status = 1;
					
					if ($pr_result[3] == "SUCCESS")
					{
						$pr_status = "1";
						$pt_status = "3";
					}
					else
					{
						$pr_status = "0";
					}
			
					$pr_refund_details = $pr_result[4]; // found  in payment gateway response
					$pr_ARRN = $pr_result[2];  // found in payment gateway response
					
					/* Insert Details in `payment_refund` DB Table */
					$update_pr_sql = "UPDATE `payment_refund` SET `status` = '".$pr_status."', `ARRN` = '".$pr_ARRN."', `refund_details` ='".$pr_refund_details."' WHERE refund_request_id = '".$refund_request_id."'";
					mysql_query($update_pr_sql);
					
					/* Update Details in `payment_transaction` DB Table */
					$update_pt_sql = "UPDATE `payment_transaction` SET `status` = '".$pt_status."' WHERE receipt_no = '".$MerchantOrderNo."' AND gateway = 'sbiepay'";
					mysql_query($update_pt_sql);
					
					/* Insert Details in `refund_paymentlogs` DB Table */
					$insert_rpl_sql = "INSERT INTO `refund_paymentlogs`(`date`, `gateway`, `data`, `result`) VALUES ('".date('Y-m-d H:i:s')."','".$gateway."','".$pr_result_str."','".$pr_status."')";
					mysql_query($insert_rpl_sql);	
					
					/* Update Details in `payment_refund_mail` DB Table */
					$update_exam_invoice_settlement_sql = "UPDATE `payment_refund_mail` SET `refund_status` = 1, refund_date = '".date('Y-m-d')."' WHERE trn_no = '".$transaction_no."' ";
					mysql_query($update_exam_invoice_settlement_sql);	
				}
			}
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