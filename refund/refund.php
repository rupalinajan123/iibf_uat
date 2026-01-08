<?php
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
	$get_details = mysqli_query($connect,"SELECT * FROM `payment_transaction` WHERE `date` BETWEEN '2021-02-24 00:00:00.000000' AND '2021-02-28 00:00:00.000000' AND `status` IN (0,2)");
		
	while($get_details_row = mysqli_fetch_array($get_details))
	{
		
		$receipt_no = $get_details_row['receipt_no'];
	
		//to get trn no
		$q_result = sbiqueryapi($receipt_no); 
		echo "<BR><BR> q_result = "; print_r($q_result);	
		
		if($q_result[2] == 'SUCCESS')
		{
			$transaction_no = $q_result[1];
			$get_invoice_no = mysqli_query($connect,"SELECT transaction_no,invoice_no,invoice_image FROM `exam_invoice` WHERE receipt_no = '".$receipt_no."' LIMIT 1");
			$inv = mysqli_fetch_array($get_invoice_no);
			//print_r($inv);
			if($inv[0]['invoice_no'] == '' || $inv[0]['invoice_image'] == '' || $inv[0]['transaction_no'] == '')
			{
				
				echo $log = "INSERT INTO refund_status0or2_new (receipt_no,transaction_no) VALUES ('".$receipt_no."','".$transaction_no."')";
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


?>