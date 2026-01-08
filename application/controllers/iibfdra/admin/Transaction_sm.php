<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaction_sm extends CI_Controller {
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
		$this->load->model('Emailsending'); 
		$this->load->helper('dra_seatallocation_helper');
		$this->load->helper('dra_admitcard_helper');
		
	}
	
	public function transactions()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		//$data["exam_period_list"] = array_unique($this->Master_model->getRecords("dra_misc_master","","exam_period")); // remove duplicates from this array
		$data["exam_period_list"] = $this->db->query("SELECT DISTINCT(exam_period) FROM dra_misc_master WHERE misc_delete = '0'")->result_array();
		$data["institute_list"] = $this->Master_model->getRecords("dra_accerdited_master","accerdited_delete = '0'","institute_code,institute_name");
		$data["exam_list"] = $this->Master_model->getRecords("dra_exam_master","exam_delete = '0'","exam_code,description");
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Transactions</li>
		 </ol>';
		 
		$this->load->view('iibfdra/admin/transaction/transactions',$data);
	}
	
	// function to get list of All transactions-
	public function getTransactions()
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
		
		$reg_no = '';
		
		$where = '';
		if($value != "")
		{
			$temp_where = array();
			
			$post_data = explode('~',$value);
			
			if(count($post_data) > 0)
			{
				$reg_no			= isset($post_data[0]) ? $post_data[0] : '';
				$txn_no 		= isset($post_data[1]) ? $post_data[1] : '';
				$from_date 		= isset($post_data[2]) ? $post_data[2] : '';
				$to_date 		= isset($post_data[3]) ? $post_data[3] : '';
				$payment_mode 	= isset($post_data[4]) ? $post_data[4] : '';
				$payment_status = isset($post_data[5]) ? $post_data[5] : '';
				$inst_code 		= isset($post_data[6]) ? $post_data[6] : '';
				$exam_period 	= isset($post_data[7]) ? $post_data[7] : '';
				$exam_code 		= isset($post_data[8]) ? $post_data[8] : '';
				
				if($reg_no != "")
				{
					$temp_where[] = 'dra_members.regnumber = "'.$reg_no.'"';
				}
				
				if($txn_no != "")
				{
					$temp_where[] = 'transaction_no = "'.$txn_no.'" OR UTR_no = "'.$txn_no.'"';
				}
				
				if($from_date != "" && $to_date == "")
				{
					$temp_where[] = 'DATE(dra_payment_transaction.date) = "'.$from_date.'"';
				}
				else if($from_date != "" && $to_date != "")
				{
					$temp_where[] = '(DATE(dra_payment_transaction.date) BETWEEN "'.$from_date.'" AND "'.$to_date.'")';
				}
				
				if($payment_mode != "")
				{
					$temp_where[] = 'gateway = "'.$payment_mode.'"';
				}
				
				if($payment_status != "")
				{
					$temp_where[] = 'dra_payment_transaction.status = "'.$payment_status.'"';
				}
				else
				{
					$temp_where[] = '(dra_payment_transaction.status = "0" OR dra_payment_transaction.status = "1")';	// status = success or fail	
				}
				
				if($inst_code != "")
				{
					$temp_where[] = 'dra_payment_transaction.inst_code = "'.$inst_code.'"';
				}
				
				if($exam_period != "")
				{
					$temp_where[] = 'exam_period = "'.$exam_period.'"';
				}
				
				if($exam_code != "")
				{
					$temp_where[] = 'exam_code = "'.$exam_code.'"';
				}
				
				if( !empty($temp_where))
				{
					$where .= implode(" AND ", $temp_where);		
				}
			}
		}
		else
		{
			$where .= '(status = "0" OR status = "1")';	// status = success or fail
		}
		
	 	$select = 'dra_payment_transaction.id,gateway,dra_payment_transaction.inst_code,receipt_no,status,transaction_no,UTR_no,DATE_FORMAT(date,"%Y-%m-%d") As pay_date,bankcode,pay_count AS member_count,amount,dra_accerdited_master.institute_name AS inst_name';	
		
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		
		if($reg_no != "")
		{
			$this->db->join('dra_member_payment_transaction','dra_member_payment_transaction.ptid = dra_payment_transaction.id','LEFT');
			$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
			$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		}
		
		//$where .= ' ORDER BY date DESC';
		
		//do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		$where .= ' AND date(dra_payment_transaction.date) > "2017-01-08"';
		
		$this->db->where($where);
		
		// get total record count for pagination
		$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
		
		//$data['query1'] = $this->db->last_query();
		
		// transactions order by date in descending order -
		$sortkey = 'date';
		$sortval = 'DESC';
		
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		
		if($reg_no != "")
		{
			$this->db->join('dra_member_payment_transaction','dra_member_payment_transaction.ptid = dra_payment_transaction.id','LEFT');
			$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
			$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		}
		//do not show records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		$where .= ' AND date(dra_payment_transaction.date) > "2017-01-08"';
		
		$this->db->where($where);
			
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
		
		//$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();
			
			$result_new = array();
			
			foreach($result as $row)
			{
				foreach($row as $key => $value)
				{
					if($key == "status" && $value == 1) // status = 1 for Success
					{
						$row['status'] = "Successful Transaction";	
					}
					else if($key == "status" && $value == 0) // status = 0 for Error
					{
						$row['status'] = "Failure Transaction";	
					}
					
					if($key == "gateway" && $value == "1") // gateway = 1 for NEFT/RTGS
					{
						$row['transaction_no'] = $row['UTR_no'];
					}
					
					// if transaction no is empty -
					if($key == "transaction_no" || $key == "UTR_no")
					{
						if($row['transaction_no'] == "" && $row['UTR_no'] == "")
						{
							$row['transaction_no'] = 'NA';
						}
					}
					
					// if bankcode is empty -
					if($key == "bankcode") // gateway = 1 for NEFT/RTGS
					{
						if($row['bankcode'] == "")
						{
							$row['bankcode'] = 'NA';	
						}
					}
					
					// get reg nos. for each payment transaction -
					$reg_no_list = array();
					
					$select2 = 'dra_members.regnumber';	
					$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
					$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
					$this->db->where('dra_member_payment_transaction.ptid = '.$row['id']);
					$res2 = $this->UserModel->getRecords("dra_member_payment_transaction", $select2, '', '', '', '', '', '');
					$result2 = $res2->result_array();
					
					/*$select2 = 'dra_member_exam.regid';	
					$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
					$this->db->where('dra_member_payment_transaction.ptid = '.$row['id']);
					$res2 = $this->UserModel->getRecords("dra_member_payment_transaction", $select2, '', '', '', '', '', '');
					$result2 = $res2->result_array();*/
					
					//$data['query3'] = $this->db->last_query();
					
					foreach($result2 as $row2)
					{
						$reg_no_list[] = $row2['regnumber'];	
					}
					
					//$reg_nos = implode(",", $reg_no_list);
					
					//$row['paid_reg_nos'] = wordwrap($reg_nos,31,"<br>\n", TRUE);
					
					$reg_nos = "";
					$cnt = 0;
					if(count($reg_no_list) > 0 && $reg_no_list[0] != "")	// check if more than 1 reg. no.
					{
						$temp_arr = array();
						foreach($reg_no_list as $r)
						{
							$temp_list = '';
							
							$cnt++;
							$temp_list .= $r;
							if($cnt % 2 == 0)
								$temp_list .= "<br>";	// display 2 reg. nos. each line
								
							$temp_arr[] = $temp_list;
						}
						$reg_nos .= implode(',', $temp_arr);
					}
					else
					{
						$reg_nos .= "-";
					}
					$row['paid_reg_nos'] = $reg_nos;
				}
				
				$result_new[] = $row;
				
				// action -
				$action = '<a href="'.base_url().'iibfdra/admin/transaction/view_inst_receipt/'.base64_encode($row['id']).'" target="_blank">Receipt</a>';
				
				/******************* code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
				
				// get invoice image for this transaction
				$invoice_img_path = '';
				
				$exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $row['id']),'invoice_image');
				if(count($exam_invoice) > 0)
				{
					// generate exam invoice no
					$invoice_image = $exam_invoice[0]['invoice_image'];
					if($invoice_image)
					{
						$invoice_img_path = $invoice_image;
					}
				}
				
				if($invoice_img_path != '')
				{
					$action .= ' <br> <a href="'.base_url().'uploads/draexaminvoice/supplier/'.$invoice_img_path.'" target="_blank">Invoice</a>';
				}
				
				/******************* eof code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
				
				$data['action'][] = $action;
			}
			
			$data['result'] = $result_new;
			
			if(count($result_new))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."iibfdra/admin/transaction/getTransactions/";
			//$total_row = count($result_new);
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
	
	// function to view DRA institute payment receipt -
	public function view_inst_receipt($txn_id)
	{
		$txn_id = base64_decode($txn_id);
		
		$select = 'dra_payment_transaction.id,dra_payment_transaction.inst_code,receipt_no,gateway,transaction_no,UTR_no,amount,date,exam_period,status,dra_accerdited_master.institute_name AS inst_name,dra_accerdited_master.email AS inst_email'; 
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->where('dra_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
		
		$result = $res->result_array();
		
		$data['result'] = $result[0];
		 
		$this->load->view('iibfdra/admin/transaction/view_inst_receipt',$data);
	}
	
	// function to view member receipt list for DRA payment -
	public function mem_receipt_list($txn_id)
	{
		$txn_id = base64_decode($txn_id);
		
		$data['txn_id'] = $txn_id;
		
		// get payment transaction details -
		$select = 'dra_payment_transaction.id,dra_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,date,exam_period,status,dra_accerdited_master.institute_name AS inst_name,dra_accerdited_master.email AS inst_email'; 
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->where('dra_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
		
		$txn_result = $res->result_array();
		
		$total_amount = $txn_result[0]['amount'];
		
		$data['total_amount'] = $total_amount;
		
		// get list of all members for this payment transaction -
		$select = 'dra_members.regid,dra_members.regnumber,dra_members.firstname,dra_members.lastname,dra_members.email,dra_member_exam.exam_fee';	
		$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
		$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		$this->db->where('dra_member_payment_transaction.ptid = '.$txn_id);
		$res = $this->UserModel->getRecords("dra_member_payment_transaction", $select, '', '', '', '', '', '');
		
		$result = $res->result_array();
		
		$data['result'] = $result;
		 
		$this->load->view('iibfdra/admin/transaction/mem_receipt_list',$data);
	}
	
	// function to get member receipt details -
	public function mem_receipt($txn_id, $mem_id)
	{
		$txn_id = base64_decode($txn_id);
		$mem_id = base64_decode($mem_id);
		
		// get payment transaction details -
		$select = 'dra_payment_transaction.id,dra_payment_transaction.inst_code,receipt_no,gateway,transaction_no,UTR_no,pay_count,amount,date,exam_period,status,dra_accerdited_master.institute_name AS inst_name,dra_accerdited_master.email AS inst_email'; 
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->where('dra_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
		
		$txn_result = $res->result_array();
		
		$data['txn_details'] = $txn_result[0];
		
		// get list of all members for this payment transaction -
		$select = 'dra_members.regid,dra_members.regnumber,dra_members.firstname,dra_members.lastname,email,dra_member_exam.exam_fee';	
		$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
		$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		$this->db->where('dra_member_payment_transaction.ptid = '.$txn_id.' AND dra_members.regid = '.$mem_id);
		$res = $this->UserModel->getRecords("dra_member_payment_transaction", $select, '', '', '', '', '', '');
		
		$mem_result = $res->result_array();
		
		$data['mem_details'] = $mem_result[0];
		 
		$this->load->view('iibfdra/admin/transaction/mem_receipt',$data);
	}
	
	public function neft_transactions()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Approve NEFT Transactions</li>
		 </ol>';
		 
		$this->load->view('iibfdra/admin/transaction/neft_transactions_sm',$data);
	}
	
	// function to get list of NEFT transactions-
	public function getNeftTransactions()
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
		
		// transactions order by date in descending order -
		$sortkey = 'created_date';
		$sortval = 'DESC';
		
		// get total record count for pagination
		$this->db->select('count(*) as tot');
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_payment_transaction.exam_code','LEFT');
		$this->db->where('gateway = "1"');
		
		//do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		$this->db->where('date(dra_payment_transaction.date) > ','2017-01-08');
		
		$resArr = $this->db->get("dra_payment_transaction");
		if($resArr)
		{
			$result = $resArr->result_array();
		}
		$total_row = $result[0]["tot"];
		
		//$data['query1'] = $this->db->last_query();
		
		$select = 'dra_payment_transaction.id,UTR_no AS transaction_no,dra_exam_master.description AS DRA,pay_count AS member_count,amount,DATE_FORMAT(date,"%Y-%m-%d") As pay_date,exam_period,status,dra_accerdited_master.institute_name AS inst_name'; // "DRA" is Application in NEFT Transactions table in DRA Admin (Hard coded)
		
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_payment_transaction.exam_code','LEFT');
		$this->db->where('gateway = "1"');
		
		//do not include records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		$this->db->where('date(dra_payment_transaction.date) > ','2017-01-08');
		
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
		
		$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();
			
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				// check if approved by DRA Admin -
				if($row['status'] == 3)	// status = 3 for Applied
				{
					$action = '<span data-id="'.$row['id'].'"><a href="javascript:void(0)" onclick="confirmVerify('.$row['id'].');">Verify </a></span> <br><span data-id="'.$row['id'].'"><a href="getNeftTransactionFeeDetails/'.$row['id'].'" target="_blank">Details </a></span>';
				}
				else if($row['status'] == 1) // status = 1 for Success
				{
					$action = "Approved";	
				}
				else if($row['status'] == 0) // status = 0 for Rejected
				{
					$action = "Rejected";	
				}
				$data['action'][] = $action;
			}
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."iibfdra/admin/transaction_sm/getNeftTransactions/";
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
	
	// function to get NEFT transaction details by id -
	public function getNeftTransactionDetails()
	{
		$data['result'] = array();
		$data['success'] = '';
		
		$id = $this->input->post('id');
		$sortkey = 'created_date';
		$sortval = 'DESC';
		// "DRA" is Application in NEFT Transactions table in DRA Admin (Hard coded)
		$select = 'dra_payment_transaction.id,UTR_no AS transaction_no,dra_exam_master.description AS DRA,pay_count AS member_count,amount,DATE_FORMAT(date,"%Y-%m-%d") As date,exam_period,status,dra_accerdited_master.institute_name AS inst_name'; 
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_payment_transaction.exam_code','LEFT');
		$this->db->where('gateway = "1" AND dra_payment_transaction.id = "'.$id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', 'dra_payment_transaction.created_date', $sortval, '', '');
		if($res)
		{
			$result = $res->result_array();
			
			if(count($result))
				$data['success'] = 'success';
			else
				$data['success'] = '';
			
			$data['result'] = $result;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
		// function to get NEFT transaction details by id -
	public function getNeftTransactionFeeDetails()
	{
		$data['result'] = array();
		$data['success'] = '';
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		
		// "DRA" is Application in NEFT Transactions table in DRA Admin (Hard coded)
		$select = 'dra_payment_transaction.id,UTR_no AS transaction_no,dra_payment_transaction.amount,dra_members.firstname,dra_members.lastname,dra_members.middlename,dra_member_exam.exam_fee'; 
		$this->db->join('dra_member_payment_transaction','dra_member_payment_transaction.ptid = dra_payment_transaction.id','LEFT');
		$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
		$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		$this->db->where('gateway = "1" AND dra_payment_transaction.id = "'.$id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
		
		if($res)
		{
			$result = $res->result_array();
			
			if(count($result))
				$data['success'] = 'success';
			else
				$data['success'] = '';
			
			$data['result'] = $result;


		}
		else{
			$data['result']="No data found.";
		}

		$this->load->view('iibfdra/admin/transaction/transection_details',$data);
	}
	
	// function to approve/reject NEFT transaction -
	public function approveNeftTransactions()
	{		
		$flag = 0;
		$data = array();
		$sub_arr = array();
		if($this->input->post('id') && $this->input->post('action'))
		{		   
			$id = $this->input->post('id');
			$utr_no = $this->input->post('utr_no');
			$mem_count = $this->input->post('mem_count');
			$payment_amt = $this->input->post('payment_amt');
			$payment_date = $this->input->post('payment_date');
			
			$updte_data = array();			
			$updated_date = date('Y-m-d H:i:s');			
			
			//fetch all record for which we want to check capacity
			$admit_memexamidlst = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid' => $id));
			foreach($admit_memexamidlst as $admit_memexamids)
			{
				$mem_exam_id_arr[] = $admit_memexamids['memexamid'];
			}
			
			$this->db->where_in('mem_exam_id',$mem_exam_id_arr);
			$member_array = $this->master_model->getRecords('dra_admit_card_details',array('remark'=>2,'record_source'=>'bulk'));
			
			if($this->input->post('action') == "Approved")
			{			    
				$status = '1';
				$desc = 'Payment Success - Approved by Admin';	
				$data['success'] = 'swati';
				//break;
				$update_data = array(
									'status'		=> $status,
									'UTR_no'		=> $utr_no,
									'pay_count'		=> $mem_count,
									'amount'		=> $payment_amt,
									'date'			=> date("Y-m-d", strtotime($payment_date)),
									'description'	=> $desc,
								 	'updated_date'	=> $updated_date
								);
								
				log_dra_admin($log_title = "DRA Admin NEFT Approved Successfully", $log_message = serialize($update_data));
				
				// seat allocation code start
				$i = 0;
				foreach($member_array as $member_record)
				{
					$venue_code = $member_record['venueid'];
					$exam_date = $member_record['exam_date'];
					$center_code = $member_record['center_code'];
					$exam_time = $member_record['time'];
					$sub_code = $member_record['sub_cd'];
					$type = 'bulk';
					$capacity = dra_check_capacity_bulk_approve($venue_code,$exam_date,$exam_time,$center_code);
					//echo '>>'. $capacity;
					//exit;
						
					//echo "<br/>";
					if($capacity != 0)
					{
						// capacity available
						$sub_details = array("exam_code" => $member_record['exm_cd'], "center_code" => $center_code, "venue_code" => $venue_code, "exam_date" => $exam_date, "exam_time" => $exam_time, "mem_mem_no" => $member_record['mem_mem_no'], "admitcard_id" => $member_record['admitcard_id'],"sub_code"=>$member_record['sub_cd'],'exam_period'=>$member_record['exm_prd'],'member_exam_id'=>$member_record['mem_exam_id']);
						$sub_arr[] = $sub_details;
						$i++;
						
						$log_update_data = array(
									'regnumber'	=> $member_record['mem_mem_no'],
									'venue_code'=> $member_record['venueid'],
								 	'exam_date'	=> $member_record['exam_date'],
									'center_code'=> $member_record['center_code'],
									'exam_time'	=> $member_record['time'],
									'sub_code'	=> $member_record['sub_cd']
								);
								
						log_dra_admin($log_title = "one DRA Capacity available after NEFT Approval.", $log_message = serialize($log_update_data));
					}
					else
					{
						// capacity full						
						$flag = 1;
						
						$log_update_data_one = array(
									'regnumber'	=> $member_record['mem_mem_no'],
									'venue_code'=> $member_record['venueid'],
								 	'exam_date'	=> $member_record['exam_date'],
									'center_code'=> $member_record['center_code'],
									'exam_time'	=> $member_record['time'],
									'sub_code'	=> $member_record['sub_cd'],
									'approve_id'=> $id
								);
								
						log_dra_admin($log_title = "two DRA Capacity not available (Receipt No : ".$id.",UTR No :".$utr_no.") after NEFT Approval.", $log_message = serialize($log_update_data_one));
						
						$data['success'] = 'error1';
						break;
					}
					if($flag == 1)
					{
						break;	
					}
				} // end of member array forloop
				
				if($flag == 1)
				{
					/*echo "<br/>";
					echo "Flag one";
					echo "<br/>";*/
					$reject_status = 0;
					$mem_exam_str = implode(",",$mem_exam_id_arr);
					$desc = 'Payment Failed - Rejected by Admin';
					
					$this->db->query("update dra_member_exam set pay_status = 0 where id IN (".$mem_exam_str.")");
					
					// update bulk payment transaction table
					$update_payment_transaction_reject = array('status' => $reject_status,'updated_date'=>$updated_date,'description'=>$desc);
					$this->master_model->updateRecord('dra_payment_transaction',$update_payment_transaction_reject,array("id"=>$id));
					
					// update exam invoice table
					$update_exam_invoice_reject = array('transaction_no' => '');
					$this->master_model->updateRecord('exam_invoice',$update_exam_invoice_reject,array("pay_txn_id"=>$id));
					
					// update admit card details table
					$this->db->query("update dra_admit_card_details set remark = 4 where mem_exam_id IN (".$mem_exam_str.")");
					
					$log_update_data_two = array(
									'Receipt No'=> $id,
									'UTR'=> $utr_no
								);
								
						log_dra_admin($log_title = "three Flag one DRA Capacity not available (Receipt No : ".$id.",UTR No :".$utr_no.") after NEFT Approval.", $log_message = serialize($log_update_data_two));
					
					$data['success'] = 'error2';
					//break;
					
				}
				
				// below code execute if capacity is available for all member in runnig batch and allocate seatnumber
				if(count($member_array) > 0 && $flag == 0 && count($member_array) == count($sub_arr))
				{
					$j = 0;
					foreach($sub_arr as $sub_details)
					{
						//$password = random_password();		
						$v_code = $sub_details['venue_code'];
						$e_date = $sub_details['exam_date'];
						$e_time = $sub_details['exam_time'];
						$sub_code = $sub_details['sub_code'];
						$exam_code = $sub_details['exam_code'];
						$mem_mem_no = $sub_details['mem_mem_no'];
						$admitcard_id = $sub_details['admitcard_id'];
						$exam_period = $sub_details['exam_period'];
						$center_code = $sub_details['center_code'];
						
						// get venue details
						$get_venue_details=$this->master_model->getRecords('dra_venue_master',array('venue_code'=>$v_code,'exam_date'=>$e_date,'session_time'=>$e_time,'center_code'=>$center_code));
						
						$seat_allocation = getseat_dra($exam_code, $center_code, $v_code, $e_date, $e_time, $exam_period, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);
						
						//$seat_allocation = 2;
						if($seat_allocation != '')
						{
							// update admit_card_detail table
							//$update_seatno = array('seat_identification'=>$seat_allocation,'pwd' => $password);
							$update_seatno = array('seat_identification'=>$seat_allocation);
							$this->master_model->updateRecord('dra_admit_card_details',$update_seatno,array('admitcard_id'=>$admitcard_id));	
							$j++;
							$arr_cnt[] = $sub_details['admitcard_id'];
							$mem_cnt[] = $sub_details['mem_mem_no'];
							
							$log_update_data_three = array(
									'regnumber'	=> $mem_mem_no,
									'venue_code'=> $v_code,
								 	'exam_date'	=> $e_date,
									'center_code'=> $center_code,
									'exam_time'	=> $e_time,
									'sub_code'	=> $sub_code
								);
								
						log_dra_admin($log_title = "four DRA Capacity available after NEFT Approval and allocate seat number.", $log_message = serialize($log_update_data_three));
						
							//echo "seat allocation done=>".$password." # ".$mem_mem_no;
							//echo "<br/>";
						}
						else
						{
							// allocation fail
							$arr_cnt = array();
							$mem_cnt = array();
							//echo "seat no not generated";
							//echo "<br/>";
							$data['success'] = 'error3';
							$log_update_data_three = array(
									'regnumber'	=> $mem_mem_no,
									'venue_code'=> $v_code,
								 	'exam_date'	=> $e_date,
									'center_code'=> $center_code,
									'exam_time'	=> $e_time,
									'sub_code'	=> $sub_code
								);
								
							log_dra_admin($log_title = "five DRA Capacity not available after NEFT Approval and allocate seat number fail.", $log_message = serialize($log_update_data_three));
								
								
						}
					} // end of forloop of sub_arr
				}
				else
				{
					$log_update_data_four = array(
									'approve_id'=> $id
								);
								
					log_dra_admin($log_title = "six DRA Capacity not available after NEFT Approval and out of array of seat allocation loop.", $log_message = serialize($log_update_data_four));
					$data['success'] = 'error4';
					//echo "capacity not available";	
					//echo "<br/>";
				}
			}
			else if($this->input->post('action') == "Rejected")
			{
				$sub_arr = $member_array;
				$status = '0';
				$desc = 'Payment Failed - Rejected by Admin';
				
				$update_data = array(
									'status'		=> $status,
									'description'	=> $desc,
								 	'updated_date'	=> $updated_date
								);	
								
				log_dra_admin($log_title = "DRA Admin NEFT Rejected Successfully", $log_message = serialize($update_data));
			}
			
			/*echo "<br>sub_arr : ".count($sub_arr);
				echo "<br>member_array : ".count($member_array);
			echo "<br>flag : ".$flag; exit;  */
			
			// update required table
			if(count($sub_arr) == count($member_array) && $flag == 0 && (count($member_array) > 0 || $this->input->post('action') == "Rejected"))
			{
				if($this->master_model->updateRecord('dra_payment_transaction',$update_data,  array('id' => $id)))
				{
					//generate registration number for members if admin approves NEFT transaction
					if( $status == 1 ) //WHEN PAYMENT IS APPROVED
					{
						$memexamidlst = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid' => $id));
						if( count( $memexamidlst ) > 0 ) 
						{
							foreach( $memexamidlst as $memexamids ) 
							{
								$memexamid = $memexamids['memexamid'];
								$memregid = $this->master_model->getValue('dra_member_exam',array('id' => $memexamid), 'regid');
								if( $memregid ) 
								{
									$regnum = $this->master_model->getValue('dra_members',array('regid'=>$memregid),'regnumber');
									//code by swati-------
									//echo "<BR>regnum = ".$regnum;
									if( empty( $regnum ) ) 
									{
										//$memregnumber = generate_dra_reg_num();
										//$memregnumber = generate_nm_reg_num();
										$memregnumber = generate_NM_memreg($memregid);
										$update_data = array(
											'regnumber'		=> $memregnumber
										);	
										$this->master_model->updateRecord('dra_members',$update_data, array('regid' => $memregid));
										$update_data_admit = array(
											'mem_mem_no'		=> $memregnumber
										);	
										$this->master_model->updateRecord('dra_admit_card_details',$update_data_admit, array('mem_exam_id' => $memexamid)); 
										
										$log_update_data = array(
											'regnumber'		=> $memregnumber,
											'regid'			=> $memregid,
											
										);
										log_dra_admin($log_title = "DRA Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_data));
										
										//update uploaded file names which will include generated registration number
										//get cuurent saved file names from DB
										$currentpics = $this->master_model->getRecords('dra_members', array('regid'=>$memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate'); 									$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $trainingphoto_file = $qualiphoto_file = '';
											
										if( count($currentpics) > 0 ) 
										{
											$currentphotos = $currentpics[0];
											$scannedphoto_file = $currentphotos['scannedphoto'];
											$scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
											$idproofphoto_file = $currentphotos['idproofphoto'];
											$trainingphoto_file = $currentphotos['training_certificate'];
											$qualiphoto_file = $currentphotos['quali_certificate'];
										}
										$upd_files = array();
										$photo_file = 'p_'.$memregnumber.'.jpg';
										$sign_file = 's_'.$memregnumber.'.jpg';
										$proof_file = 'pr_'.$memregnumber.'.jpg';
										$quali_file = 'degre_'.$memregnumber.'.jpg';
										$training_file = 'traing_'.$memregnumber.'.jpg';
										if( !empty( $scannedphoto_file ) ) 
										{
											if(@ rename("./uploads/iibfdra/".$scannedphoto_file,"./uploads/iibfdra/".$photo_file))
											{	
												$upd_files['scannedphoto'] = $photo_file;	
											}
										}
										if( !empty( $scannedsignaturephoto_file ) ) 
										{
											if(@ rename("./uploads/iibfdra/".$scannedsignaturephoto_file,"./uploads/iibfdra/".$sign_file))
											{	
												$upd_files['scannedsignaturephoto'] = $sign_file;	
											}
										}
										if( !empty( $idproofphoto_file ) ) 
										{
											if(@ rename("./uploads/iibfdra/".$idproofphoto_file,"./uploads/iibfdra/".$proof_file))
											{	
												$upd_files['idproofphoto'] = $proof_file;	
											}
										}
										if( !empty( $qualiphoto_file ) ) {
											if(@ rename("./uploads/iibfdra/".$qualiphoto_file,"./uploads/iibfdra/".$quali_file))
											{	
												$upd_files['quali_certificate'] = $quali_file;	
											}
										}
										if( !empty( $trainingphoto_file ) ) 
										{
											if(@ rename("./uploads/iibfdra/".$trainingphoto_file,"./uploads/iibfdra/".$training_file))
											{	
												$upd_files['training_certificate'] = $training_file;	
											}
										}
										if(count($upd_files)>0)
										{
											log_dra_admin($log_title = "DRA Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));
											
											$this->master_model->updateRecord('dra_members',$upd_files,array('regid'=>$memregid));
										}
									}
								}
							}
						}
						
								
						/******************* code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
				
						// get invoice
						$exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $id),'invoice_id');
						
						if(count($exam_invoice) > 0 && $payment_amt > 0)
						{
							// generate exam invoice no
							//$invoice_no = generate_exam_invoice_number($exam_invoice[0]['invoice_id']);
							$invoice_no = generate_draexam_invoice_number($exam_invoice[0]['invoice_id']);
							if($invoice_no)
							{
								//$invoice_no = $this->config->item('exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
								$invoice_no = $this->config->item('draexam_invoice_no_prefix').$invoice_no;
							}
							
							//get payment date from dra_payment_transaction
							$payment_date = $this->master_model->getRecords('dra_payment_transaction',array('id' => $id),'date,UTR_no');
							
							// update invoice details
							$invoice_update_data = array('invoice_no' => $invoice_no,'transaction_no' => $payment_date[0]['UTR_no'],'date_of_invoice' => $payment_date[0]['date'],'modified_on' => $updated_date);
							$this->db->where('pay_txn_id',$id);
							$this->master_model->updateRecord('exam_invoice',$invoice_update_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
							
							log_dra_admin($log_title = "DRA Exam Invoice updated after NEFT Approval.", $log_message = serialize($invoice_update_data));
							
							// generate invoice image
							$invoice_img_path = genarate_draexam_invoice($exam_invoice[0]['invoice_id']);  
						}
						
						/******************* eof code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/


						//----------------code by swati------------------//
						$memexamidlst = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid' => $id));
						if( count( $memexamidlst ) > 0 ) 
						{
							foreach( $memexamidlst as $memexamids ) 
							{
								$memexamid = $memexamids['memexamid'];
								$memregid = $this->master_model->getValue('dra_member_exam',array('id' => $memexamid), 'regid');
								if( $memregid ) 
								{
									$regnum = $this->master_model->getValue('dra_members',array('regid'=>$memregid),'regnumber');
									
									$reattempt = $this->master_model->getValue('dra_members',array('regid'=>$memregid),'re_attempt');
									if($reattempt >= 1)
									{
										$re_attempt = $reattempt + 1;

											 $update_datas = array(
											're_attempt'		=> $re_attempt,
											'new_reg'		=> 0,
										);

								 }
								 else
								 {
										$re_attempt = $reattempt + 1;

											 $update_datas = array(
											're_attempt'		=> $re_attempt
										);
								}

								$this->master_model->updateRecord('dra_members',$update_datas, array('regid' => $memregid));
										
								$log_update_datas = array(
									'regid'			=> $memregid,
									're_attempt'			=> $re_attempt
								);
								log_dra_admin($log_title = "DRA Re-attempt successfully after NEFT Approval.", $log_message = serialize($log_update_datas));
									
							}
						 }
					 }					
						//------------code by swati end-------	
					}
					
					// update pay_status flag in dra_member_exam table -
					$this->db->query("UPDATE dra_member_exam, dra_member_payment_transaction SET dra_member_exam.pay_status = '".$status."' WHERE dra_member_exam.id = dra_member_payment_transaction.memexamid AND ptid = ".$id);
					
					$update_data = array(
										'pay_status' => $status,
										'ptid' => $id
									);
					log_dra_admin($log_title = "DRA Member Exam Payment Transaction Status updated after NEFT Approval.", $log_message = serialize($update_data));
					$data['success'] = 'success';				
					
					if( $status == 1 ) //WHEN PAYMENT IS APPROVED
					{
						// admit card generation and mail send code start
						foreach($sub_arr as $record)
						{
							// update admit_card_detail table
							$update_seatno_remark = array('remark'=>1,'modified_on'=>$updated_date);
							$this->master_model->updateRecord('dra_admit_card_details',$update_seatno_remark,array('admitcard_id'=>$record['admitcard_id']));	
						}// end of forloop 
						
						$parent_dir_flg = 0;
						$dir_flg = 0;
						$current_date = date("Ymd");	
						$cron_file_dir = "./uploads/dra_admitcardpdf_zip/";
						
						if(!file_exists($cron_file_dir.$current_date))
						{
							$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
						}
						
						if(file_exists($cron_file_dir.$current_date))
						{
							$cron_file_path = $cron_file_dir.$current_date;
							
							$dirname = "DRA_".$current_date."_".$utr_no."_".$mem_count;
							$directory = $cron_file_path.'/'.$dirname;
							
							if(file_exists($directory))
							{ 
								array_map('unlink', glob($directory."/*.*"));
								rmdir($directory);
								//"<br> <br> dir_flg if 7 ".$dir_flg = mkdir($directory, 0700);
							}
							else
							{
								//"<br> <br> dir_flg else 8 ".$dir_flg = mkdir($directory, 0700);
							}
							
							// Create a zip of images folder
							$zip = new ZipArchive;
							$zip->open($directory.'.zip', ZipArchive::CREATE);

							// Generate admitcard pdf call
							$this->db->group_by('mem_mem_no,exm_cd'); 
							$this->db->where_in('mem_exam_id',$mem_exam_id_arr);
							$member_array_admitcard = $this->master_model->getRecords('dra_admit_card_details',array('remark'=>'1','record_source'=>'bulk'),'mem_mem_no,exm_cd,exm_prd,mem_exam_id');
							
							/*echo '<pre>';
							print_r($member_array_admitcard);
							exit;*/
							
							foreach($member_array_admitcard as $member_array_record)
							{
								$attchpath_admitcard = genarate_admitcard_dra($member_array_record['mem_mem_no'],$member_array_record['exm_cd'],$member_array_record['exm_prd']);
								
								if($attchpath_admitcard != '')
								{ 
									// email sending code
									$new_attchpath_admitcard = substr($attchpath_admitcard,strrpos($attchpath_admitcard,'/') + 1);
									$zip->addFile($attchpath_admitcard,$new_attchpath_admitcard);							
								}
								
							}
							$zip->close();			
				
							$this->db->join("dra_accerdited_master am","am.institute_code = pt.inst_code","INNER");
							$email_data = $this->master_model->getRecords('dra_payment_transaction pt',array('pt.status'=>'1', 'pt.UTR_no'=>$utr_no),'pt.id, pt.inst_code, am.email, am.institute_name');
							/* echo $this->db->last_query(); exit; */
							
							if(count($email_data) > 0) 
							{
								if($email_data[0]['email'] != "")
								{
									$receiver_email = $email_data[0]['email']; //'pawansing.pardeshi@esds.co.in, bhushan.Amrutkar@esds.co.in, sagar.matale@esds.co.in, akshay.shirke@esds.co.in'; //;
									$attachpath = $directory.'.zip';		
									$info_arr = array('to'=>$receiver_email, 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Admit Card - UTR No. : '.$utr_no,'message'=>'PFA');
									$this->Emailsending->mailsend_attch($info_arr,$attachpath);
								}
							}
						}				
						// admit card generation and mail send code end				  
					}
				}
			}
			else
			{
				log_dra_admin($log_title = "DRA Admin NEFT Approved Failed.", $log_message = serialize($update_data));				
				$data['success'] = 'error';	
			}
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
}