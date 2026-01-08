<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GstRecoveryDashboard extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
    }
    public function index(){
		
		$this->db->join('admit_exam_master', 'gst_recovery_details.exam_code = admit_exam_master.exam_code', 'left');
		$this->db->join('member_registration', 'gst_recovery_details.member_no = member_registration.regnumber', 'left');
		$this->db->where('gst_recovery_details.pay_status',1);
		$this->db->group_by('gst_recovery_details_pk');
		$mem_info = $this->master_model->getRecords('gst_recovery_details','','member_no,gst_recovery_details.exam_code,firstname,middlename,lastname,pay_type,igst_amt,doc_no,date_of_doc,doc_image,description', array('gst_recovery_details_pk' => 'desc'));
		$data['mem_info'] = $mem_info;
		$this->load->view('admin/gst_recovery_dashboard/member_list',$data);
    }
	
    public function monthly()
	{	
		if (isset($_POST['btnSearch'])) 
		{
		    
			$app_type = $_POST["app_type"];
		    $pay_type = $_POST["pay_type"];
		    $from_date = $_POST["from_date"];
			$to_date = $_POST['to_date'];
			
			$this->db->where('DATE(date_of_invoice) >=',$from_date);
			$this->db->where('DATE(date_of_invoice) <=',$to_date);
			$this->db->where('app_type',$app_type);
			$this->db->where('transaction_no !=','');
			$invoice_info = $this->master_model->getRecords('exam_invoice','','invoice_no');
			//echo $this->db->last_query();
			$this->db->where('DATE(date) >=',$from_date);
			$this->db->where('DATE(date) <=',$to_date);
			$this->db->where('status',1);
			$this->db->where('pay_type',$pay_type);
			$this->db->where('transaction_no !=','');
			$payment_info = $this->master_model->getRecords('payment_transaction','','id');
			//echo $this->db->last_query();die;
			if($pay_type == 21)
			{
				$this->db->where('DATE(sbi_refund_date) >=',$from_date);
				$this->db->where('DATE(sbi_refund_date) <=',$to_date);
				$this->db->where('req_status',5);
				$this->db->where('credit_note_number !=','');
				$credit_info = $this->master_model->getRecords('maker_checker','','credit_note_number');
				//echo $this->db->last_query();die;
			}
			
			$data['payment_count'] = count($payment_info);
			$data['invoice_info'] = $invoice_info;
			$data['invoice_count'] = count($invoice_info);
			$data['credit_count'] = count($credit_info);
			$data['app_type'] = $app_type;
		    $data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$this->load->view('admin/gst_recovery_dashboard/monthly',$data);
		}
		else
		{
		    $this->load->view('admin/gst_recovery_dashboard/monthly');
		}
	}
	public function monthly_download_CSV($app_type,$from_date,$to_date)
	{
		$this->db->where('DATE(date_of_invoice) >=',$from_date);
		$this->db->where('DATE(date_of_invoice) <=',$to_date);
		$this->db->where('app_type',$app_type);
		$this->db->where('transaction_no !=','');
		$invoice_info = $this->master_model->getRecords('exam_invoice','','invoice_no');
		if($app_type == 'CN')
		{
			    $this->db->where('DATE(sbi_refund_date) >=',$from_date);
				$this->db->where('DATE(sbi_refund_date) <=',$to_date);
				$this->db->where('req_status',5);
				$this->db->where('credit_note_number !=','');
				$creditnote_info = $this->master_model->getRecords('maker_checker','','credit_note_number');
			//echo $this->db->last_query();die;
		
		}
		$csv = '';
		if(!empty($invoice_info)){		
			foreach($invoice_info as $row){	
				 $csv.= $row['invoice_no']."\n"; 
			}
		}
		else if(!empty($creditnote_info))
		{
			foreach($creditnote_info as $row){	
				 $csv.= $row['credit_note_number']."\n"; 
			}
		}
        
		$filename = "Monthly_Counts.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}
}
