<?php
defined('BASEPATH')||exit('No Direct Allowed Here');

/* DV Call Funcation for Exam only : Bhushan */
function check_blended_payment_status($member_num)
{
    $CI = & get_instance();
	$val = 0;
	$current_date = date('Y-m-d H:i:s'); // FOR - 20  minutes Diffirence Calculation
	$today_date = date('Y-m-d'); // FOR - SQL
	
	/* Current Date Txn Sql */
	$current_day_txn = "SELECT status, receipt_no, date FROM payment_transaction WHERE DATE(date) = '".$today_date."'  AND gateway = 'sbiepay' AND status = '2' AND pay_type = '10' AND member_regnumber = ".$member_num." ORDER BY id DESC LIMIT 1";
	$crnt_day_txn_qry = $CI->db->query($current_day_txn);
	
	//$last_query = $CI->db->last_query();
	//echo $last_query . "<br>";
	//exit;
	
	if ($crnt_day_txn_qry->num_rows())
	{
		foreach ($crnt_day_txn_qry->result_array() as $c_row)
		{
			$txn_date = $c_row['date']; 
			$pay_status = $c_row['status']; 
			$order_id = $c_row['receipt_no']; 
			
			/* 20  minutes Diffirence Calculation */
			$start_date = new DateTime($txn_date, new DateTimeZone('Asia/Kolkata'));
			$end_date = new DateTime($current_date, new DateTimeZone('Asia/Kolkata'));
			$interval = $start_date->diff($end_date);
			$hours   = $interval->format('%h'); 
			$minutes = $interval->format('%i');
			$minutes_diff = ($hours * 60 + $minutes);
			
			if($minutes_diff <= '20')
			{ 
				//echo "IF";
				/* SBI call of : 'sbi query staus api' */
				$q_details = cron_sbiqueryapiCheckblended($MerchantOrderNo = $order_id);
				
				if ($q_details)
				{
					$tnx_status = $q_details[2]; // TXN Status
			
					if ($tnx_status == "SUCCESS"){
						$val = 1;	
					}
					else{
						$val = 0;
					}
				}
				else
				{
					$val = 1;
				}	
			}
			else
			{
				//echo "ELSE";
				/* SBI call of : 'sbi query staus api' */
				$q_details = cron_sbiqueryapiCheckblended($MerchantOrderNo = $order_id);
				
				if ($q_details)
				{
					$tnx_status = $q_details[2]; // TXN Status
	
					if ($tnx_status == "SUCCESS")
					{
						$val = 0;
						
						/* SBI Response Values */
						$trn_no = $q_details[1];
						$pay_status = $q_details[2];
						$order_id = $q_details[6];
						$amount = $q_details[7];
						$transaction_details = $q_details[8];
						$txn_date = $q_details[11];
						
						/* Insert in 'payment_status_query_tbl' table */
						$insert_payment_arr = array(
						'trn_no' => $trn_no,
						'pay_status' => $pay_status,
						'order_id' => $order_id,
						'amount' => $amount,
						'transaction_details' => $transaction_details,
						'txn_date' => $txn_date,
						'rec_insert_date' => date('Y-m-d H:i:s')
						);
						$CI->master_model->insertRecord('dv_blended_payment_refunds',$insert_payment_arr);
						//echo "<br>INSERT => ".$CI->db->last_query();
						
						/* Update in 'payment_transaction' table */
						$update_arr = array('status' => 0);
						$CI->master_model->updateRecord('payment_transaction',$update_arr,array('receipt_no'=>$order_id));
						//echo "<br>UPDATE => ".$CI->db->last_query();
						
						//exit;
						
					}
					else{
						$val = 0;
					}
				}
			}
		}
	}
	return $val;
}

/* SBI call of : 'sbi query staus api'  : Bhushan */
function cron_sbiqueryapiCheckblended($MerchantOrderNo = NULL)
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
