<?php
/*
 * Controller Name	:	SBI MIS Report
 * Created By		:	Bhagwan Sahane
 * Created Date		:	
 *
 * Updated By		:	Bhagwan Sahane
 * Updated Date		:	14-09-2017	[ Changes done as per MIS API Updation ]
 * Updated Date		:	19-09-2017	[ Changes done as per MIS API Updation ]
 * Updated Date		:	05-04-2018	[ Bhushan A email id added ]
 * Updated Date		:	10-07-2020	[ API URL changed ]
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sbi_mis_report extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		//$this->load->model('log_model');
		$this->load->model('Emailsending');
		
		error_reporting(E_ALL);
		ini_set("display_errors", 1); 
	}
	
	// SBI ePay : MIS Report, Added by Bhagwan Sahane, on 13-03-2017
	public function index($txn_status = "ALL", $date = "")
	{
		//echo 'Welcome 5 <br>';
		
		if($date == "")
		{
			// previous day date
			$date = date("dmY", strtotime("- 1 day"));	// 25012017	
		}
		else
		{
			$date = trim($date);	
		}
		
		// Transaction Status : SUCCESS,FAILED,BOOKED,PENDING,ABORT,EXPIRED,REFUND,CLOSED
		if($txn_status == "ALL")
		{
			$txn_status = "";	// default blank for ALL txns	
		}
		else
		{
			$txn_status = trim($txn_status);	
		}
		
		$this->sbi_mis_report_api($txn_status, $date);
	}
	
	// SBI ePay API for query transaction
	private function sbi_mis_report_api($txn_status, $date)
	{
		$merchIdVal = $this->config->item('sbi_merchIdVal');
		$AggregatorId = $this->config->item('sbi_AggregatorId');
		
		//$service_url = $this->config->item('sbi_mis_report_api');
		//$service_url = "https://test.sbiepay.com/payagg/MISSettleReport/transactionMISAPI";	// TEST
		//$service_url = "https://www.sbiepay.com/payagg/MISSettleReport/transactionMISAPI";		// LIVE
		$service_url = "https://www.sbiepay.sbi/payagg/MISSettleReport/transactionMISAPI";		// LIVE
		
		//	queryRequest: SBIEPAY|MERCHANTID|DOM|IN|INR|ddmmyyyy|Transaction Status
		$post_param = "queryRequest=SBIEPAY|".$merchIdVal."|DOM|IN|INR|".$date."|".$txn_status;
		
		$ch = curl_init();       
		curl_setopt($ch, CURLOPT_URL,$service_url);              
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		$result = curl_exec($ch);
		
		$info = curl_getinfo($ch);
		
		curl_close($ch);
		
		//print_r($info);
		
		include('sbi_mcrypt_lib/sbi_epay/CryptAES.php');
		$aes = new CryptAES();
		
		//$key = "fIE7EwnuIt/DjAdz1yyfoA==";		// TEST
		$key = $this->config->item('sbi_m_key');	// LIVE
		
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		
		//echo "<Br><Br> Encrypted data = ".$result;
		
		if($result == 'No Record found')
		{
			die("No Record found.");	
		}
		
		$encData = $aes->decrypt($result);
		
		//echo "<Br> Decrypted data = ". $encData; die();
		
		require_once "sbi_mcrypt_lib/sbi_epay/xml_to_array.php";
		$xmlObj = new ArrayToXML();
		$result_array = $xmlObj->toArray($encData);
		
		//echo "<BR>";
		//print_r($result_array);
		
		$mis_file_path = 'uploads/sbi_mis_report/';
		
		if($txn_status == '')
		{
			$filename = "SBI_MIS_ALL_" . $date . ".csv";  // your path and file name
		}
		else
		{
			$filename = "SBI_MIS_SUCCESS_" . $date . ".csv";  // your path and file name	
		}
		
		$header = '';
		$createFile = fopen($mis_file_path . $filename,"w+");
		//foreach ($result_array['ORDERDETAILS']['ORDER'] as $row) {
		foreach ($result_array['ORDD']['ORD'] as $row) {	// Change added by Bhagwan Sahane, On 19-09-2017
			
			if(!$header) {
				fputcsv($createFile, array_keys($row));
				$header = 1;
			}
			else {
				fputcsv($createFile, array_values($row));
			}
		}
		fclose($createFile);
		
		echo "SBI MIS Report CSV generated successfully.";
		
		exit;
	}
	
	// validate sbi mis with payment txns
	public function sbi_mis_pay_txn_validate($date = "")
	{
		//echo 'Welcome Validation : 2 <br>';
		
		ini_set("memory_limit", "-1");
		
		$start_time = date("Y-m-d H:i:s");
		
		if($date == "")
		{
			// previous day date
			$date = date("dmY", strtotime("- 1 day"));	// 25012017
		}
		else
		{
			$date = trim($date);	
		}
		
		$success_txns_mis = array();
		$success_txns_db = array();
		
		$dra_success_txns_mis = array();
		$dra_success_txns_db = array();
		
		// mis csv file name
		$filename = "uploads/sbi_mis_report/SBI_MIS_SUCCESS_" . $date . ".csv";  // your path and file name
		
		if(!file_exists($filename))
		{
			die("File Not Found : " . $filename);	
		}
		
		// read mis csv file
		$file = fopen($filename, "r");
		
		// header row of csv
		fgetcsv($file);
		
		while(! feof($file))
		{
			//print_r(fgetcsv($file));
			
			$row = fgetcsv($file);
			
			// get all success txns from mis csv
			if($row[5] != '')
			{
				//$pg_flag_str = trim($row[19]);
				$pg_flag_str = trim($row[20]);	// Change added by Bhagwan Sahane, On 14-09-2017
				$pg_flag_arr = explode("^", $pg_flag_str);
				
				// check if DRA Payment Transaction
				if($pg_flag_arr[2] == 'iibfdra')
				{
					$dra_success_txns_mis[] = trim($row[5]);
				}
				else
				{
					$success_txns_mis[] = trim($row[5]);	
				}
			}
		}
		fclose($file);
		
		//echo "<pre>";
		//print_r($success_txns_mis);
		
		//echo "<pre>";
		//print_r($dra_success_txns_mis);
		
		$txn_success_sbi = array();
		$txn_success_iibf_portal_db = array();
		
		if(empty($success_txns_mis))
		{
			echo "<br>No Transactions found.";	
		}
		else
		{
			// get all success txns from payment transaction table
			$select_sql = "SELECT receipt_no FROM payment_transaction WHERE DATE_FORMAT(date, '%d%m%Y') = '".$date."' AND gateway = 'sbiepay' AND status = 1 ORDER BY id DESC";
			
			//echo $select_sql . "<br>";
			
			$select_result = $this->db->query($select_sql);
			
			if($select_result->num_rows() > 0)
			{
				foreach($select_result->result_array() as $row)
				{
					$success_txns_db[] = $row['receipt_no'];
				}
			}
			
			//echo "<pre>";
			//print_r($success_txns_db);
			
			// find array diff
			$txn_success_sbi = array_diff($success_txns_mis, $success_txns_db);
			
			//echo "<pre>";
			//print_r($txn_success_sbi);
			
			// find array diff
			$txn_success_iibf_portal_db = array_diff($success_txns_db, $success_txns_mis);
			
			//echo "<pre>";
			//print_r($txn_success_iibf_portal_db);
		}
		
		// ===================== DRA Payment Transactions =========================
		
		$dra_txn_success_sbi = array();
		$dra_txn_success_iibf_portal_db = array();
		
		if(empty($dra_success_txns_mis))
		{
			echo "<br>No DRA Transactions found.";	
		}
		else
		{
			// get all success txns from DRA payment transaction table
			$select_sql = "SELECT receipt_no FROM dra_payment_transaction WHERE DATE_FORMAT(date, '%d%m%Y') = '".$date."' AND gateway = '2' AND status = 1 ORDER BY id DESC";
			
			//echo $select_sql . "<br>";
			
			$select_result = $this->db->query($select_sql);
			
			if($select_result->num_rows() > 0)
			{
				foreach($select_result->result_array() as $row)
				{
					$dra_success_txns_db[] = $row['receipt_no'];
				}
			}
			
			//echo "<pre>";
			//print_r($dra_success_txns_db);
			
			// find array diff
			$dra_txn_success_sbi = array_diff($dra_success_txns_mis, $dra_success_txns_db);
			
			//echo "<pre>";
			//print_r($dra_txn_success_sbi);
			
			// find array diff
			$dra_txn_success_iibf_portal_db = array_diff($dra_success_txns_db, $dra_success_txns_mis);
			
			//echo "<pre>";
			//print_r($dra_txn_success_iibf_portal_db);
		}
		
		// ================ eof DRA Payment Transactions ========================================
		
		$end_time = date("Y-m-d H:i:s");
		
		// send email notification -
		$message = '';
		$message .= '<b><u>Payment Transactions :</u></b>';
		$message .= '<br><b>Payment Transactions Success at SBI ePay but not updated in IIBF Portal DB :</b> <br><br>';
		$message .= implode("<br>", $txn_success_sbi);
		$message .= '<br><br><b>Payment Transactions Success at IIBF Portal DB but not succeeded at SBI ePay :</b> <br><br>';
		$message .= implode("<br>", $txn_success_iibf_portal_db);
		
		$message .= '<br><br><b><u>DRA Payment Transactions :</b></u>';
		$message .= '<br><b>Payment Transactions Success at SBI ePay but not updated in IIBF Portal DB :</b> <br><br>';
		$message .= implode("<br>", $dra_txn_success_sbi);
		$message .= '<br><br><b>Payment Transactions Success at IIBF Portal DB but not succeeded at SBI ePay :</b> <br><br>';
		$message .= implode("<br>", $dra_txn_success_iibf_portal_db);
		
		$message .= '<br><br><b>Start Time :</b> ' . $start_time;
		$message .= '<br><b>End Time :</b> ' . $end_time;
		
		$from_name = 'IIBF';
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'SBI MIS Report Transaction Validation of ' . $date;
		
		// email receipient list -
		$recipient_list = array('iibfdevp@esds.co.in', 'chaitali.jadhav@esds.co.in');
		
		//$recipient_list = array('bvsahane89@gmail.com');
		
		$config = Array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
		
		$this->email->initialize($config);
		$this->email->from($from_email, $from_name); 
		$this->email->to($recipient_list);
		$this->email->subject($subject);
		$this->email->message($message);
		
		if($this->email->send())
		{
			echo '<br>Email Sent.<br>';
			
			//$this->email->print_debugger();
			//echo $this->email->print_debugger();
		}
		else
		{
			echo '<br>Email Not Sent.<br>';
		}
		
		echo "<br>SBI MIS Validaion Done !!!";
	}
}