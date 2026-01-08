<?php
/*
 * Controller Name	:	Cron CSC Exam Settlement
 * Created By		:	Priyanka Dhikale
 * Created Date		:	04-jan-2023
 * Last Update 		:   04-jan-2023
*/
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');
class Cron_csc_exam_settlement extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('general_helper');
			$this->load->model('master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model'); 
			$this->load->model('csc_exam_settl_model');
      		date_default_timezone_set("Asia/Kolkata");
      //  

    }

  
    public function customprocess()
    {
		$fromDateTime	=	date('Y-m-d H:i:s', strtotime('-30 minutes'));
		$toDateTime	=	date('Y-m-d H:i:s', strtotime('-15 minutes'));
    $status_url = "https://bridge.csccloud.in/v2/transaction/status";
    $reverse_url = "https://bridge.csccloud.in/v2/transaction/reverse";

    $status = 2;
    $foundPendingPaySuccess = 0;
		//$records = $this->master_model->getRecords('payment_transaction', array( 'gateway ' => 'csc','status' => $status, 'date >=' => $fromDateTime, 'date <=' => $toDateTime));
    $records = $this->master_model->getRecords('payment_transaction', array('receipt_no' => 44238139));
		$cron_file_path = "./uploads/rahultest/"; 
    $current_date = date('ymdhis');
		$file1 = "1-csc_exam_settlment_logs_" . $current_date . ".txt";
		$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
		//echo'<pre>';print_r($records);exit;
    if (count($records))
    {
			$final_str = 'Hello <br/><br/>';
			
      foreach ($records as $key => $value)
      {

        $checkPreviousRecordwithSameReceiptNo = $this->master_model->getRecords('payment_transaction', array('receipt_no ' => $value['receipt_no'], 'status' => '1', 'id != ' => $value['id']));
				//echo $this->db->last_query();
				//echo'<pre>';print_r($checkPreviousRecordwithSameReceiptNo);exit;
        if (count($checkPreviousRecordwithSameReceiptNo))
        {
          echo 'found duplicate';
          $log_title   = "csc admit card and invoice settle. >> Duplicate recipt no found :" . $value['receipt_no'];
							$log_message = serialize($records);
							$rId         = 0;
							$regNo       = $value['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
				}
				else 
				{
					$merchant_txn     	= 	$value['receipt_no'];
          $merchant_id    =  69910; //$value['customer_id'];
					$member_no			=	$value['member_regnumber'];
					//$csc_txn	=	'N';//$value['transaction_no'];
				//	echo'<pre>request=';print_r($value);
          $qry_api_response = $this->cscTransationStatusReverse('N', $merchant_id, $merchant_txn, $status_url, 'status', $member_no);
          echo '<pre>';
          print_r($qry_api_response);
          exit;
          if (count($qry_api_response) > 0 && array_key_exists('csc_txn', $qry_api_response) && $qry_api_response['response_status'] == 'Success')
          {
            $this->db->join('admit_card_details', 'admit_card_details.mem_exam_id=payment_transaction.ref_id');
            $this->db->where('admit_card_details.exm_cd', '991');
            $this->db->where('admit_card_details.exam_date < ', date('Y-m-d'));
            $check_if_exam_date_gone = $this->master_model->getRecords(
              'payment_transaction',
						array(
							'payment_transaction.id  '            => $value['id'],

              ),
              ''
            );
            if (count($check_if_exam_date_gone))
            {
							echo 'exam date is gone';
              $qry_api_response = $this->cscTransationStatusReverse($value['transaction_no'], $merchant_id, $merchant_txn, $reverse_url, 'reverse', $member_no);

							$log_title   = "csc reverse made as exam date is gone :" . $value['member_regnumber'];
								$log_message = serialize($value);
								$rId         = 0;
								$regNo       = $value['member_regnumber'];
							//	storedUserActivity($log_title, $log_message, $rId, $regNo);

						}
						else 
						{
              $foundPendingPaySuccess = 1;
              $response = $this->csc_exam_settl_model->settle($value['member_regnumber'], $value['receipt_no'], $status, $qry_api_response['csc_txn'], $qry_api_response['merchant_id'], $qry_api_response['merchant_txn'], $qry_api_response['product_id'], $qry_api_response['csc_id'], $file1);

              if ($response == 'reverse')
              {
                $qry_api_response = $this->cscTransationStatusReverse($value['transaction_no'], $merchant_id, $merchant_txn, $reverse_url, 'reverse', $member_no);

								$final_str .= 'csc_settelment_cron/process need to reverse >> details are  ' . json_encode($value);
								$final_str .= '<br/><br/>';
							}
              else
              {
                $payment_data2 = $this->master_model->getRecords(
                  'payment_transaction',
								array(
									'receipt_no'         => $value['receipt_no'],
								//   'status'                 => $status
                  )
                );
				
								$log_title   = "csc admit card and invoice settle :" . $payment_data2[0]['member_regnumber'];
								$log_message = serialize($records);
								$rId         = 0;
								$regNo       = $payment_data2[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);

								
								$final_str .= 'csc_settelment_cron/process done with admit card and invoice settlement >> details are  ' . json_encode($value);
								$final_str .= '<br/><br/>';
							}
						}
					}
				}

            }
      $final_str   .=  'File to check = ' . $cron_file_path . '/' . $file1;
					$final_str .= 'Regards,';
					$final_str .= '<br/>';
					$final_str .= 'ESDS TEAM';
      $info_arr = array(
        'to' => 'priyanka.dhikale@esds.co.in', //'iibfdevp@esds.co.in,priyanka.dhikale@esds.co.in',
						'from'                 => 'noreply@iibf.org.in',
						'subject'              => 'custom - csc settelment cron executed',
						'message'              => $final_str,
					);
      if ($foundPendingPaySuccess == 1)
				 	$this->Emailsending->mailsend_attch($info_arr);
        }

        $final_str = 'Hello Priyanka <br/><br/>';
        $final_str .= 'csc cron executed.<br/> Function name: csc_settelment_cron/process.<br/>Record count is- ' . count($records);
        $final_str .= '<br/><br/>';
        $final_str .= 'Regards,';
        $final_str .= '<br/>';
        $final_str .= 'ESDS TEAM';
    $info_arr = array(
      'to' => 'iibfdevp@esds.co.inpriyanka.dhikale@esds.co.in',
            'from'                 => 'noreply@iibf.org.in',
            'subject'              => 'csc settelment cron executed',
            'message'              => $final_str,
        );
        //$this->Emailsending->mailsend_attch($info_arr);

    }
	public function customprocess1()
    {
		$fromDateTime	=	date('Y-m-d H:i:s', strtotime('-30 minutes'));
		$toDateTime	=	date('Y-m-d H:i:s', strtotime('-15 minutes'));
    $status_url = "https://bridge.csccloud.in/v2/transaction/status";
    $reverse_url = "https://bridge.csccloud.in/v2/transaction/reverse-";

    $status = 2;
    $foundPendingPaySuccess = 0;
    $records = $this->master_model->getRecords('payment_transaction', array('receipt_no' => 56066992));

		$cron_file_path = "./uploads/rahultest/"; 
    $current_date = date('ymdhis');
		$file1 = "csc_exam_settlment_logs_" . $current_date . ".txt";
		$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
    if (count($records))
    {
			$final_str = 'Hello <br/><br/>';
			
      foreach ($records as $key => $value)
      {

        $checkPreviousRecordwithSameReceiptNo = $this->master_model->getRecords('payment_transaction', array('receipt_no ' => $value['receipt_no'], 'status' => '1', 'id != ' => $value['id']));
        if (count($checkPreviousRecordwithSameReceiptNo))
        {
          $log_title   = "csc admit card and invoice settle. >> Duplicate recipt no found :" . $value['receipt_no'];
							$log_message = serialize($records);
							$rId         = 0;
							$regNo       = $value['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
				}
				else 
				{
					$merchant_txn     	= 	$value['receipt_no'];
          $merchant_id    =  69910; //$value['customer_id'];
					$member_no			=	$value['member_regnumber'];
					//$csc_txn	=	'N';//$value['transaction_no'];
          echo '<pre>request=';
          print_r($value);
          $qry_api_response = $this->cscTransationStatusReverse('N', $merchant_id, $merchant_txn, $status_url, 'status', $member_no);
					
          if (count($qry_api_response) > 0 && array_key_exists('csc_txn', $qry_api_response) && $qry_api_response['response_status'] == 'Success')
          {
            $foundPendingPaySuccess = 1;
            $response = $this->csc_exam_settl_model->settle($value['member_regnumber'], $value['receipt_no'], $status, $qry_api_response['csc_txn'], $qry_api_response['merchant_id'], $qry_api_response['merchant_txn'], $qry_api_response['product_id'], $qry_api_response['csc_id'], $file1);
						//echo $response;exit;
            if ($response == 'reverse')
            {
              $qry_api_response = $this->cscTransationStatusReverse($value['transaction_no'], $merchant_id, $merchant_txn, $reverse_url, 'reverse', $member_no);

							$final_str .= 'csc_settelment_cron/process need to reverse >> details are  ' . json_encode($value);
							$final_str .= '<br/><br/>';
						}
            else
            {
              $payment_data2 = $this->master_model->getRecords(
                'payment_transaction',
							array(
								'receipt_no'         => $value['receipt_no'],
							//   'status'                 => $status
                )
              );
			
							$log_title   = "csc admit card and invoice settle :" . $payment_data2[0]['member_regnumber'];
							$log_message = serialize($records);
							$rId         = 0;
							$regNo       = $payment_data2[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);

							
							$final_str .= 'csc_settelment_cron/process done with admit card and invoice settlement >> details are  ' . json_encode($value);
							$final_str .= '<br/><br/>';
						}
						
						

					}
				}

            }
      $final_str   .=  'File to check = ' . $cron_file_path . '/' . $file1;
					$final_str .= 'Regards,';
					$final_str .= '<br/>';
					$final_str .= 'ESDS TEAM';
      $info_arr = array(
        'to' => 'priyanka.dhikale@esds.co.in', //'iibfdevp@esds.co.in,priyanka.dhikale@esds.co.in',
						'from'                 => 'noreply@iibf.org.in',
						'subject'              => 'csc settelment cron executed',
						'message'              => $final_str,
					);
      if ($foundPendingPaySuccess == 1)
				 	$this->Emailsending->mailsend_attch($info_arr);
        }

        $final_str = 'Hello Priyanka <br/><br/>';
        $final_str .= 'csc cron executed.<br/> Function name: csc_settelment_cron/process.<br/>Record count is- ' . count($records);
        $final_str .= '<br/><br/>';
        $final_str .= 'Regards,';
        $final_str .= '<br/>';
        $final_str .= 'ESDS TEAM';
    $info_arr = array(
      'to' => 'priyanka.dhikale@esds.co.in',
            'from'                 => 'noreply@iibf.org.in',
            'subject'              => 'csc settelment cron executed',
            'message'              => $final_str,
        );
       // $this->Emailsending->mailsend_attch($info_arr);

    }
	public function process()
    {
    $this->process_iibfbcbf(); exit;

		$fromDateTime	=	date('Y-m-d H:i:s', strtotime('-30 minutes'));
		$toDateTime	=	date('Y-m-d H:i:s', strtotime('-15 minutes'));
    $status_url = "https://bridge.csccloud.in/v2/transaction/status";
    $reverse_url = "https://bridge.csccloud.in/v2/transaction/reverse-";

    $status = 2;
    $foundPendingPaySuccess = 0;
    $records = $this->master_model->getRecords('payment_transaction', array('gateway ' => 'csc', 'status' => $status, 'date >=' => $fromDateTime, 'date <=' => $toDateTime));


    if (count($records))
    {
		$cron_file_path = "./uploads/rahultest/"; 
      $current_date = date('ymdhis');
		$file1 = "csc_exam_settlment_logs_" . $current_date . ".txt";
		$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
			$final_str = 'Hello <br/><br/>';
			
      foreach ($records as $key => $value)
      {

        $checkPreviousRecordwithSameReceiptNo = $this->master_model->getRecords('payment_transaction', array('receipt_no ' => $value['receipt_no'], 'status' => '1', 'id != ' => $value['id']));
        if (count($checkPreviousRecordwithSameReceiptNo))
        {
          $log_title   = "csc admit card and invoice settle. >> Duplicate recipt no found :" . $value['receipt_no'];
							$log_message = serialize($records);
							$rId         = 0;
							$regNo       = $value['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
				}
				else 
				{
					$merchant_txn     	= 	$value['receipt_no'];
          $merchant_id    =  69910; //$value['customer_id'];
					$member_no			=	$value['member_regnumber'];
					//$csc_txn	=	'N';//$value['transaction_no'];
          echo '<pre>request=';
          print_r($value);
          $qry_api_response = $this->cscTransationStatusReverse('N', $merchant_id, $merchant_txn, $status_url, 'status', $member_no);
					
          if (count($qry_api_response) > 0 && array_key_exists('csc_txn', $qry_api_response) && $qry_api_response['response_status'] == 'Success')
          {
            $foundPendingPaySuccess = 1;
            $response = $this->csc_exam_settl_model->settle($value['member_regnumber'], $value['receipt_no'], $status, $qry_api_response['csc_txn'], $qry_api_response['merchant_id'], $qry_api_response['merchant_txn'], $qry_api_response['product_id'], $qry_api_response['csc_id'], $file1);

            if ($response == 'reverse')
            {
              $qry_api_response = $this->cscTransationStatusReverse($value['transaction_no'], $merchant_id, $merchant_txn, $reverse_url, 'reverse', $member_no);

							$final_str .= 'csc_settelment_cron/process need to reverse >> details are  ' . json_encode($value);
							$final_str .= '<br/><br/>';
						}
            else
            {
              $payment_data2 = $this->master_model->getRecords(
                'payment_transaction',
							array(
								'receipt_no'         => $value['receipt_no'],
							//   'status'                 => $status
                )
              );
			
							$log_title   = "csc admit card and invoice settle :" . $payment_data2[0]['member_regnumber'];
							$log_message = serialize($records);
							$rId         = 0;
							$regNo       = $payment_data2[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);

							
							$final_str .= 'csc_settelment_cron/process done with admit card and invoice settlement >> details are  ' . json_encode($value);
							$final_str .= '<br/><br/>';
						}
						
						

					}
				}

            }
      $final_str   .=  'File to check = ' . $cron_file_path . '/' . $file1;
					$final_str .= 'Regards,';
					$final_str .= '<br/>';
					$final_str .= 'ESDS TEAM';
      $info_arr = array(
        'to' => 'priyanka.dhikale@esds.co.in', //'iibfdevp@esds.co.in,priyanka.dhikale@esds.co.in',
						'from'                 => 'noreply@iibf.org.in',
						'subject'              => 'csc settelment cron executed',
						'message'              => $final_str,
					);
      if ($foundPendingPaySuccess == 1)
				 	$this->Emailsending->mailsend_attch($info_arr);
        }

        $final_str = 'Hello Priyanka <br/><br/>';
        $final_str .= 'csc cron executed.<br/> Function name: csc_settelment_cron/process.<br/>Record count is- ' . count($records);
        $final_str .= '<br/><br/>';
        $final_str .= 'Regards,';
        $final_str .= '<br/>';
        $final_str .= 'ESDS TEAM';
    $info_arr = array(
      'to' => 'priyanka.dhikale@esds.co.in',
            'from'                 => 'noreply@iibf.org.in',
            'subject'              => 'csc settelment cron executed',
            'message'              => $final_str,
        );
       // $this->Emailsending->mailsend_attch($info_arr);

    }
	
  public function cscTransationStatusReverse($csc_txn, $merchant_id, $merchant_txn, $url, $flag, $member_no)
  {
		
    if ($flag == 'reverse')
			return 1;
		// include the BridgePGUtil file here
    require_once FCPATH . "/BridgePG/PHP_BridgePG/BridgePGUtil.php";
 
    $bconn = new BridgePGUtil();

		//echo "merchant_id=$merchant_id|merchant_txn=$merchant_txn|csc_txn=$csc_txn|";
		// Prepare JSON Post Data
		$data['merchant_id'] = $merchant_id;
		$data['merchant_txn'] = $merchant_txn;
		$data['csc_txn'] = $csc_txn;

    //echo '<pre>'; print_r($data);
		$message_text = '';
    foreach ($data as $p => $v)
    {
                        $message_text .= $p . '=' . $v . '|';
                    }
				//	echo $message_text.'<br>';
		$message_cipher = $bconn->encrypt($message_text);
	//	echo'<br>'.$message_cipher;
		//echo'<br>dec='.$bconn->decrypt($message_cipher);;
		$json_data_array = array(
		'merchant_id' => $merchant_id,
		'request_data' => $message_cipher
		);

		$post = json_encode($json_data_array);
		//https://bridge.csccloud.in/v2/transaction/status
		// cURL Request starts here
		$curl = curl_init();
		$headers = array('Content-Type: application/json');
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			// CURLOPT_URL => "https://bridgeuat.csccloud.in/cscbridge/v2/transaction/status",
			CURLOPT_URL => $url,
			CURLOPT_VERBOSE => true,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $headers,
			CURLINFO_HEADER_OUT => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
			//CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POST => 1,
			//CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_POSTFIELDS => $post
		));
    $server_output = curl_exec($curl);

		$info = curl_getinfo($curl);
    if (curl_errno($curl))
    {
			$error_msg = curl_error($curl);
			echo $error_msg;
		}
    //echo '<pre> original response='; print_r($server_output);
    if ($server_output)
    {
			$xml_response = simplexml_load_string($server_output); 
			$p = $bconn->decrypt($xml_response->response_data);
			
			$p = explode('|',  $p);
			
			$fine_params = array();
      foreach ($p as $param)
      {

				$param = explode('=', $param);
        if (isset($param[0]))
        {
          if (isset($param[1]))
					$fine_params[$param[0]] = $param[1];
				}   
			}
			$p = $fine_params;

			$xml_response = (array) $xml_response;
      //echo '<pre>response='; print_r($p);
      if ($flag == 'reverse' && count($p) > 0)
      {

        if ($csc_txn == $p['csc_txn'])
					{
          echo '<br>Reverse made';
						$add_data['txn_no'] = $csc_txn;
						$add_data['order_id'] = $merchant_txn;
          $add_data['txn_date'] = ''; //$txn_date;
						$add_data['txn_amt'] = $refund_deduction;
          $add_data['refund_reason'] = 'Some issue occured while setup admit card and invoice'; //$refund_reason;
						$add_data['refund_date'] = date("Y-m-d H:i:s");
						$add_data['refund_status'] = $p['status'];
						$add_data['merchant_id'] = $merchant_id;
						$add_data['merchant_txn'] = $merchant_txn;
						$add_data['merchant_reference'] = $p['merchant_reference'];
						$add_data['refund_reference'] = '';
						$add_data['csc_txn'] = $p['csc_txn'];
						$add_data['created_on'] = date("Y-m-d H:i:s");
				
          $this->master_model->insertRecord("csc_refund_details", $add_data);
						
						
          $update_data1 = array('status' => '3', 'transaction_details' => 'Refunded', 'transaction_no' => $csc_txn);
          $this->master_model->updateRecord('payment_transaction', $update_data1, array('transaction_no' => $csc_txn, 'receipt_no' => $merchant_txn));

						$log_title   = "csc admit card and invoice settle  - Refunded:" . $member_no;
						$log_message = serialize($p);
						$rId         = 0;
						$regNo       = $member_no;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
        }
      }

      if(count($p) > 0) { return $p; }
      else 
      { 
        $xml_response = new SimpleXMLElement($server_output);
        $jsonString_xml_response = json_encode($xml_response);
        $xml_response_array = json_decode($jsonString_xml_response, true);
        return $xml_response_array;
					}
			}
		}

  //START : THIS FUNCTION IS ADDED BY SAGAR & ANIL ON 2024-04-29 FOR CSC SETTLEMENT C_S2S CALL
  public function process_iibfbcbf()
  {
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
    $this->load->helper('file'); 
    $this->load->helper('getregnumber_helper');
    $this->load->model('log_model');    

    $fromDateTime  =  date('Y-m-d H:i:s', strtotime('-75 minutes'));
    $toDateTime  =  date('Y-m-d H:i:s', strtotime('-15 minutes'));
    $status_url = "https://bridge.csccloud.in/v2/transaction/status";
    $reverse_url = "https://bridge.csccloud.in/v2/transaction/reverse-";

    $status = 2;
    $foundPendingPaySuccess = 0;
    $records = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'status' => $status, 'date >=' => $fromDateTime, 'date <=' => $toDateTime));
    //echo $this->db->last_query(); exit;

    if (count($records))
    {
      $cron_file_path = "./uploads/rahultest/";
      $current_date = date('ymdhis');
      $file1 = "csc_iibfbcbf_exam_settlment_logs_" . $current_date . ".txt";
      $fp1 = fopen($cron_file_path . '/' . $file1, 'a');
      $final_str = 'Hello <br/><br/>';

      foreach ($records as $key => $value)
      {
        $checkPreviousRecordwithSameReceiptNo = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'receipt_no ' => $value['receipt_no'], 'status' => '1', 'id != ' => $value['id']));

        if (count($checkPreviousRecordwithSameReceiptNo))
        {
          $log_title   = "csc IIBFBCBF. >> Duplicate recipt no found :" . $value['receipt_no'];
          $log_message = serialize($records);
          $rId         = 0;
          $regNo       = $value['id'];
          storedUserActivity($log_title, $log_message, $rId, $regNo);

          $this->Iibf_bcbf_model->insert_common_log('CRON CSC PAYMENT CALLBACK', '', '', '0', 'CRON_csc_payment_callback','The csc wallet payment response received successfully', json_encode($qry_api_response));
	      }
        else
        {
          $merchant_txn =   $value['receipt_no'];
          $merchant_id =  69910; //$value['customer_id'];
          $member_no =  $value['id'];
          //$csc_txn	=	'N';//$value['transaction_no'];
          //echo '<pre>request='; print_r($value);

          $qry_api_response = $this->cscTransationStatusReverse('N', $merchant_id, $merchant_txn, $status_url, 'status', $member_no);
          //print_r($qry_api_response); exit;

          if (count($qry_api_response) > 0 && array_key_exists('csc_txn', $qry_api_response) && $qry_api_response['response_status'] == 'Success')
          {
            $foundPendingPaySuccess = 1;
            
            $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', '', '', '0', 'csc_payment_callback','The csc wallet payment response received successfully - C_S2S', json_encode($qry_api_response));
      
            $transaction_no = $qry_api_response['csc_txn'];
            $merchant_id = $qry_api_response['merchant_id'];
            $csc_id = $qry_api_response['csc_id'];
            $receipt_no = $qry_api_response['merchant_txn'];
            $txn_status = $qry_api_response['txn_status'];
            $merchant_txn_date_time = $qry_api_response['creation_date'];
            $product_id = $qry_api_response['product_id'];
            $txn_amount = $qry_api_response['txn_amount'];
            $merchant_receipt_no = $qry_api_response['merchant_txn'];
            $txn_status_message = $qry_api_response['response_status'];
            $status_message = $qry_api_response['response_status'];

            if ($txn_status == "100")
            {
              $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status, date');					
              
              if($payment_data[0]['status'] == '2')//IF payment status is PENDING
              {
                // START : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
                $update_data = array();
                $update_data['transaction_no'] = $transaction_no;
                $update_data['status'] = '1';
                $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
                $update_data['auth_code'] = '0300';
                $update_data['bankcode'] = 'csc';
                $update_data['paymode'] = 'wallet';
                $update_data['callback'] = 'C_S2S';						
                $update_data['description'] = 'Payment Success By CSC - C_S2S';
                $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
                $update_data['updated_on'] = date('Y-m-d H:i:s');
                $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
                
                $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is success - C_S2S', json_encode($update_data));
                // END : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              
                // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
                $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
          
                if(count($payment_info) > 0 && $payment_info[0]['status'] == '1')
                {
                  $member_exam_id = $payment_info[0]['exam_ids'];

                  //START : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT         
                  $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                  $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                  if(count($member_data) > 0)
                  {
                    $up_cand_data = array();

                    //START : GENERATE REGNUMBER AND RENAME THE IMAGES
                    $log_msg = '';
                    if($member_data[0]['regnumber'] == '')
                    {
                      $id_proof_file_path = 'uploads/iibfbcbf/id_proof';
                      $qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
                      $candidate_photo_path = 'uploads/iibfbcbf/photo';
                      $candidate_sign_path = 'uploads/iibfbcbf/sign';
                      
                      $current_id_proof_file = $member_data[0]['id_proof_file'];
                      $current_qualification_certificate_file = $member_data[0]['qualification_certificate_file'];
                      $current_candidate_photo = $member_data[0]['candidate_photo'];
                      $current_candidate_sign = $member_data[0]['candidate_sign'];              
                      
                      $up_cand_data['regnumber'] = $new_regnumber = generate_NM_memreg($member_data[0]['candidate_id']);
                      
                      if(!empty($current_id_proof_file)) 
                      {
                        $new_id_proof_file = 'id_proof_'.$new_regnumber.'.'.strtolower(pathinfo($current_id_proof_file, PATHINFO_EXTENSION));
                        $chk_rename_id_proof = $this->Iibf_bcbf_model->check_file_rename($current_id_proof_file, "./".$id_proof_file_path."/", $new_id_proof_file);

                        if($chk_rename_id_proof == 'success') { $up_cand_data['id_proof_file'] = $new_id_proof_file; }
                      }

                      if(!empty( $current_qualification_certificate_file)) 
                      {
                        $new_qualification_certificate_file = 'quali_cert_'.$new_regnumber.'.'.strtolower(pathinfo($current_qualification_certificate_file, PATHINFO_EXTENSION));                
                        $chk_rename_quali_cert = $this->Iibf_bcbf_model->check_file_rename($current_qualification_certificate_file, "./".$qualification_certificate_file_path."/", $new_qualification_certificate_file);

                        if($chk_rename_quali_cert == 'success') { $up_cand_data['qualification_certificate_file'] = $new_qualification_certificate_file; }
                      }

                      if(!empty($current_candidate_photo)) 
                      {
                        $new_candidate_photo = 'photo_'.$new_regnumber.'.'.strtolower(pathinfo($current_candidate_photo, PATHINFO_EXTENSION));
                        $chk_rename_photo = $this->Iibf_bcbf_model->check_file_rename($current_candidate_photo, "./".$candidate_photo_path."/", $new_candidate_photo);

                        if($chk_rename_photo == 'success') { $up_cand_data['candidate_photo'] = $new_candidate_photo; }
                      }

                      if(!empty( $current_candidate_sign)) 
                      {
                        $new_candidate_sign = 'sign_'.$new_regnumber.'.'.strtolower(pathinfo($current_candidate_sign, PATHINFO_EXTENSION));
                        $chk_rename_sign = $this->Iibf_bcbf_model->check_file_rename($current_candidate_sign, "./".$candidate_sign_path."/", $new_candidate_sign);

                        if($chk_rename_sign == 'success') { $up_cand_data['candidate_sign'] = $new_candidate_sign; }
                      }   
                      
                      $log_msg .= 'The regnumber is successfully generated, successfully rename the images';
                    }//END : GENERATE REGNUMBER AND RENAME THE IMAGES
                    
                    $up_cand_data['re_attempt'] = $member_data[0]['re_attempt'] + 1;//UPDATE RE-ATTEMT
                    $this->master_model->updateRecord('iibfbcbf_batch_candidates',$up_cand_data, array('candidate_id' => $member_data[0]['candidate_id']));
                    if($log_msg == "") { $log_msg .= 'The re-attempt is updated successfully'; }
                    else { $log_msg .= ' and re-attempt is updated successfully'; }

                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : generate new regnumber, rename the images and update re-attempt - C_S2S', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_data[0]['candidate_id'],'csc_payment_callback',$log_msg.' - C_S2S', json_encode($update_data));

                    //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                    $up_exam_data = array();
                    $up_exam_data['ref_utr_no'] = $transaction_no;
                    $up_exam_data['pay_status'] = '1';
                    if(isset($new_regnumber) && $new_regnumber != '') { $up_exam_data['regnumber'] = $new_regnumber; }
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update transaction number and payment status in member exam - C_S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The transaction number and payment status is successfully updated in member exam - C_S2S', json_encode($up_exam_data));

                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
                  }//END : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT
                  
                  // START : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
                  $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
                
                  if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                  {
                    $invoice_no = generate_iibfbcbf_exam_invoice_number($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php
                  
                    if($invoice_no)
                    {
                      $invoice_no = $this->config->item('iibfbcbf_exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
                    }
                    
                    $up_invoice_data['invoice_no'] = $invoice_no;
                    $up_invoice_data['transaction_no'] = $transaction_no;
                    $up_invoice_data['date_of_invoice'] = date('Y-m-d H:i:s');
                    $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                    $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update exam invoice number and image - C_S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The exam invoice number and image is successfully updated in exam invoice table - C_S2S', json_encode($up_invoice_data));          
                    
                    $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  
                  }// END : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
                  
                  $this->Iibf_bcbf_model->generate_admit_card_common(url_encode($payment_data[0]['id'])); //GENERATE ADMITCARD
                
                  $this->Iibf_bcbf_model->send_transaction_details_email_sms($payment_data[0]['id']);
                }
              }
            }
            else
            {
              $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status, date');					
              
              if($payment_data[0]['status'] == '2')//IF payment status is PENDING
              {
                // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
                $update_data = array();
                $update_data['transaction_no'] = $transaction_no;
                $update_data['status'] = '0';
                $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
                $update_data['auth_code'] = '0300';
                $update_data['bankcode'] = 'csc';
                $update_data['paymode'] = 'wallet';
                $update_data['callback'] = 'C_S2S';						
                $update_data['description'] = 'Payment Fail By CSC - C_S2S';
                $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
                $update_data['updated_on'] = date('Y-m-d H:i:s');
                $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
	
                $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is fail - C_S2S', json_encode($update_data));
                // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              
                // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
                $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
          
                if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
                {
                  $member_exam_id = $payment_info[0]['exam_ids'];

                  $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                  $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                  if(count($member_data) > 0)
                  {
                    //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                    $up_exam_data = array();
                    $up_exam_data['ref_utr_no'] = $transaction_no;
                    $up_exam_data['pay_status'] = '0';
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The csc wallet payment is fail and transaction number and payment status is updated in member exam - C_S2S', json_encode($up_exam_data));

                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
                  }
                  
                  // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                  $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
                
                  if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                  {
                    $up_invoice_data['transaction_no'] = $transaction_no;                
                    $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                    $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment - C_S2S', json_encode($up_invoice_data));
                  }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                }
              }
            }
          }
          else
          {
            $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', '', '', '0', 'csc_payment_callback_C_S2S','The csc wallet payment response not received - C_S2S', json_encode($qry_api_response));

            $transaction_no = ''; //$fine_params['csc_txn'];
            $merchant_id = $merchant_id;
            $csc_id = '';//$fine_params['csc_id'];
            $receipt_no = $merchant_txn;
            $txn_status = '';//$fine_params['txn_status'];
            $merchant_txn_date_time = '';// $fine_params['merchant_txn_date_time'];
            $product_id = ''; //$fine_params['product_id'];
            $txn_amount = ''; //$fine_params['txn_amount'];
            $merchant_receipt_no = '';//$fine_params['merchant_receipt_no'];
            $txn_status_message = $qry_api_response['response_code'].' - '.$qry_api_response['response_status'];
            $status_message = $qry_api_response['response_message']; //$fine_params['status_message'];

            // Handle transaction fail case 
            $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status');
            if($payment_data[0]['status'] == '2')//IF payment status is PENDING
            {
              // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              $update_data = array();
              $update_data['transaction_no'] = $transaction_no;
              $update_data['status'] = '0';
              $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
              $update_data['auth_code'] = '0300';
              $update_data['bankcode'] = 'csc';
              $update_data['paymode'] = 'wallet';
              $update_data['callback'] = 'C_S2S';						
              $update_data['description'] = 'The transaction was not completed by the csc centre';
              $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
              $update_data['updated_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback_C_S2S','The csc wallet payment is fail - C_S2S', json_encode($update_data));
              // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
            
              // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
              $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
        
              if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
              {
                $member_exam_id = $payment_info[0]['exam_ids'];

                $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                if(count($member_data) > 0)
                {
                  //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                  $up_exam_data = array();
                  $up_exam_data['ref_utr_no'] = $transaction_no;
                  $up_exam_data['pay_status'] = '0';
                  $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                  
                  $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'CRON_csc_payment_callback','The csc wallet payment is fail and transaction number and payment status is updated in member exam - C_S2S', json_encode($up_exam_data));

                  $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
                }
                
                // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
              
                if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                {
                  $up_invoice_data['transaction_no'] = $transaction_no;                
                  $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                  $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                  
                  $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'CRON_csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment - C_S2S', json_encode($up_invoice_data));
                }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
              }
            }
          }
        }
      }

      $final_str   .=  'File to check = ' . $cron_file_path . '/' . $file1;
      $final_str .= 'Regards,';
      $final_str .= '<br/>';
      $final_str .= 'ESDS TEAM';
      $info_arr = array(
        'to' => 'sagar.matale@esds.co.in', //'iibfdevp@esds.co.in,priyanka.dhikale@esds.co.in',
        'from'                 => 'noreply@iibf.org.in',
        'subject'              => 'csc settelment cron executed',
        'message'              => $final_str,
      );

      if ($foundPendingPaySuccess == 1)
        $this->Emailsending->mailsend_attch($info_arr);
    }
  }//END : THIS FUNCTION IS ADDED BY SAGAR & ANIL ON 2024-04-29 FOR CSC SETTLEMENT C_S2S CALL

  //START : THIS FUNCTION IS ADDED BY SAGAR & ANIL ON 2024-04-29 FOR CSC QUERY API
  public function csc_callback_iibfbcbf($receipt_no='')
  {
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
    $this->load->helper('file'); 
    $this->load->helper('getregnumber_helper');
    $this->load->model('log_model');    

    $status_url = "https://bridge.csccloud.in/v2/transaction/status";
    $reverse_url = "https://bridge.csccloud.in/v2/transaction/reverse-";

    $records = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'receipt_no' => $receipt_no));
    
    if (count($records))
    {
      foreach ($records as $key => $value)
      {
        $merchant_txn =   $value['receipt_no'];
        $merchant_id =  69910; //$value['customer_id'];
        $member_no =  $value['id'];
        //$csc_txn	=	'N';//$value['transaction_no'];
        //echo '<pre>request='; print_r($value);

        $qry_api_response = $this->cscTransationStatusReverse('N', $merchant_id, $merchant_txn, $status_url, 'status', $member_no);
        echo '<pre>'; print_r($qry_api_response); echo '</pre>';
      }
    }
    else
    {
      echo 'No record found for '.$receipt_no;
    }
  }//END : THIS FUNCTION IS ADDED BY SAGAR & ANIL ON 2024-04-29 FOR CSC QUERY API

  //START : THIS FUNCTION IS ADDED BY ANIL S ON 2025-03-18 FOR CSC SETTLEMENT C_S2S CALL for OLD BCBF Exam Code 1052,1053,1054
  public function process_old_bcbf()
  {
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
    $this->load->helper('file'); 
    $this->load->helper('getregnumber_helper');
    $this->load->model('log_model');    

    $fromDateTime  =  date('Y-m-d H:i:s', strtotime('-75 minutes'));
    $toDateTime  =  date('Y-m-d H:i:s', strtotime('-15 minutes'));
    $status_url = "https://bridge.csccloud.in/v2/transaction/status";
    $reverse_url = "https://bridge.csccloud.in/v2/transaction/reverse-";

    $status = 2;
    $foundPendingPaySuccess = 0;
    $records = $this->master_model->getRecords('payment_transaction', array('gateway' => 'csc', 'status' => $status, 'date >=' => $fromDateTime, 'date <=' => $toDateTime));
    //echo $this->db->last_query(); exit;

    if (count($records))
    {
      $cron_file_path = "./uploads/rahultest/";
      $current_date = date('ymdhis');
      $file1 = "csc_old_bcbf_exam_settlment_logs_" . $current_date . ".txt";
      $fp1 = fopen($cron_file_path . '/' . $file1, 'a');
      $final_str = 'Hello <br/><br/>';

      foreach ($records as $key => $value)
      {
        $checkPreviousRecordwithSameReceiptNo = $this->master_model->getRecords('payment_transaction', array('gateway' => 'csc', 'receipt_no ' => $value['receipt_no'], 'status' => '1', 'id != ' => $value['id']));

        if (count($checkPreviousRecordwithSameReceiptNo))
        {
          $log_title   = "csc OLD_BCBF. >> Duplicate recipt no found :" . $value['receipt_no'];
          $log_message = serialize($records);
          $rId         = 0;
          $regNo       = $value['id'];
          storedUserActivity($log_title, $log_message, $rId, $regNo);

          //$this->Iibf_bcbf_model->insert_common_log('CRON CSC PAYMENT CALLBACK', '', '', '0', 'CRON_csc_payment_callback','The csc wallet payment response received successfully', json_encode($qry_api_response));
        }
        else
        {
          $merchant_txn =   $value['receipt_no'];
          $merchant_id =  69910; //$value['customer_id'];
          $member_no =  $value['id'];
          //$csc_txn  = 'N';//$value['transaction_no'];
          //echo '<pre>request='; print_r($value);

          $qry_api_response = $this->cscTransationStatusReverse('N', $merchant_id, $merchant_txn, $status_url, 'status', $member_no);
          //print_r($qry_api_response); exit;

          if (count($qry_api_response) > 0 && array_key_exists('csc_txn', $qry_api_response) && $qry_api_response['response_status'] == 'Success')
          {
            $foundPendingPaySuccess = 1;
            
            //$this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', '', '', '0', 'csc_payment_callback','The csc wallet payment response received successfully - C_S2S', json_encode($qry_api_response));

            $log_title   = "The csc wallet payment response received successfully - C_S2S: " . $merchant_txn;
            $log_message = serialize($qry_api_response);
            $rId         = 0;
            $regNo       = $member_no;
            storedUserActivity($log_title, $log_message, $rId, $regNo);

      
            $transaction_no = $qry_api_response['csc_txn'];
            $merchant_id = $qry_api_response['merchant_id'];
            $csc_id = $qry_api_response['csc_id'];
            $receipt_no = $qry_api_response['merchant_txn'];
            $txn_status = $qry_api_response['txn_status'];
            $merchant_txn_date_time = $qry_api_response['creation_date'];
            $product_id = $qry_api_response['product_id'];
            $txn_amount = $qry_api_response['txn_amount'];
            $merchant_receipt_no = $qry_api_response['merchant_txn'];
            $txn_status_message = $qry_api_response['response_status'];
            $status_message = $qry_api_response['response_status'];

            if ($txn_status == "100")
            {
              $payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no, 'gateway'=>'csc'), 'id, status, date');         
              
              if($payment_data[0]['status'] == '2')//IF payment status is PENDING
              {
                // START : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
                $update_data = array();
                $update_data['transaction_no'] = $transaction_no;
                $update_data['status'] = '1';
                $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
                $update_data['auth_code'] = '0300';
                $update_data['bankcode'] = 'csc';
                $update_data['paymode'] = 'wallet';
                $update_data['callback'] = 'C_S2S';           
                $update_data['description'] = 'Payment Success By CSC - C_S2S';
                //$update_data['approve_reject_date'] = date('Y-m-d H:i:s');
                //$update_data['updated_on'] = date('Y-m-d H:i:s');
                $update_data['date'] = date('Y-m-d H:i:s');
                $update_data['csc_id'] = $csc_id;
                $update_data['product_id'] = $product_id;
                $update_data['merchant_receipt_no'] = $merchant_receipt_no; 

                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'gateway'=>'csc', 'id'=>$payment_data[0]['id']));
                
                //$this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is success - C_S2S', json_encode($update_data));

                $log_title   = "The csc wallet payment is success - C_S2S: " . $merchant_txn;
                $log_message = serialize($update_data);
                $rId         = 0;
                $regNo       = $payment_data[0]['id'];
                storedUserActivity($log_title, $log_message, $rId, $regNo);

                // END : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              
                // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
                $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no, 'gateway'=>'csc'), 'transaction_no, date, amount, id, description, status, exam_code, member_regnumber, receipt_no');
          
                if(count($payment_info) > 0 && $payment_info[0]['status'] == '1')
                {
                  $member_regnumber = $payment_info[0]['member_regnumber'];
                  $member_data = array();
                  if($member_regnumber){
                    $member_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_regnumber), 'regid,regnumber,namesub,firstname,middlename,lastname,email,registrationtype,mobile,scannedphoto,scannedsignaturephoto,idproofphoto,excode,exam_period,createdon,bank_bc_id_card');
                    if(isset($member_registration) && count($member_registration) > 0){

                    }else{
                      $member_data = $this->master_model->getRecords('member_registration', array('regid' => $member_regnumber), 'regid,regnumber,namesub,firstname,middlename,lastname,email,registrationtype,mobile,scannedphoto,scannedsignaturephoto,idproofphoto,excode,exam_period,createdon,bank_bc_id_card'); 
                    }
                  }


                  //START : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT         
                  //$this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                  //$member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                  if(count($member_data) > 0)
                  {
                    $up_cand_data = array();

                    //START : GENERATE REGNUMBER AND RENAME THE IMAGES
                    $log_msg = '';
                    if($member_data[0]['regnumber'] == '')
                    {
                       
                      //$up_cand_data['regnumber'] = $new_regnumber = generate_NM_memreg($member_data[0]['candidate_id']);

                      $up_cand_data['regnumber'] = $new_regnumber = generate_NM_memreg($member_data[0]['regid']);
                      $upd_files = array();
                      $photo_file = 'p_'.$new_regnumber.'.jpg';
                      $sign_file = 's_'.$new_regnumber.'.jpg';
                      $proof_file = 'pr_'.$new_regnumber.'.jpg';
                      $bank_bc_id_card_file = 'bank_bc_id_card_'.$new_regnumber.'.jpg';
                      /* if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
                      { $upd_files['scannedphoto'] = $photo_file; } */
                      $chk_photo = update_image_name("./uploads/photograph/", $result[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
                      if($chk_photo != "") { $upd_files['scannedphoto'] = $chk_photo; }
                      /* if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
                      { $upd_files['scannedsignaturephoto'] = $sign_file; } */
                      $chk_sign = update_image_name("./uploads/scansignature/", $result[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
                      if($chk_sign != "") { $upd_files['scannedsignaturephoto'] = $chk_sign; }
                      /* if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
                      { $upd_files['idproofphoto'] = $proof_file; } */
                      $chk_proof = update_image_name("./uploads/idproof/", $result[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
                      if($chk_proof != "") { $upd_files['idproofphoto'] = $chk_proof; }

                      $chk_bank_bc_id_card = update_image_name("./uploads/empidproof/", $result[0]['bank_bc_id_card'], $bank_bc_id_card_file); //update_image_name_helper.php
                      if($chk_bank_bc_id_card != "") { $upd_files['bank_bc_id_card'] = $chk_bank_bc_id_card; }
                      if(count($upd_files)>0)
                      {
                        $this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
                        $log_title ="CSC nonreg PICS Update :".$reg_id;
                        $log_message = serialize($this->db->last_query());
                        $rId = $reg_id;
                        $regNo = $reg_id;
                        storedUserActivity($log_title, $log_message, $rId, $regNo); 
                      }
                      else
                      {
                        $upd_files['scannedphoto'] = $photo_file;
                        $upd_files['scannedsignaturephoto'] = $sign_file; 
                        $upd_files['idproofphoto'] = $proof_file;
                        $upd_files['bank_bc_id_card'] = $bank_bc_id_card_file;
                        $this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
                        $log_title ="CSC nonreg MANUAL PICS Update :".$reg_id;
                        $log_message = serialize($upd_files);
                        $rId = $reg_id;
                        $regNo = $reg_id;
                        storedUserActivity($log_title, $log_message, $rId, $regNo); 
                      }    
                      
                      $log_msg .= 'The regnumber is successfully generated, successfully rename the images';
                    }//END : GENERATE REGNUMBER AND RENAME THE IMAGES
                    
                    $up_cand_data['re_attempt'] = $member_data[0]['re_attempt'] + 1;//UPDATE RE-ATTEMT
                    $this->master_model->updateRecord('iibfbcbf_batch_candidates',$up_cand_data, array('candidate_id' => $member_data[0]['candidate_id']));
                    if($log_msg == "") { $log_msg .= 'The re-attempt is updated successfully'; }
                    else { $log_msg .= ' and re-attempt is updated successfully'; }

                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : generate new regnumber, rename the images and update re-attempt - C_S2S', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_data[0]['candidate_id'],'csc_payment_callback',$log_msg.' - C_S2S', json_encode($update_data));

                    //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                    $up_exam_data = array();
                    $up_exam_data['ref_utr_no'] = $transaction_no;
                    $up_exam_data['pay_status'] = '1';
                    if(isset($new_regnumber) && $new_regnumber != '') { $up_exam_data['regnumber'] = $new_regnumber; }
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update transaction number and payment status in member exam - C_S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The transaction number and payment status is successfully updated in member exam - C_S2S', json_encode($up_exam_data));

                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
                  }//END : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT
                  
                  // START : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
                  $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
                
                  if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                  {
                    $invoice_no = generate_iibfbcbf_exam_invoice_number($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php
                  
                    if($invoice_no)
                    {
                      $invoice_no = $this->config->item('iibfbcbf_exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
                    }
                    
                    $up_invoice_data['invoice_no'] = $invoice_no;
                    $up_invoice_data['transaction_no'] = $transaction_no;
                    $up_invoice_data['date_of_invoice'] = date('Y-m-d H:i:s');
                    $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                    $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update exam invoice number and image - C_S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The exam invoice number and image is successfully updated in exam invoice table - C_S2S', json_encode($up_invoice_data));          
                    
                    $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  
                  }// END : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
                  
                  $this->Iibf_bcbf_model->generate_admit_card_common(url_encode($payment_data[0]['id'])); //GENERATE ADMITCARD
                
                  $this->Iibf_bcbf_model->send_transaction_details_email_sms($payment_data[0]['id']);
                }
              }
            }
            else
            {
              $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status, date');         
              
              if($payment_data[0]['status'] == '2')//IF payment status is PENDING
              {
                // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
                $update_data = array();
                $update_data['transaction_no'] = $transaction_no;
                $update_data['status'] = '0';
                $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
                $update_data['auth_code'] = '0300';
                $update_data['bankcode'] = 'csc';
                $update_data['paymode'] = 'wallet';
                $update_data['callback'] = 'C_S2S';           
                $update_data['description'] = 'Payment Fail By CSC - C_S2S';
                $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
                $update_data['updated_on'] = date('Y-m-d H:i:s');
                $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
                
                $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is fail - C_S2S', json_encode($update_data));
                // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              
                // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
                $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
          
                if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
                {
                  $member_exam_id = $payment_info[0]['exam_ids'];

                  $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                  $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                  if(count($member_data) > 0)
                  {
                    //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                    $up_exam_data = array();
                    $up_exam_data['ref_utr_no'] = $transaction_no;
                    $up_exam_data['pay_status'] = '0';
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The csc wallet payment is fail and transaction number and payment status is updated in member exam - C_S2S', json_encode($up_exam_data));

                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
                  }
                  
                  // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                  $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
                
                  if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                  {
                    $up_invoice_data['transaction_no'] = $transaction_no;                
                    $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                    $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                    
                    $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment - C_S2S', json_encode($up_invoice_data));
                  }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                }
              }
            }
          }
          else
          {
            $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', '', '', '0', 'csc_payment_callback_C_S2S','The csc wallet payment response not received - C_S2S', json_encode($qry_api_response));

            $transaction_no = ''; //$fine_params['csc_txn'];
            $merchant_id = $merchant_id;
            $csc_id = '';//$fine_params['csc_id'];
            $receipt_no = $merchant_txn;
            $txn_status = '';//$fine_params['txn_status'];
            $merchant_txn_date_time = '';// $fine_params['merchant_txn_date_time'];
            $product_id = ''; //$fine_params['product_id'];
            $txn_amount = ''; //$fine_params['txn_amount'];
            $merchant_receipt_no = '';//$fine_params['merchant_receipt_no'];
            $txn_status_message = $qry_api_response['response_code'].' - '.$qry_api_response['response_status'];
            $status_message = $qry_api_response['response_message']; //$fine_params['status_message'];

            // Handle transaction fail case 
            $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('gateway' => '3', 'receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status');
            if($payment_data[0]['status'] == '2')//IF payment status is PENDING
            {
              // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              $update_data = array();
              $update_data['transaction_no'] = $transaction_no;
              $update_data['status'] = '0';
              $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
              $update_data['auth_code'] = '0300';
              $update_data['bankcode'] = 'csc';
              $update_data['paymode'] = 'wallet';
              $update_data['callback'] = 'C_S2S';           
              $update_data['description'] = 'The transaction was not completed by the csc centre';
              $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
              $update_data['updated_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback_C_S2S','The csc wallet payment is fail - C_S2S', json_encode($update_data));
              // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
            
              // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
              $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
        
              if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
              {
                $member_exam_id = $payment_info[0]['exam_ids'];

                $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                if(count($member_data) > 0)
                {
                  //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                  $up_exam_data = array();
                  $up_exam_data['ref_utr_no'] = $transaction_no;
                  $up_exam_data['pay_status'] = '0';
                  $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                  
                  $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'CRON_csc_payment_callback','The csc wallet payment is fail and transaction number and payment status is updated in member exam - C_S2S', json_encode($up_exam_data));

                  $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
                }
                
                // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
              
                if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                {
                  $up_invoice_data['transaction_no'] = $transaction_no;                
                  $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                  $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                  
                  $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK - C_S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'CRON_csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment - C_S2S', json_encode($up_invoice_data));
                }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
              }
            }
          }
        }
      }

      $final_str   .=  'File to check = ' . $cron_file_path . '/' . $file1;
      $final_str .= 'Regards,';
      $final_str .= '<br/>';
      $final_str .= 'ESDS TEAM';
      $info_arr = array(
        'to' => 'sagar.matale@esds.co.in', //'iibfdevp@esds.co.in,priyanka.dhikale@esds.co.in',
        'from'                 => 'noreply@iibf.org.in',
        'subject'              => 'csc settelment cron executed',
        'message'              => $final_str,
      );

      if ($foundPendingPaySuccess == 1)
        $this->Emailsending->mailsend_attch($info_arr);
    }
  }//END : THIS FUNCTION IS ADDED BY ANIL S ON 2025-03-18 FOR CSC SETTLEMENT C_S2S CALL for OLD BCBF Exam Code 1052,1053,1054


}
