<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report extends CI_Controller
{
	public $UserID;

	public function __construct()
	{
		parent::__construct();
		if ($this->session->id == "") {
			redirect('admin/Login');
		}

		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->UserID = $this->session->id;

		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('KYC_Log_model');
	}


	public function datewise()
	{
		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Member List</li>
							   </ol>';

		$this->load->view('admin/reg_list', $data);
	}

	public function getList()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		//$this->db->join('payment_transaction','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) { //echo "one";
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '"  AND isdeleted=0');
			/*
			- SAGAR WALZADE : Code start here
			- date : 27-4-2022
			- issue : result getting wrong with blank regnumbers. (need to add condition here)
			- change : below condition added -- regnumber blank is not allowed
			*/
			$this->db->where('regnumber !=', '');
			/* SAGAR WALZADE : Code end here */
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('isactive', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration a", '', '', 'regnumber');
		} else {  //echo "two";
			//$this->db->where('isactive','1');

			/*
			- SAGAR WALZADE : Code start here
			- date : 27-4-2022
			- issue : result getting wrong with blank regnumbers. (need to add condition here)
			- change : below condition added -- regnumber blank is not allowed
			*/
			$this->db->where('regnumber !=', '');
			/* SAGAR WALZADE : Code end here */

			$this->db->where('isdeleted', 0);
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('isactive', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration a", $field, $value);
		}

		$url = base_url() . "admin/Report/getList/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'regid,regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,DATE(createdon) createdon,usrpassword,isactive,registrationtype';
		//$this->db->join('payment_transaction','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND  isdeleted=0 ');
			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('isactive', 'DESC');
			}

			/*
			- SAGAR WALZADE : Code start here
			- date : 27-4-2022
			- issue : result getting wrong with blank regnumbers. (need to add condition here)
			- change : below condition added -- regnumber blank is not allowed
			*/
			$this->db->where('regnumber !=', '');
			/* SAGAR WALZADE : Code end here */

			$res = $this->UserModel->getRecords("member_registration a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('isactive','1');
			$this->db->where('isdeleted', 0);
			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('isactive', 'DESC');
			}

			/*
			- SAGAR WALZADE : Code start here
			- date : 27-4-2022
			- issue : result getting wrong with blank regnumbers. (need to add condition here)
			- change : below condition added -- regnumber blank is not allowed
			*/
			$this->db->where('regnumber !=', '');
			/* SAGAR WALZADE : Code end here */

			$res = $this->UserModel->getRecords("member_registration a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}
		//echo $field."----".$value;
		//echo $this->db->last_query();exit;
		$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;

			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			foreach ($result as $row) {
				$status = 0;
				/*$payment = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$row['regnumber'],'ref_id'=>$row['regid']),'status,date,id,transaction_no',array('id','ASC'),0,1);
				if(count($payment))
				{
					//$status = $payment[0]['status'];
				}*/

				// TRANSACTION DETAILS NOT USED
				$transaction_no = '';
				$transaction_date = '';
				$transaction_amt = '0';
				if ($row['registrationtype'] != 'NM') {
					if ($row['registrationtype'] == 'DB') {
						$trans_details = $this->Master_model->getRecords('payment_transaction a', array('pay_type' => 2, 'member_regnumber' => $row['regnumber']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date', array('id', 'ASC'));
						//'pg_flag'=>'IIBF_EXAM_DB',
					} else {
						$trans_details = $this->Master_model->getRecords('payment_transaction a', array('pay_type' => 1, 'ref_id' => $row['regid']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date', array('id', 'ASC'));
						//'pg_flag'=>'iibfregn',
					}
				} else {
					$trans_details = $this->Master_model->getRecords('payment_transaction a', array('pay_type' => 2, 'member_regnumber' => $row['regnumber']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date', array('id', 'ASC'));
					//'pg_flag'=>'IIBF_EXAM_REG',
				}
				if (count($trans_details)) {
					$transaction_no = $trans_details[0]['transaction_no'];
					$transaction_amt = $trans_details[0]['amount'];
					$date = $trans_details[0]['date'];
					if ($date != '0000-00-00') {
						$transaction_date = date('d-M-y', strtotime($date));
					}
				}


				$status = $row['isactive'];
				if ($status == 1)
					$result[$i]['status'] = 'Completed';
				/*else if($status==2)
					$result[$i]['status'] = 'Pending';*/
				else
					$result[$i]['status'] = 'Incomplete';

				$decpass = $aes->decrypt(trim($row['usrpassword']));
				$result[$i]['usrpassword'] = $decpass;

				$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '" target="_blank">' . $row['regnumber'] . '</a>';
				$confirm = "return confirm('Are you sure to delete this record?');";
				$result[$i]['regnumber'] = $regnumber;

				if ($status == 1) {
					$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="' . base_url() . 'admin/Report/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
				} else {
					$action = '';
					$send_mail = '';
				}
				//Customized columns

				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				if ($row['createdon'] != '' && $row['createdon'] != '0000-00-00') {
					$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));
				} else {
					$result[$i]['createdon'] = '';
				}


				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}


		$json_res = json_encode($data);
		echo $json_res;
	}

	// By VSU : Function to get Successful registration report (Success Billdesk of SIFFY)
	public function reg_success()
	{
		ini_set("memory_limit", "-1");

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		if (isset($_POST['download'])) {
			$data['result'] = array();
			$field = '';
			$value = '';
			$sortkey = '';
			$sortval = '';
			$from_date = '';
			$to_date = '';

			if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
				$from_date = $_POST['from_date'];
			}

			if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
				$to_date = $_POST['to_date'];
			}

			$select = 'regid,regnumber,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,registrationtype';
			//$this->db->join('member_registration','ref_id = regid AND member_regnumber = regnumber','LEFT');
			if ($from_date != '' && $to_date != '') {
				$this->db->where('DATE(createdon) BETWEEN "' . $from_date . '" AND "' . $to_date . '" AND isactive = "1" AND isdeleted=0');
				//isactive = "1" AND
			} else if ($from_date != '' & $to_date == '') {
				$this->db->where('DATE(createdon) = "' . $from_date . '" AND isactive = "1" AND isdeleted=0');
				//isactive = "1" AND
			} else {
				$this->db->where('isactive = "1" AND isdeleted=0');
			}
			$res = $this->master_model->getRecords("member_registration a", '', $select, array('createdon' => 'DESC'));
			//echo $this->db->last_query();

			if (count($res)) { //echo "111";
				$data = "";
				foreach ($res as $row) {
					// Payment Data
					if ($row['registrationtype'] == 'NM' || $row['registrationtype'] == 'DB') {
						$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 2, 'status' => 1, 'member_regnumber' => $row['regnumber']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no', array('id', 'ASC'));
						//'pg_flag'=>'IIBF_EXAM_DB',
					} else {
						$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 1, 'ref_id' => $row['regid'], 'member_regnumber' => $row['regnumber'], 'status' => 1), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no', array('id', 'ASC'));
						//'pg_flag'=>'iibfregn',
					}
					//$payment_res = $this->master_model->getRecords('payment_transaction b',array('member_regnumber'=>$row['regnumber'],'pay_type'=>1,'status'=>1),'DATE_FORMAT(date,"%d-%m-%Y") date,b.transaction_no,amount,b.receipt_no');
					if (count($payment_res) > 0) {
						$data .= $payment_res[0]['transaction_no'] . ",";
						$data .= $payment_res[0]['receipt_no'] . ",";
						$data .= $payment_res[0]['amount'] . ",";
						if ($payment_res[0]['date'] != '' && $payment_res[0]['date'] != '0000-00-00') {
							$data .= date('Ymd', strtotime($payment_res[0]['date'])) . "\n";
						} else {
							$data .= "0000-00-00" . "\n";
						}
					}
				}

				logadminactivity($log_title = "Registration Success Report Downloaded", $log_message = "");

				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_Settlement_' . date('YmdHis') . '.txt.gz"');
				echo gzencode($data, 9);
				exit();
			}
			/*header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename="default-filename.txt"');	*/
		}

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Success Registrations</li>
							   </ol>';

		$this->load->view('admin/reg_success_list', $data);
	}

	// By VSU : Function to get Successful registration report (Success Billdesk of SIFFY)
	public function getRegSuccess()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		//$this->db->join('member_registration a','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 1 ');
			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0');
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('isactive', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration a", '', '', 'regnumber');
		} else {
			//$this->db->where('isdeleted',0);
			$this->db->where(' isactive = "1" AND isdeleted=0');
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('isactive', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration a", $field, $value);
		}

		$url = base_url() . "admin/Report/getRegSuccess/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'regid,regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,usrpassword,isactive,registrationtype';
		//$this->db->join('member_registration a','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 1 ');
			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0');
			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('isactive', 'DESC');
			}

			$res = $this->UserModel->getRecords("member_registration a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('isdeleted',0);
			$this->db->where(' isactive = "1" AND isdeleted=0');
			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('isactive', 'DESC');
			}
			$res = $this->UserModel->getRecords("member_registration a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}

		//echo $this->db->last_query();
		//$data['query'] = $this->db->last_query();
		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;

			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			foreach ($result as $row) {
				$status = 0;
				/*$payment = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$row['regnumber'],'ref_id'=>$row['regid']),'status,date,id,transaction_no',array('id','ASC'),0,1);
				if(count($payment))
				{
					$status = $payment[0]['status'];
				}*/
				$status = $row['isactive'];
				if ($status == 1)
					$result[$i]['status'] = 'Completed';
				/*else if($status==2)
					$result[$i]['status'] = 'Pending';*/
				else
					$result[$i]['status'] = 'Incomplete';

				$decpass = $aes->decrypt(trim($row['usrpassword']));
				$result[$i]['usrpassword'] = $decpass;

				$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '" target="_blank">' . $row['regnumber'] . '</a>';
				$confirm = "return confirm('Are you sure to delete this record?');";
				$result[$i]['regnumber'] = $regnumber;

				if ($status == 1) {
					$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="' . base_url() . 'admin/Report/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
				} else {
					$action = '';
					$send_mail = '';
				}

				// Payment Data
				$result[$i]['transaction_no'] = '';
				$result[$i]['amount'] = '';
				if ($row['registrationtype'] == 'NM' || $row['registrationtype'] == 'DB') {
					$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 2, 'status' => 1, 'member_regnumber' => $row['regnumber']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no', array('id', 'ASC'));
					//'pg_flag'=>'IIBF_EXAM_DB',
				} else {
					$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 1, 'ref_id' => $row['regid'], 'member_regnumber' => $row['regnumber'], 'status' => 1), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no', array('id', 'ASC'));
					//'pg_flag'=>'iibfregn',
				}
				if (count($payment_res) > 0) {
					$result[$i]['transaction_no'] = $payment_res[0]['transaction_no'];
					$result[$i]['amount'] = $payment_res[0]['amount'];
				}

				//Customized columns
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				if ($row['createdon'] != '' && $row['createdon'] != '0000-00-00 00:00:00') {
					$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon'])) . "\n";
				} else {
					$result[$i]['createdon'] = "0000-00-00" . "\n";
				}

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	// By VSU : Function to get Registration Failure  report (Failure Billdesk of SIFFY)
	public function reg_failure($flag = '')
	{
		ini_set("memory_limit", "-1");

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		if (isset($_POST['download'])) {
			$data['result'] = array();
			$field = '';
			$value = '';
			$sortkey = '';
			$sortval = '';
			$from_date = '';
			$to_date = '';

			/*$session_arr = check_session();
			if($session_arr)
			{
				$field = $session_arr['field'];
				$value = $session_arr['value'];
			}*/

			if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
				$from_date = $_POST['from_date'];
			}

			if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
				$to_date = $_POST['to_date'];
			}

			$select = 'regid,regnumber,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,registrationtype';
			if ($from_date != '' && $to_date != '') {
				$this->db->where('DATE(createdon) BETWEEN "' . $from_date . '" AND "' . $to_date . '" AND isactive = "0" AND isdeleted=0');
				//isactive = "1" AND
			} else if ($from_date != '' & $to_date == '') {
				$this->db->where('DATE(createdon) = "' . $from_date . '" AND isactive = "0" AND isdeleted=0');
				//isactive = "1" AND
			} else {
				$this->db->where('isactive = "0" AND isdeleted=0 ');
			}
			$res = $this->master_model->getRecords("member_registration", '', $select, array('createdon' => 'DESC'));
			//echo $this->db->last_query();

			if (count($res)) { //echo "111";
				$data = "";
				foreach ($res as $row) {
					// Payment Data
					if ($row['registrationtype'] == 'NM' || $row['registrationtype'] == 'DB') {
						$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 2, 'status' => 0, 'member_regnumber' => $row['regnumber']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no,transaction_details', array('id', 'ASC'));
						//'pg_flag'=>'IIBF_EXAM_DB',
					} else {
						$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 1, 'ref_id' => $row['regid'], 'member_regnumber' => $row['regnumber'], 'status' => 0), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no,transaction_details', array('id', 'ASC'));
						//'pg_flag'=>'iibfregn',
					}

					if (count($payment_res) > 0) {
						if ($payment_res[0]['transaction_no']) {
							$trans_no = $payment_res[0]['transaction_no'];
						} else {
							$trans_no = "NULL";
						}
						$data .= $trans_no . ",";
						$data .= $payment_res[0]['receipt_no'] . ",";
						$data .= $payment_res[0]['amount'] . ",";
						if ($payment_res[0]['date'] != '' && $payment_res[0]['date'] != '0000-00-00') {
							$data .= date('Ymd', strtotime($payment_res[0]['date'])) . "\n";
						} else {
							$data .= "0000-00-00";
						}
						if ($flag != '' && $flag == 'reason') {
							$data .= "," . $payment_res[0]['transaction_details'];
						}
						$data .= "\n";
					}
				}

				logadminactivity($log_title = "Registration Failure Report Downloaded", $log_message = "");

				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_Failure_' . date('YmdHis') . '.txt.gz"');
				echo gzencode($data, 9);
				exit();
			}
			/*header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename="default-filename.txt"');	*/
		}

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Failure Registrations</li>
							   </ol>';
		if ($flag == 'reason')
			$this->load->view('admin/reg_failure_reason_list', $data);
		else
			$this->load->view('admin/reg_failure_list', $data);
	}

	// By VSU : Function to get Registration Failure  report (Failure Billdesk of SIFFY)
	public function getRegFailure($flag = '')
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		//$this->db->join('member_registration a','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "0" AND isdeleted=0');
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('isactive', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration", '', '', 'regnumber');
		} else {
			//$this->db->where('isdeleted',0);
			$this->db->where(' isactive = "0" AND isdeleted=0');
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('isactive', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration", $field, $value);
		}

		$url = base_url() . "admin/Report/getRegFailure/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'regid,regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,usrpassword,isactive,registrationtype';
		//$this->db->join('member_registration a','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "0" AND isdeleted=0');
			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('isactive', 'DESC');
			}

			$res = $this->UserModel->getRecords("member_registration", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('isdeleted',0);
			$this->db->where(' isactive = "0" AND isdeleted=0');
			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('isactive', 'DESC');
			}
			$res = $this->UserModel->getRecords("member_registration", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}
		//echo $this->db->last_query();
		//$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;

			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			foreach ($result as $row) {
				$status = 0;

				$status = $row['isactive'];
				if ($status == 1)
					$result[$i]['status'] = 'Completed';
				/*else if($status==2)
					$result[$i]['status'] = 'Pending';*/
				else
					$result[$i]['status'] = 'Incomplete';

				$decpass = $aes->decrypt(trim($row['usrpassword']));
				$result[$i]['usrpassword'] = $decpass;

				$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '" target="_blank">' . $row['regnumber'] . '</a>';
				$confirm = "return confirm('Are you sure to delete this record?');";
				$result[$i]['regnumber'] = $regnumber;

				if ($status == 1) {
					$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="' . base_url() . 'admin/Report/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
				} else {
					$action = '';
					$send_mail = '';
				}

				//Customized columns
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));


				$result[$i]['transaction_no'] = '';
				$result[$i]['amount'] = '';
				if ($row['registrationtype'] == 'NM' || $row['registrationtype'] == 'DB') {
					$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 2, 'status' => 0, 'member_regnumber' => $row['regnumber']), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no,transaction_details', array('id', 'ASC'));
					//'pg_flag'=>'IIBF_EXAM_DB',
				} else {
					$payment_res = $this->Master_model->getRecords('payment_transaction', array('pay_type' => 1, 'ref_id' => $row['regid'], 'member_regnumber' => $row['regnumber'], 'status' => 0), 'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,receipt_no,transaction_details', array('id', 'ASC'));
					//'pg_flag'=>'iibfregn',
				}
				//echo $this->db->last_query()."<br>";
				if (count($payment_res) > 0) {
					$result[$i]['transaction_no'] = $payment_res[0]['transaction_no'];
					$result[$i]['amount'] = $payment_res[0]['amount'];
					if ($payment_res[0]['date'] != '' && $payment_res[0]['date'] != '0000-00-00') {
						$result[$i]['date'] = date('d-m-Y', strtotime($payment_res[0]['date']));
					} else {
						$result[$i]['date'] = '0000-00-00';
					}

					if ($flag != '' && $flag == 'reason') {
						$result[$i]['transaction_details'] = $payment_res[0]['transaction_details'];
					}
				}
				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}


	public function getList1()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		//echo $sortkey."***".$sortval;
		$select = 'DISTINCT(regid)';
		$this->db->join('payment_transaction b', 'b.ref_id=a.regid AND b.member_regnumber=a.regnumber', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0');
			// Not to apply another WHERE condition
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('status', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration a", '', '', $select);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			if ($sortkey == '' && $sortval == '')
				$this->db->order_by('status', 'DESC');
			$total_row = $this->UserModel->getRecordCount("member_registration a", $field, $value, $select);
		}

		$url = base_url() . "admin/Report/getList/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'DISTINCT(regid),regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,b.status,usrpassword';
		$this->db->join('payment_transaction b', 'b.ref_id=a.regid AND b.member_regnumber=a.regnumber', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(createdon) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0');
			// Not to apply another WHERE condition

			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('status', 'DESC');
			}

			$res = $this->UserModel->getRecords("member_registration a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);

			if ($sortkey != '' && $sortval != '') {
			} else {
				$this->db->order_by('status', 'DESC');
			}
			$res = $this->UserModel->getRecords("member_registration a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}

		////$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;

			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			foreach ($result as $row) {
				if ($row['status'] == 1)
					$result[$i]['status'] = 'Completed';
				else if ($row['status'] == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';

				$decpass = $aes->decrypt(trim($row['usrpassword']));
				$result[$i]['usrpassword'] = $decpass;

				if ($row['status'] == 1) {
					$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '" target="_blank">' . $row['regnumber'] . '</a>';
					$confirm = "return confirm('Are you sure to delete this record?');";
					$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="' . base_url() . 'admin/Report/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
				} else {
					$regnumber = '';
					$action = '';
					$send_mail = '';
				}
				//Customized columns
				$result[$i]['regnumber'] = $regnumber;
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function examReg()
	{
		if ($this->session->userdata('roleid') != 1) {
			redirect(base_url() . 'admin/MainController');
		}

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Member List</li>
							   </ol>';

		$this->load->view('admin/exam_details', $data);
	}

	public function getExamReport()
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		//echo $sortkey."***".$sortval;

		switch ($field) {
			case '01':
				$field = 'c.exam_code';
				break;
			case '02':
				$field = 'trim(d.description)';
				break;
			case '03':
				$field = 'c.exam_center_code';
				break;
			case '04':
				$field = 'a.regnumber';
				break;
			case '05':
				$field = 'transaction_no';
				break;
			case '06':
				$field = 'all';
				break;
		}

		if ($field == "all") {
			$field = 'c.exam_code, d.description, c.exam_center_code, a.regnumber, transaction_no,';
		}

		$this->db->where('isactive', '1');
		$this->db->where('isdeleted', 0);
		$this->db->where('pay_type', 2);

		if ($sortkey == '' && $sortval == '')
			$this->db->order_by('status', 'DESC');

		//$select = 'DISTINCT(b.transaction_no)';
		$select = 'b.transaction_no';
		//e.center_name
		//$this->db->select($select);
		$this->db->join('member_registration a', 'a.regnumber=c.regnumber', 'RIGHT');
		$this->db->join('exam_master d', 'd.exam_code=c.exam_code', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');

		$this->db->group_by('b.transaction_no');
		$res1 = $this->UserModel->getRecords("member_exam c", $select, $field, $value);
		$total_row = count($res1->result_array());
		//$query = $this->db->get('member_exam c');
		//$total_row = $query->num_rows();
		////$data['query'] = $this->db->last_query();

		$this->db->where('isactive', '1');
		$this->db->where('isdeleted', 0);
		$this->db->where('pay_type', 2);
		if ($sortkey == '' && $sortval == '')
			$this->db->order_by('status', 'DESC');

		$select = 'b.transaction_no,a.regid,a.regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,b.status,b.date,c.exam_medium,c.exam_fee,gender,d.description,c.exam_center_code,b.transaction_details';

		$this->db->join('member_registration a', 'a.regnumber=c.regnumber', 'RIGHT');
		$this->db->join('exam_master d', 'd.exam_code=c.exam_code', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		//$this->db->join('member_registration a','a.regnumber=b.member_regnumber','RIGHT');
		$this->db->group_by('b.transaction_no');
		$res = $this->UserModel->getRecords("member_exam c", $select, $field, $value, $sortkey, $sortval, $per_page, $start);

		//echo $total_row;
		$url = base_url() . "admin/Report/getExamReport/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);
		//echo $this->db->last_query();
		//$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				if ($row['status'] == 1)
					$result[$i]['status'] = 'Completed';
				else if ($row['status'] == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';

				if ($row['status'] == 1) {
					$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '">' . $row['regnumber'] . '</a>';
					$confirm = "return confirm('Are you sure to delete this record?');";
					$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="' . base_url() . 'admin/Report/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
				} else {
					$regnumber = '';
					$action = '';
					$send_mail = '';
				}

				$center = $this->master_model->getRecords("center_master", array('center_code' => $row['exam_center_code']), 'center_name,center_code');

				if (count($center)) {
					$result[$i]['center_name'] = $center[0]['center_name'] . "<br>(" . $center[0]['center_code'] . ")";
				} else {
					$result[$i]['center_name'] = '';
				}

				$medium = $this->master_model->getRecords("medium_master", array('medium_code' => $row['exam_medium']), 'medium_description');

				if (count($medium)) {
					$result[$i]['medium_description'] = $medium[0]['medium_description'];
				} else {
					$result[$i]['medium_description'] = '';
				}

				//Customized columns
				//$result[$i]['regnumber'] = $regnumber;
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function getExamDetailsToPrint()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$total_row = 0;

		$session_arr = check_session();
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
		}
		//echo $sortkey."***".$sortval;

		switch ($field) {
			case '01':
				$field = 'c.exam_code';
				break;
			case '02':
				$field = 'trim(d.description)';
				break;
			case '03':
				$field = 'c.exam_center_code';
				break;
			case '04':
				$field = 'a.regnumber';
				break;
			case '05':
				$field = 'transaction_no';
				break;
			case '06':
				$field = 'all';
				break;
		}
		if ($field == "all") {
			$field = 'c.exam_code, d.description, c.exam_center_code, a.regnumber, transaction_no,';
		}

		$this->db->where('isactive', '1');
		$this->db->where('isdeleted', 0);
		$this->db->where('pay_type', 2);
		$this->db->order_by('status', 'DESC');

		$select = 'b.transaction_no,a.regid,a.regnumber,namesub,firstname,lastname, dateofbirth,createdon,b.status,b.date,c.exam_medium,c.exam_fee,gender,d.description,c.exam_center_code,b.transaction_details';
		//DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth
		$this->db->join('member_registration a', 'a.regnumber=c.regnumber', 'RIGHT');
		$this->db->join('exam_master d', 'd.exam_code=c.exam_code', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		//$this->db->join('member_registration a','a.regnumber=b.member_regnumber','RIGHT');
		$this->db->group_by('b.transaction_no');
		if ($field == 'regnumber') $field = 'a.regnumber';
		$res = $this->UserModel->getRecords("member_exam c", $select, $field, $value);

		////$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				if ($row['status'] == 1)
					$result[$i]['status'] = 'Completed';
				else if ($row['status'] == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';

				if ($row['status'] == 1) {
					$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '">' . $row['regnumber'] . '</a>';
					$confirm = "return confirm('Are you sure to delete this record?');";
					$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="' . base_url() . 'admin/Report/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
				} else {
					$regnumber = '';
					$action = '';
					$send_mail = '';
				}

				$center = $this->master_model->getRecords("center_master", array('center_code' => $row['exam_center_code']), 'center_name');

				if (count($center)) {
					$result[$i]['center_name'] = $center[0]['center_name'];
				} else {
					$result[$i]['center_name'] = '';
				}

				$medium = $this->master_model->getRecords("medium_master", array('medium_code' => $row['exam_medium']), 'medium_description');

				if (count($medium)) {
					$result[$i]['medium_description'] = $medium[0]['medium_description'];
				} else {
					$result[$i]['medium_description'] = '';
				}

				//Customized columns
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function reg_edit()
	{
		if ($this->session->userdata('roleid') == 3) {
			redirect(base_url() . 'admin/MainController');
		}
		$kycflag = 0;
		$data = array();
		$data['centerRes'] = array();
		$prevData = array();

		$last = $this->uri->total_segments();
		$regid = base64_decode($this->uri->segment($last));
		$memtype = '';
		if ($regid) {
			$regnum = '';
			$regData = $this->master_model->getRecords('member_registration', array('regid' => $regid));
			if (count($regData)) {
				$data['regData'] = $regData[0];
				$prevData = $regData[0];
				$regnum = $prevData['regnumber'];
				$memtype = $regData[0]['registrationtype'];
				$excode = $regData[0]['excode'];
			} else {
				redirect(base_url() . 'admin/Report/datewise');
			}
		}

		if (isset($_POST['btnSubmit'])) {
			$this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required');
			$this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required');
			$this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required');
			$this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required');
			$this->form_validation->set_rules('state', 'State', 'trim');
			$this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required');
			$this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required');
			$this->form_validation->set_rules('gender', 'Gender', 'trim');
			$this->form_validation->set_rules('optedu', 'Qualification', 'trim');
			$this->form_validation->set_rules('bank_emp_id', 'Bank Employee Id', 'trim');

			$optedu = '';
			if (isset($_POST['optedu'])) {
				$optedu = $_POST['optedu'];
				if ($_POST['optedu'] == 'U') {
					$this->form_validation->set_rules('eduqual1', 'Please specify', 'trim');
				} else if ($_POST['optedu'] == 'G') {
					$this->form_validation->set_rules('eduqual2', 'Please specify', 'trim');
				} else if ($_POST['optedu'] == 'P') {
					$this->form_validation->set_rules('eduqual3', 'Please specify', 'trim');
				}
			}

			//echo $excode; 
			//$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[member_registration.email.regid.'.$regid.']');
			if ($excode == 1015) {
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
			} else {
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_validuniqueO[member_registration.email.regid.' . $regid . '.isactive.1.registrationtype.' . $memtype . ']|xss_clean');
			}
			//die;
			/* $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_validuniqueO[member_registration.email.regid.'.$regid.'.isactive.1.registrationtype.'.$memtype.']|xss_clean'); */
			$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
			$this->form_validation->set_rules('idproof', 'Id Proof', 'trim|required');
			//	$this->form_validation->set_rules('aadhar_card','Aadhar Card No','trim|required|max_length[12]|min_length[12]|is_unique[member_registration.aadhar_card]');
			//$this->form_validation->set_rules('aadhar_card','Aadhar Card No','trim|required|max_length[12]|min_length[12]');
			//$this->form_validation->set_rules('idNo','ID No','trim|max_length[25]');

			//if($this->input->post('aadhar_card')!='')
			//{

			//$this->form_validation->set_rules('aadhar_card','Aadhar Card No','trim|required|max_length[12]|min_length[12]');  
			//$this->form_validation->set_rules('aadhar_card','Aadhar Card No','trim|required|max_length[12]|min_length[12]|is_unique[member_registration.aadhar_card]');
			//}

			if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
				if ($_POST["aadhar_card"] != '') {
					$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $regid . '.registrationtype.' . $memtype . ']');
				}
			} else {
				if ($_POST["aadhar_card"] != '') {
					//$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
					$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $regid . '.registrationtype.' . $memtype . ']');
				}
			}
			if ($regData[0]['registrationtype'] != 'NM' && $regData[0]['registrationtype'] != 'DB') {
				$this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|max_length[35]');
				$this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim');
				$this->form_validation->set_rules('office', 'Branch/Office', 'trim');
				$this->form_validation->set_rules('designation', 'Designation', 'trim');
				$this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim');
			} else {
				$this->form_validation->set_rules('idNo', 'ID No', 'trim|max_length[25]|required');
			}


			if ($this->form_validation->run() == TRUE) {
				$sel_namesub = strtoupper($_POST["sel_namesub"]);
				$firstname = strtoupper($_POST["firstname"]);
				$middlename = strtoupper($_POST["middlename"]);
				$lastname = strtoupper($_POST["lastname"]);

				$addressline1 = strtoupper($_POST["addressline1"]);
				$addressline2 = strtoupper($_POST["addressline2"]);
				$addressline3 = strtoupper($_POST["addressline3"]);
				$addressline4 = strtoupper($_POST["addressline4"]);
				$district = strtoupper($_POST["district"]);
				$city = strtoupper($_POST["city"]);
				$state = $_POST["state"];
				$pincode = $_POST["pincode"];
				$dob1 = $_POST["dob1"];
				$dob = str_replace('/', '-', $dob1);
				$gender = '';
				if (isset($_POST["gender"]) && $_POST["gender"] != '') {
					$gender = $_POST["gender"];
				}
				$bank_emp_id = $this->input->post("bank_emp_id");

				//$optedu= $optedu;
				$specify_qualification = '';
				if ($optedu == 'U') {
					$specify_qualification = $_POST["eduqual1"];
				} elseif ($optedu == 'G') {
					$specify_qualification = $_POST["eduqual2"];
				} else if ($optedu == 'P') {
					$specify_qualification = $_POST["eduqual3"];
				}

				$email = $_POST["email"];
				$stdcode = $_POST["stdcode"];
				$phone = $_POST["phone"];
				$mobile = $_POST["mobile"];
				$idproof = '';
				if (isset($_POST["idproof"])) {
					$idproof = $_POST["idproof"];
				}
				//$idNo = $_POST["idNo"];
				$aadhar_card = $_POST["aadhar_card"];
				$optnletter = $_POST["optnletter"];

				if ($regData[0]['registrationtype'] != 'NM' && $regData[0]['registrationtype'] != 'DB') {
					$nameoncard = strtoupper($_POST["nameoncard"]);
					$institutionworking = $_POST["institutionworking"];
					$office = strtoupper($_POST["office"]);
					$designation = $_POST["designation"];
					$doj = $_POST["doj1"];
				} else {
					$idNo = $_POST["idNo"];
				}

				// Check if value is edited
				$update_data = array();
				if (count($prevData)) {
					if ($prevData['namesub'] != $sel_namesub) {
						$update_data['namesub'] = $sel_namesub;
						$update_data['kyc_edit'] = '1';
						$update_data['kyc_status'] = '0';
						$kycflag = 1;
						$kyc_update_data['edited_mem_name'] = 1;
					}

					if ($prevData['firstname'] != $firstname) {
						$update_data['firstname'] = $firstname;
						$update_data['kyc_edit'] = '1';
						$update_data['kyc_status'] = '0';
						$kycflag = 1;
						$kyc_update_data['edited_mem_name'] = 1;
					}

					if ($prevData['middlename'] != $middlename) {
						$update_data['middlename'] = $middlename;
						$update_data['kyc_edit'] = '1';
						$update_data['kyc_status'] = '0';
						$kycflag = 1;
						$kyc_update_data['edited_mem_name'] = 1;
					}

					if ($prevData['lastname'] != $lastname) {
						$update_data['lastname'] = $lastname;
						$update_data['kyc_edit'] = '1';
						$update_data['kyc_status'] = '0';
						$kycflag = 1;
						$kyc_update_data['edited_mem_name'] = 1;
					}


					if ($prevData['address1'] != $addressline1) {
						$update_data['address1'] = $addressline1;
					}

					if ($prevData['address2'] != $addressline2) {
						$update_data['address2'] = $addressline2;
					}

					if ($prevData['address3'] != $addressline3) {
						$update_data['address3'] = $addressline3;
					}

					if ($prevData['address4'] != $addressline4) {
						$update_data['address4'] = $addressline4;
					}

					if ($prevData['district'] != $district) {
						$update_data['district'] = $district;
					}

					if ($prevData['city'] != $city) {
						$update_data['city'] = $city;
					}

					if ($prevData['state'] != $state) {
						$update_data['state'] = $state;
					}

					if ($prevData['pincode'] != $pincode) {
						$update_data['pincode'] = $pincode;
					}

					if (date('Y-m-d', strtotime($prevData['dateofbirth'])) != date('Y-m-d', strtotime($dob))) {
						$update_data['dateofbirth'] = date('Y-m-d', strtotime($dob));
						$update_data['kyc_edit'] = '1';
						$update_data['kyc_status'] = '0';
						$kycflag = 1;
						$kyc_update_data['edited_mem_dob'] = 1;
					}

					if ($gender) {
						if ($prevData['gender'] != $gender) {
							$update_data['gender'] = $gender;
						}
					}

					if ($prevData['qualification'] != $optedu) {
						$update_data['qualification'] = $optedu;
					}

					if ($prevData['specify_qualification'] != $specify_qualification) {
						$update_data['specify_qualification'] = $specify_qualification;
					}

					if ($regData[0]['registrationtype'] != 'NM' && $regData[0]['registrationtype'] != 'DB') {
						if ($prevData['displayname'] != $nameoncard) {
							$update_data['displayname'] = $nameoncard;
						}

						if ($prevData['associatedinstitute'] != $institutionworking) {
							$update_data['associatedinstitute'] = $institutionworking;
							$update_data['kyc_edit'] = '1';
							$update_data['kyc_status'] = '0';
							$kycflag = 1;
							$kyc_update_data['edited_mem_associate_inst'] = 1;
						}

						if ($prevData['office'] != $office) {
							$update_data['office'] = $office;
						}

						if ($prevData['designation'] != $designation) {
							$update_data['designation'] = $designation;
						}

						if (date('Y-m-d', strtotime($prevData['dateofjoin'])) != date('Y-m-d', strtotime($doj))) {
							$update_data['dateofjoin'] = date('Y-m-d', strtotime($doj));
						}
					} else {
						if ($prevData['idNo'] != $idNo) {
							$update_data['idNo'] = $idNo;
						}
					}


					if ($prevData['email'] != $email) {
						$update_data['email'] = $email;
					}

					if ($prevData['stdcode'] != $stdcode) {
						$update_data['stdcode'] = $stdcode;
					}

					if ($prevData['office_phone'] != $phone) {
						$update_data['office_phone'] = $phone;
					}

					if ($prevData['mobile'] != $mobile) {
						$update_data['mobile'] = $mobile;
					}

					if ($prevData['idproof'] != $idproof) {
						$update_data['idproof'] = $idproof;
					}

					/*if($prevData['idNo'] != $idNo)
					{	$update_data['idNo'] = $idNo;	}*/

					if ($prevData['aadhar_card'] != $aadhar_card) {
						$update_data['aadhar_card'] = $aadhar_card;
					}

					if ($prevData['optnletter'] != $optnletter) {
						$update_data['optnletter'] = $optnletter;
					}

					//changes by tejasvi
					if ($prevData['bank_emp_id'] != $bank_emp_id) {
						$update_data['bank_emp_id'] = $bank_emp_id;
					}
				}

				$edited = array();
				$edited = '';
				if (count($update_data)) {
					foreach ($update_data as $key => $val) {
						$edited .= strtoupper($key) . " = " . strtoupper($val) . " && ";
					}
					$update_data['editedon'] = date('Y-m-d H:i:s');
					$update_data['editedby'] = $this->session->userdata('username');
					$update_data['editedbyadmin'] = $this->UserID;

					if ($this->master_model->updateRecord('member_registration', $update_data, array('regid' => $regid))) {
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $regData[0];
						//profile update logs
						log_profile_admin($log_title = "Profile updated successfully", $edited, 'data', $regid, $regnum);

						logadminactivity($log_title = "Profile updated id:" . $regid, $description = serialize($desc));


						if ($kycflag == 1) {
							$kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
							$kyc_update_data['kyc_state'] = '2';
							$kyc_update_data['kyc_status'] = '0';

							$this->db->like('allotted_member_id', $regnum);
							$this->db->or_like('original_allotted_member_id', $regnum);
							$this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
							$check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
							// $check_duplicate_entry=$this->master_model->getRecords('admin_kyc_users',array('list_type'=>'New'));
							if (count($check_duplicate_entry) > 0) {
								foreach ($check_duplicate_entry as $row) {
									$allotted_member_id = $this->removeFromString($row['allotted_member_id'], $regnum);
									$original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $regnum);
									$admin_update_data = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);
									$this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));
								}
							}
							$kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $regnum), '', array('kyc_id' => 'DESC'), '0', '1');
							if (count($kycmemdetails) > 0) {
								if ($kycmemdetails[0]['kyc_status'] == '0') {

									$this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));
									//$this->KYC_Log_model->create_log('kyc member edited images', '','',$regnum, serialize($desc));
									$this->KYC_Log_model->create_log('kyc member profile edit', '', '', $regnum, serialize($desc));
								}
							}

							//echo $this->db->last_query();exit;
							//change by pooja godse for  membership id card  dowanload count reset
							//check membership count
							$check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $regnum));
							if (count($check_membership_cnt) > 0) {
								//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$regnum);
								/* update dowanload count 8-8-2017 */
								$this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $regnum));
								/* Close update dowanload count */
								/* User Log Activities : Pooja */
								$uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $regnum), 'regid');
								$user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $regnum));
								$log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
								$log_message = serialize($user_info);
								$rId = $uerlog[0]['regid'];
								$regNo = $this->session->userdata('regnumber');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								/* Close User Log Actitives */
							}
							logadminactivity($log_title = "KYC Profile updated id:" . $regid, $description = serialize($desc));
						}
						// Send mail start
						// Send mail end
						$this->session->set_flashdata('success', 'Profile updated successfully');
						redirect(base_url() . 'admin/Report/datewise');
					} else {
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $regData[0];

						//profile update logs
						log_profile_admin("Error updating profile", $edited, 'data', $regid, $regnum);
						//admin logs
						logadminactivity($log_title = "Error updating profile id:" . $regid, $log_message = serialize($desc));

						$this->session->set_flashdata('error', 'Error occured while updating record');
						redirect(base_url() . 'admin/Report/reg_edit/' . base64_encode($regid));
					}
				} else {
					$this->session->set_flashdata('error', 'Change atleast one field');
					redirect(base_url() . 'admin/Report/reg_edit/' . base64_encode($regid));
				}
			} else {
				$data['validation_errors'] = validation_errors();
			}
		}


		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="' . base_url() . 'admin/' . $this->router->fetch_class() . '">Registrations</a></li>
					<li class="active">Edit</li>
				</ol>';
		$data['states'] = $this->master_model->getRecords('state_master', '', '', array('state_name' => 'ASC'));
		$data['idtype_master'] = $this->master_model->getRecords('idtype_master');

		$data['undergraduate'] = $this->master_model->getRecords('qualification', array('type' => 'UG'), '', array('name' => 'ASC'));
		$data['graduate'] = $this->master_model->getRecords('qualification', array('type' => 'GR'), '', array('name' => 'ASC'));
		$data['postgraduate'] = $this->master_model->getRecords('qualification', array('type' => 'PG'), '', array('name' => 'ASC'));
		// 1444
		$instiarray = array(764, 793, 939, 946, 1238, 1449, 1456, 1458, 1459, 1460, 1464, 1465, 1469, 1470, 1471, 1472, 1473, 1474, 1476, 1487, 1490, 1491, 1497, 1499, 1506, 1511, 1522, 1525, 1526, 1527, 1528, 1530, 1531, 1533, 1538, 1539, 1540, 1541, 1549, 1559, 1567, 1570, 1571, 1573, 1574, 1575, 1576, 1581, 1584, 1587, 1589, 1591, 1592, 1593, 1594, 1598, 1602, 1603, 1607, 1608, 1609, 1612, 1615, 1616, 1617, 1620, 1625, 1626, 1627, 1628, 1629, 1630, 1635, 1643, 1644, 1646, 1648, 1652, 1654, 1655, 1656, 1657, 1658, 1660, 1661, 1663, 1669, 1678, 1679, 1680, 1687, 1688, 1690, 1691, 1692, 1699, 1707, 1708, 1709, 1714, 1720, 1721, 1723, 1724, 1725, 1727, 1730, 1731, 1740, 1742, 1743, 1755, 1758, 1760, 1767, 1769, 1773, 1774, 1775, 1776, 1780, 1781, 1782, 1783, 1785, 1786, 1788, 1790, 1795, 1802, 1803, 1804, 1806, 1813, 1814, 1815, 1817, 1818, 1820, 1824, 1825, 1828, 1844, 1845, 1846, 1848, 1850, 1851, 1852, 1853, 1855, 1861, 1862, 1863, 1864, 1868, 1869, 1870, 1876, 1884, 1885, 1886, 1890, 1894, 1896, 1897, 1898, 1905, 1908, 1909, 1910, 1911, 1912, 1913, 1914, 1915, 1921, 1925, 1926, 1930, 1931, 1936, 1947, 1951, 1952, 1961, 1971, 1976, 1982, 1986, 1988, 1991, 1992, 1994, 1995, 1996, 1997, 2000, 2002, 2012, 2025, 2028, 2029, 2034, 2041, 2043, 2044, 2046, 2050, 2052, 2053, 2054, 2056, 2058, 2059, 2060, 2061, 2062, 2063, 2064, 2065, 2066, 2067, 2068, 2069, 2070, 2071, 2072, 2073, 2076, 2077, 2078, 2079, 2080, 2081, 2082, 2083, 2084, 2086, 2087, 2090, 2093, 2096, 2097, 2098, 2100, 2101, 2102, 2105, 2106, 2107, 2108, 2109, 2110, 2111, 2112, 2113, 2115, 2116, 2117, 2118, 2119, 2120, 2122, 2123, 2125, 2126, 2129, 2130, 2131, 2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140, 2141, 2145, 2146, 2147, 2152, 2153, 2154, 2155, 2156, 2180, 2183, 2184, 2185, 2186, 2187, 2683, 2698);
		$this->db->where_not_in('institude_id', $instiarray);
		$data['institution_master'] = $this->master_model->getRecords('institution_master', '', '', array('name' => 'ASC'));
		$data['designation'] = $this->master_model->getRecords('designation_master', '', '', array('dname' => 'ASC'));

		if ($regData[0]['registrationtype'] == 'NM' || $regData[0]['registrationtype'] == 'DB') {
			$this->load->view('admin/reg_edit_nm', $data);
		} else {
			$this->load->view('admin/reg_edit', $data);
		}
	}

	// ##---------Edit Images(Vrushali)-----------##
	public function editimages()
	{
		$kyc_update_data = array();
		$kyc_edit_flag = 0;
		if ($this->session->userdata('roleid') == 3) {
			redirect(base_url() . 'admin/MainController');
		}

		$last = $this->uri->total_segments();
		$reg_id = base64_decode($this->uri->segment($last - 1));
		$reg_no = base64_decode($this->uri->segment($last));
		$applicationNo = $reg_no;
		if ($reg_id != '' && $reg_no != '') {
			if (is_numeric($reg_id)) {
				$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = '';
				$member_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'scannedphoto,scannedsignaturephoto,	idproofphoto, declaration, regnumber,registrationtype');

				if (isset($_POST['btnSubmit'])) {
					if ($_FILES['scannedphoto']['name'] == '' && $_FILES['scannedsignaturephoto']['name'] == '' && $_FILES['idproofphoto']['name'] == '' && $_FILES['declaration']['name'] == '') {
						//$this->form_validation->set_rules('scannedphoto','Please Change atleast One Value','file_required');
						$this->session->set_flashdata('error', 'Please Change atleast One Value');
						redirect(base_url() . 'admin/Report/editimages/' . base64_encode($reg_id) . '/' . base64_encode($reg_no));
					}
					if ($_FILES['scannedphoto']['name'] != '') {
						$this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
					}
					if ($_FILES['scannedsignaturephoto']['name'] != '') {
						$this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
					}
					if ($_FILES['idproofphoto']['name'] != '') {
						$this->form_validation->set_rules('idproofphoto', 'id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[25]');
					}
					if ($_FILES['declaration']['name'] != '') {
						$this->form_validation->set_rules('declaration', 'declaration', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
					}

					if ($this->form_validation->run() == TRUE) {
						$prev_edited_on = '';
						$prev_photo_flg = "N";
						$prev_signature_flg = "N";
						$prev_id_flg = "N";
						$prev_declaration_flg = "N";
						$prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg,declaration_flg');
						if (count($prev_edited_on_qry)) {
							$prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
							$prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
							$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
							$prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
							$prev_declaration_flg = $prev_edited_on_qry[0]['declaration_flg'];

							if ($prev_edited_on != date('Y-m-d')) {
								$this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N', 'declaration_flg' => 'N'), array('regid' => $reg_id));
							}
						}


						$scannedphoto_file = '';
						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$photo_flg = 'N';
						} else {
							$photo_flg = $prev_photo_flg;
						}
						$edited = '';
						if (isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name'] != '') {

							$path = "./uploads/photograph";
							$date = date_create();
							//$timestamp = date_timestamp_get($date);
							//$new_filename = 'photo_'.rand(1,99999);

							//@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);

							$new_filename = 'p_' . $applicationNo;
							$uploadData = upload_file('scannedphoto', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_photo'] = 1;
								// No need to unlink as it overwrites file 
								//@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);
								$scannedphoto_file = $uploadData['file_name'];
								$photo_flg = 'Y';
								$edited .= 'PHOTO || ';
							} else {
								$scannedphoto_file = $this->input->post('scannedphoto1_hidd');
							}
						} else {
							$scannedphoto_file = $this->input->post('scannedphoto1_hidd');
						}

						// Upload DOB Proof
						$scannedsignaturephoto_file = '';
						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$signature_flg = 'N';
						} else {
							$signature_flg = $prev_signature_flg;
						}

						if ($_FILES['scannedsignaturephoto']['name'] != '') {

							$path = "./uploads/scansignature";
							$date = date_create();
							//$timestamp = date_timestamp_get($date);
							//$new_filename = 'sign_'.rand(1,99999);
							$new_filename = 's_' . $applicationNo;
							$uploadData = upload_file('scannedsignaturephoto', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_sign'] = 1;
								$scannedsignaturephoto_file = $uploadData['file_name'];
								$signature_flg = 'Y';
								$edited .= 'SIGNATURE || ';
							} else {
								$scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
							}
						} else {
							$scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
						}

						// Upload Education Certificate
						$idproofphoto_file = '';

						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$id_flg = 'N';
						} else {
							$id_flg = $prev_id_flg;
						}

						if ($_FILES['idproofphoto']['name'] != '') {
							$path = "./uploads/idproof";
							$date = date_create();
							//$timestamp = date_timestamp_get($date);
							//$new_filename = 'idproof_'.rand(1,99999);
							$new_filename = 'pr_' . $applicationNo;
							$uploadData = upload_file('idproofphoto', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_proof'] = 1;
								$idproofphoto_file = $uploadData['file_name'];
								$id_flg = 'Y';
								$edited .= 'PROOF || ';
							} else {
								$idproofphoto_file = $this->input->post('idproofphoto1_hidd');
							}
						} else {
							$idproofphoto_file = $this->input->post('idproofphoto1_hidd');
						}



						// Upload Declaration Certificate, this code added by pratibha borse on 22 april 22
						$declaration_file = '';

						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$declaration_flg = 'N';
						} else {
							$declaration_flg = $prev_declaration_flg;
						}

						if ($_FILES['declaration']['name'] != '') {
							$path = "./uploads/declaration";
							$date = date_create();
							$new_filename = 'pr_' . $applicationNo;
							$uploadData = upload_file('declaration', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_declaration'] = 1;
								$declaration_file = $uploadData['file_name'];
								$declaration_flg = 'Y';
								$edited .= 'DECLARATION || ';
							} else {
								$declaration_file = $this->input->post('declaration_hidd');
							}
						} else {
							$declaration_file = $this->input->post('declaration_hidd');
						}

						$update_info = array(
							'scannedphoto' => $scannedphoto_file,
							'scannedsignaturephoto' => $scannedsignaturephoto_file,
							'idproofphoto' => $idproofphoto_file,
							'declaration' => $declaration_file,
							'images_editedon' => date('Y-m-d H:i:s'),
							'images_editedby' => $this->session->userdata('username'),
							'images_editedbyadmin' => $this->UserID,
							'photo_flg' => $photo_flg,
							'signature_flg' => $signature_flg,
							'id_flg' => $id_flg,
							'declaration_flg' => $declaration_flg,
							'kyc_edit' => $kyc_edit_flag,
							'kyc_status' => '0'
						);

						/*$update_info = array(
												'scannedphoto'=>$scannedphoto_file,
												'scannedsignaturephoto'=>$scannedsignaturephoto_file,
												'idproofphoto'=>$idproofphoto_file,
												'images_editedon'=>date('Y-m-d H:i:s'),
												'images_editedby'=>$this->session->userdata('username'),
												'images_editedbyadmin'=>$this->UserID,
												'photo_flg'=>$photo_flg,
												'signature_flg'=>$signature_flg,
												'id_flg'=>$id_flg,
											);*/

						//$personalInfo = filter($personal_info);
						if ($this->master_model->updateRecord('member_registration', $update_info, array('regid' => $reg_id, 'regnumber' => $reg_no))) {
							$finalStr = '';
							if ($edited != '') {
								$edit_data = trim($edited);
								$finalStr = rtrim($edit_data, "||");
							}
							log_profile_admin($log_title = "Profile images updated successfully", $finalStr, 'image', $reg_id, $reg_no);

							if ($kyc_edit_flag == 1) {
								$kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $reg_no), '', array('kyc_id' => 'DESC'), '0', '1');
								if (count($kycmemdetails) > 0) {
									$kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
									$kyc_update_data['kyc_state'] = '2';
									$kyc_update_data['kyc_status'] = '0';

									$this->db->like('allotted_member_id', $reg_no);
									$this->db->or_like('original_allotted_member_id', $reg_no);
									$this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
									$check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
									// $check_duplicate_entry=$this->master_model->getRecords('admin_kyc_users',array('list_type'=>'New'));
									if (count($check_duplicate_entry) > 0) {
										foreach ($check_duplicate_entry as $row) {
											$allotted_member_id = $this->removeFromString($row['allotted_member_id'], $reg_no);
											$original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $reg_no);
											$admin_update_data = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);

											$this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));
										}
									}

									//$kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
									if ($kycmemdetails[0]['kyc_status'] == '0') {
										$this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));
										$this->KYC_Log_model->create_log('kyc member edited images', '', '', $reg_no, serialize($update_info));
									}


									//check membership count
									$check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
									if (count($check_membership_cnt) > 0) {
										//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$reg_no);
										/* update dowanload count 8-8-2017 */
										$this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $reg_no));
										/* Close update dowanload count */
										/* User Log Activities : Pooja */
										$uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $reg_no), 'regid');
										$user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
										$log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
										$log_message = serialize($user_info);
										$rId = $uerlog[0]['regid'];
										$regNo = $this->session->userdata('regnumber');
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										/* Close User Log Actitives */
									}
								}

								//echo $this->db->last_query();exit;
								//change by pooja godse for  memebersgip id card  dowanload count reset
								//check membership count
								$check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
								if (count($check_membership_cnt) > 0) {
									//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$reg_no);
									/* update dowanload count 8-8-2017 */
									$this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $reg_no));
									/* Close update dowanload count */
									/* User Log Activities : Pooja */
									$uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $reg_no), 'regid');
									$user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
									$log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
									$log_message = serialize($user_info);
									$rId = $uerlog[0]['regid'];
									$regNo = $this->session->userdata('regnumber');
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									/* Close User Log Actitives */
								}

								logadminactivity($log_title = "kyc member edited images id : " . $reg_id, $description = serialize($update_info));
							}



							$this->session->set_flashdata('success', 'Profile has been updated successfully !!');
							redirect(base_url() . 'admin/Report/reg_edit/' . base64_encode($reg_id));

							/*logactivity($log_title ="Member Edit Images", $log_message = serialize($update_info));
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
							if(count($emailerstr) > 0)
							{
								$member_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'email');
								$newstring = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
								$final_str= str_replace("#password#", "".base64_decode($this->session->userdata('password'))."",  $newstring);
								$info_arr=array(
															'to'=>$member_info[0]['email'],
															'from'=>$emailerstr[0]['from'],
															'subject'=>$emailerstr[0]['subject'],
															'message'=>$final_str
														);
														
								if($this->Emailsending->mailsend($info_arr))
								{
									//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
									redirect(base_url('home/acknowledge/'));
								}
								else
								{
									$this->session->set_flashdata('error','Error while sending email !!');
									redirect(base_url('home/editimages/'));
								}
							}*/
						} else {
							$this->session->set_flashdata('error', 'Error while updating profile !!');
							redirect(base_url() . 'admin/Report/datewise');
						}
					} else {
						$data['validation_errors'] = validation_errors();
					}
				}
				$data['member_info'] = $member_info;
				$this->load->view('admin/reg_edit_images', $data);
			} else {
				redirect(base_url() . 'admin/Report/datewise');
			}
		} else {
			redirect(base_url() . 'admin/Report/datewise');
		}
	}



	public function preview()
	{
		$data['regData'] = array();
		$data['regnum'] = '';
		$last = $this->uri->total_segments();
		$regid = base64_decode($this->uri->segment($last));
		if ($regid) {
			$regnum = '';
			$select = 'regid,regnumber,usrpassword,namesub,firstname,middlename,lastname,displayname,contactdetails,address1,address2,address3,address4,
				country,district,city,state,pincode,centerid,dateofbirth,gender,qualification,specify_qualification,associatedinstitute,branch,office,
				designation,dateofjoin,staffnumber,email,registrationtype,stdcode,office_phone,mobile,scannedphoto,scannedsignaturephoto,idproof,idNo,aadhar_card,
				idproofphoto,declaration,optnletter,,excode,fee,exam_period,centercode,exmode,registration_status,createdon,qid,c.name q_name,b.id,b.state_code,b.state_name,institude_id,d.name institute_name,dcode,dname,f.id,f.name id_name';
			//paymode
			$this->db->join('state_master b', 'b.state_code=a.state', 'LEFT');
			$this->db->join('qualification c', 'c.qid=a.specify_qualification', 'LEFT');
			$this->db->join('institution_master d', 'd.institude_id=a.associatedinstitute', 'LEFT');
			$this->db->join('designation_master e', 'e.dcode=a.designation', 'LEFT');
			$this->db->join('idtype_master f', 'f.id=a.idproof', 'LEFT');
			$regData = $this->master_model->getRecords('member_registration a', array('regid' => $regid), $select);
			if (count($regData)) {
				if ($regData[0]['registrationtype'] == 'O') {
					$payment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $regData[0]['regnumber'], 'ref_id' => $regData[0]['regid']));
				} else {
					$payment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $regData[0]['regnumber']), '', array('id' => 'DESC'));
				}
				$regData[0]['transaction_no'] = '';
				$regData[0]['date'] = '';
				$regData[0]['amount'] = '';
				if (count($payment)) {
					$regData[0]['transaction_no'] = $payment[0]['transaction_no'];
					$regData[0]['date'] = $payment[0]['date'];
					$regData[0]['amount'] = $payment[0]['amount'];
				}
				$data['regData'] = $regData[0];
				$data['regnum'] = $regData[0]['regnumber'];
			} else {
				redirect(base_url() . 'admin/Report/datewise');
			}
		}
		$this->load->view('admin/preview', $data);
	}

	public function send_mail()
	{

		//email to user
		$last = $this->uri->total_segments();
		$regid = base64_decode($this->uri->segment($last - 2));
		$regnum = base64_decode($this->uri->segment($last - 1));
		$flag = $this->uri->segment($last);
		if (!$flag) {
			$flag = 0;
		}

		if ($regnum != '' && is_numeric($regid)) {
			// TODO : Update aadhar card field in emailer - admin_resend_mail
			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'admin_resend_mail'));
			if (count($emailerstr) > 0) {
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

				//$decpass = $aes->decrypt($user_info[0]['usrpassword']);

				//Query to get user details
				$this->db->join('state_master', 'state_master.state_code=member_registration.state', 'LEFT');
				$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute', 'LEFT');
				$this->db->join('qualification', 'qualification.qid=member_registration.specify_qualification', 'LEFT');
				$this->db->join('idtype_master', 'idtype_master.id=member_registration.idproof', 'LEFT');
				$this->db->join('designation_master', 'designation_master.dcode=member_registration.designation', 'LEFT');
				//$this->db->join('payment_transaction','payment_transaction.ref_id=member_registration.regid','LEFT');				
				$result = $this->master_model->getRecords('member_registration', array('regnumber' => $regnum, 'regid' => $regid), 'regid,regnumber,registrationtype,firstname,middlename,lastname,usrpassword,dateofbirth,dateofjoin,gender,qualification,specify_qualification,idproof,idNo,aadhar_card,address1,address2,address3,address4,district,city,pincode,email,mobile,office,pincode,state_master.state_name,institution_master.name inst_name,qualification.name qual_name,idtype_master.name id_name,designation_master.dname');
				//echo $this->db->last_query();exit;
				if (count($result) > 0) {
					$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					$qualification = '';
					if ($result[0]['qualification'] == 'U') {
						$qualification = 'Under Graduate';
					} else if ($result[0]['qualification'] == 'G') {
						$qualification = 'Graduate';
					} else if ($result[0]['qualification'] == 'P') {
						$qualification = 'Post Graduate';
					}

					$trans_details = array();
					$transaction_no = 'NA';
					$transaction_amt = 'NA';
					$transaction_date = 'NA';
					if ($result[0]['registrationtype'] == 'NM' || $result[0]['registrationtype'] == 'DB') {
						$trans_details = $this->Master_model->getRecords('payment_transaction a', array('pay_type' => 2, 'member_regnumber' => $regnum), 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					} else {
						$trans_details = $this->Master_model->getRecords('payment_transaction a', array('status' => 1, 'pay_type' => 1, 'ref_id' => $regid), 'transaction_no,receipt_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					}
					//echo $this->db->last_query();
					if (count($trans_details)) {
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if ($date != '0000-00-00') {
							$transaction_date = date('d-m-Y h:i:s A', strtotime($date));
						}
					}


					$newstring1 = str_replace("#REG_NUM#", "" . $regnum . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#PASSWORD#", "" . $decpass . "", $newstring1);
					$newstring3 = str_replace("#NAME#", "" . strtoupper($userfinalstrname) . "", $newstring2);
					$newstring4 = str_replace("#TRANSACTION_NO#", "" . $transaction_no . "", $newstring3);
					$newstring5 = str_replace("#TRANSACTION_DATE#", "" . $transaction_date . "", $newstring4);
					$newstring6 = str_replace("#AMOUNT#", "" . $transaction_amt . "", $newstring5);
					$newstring7 = str_replace("#DOB#", "" . date('d-m-Y', strtotime($result[0]['dateofbirth'])) . "", $newstring6);
					$newstring8 = str_replace("#GENDER#", "" . strtoupper($result[0]['gender']) . "", $newstring7);
					$newstring9 = str_replace("#QUALIFICATION#", "" . strtoupper($qualification) . "", $newstring8);
					$newstring10 = str_replace("#SPECIFY#", "" . strtoupper($result[0]['qual_name']) . "", $newstring9);
					$newstring11 = str_replace("#INSTITUDE#", "" . strtoupper($result[0]['inst_name']) . "", $newstring10);
					$newstring12 = str_replace("#BRANCH#", "" . strtoupper($result[0]['office']) . "", $newstring11);
					$newstring13 = str_replace("#DESIGNATION#", "" . strtoupper($result[0]['dname']) . "", $newstring12);
					$newstring14 = str_replace("#DOJ#", "" . date('d-m-Y', strtotime($result[0]['dateofjoin'])) . "", $newstring13);
					$newstring15 = str_replace("#ID_PROOF#", "" . strtoupper($result[0]['id_name']) . "", $newstring14);

					if ($result[0]['registrationtype'] == 'NM' && $result[0]['registrationtype'] == 'DB') {
						$newstring151 = str_replace("#ID_NO#", "" . strtoupper($result[0]['idNo']) . "", $newstring15);
					} else {
						$newstring151 = $newstring15;
					}


					$newstring16 = str_replace("#AADHAR_NO#", "" . strtoupper($result[0]['aadhar_card']) . "", $newstring151);
					$newstring17 = str_replace("#MOBILE#", "" . strtoupper($result[0]['mobile']) . "", $newstring16);

					//$newstring17 = str_replace("#MOBILE#", "".strtoupper($result[0]['mobile'])."",$newstring16);

					$newstring18 = str_replace("#ADDRESS1#", "" . strtoupper($result[0]['address1']) . "", $newstring17);
					$newstring19 = str_replace("#ADDRESS2#", "" . strtoupper($result[0]['address2']) . "", $newstring18);
					$newstring20 = str_replace("#ADDRESS3#", "" . strtoupper($result[0]['address3']) . "", $newstring19);
					$newstring21 = str_replace("#ADDRESS4#", "" . strtoupper($result[0]['address4']) . "", $newstring20);
					$newstring22 = str_replace("#CITY#", "" . strtoupper($result[0]['city']) . "", $newstring21);
					$newstring23 = str_replace("#DISTRICT#", "" . strtoupper($result[0]['district']) . "", $newstring22);
					$newstring24 = str_replace("#STATE#", "" . strtoupper($result[0]['state_name']) . "", $newstring23);
					$final_str = str_replace("#PINCODE#", "" . strtoupper($result[0]['pincode']) . "", $newstring24);

					$info_arr = array(
						'to' => $result[0]['email'],
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $final_str
					);


					if ($this->Emailsending->mailsend($info_arr)) {
						$this->session->set_flashdata('success', 'Email sent successfully !!');
						if ($flag != 0 && $flag != 1) {
							redirect(base_url() . 'admin/Search/search_success');
						} elseif ($flag == 0) {
							redirect(base_url() . 'admin/Report/datewise');
						} elseif ($flag == 1) {
							//redirect(base_url().'admin/Report/query');
							redirect(base_url() . 'admin/Search/success');
						}
					} else {
						//echo 'Error while sending email';
						$this->session->set_flashdata('error', 'Error while sending email !!');
						if ($flag != 0 && $flag != 1 && $flag != 2) {
							redirect(base_url() . 'admin/Search/search_success');
						} elseif ($flag == 0) {
							redirect(base_url() . 'admin/Report/datewise');
						} elseif ($flag == 1) {
							redirect(base_url() . 'admin/Report/query');
						}
					}
				} else {
					$this->session->set_flashdata('error', 'Something went wrong...');
					if ($flag != 0 && $flag != 1) {
						redirect(base_url() . 'admin/Search/search_success');
					} elseif ($flag == 0) {
						redirect(base_url() . 'admin/Report/datewise');
					} elseif ($flag == 1) {
						redirect(base_url() . 'admin/Report/query');
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong...');
				if ($flag != 0 && $flag != 1) {
					redirect(base_url() . 'admin/Search/search_success');
				} elseif ($flag == 0) {
					redirect(base_url() . 'admin/Report/datewise');
				} elseif ($flag == 1) {
					redirect(base_url() . 'admin/Report/query');
				}
			}
		}
	}

	//Successfull Exam Application
	public function BD_success()
	{
		/*if($this->session->userdata('roleid')!=1){
			redirect(base_url().'admin/MainController');
		}*/

		ini_set("memory_limit", "-1");

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>BillDesk Success Report</li>
							   </ol>';

		if (isset($_POST['download'])) {
			$data['result'] = array();
			$field = '';
			$value = '';
			$sortkey = '';
			$sortval = '';
			$from_date = '';
			$to_date = '';

			/*$session_arr = check_session();
			if($session_arr)
			{
				$field = $session_arr['field'];
				$value = $session_arr['value'];
			}*/

			if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
				$from_date = $_POST['from_date'];
			}

			if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
				$to_date = $_POST['to_date'];
			}

			$select = 'DISTINCT(b.transaction_no),regid,a.regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,b.status,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no';
			$this->db->join('member_registration a', 'a.regnumber = c.regnumber', 'LEFT');
			$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
			if ($from_date != '' && $to_date != '') {
				$this->db->where('DATE(date) BETWEEN "' . $from_date . '" AND "' . $to_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND pay_status = 1 ');
				//isactive = "1" AND
			} else if ($from_date != '' & $to_date == '') {
				$this->db->where('DATE(date) = "' . $from_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND pay_status = 1 ');
				//isactive = "1" AND
			} else {
				$this->db->where('isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND pay_status = 1 ');
			}
			$res = $this->master_model->getRecords("member_exam c", '', $select, array('date' => 'DESC'));


			if (count($res)) { //echo "111";
				$data = "";
				foreach ($res as $row) {
					$data .= $row['transaction_no'] . ",";
					$data .= $row['receipt_no'] . ",";
					$data .= $row['amount'] . ",";
					$data .= date('Ymd', strtotime($row['date'])) . "\n";
				}

				logadminactivity($log_title = "Success BillDesk Report Downloaded", $log_message = "");

				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_Settlement_' . date('YmdHis') . '.txt.gz"');
				echo gzencode($data, 9);
				exit();
			}
			/*header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename="default-filename.txt"');	*/
		}

		$this->load->view('admin/success_bd_list', $data);
	}

	//Successfull Exam Application (Billdesk)
	public function getSuccessBDList()
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
		$res1 = 0;

		$session_arr = check_session();
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		$select = 'DISTINCT(b.transaction_no)';

		$this->db->join('member_registration a', 'a.regnumber = c.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND pay_type=1');
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND `pay_status` = 1 ');
				//isactive = "1" AND
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND `pay_status` = 1 ');
				//isactive = "1" AND
			}
			//$res1 = $this->UserModel->getRecordCount("member_registration a", '','',$select);
			$this->db->select('DISTINCT(b.transaction_no),regid');
			$query = $this->db->get('member_exam c');
			$res1 = $query->num_rows();
		} else {
			$this->db->select('DISTINCT(b.transaction_no),regid');
			$this->db->where('isdeleted', 0);
			$this->db->where('isactive', '1');
			$this->db->where('status', 1);
			$this->db->where('pay_type', 2);
			$this->db->where('pay_status', 1);
			//$this->db->where('gateway','billdesk');
			//$res1 = $this->UserModel->getRecordCount("member_registration a", $field, $value,$select);
			$query = $this->db->get('member_exam c');
			$res1 = $query->num_rows();
		}

		$url = base_url() . "admin/Report/getSuccessBDList/";
		$total_row = $res1;
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);

		$select = 'DISTINCT(b.transaction_no),regid,a.regnumber,firstname,lastname,createdon,b.status,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date';
		$this->db->join('member_registration a', 'a.regnumber = c.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND pay_type=1');
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '"  AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND `pay_status` = 1 ');
				//isactive = "1" AND
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '"  AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 2 AND `pay_status` = 1 ');
				//isactive = "1" AND
			}
			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("member_exam c", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 1);
			$this->db->where('pay_type', 2);
			$this->db->where('pay_status', 1);
			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("member_exam c", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}


		//$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
				$data['action'][] = $action;

				$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '">' . $row['regnumber'] . '</a>';
				//$result[$i]['regnumber'] = $regnumber;
				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	//Unsuccessfull Exam Application
	public function BD_failure($flag = '')
	{
		ini_set("memory_limit", "-1");

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>BillDesk Failure Report</li>
							   </ol>';

		if (isset($_POST['download'])) {
			$data['result'] = array();
			$field = '';
			$value = '';
			$sortkey = '';
			$sortval = '';

			if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
				$from_date = $_POST['from_date'];
			}

			if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
				$to_date = $_POST['to_date'];
			}

			$select = 'DISTINCT(b.transaction_no),regid,a.regnumber,namesub,firstname,lastname,createdon,b.status,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no,b.transaction_details';
			$this->db->join('member_registration a', 'a.regnumber = c.regnumber', 'LEFT');
			$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
			if ($from_date != '' && $to_date != '') {
				$this->db->where('DATE(date) BETWEEN "' . $from_date . '" AND "' . $to_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND `pay_type` = 2 AND `pay_status` = 0 ');
				//isactive = "0" AND
			} else if ($from_date != '' & $to_date == '') {
				$this->db->where('DATE(date) = "' . $from_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND `pay_type` = 2 AND `pay_status` = 0');
				//isactive = "0" AND 
			} else {
				$this->db->where('isdeleted=0 AND b.status=0 AND isactive = "1" AND `pay_type` = 2 AND `pay_status` = 0 ');
				//isactive = "0" AND
			}
			$res = $this->master_model->getRecords("member_exam c", '', $select, array('date' => 'DESC'));

			if (count($res)) {
				// echo $this->db->last_query();

				$data = "";
				foreach ($res as $row) {
					$data .= $row['transaction_no'] . ",";
					$data .= $row['receipt_no'] . ",";
					$data .= $row['amount'] . ",";
					$data .= date('Ymd', strtotime($row['date']));
					if ($flag != '' && $flag == 'reason') {
						$data .= "," . $row['transaction_details'];
					}
					$data .= "\n";
				}
				//echo $data;exit;
				logadminactivity($log_title = "Failure BillDesk Report Downloaded", $log_message = "");

				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_Failure_' . date('YmdHis') . '.txt.gz"');
				echo gzencode($data, 9);
				exit();
			}
		}
		if ($flag == 'reason') {
			$this->load->view('admin/failure_bd_reasons', $data);
		} else {
			$this->load->view('admin/failure_bd_list', $data);
		}
	}

	//Unsuccessfull Exam Application
	public function getFailureBDList()
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		$select = 'DISTINCT(b.transaction_no)';
		$this->db->join('member_registration a', 'a.regnumber = c.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND pay_type=1');
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND `pay_type` = 2 AND `pay_status` = 0 ');
				//isactive = "0" AND 
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND `pay_type` = 2 AND `pay_status` = 0 ');
				//isactive = "0" AND 
			}

			// Not to apply another WHERE condition
			//$res1 = $this->UserModel->getRecordCount("member_registration a", '', '',$select);
			$this->db->select('DISTINCT(b.transaction_no),regid');
			$query = $this->db->get('member_exam c');
			$res1 = $query->num_rows();
		} else {
			$this->db->where('isactive', '1');
			$this->db->select('DISTINCT(b.transaction_no),regid');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 0);
			$this->db->where('pay_type', 2);
			$this->db->where('pay_status', 0);
			//$res1 = $this->UserModel->getRecordCount("member_registration a", $field, $value,$select);
			$query = $this->db->get('member_exam c');
			$res1 = $query->num_rows();
		}
		$url = base_url() . "admin/Report/getFailureBDList/";
		$total_row = $res1;
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'DISTINCT(b.transaction_no),regid,a.regnumber,namesub,firstname,lastname,createdon,b.status,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.transaction_details';
		$this->db->join('member_registration a', 'a.regnumber = c.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		//$this->db->join('member_exam c','b.ref_id=c.id','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND pay_type=1');
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND `pay_type` = 2 AND `pay_status` = 0 ');
				//isactive = "0" AND 
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND `pay_type` = 2 AND `pay_status` = 0 ');



				//isactive = "0" AND 
			}
			$this->db->order_by('date', 'DESC');
			// Not to apply another WHERE condition
			$res = $this->UserModel->getRecords("member_exam c", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 0);
			$this->db->where('pay_type', 2);
			$this->db->where('pay_status', 0);
			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("member_registration a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}


		////$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '">' . $row['regnumber'] . '</a>';
				//$result[$i]['regnumber'] = $regnumber;
				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function dup_icard_success()
	{
		ini_set("memory_limit", "-1");

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		if (isset($_POST['download'])) {
			$data['result'] = array();
			$field = '';
			$value = '';
			$sortkey = '';
			$sortval = '';

			/*$session_arr = check_session();
			if($session_arr)
			{
				$field = $session_arr['field'];
				$value = $session_arr['value'];
			}*/

			if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
				$from_date = $_POST['from_date'];
			}

			if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
				$to_date = $_POST['to_date'];
			}

			$select = 'regid,c.regnumber,firstname,lastname,createdon,b.status,b.transaction_no,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no';
			$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
			$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
			if ($from_date != '' && $to_date != '') {
				$this->db->where('DATE(date) BETWEEN "' . $from_date . '" AND "' . $to_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			} else if ($from_date != '' & $to_date == '') {
				$this->db->where('DATE(date) = "' . $from_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			} else {
				$this->db->where('isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			}
			$res = $this->master_model->getRecords("duplicate_icard a", '', $select, array('date' => 'DESC'));

			if (count($res)) {
				// echo $this->db->last_query();

				$data = "";
				foreach ($res as $row) {
					$data .= $row['transaction_no'] . ",";
					$data .= $row['receipt_no'] . ",";
					$data .= $row['amount'] . ",";
					$data .= date('Ymd', strtotime($row['date']));
					$data .= "\n";
				}

				logadminactivity($log_title = "Duplicate I-card Success Transaction Report Downloaded", $log_message = "");

				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_Settlement_' . date('YmdHis') . '.txt.gz"');
				echo gzencode($data, 9);
				exit();
			}
			/*header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename="default-filename.txt"');	*/
		}

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Dup I-card Success Transaction</li>
							   </ol>';

		$this->load->view('admin/dup_icard_success_list', $data);
	}

	//Successfull Duplicate I-card transaction (SBI ePay)
	public function getSuccessDupIcard()
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		$select = 'DISTINCT(b.transaction_no)';
		$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			}
			$res1 = $this->UserModel->getRecordCount("duplicate_icard a", '', '', $select);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 1);
			$this->db->where('pay_type', 3);
			$this->db->where('pay_status', '1');
			$res1 = $this->UserModel->getRecordCount("duplicate_icard a", '', '', $select);
		}
		$url = base_url() . "admin/Report/getSuccessDupIcard/";
		$total_row = $res1;
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'regid,c.regnumber,firstname,lastname,createdon,b.status,b.transaction_no,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date';
		$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 3 AND pay_status = "1" ');
			}
			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("duplicate_icard a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 1);
			$this->db->where('pay_type', 3);
			$this->db->where('pay_status', '1');

			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("duplicate_icard a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		}


		////$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
				$data['action'][] = $action;

				//$regnumber = '<a href="'.base_url().'admin/Report/preview/'.base64_encode($row['regid']).'">'.$row['regnumber'].'</a>';
				//$result[$i]['regnumber'] = $regnumber;
				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function dup_icard_failure($flag = '')
	{
		ini_set("memory_limit", "-1");

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		if (isset($_POST['download'])) {
			$data['result'] = array();
			$field = '';
			$value = '';
			$sortkey = '';
			$sortval = '';

			if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
				$from_date = $_POST['from_date'];
			}

			if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
				$to_date = $_POST['to_date'];
			}

			$select = 'regid,c.regnumber,firstname,lastname,createdon,b.status,b.transaction_no,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no,b.transaction_details';
			$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
			$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
			if ($from_date != '' && $to_date != '') {
				$this->db->where('DATE(date) BETWEEN "' . $from_date . '" AND "' . $to_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3  AND pay_status != "1" ');
			} else if ($from_date != '' & $to_date == '') {
				$this->db->where('DATE(date) = "' . $from_date . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3 AND pay_status != "1" ');
			} else {
				$this->db->where('isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3 AND pay_status != "1" ');
			}
			$res = $this->master_model->getRecords("duplicate_icard a", '', $select, array('date' => 'DESC'));

			if (count($res)) {
				// echo $this->db->last_query();

				$data = "";
				foreach ($res as $row) {
					$data .= $row['transaction_no'] . ",";
					$data .= $row['receipt_no'] . ",";
					$data .= $row['amount'] . ",";
					$data .= date('Ymd', strtotime($row['date']));
					if ($flag != '' && $flag == 'reason') {
						$data .= "," . $row['transaction_details'];
					}
					$data .= "\n";
				}

				logadminactivity($log_title = "Duplicate I-card Failure Transaction Report Downloaded", $log_message = "");

				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="IIBF_Failure_' . date('YmdHis') . '.txt.gz"');
				echo gzencode($data, 9);
				exit();
			}
			/*header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename="default-filename.txt"');	*/
		}

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Dup I-card Failure Transaction</li>
							   </ol>';

		if ($flag == 'reason') {
			$this->load->view('admin/dup_icard_failure_reasons', $data);
		} else {
			$this->load->view('admin/dup_icard_failure_list', $data);
		}
	}

	//Unsuccessfull Duplicate I-card transaction (SBI ePay)
	public function getFailureDupIcard()
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		$select = 'DISTINCT(b.transaction_no)';
		$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3 AND pay_status != "1" ');
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3 AND pay_status != "1" ');
			}
			$res1 = $this->UserModel->getRecordCount("duplicate_icard a", '', '', $select);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 0);
			$this->db->where('pay_type', 3);
			$this->db->where('pay_status !=', '1');
			$res1 = $this->UserModel->getRecordCount("duplicate_icard a", '', '', $select);
		}
		$url = base_url() . "admin/Report/getFailureDupIcard/";
		$total_row = $res1;
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);

		$select = 'regid,c.regnumber,firstname,lastname,createdon,b.status,b.transaction_no,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.transaction_details';
		$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3 AND pay_status != "1" ');
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "1" AND isdeleted=0 AND b.status=0 AND pay_type = 3 AND pay_status != "1" ');
			}
			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("duplicate_icard a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '1');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 0);
			$this->db->where('pay_type', 3);
			$this->db->where('pay_status !=', '1');

			$this->db->order_by('date', 'DESC');
			$res = $this->UserModel->getRecords("duplicate_icard a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		}


		////$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="' . base_url() . 'admin/Report/reg_edit/' . base64_encode($row['regid']) . '">Edit </a>';
				$data['action'][] = $action;

				//$regnumber = '<a href="'.base_url().'admin/Report/preview/'.base64_encode($row['regid']).'">'.$row['regnumber'].'</a>';
				//$result[$i]['regnumber'] = $regnumber;
				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function failCandReport()
	{
		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Failure Candidate Report</li>
							   </ol>';

		$this->load->view('admin/failure_candidate_list', $data);
	}

	//Failde Candidate Registartions (SBI ePay)
	public function getFailCandReport()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		//$where = " `isactive` = '0' AND `isdeleted` = 0 AND `pay_type` = 1 AND `b`.`status` = 0 ";
		$select = 'DISTINCT(b.transaction_no)';
		$this->db->join('payment_transaction b', 'b.ref_id=a.regid', 'RIGHT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND pay_type=1');
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "0" AND isdeleted=0 AND b.status=0 AND pay_type = 1');
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "0" AND isdeleted=0 AND b.status=0 AND pay_type = 1');
			}

			// Not to apply another WHERE condition
			$res1 = $this->UserModel->getRecordCount("member_registration a", '', '', $select);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '0');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 0);
			$this->db->where('pay_type', 1);
			$res1 = $this->UserModel->getRecordCount("member_registration a", $field, $value, $select);
		}

		$url = base_url() . "admin/Report/getFailCandReport/";
		$total_row = $res1;
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		$select = 'regid,regnumber,namesub,firstname,middlename,lastname,usrpassword,createdon,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,DATE_FORMAT(createdon,"%d-%m-%Y") createdon,mobile,b.status,b.transaction_no,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.transaction_details';
		$this->db->join('payment_transaction b', 'b.ref_id=a.regid', 'RIGHT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];
			if ($value1 != '' && $value2 != '') {
				//$this->db->where('DATE(createdon) BETWEEN "'.$value1.'" AND "'.$value2.'" AND pay_type=1');
				$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" AND isactive = "0" AND isdeleted=0 AND b.status=0 AND pay_type = 1');
			} else if ($value1 != '' & $value2 == '') {
				$this->db->where('DATE(date) = "' . $value1 . '" AND isactive = "0" AND isdeleted=0 AND b.status=0 AND pay_type = 1');
			}

			// Not to apply another WHERE condition
			$res = $this->UserModel->getRecords("member_registration a", $select, '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('pay_type',1);
			$this->db->where('isactive', '0');
			$this->db->where('isdeleted', 0);
			$this->db->where('status', 0);
			$this->db->where('pay_type', 1);
			$res = $this->UserModel->getRecords("member_registration a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		}

		//echo $this->db->last_query();
		////$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();

			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			$i = 0;
			foreach ($result as $row) {
				$regnumber = '<a href="' . base_url() . 'admin/Report/preview/' . base64_encode($row['regid']) . '">' . $row['regnumber'] . '</a>';
				$result[$i]['regnumber'] = $regnumber;

				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));
				$result[$i]['firstname'] = $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'];
				$decpass = $aes->decrypt(trim($row['usrpassword']));
				$result[$i]['usrpassword'] = $decpass;

				if ($row['status'] == 1)
					$result[$i]['status'] = 'Completed';
				else if ($row['status'] == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function query()
	{
		if ($this->session->userdata('roleid') == 4 || $this->session->userdata('roleid') == 5) {
			redirect(base_url() . 'admin/MainController');
		}
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$data['member'] = array();
		$data['exams'] = array();
		$data['duplicate_icard'] = array();
		$data['duplicate_certi'] = array();
		$data['renewal_details'] = array();
		$data['non_member_csc'] = array();
		$data['membership_details'] = 0;
		$per_page = 10;
		$last = $this->uri->total_segments();
		$start = 0;
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$SearchVal = '';
		$searchBy = '';
		$searchOn = '';
		$data['member_details'] = array();
		$bulk_entry = '';
		$members2 = array();
		$transaction_result2 = array();
		$exams1 = array();
		$exams2 = array();

		if ($page != 0) {
			$start = $page - 1;
		}

		$where = " `isactive` = '1' AND `isdeleted` = 0 ";
		//$where = " `isdeleted` = 0 ";
		$examWhr = '';
		$icardWhr = '';
		$certiWhr = '';
		$renewalWhr = '';
		$contactclassWhr = '';
		$nonmembercscWhr = '';

		if (isset($_POST['btnSearch'])) {  //error_reporting(1);
			if (isset($_POST['searchBy']) && $_POST['searchBy'] != '') {
				$searchBy = trim($_POST['searchBy']);
			}

			if (isset($_POST['searchOn']) && $_POST['searchOn'] != '') {
				$searchOn = trim($_POST['searchOn']);
			}

			if (isset($_POST['SearchVal']) && $_POST['SearchVal'] != '') {
				$SearchVal = trim($_POST['SearchVal']);
			}

			if ($searchBy != '' && $searchOn != '' && $SearchVal != '') {
				switch ($searchBy) {
					case 'regnumber':
						$where .= ' AND a.regnumber = "' . $SearchVal . '"';
						$examWhr = ' a.regnumber = "' . $SearchVal . '" AND a.isdeleted = 0 ';
						$icardWhr = ' c.regnumber = "' . $SearchVal . '"';
						$certiWhr = ' c.regnumber = "' . $SearchVal . '"';
						$renewalWhr =  ' mr.regnumber = "' . $SearchVal . '"';
						$contactclassWhr =  ' c.regnumber = "' . $SearchVal . '"';
						$nonmembercscWhr =  ' a.regnumber = "' . $SearchVal . '"';
						break;
					case 'mobile':
						$where .= ' AND a.mobile = "' . $SearchVal . '"';
						$examWhr = ' a.mobile = "' . $SearchVal . '" AND a.isdeleted = 0 ';
						$icardWhr = ' c.mobile = "' . $SearchVal . '"';
						$certiWhr = ' c.mobile = "' . $SearchVal . '"';
						$renewalWhr =  ' mr.mobile = "' . $SearchVal . '"';
						$contactclassWhr =  ' c.mobile = "' . $SearchVal . '"';
						$nonmembercscWhr =  ' a.mobile = "' . $SearchVal . '"';
						break;
					case 'transaction_no': 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
						$examWhr = ' b.transaction_no = "' . $SearchVal . '" AND a.isdeleted = 0 ';
						$icardWhr = ' b.transaction_no = "' . $SearchVal . '"';
						$certiWhr = ' b.transaction_no = "' . $SearchVal . '"';
						$renewalWhr =  ' p.transaction_no = "' . $SearchVal . '"';
						$contactclassWhr =  ' c.transaction_no = "' . $SearchVal . '"';
						$nonmembercscWhr =  ' b.transaction_no = "' . $SearchVal . '"';
						break;
					case 'email':
						$where .= ' AND a.email = "' . $SearchVal . '"';
						$examWhr = ' a.email = "' . $SearchVal . '" AND a.isdeleted = 0 ';
						$icardWhr = ' c.email = "' . $SearchVal . '"';
						$certiWhr = ' c.email = "' . $SearchVal . '"';
						$renewalWhr =  ' mr.email = "' . $SearchVal . '"';
						$contactclassWhr =  ' c.email = "' . $SearchVal . '"';
						$nonmembercscWhr =  ' a.email = "' . $SearchVal . '"';
						break;
				}
				//error_reporting(E_ALL);

				$this->db->select('registrationtype');
				$type = $this->db->get_where('member_registration', array('regnumber' => $SearchVal));
				$type_result = $type->row();

				//check member is bulk or not 
				$is_bulk_entry = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $SearchVal, 'record_source' => 'Bulk'), 'record_source');
				if (count($is_bulk_entry)) {
					$bulk_entry = 'Bulk';
				}

				$select = 'DISTINCT(b.transaction_no),regid,a.regnumber,namesub,firstname,middlename,lastname,usrpassword, registrationtype,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.amount,b.transaction_details,b.auth_code,b.date';

				if ($searchBy == 'transaction_no') {

					$reg_num_res =  $this->master_model->getRecords("payment_transaction b", array('transaction_no' => $SearchVal), 'member_regnumber');

					//print_r($reg_num_res);
					$where .= " AND transaction_no = '" . $SearchVal . "' ";
					if (count($reg_num_res)) {
						$mem_regnum = $reg_num_res[0]['member_regnumber'];
						$where .= " AND a.regnumber = '" . $mem_regnum . "'";
					}
				}

				// MEMBER PAYMENT DETAILS
				$this->db->join('payment_transaction b', 'b.member_regnumber=a.regnumber', 'LEFT');
				$this->db->join('member_exam c', 'c.regnumber=a.regnumber', 'LEFT');

				$where1 = $where;


				$where1 .= ' GROUP BY a.regnumber,a.registrationtype ';

				$where .= '  AND b.status = 1  AND CASE WHEN a.registrationtype = "NM" THEN b.pay_type = 2 AND b.ref_id = c.id WHEN a.registrationtype = "DB" THEN b.pay_type = 2 AND b.ref_id = c.id ELSE b.pay_type = 1 AND b.ref_id = a.regid END';

				$this->db->where($where, '', false);
				//$members = $this->master_model->getRecords("member_registration a", "", $select,array('date'=>'ASC'),0,1);
				$members1 = $this->master_model->getRecords("member_registration a", "", $select, array('date' => 'ASC'));
				//echo $this->db->last_query();

				// MEMBER DETAILS
				$select1 = 'DISTINCT(a.regnumber),b.transaction_no, regid,namesub,firstname,middlename,lastname,usrpassword, registrationtype,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.amount,b.transaction_details,b.auth_code,b.date';
				$this->db->join('payment_transaction b', 'b.member_regnumber=a.regnumber', 'LEFT');
				$this->db->where($where1, '', false);
				$member_details = $this->master_model->getRecords("member_registration a", "", $select1, array('date' => 'ASC'));
				//echo $this->db->last_query();

				//Transaction Details
				$this->db->select('DISTINCT(payment_transaction.transaction_no), regid, regnumber, namesub, firstname, middlename, lastname, usrpassword, registrationtype, DATE_FORMAT(dateofbirth,"%d-%m-%Y")dateofbirth,mobile ,createdon, status, amount, transaction_details, auth_code,date');
				$this->db->from('payment_transaction');
				$this->db->join('member_registration ', 'payment_transaction.member_regnumber = member_registration.regnumber');
				$this->db->where(array('payment_transaction.transaction_no' => $SearchVal));
				$this->db->where(array('member_registration.isactive' => '1'));

				$this->db->where(array('payment_transaction.status' => 1));
				$transaction = $this->db->get();
				$transaction_result1 = $transaction->result();
				//echo $this->db->last_query(); die;
				//  echo "<pre>";
				//print_r($transaction_result1);
				//exit;

				//echo "<br>";

				if (count($member_details)) {

					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();

					for ($i = 0; $i < count($member_details); $i++) {
						$decpass = $aes->decrypt(trim($member_details[$i]['usrpassword']));
						$member_details[$i]['usrpassword'] = $decpass;
					}

					$data['membership_details'] = 1;

					/*if($members[0]['registrationtype'] != "NM")
					{
						$data['membership_details'] = 1;
					}
					else
						$data['membership_details'] = 0;*/

					$data['member_details'] = $member_details;
					//$data['member'] = $members;
					//$data['transaction_detail'] = $transaction_result1;

					if ($bulk_entry == 'Bulk') {
						//	 echo 'in',$bulk_entry;exit;
						//members
						/*SELECT DISTINCT(b.transaction_no), `regid`, `a`.`regnumber`, `namesub`, `firstname`, `middlename`, `lastname`, `usrpassword`, `registrationtype`, DATE_FORMAT(dateofbirth, "%d-%m-%Y") dateofbirth, `mobile`, `createdon`, `b`.`status`, `b`.`amount`, `b`.`transaction_details`, `b`.`auth_code`, `b`.`date` 
					FROM `member_registration` `a`
					LEFT JOIN `payment_transaction` `b` ON `b`.`member_regnumber`=`a`.`regnumber` 
					LEFT JOIN `member_exam` `c` ON `c`.`regnumber`=`a`.`regnumber` 
					WHERE `isactive` = '1' AND `isdeleted` = 0 AND a.regnumber = "510000742" AND b.status = 1 AND CASE WHEN a.registrationtype = "NM" THEN b.pay_type = 2 AND b.ref_id = c.id WHEN a.registrationtype = "DB" THEN b.pay_type = 2 AND b.ref_id = c.id ELSE b.pay_type = 1 AND b.ref_id = a.regid END ORDER BY `date` ASC*/
						$not_nos = array(2, 3);
						$this->db->select('bulk_payment_transaction.transaction_no,regid, member_registration.regnumber, namesub, firstname,middlename,lastname, usrpassword,registrationtype,DATE_FORMAT(dateofbirth, "%d-%m-%Y") dateofbirth,mobile,createdon,bulk_payment_transaction.status, bulk_payment_transaction.amount, bulk_payment_transaction.transaction_details, bulk_payment_transaction.auth_code,bulk_payment_transaction.date,bulk_payment_transaction.UTR_no,bulk_payment_transaction.description,member_exam.exam_fee,member_exam.base_fee,member_exam.id,bulk_payment_transaction.updated_date,bulk_payment_transaction.pay_count,bulk_payment_transaction.receipt_no');
						$this->db->from('member_registration');
						$this->db->join('member_exam', 'member_exam.regnumber = member_registration.regnumber');
						$this->db->join('bulk_member_payment_transaction', 'bulk_member_payment_transaction.memexamid= member_exam.id');
						$this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.id=bulk_member_payment_transaction.ptid');
						$this->db->where(array('member_registration.regnumber' => $SearchVal));
						$this->db->where(array('member_registration.isactive' => '1', 'member_registration.registrationtype' => 'NM', 'member_exam.bulk_isdelete!=' => 1, 'bulk_payment_transaction.status' => 1));
						$this->db->where_not_in('member_exam.pay_status', $not_nos);
						$this->db->order_by('member_exam.id', 'DESC');
						$this->db->limit(1);
						$members_rs = $this->db->get();
						$members2 = $members_rs->result_array();

						//$data['member'] = $members;
						//echo 'member</br>';
						//echo $this->db->last_query(); exit;

						//Transaction Details
						$this->db->select('regid, member_registration.regnumber, namesub, firstname, middlename, lastname, usrpassword, registrationtype, DATE_FORMAT(dateofbirth,"%d-%m-%Y")dateofbirth,mobile ,createdon, status, amount, transaction_details, auth_code,date,UTR_no,bulk_payment_transaction.description,bulk_payment_transaction.updated_date,bulk_payment_transaction.pay_count,bulk_payment_transaction.receipt_no,member_exam.base_fee');
						$this->db->from('bulk_payment_transaction');
						$this->db->join('bulk_member_payment_transaction', 'bulk_member_payment_transaction.ptid = bulk_payment_transaction.id');
						$this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid');
						$this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber');
						$this->db->where(array('member_registration.regnumber' => $SearchVal));
						$this->db->where(array('bulk_payment_transaction.status' => 1));
						$this->db->where(array('member_exam.bulk_isdelete!=' => 1));
						$this->db->where_not_in('member_exam.pay_status', $not_nos);
						$transaction = $this->db->get();
						$transaction_result2 = $transaction->result();
						//echo 'transaction_result</br>';
						//echo $this->db->last_query();
						//$data['transaction_detail'] = $transaction_result2;
					}
				} else {
					$data['member_details'] = array();
				}

				if ($searchOn == '01') // 01 for Exam Details
				{

					//$examWhr .= ' AND pay_type = 2';
					//$examWhr .= ' AND c.pay_status = 1 ';
					$examWhr .= ' ';

					$selectExam = 'DISTINCT(b.transaction_no),regid,a.regnumber,namesub,firstname,middlename,lastname,usrpassword, DATE_FORMAT(dateofbirth,"%d-%m-%Y")dateofbirth,mobile,createdon,b.status,b.date,b.receipt_no,ex.description,b.auth_code, b.transaction_details,c.exam_code,c.exam_period,c.exam_mode,c.exam_fee,c.exam_medium,c.exam_center_code,b.gateway';

					$this->db->join('member_registration a', 'c.regnumber=a.regnumber', 'LEFT');
					$this->db->join('exam_master ex', 'c.exam_code=ex.exam_code', 'LEFT');
					//$this->db->join('payment_transaction b','b.member_regnumber=a.regnumber AND c.id=b.ref_id','LEFT');
					$this->db->join('payment_transaction b', 'b.member_regnumber=c.regnumber AND b.ref_id = c.id', 'RIGHT');	// RIGHT LEFT
					$this->db->where($examWhr);
					$this->db->order_by('b.date', 'DESC');
					$exams1 = $this->master_model->getRecords("member_exam c", '', $selectExam);
					//	echo $this->db->last_query(); die;

					if (count($exams1)) {
						for ($j = 0; $j < count($exams1); $j++) {
							$ex_medium_desc = '';
							//$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code'=>$exams1[$j]['exam_code'],'exam_period'=>$exams1[$j]['exam_period']), 'medium_description');
							$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code' => $exams1[$j]['exam_code'], 'exam_period' => $exams1[$j]['exam_period'], 'medium_code' => $exams1[$j]['exam_medium']), 'medium_description');

							if (count($ex_medium)) {
								$ex_medium_desc = $ex_medium[0]['medium_description'];
							}

							$center_name = '';
							$center = $this->master_model->getRecords("center_master", array('exam_name' => $exams1[$j]['exam_code'], 'exam_period' => $exams1[$j]['exam_period'], 'center_code' => $exams1[$j]['exam_center_code']), 'center_name');
							//echo "<br> center qry - ".$this->db->last_query();
							if (count($center)) {
								$center_name = $center[0]['center_name'];
							}

							$exams1[$j]['exam_medium'] = $ex_medium_desc;
							$exams1[$j]['center_name'] = $center_name;
						}
					}
					//$data['exams'] = $exams;

					if ($bulk_entry == 'Bulk') {
						$examWhr .= ' ';
						$not_nos = array(2, 3);
						$selectExam = 'DISTINCT(bulk_payment_transaction.transaction_no),bulk_payment_transaction.UTR_no,bulk_payment_transaction.id as bulk_pay_id,regid,a.regnumber,namesub,firstname,middlename,lastname,usrpassword, DATE_FORMAT(dateofbirth,"%d-%m-%Y")dateofbirth,mobile,createdon,bulk_payment_transaction.status,bulk_payment_transaction.date,bulk_payment_transaction.receipt_no,exam_master.description,bulk_payment_transaction.auth_code, bulk_payment_transaction.transaction_details,member_exam.exam_code,member_exam.exam_period,member_exam.exam_mode,member_exam.base_fee,member_exam.exam_fee,member_exam.exam_medium,member_exam.exam_center_code,bulk_payment_transaction.description,bulk_payment_transaction.updated_date,bulk_payment_transaction.pay_count';
						$this->db->join('member_registration a', 'member_exam.regnumber=a.regnumber', 'LEFT');
						$this->db->join('exam_master', 'member_exam.exam_code=exam_master.exam_code', 'LEFT');
						$this->db->join('bulk_member_payment_transaction', 'bulk_member_payment_transaction.memexamid= member_exam.id');
						$this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.id=bulk_member_payment_transaction.ptid');
						$this->db->where($examWhr);
						$this->db->where_not_in('member_exam.pay_status', $not_nos);
						$this->db->order_by('bulk_payment_transaction.date', 'DESC');
						$exams2 = $this->master_model->getRecords("member_exam", '', $selectExam);
						//echo $this->db->last_query();

						if (count($exams2)) {
							for ($j = 0; $j < count($exams2); $j++) {
								$ex_medium_desc = '';
								//$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code'=>$exams2[$j]['exam_code'],'exam_period'=>$exams2[$j]['exam_period']), 'medium_description');
								$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code' => $exams2[$j]['exam_code'], 'exam_period' => $exams2[$j]['exam_period'], 'medium_code' => $exams2[$j]['exam_medium']), 'medium_description');
								if (count($ex_medium)) {
									$ex_medium_desc = $ex_medium[0]['medium_description'];
								}

								$center_name = '';
								$center = $this->master_model->getRecords("center_master", array('exam_name' => $exams2[$j]['exam_code'], 'exam_period' => $exams2[$j]['exam_period'], 'center_code' => $exams2[$j]['exam_center_code']), 'center_name');
								//echo "<br> center qry - ".$this->db->last_query();
								if (count($center)) {
									$center_name = $center[0]['center_name'];
								}

								$exams2[$j]['exam_medium'] = $ex_medium_desc;
								$exams2[$j]['center_name'] = $center_name;
							}
						}
						//$data['exams'] = $exams;
					}
				} else if ($searchOn == '02') //02 for Duplicate i-card Details
				{

					$icardWhr .= ' AND b.pay_type = 3';
					$icardWhr .= ' ';
					$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');

					//$this->db->join('payment_transaction b','b.ref_id=a.did AND b.member_regnumber=a.regnumber','LEFT');
					$this->db->join('payment_transaction b', 'b.ref_id=a.did', 'LEFT');
					$this->db->where($icardWhr);
					$dup_icard = $this->master_model->getRecords("duplicate_icard a", '', 'DISTINCT(b.transaction_no),regid,c.regnumber,firstname,lastname,createdon,b.status,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no', array('date' => 'DESC'));
					//echo $this->db->last_query(); 
					$data['duplicate_icard'] = $dup_icard;
				} else if ($searchOn == '04') {

					$certiWhr .= ' AND b.pay_type = 4 AND b.member_regnumber = dc.regnumber';
					//$certiWhr .= ' ';
					$this->db->join('member_registration c', 'c.regnumber=dc.regnumber', 'LEFT');

					//$this->db->join('payment_transaction b','b.ref_id=a.did AND b.member_regnumber=a.regnumber','LEFT');
					$this->db->join('payment_transaction b', 'b.ref_id=dc.id', 'LEFT');
					$this->db->where($certiWhr);
					$dup_certi = $this->master_model->getRecords("duplicate_certificate dc", '', 'DISTINCT(b.transaction_no),regid,c.regnumber,c.firstname,c.lastname,c.createdon,b.status,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no', array('date' => 'DESC', 'pay_status' => '1'));
					//echo $this->db->last_query(); 
					$data['duplicate_certi'] = $dup_certi;
				} else if ($searchOn == '05') {


					//echo $this->db->last_query();die;
					$renewalWhr .= ' AND mr.isactive = "1" AND mr.isdeleted = 0 AND mr.is_renewal = "1" AND p.pay_type = 5';
					//Transaction Details
					$this->db->select('DISTINCT(p.transaction_no), regid, regnumber, namesub, firstname, middlename, lastname, usrpassword, registrationtype, DATE_FORMAT(dateofbirth,"%d-%m-%Y")dateofbirth,mobile ,createdon, status, amount, transaction_details, auth_code,date,receipt_no');
					$this->db->from('member_registration mr');
					$this->db->join('payment_transaction p', 'p.member_regnumber = mr.regnumber');
					//$this->db->where(array('member_registration.regnumber' => $SearchVal));
					$this->db->where($renewalWhr);

					$transaction = $this->db->get();
					$transaction_result1 = $transaction->result_array();
					$data['renewal_details'] = $transaction_result1;
					//echo $this->db->last_query();  die;

				} else if ($searchOn == '06') //06 for Contact class Details
				{

					$contactclassWhr .= ' AND b.pay_type = 11';
					$contactclassWhr .= ' ';
					$this->db->join('member_registration c', 'c.regnumber=a.member_no', 'LEFT');

					//$this->db->join('payment_transaction b','b.ref_id=a.did AND b.member_regnumber=a.regnumber','LEFT');
					$this->db->join('payment_transaction b', 'b.ref_id=a.contact_classes_id', 'LEFT');
					$this->db->where($contactclassWhr);
					$contact_classes = $this->master_model->getRecords("contact_classes_registration a", '', 'DISTINCT(b.transaction_no),regid,c.regnumber,c.firstname,c.lastname,a.createdon,b.status,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no', array('date' => 'DESC'));
					//echo $this->db->last_query(); 
					$data['contact_classes'] = $contact_classes;
				} else if ($searchOn == '07') // 09 for csc Exam Details
				{

					$non_member_csc = array();
					$nonmembercscWhr .= ' ';
					$this->db->join('payment_transaction b', 'b.ref_id = a.regid', 'RIGHT');
					$this->db->where($nonmembercscWhr);
					$this->db->order_by('a.createdon', 'DESC');
					$member_data = $this->master_model->getRecords("member_registration a", '', 'regid,regnumber,registrationtype');
					//echo $this->db->last_query(); die;


					if (!empty($member_data)) {
						foreach ($member_data as $res) {

							$regid = $res['regid'];
							$regnumber = $res['regnumber'];
							$registrationtype = $res['registrationtype'];

							if ($registrationtype != 'NM') {
								$selectExam = 'DISTINCT(b.transaction_no),b.status,b.date,b.receipt_no,b.auth_code, b.transaction_details,b.exam_code,b.amount,b.description,b.gateway';
								//$this->db->join('payment_transaction b','b.ref_id = c.id','RIGHT');	// RIGHT LEFT
								$this->db->where('b.ref_id', $regid);
								$this->db->where('b.pay_type', '1');
								//$this->db->order_by('b.date','DESC');
								$res = $this->master_model->getRecords("payment_transaction b", '', $selectExam);
								if (!empty($res)) {
									$non_member_csc[] = $res;
								} else {
									$non_member_csc = array();
								}
								//echo $this->db->last_query();	die;

							} else if ($registrationtype == 'NM') {
								if ($regnumber == '') {
									$selectExam = 'DISTINCT(b.transaction_no),b.member_regnumber,b.status,b.date,b.receipt_no,b.auth_code, b.transaction_details,c.exam_code,c.exam_period,c.exam_mode,b.amount,b.description,c.exam_medium,c.exam_center_code,b.gateway';
									$this->db->join('payment_transaction b', 'b.ref_id = c.id', 'RIGHT');	// RIGHT LEFT
									$this->db->where('b.member_regnumber', $regid);
									$this->db->where('b.pay_type', '2');
									$this->db->order_by('b.date', 'DESC');
									$res = $this->master_model->getRecords("member_exam c", '', $selectExam);
									if (!empty($res)) {
										$non_member_csc[] = $res;
									} else {
										$non_member_csc = array();
									}
									//echo '<br>'.$this->db->last_query();
								} else if ($regnumber != '') {
									$selectExam = 'DISTINCT(b.transaction_no),b.status,b.date,b.receipt_no,b.auth_code, b.transaction_details,c.exam_code,c.exam_period,c.exam_mode,b.amount,b.description,c.exam_medium,c.exam_center_code,b.gateway';
									$this->db->join('payment_transaction b', 'b.ref_id = c.id', 'RIGHT');	// RIGHT LEFT
									$this->db->where('b.member_regnumber', $regnumber);
									$this->db->where('b.pay_type', '2');
									$this->db->order_by('b.date', 'DESC');
									$non_member_csc[] = $this->master_model->getRecords("member_exam c", '', $selectExam);
									//echo $this->db->last_query(); die;
									if (!empty($res)) {
										$non_member_csc[] = $res;
									} else {
										$non_member_csc = array();
									}
								}
							}
							/*  if(count($non_member_csc))
					{
						for($j=0;$j<count($non_member_csc);$j++)
						{
							$ex_medium_desc = '';
							//$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code'=>$exams1[$j]['exam_code'],'exam_period'=>$exams1[$j]['exam_period']), 'medium_description');
							$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code'=>$exams1[$j]['exam_code'],'exam_period'=>$exams1[$j]['exam_period'], 'medium_code'=>$exams1[$j]['exam_medium']), 'medium_description');
							
							if(count($ex_medium))
							{
								$ex_medium_desc = $ex_medium[0]['medium_description'];
							}
							
							$center_name = '';
							$center = $this->master_model->getRecords("center_master", array('exam_name'=>$exams1[$j]['exam_code'],'exam_period'=>$exams1[$j]['exam_period'],'center_code'=>$exams1[$j]['exam_center_code']), 'center_name');
							//echo "<br> center qry - ".$this->db->last_query();
							if(count($center))
							{
								$center_name = $center[0]['center_name'];
							}
						
							$exams1[$j]['exam_medium'] = $ex_medium_desc;
							$exams1[$j]['center_name'] = $center_name;
						}
						
					}  */
						}
					}
				}
				//print_r($non_member_csc); die;
				$data['member'] = array_merge($members1, $members2);
				$data['transaction_detail'] = array_merge($transaction_result1, $transaction_result2);

				//$data['exams'] = array_merge($exams1,$exams2);
				$exams = array_merge($exams1, $exams2);
				$new_exam_arr = array();
				if (count($exams) > 0) {
					foreach ($exams as $examsRes) {
						$new_exam_arr[$examsRes['transaction_no']] = $examsRes;
					}
				}
				$data['exams'] = $new_exam_arr;
				$data['non_member_csc'] = $non_member_csc;


				$data['bulk_entry'] = $bulk_entry;
				//echo '<pre>exams',print_r($data['exams']),'</pre>';
				//echo '<pre>exams1',print_r($exams1),'</pre>';
				//echo '<pre>exams2',print_r($exams2),'</pre>';

			}
		}

		//echo "<pre>";print_r($data['membership_details']);exit;
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';

		//echo '<pre>';print_r($data);exit;
		//echo 'transaction_detail</br>',print_r($data['transaction_detail']);
		$this->load->view('admin/query_report', $data);
	}


	public function member()
	{
		$SearchVal = '';
		$searchBy = '';
		$searchOn = '';
		$where = " `b.isactive` = '1' AND `b.isdeleted` = 0 ";
		$trnWhr = '';
		$recWhr = " `a.status` = '1'";
		$data = '';
		$records = array();
		$data['reg_num_res'] = array();
		if (isset($_POST['btnSearch'])) { //print_r($_POST); 
			if (isset($_POST['searchBy']) && $_POST['searchBy'] != '') {
				$searchBy = trim($_POST['searchBy']);
			}

			if (isset($_POST['SearchVal']) && $_POST['SearchVal'] != '') {
				$SearchVal = trim($_POST['SearchVal']);
			}
			if ($searchBy != '' && $SearchVal != '') {
				switch ($searchBy) {
					case 'regnumber':
						$where .= ' AND b.regnumber = "' . $SearchVal . '"';

						break;

					case 'mobile':
						$where .= ' AND b.mobile = "' . $SearchVal . '"';

						break;
					case 'transaction_no': 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
						$recWhr .= ' a.transaction_no = "' . $SearchVal . '"';
						break;
					case 'email':
						$where .= ' AND b.email = "' . $SearchVal . '"';
						break;
					case 'receipt_no': 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
						$recWhr .= ' a.receipt_no = "' . $SearchVal . '"';
						break;
				}
				if ($searchBy == 'transaction_no') {
					$this->db->DISTINCT('a. receipt_no');
					$this->db->join('member_registration b', 'b.regnumber = a.member_regnumber', 'LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.transaction_no' => $SearchVal));
					if (!empty($reg_num_res)) {
						$i = 0;
						foreach ($reg_num_res as $reg_num_res) {
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password

							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no'] = $reg_num_res['receipt_no'];
							$records[$i]['transaction_no'] = $reg_num_res['transaction_no'];
							$records[$i]['namesub'] = $reg_num_res['namesub'] . $reg_num_res['firstname'] . $reg_num_res['lastname'];
							$records[$i]['password'] = $mem_pass;
							$records[$i]['description'] = $reg_num_res['description'];
							$records[$i]['status'] = $reg_num_res['status'];
							$records[$i]['email'] = $reg_num_res['email'];
							$records[$i]['mobile'] = $reg_num_res['mobile'];
							$records[$i]['date'] = $reg_num_res['date'];

							$status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
							// Call to SBI for status 

							$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
							if ($responsedata[2] == 'SUCCESS') {
								$records[$i]['refund_type'] = '<html> Success </html>';
							} else if ($responsedata[2] == 'FAIL') {
								$records[$i]['refund_type'] = 'FAIL';
							} else if ($responsedata[2] == 'ABORT') {
								$records[$i]['refund_type'] = 'ABORT';
							} else if ($responsedata[2] == 'REFUND') {
								$records[$i]['refund_type'] = 'REFUND';

								$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no' => $responsedata[1]));
								if (!empty($refund_info)) {
									$records[$i]['refund_type'] = 'Credit Note';
								} else {

									$records[$i]['refund_type'] = 'Manual Refund';
								}
							} else if ($responsedata[1] == 'NA') {
								$records[$i]['refund_type'] = 'No Records Found at SBI END.';
							}

							$i++;
						}
					}
					$data['reg_num_res'] = $records;
				} else if ($searchBy == 'receipt_no') {
					//$this->db->DISTINCT('a. receipt_no');
					$this->db->join('member_registration b', 'b.regnumber = a.member_regnumber', 'LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.receipt_no' => $SearchVal));
					if (!empty($reg_num_res)) {	//print_r($reg_num_res);
						$i = 0;
						foreach ($reg_num_res as $reg_num_res) {
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password

							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no'] = $reg_num_res['receipt_no'];
							$records[$i]['transaction_no'] = $reg_num_res['transaction_no'];
							$records[$i]['namesub'] = $reg_num_res['namesub'] . $reg_num_res['firstname'] . $reg_num_res['lastname'];
							$records[$i]['password'] = $mem_pass;
							$records[$i]['description'] = $reg_num_res['description'];
							$records[$i]['status'] = $reg_num_res['status'];
							$records[$i]['email'] = $reg_num_res['email'];
							$records[$i]['mobile'] = $reg_num_res['mobile'];
							$records[$i]['date'] = $reg_num_res['date'];

							$status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
							// Call to SBI for status 
							//print_r($reg_num_res['receipt_no']); echo '<br>';
							$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
							if ($responsedata[2] == 'SUCCESS') {
								$records[$i]['refund_type'] = 'Success';
							} else if ($responsedata[2] == 'FAIL') {
								$records[$i]['refund_type'] = 'FAIL';
							} else if ($responsedata[2] == 'ABORT') {
								$records[$i]['refund_type'] = 'ABORT';
							} else if ($responsedata[2] == 'REFUND') {
								$records[$i]['refund_type'] = 'REFUND';

								$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no' => $responsedata[1]));
								//echo $this->db->last_query(); die;
								if (!empty($refund_info)) {
									$records[$i]['refund_type'] = 'Credit Note';
								} else {

									$records[$i]['refund_type'] = 'Manual Refund';
								}
							} else if ($responsedata[1] == 'NA') {
								$records[$i]['refund_type'] = 'No Records Found at SBI END.';
							}

							$i++;
						}
					}
					$data['reg_num_res'] = $records;
				} else if ($searchBy == 'regnumber') {
					$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile, a.date, b.namesub, b.firstname, b.lastname';
					$this->db->join('member_registration b', 'b.regnumber = a.member_regnumber', 'LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.member_regnumber' => $SearchVal), $select);
					//echo $this->db->last_query();
					if (!empty($reg_num_res)) {
						$i = 0;
						foreach ($reg_num_res as $reg_num_res) {
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password

							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no'] = $reg_num_res['receipt_no'];
							$records[$i]['transaction_no'] = $reg_num_res['transaction_no'];
							$records[$i]['namesub'] = $reg_num_res['namesub'] . $reg_num_res['firstname'] . $reg_num_res['lastname'];
							$records[$i]['password'] = $mem_pass;
							$records[$i]['description'] = $reg_num_res['description'];
							$records[$i]['status'] = $reg_num_res['status'];
							$records[$i]['email'] = $reg_num_res['email'];
							$records[$i]['mobile'] = $reg_num_res['mobile'];
							$records[$i]['date'] = $reg_num_res['date'];

							$status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];

							// Call to SBI for status 

							$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
							if ($responsedata[2] == 'SUCCESS') {
								$records[$i]['refund_type'] = 'Success';
							} else if ($responsedata[2] == 'FAIL') {
								$records[$i]['refund_type'] = 'FAIL';
							} else if ($responsedata[2] == 'ABORT') {
								$records[$i]['refund_type'] = 'ABORT';
							} else if ($responsedata[2] == 'REFUND') {
								$records[$i]['refund_type'] = 'REFUND';

								$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no' => $responsedata[1]));
								//echo $this->db->last_query(); die;
								if (!empty($refund_info)) {
									$records[$i]['refund_type'] = 'Credit Note';
								} else {

									$records[$i]['refund_type'] = 'Manual Refund';
								}
							} else if ($responsedata[1] == 'NA') {
								$records[$i]['refund_type'] = 'No Records Found at SBI END.';
							}

							$i++;
						}
					}
					$data['reg_num_res'] = $records;
				} else if ($searchBy == 'email') {
					$reg_num_data = $this->master_model->getRecords("member_registration b", array('email' => $SearchVal));
					//echo $this->db->last_query(); die;
					if (!empty($reg_num_data)) {
						foreach ($reg_num_data as $reg_res) {
							$regnumber = $reg_res['regnumber'];
							$email = $reg_res['email'];
							$reg_type = $reg_res['registrationtype'];
							if ($reg_type != 'NM') {
								if ($regnumber == '') {

									$this->db->where('b.email', $email);
									$reg_num_res =  $this->master_model->getRecords("member_registration b", '', 'b.regnumber,b.email, b.namesub, b.firstname, b.lastname, b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');
									//echo $this->db->last_query(); die;
									if ($reg_num_res == '') {
										$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
										$this->db->join('member_registration b', 'b.regid=a.member_regnumber', 'INNER');
										$this->db->order_by('a.date', "desc");
										$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email' => $SearchVal), $select);
									}

									//echo $this->db->last_query();


								} else {
									$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b', 'b.regnumber = a.member_regnumber', 'LEFT');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email' => $SearchVal), $select);

									//echo $this->db->last_query(); 
								}
							} else if ($reg_type == 'NM') {
								if ($regnumber == '') {

									$this->db->where('b.email', $email);
									$reg_num_res =  $this->master_model->getRecords("member_registration b", '', 'b.regnumber,b.email, b.namesub, b.firstname, b.lastname, b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS description', '');
									//echo $this->db->last_query(); die;
									if ($reg_num_res == '') {
										$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
										$this->db->join('member_registration b', 'b.regid=a.member_regnumber', 'INNER');
										$this->db->order_by('a.date', "desc");
										$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email' => $SearchVal), $select);
									}

									//echo $this->db->last_query();


								} else {
									$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b', 'b.regnumber = a.member_regnumber', 'LEFT');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email' => $SearchVal), $select);

									//echo $this->db->last_query(); 
								}
							}
						}
					}
					if (!empty($reg_num_res)) {
						$i = 0;
						foreach ($reg_num_res as $reg_num_res) {
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password

							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no'] = $reg_num_res['receipt_no'];
							$records[$i]['transaction_no'] = $reg_num_res['transaction_no'];
							$records[$i]['namesub'] = $reg_num_res['namesub'] . $reg_num_res['firstname'] . $reg_num_res['lastname'];
							$records[$i]['password'] = $mem_pass;
							$records[$i]['description'] = $reg_num_res['description'];
							$records[$i]['status'] = $reg_num_res['status'];
							$records[$i]['email'] = $reg_num_res['email'];
							$records[$i]['mobile'] = $reg_num_res['mobile'];
							$records[$i]['date'] = $reg_num_res['date'];

							$status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
							// Call to SBI for status 

							$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
							if ($responsedata[2] == 'SUCCESS') {
								$records[$i]['refund_type'] = 'Success';
							} else if ($responsedata[2] == 'FAIL') {
								$records[$i]['refund_type'] = 'FAIL';
							} else if ($responsedata[2] == 'ABORT') {
								$records[$i]['refund_type'] = 'ABORT';
							} else if ($responsedata[2] == 'REFUND') {
								$records[$i]['refund_type'] = 'REFUND';

								$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no' => $responsedata[1]));
								//echo $this->db->last_query(); die;
								if (!empty($refund_info)) {
									$records[$i]['refund_type'] = 'Credit Note';
								} else {

									$records[$i]['refund_type'] = 'Manual Refund';
								}
							} else if ($responsedata[1] == 'NA') {
								$records[$i]['refund_type'] = 'No Records Found at SBI END.';
							}

							$i++;
						}
					}
					$data['reg_num_res'] = $records;
				} else if ($searchBy == 'mobile') {
					$reg_num_data =  $this->master_model->getRecords("member_registration b", array('b.mobile' => $SearchVal));


					if (!empty($reg_num_data)) {
						foreach ($reg_num_data as $reg_res) {
							$regnumber = $reg_res['regnumber'];
							if ($regnumber == '') {

								$this->db->where('b.mobile', $SearchVal);
								$reg_num_res =  $this->master_model->getRecords("member_registration b", '', 'b.regnumber, b.namesub, b.firstname, b.lastname, b.email , b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');

								if ($reg_num_res == '') {
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile ,  b.namesub, b.firstname, b.lastname, a.date ';
									$this->db->join('member_registration b', 'b.regid=a.member_regnumber', 'INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile' => $SearchVal), $select);
								}
							} else {

								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , b.namesub, b.firstname, b.lastname, a.date ';
								$this->db->join('member_registration b', 'b.regnumber = a.member_regnumber', 'INNER');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile' => $SearchVal), $select);
							}
						}
					}
					if (!empty($reg_num_res)) {
						$i = 0;
						foreach ($reg_num_res as $reg_num_res) {
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password

							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no'] = $reg_num_res['receipt_no'];
							$records[$i]['transaction_no'] = $reg_num_res['transaction_no'];
							$records[$i]['namesub'] = $reg_num_res['namesub'] . $reg_num_res['firstname'] . $reg_num_res['lastname'];
							$records[$i]['password'] = $mem_pass;
							$records[$i]['description'] = $reg_num_res['description'];
							$records[$i]['status'] = $reg_num_res['status'];
							$records[$i]['email'] = $reg_num_res['email'];
							$records[$i]['mobile'] = $reg_num_res['mobile'];
							$records[$i]['date'] = $reg_num_res['date'];

							$status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
							// Call to SBI for status 

							$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
							if ($responsedata[2] == 'SUCCESS') {
								$records[$i]['refund_type'] = 'Success';
							} else if ($responsedata[2] == 'FAIL') {
								$records[$i]['refund_type'] = 'FAIL';
							} else if ($responsedata[2] == 'ABORT') {
								$records[$i]['refund_type'] = 'ABORT';
							} else if ($responsedata[2] == 'REFUND') {
								$records[$i]['refund_type'] = 'REFUND';

								$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no' => $responsedata[1]));
								//echo $this->db->last_query(); die;
								if (!empty($refund_info)) {
									$records[$i]['refund_type'] = 'Credit Note';
								} else {

									$records[$i]['refund_type'] = 'Manual Refund';
								}
							} else if ($responsedata[1] == 'NA') {
								$records[$i]['refund_type'] = 'No Records Found at SBI END.';
							}

							$i++;
						}
					}
					$data['reg_num_res'] = $records;
				}
			}
		}
		$this->load->view('refund_details/member_details', $data);
	}

	public function receipt()
	{
		$payment = array();
		//$last = $this->uri->total_segments();
		//$tr_no = base64_decode($this->uri->segment($last));

		$tr_no = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));

		if ($tr_no) {
			$selectExam = 'regid,a.regnumber,namesub,firstname,middlename,lastname,usrpassword,email,address1,address2,address3,address4,district,city,state,pincode,associatedinstitute,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.transaction_no,b.date,b.receipt_no,b.description,b.auth_code,b.transaction_details,c.exam_code,c.exam_period,c.exam_mode,c.exam_fee,c.exam_medium,c.exam_center_code,c.elected_sub_code,c.place_of_work,c.state_place_of_work,c.pin_code_place_of_work,d.state_name, c.examination_date, b.pg_other_details';

			$this->db->join('member_registration a', 'c.regnumber=a.regnumber', 'LEFT');
			$this->db->join('state_master d', 'd.state_code=a.state', 'LEFT');
			$this->db->join('payment_transaction b', 'b.member_regnumber=a.regnumber AND c.id=b.ref_id', 'LEFT');
			$this->db->where('transaction_no', $tr_no);
			$this->db->where('a.regnumber', $member_no);

			$payment_res = $this->master_model->getRecords("member_exam c", '', $selectExam);
			//echo $this->db->last_query();	
			if (count($payment_res)) {
				$ex_medium_desc = '';
				//$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code'=>$payment_res[0]['exam_code'],'exam_period'=>$payment_res[0]['exam_period'],'medium_code'=>$payment_res[0]['exam_medium']), 'medium_description');
				$ex_medium = $this->master_model->getRecords("medium_master", array('medium_code' => $payment_res[0]['exam_medium']), 'medium_description');
				if (count($ex_medium)) {
					$ex_medium_desc = $ex_medium[0]['medium_description'];
				}

				$center_name = '';
				//$center = $this->master_model->getRecords("center_master", array('exam_name'=>$payment_res[0]['exam_code'],'exam_period'=>$payment_res[0]['exam_period'],'center_code'=>$payment_res[0]['exam_center_code']), 'center_name');
				$center = $this->master_model->getRecords("center_master", array('center_code' => $payment_res[0]['exam_center_code']), 'center_name');
				if (count($center)) {
					$center_name = $center[0]['center_name'];
				}

				$exam_month = '';
				/*$center = $this->master_model->getRecords("misc_master", array('exam_code'=>$payment_res[0]['exam_code'],'exam_period'=>$payment_res[0]['exam_period']), 'exam_month');
				if(count($center))
				{
					$exam_month = $center[0]['exam_month'];
				}*/

				$pg_other_details = $payment_res[0]['pg_other_details'];
				$other_details_arr = explode('^', $pg_other_details);
				$last6chars = substr($other_details_arr[3], -6); //exam year and month like 201801
				$exam_month = $last6chars;

				$inst_name = '--';
				$institute = $this->master_model->getRecords("institution_master", array('institude_id' => $payment_res[0]['associatedinstitute']), 'name inst_name');
				if (count($institute)) {
					$inst_name = $institute[0]['inst_name'];
				}

				// SPECIAL EXAM
				/*if($payment_res[0]['examination_date'] !='' && $payment_res[0]['examination_date'] != '0000-00-00')
				{
					
				}*/

				$payment_res[0]['exam_medium'] = $ex_medium_desc;
				$payment_res[0]['center_name'] = $center_name;
				$payment_res[0]['exam_month'] = $exam_month;
				$payment_res[0]['inst_name'] = $inst_name;

				$payment = $payment_res[0];
			}
		}
		/*echo "<pre>";
		print_r($payment);*/
		$data['payment'] = $payment;
		$this->load->view('admin/payment_receipt', $data);
	}
	//Tejasvi
	public function mem_receipt($txn_id, $mem_id)
	{
		$txn_id = base64_decode($txn_id);
		$mem_id = base64_decode($mem_id);

		// get payment transaction details -
		$select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status, gateway, UTR_no, bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email';
		$this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
		$this->db->where('bulk_payment_transaction.id = "' . $txn_id . '"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');

		$txn_result = $res->result_array();

		$data['txn_details'] = $txn_result[0];

		// get list of all members for this payment transaction -
		$select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.lastname,email,member_exam.exam_fee, member_exam.exam_center_code, member_exam.exam_medium';
		$this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
		$this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
		$this->db->where('member_registration.isactive = "1"');
		$this->db->where('bulk_member_payment_transaction.ptid = ' . $txn_id . ' AND member_registration.regid = ' . $mem_id);
		$res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');

		$mem_result = $res->result_array();
		$memresult = $mem_result[0];

		$memresult['centername'] = '';
		$memresult['mediumname'] = '';
		if ($memresult) {
			$mediumcode = $memresult['exam_medium'];
			$centercode = $memresult['exam_center_code'];
			$mediumname = $this->master_model->getValue('medium_master', array('medium_code' => $mediumcode), 'medium_description');
			$centername = $this->master_model->getValue('center_master', array('center_code' => $centercode), 'center_name');
			$memresult['centername'] = $centername;
			$memresult['mediumname'] = $mediumname;
		}

		$data['mem_details'] = $memresult;

		$this->load->view('admin/mem_receipt', $data);
	}
	public function id_receipt()
	{
		$icardWhr = '';
		$payment = array();
		$last = $this->uri->total_segments();
		//$tr_no = base64_decode($this->uri->segment($last));
		$tr_no = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));

		if ($tr_no) {
			$icardWhr .= ' AND b.pay_type = 3';
			$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
			$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
			$this->db->join('state_master d', 'd.state_code=c.state', 'LEFT');
			$this->db->where('transaction_no', $tr_no);
			$this->db->where('c.regnumber', $member_no);
			$payment_res = $this->master_model->getRecords("duplicate_icard a", '', 'regid,c.regnumber,firstname,middlename,lastname,createdon,address1,address2,address3,address4,district,city,state,pincode,email,b.status,b.transaction_no,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no,d.state_name', array('date' => 'DESC'));
			// echo $this->db->last_query();
			if (count($payment_res)) {
				$payment = $payment_res[0];
			}
		}
		/*echo "<pre>";
		print_r($payment);*/
		$data['payment'] = $payment;
		$this->load->view('admin/dup_icard_receipt', $data);
	}

	public function contactclass_receipt()
	{
		$contactclassWhr = '';
		$payment = array();
		$last = $this->uri->total_segments();
		//$tr_no = base64_decode($this->uri->segment($last));
		$tr_no = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));

		if ($tr_no) {
			$contactclassWhr .= ' AND b.pay_type = 11';
			$this->db->join('member_registration c', 'c.regnumber=a.member_no', 'LEFT');
			$this->db->join('payment_transaction b', 'b.ref_id=a.contact_classes_id AND b.member_regnumber=a.member_no', 'LEFT');
			$this->db->join('state_master d', 'd.state_code=c.state', 'LEFT');
			$this->db->where('transaction_no', $tr_no);
			$this->db->where('c.regnumber', $member_no);
			$payment_res = $this->master_model->getRecords("contact_classes_registration a", '', 'regid,c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,a.createdon,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.email,b.status,b.transaction_no,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no,d.state_name', array('date' => 'DESC'));

			//print_r($payment_res); exit;
			//echo $this->db->last_query();
			if (count($payment_res)) {
				$payment = $payment_res[0];
			}
		}
		/*echo "<pre>";
		print_r($payment);*/
		$data['payment'] = $payment;
		$this->load->view('admin/contact_classes_receipt', $data);
	}

	public function transaction_mail()
	{
		$payment = array();
		//$last = $this->uri->total_segments();
		$tr_no = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));

		if ($tr_no) {
			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_transaction_details'));
			if (count($emailerstr) > 0) {
				$selectExam = 'regid,a.regnumber,namesub,firstname,middlename,lastname,usrpassword,email,address1,address2,address3,address4,district,city,state,pincode,associatedinstitute,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.transaction_no,b.date,b.receipt_no,b.description,b.auth_code,b.transaction_details,c.exam_code,c.exam_period,c.exam_mode,c.exam_fee,c.exam_medium,c.exam_center_code,c.elected_sub_code,c.place_of_work,c.state_place_of_work,c.pin_code_place_of_work,d.state_name, c.examination_date';

				$this->db->join('member_registration a', 'c.regnumber=a.regnumber', 'LEFT');
				$this->db->join('state_master d', 'd.state_code=a.state', 'LEFT');
				$this->db->join('payment_transaction b', 'b.member_regnumber=a.regnumber AND c.id=b.ref_id', 'LEFT');
				$this->db->where('transaction_no', $tr_no);
				$this->db->where('transaction_no!=', ' ');
				$this->db->where('a.isactive', '1');
				$this->db->where('a.regnumber', $member_no);
				$result = $this->master_model->getRecords("member_exam c", '', $selectExam);
				//echo $this->db->last_query();exit;
				if (count($result) > 0) {
					$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

					$address = '';
					$address .= $result[0]['address1'];
					if ($result[0]['address2'] != '') {
						$address .=  ", " . $result[0]['address2'];
					}
					if ($result[0]['address3'] != '' || $result[0]['address4'] != '') {
						$address .=  "<br>";
					}
					if ($result[0]['address3'] != '') {
						$address .=  "," . $result[0]['address3'];
					}
					if ($result[0]['address4'] != '') {
						$address .=  "," . $result[0]['address4'];
					}

					$address .=  ",<br>" . $result[0]['district'] . ", " . $result[0]['city'];
					$address .=  ",<br>" . $result[0]['state_name'] . ", " . $result[0]['pincode'];

					if ($result[0]['exam_mode'] == 'ON') {
						$exam_mode = "Online";
					} else {
						$exam_mode = "Offline";
					}

					if ($result[0]['status'] == 0)
						$status = "Failure";
					else if ($result[0]['status'] == 1)
						$status = "Success";
					else if ($result[0]['status'] == 2)
						$status = "Pending";
					else
						$status = "--";

					$ex_medium_desc = '';
					$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code' => $result[0]['exam_code'], 'exam_period' => $result[0]['exam_period']), 'medium_description');
					if (count($ex_medium)) {
						$ex_medium_desc = $ex_medium[0]['medium_description'];
					}

					$center_name = '';
					$center = $this->master_model->getRecords("center_master", array('exam_name' => $result[0]['exam_code'], 'exam_period' => $result[0]['exam_period'], 'center_code' => $result[0]['exam_center_code']), 'center_name');
					if (count($center)) {
						$center_name = $center[0]['center_name'];
					}

					$exam_month = '';
					$center = $this->master_model->getRecords("misc_master", array('exam_code' => $result[0]['exam_code'], 'exam_period' => $result[0]['exam_period']), 'exam_month');
					if (count($center)) {
						$exam_month = $center[0]['exam_month'];
					}

					$inst_name = '--';
					$institute = $this->master_model->getRecords("institution_master", array('institude_id' => $result[0]['associatedinstitute']), 'name inst_name');
					if (count($institute)) {
						$inst_name = $institute[0]['inst_name'];
					}

					$result[0]['exam_medium'] = $ex_medium_desc;
					$result[0]['center_name'] = $center_name;
					$result[0]['exam_month'] = $exam_month;
					$result[0]['inst_name'] = $inst_name;

					if ($result[0]['pin_code_place_of_work'])
						$pincode = $result[0]['pin_code_place_of_work'];
					else
						$pincode = '--';


					if ($result[0]['examination_date'] != '' && $result[0]['examination_date'] != '0000-00-00') {
						// SPECIAL EX
						$ex_month = date('F-Y', strtotime($result[0]['examination_date']));
					} else {
						$month = date('Y') . "-" . substr($result[0]['exam_month'], 4) . "-" . date('d');
						$ex_month = date('F', strtotime($month)) . "-" . substr($result[0]['exam_month'], 0, -2);
					}


					$newstring1 = str_replace("#name#", "" . strtoupper($userfinalstrname) . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#regnumber#", "" . $result[0]['regnumber'] . "", $newstring1);
					$newstring3 = str_replace("#candidate_name#", "" . strtoupper($userfinalstrname) . "", $newstring2);
					$newstring4 = str_replace("#exam_name#", "" . $result[0]['description'] . "", $newstring3);
					$newstring5 = str_replace("#exam_month#", "" . $ex_month . "", $newstring4);
					$newstring6 = str_replace("#amount#", "" . $result[0]['exam_fee'] . "", $newstring5);
					$newstring7 = str_replace("#address#", "" . $address . "", $newstring6);
					$newstring8 = str_replace("#email#", "" . $result[0]['email'] . "", $newstring7);
					$newstring9 = str_replace("#institute#", "" . strtoupper($result[0]['inst_name']) . "", $newstring8);
					$newstring10 = str_replace("#medium#", "" . strtoupper($result[0]['exam_medium']) . "", $newstring9);
					$newstring11 = str_replace("#center_name#", "" . strtoupper($result[0]['center_name']) . "", $newstring10);
					$newstring12 = str_replace("#center_code#", "" . strtoupper($result[0]['exam_center_code']) . "", $newstring11);
					$newstring13 = str_replace("#exam_mode#", "" . strtoupper($exam_mode) . "", $newstring12);
					$newstring14 = str_replace("#place_of_work#", "" . strtoupper($result[0]['place_of_work']) . "", $newstring13);
					$newstring15 = str_replace("#state_place_of_work#", "" . strtoupper($result[0]['state_place_of_work']) . "", $newstring14);
					$newstring16 = str_replace("#pincode_place_of_work#", "" . $pincode . "", $newstring15);
					$newstring17 = str_replace("#transaction_no#", "" . strtoupper($result[0]['transaction_no']) . "", $newstring16);
					$newstring18 = str_replace("#status#", "" . strtoupper($status) . "", $newstring17);
					$final_str = str_replace("#date#", "" . date('d-m-Y h:i:s A', strtotime($result[0]['date'])) . "", $newstring18);


					$info_arr = array(
						'to' => $result[0]['email'],
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $final_str
					);

					//print_r($info_arr);exit;

					if ($this->Emailsending->mailsend($info_arr)) {
						$this->session->set_flashdata('success', 'Email sent successfully !!');
						redirect(base_url() . 'admin/Report/query');
					} else {
						$this->session->set_flashdata('error', 'Error while sending email !!');
						redirect(base_url() . 'admin/Report/query');
					}
				} else {
					$this->session->set_flashdata('error', 'No Such Data Available...');
					redirect(base_url() . 'admin/Report/query');
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong...');
				redirect(base_url() . 'admin/Report/query');
			}
		}
	}
	// bulk member mail sending (Tejasvi)
	public function transaction_mail_bulkmem()
	{
		//exit;
		$payment = array();
		$last = $this->uri->total_segments();
		//$tr_no = base64_decode($this->uri->segment($last));
		$utr_no = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));
		if ($utr_no) {
			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_transaction_details'));
			if (count($emailerstr) > 0) {
				$selectExam = 'regid,a.regnumber,namesub,firstname,middlename,lastname,usrpassword,email,address1,address2,address3,address4,district,city,state,pincode,associatedinstitute,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.transaction_no,b.date,b.receipt_no,b.description,b.auth_code,b.transaction_details,c.exam_code,c.exam_period,c.exam_mode,c.exam_fee,c.exam_medium,c.exam_center_code,c.elected_sub_code,c.place_of_work,c.state_place_of_work,c.pin_code_place_of_work,d.state_name,c.examination_date,b.exam_code as exm_cd';

				//$this->db->from('member_registration');
				//$this->db->join('member_exam','member_exam.regnumber = member_registration.regnumber');
				$this->db->join('member_registration a', 'a.regnumber = c.regnumber');
				$this->db->join('state_master d', 'd.state_code=a.state', 'LEFT');
				$this->db->join('bulk_member_payment_transaction p', 'p.memexamid= c.id');
				$this->db->join('bulk_payment_transaction b', 'b.id=p.ptid');
				$this->db->where('a.regnumber', $member_no);
				$this->db->where('UTR_no', $utr_no);
				$result = $this->master_model->getRecords("member_exam c", '', $selectExam);

				//$this->db->join('member_registration a','c.regnumber=a.regnumber','LEFT');

				//$this->db->join('payment_transaction b','b.member_regnumber=a.regnumber AND c.id=b.ref_id','LEFT');
				//$this->db->join('payment_transaction b','b.member_regnumber=a.regnumber AND c.id=b.ref_id','LEFT');

				if (count($result) > 0) {
					$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);



					$address = '';
					$address .= $result[0]['address1'];
					if ($result[0]['address2'] != '') {
						$address .=  ", " . $result[0]['address2'];
					}
					if ($result[0]['address3'] != '' || $result[0]['address4'] != '') {
						$address .=  "<br>";
					}
					if ($result[0]['address3'] != '') {
						$address .=  "," . $result[0]['address3'];
					}
					if ($result[0]['address4'] != '') {
						$address .=  "," . $result[0]['address4'];
					}

					$address .=  ",<br>" . $result[0]['district'] . ", " . $result[0]['city'];
					$address .=  ",<br>" . $result[0]['state_name'] . ", " . $result[0]['pincode'];

					if ($result[0]['exam_mode'] == 'ON') {
						$exam_mode = "Online";
					} else {
						$exam_mode = "Offline";
					}

					if ($result[0]['status'] == 0)
						$status = "Failure";
					else if ($result[0]['status'] == 1)
						$status = "Success";
					else if ($result[0]['status'] == 2)
						$status = "Pending";
					else
						$status = "--";

					//getting exm_name by exam code
					$exam_name_desc = '';
					$exam_name = $this->master_model->getRecords('exam_master', array('exam_code' => $result[0]['exm_cd']), 'description');
					if (count($exam_name)) {
						$exam_name_desc = $exam_name[0]['description'];
					}

					$ex_medium_desc = '';
					$ex_medium = $this->master_model->getRecords("medium_master", array('exam_code' => $result[0]['exam_code'], 'exam_period' => $result[0]['exam_period']), 'medium_description');
					if (count($ex_medium)) {
						$ex_medium_desc = $ex_medium[0]['medium_description'];
					}

					$center_name = '';
					$center = $this->master_model->getRecords("center_master", array('exam_name' => $result[0]['exam_code'], 'exam_period' => $result[0]['exam_period'], 'center_code' => $result[0]['exam_center_code']), 'center_name');
					if (count($center)) {
						$center_name = $center[0]['center_name'];
					}

					$exam_month = '';
					$center = $this->master_model->getRecords("misc_master", array('exam_code' => $result[0]['exam_code'], 'exam_period' => $result[0]['exam_period']), 'exam_month');
					if (count($center)) {
						$exam_month = $center[0]['exam_month'];
					}

					$inst_name = '--';
					$institute = $this->master_model->getRecords("institution_master", array('institude_id' => $result[0]['associatedinstitute']), 'name inst_name');
					if (count($institute)) {
						$inst_name = $institute[0]['inst_name'];
					}

					$result[0]['exam_medium'] = $ex_medium_desc;
					$result[0]['center_name'] = $center_name;
					$result[0]['exam_month'] = $exam_month;
					$result[0]['inst_name'] = $inst_name;

					if ($result[0]['pin_code_place_of_work'])
						$pincode = $result[0]['pin_code_place_of_work'];
					else
						$pincode = '--';


					if ($result[0]['examination_date'] != '' && $result[0]['examination_date'] != '0000-00-00') {
						// SPECIAL EX
						$ex_month = date('F-Y', strtotime($result[0]['examination_date']));
					} else {
						$month = date('Y') . "-" . substr($result[0]['exam_month'], 4) . "-" . date('d');
						$ex_month = date('F', strtotime($month)) . "-" . substr($result[0]['exam_month'], 0, -2);
					}


					$newstring1 = str_replace("#name#", "" . strtoupper($userfinalstrname) . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#regnumber#", "" . $result[0]['regnumber'] . "", $newstring1);
					$newstring3 = str_replace("#candidate_name#", "" . strtoupper($userfinalstrname) . "", $newstring2);
					$newstring4 = str_replace("#exam_name#", "" . $exam_name_desc . "", $newstring3);
					$newstring5 = str_replace("#exam_month#", "" . $ex_month . "", $newstring4);
					$newstring6 = str_replace("#amount#", "" . $result[0]['exam_fee'] . "", $newstring5);
					$newstring7 = str_replace("#address#", "" . $address . "", $newstring6);
					$newstring8 = str_replace("#email#", "" . $result[0]['email'] . "", $newstring7);
					$newstring9 = str_replace("#institute#", "" . strtoupper($result[0]['inst_name']) . "", $newstring8);
					$newstring10 = str_replace("#medium#", "" . strtoupper($result[0]['exam_medium']) . "", $newstring9);
					$newstring11 = str_replace("#center_name#", "" . strtoupper($result[0]['center_name']) . "", $newstring10);
					$newstring12 = str_replace("#center_code#", "" . strtoupper($result[0]['exam_center_code']) . "", $newstring11);
					$newstring13 = str_replace("#exam_mode#", "" . strtoupper($exam_mode) . "", $newstring12);
					$newstring14 = str_replace("#place_of_work#", "" . strtoupper($result[0]['place_of_work']) . "", $newstring13);
					$newstring15 = str_replace("#state_place_of_work#", "" . strtoupper($result[0]['state_place_of_work']) . "", $newstring14);
					$newstring16 = str_replace("#pincode_place_of_work#", "" . $pincode . "", $newstring15);
					$newstring17 = str_replace("#transaction_no#", "" . strtoupper($result[0]['transaction_no']) . "", $newstring16);
					$newstring18 = str_replace("#status#", "" . strtoupper($status) . "", $newstring17);
					$final_str = str_replace("#date#", "" . date('d-m-Y h:i:s A', strtotime($result[0]['date'])) . "", $newstring18);


					$info_arr = array(
						'to' => $result[0]['email'],
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $final_str
					);

					if ($this->Emailsending->mailsend($info_arr)) {
						$this->session->set_flashdata('success', 'Email sent successfully !!');
						redirect(base_url() . 'admin/Report/query');
					} else {
						$this->session->set_flashdata('error', 'Error while sending email !!');
						redirect(base_url() . 'admin/Report/query');
					}
				} else {
					$this->session->set_flashdata('error', 'No Such Data Available...');
					redirect(base_url() . 'admin/Report/query');
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong...');
				redirect(base_url() . 'admin/Report/query');
			}
		}
	}


	public function dup_icard_mail()
	{
		$payment = array();
		//$last = $this->uri->total_segments();
		//$tr_no = base64_decode($this->uri->segment($last));
		$tr_no  = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));
		$icardWhr = '';
		if ($tr_no) {
			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'duplicate_icard_resend_mail'));
			if (count($emailerstr) > 0) {
				//Query to get user details
				$icardWhr .= ' AND b.pay_type = 3';
				$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
				$this->db->join('payment_transaction b', 'b.ref_id=a.did AND b.member_regnumber=a.regnumber', 'LEFT');
				$this->db->join('state_master d', 'd.state_code=c.state', 'LEFT');
				$this->db->where('transaction_no', $tr_no);
				$this->db->where('c.regnumber', $member_no);
				$user_info = $this->master_model->getRecords("duplicate_icard a", '', 'regid,c.regnumber,namesub,firstname,middlename,lastname,createdon,address1,address2,address3,address4,district,city,state,pincode,email,b.status,b.transaction_no,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no,d.state_name', array('date' => 'DESC'));

				$username = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
				$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

				if ($user_info[0]['status'] == 0)
					$status = "Failure";
				else if ($user_info[0]['status'] == 1)
					$status = "Success";
				else if ($user_info[0]['status'] == 2)
					$status = "Pending";
				else
					$status = "--";

				$newstring1 = str_replace("#name#", "" . strtoupper($userfinalstrname) . "", $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $newstring1);
				$newstring3 = str_replace("#candidate_name#", "" . strtoupper($userfinalstrname) . "", $newstring2);
				$newstring4 = str_replace("#amount#", "" . $user_info[0]['amount'] . "", $newstring3);
				$newstring5 = str_replace("#email#", "" . $user_info[0]['email'] . "", $newstring4);
				$newstring6 = str_replace("#transaction_no#", "" . strtoupper($user_info[0]['transaction_no']) . "", $newstring5);
				$newstring7 = str_replace("#status#", "" . strtoupper($status) . "", $newstring6);
				$final_str = str_replace("#date#", "" . date('d-m-Y h:i:s A', strtotime($user_info[0]['date'])) . "", $newstring7);

				$info_arr = array('to' => $user_info[0]['email'], 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);
				if ($this->Emailsending->mailsend($info_arr)) {
					$this->session->set_flashdata('success', 'Email sent successfully !!');
					redirect(base_url() . 'admin/Report/query');
				} else {
					//echo 'Error while sending email';
					$this->session->set_flashdata('error', 'Error while sending email !!');
					redirect(base_url() . 'admin/Report/query');
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong...');
				redirect(base_url() . 'admin/Report/query');
			}
		} else {
			redirect(base_url() . 'admin/Report/query');
		}
	}

	public function contact_classes_mail()
	{
		$payment = array();
		//$last = $this->uri->total_segments();
		//$tr_no = base64_decode($this->uri->segment($last));
		$tr_no  = base64_decode($this->uri->segment(4));
		$member_no = base64_decode($this->uri->segment(5));
		$contactclassWhr = '';
		if ($tr_no) {
			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'contact_classes_resend_mail'));
			if (count($emailerstr) > 0) {
				//Query to get user details
				$contactclassWhr .= ' AND b.pay_type = 11';
				$this->db->join('member_registration c', 'c.regnumber=a.member_no', 'LEFT');
				$this->db->join('payment_transaction b', 'b.ref_id=a.contact_classes_id AND b.member_regnumber=a.member_no', 'LEFT');
				$this->db->join('state_master d', 'd.state_code=c.state', 'LEFT');
				$this->db->where('transaction_no', $tr_no);
				$this->db->where('c.regnumber', $member_no);
				$user_info = $this->master_model->getRecords("contact_classes_registration a", '', 'regid,c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,a.createdon,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.email,b.status,b.transaction_no,b.transaction_details,b.auth_code,b.amount,DATE_FORMAT(date,"%d-%m-%Y") date,b.receipt_no,d.state_name', array('date' => 'DESC'));

				//print_r($user_info); exit;		
				$username = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
				$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

				if ($user_info[0]['status'] == 0)
					$status = "Failure";
				else if ($user_info[0]['status'] == 1)
					$status = "Success";
				else if ($user_info[0]['status'] == 2)
					$status = "Pending";
				else
					$status = "--";

				$newstring1 = str_replace("#name#", "" . strtoupper($userfinalstrname) . "", $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $newstring1);
				$newstring3 = str_replace("#candidate_name#", "" . strtoupper($userfinalstrname) . "", $newstring2);
				$newstring4 = str_replace("#amount#", "" . $user_info[0]['amount'] . "", $newstring3);
				$newstring5 = str_replace("#email#", "" . $user_info[0]['email'] . "", $newstring4);
				$newstring6 = str_replace("#transaction_no#", "" . strtoupper($user_info[0]['transaction_no']) . "", $newstring5);
				$newstring7 = str_replace("#status#", "" . strtoupper($status) . "", $newstring6);
				$final_str = str_replace("#date#", "" . date('d-m-Y h:i:s A', strtotime($user_info[0]['date'])) . "", $newstring7);

				$info_arr = array('to' => $user_info[0]['email'], 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);
				if ($this->Emailsending->mailsend($info_arr)) {
					$this->session->set_flashdata('success', 'Email sent successfully !!');
					redirect(base_url() . 'admin/Report/query');
				} else {
					//echo 'Error while sending email';
					$this->session->set_flashdata('error', 'Error while sending email !!');
					redirect(base_url() . 'admin/Report/query');
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong...');
				redirect(base_url() . 'admin/Report/query');
			}
		} else {
			redirect(base_url() . 'admin/Report/query');
		}
	}


	public function queryReport1()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$data['result'] = array();
		$per_page = 10;
		$last = $this->uri->total_segments();
		$start = 0;
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$searchText = '';
		$searchBy = '';

		if ($page != 0) {
			$start = $page - 1;
		}

		$where = " `isactive` = '1' AND `isdeleted` = 0 AND `pay_type` = 1 AND `b`.`status` = 1";

		// Set session for the Search crieteria 
		if ($this->session->userdata('searchBy') == '') {
			if (isset($_POST['searchBy']) && $_POST['searchBy'] != '')
				$searchBy = trim($_POST['searchBy']);
			else
				$searchBy = '';
			$this->session->set_userdata('searchBy', $searchBy);
		} else {
			if (isset($_POST['searchBy']) && $_POST['searchBy'] != $this->session->userdata('searchBy')) {
				$searchBy = trim($_POST['searchBy']);
				$this->session->set_userdata('searchBy', $searchBy);
			}
		}
		$searchBy = $this->session->userdata('searchBy');

		if ($this->session->userdata('searchText') == '') {
			if (isset($_POST['searchText']) && $_POST['searchText'] != '')
				$searchText = trim($_POST['searchText']);
			else
				$searchText = '';
			$this->session->set_userdata('searchText', $searchText);
		} else {
			if (isset($_POST['searchText']) && $_POST['searchText'] != $this->session->userdata('searchText')) {
				$searchText = trim($_POST['searchText']);
				$this->session->set_userdata('searchText', $searchText);
			}
		}
		$searchText = $this->session->userdata('searchText');


		if ($this->session->userdata('searchBy') != '' && $this->session->userdata('searchText') != '') {
			$searcharr = array();
			$searchBy = $this->session->userdata('searchBy');
			$search_val = $this->session->userdata('searchText');
			if (strpos($search_val, ',') !== false) {
				$searcharr = explode(',', $search_val);
			}
			if (count($searcharr))	//For Comma separated values
			{
				//echo $this->session->userdata('searchBy');exit;
				if ($this->session->userdata('searchBy') == 'name') {
					$where .= " AND ( ";
					for ($i = 0; $i < count($searcharr); $i++) {
						if (strpos($searcharr[$i], ' ') !== false) {
							$searcharr1 = explode(' ', $searcharr[$i]);
							if (count($searcharr1)) {
								for ($j = 0; $j < count($searcharr1); $j++) {
									$where .= " `firstname` LIKE '%" . $searcharr1[$j] . "%' OR `middlename` LIKE  '%" . $searcharr1[$j] . "%' OR `lastname` LIKE  '%" . $searcharr1[$j] . "%' OR";
								}
							} else {
								$where .= " `firstname` LIKE '%" . $searcharr[$i] . "%' OR `middlename` LIKE  '%" . $searcharr[$i] . "%' OR `lastname` LIKE  '%" . $searcharr[$i] . "%' OR";
							}
						} else {
							$where .= " `firstname` LIKE '%" . $searcharr[$i] . "%' OR `middlename` LIKE  '%" . $searcharr[$i] . "%' OR `lastname` LIKE  '%" . $searcharr[$i] . "%' OR";
						}
					}
					$where  = rtrim($where, 'OR');
					$where .= ")";
				} else {
					$where .= " AND ( ";
					for ($i = 0; $i < count($searcharr); $i++) {
						$where .= " " . $searchBy . " = '" . $searcharr[$i] . "' OR";
					}
					$where  = rtrim($where, 'OR');
					$where .= ")";
				}
			} else		//For Single value
			{
				if ($this->session->userdata('searchBy') == 'name') {
					$where .= " AND ( ";
					if (strpos($search_val, ' ') !== false) {
						$searcharr1 = explode(' ', $search_val);
						if (count($searcharr1)) {
							for ($j = 0; $j < count($searcharr1); $j++) {
								$where .= " `firstname` LIKE '%" . $searcharr1[$j] . "%' OR `middlename` LIKE  '%" . $searcharr1[$j] . "%' OR `lastname` LIKE  '%" . $searcharr1[$j] . "%' OR";
							}
						} else {
							$where .= " `firstname` LIKE '%" . $search_val . "%' OR `middlename` LIKE  '%" . $search_val . "%' OR `lastname` LIKE  '%" . $search_val . "%' OR";
						}
					} else {
						$where .= " `firstname` LIKE '%" . $search_val . "%' OR `middlename` LIKE  '%" . $search_val . "%' OR `lastname` LIKE  '%" . $search_val . "%' OR";
					}
					//$where .= " AND ( `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%')";
					$where  = rtrim($where, 'OR');
					$where .= ")";
				} else {
					$where .= " AND " . $searchBy . " = '" . $search_val . "'";
				}
			}
		}

		$this->db->join('payment_transaction b', 'b.ref_id=a.regid AND b.member_regnumber=a.regnumber', 'RIGHT');
		$this->db->where($where);
		//$total_row = $this->master_model->getRecordCount("member_registration a",array('isactive'=>'1','isdeleted'=>0,'pay_type'=>1,'b.status'=>1),'regid');
		$total_row = $this->master_model->getRecordCount("member_registration a", '', 'regid');
		$url = base_url() . "admin/Search/success/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);

		$select = 'regid,regnumber,namesub,firstname,middlename,lastname,usrpassword,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.transaction_no,b.date';
		$this->db->join('payment_transaction b', 'b.ref_id=a.regid AND b.member_regnumber=a.regnumber', 'RIGHT');
		$this->db->where($where);
		//$members = $this->master_model->getRecords("member_registration a",array('isactive'=>'1','isdeleted'=>0,'pay_type'=>1,'b.status'=>1), $select, array('regid'=>'ASC'), $start, $per_page);
		$members = $this->master_model->getRecords("member_registration a", "", $select, array('regid' => 'ASC'), $start, $per_page);

		////$data['query'] = $this->db->last_query();

		if (count($members)) {
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			for ($i = 0; $i < count($members); $i++) {
				$decpass = $aes->decrypt(trim($members[$i]['usrpassword']));
				$members[$i]['usrpassword'] = $decpass;
			}
			$data['result'] = $members;
		}

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;

		if (($start + $per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;

		if ($total_row)
			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
		else
			$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

		$data['index'] = $start + 1;

		$this->load->view('admin/query_report', $data);
	}


	public function dashboard()
	{
		$data['result'] = $data['withot_pay'] = array();
		$exam_code = '';
		$exam_period = '';
		$search_for = '';
		$where = '';
		$res_flg = 0;
		$exam = array();

		if (isset($_POST['btnSearch'])) {
			if (isset($_POST['exam_code']) && $_POST['exam_code'] != '') {
				$exam_code = $_POST['exam_code'];
			}
			if (isset($_POST['exam_period']) && $_POST['exam_period'] != '') {
				$exam_period = $_POST['exam_period'];
			}
			if (isset($_POST['search_for']) && $_POST['search_for'] != '') {
				$search_for = $_POST['search_for'];
			}

			$special_ex = 0;
			if ($exam_code) {



				$exam_res = $this->master_model->getRecords('exam_master', array('exam_code' => $exam_code), 'exam_category');

				if (count($exam_res) > 0) {
					$special_ex = $exam_res[0]['exam_category'];
				}
				$exam_date
					= '';
				if ($special_ex == 1) {
					if ($exam_period == 701 || $exam_period == 702 || $exam_period == 703 || $exam_period == 704 || $exam_period == 705  || $exam_period == 720 || $exam_period == 721) {
						$ex_date = $this->master_model->getRecords('special_exam_dates_701_to_705', array('period' => $exam_period));
					} else {
						$ex_date = $this->master_model->getRecords('special_exam_dates', array('period' => $exam_period));
					}
					if (count($ex_date)) {
						$exam_date = $ex_date[0]['examination_date'];
					}
				}
			} else {
				// If selected exam_period is for "Special Exam" 
				if ($exam_period) {

					$exam_date = '';
					if ($exam_period == 701 || $exam_period == 702 || $exam_period == 703 || $exam_period == 704 || $exam_period == 705  || $exam_period == 720 || $exam_period == 721) {
						$ex_date = $this->master_model->getRecords('special_exam_dates_701_to_705', array('period' => $exam_period));
					} else {
						$ex_date = $this->master_model->getRecords('special_exam_dates', array('period' => $exam_period));
					}

					if (count($ex_date)) {
						$exam_date = $ex_date[0]['examination_date'];
					}

					if ($exam_date != '') {
						$select = 'a.register_num,a.exam_code,COUNT(a.splex_id) mem_cnt ,b.description,b.exam_type';
						if ($exam_period != '' && $search_for != '') {
							if ($search_for == "01") {
								$this->db->join('member_registration c', 'c.regnumber=a.register_num', 'LEFT');
								$where = " a.examination_date = '" . $exam_date . "' AND c.registrationtype = 'NM'";
							} else {
								$where = " a.examination_date = '" . $exam_date . "' ";
							}
						}

						if ($where)
							$this->db->where($where);
						$this->db->group_by('a.exam_code');
						$this->db->join('exam_master b', 'b.exam_code=a.exam_code', 'LEFT');

						if ($exam_period == 701 || $exam_period == 702 || $exam_period == 703 || $exam_period == 704 || $exam_period == 705  || $exam_period == 720 || $exam_period == 721) {
							$exam = $this->master_model->getRecords("special_exam_apply_701_to_705 a", "", $select);
						} else {
							$exam = $this->master_model->getRecords("special_exam_apply a", "", $select);
						}
						$res_flg = 1;

						/*echo "0000 <br>";
						echo $this->db->last_query();*/
					}
				}
			}


			if ($special_ex == 1 && $exam_date != '')	// for "Special Exam" 
			{

				$select = 'a.regnumber,a.exam_code,COUNT(a.id) mem_cnt ,b.description,b.exam_type';
				if ($exam_period != '' && $search_for != '') {
					if ($search_for == "01") {
						$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
						$where = " a.examination_date = '" . $exam_date . "' AND a.exam_code = " . $exam_code . " AND c.registrationtype = 'NM' AND a.pay_status = '1'";
					} else {
						$where = " a.examination_date = '" . $exam_date . "' AND a.exam_code = " . $exam_code . " AND a.pay_status = '1' ";
					}
				}

				if ($where)
					$this->db->where($where);
				$this->db->group_by('a.exam_code');
				$this->db->join('exam_master b', 'b.exam_code=a.exam_code', 'LEFT');
				if ($exam_period == 701 || $exam_period == 702 || $exam_period == 703 || $exam_period == 704 || $exam_period == 705  || $exam_period == 720 || $exam_period == 721) {
					$exam = $this->master_model->getRecords("special_exam_apply_701_to_705 a", "", $select);
				} else {
					$exam = $this->master_model->getRecords("member_exam a", "", $select);
				}


				$res_flg = 1;

				/*echo "1111 <br>";
				echo $this->db->last_query();
				exit;*/
			} else	// for "Noraml Exams" 
			{

				if ($res_flg == 0) {

					$select = 'a.regnumber,a.exam_code,a.exam_period,COUNT(a.id) mem_cnt ,b.description,b.exam_type';
					if ($exam_period != '' && $search_for != '') {
						if ($search_for == "01") {

							$where = " a.exam_period = " . $exam_period . " AND registrationtype = 'NM'  AND `pay_status` = '1' AND d.status = 1";
							if ($exam_code != '')
								$where .= " AND  a.exam_code = " . $exam_code . "";
							$this->db->join('payment_transaction d', 'd.ref_id=a.id', 'LEFT');
							$this->db->join('exam_master b', 'b.exam_code=a.exam_code', 'LEFT');
							$this->db->join('member_registration c', 'c.regnumber=a.regnumber', 'LEFT');
						} else {

							$where = " a.exam_period = " . $exam_period . " AND `pay_status` = '1'  AND d.status = 1";
							if ($exam_code != '')
								$where .= " AND  a.exam_code = " . $exam_code . "";
							$this->db->join('payment_transaction d', 'd.ref_id=a.id', 'LEFT');
							$this->db->join('exam_master b', 'b.exam_code=a.exam_code', 'LEFT');
							//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');

						}

						if ($where)
							$this->db->where($where);
						if ($exam_code == 1003 && $exam_period == 777) {
							$this->db->where('a.created_on >=', '2020-06-09');
						}
						$this->db->group_by('exam_code');
						$exam = $this->master_model->getRecords("member_exam a", "", $select);
						//echo $this->db->last_query();
						//exit;
						/*echo "2222 <br>";
						echo $this->db->last_query();*/
					}
				}
			}

			/*if($exam_code=='1002' ||$exam_code=='1003' || $exam_code=='1004')
				{
					if($exam_code=='1002' || $exam_code=='1004')
					{
						$select = 'member_exam.regnumber,member_exam.exam_code,member_exam.exam_period,COUNT(member_exam.id) mem_cnt,exam_master.description,exam_master.exam_type';
						$this->db->select($select);			
						$this->db->where('member_exam.exam_code',$exam_code);
						$this->db->where('member_exam.exam_period',$exam_period);
						$this->db->where('member_exam.pay_status',1);
						$this->db->group_by('member_exam.exam_code');	
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code','LEFT');		
						$exam = $this->master_model->getRecords("member_exam");
				//echo '***'.$this->db->last_query();exit;
					}
				
				else
				{		
						$select = 'member_exam.regnumber,member_exam.exam_code,member_exam.exam_period,COUNT(member_exam.id) mem_cnt,exam_master.description,exam_master.exam_type';
						$this->db->select($select);			
						$this->db->where('member_exam.exam_code',$exam_code);
						$this->db->where('member_exam.exam_fee <=','300');
						$this->db->where('member_exam.exam_period',$exam_period);
						$this->db->where('member_exam.pay_status',1);
						$this->db->group_by('member_exam.exam_code');	
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code','LEFT');		
						$exam = $this->master_model->getRecords("member_exam");
					
						$select = 'member_exam.regnumber,member_exam.exam_code,member_exam.exam_period,COUNT(member_exam.id) mem_cnt,exam_master.description,exam_master.exam_type';
						$this->db->select($select);			
						$this->db->where('member_exam.exam_code',$exam_code);
						$this->db->where('member_exam.exam_fee >','300');
						$this->db->where('member_exam.exam_period',$exam_period);
						$this->db->where('member_exam.pay_status',1);
						$this->db->group_by('member_exam.exam_code');	
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code','LEFT');		
						$withot_pay = $this->master_model->getRecords("member_exam");
						
					//echo $this->db->last_query();exit;
				}
				}*/
		} else {
			$data["links"] = '';
			$data['info'] = '';
		}

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i>Home</a></li>
										<li>Exam Registration Details</li>
								   </ol>';
		$current_date = date('Y-m-d');
		$d = date('Y-m-d', strtotime("- 15 day", strtotime($current_date)));
		$this->db->where("(exam_to_date >='" . $d . "' OR exam_to_date='0000-00-00')");
		//$this->db->or_where('exam_to_date','0000-00-00');
		$data["exam_code"] = $this->Master_model->getRecords("exam_activation_master", array('exam_activation_delete' => 0), '', array('exam_code' => 'ASC'));
		//echo $this->db->last_query();exit;
		//,'exam_to_date >='=>date('Y-m-d')
		$this->db->distinct();
		$this->db->where("(exam_to_date >='" . $d . "' OR exam_to_date='0000-00-00')");
		$this->db->select('exam_period');
		$data["exam_period"] =  $this->Master_model->getRecords("exam_activation_master", array('exam_activation_delete' => 0), '', array('exam_code' => 'ASC'));


		$data['withot_pay'] = $withot_pay;
		$data['result'] = $exam;
		$this->load->view('admin/dashboard_report', $data);
	}

	public function GetExamPeriod($ex_code = '')
	{
		$str = '';
		if ($ex_code) {
			$this->db->distinct();
			$this->db->select('exam_period');
			$exam_period = $this->Master_model->getRecords("misc_master", array('exam_code' => $ex_code), '', array('exam_period' => 'ASC'));
			if (count($exam_period)) {
				$str .= '<option value="">Select</option>';
				foreach ($exam_period as $row) {
					$str .= '<option value="' . $row['exam_period'] . '">' . $row['exam_period'] . '</option>';
				}
			}
		}
		echo $str;
	}

	//##---------check mail alredy exist or not on edit page(Prafull)-----------## 
	public function editemailduplication()
	{
		$email = $_POST['email'];
		$regid = $_POST['regid'];
		$regtype = $_POST['regtype'];
		$excode = $_POST['excode'];
		if ($email != "" && $regid != "") {
			$prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $regid, 'isactive' => '1', 'registrationtype' => $regtype));
			//echo $this->db->last_query();
			if ($prev_count == 0) {
				echo 'ok';
			} else {
				echo 'exists';
			}
		} else {
			echo 'error';
		}
	}

	//##---------check mobile number alredy exist or not on edit page(Prafull)-----------## 
	public function editmobile()
	{
		$mobile = $_POST['mobile'];
		$regid = $_POST['regid'];
		$regtype = $_POST['regtype'];
		if ($mobile != "" && $regid != "") {
			$prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $regid, 'isactive' => '1', 'registrationtype' => $regtype));
			if ($prev_count == 0) {
				echo 'ok';
			} else {
				echo 'exists';
			}
		} else {
			echo 'error';
		}
	}


	##---------check pincode/zipcode alredy exist or not (prafull)-----------##
	public function checkpin()
	{
		$statecode = $_POST['statecode'];
		$pincode = $_POST['pincode'];
		if ($statecode != "") {
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
			$prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
			//echo $this->db->last_query();
			if ($prev_count == 0) {
				echo 'false';
			} else {
				echo 'true';
			}
		} else {
			echo 'false';
		}
	}

	public function refund_list()
	{
		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Refund Transaction</li>
							   </ol>';

		$this->load->view('admin/refund_list', $data);
	}

	public function refund_transaction()
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
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		//$this->db->join('payment_transaction','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '"');
			$total_row = $this->UserModel->getRecordCount("payment_refund a", '', '', 'regnumber');
		} else {
			//$this->db->where('isactive','1');
			//$this->db->where('isdeleted',0);
			$total_row = $this->UserModel->getRecordCount("payment_refund a", $field, $value);
		}

		$url = base_url() . "admin/Report/refund_transaction/";

		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);


		//$select = 'regid,regnumber,namesub,firstname,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,DATE(createdon) createdon,usrpassword,isactive,registrationtype';
		//$this->db->join('payment_transaction','ref_id = regid AND member_regnumber = regnumber','LEFT');
		if (strpos($value, '~') !== false) {
			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" ');
			$res = $this->UserModel->getRecords("payment_refund a", '', '', '', $sortkey, $sortval, $per_page, $start);
		} else {
			//$this->db->where('isactive','1');
			//$this->db->where('isdeleted',0);			
			$res = $this->UserModel->getRecords("payment_refund a", '', $field, $value, $sortkey, $sortval, $per_page, $start);
		}

		//echo $this->db->last_query();
		//$data['query'] = $this->db->last_query();

		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;

			foreach ($result as $row) {
				$status = 0;

				// TRANSACTION DETAILS NOT USED
				/*$transaction_no = '';
				$transaction_date = '';
				$transaction_amt = '0';
				if($row['registrationtype']!='NM')
				{
					if($row['registrationtype']=='DB')
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pay_type'=>2,'member_regnumber'=>$row['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date', array('id','ASC'));
						//'pg_flag'=>'IIBF_EXAM_DB',
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pay_type'=>1,'ref_id'=>$row['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date', array('id','ASC'));
						//'pg_flag'=>'iibfregn',
					}
				}
				else
				{
					$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pay_type'=>2,'member_regnumber'=>$row['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date', array('id','ASC'));
					//'pg_flag'=>'IIBF_EXAM_REG',
				}
				if(count($trans_details))
				{
					$transaction_no = $trans_details[0]['transaction_no'];
					$transaction_amt = $trans_details[0]['amount'];
					$date = $trans_details[0]['date'];
					if($date!='0000-00-00')
					{	$transaction_date = date('d-M-y',strtotime($date));	}
				}
				*/

				$status = $row['status'];
				if ($status == 1)
					$result[$i]['status'] = 'Success';
				else if ($status == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Fail';

				$i++;
			}

			$data['result'] = $result;

			if (count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';

			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if (($start + $per_page) > $total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start + $per_page;

			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
			$data['index'] = $start + 1;
		}

		$json_res = json_encode($data);
		echo $json_res;
	}


	public function custom_reg_mail($regid, $regnum)
	{

		//email to user
		$last = $this->uri->total_segments();
		/*$regid = $this->uri->segment($last-2);
		$regnum = $this->uri->segment($last-1);*/

		if (is_numeric($regnum) && is_numeric($regid)) {
			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
			if (count($emailerstr) > 0) {
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

				//$decpass = $aes->decrypt($user_info[0]['usrpassword']);

				//Query to get user details
				/*$this->db->join('state_master','state_master.state_code=member_registration.state','LEFT');
				$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute','LEFT');
				$this->db->join('qualification','qualification.qid=member_registration.specify_qualification','LEFT');
				$this->db->join('idtype_master','idtype_master.id=member_registration.idproof','LEFT');
				$this->db->join('designation_master','designation_master.dcode=member_registration.designation','LEFT');
				$this->db->join('payment_transaction','payment_transaction.ref_id=member_registration.regid','LEFT');			*/

				$result = $this->master_model->getRecords('member_registration', array('regnumber' => "'" . $regnum . "'", 'regid' => $regid), 'regid,regnumber,firstname,middlename,lastname,email,usrpassword,dateofbirth,dateofjoin,gender');
				//echo $this->db->last_query()."<br>";
				if (count($result) > 0) {
					$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));

					$newstring1 = str_replace("#application_num#", "" . $regnum . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#password#", "" . $decpass . "", $newstring1);

					//$final_str = str_replace("#PINCODE#", "".strtoupper($result[0]['pincode'])."",$newstring24);

					$info_arr = array(
						'to' => $result[0]['email'],
						/*'bcc'=>'ugalevrushali@gmail.com',*/
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $newstring2
					);


					if ($this->Emailsending->mailsend($info_arr)) {
						echo 'Email sent successfully !!';
						/*$this->session->set_flashdata('success','Email sent successfully !!');
						if($flag!=0 && $flag!=1)
						{
							redirect(base_url().'admin/Search/search_success');
						}
						elseif($flag==0)
						{
							redirect(base_url().'admin/Report/datewise');
						}elseif($flag==1){
							redirect(base_url().'admin/Report/query');
						}*/
					} else {
						echo 'Error while sending email';
						/*$this->session->set_flashdata('error','Error while sending email !!');
						if($flag!=0 && $flag!=1 && $flag!=2)
						{
							redirect(base_url().'admin/Search/search_success');
						}
						elseif($flag==0)
						{
							redirect(base_url().'admin/Report/datewise');
						}elseif($flag==1){
							redirect(base_url().'admin/Report/query');
						}*/
					}
				} else {
					echo 'Something went wrong...';
					/*$this->session->set_flashdata('error','Something went wrong...');
					if($flag!=0 && $flag!=1)
					{
						redirect(base_url().'admin/Search/search_success');
					}
					elseif($flag==0)
					{
						redirect(base_url().'admin/Report/datewise');
					}elseif($flag==1){
						redirect(base_url().'admin/Report/query');
					}*/
				}
			} else {
				echo 'Something went wrong...';
				/*$this->session->set_flashdata('error','Something went wrong...');
				if($flag!=0 && $flag!=1)
				{
					redirect(base_url().'admin/Search/search_success');
				}
				elseif($flag==0)
				{
					redirect(base_url().'admin/Report/datewise');
				}elseif($flag==1){
					redirect(base_url().'admin/Report/query');
				}*/
			}
		}
	}




	// Remove an item from string
	public function removeFromString($str, $item)
	{
		$parts = explode(',', $str);
		while (($i = array_search($item, $parts)) !== false) {
			unset($parts[$i]);
		}
		return implode(',', $parts);
	}

	public function dupCert()
	{
		//print_r($_POST);
		//Array ( [searchBy] => 01 [SearchVal] => 801359088 )  
		$data = $mem_details = array();
		if (isset($_POST['SearchVal'])) {
			$this->db->Order_by('payment_transaction.date', 'DESC');
			$this->db->join('duplicate_certificate ', 'duplicate_certificate.id=payment_transaction.ref_id', 'LEFT');
			$this->db->join('exam_invoice', 'exam_invoice.receipt_no=payment_transaction.receipt_no', 'LEFT');
			$this->db->where('duplicate_certificate.regnumber', $_POST['SearchVal']);
			$this->db->where('payment_transaction.member_regnumber', $_POST['SearchVal']);
			$this->db->where('payment_transaction.pay_type', '4');
			$mem_details =  $this->master_model->getRecords("payment_transaction");
			//echo  $this->db->last_query();

			//echo 'hjghjg';//exit;
			//$this->db->join('payment_transaction b','b.ref_id=a.regid AND b.member_regnumber=a.regnumber','LEFT');	

			//$mem_details = $this->master_model->getRecords('duplicate_certificate',
			//  array('regnumber'=>$_POST['SearchVal'],		           
			//  'pay_status'=>1));
			if (!empty($mem_details)) {
				//$mem_details = $this->master_model->getRecords('payment_transaction',
				//        array(''=>$_POST['SearchVal'],		           
				//        'pay_status'=>1));
			}
		}

		//echo $this->db->last_query();
		//print_r($data);
		$data['mem_details'] = $mem_details;
		$this->load->view('admin/dupCert_details', $data);
	}
}
