<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class MonthlyCount_CSV extends CI_Controller 
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
				if ($this->form_validation->run() == TRUE)
				{
					//print_r($_POST); 
					 $exam_code = $_POST['exam_code'];
					if($exam_code == '01')
					{
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
						$count = 0;
						
						$res = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `payment_transaction` LEFT JOIN `exam_invoice` ON `exam_invoice`.`receipt_no` = `payment_transaction`.`receipt_no` WHERE DATE(`exam_invoice`.`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND `exam_invoice`.`exam_code` = 1016 AND `exam_invoice`.`app_type` = 'O' AND `exam_invoice`.`invoice_no` != '' AND `exam_invoice`.`transaction_no` != '' AND `payment_transaction`.`status` = '1' ");
						
						$approved_chartered_count = $res->result()[0]->numrows;
					//	echo $this->db->last_query(); die;
					}
					else if($exam_code == '02')
					{
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
						$count = 0;
						
						
						
						
						$res = $this->db->query("SELECT count(exam_invoice.receipt_no) AS `numrows` FROM`exam_invoice`,dra_payment_transaction WHERE exam_invoice.`exam_period` != 0 AND exam_invoice.exam_code IN (45,57) AND exam_invoice.receipt_no =dra_payment_transaction.receipt_no AND`invoice_no` != '' and exam_invoice.gstin_no != '-' and dra_payment_transaction.status = 1 AND Date(`date_of_invoice`) BETWEEN '".$from_date."' and '".$to_date."' AND app_type='I'");
						
						$approved_dra_count = $res->result()[0]->numrows;
						
					}
					
				}
			}
			$data['approved_chartered_count'] = $approved_chartered_count;
			$data['approved_garp_count'] = $approved_garp_count;
			$data['approved_ampself_count'] = $approved_ampself_count;
			$data['approved_dra_count'] = $approved_dra_count;
			$data['approved_ampbank_count'] = $approved_ampbank_count;
			$data['approved_xlriself_count'] = $approved_xlriself_count;
			$data['approved_xlribank_count'] = $approved_xlribank_count;
			$data['exam_code'] = $exam_code;
			$this->load->view('MonthlyCount_CSV/gst_dashboard',$data);
		}
		
		
	}		