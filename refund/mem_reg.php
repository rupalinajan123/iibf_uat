<?php
/*
 * Module Name	:	SBIEPAY Member Registration Refund
 * Author Name	:	Bhagwan Sahane
 * Created Date	:	26-09-2017
 * Updated Date	:	06-02-2018
 *
 */
 
error_reporting(E_ALL);
ini_set('display_errors', 1);

try{
	//$connect = mysql_connect('10.11.38.98','supp0rttest_iifbportal','Hdz&BRo00PL1');
	$connect = mysql_connect('10.11.38.120','supp0rttest_iifbportal','Hdz&BRo00PL1');
	if (!$connect){
		die('Could not connect to MySQL: ' . mysql_error());
	}
	$cid = mysql_select_db('supp0rttest_iibf_portal',$connect);
	
	$sql = mysql_query("SELECT * FROM `payment_transaction` WHERE gateway = 'sbiepay' AND amount = '1770.00' AND pay_type = 1 AND status = 1 AND receipt_no IN ('812183313','812183425')");
	while($row = mysql_fetch_array($sql)){
		
		echo "<BR> Member No. = ".$member_regnumber = $row['member_regnumber'];
		echo " === Txn AMT : ".$refundAmount = $amount = $row['amount'];
		echo " === Txn Date : ".$date = $row['date'];
		echo " === Txn No. : ".$atrn = $transaction_no = $row['transaction_no'];
		echo " === Receipt No. : ".$MerchantOrderNo = $receipt_no = $row['receipt_no'];
		echo " === Gateway : ".$gateway = $row['gateway'];
		
		$q_result = sbiqueryapi($receipt_no); //print_r($q_result);
		
		echo "<BR> Query result = ". implode("|", $q_result);
		echo "<BR> Query status = ".$q_result[8];
		
		if ($q_result[8] == "Transaction Paid Out") {
			
			$refund_request_id = sbi_refund_request_id($transaction_no, $receipt_no);
		
			echo "<BR>Insert PR : ".$insert_sql = "INSERT INTO `payment_refund`(`member_regnumber`, `gateway`, `amount`, `date`, `refund_request_id`, `transaction_no`, `receipt_no`, `ARRN`, `description`, `refund_details`, `status`, `admin_user_id`) VALUES ('".$member_regnumber."','".$gateway."','".$amount."','".date('Y-m-d H:i:s')."','".$refund_request_id."','".$transaction_no."','".$receipt_no."','','Found in SBI MIS Report - no invoice - no application(Rs.1770.00)','',2,'')";
			
			mysql_query($insert_sql);
		
			$pr_result = sbirefundapi($MerchantOrderNo, $refund_request_id, $atrn, $refundAmount);
			//print_r($pr_result); echo "<BR>"; exit;
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
			
			echo "<BR>UPDATE PR : ".$update_pr_sql = "UPDATE `payment_refund` SET `status` = '".$pr_status."', `ARRN` = '".$pr_ARRN."', `refund_details` ='".$pr_refund_details."' WHERE refund_request_id = '".$refund_request_id."'";
		
			mysql_query($update_pr_sql);
			
			// update payment transaction status table for
			echo "<BR>UPDATE PT : ".$update_pt_sql = "UPDATE `payment_transaction` SET `status` = '".$pt_status."' WHERE receipt_no = '".$MerchantOrderNo."' AND gateway = 'sbiepay' AND amount = '1770.00' AND pay_type = 1 AND status = 1";
		
			mysql_query($update_pt_sql);
			
			// Log payment refunds
			$insert_rpl_sql = "INSERT INTO `refund_paymentlogs`(`date`, `gateway`, `data`, `result`) VALUES ('".date('Y-m-d H:i:s')."','".$gateway."','".$pr_result_str."','".$pr_status."')";
		
			mysql_query($insert_rpl_sql);			
		}
	}

} catch(Exception $e){
	echo $e->getMessage();
}

function sbiqueryapi($MerchantOrderNo = "DP123369121")
{
	$merchIdVal = "1000169"; //$this->config->item('sbi_merchIdVal');
	$AggregatorId = "SBIEPAY"; //$this->config->item('sbi_AggregatorId');

	$atrn  = "";

	$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
	
	$service_url = "https://www.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery"; //$this->config->item('sbi_status_query_api');
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
	
	$service_url = "https://www.sbiepay.com/payagg/orderRefundCancellation/bookRefundCancellation";
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

function generate_refund_request_id($field_name)
{
	echo "ERR000123";
	exit;
	
	$sc_query = mysql_query("SELECT value FROM site_config WHERE name = '".$field_name."'");

	$refund_request_id = "";

	if(mysql_num_rows($sc_query) > 0)
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

function sbi_refund_request_id($transaction_no = NULL, $receipt_no = NULL)
{
	$last_id='';
	//$CI = & get_instance();
	//$CI->load->model('my_model');
	if($transaction_no  != NULL && $receipt_no  != NULL)
	{
		//$insert_info = array('transaction_no'=>$transaction_no, 'receipt_no'=>$receipt_no);
		//$last_id = $CI->master_model->insertRecord('config_sbi_refund_request_id',$insert_info,true);
		
		$insert_sql = "INSERT INTO `config_sbi_refund_request_id`(`transaction_no`, `receipt_no`) VALUES ('".$transaction_no."','".$receipt_no."')";
		
		mysql_query($insert_sql);
		
		$last_id = mysql_insert_id();
	}
	return $last_id;
}
?>