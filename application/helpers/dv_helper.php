<?php
defined('BASEPATH')||exit('No Direct Allowed Here');

/* DV Call Funcation for Exam only : Bhushan */
function check_payment_status($member_num)
{
    $CI = & get_instance();
	$val = 0;
	return $val;
}

/* SBI call of : 'sbi query staus api'  : Bhushan */
function cron_sbiqueryapiCheck($MerchantOrderNo = NULL)
{
	$CI = & get_instance();
	
	if($MerchantOrderNo != NULL)
	{
		$merchIdVal = $CI->config->item('sbi_merchIdVal');
		$AggregatorId = $CI->config->item('sbi_AggregatorId');
		$atrn  = "";
		$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
		
		//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
		$service_url = $CI->config->item('sbi_status_query_api');
		$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
		$ch = curl_init();       
		curl_setopt($ch, CURLOPT_URL,$service_url);                                                 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
	else
	{
		return 0;
	}
	//print_r($response_array);
	//var_dump($result);   
	//exit;
}
function check_payment_status_bk($member_num)
{
    $CI = & get_instance();
	$val = 0;
	$interval = "15 MINUTE"; /* Set Interval */ 
	$get_status = "SELECT status, receipt_no FROM payment_transaction WHERE date >= NOW() - INTERVAL  ".$interval."  AND gateway = 'sbiepay' AND status = '2' AND pay_type = '2' AND member_regnumber = ".$member_num." ORDER BY id DESC";
	$pt_query = $CI->db->query($get_status);	
	//echo $last_query;
	
	if ($pt_query->num_rows())
	{
		foreach ($pt_query->result_array() as $row)
		{
			$receipt_no = $row['receipt_no']; 
			
			/* SBI call of : 'sbi query staus api' */
			$q_details = $CI->cron_sbiqueryapiCheck($MerchantOrderNo = $receipt_no);
			
			if ($q_details)
			{
				$tnx_status = $q_details[2]; // TXN Status
				if ($tnx_status == "SUCCESS")
				{
					$val = 1;
					
					/* SBI Response Values */
					$trn_no = $q_details[1];
					$pay_status = $q_details[2];
					$order_id = $q_details[6];
					$pay_amount = $q_details[7];
					$transaction_details = $q_details[8];
					$txn_date = $q_details[11];
					
					/* Insert in 'payment_status_query_tbl' table */
					$insert_payment_arr = array(
					'trn_no' => $trn_no,
					'pay_status' => $pay_status,
					'order_id' => $order_id,
					'pay_amount' => $pay_amount,
					'transaction_details' => $transaction_details,
					'rec_insert_date' => date('Y-m-d H:i:s')
					);
					$CI->master_model->insertRecord('dv_payment_refunds',$insert_payment_arr);
					
					/* Update in 'payment_transaction' table */
					$update_arr = array('status' => 0);
					$CI->master_model->updateRecord('payment_transaction',$update_arr,array('receipt_no'=>$order_id));
				}
				elseif($tnx_status == "FAIL")
				{
					$val = 0;
				}
				elseif($tnx_status == "ABORT")
				{
					$val = 0;
				}
				else
				{
					$val = 0;
				}
			}
		}
	}
	return $val;
}