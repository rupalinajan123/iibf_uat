<?php exit;
## Refund payments without credit note Cron - Created on 22-Mar-2021
try
{	
	/* MySQL DB Connection */
	$connect = mysqli_connect('10.11.38.120','supp0rttest_iifbportal','Hdz&BRo00PL1');
	if (!$connect){
		die('Could not connect to MySQL: ' . mysqli_error());
	}
	$cid = mysqli_select_db($connect,'supp0rttest_iibf_portal');

	/* Get all transaction nos from DB Table to refund*/
	$get_details = mysqli_query($connect,"SELECT * FROM `refund_one`");
		
	while($get_details_row = mysqli_fetch_array($get_details))
	{
		
		$receipt_no = $get_details_row['receipt_no'];
		
		//to get trn no
		$q_result = sbiqueryapi($receipt_no); 
		echo "<BR><BR> q_result = "; print_r($q_result);	
		
		$transaction_no = $q_result[1];
		/* Get Details from `payment_transaction` DB Table */
		echo "SELECT member_regnumber,receipt_no,amount,pay_type,status,gateway,date FROM `payment_transaction` WHERE receipt_no = '".$receipt_no."' AND pay_type = '13' AND status = '2' AND gateway = 'sbiepay' LIMIT 1";
		
		$get_receipt_no = mysqli_query($connect,"SELECT member_regnumber,receipt_no,amount,pay_type,status,gateway,date FROM `payment_transaction` WHERE receipt_no = '".$receipt_no."' AND pay_type = '13' AND status = '2' AND gateway = 'sbiepay' LIMIT 1");
	
		while($row = mysqli_fetch_array($get_receipt_no))
		{
			$receipt_no = $row['receipt_no'];
			$pay_type = $row['pay_type'];
			
			$member_regnumber = $row['member_regnumber'];
			$refundAmount = $amount = $row['amount'];
			$date = $row['date'];
			$atrn = $transaction_no;
			$MerchantOrderNo = $receipt_no = $row['receipt_no'];
			$gateway = $row['gateway'];
			echo "Request send to sbi at: ".date("Y-m-d H:i:s");
				
			echo $insert_sql = "INSERT INTO `config_sbi_refund_request_id`(`transaction_no`, `receipt_no`) VALUES ('".$transaction_no."','".$receipt_no."')";
			mysqli_query($connect,$insert_sql);
			echo "last ID=>".$refund_request_id = mysqli_insert_id($connect);
		
		
			/* Insert Details in `payment_refund` DB Table */	
			echo $insert_sql = "INSERT INTO `payment_refund`(`member_regnumber`, `gateway`, `amount`, `date`, `refund_request_id`, `transaction_no`, `receipt_no`, `ARRN`, `description`, `refund_details`, `status`, `admin_user_id`) VALUES ('".$member_regnumber."','".$gateway."','".$amount."','".date('Y-m-d H:i:s')."','".$refund_request_id."','".$transaction_no."','".$receipt_no."','','Amount deducted at SBI and no response to ESDS  (".$member_regnumber.")','',2,'')";
			mysqli_query($connect,$insert_sql);
			
			$pr_result = sbirefundapi($MerchantOrderNo, $refund_request_id, $atrn, $refundAmount);
			echo "<pre>";
			print_r($pr_result);
			echo "</pre>";
			if (!$pr_result)
			{
				//die("Error in Refund request API response");
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
			if($pt_status == "3")
			{
				$pr_refund_details = $pr_result[4]; // found  in payment gateway response
				$pr_ARRN = $pr_result[2];  // found in payment gateway response
				
				/* Insert Details in `payment_refund` DB Table */
				echo $update_pr_sql = "UPDATE `payment_refund` SET `status` = '".$pr_status."', `ARRN` = '".$pr_ARRN."', `refund_details` ='".$pr_refund_details."' WHERE refund_request_id = '".$refund_request_id."'";
			
				mysqli_query($connect,$update_pr_sql);
				
				/* Update Details in `payment_transaction` DB Table */
				echo $update_pt_sql = "UPDATE `payment_transaction` SET `status` = '".$pt_status."',transaction_no = '".$transaction_no."' WHERE receipt_no = '".$MerchantOrderNo."' AND gateway = 'sbiepay' AND pay_type = '13' AND status = 2";
			
				mysqli_query($connect,$update_pt_sql);
				
				
				##Update log
				echo $log = "UPDATE refund_one SET response = '".$pr_result_str."' WHERE receipt_no = '".$MerchantOrderNo."'";
				mysqli_query($connect,$log);
				
				echo "<br />Refund successful<br/>End at: ".date("Y-m-d H:i:s");
			}
			else{
				
				echo $log = "UPDATE refund_one SET response = '".$pr_result_str."' WHERE receipt_no = '".$MerchantOrderNo."'";
				mysqli_query($connect,$log);
				
			}
		}
	}
} catch(Exception $e){ 
	echo $e->getMessage();
}

/* sbiqueryapi funcation for SBI */
function sbiqueryapi($MerchantOrderNo = '')
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
	$merchIdVal = "1000169"; //$this->config->item('sbi_merchIdVal');
	$AggregatorId = "SBIEPAY"; //$this->config->item('sbi_AggregatorId');

	$refundAmountCurrency = "INR";

	$refundRequest = $AggregatorId."|".$merchIdVal."|".$refundRequestId."|".$atrn."|".$refundAmount."|".$refundAmountCurrency."|".$MerchantOrderNo;
	
	$log = "INSERT INTO refund_request_log (receipt_no,request) VALUES('".$MerchantOrderNo."','".$refundRequest."')";
	mysqli_query($connect,$log);
						
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
	curl_close($ch);	
}
?>