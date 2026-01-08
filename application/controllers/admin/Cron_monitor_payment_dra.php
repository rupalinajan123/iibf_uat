<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_monitor_payment_dra extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		//$this->load->model('log_model');
		$this->load->model('Emailsending');
		
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
	
	/*
	SBI ePay : Monitor SBI ePay open transaction
		Algorithm:
			- find last to last 1 hr back open transactions
			- make SBI ePay query for those transactions and check the transactions status
	*/
	public function index()
	{
		$start_time = date("Y-m-d H:i:s");
		
		$interval1 = "1 HOUR";
		$interval2 = "2 HOUR";
		
		$all_txn = array();
		$success_txn = array();
		$failure_txn = array();
		
		$current_date = date("Y-m-d");
		
		// sql query 
		$sql = "SELECT receipt_no FROM dra_payment_transaction WHERE date >= NOW() - INTERVAL ".$interval2." AND date < NOW() - INTERVAL ".$interval1." AND gateway = '2' AND status = 2 ORDER BY id DESC";
		
		//echo $sql . "<br>";
		
		$pt_query = $this->db->query($sql);
		
		//$pt_query = $this->db->query("SELECT receipt_no FROM dra_payment_transaction WHERE date >= '2017-02-18 02:12:23' AND date < '2017-02-18 03:25:45' AND gateway = '2' AND status = 2 ORDER BY id DESC");
		
		$last_query = $this->db->last_query();
		
		echo $last_query . "<br>";
		
		if ($pt_query->num_rows())
		{
			$monitoring_log = '';
			
			foreach ($pt_query->result_array() as $row)
			{
				$all_txn[] = $row['receipt_no'];
				
				$receipt_no = $row['receipt_no'];  // order_no
				
				$q_details = $this->cron_sbiqueryapi($MerchantOrderNo = $receipt_no);
				
				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						$success_txn[] = $row['receipt_no'];
					}
					else if ($q_details[2] == "FAIL")
					{
						$failure_txn[] = $row['receipt_no'];
					}
					
					$pg_response = "".implode("|", $q_details);
					$monitoring_log .= $pg_response . "\n\n";
				}
				else
				{
					echo "Alert !!! SBI ePay Status Query API Down.<br>";
					
					// email notifictaion if sbi epay down
					$message = 'Alert !!! SBI ePay Status Query API Down at : ' . date("Y-m-d H:i:s");
					
					$from_name = 'IIBF';
					$from_email = 'noreply@iibf.org.in';
					$subject = 'Alert !!! SBI ePay Status Query API Down [DRA Payment Monitoring Cron]';
					
					// email receipient list -
					$recipient_list = array('bvsahane89@gmail.com', 'bhagwan.sahane@esds.co.in');
					
					$config = Array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
					
					$this->email->initialize($config);
					$this->email->from($from_email, $from_name);
					$this->email->to($recipient_list);
					$this->email->subject($subject);
					$this->email->message($message);
					
					if($this->email->send())
					{
						echo 'Email Sent.<br>';
						
						//$this->email->print_debugger();
						//echo $this->email->print_debugger();
					}
					else
					{
						echo 'Email Not Sent.<br>';
					}	
				}
			}
			
			$end_time = date("Y-m-d H:i:s");
			
			echo "<pre>All Txn : ";
			print_r($all_txn);
			echo "<br><br>Success Txn : ";
			print_r($success_txn);
			echo "<br><br>Failure Txn : ";
			print_r($failure_txn);
			echo "<br><br>";
			echo "</pre>";
			
			echo "Monitoring Log : <br> " . $monitoring_log;
			
			if(!empty($success_txn))
			{
				// send email notification -
				$message = '';
				$message .= '<b>Success Txn :</b> <br><br>';
				$message .= implode("<br>", $success_txn);
				$message .= '<br><br><b>Start Time :</b> ' . $start_time;
				$message .= '<br><b>End Time :</b> ' . $end_time;
				
				$from_name = 'IIBF';
				$from_email = 'noreply@iibf.org.in';
				$subject = 'DRA Payment Monitoring Cron';
				
				// email receipient list -
				$recipient_list = array('bvsahane89@gmail.com', 'bhagwan.sahane@esds.co.in');
				
				$config = Array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
				
				$this->email->initialize($config);
				$this->email->from($from_email, $from_name); 
				$this->email->to($recipient_list);
				$this->email->subject($subject);
				$this->email->message($message);
				
				if($this->email->send())
				{
					echo 'Email Sent.<br>';
					
					//$this->email->print_debugger();
					//echo $this->email->print_debugger();
				}
				else
				{
					echo 'Email Not Sent.<br>';
				}
			}
			
			// add monitoring logs in txt file
			$log_file = "dra_payment_monitoring_log_".$current_date.".txt";
			$fp = fopen('uploads/payment_monitoring_logs/'.$log_file, 'a');
			fwrite($fp, $monitoring_log . "\n\n");
			fclose($fp);
		}
		else
		{
			echo "No Transaction Found.";	
		}
	}
	
	// SBI ePay API for query transaction
	private function cron_sbiqueryapi($MerchantOrderNo = NULL)
	{
		if($MerchantOrderNo != NULL)
		{
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$atrn  = "";
	
			$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
			
			//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
			$service_url = $this->config->item('sbi_status_query_api');
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
	}
}