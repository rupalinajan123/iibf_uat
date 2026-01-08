<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class GstB2BDashboard extends CI_Controller 
	{
		public function __construct()
  	{ 		
   		parent::__construct();
			//$this->load->helper(array('form', 'url'));
			//this->load->helper('page');
			/* Load form validation library */ 
			//$this->load->library('upload');
			//$this->load->library('email');
			//$this->load->library('pagination');
			//$this->load->library('table');	
			
			$this->load->library('form_validation');
			$this->load->model('Master_model'); 
			$this->load->library('session');    
			
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
		}
		
		public function index()
		{
			$count = $exam_code = $from_date = $to_date = '';
			if(isset($_POST['submit']))
			{ 
				$this->form_validation->set_rules('exam_code', 'Exam Name', 'trim|required', array('required'=>"Please select the %s"));
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
				//$this->form_validation->set_rules('from_date_cn', 'From Date', 'trim|required');
				//$this->form_validation->set_rules('to_date_cn', 'To Date', 'trim|required');
				if ($this->form_validation->run() == TRUE)
				{
					//print_r($_POST); 
					 $exam_code = $_POST['exam_code'];
					if($exam_code == '01')
					{
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
						
						$count = 0;
						
						//Count of Pending Approvals
						/* $this->db->join('exam_invoice','exam_invoice.receipt_no = bulk_payment_transaction.receipt_no','LEFT'); 
						$this->db->where('exam_invoice.app_type','Z');
						//$this->db->where('exam_invoice.invoice_no','');
						//$this->db->where('exam_invoice.transaction_no!=','');
						$this->db->where('exam_invoice.date_of_invoice BETWEEN "'. $from_date.'" and "'.$to_date.'"');
						$this->db->where('bulk_payment_transaction.status','2');
						$pending_bulk_count = $this->master_model->getRecordCount('bulk_payment_transaction'); */
						//echo $this->db->last_query(); die;
						
						$res1 = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `bulk_payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`receipt_no` = `bulk_payment_transaction`.`receipt_no` WHERE DATE(`exam_invoice`.`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND `exam_invoice`.`app_type` = 'Z' AND `exam_invoice`.`invoice_no` != '' AND `exam_invoice`.`transaction_no` != '' AND `exam_invoice`.`gstin_no` != '' AND `bulk_payment_transaction`.`status` = '2' ");
						$pending_bulk_count1 = $res1->result()[0]->numrows;

            $res2 = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `iibfbcbf_payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`receipt_no` = `iibfbcbf_payment_transaction`.`receipt_no` AND `exam_invoice`.`exam_code` = `iibfbcbf_payment_transaction`.`exam_code` WHERE DATE(`exam_invoice`.`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND `exam_invoice`.`app_type` = 'BC' AND `exam_invoice`.`invoice_no` != '' AND `exam_invoice`.`transaction_no` != '' AND `exam_invoice`.`gstin_no` != '' AND `iibfbcbf_payment_transaction`.`status` = '2' AND `iibfbcbf_payment_transaction`.`payment_mode` = 'BULK' ");
            $pending_bulk_count2 = $res2->result()[0]->numrows;

            $pending_bulk_count = $pending_bulk_count1 + $pending_bulk_count2;
						
						//Count of Approveds
						/*$this->db->join('exam_invoice','exam_invoice.receipt_no = bulk_payment_transaction.receipt_no','LEFT'); 
						$this->db->where(DATE('exam_invoice.date_of_invoice) BETWEEN "'. $from_date.'" and "'.$to_date.'"');
						$this->db->where('exam_invoice.app_type','Z');
						$this->db->where('exam_invoice.invoice_no !=','');
						$this->db->where('exam_invoice.transaction_no !=','');
						$this->db->where('exam_invoice.gstin_no!=','');
						$this->db->where('bulk_payment_transaction.status','1');*/
						//$approved_bulk_count = $this->master_model->getRecordCount('bulk_payment_transaction');
						$res1 = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `bulk_payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`receipt_no` = `bulk_payment_transaction`.`receipt_no` WHERE DATE(`exam_invoice`.`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND `exam_invoice`.`app_type` = 'Z' AND `exam_invoice`.`invoice_no` != '' AND `exam_invoice`.`transaction_no` != '' AND `exam_invoice`.`gstin_no` != '' AND `bulk_payment_transaction`.`status` = '1' ");						
						$approved_bulk_count1 = $res1->result()[0]->numrows;
						
            $res2 = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `iibfbcbf_payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`receipt_no` = `iibfbcbf_payment_transaction`.`receipt_no` AND `exam_invoice`.`exam_code` = `iibfbcbf_payment_transaction`.`exam_code` WHERE DATE(`exam_invoice`.`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND `exam_invoice`.`app_type` = 'BC' AND `exam_invoice`.`invoice_no` != '' AND `exam_invoice`.`transaction_no` != '' AND `exam_invoice`.`gstin_no` != '' AND `iibfbcbf_payment_transaction`.`status` = '1' AND `iibfbcbf_payment_transaction`.`payment_mode` = 'BULK' ");						
						$approved_bulk_count2 = $res2->result()[0]->numrows;

            $approved_bulk_count = $approved_bulk_count1 + $approved_bulk_count2;
						//echo $this->db->last_query(); die;
					}
					else if($exam_code == '02') // dra count
					{
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
						$count = 0;
						
						//Count of Pending Approvals
						/* $this->db->join('exam_invoice','exam_invoice.receipt_no = dra_payment_transaction.receipt_no','LEFT'); 
						$this->db->where('exam_invoice.date_of_invoice BETWEEN "'. $from_date.'" and "'.$to_date.'"');
						$this->db->where('exam_invoice.app_type','I');
						$this->db->where('exam_invoice.exam_period !=','0');
						$this->db->where('exam_invoice.gstin_no!=','-');
						$this->db->where('dra_payment_transaction.status','2');
						$pending_dra_count = $this->master_model->getRecordCount('dra_payment_transaction'); */
						
						 // $res = $this->db->query("SELECT count(dra_payment_transaction.receipt_no) AS `numrows` FROM `exam_invoice`,dra_payment_transaction WHERE exam_invoice.`exam_period` != 0 AND dra_payment_transaction.exam_code IN (45,57,1036) AND exam_invoice.pay_txn_id =dra_payment_transaction.id   and dra_payment_transaction.status = 3 AND Date(`date`) BETWEEN '".$from_date."' and '".$to_date."' AND app_type='I'");

						$res = $this->db->query("SELECT count(dra_payment_transaction.receipt_no) AS `numrows` FROM dra_payment_transaction WHERE dra_payment_transaction.exam_code IN (45,57,1036) AND dra_payment_transaction.exam_period != 0 AND dra_payment_transaction.status = 3 AND Date(`date`) BETWEEN '".$from_date."' and '".$to_date."'");
						
						 $pending_dra_count = $res->result()[0]->numrows;
						
						//Count of Pending Approvals
						/* $this->db->join('exam_invoice','exam_invoice.receipt_no = dra_payment_transaction.receipt_no','LEFT'); 
						$this->db->where('exam_invoice.date_of_invoice BETWEEN "'. $from_date.'" and "'.$to_date.'"');
						$this->db->where('exam_invoice.app_type','I');
						$this->db->where('exam_invoice.invoice_no !=','');
						$this->db->where('exam_invoice.exam_period !=','0');
						$this->db->where('exam_invoice.gstin_no!=','-');
						$this->db->where('dra_payment_transaction.status','1');
						$approved_dra_count = $this->master_model->getRecordCount('dra_payment_transaction'); */
						//echo $this->db->last_query(); die;
						
						$res = $this->db->query("SELECT count(exam_invoice.receipt_no) AS `numrows` FROM`exam_invoice`,dra_payment_transaction WHERE exam_invoice.`exam_period` != 0 AND exam_invoice.exam_code IN (45,57,1036) AND exam_invoice.pay_txn_id =dra_payment_transaction.id AND`invoice_no` != '' and exam_invoice.gstin_no != '-' AND `exam_invoice`.`gstin_no` != '' and dra_payment_transaction.status = 1 AND Date(`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND app_type='I'");
						
						$approved_dra_count = $res->result()[0]->numrows;
						
					}
          else if($exam_code == '03')
					{
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
						$count = 0;
						// dra reg count of pending
						 $res = $this->db->query("SELECT count(exam_invoice.receipt_no) AS `numrows` FROM`exam_invoice`,payment_transaction WHERE exam_invoice.receipt_no =payment_transaction.receipt_no and exam_invoice.gstin_no != '' and payment_transaction.status = 2 AND Date(`date`) BETWEEN '".$from_date."' and '".$to_date."' AND exam_invoice.app_type='H' and payment_transaction.pay_type=16");
						
						  $pending_dra_reg_count = $res->result()[0]->numrows;
						 //dra reg count of approved
						 $res = $this->db->query("SELECT count(exam_invoice.receipt_no) AS `numrows` FROM`exam_invoice`,payment_transaction WHERE exam_invoice.receipt_no =payment_transaction.receipt_no and exam_invoice.gstin_no != '' and payment_transaction.status = 1 AND Date(`date`) BETWEEN '".$from_date."' and '".$to_date."' AND exam_invoice.app_type='H' and payment_transaction.pay_type=16 AND exam_invoice.transaction_no != ''");
						
						 $approved_dra_reg_count = $res->result()[0]->numrows;
						 
					}
          else if($exam_code == '04')
					{
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
						$count = 0;
						// renewal count of pending
						 $res = $this->db->query("SELECT count(payment_transaction.receipt_no) AS `numrows` FROM `agency_center_renew` as ar left JOIN agency_center as a ON a.center_id=ar.agency_id left JOIN dra_inst_registration ON ar.agency_id=dra_inst_registration.id left JOIN payment_transaction ON ar.agency_renew_id=payment_transaction.ref_id WHERE ar.`pay_status` = '2' and payment_transaction.pay_type = 17 and payment_transaction.status = 2 AND Date(payment_transaction.`date`) BETWEEN '".$from_date."' and '".$to_date."'");
						
						  $pending_agn_renewal_count = $res->result()[0]->numrows;
						  // renewal count of approved
						 $res = $this->db->query("SELECT count(payment_transaction.receipt_no) AS `numrows` FROM `agency_center_renew` as ar left JOIN agency_center as a ON a.center_id=ar.agency_id left JOIN dra_inst_registration ON ar.agency_id=dra_inst_registration.id left JOIN payment_transaction ON ar.agency_renew_id=payment_transaction.ref_id WHERE ar.`pay_status` = '1' and payment_transaction.pay_type = 17 and payment_transaction.status = 2 AND Date(payment_transaction.`date`) BETWEEN '".$from_date."' and '".$to_date."'");
						
						  $approved_agn_renewal_count = $res->result()[0]->numrows;
						  
					}
					
				}
			}
			//credit note count
			//echo 'in out'; die;
			if(isset($_POST['from_date_cn']) && $_POST['from_date_cn'] != "") { $from_date_cn = date("Y-m-d",strtotime($_POST['from_date_cn'])); } else { $from_date_cn = ''; }
						if(isset($_POST['to_date_cn']) && $_POST['to_date_cn'] != "") { $to_date_cn = date("Y-m-d",strtotime($_POST['to_date_cn'])); } else { $to_date_cn = ''; }
						
						
				$this->db->where("req_status ","5");
				$this->db->where("credit_note_number != ",'');
				$this->db->where("credit_note_date != ",'0000-00-00');
				$this->db->where("credit_note_date BETWEEN '".$from_date_cn."' AND '".$to_date_cn."' ");
				$credit_note_counts = $this->master_model->getRecordCount('maker_checker');
				//echo $this->db->last_query();  die;
			$data['pending_bulk_count'] = $pending_bulk_count;
			$data['approved_bulk_count'] = $approved_bulk_count;
			$data['pending_dra_count'] = $pending_dra_count;
			$data['approved_dra_count'] = $approved_dra_count;
			$data['pending_dra_reg_count'] = '0';
			$data['approved_dra_reg_count'] = $approved_dra_reg_count;
			$data['pending_agn_renewal_count'] = $pending_agn_renewal_count;
			$data['approved_agn_renewal_count'] = $approved_agn_renewal_count;
			$data['exam_code'] = $exam_code;
			$data['credit_note_counts'] = $credit_note_counts;
			
			$this->load->view('gstb2bdashboard/gst_dashboard',$data);
		}
		
		
	}		