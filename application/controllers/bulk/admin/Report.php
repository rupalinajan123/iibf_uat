<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
	}
	
	public function billdesk_success()
	{
		// check if download submitted -
		if($this->input->post('submit'))
		{
			$report_data = "";
			
			//$data .= "Transaction No,Receipt No,Amount,Date\n";
		
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if($from_date != "" && $to_date == "")
			{
				$date1 = $from_date;
				$date2 = $from_date;
			}
			else if($from_date != "" && $to_date != "")
			{
				$date1 = $from_date;
				$date2 = $to_date;	
			}
			
			$select = 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date';
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "1" AND gateway = "2"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
			
			if($res->num_rows() > 0)
			{
				$result = $res->result_array();
			
				foreach($result = $res->result_array() as $row) {
					$report_data .= $row['transaction_no'].",";
				  	$report_data .= $row['receipt_no'].",";
				  	$report_data .= $row['amount'].",";
				  	$report_data .= $row['date']."\n";
				}
		
				log_dra_admin($log_title = "DRA Success BillDesk downloaded", $log_message = "");
				
				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_DRA_Success_BillDesk.txt.gz"');
				echo gzencode($report_data, 9); exit();
			}
		}
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Success BillDesk</li>
		 </ol>';
		 
		$this->load->view('iibfdra/admin/report/billdesk_success',$data);
	}
	
	public function getBDSuccess()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		$select = 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date';
		if(strpos($value, '~') !== false)
		{
			$new_value = explode('~',$value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			
			if($value1 != "" && $value2 == "")
			{
				$date1 = $value1;
				$date2 = $value1;
			}
			else if($value1 != "" && $value2 != "")
			{
				$date1 = $value1;
				$date2 = $value2;	
			}
			
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "1" AND gateway = "2"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
			
			// get total record count for pagination
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "1" AND gateway = "2"');
			$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
			
			//$data['query1'] = $this->db->last_query();
		}
		else
		{
			$this->db->where('status','1');	// status = 1 for SUCCESS
			$this->db->where('gateway','2'); // gateway = 2 for SBIePay
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
			
			// get total record count for pagination
			$this->db->where('status','1');	// status = 1 for SUCCESS
			$this->db->where('gateway','2'); // gateway = 2 for SBIePay
			$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
			
			//$data['query1'] = $this->db->last_query();
		}
		
		//$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();
			
			$data['result'] = $result;
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."iibfdra/admin/report/getBDSuccess/";
			//$total_row = count($result);
			$config = pagination_init($url,$total_row, $per_page, 2);
			$this->pagination->initialize($config);
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
	public function billdesk_failure()
	{
		// check if download submitted -
		if($this->input->post('submit'))
		{
			$report_data = "";
			
			//$data .= "Transaction No,Receipt No,Amount,Date\n";
		
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if($from_date != "" && $to_date == "")
			{
				$date1 = $from_date;
				$date2 = $from_date;
			}
			else if($from_date != "" && $to_date != "")
			{
				$date1 = $from_date;
				$date2 = $to_date;	
			}
			
			$select = 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date';
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "0" AND gateway = "2"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
			
			if($res->num_rows() > 0)
			{
				$result = $res->result_array();
			
				foreach($result = $res->result_array() as $row) {
					
					if($row['transaction_no'] == "")
					{
						$transaction_no = 'NA';	
					}
					else
					{
						$transaction_no = $row['transaction_no'];	
					}
					
				 	$report_data .= $transaction_no.",";
				  	$report_data .= $row['receipt_no'].",";
				  	$report_data .= $row['amount'].",";
				  	$report_data .= $row['date']."\n";
				}
		
				log_dra_admin($log_title = "DRA Failure BillDesk downloaded", $log_message = "");
				
				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_DRA_Failure_BillDesk.txt.gz"');
				echo gzencode($report_data, 9); exit();
			}
		}
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Failure BillDesk</li>
		 </ol>';
		 
		$this->load->view('iibfdra/admin/report/billdesk_failure',$data);
	}
	
	public function getBDFailure()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		$select = 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date';
		if(strpos($value, '~') !== false)
		{
			$new_value = explode('~',$value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			
			if($value1 != "" && $value2 == "")
			{
				$date1 = $value1;
				$date2 = $value1;
			}
			else if($value1 != "" && $value2 != "")
			{
				$date1 = $value1;
				$date2 = $value2;	
			}
			
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "0" AND gateway = "2"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
			
			// get total record count for pagination
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "0" AND gateway = "2"');
			$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
			
			//$data['query1'] = $this->db->last_query();
		}
		else
		{
			$this->db->where('status','0');	// status = 0 for FAILURE
			$this->db->where('gateway','2'); // gateway = 2 for SBIePay
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
			
			// get total record count for pagination
			$this->db->where('status','0');	// status = 0 for FAILURE
			$this->db->where('gateway','2'); // gateway = 2 for SBIePay
			$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
			
			//$data['query1'] = $this->db->last_query();
		}
		
		//$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();
			
			$data['result'] = $result;
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."iibfdra/admin/report/getBDFailure/";
			//$total_row = count($result);
			$config = pagination_init($url,$total_row, $per_page, 2);
			$this->pagination->initialize($config);
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
	public function failure_reason()
	{
		// check if download submitted -
		if($this->input->post('submit'))
		{
			$report_data = "";
			
			$report_data .= "TxnReferenceNo,CustomerID,TxnAmount,TXNDATE,ErrorDescription\n";
		
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if($from_date != "" && $to_date == "")
			{
				$date1 = $from_date;
				$date2 = $from_date;
			}
			else if($from_date != "" && $to_date != "")
			{
				$date1 = $from_date;
				$date2 = $to_date;	
			}
			
			$select = 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,description';
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "0" AND gateway = "2"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
			
			if($res->num_rows() > 0)
			{
				$result = $res->result_array();
			
				foreach($result = $res->result_array() as $row) {
					
					if($row['transaction_no'] == "")
					{
						$transaction_no = 'NA';	
					}
					else
					{
						$transaction_no = $row['transaction_no'];	
					}
					
				 	$report_data .= $transaction_no.",";
				  	$report_data .= $row['receipt_no'].",";
				  	$report_data .= $row['amount'].",";
					$report_data .= $row['date'].",";
				  	$report_data .= $row['description']."\n";
				}
		
				log_dra_admin($log_title = "DRA Failure Reason BillDesk downloaded", $log_message = "");
				
				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_DRA_Failure_Reason_BillDesk.txt.gz"');
				echo gzencode($report_data, 9); exit();
			}
		}
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Failure Reason</li>
		 </ol>';
		 
		$this->load->view('iibfdra/admin/report/failure_reason',$data);
	}
	
	public function getFailureReason()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		$select = 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,description';
		if(strpos($value, '~') !== false)
		{
			$new_value = explode('~',$value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			
			if($value1 != "" && $value2 == "")
			{
				$date1 = $value1;
				$date2 = $value1;
			}
			else if($value1 != "" && $value2 != "")
			{
				$date1 = $value1;
				$date2 = $value2;	
			}
			
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "0" AND gateway = "2"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
			
			// get total record count for pagination
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "0" AND gateway = "2"');
			$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
			
			//$data['query1'] = $this->db->last_query();
		}
		else
		{
			$this->db->where('status','0');	// status = 0 for FAILURE
			$this->db->where('gateway','2'); // gateway = 2 for SBIePay
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
			
			// get total record count for pagination
			$this->db->where('status','0');	// status = 0 for FAILURE
			$this->db->where('gateway','2'); // gateway = 2 for SBIePay
			$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
			
			//$data['query1'] = $this->db->last_query();
		}
		
		//$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();
			
			$data['result'] = $result;
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."iibfdra/admin/report/getFailureReason/";
			//$total_row = count($result);
			$config = pagination_init($url,$total_row, $per_page, 2);
			$this->pagination->initialize($config);
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
	public function billdesk_neft_report()
	{
		// check if download submitted -
		if($this->input->post('submit'))
		{
			$report_data = "";
			
			$report_data .= "Biller Name\tDebit Type\tNarration\tPay Mode\tProduct Code\tBD Ref No\tOurID\tRef 1\tRef 2\tRef 3\tRef 4\tCreated On\tGross Amount (Rs.Ps)\tCharges (Rs.Ps)\tService Tax (Rs.Ps)\tSurcharge (Rs.Ps)\tTDS (Rs.Ps)\tNet Amount (Rs.Ps)\n";
		
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if($from_date != "" && $to_date == "")
			{
				$date1 = $from_date;
				$date2 = $from_date;
			}
			else if($from_date != "" && $to_date != "")
			{
				$date1 = $from_date;
				$date2 = $to_date;	
			}
			
			$select = 'UTR_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date';
			$this->db->where('(DATE(date) BETWEEN "'.$date1.'" AND "'.$date2.'") AND status = "1" AND gateway = "1"');
			$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
			
			if($res->num_rows() > 0)
			{
				$result = $res->result_array();
			
				foreach($result = $res->result_array() as $row) {
					$report_data .= "IIBF\t";
					$report_data .= "Net Banking\t";
					$report_data .= "NA\t";
					$report_data .= "NEFT\t";
					$report_data .= "DIRECT\t";
					
					$report_data .= $row['UTR_no']."\t";
					$report_data .= $row['receipt_no']."\t";
					$report_data .= $row['UTR_no']."\t";
					
					$report_data .= "iibfexam\t";
					$report_data .= "iibfdra\t";
					
					$report_data .= $row['receipt_no']."\t";
					$report_data .= $row['date']."\t";
					$report_data .= $row['amount']."\t";
					
					$report_data .= "0\t";
					$report_data .= "0\t";
					$report_data .= "0\t";
					$report_data .= "0\t";
					
					$report_data .= $row['amount']."\n";
				}
		
				log_dra_admin($log_title = "DRA BillDesk NEFT Report downloaded", $log_message = "");
				
				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_DRA_Billdesk_NEFT_Report.xls.gz"');
				echo gzencode($report_data, 9); exit();
			}
		}
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">NEFT BillDesk Report</li>
		 </ol>';
		 
		$this->load->view('iibfdra/admin/report/billdesk_neft_report',$data);
	}
	
}