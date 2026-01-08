<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	08-01-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_status_cron extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->model('Emailsending');
		 $this->load->helper('custom_admitcard_helper');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
}

public function payment_count()
{
	$payment_data = '';
	$from_date = date('Y-m-d',strtotime("-1 days"));
	$to_date = date('Y-m-d',strtotime("-1 days")); 
	//$current_date = date("Ymd");
	if(!empty($from_date))
	{
			$select = 'exam_code,member_regnumber,gateway,amount,date,transaction_no,receipt_no,paymode,description,transaction_details,status';
			$this->db->where('DATE(date) >=', $from_date);
			$this->db->where('DATE(date) <=', $to_date);
			$payment_data = $this->Master_model->getRecords('payment_transaction','',$select);
			
			//echo $this->db->last_query(); die;

			
	}
	if(!empty($payment_data))
	{
			 $csv = 			                                  "Exam_code,Member_no,Gateway,Amount,Date,Transaction_no,Receipt_no,Paymode,Description,Transaction_Details,Status\n";
			
		
			$query = $this->db->query("Select id,exam_code,member_regnumber,gateway,amount,date,transaction_no,receipt_no,paymode,description,transaction_details,status
FROM `payment_transaction` WHERE DATE(payment_transaction.date) BETWEEN ('$from_date') AND ('$to_date') ");
			
			
		$result = $query->result_array(); 
		//print_r($result); die;
		foreach($result as $record)
		{
			 $csv.= $record['exam_code'].','.$record['member_regnumber'].','.$record['gateway'].','.$record['amount'].','.$record['date'].','.$record['transaction_no'].','.$record['receipt_no'].',"'.$record['paymode'].'","'.$record['description'].'","'.$record['transaction_details'].'",'.$record['status']."\n";
		}
		
				$filename = $from_date."payment_status.csv";
				$path = "uploads/payment_status/".$filename ."";
				$csv_handler = fopen($path, 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);
				
				$final_str = 'Hello Sir, <br/><br/>';
				$final_str.= 'Please find attached Payment Status sheet';   
				$final_str.= '&nbsp;';
				$final_str.= 'Of:- '.$from_date;
				//$final_str.= '&nbsp;';
				//$final_str.= 'To:- '.$to_date;
				$final_str.= '<br/><br/>';
				$final_str.= 'Thanks & Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				$attachpath = $path;
				
				$info_arr=array('to'=>'assistantdirectorit3@iibf.org.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF:Payment Status Report - '.$from_date,
								'message'=>$final_str
							); 
						
				$files=array($attachpath);
							
				if($this->Emailsending->mailsend_attch_paymentsheet($info_arr,$files))
			{
				
			}
												
			}
			else
			{ 
				
					$final_str = 'Hello Sir, <br/><br/>';
					$final_str.= 'There was no Payment Done ';   
					$final_str.= '&nbsp;';
					$final_str.= 'Of:- '.$from_date;
					//$final_str.= '&nbsp;';
					//$final_str.= 'To:- '.$to_date;
					$final_str.= '<br/><br/>';
					$final_str.= 'Thanks & Regards,';
					$final_str.= '<br/>';
					$final_str.= 'IIBF TEAM'; 
					
					
					$info_arr=array('to'=>'assistantdirectorit3@iibf.org.in',
									'from'=>'noreply@iibf.org.in',
									'subject'=>'IIBF:Payment Status Report - '.$from_date,
									'message'=>$final_str
								); 
						
			if($this->Emailsending->mailsend_attch_paymentsheet($info_arr,''))
				{
				}
					
			  }
}
}