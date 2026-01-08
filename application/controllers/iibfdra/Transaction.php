<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaction extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_institute')) {
			redirect('iibfdra/InstituteLogin');
		}	
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	
	public function transactions()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/InstituteHome/dashboard"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Transactions</li>
		 </ol>';
		$data['middle_content']	= 'transaction/transactions';
		/* send active exams for display in sidebar */
		$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
		$res = $this->master_model->getRecords("dra_exam_master a");
		$data['active_exams'] = $res;
		$this->load->view('iibfdra/common_view',$data);
		//$this->load->view('iibfdra/transaction/transactions',$data);
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
		
		/* Display only current logged in institute transactions */
		$instdata = $this->session->userdata('dra_institute');
		$instcode = $instdata['institute_code'];
		if( !empty( $instcode ) ) {
			$where .= ' AND dra_payment_transaction.inst_code = '.$instcode;
		}
		
		//do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		$where .= ' AND date(dra_payment_transaction.date) > "2017-01-08"';
		
		$this->db->where($where);
		
		// get total record count for pagination
		$total_row = $this->UserModel->getRecordCount("dra_payment_transaction","","");
		
		//$data['query1'] = $this->db->last_query();
		
		// get transaction records
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		
		if($reg_no != "")
		{
			$this->db->join('dra_member_payment_transaction','dra_member_payment_transaction.ptid = dra_payment_transaction.id','LEFT');
			$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
			$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		}
		/* Display only current logged in institute transactions */
		$instdata = $this->session->userdata('dra_institute');
		$instcode = $instdata['institute_code'];
		if( !empty( $instcode ) ) {
			$where .= ' AND dra_payment_transaction.inst_code = '.$instcode;
		}
		
		// transactions order by date in descending order -
		$sortkey = 'date';
		$sortval = 'DESC';
		//do not show records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		$where .= ' AND date(dra_payment_transaction.date) > "2017-01-08"';
		$this->db->where($where);
		
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
		//print_r( $this );
		
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
					else if($key == "gateway" && $value == "1") // gateway = 1 for NEFT/RTGS
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
					
					$select = 'dra_members.regnumber';	
					$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
					$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
					$this->db->where('dra_member_payment_transaction.ptid = '.$row['id']);
					$res = $this->UserModel->getRecords("dra_member_payment_transaction", $select, '', '', '', '', '', '');
					$result2 = $res->result_array();
					
					/*$select = 'dra_member_exam.regid';	
					$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
					$this->db->where('dra_member_payment_transaction.ptid = '.$row['id']);
					$res = $this->UserModel->getRecords("dra_member_payment_transaction", $select, '', '', '', '', '', '');
					$result2 = $res->result_array();*/
					
					//$data['query3'] = $this->db->last_query();
					
					foreach($result2 as $row2)
					{
						$reg_no_list[] = $row2['regnumber'];	
					}
					
					//$reg_nos = implode(",", $reg_no_list);
					
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
				$action = '<a href="'.base_url().'iibfdra/transaction/view_inst_receipt/'.base64_encode($row['id']).'" target="_blank">Receipt</a>';
				
				/******************* code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
				
				// get invoice image for this transaction
				$invoice_img_path = '';
				
				$exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $row['id']),'invoice_image,date_of_invoice,gstin_no,invoice_no');
				if(count($exam_invoice) > 0)
				{
					// generate exam invoice no
					$invoice_image = $exam_invoice[0]['invoice_image'];
				 	$date_invoice = $exam_invoice[0]['date_of_invoice']; 
					if($invoice_image)
					{
						$invoice_img_path = $invoice_image;
					}
				}
				
				/*if($invoice_img_path != '' && $date_invoice < '2021-01-01' && $date_invoice !='')
				{
				   
					$action .= ' <br> <a href="'.base_url().'uploads/draexaminvoice/user/'.$invoice_img_path.'" target="_blank">Invoice</a> ';
				}*/
				## Code added by Pratibha
				$str_invoice_no = str_replace("/","_",$exam_invoice[0]['invoice_no']);
				if($invoice_img_path != '')
				{
					if($date_invoice < '2021-01-01' && $date_invoice !=''){
						$action .= ' <br> <a href="'.base_url().'uploads/draexaminvoice/user/'.$invoice_img_path.'" target="_blank">Invoice</a> ';
					}else{
						if($exam_invoice[0]['gstin_no'] == ''){
							$action .= ' <br> | <a href="'.base_url().'uploads/draexaminvoice/user/'.$invoice_img_path.'" target="_blank">Invoice</a>';
						}else{
							$action .= ' <br> | <a href="'.base_url().'iibfdra/transaction/getInvoice/'.base64_encode($str_invoice_no).'">E-Invoice</a>';
						}
					}
				}
				
				
				/******************* eof code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
				
				$data['action'][] = $action;
			}
			
			$data['result'] = $result_new;
			
			if(count($result_new))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."iibfdra/transaction/getTransactions/";
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
	public function getInvoice($inv_no)
	{
	
		//$inv_no = base64_decode($this->uri->segment(4));
		$inv_no = base64_decode($inv_no);
		## Test invoice no
		//$inv_no = 'EDN_20-21_000310';
		## Live Url

		//echo 'inv_no'.$inv_no; die;

        $service_url = 'http://10.10.233.76:8083/irnapi/getDataByDocNo/'.$inv_no;
        $curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, false);
		//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		$curl_response = curl_exec($curl);
		if (curl_errno($curl)) {
		    $error_msg = curl_error($curl);
		}
		//echo 'No Response Found';
		//print_r($error_msg); die;
		//echo 'No Response Found';
		//print_r($curl_response); die;
		curl_close($curl);
       	$json_objekat=json_decode($curl_response);
		$file_cont=base64_decode($json_objekat->signedPdf);
		header('Set-Cookie: fileLoading=true'); 
		header('Content-Type: application/pdf');
		header('Content-Length:'.strlen($file_cont));
		header('Content-disposition: attachment; filename=invoice.pdf');
		header('Content-Transfer-Encoding: Binary');
		echo $file_cont;

		//$this->session->set_flashdata('success','E-invoice downloaded successfully.');
		//$data['success'] = 'E-invoice downloaded successfully.';
		//redirect(base_url().'bulk/BulkTransaction/transactions');

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
		 
		$this->load->view('iibfdra/transaction/view_inst_receipt',$data);
	}
	
	// function to view member receipt list for DRA payment -
	public function mem_receipt_list($txn_id)
	{
		$txn_id = base64_decode($txn_id);
		
		$data['txn_id'] = $txn_id;
		
		// get payment transaction details -
		$select = 'dra_payment_transaction.id,dra_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status,dra_accerdited_master.institute_name AS inst_name,dra_accerdited_master.email AS inst_email'; 
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->where('dra_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
		
		$txn_result = $res->result_array();
		
		$total_amount = $txn_result[0]['amount'];
		
		$data['total_amount'] = $total_amount;
		
		// get list of all members for this payment transaction -
		$select = 'dra_members.regid,dra_members.regnumber,dra_members.firstname,dra_members.lastname,dra_members.email_id, dra_member_exam.exam_fee';	
		$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
		$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		$this->db->where('dra_member_payment_transaction.ptid = '.$txn_id);
		$res = $this->UserModel->getRecords("dra_member_payment_transaction", $select, '', '', '', '', '', '');
	
		$result = $res->result_array();
		$data['result'] = $result;
		 
		$this->load->view('iibfdra/transaction/mem_receipt_list',$data);
	}
	
	// function to get member receipt details -
	public function mem_receipt($txn_id, $mem_id)
	{
		$txn_id = base64_decode($txn_id);
		$mem_id = base64_decode($mem_id);
		
		// get payment transaction details -
		$select = 'dra_payment_transaction.id,dra_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,date,exam_period,status, gateway, UTR_no, dra_accerdited_master.institute_name AS inst_name,dra_accerdited_master.email AS inst_email'; 
		$this->db->join('dra_accerdited_master','dra_accerdited_master.institute_code = dra_payment_transaction.inst_code','LEFT');
		$this->db->where('dra_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("dra_payment_transaction", $select, '', '', '', '', '', '');
		
		$txn_result = $res->result_array();
		
		$data['txn_details'] = $txn_result[0];
		
		// get list of all members for this payment transaction -
		$select = 'dra_members.regid,dra_members.regnumber,dra_members.firstname,dra_members.middlename,dra_members.lastname,dra_members.email_id,dra_member_exam.exam_fee, dra_member_exam.exam_center_code, dra_member_exam.exam_medium';	
		$this->db->join('dra_member_exam','dra_member_exam.id = dra_member_payment_transaction.memexamid','LEFT');
		$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
		$this->db->where('dra_member_payment_transaction.ptid = '.$txn_id.' AND dra_members.regid = '.$mem_id);
		$res = $this->UserModel->getRecords("dra_member_payment_transaction", $select, '', '', '', '', '', '');
		
		$mem_result = $res->result_array();
		$memresult = $mem_result[0];
		
		$memresult['centername'] = '';
		$memresult['mediumname'] = '';
		if( $memresult ) {
			$mediumcode = $memresult['exam_medium'];
			$centercode = $memresult['exam_center_code'];
			$mediumname = $this->master_model->getValue('dra_medium_master',array('medium_code'=>$mediumcode), 'medium_description');
			$centername = $this->master_model->getValue('dra_center_master',array('center_code'=>$centercode), 'center_name');
			$memresult['centername'] = $centername;
			$memresult['mediumname'] = $mediumname;
		}
		
		$data['mem_details'] = $memresult;
		 
		$this->load->view('iibfdra/transaction/mem_receipt',$data);
	}
} ?>