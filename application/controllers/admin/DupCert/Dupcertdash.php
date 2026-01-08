<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dupcertdash extends CI_Controller
{
    public $UserID;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('UserModel');
        $this->load->model('Master_model');
		$this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
        
    }
    public function index()
    {
          $this->load->view('admin/dupcert_dashboard/dashboard');
    }
	public function dup_cert_data()
	{
		$data = '';
		if(isset($_POST['btnSearch']))
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if(!empty($from_date))
			{
				$select = 'DISTINCT(a.id)';
				//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');
				$this->db->join('payment_transaction b','b.ref_id=a.id AND b.member_regnumber=a.regnumber','LEFT');
				if($from_date!='' && $to_date!='')
				{
					//$this->db->where('DATE(date) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 4 AND pay_status = "1" ');
				}
				else if($from_date!='' & $to_date=='')
				{
					//$this->db->where('DATE(date) = "'.$from_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 4 AND pay_status = "1" ');
				}
				$data = $this->UserModel->getRecordCount("duplicate_certificate a", '', '',$select);
				
				//$this->load->view('admin/dupcert_dashboard/dupcert_statistic',$data);
			}
		}
			if(empty($data))
				{
					$data = 'No data';
				}
			$total_count = $this->UserModel->getRecordCount("duplicate_certificate");
			$success_data = $this->master_model->getRecords('duplicate_certificate',array('pay_status'=>1));
			$result = array('data'=>$data,'total_count'=>$total_count,'success_data'=>$success_data);
			$this->load->view('admin/dupcert_dashboard/dupcert_statistic',$result);
		
	}
	public function cpd_data()
	{
		$data = '';
		if(isset($_POST['btnSearch']))
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if(!empty($from_date))
			{
				$select = 'DISTINCT(a.id)';
				//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');
				$this->db->join('payment_transaction b','b.ref_id=a.id AND b.member_regnumber=a.member_no','LEFT');
				if($from_date!='' && $to_date!='')
				{
					//$this->db->where('DATE(date) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 9 AND pay_status = "1" ');
				}
				else if($from_date!='' & $to_date=='')
				{
					//$this->db->where('DATE(date) = "'.$from_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 9 AND pay_status = "1" ');
				}
				$data = $this->UserModel->getRecordCount("cpd_registration a", '', '',$select);
				
				//$this->load->view('admin/dupcert_dashboard/dupcert_statistic',$data);
			}
		}
			if(empty($data))
				{
					$data = 'No data';
				}
			$total_count = $this->UserModel->getRecordCount("cpd_registration");
			$success_data = $this->master_model->getRecords('cpd_registration',array('pay_status'=>1));
			$result = array('data'=>$data,'total_count'=>$total_count,'success_data'=>$success_data);
			$this->load->view('admin/dupcert_dashboard/cpd_statistic',$result);
	}
	public function download_CSV()
	{
	    //exit; 
		 $csv = "member_no,invoice_no,namesub,firstname,middlename,lastname,email,mobile,address1,address2,address3,address4,district,city,state,pincode,fee,fee_amt,cgst_rate,cgst_amt,sgst_rate,sgst_amt,cs_total,igst_rate,igst_amt,igst_total,occupied ,date\n";//Column headers
		
		
		$query = $this->db->query("SELECT cpd_registration.member_no,exam_invoice.invoice_no,`namesub`,`firstname`,`middlename`,`lastname`,`email`,`mobile`,`address1`,`address2`,`address3`,`address4`,`district`,`city`,`state`,`pincode`,fee,`fee_amt`,`cgst_rate`,`cgst_amt`,`sgst_rate`,`sgst_amt`,`cs_total`,`igst_rate`,`igst_amt`,`igst_total`,DATE_FORMAT(payment_transaction.date,'%Y-%m-%d') AS date FROM `cpd_registration` LEFT JOIN exam_invoice ON exam_invoice.member_no = cpd_registration.member_no LEFT JOIN payment_transaction on payment_transaction.receipt_no = exam_invoice.receipt_no WHERE cpd_registration.pay_status = 1 AND payment_transaction.pay_type = 9 AND payment_transaction.status = 1 ");
		
		
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.= $record['member_no'].','.$record['invoice_no'].','.$record['namesub'].',"'.$record['firstname'].'","'.$record['middlename'].'","'.$record['lastname'].'",'.$record['email'].','.$record['mobile'].',"'.$record['address1'].'","'.$record['address2'].'","'.$record['address2'].'","'.$record['address3'].'","'.$record['address4'].'",'.$record['district'].','.$record['city'].','.$record['state'].','.$record['pincode'].','.$record['fee'].','.$record['fee_amt'].','.$record['cgst_rate'].','.$record['cgst_amt'].','.$record['sgst_rate'].','.$record['sgst_amt'].','.$record['cs_total'].','.$record['igst_rate'].','.$record['igst_amt'].','.$record['igst_total'].','.$record['date']."\n";
		}
		
        $filename = "cpd_registration.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}
	public function DRA_data()
	{
		$data = '';
		if(isset($_POST['btnSearch']))
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if(!empty($from_date))
			{
				$select = 'DISTINCT(a.id)';
				//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');
				$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
				if($from_date!='' && $to_date!='')
				{
					//$this->db->where('DATE(date) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 12 AND a.status = "1" ');
				}
				else if($from_date!='' & $to_date=='')
				{
					//$this->db->where('DATE(date) = "'.$from_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 12 AND a.status = "1" ');
				}
				$data = $this->UserModel->getRecordCount("dra_inst_registration a", '', '',$select);
				
				//$this->load->view('admin/dupcert_dashboard/dupcert_statistic',$data);
			}
		}
			if(empty($data))
				{
					$data = 'No data';
				}
			$total_count = $this->UserModel->getRecordCount("dra_inst_registration");
			$success_data = $this->master_model->getRecords('dra_inst_registration',array('status'=>1));
			$result = array('data'=>$data,'total_count'=>$total_count,'success_data'=>$success_data);
			$this->load->view('admin/dupcert_dashboard/dra_statistic',$result);
	
	}
	public function download_CSV_dra()
	{
		  //exit; 
		 $csv = "invoice_no,inst_name,establish_year,main_address1,main_address2,main_address3,main_address4,main_district,main_city,main_state,main_pincode,inst_phone,inst_fax_no,inst_website,inst_head_name,inst_head_contact_no,inst_head_email,location_name,location_address,address1,address2,address3,address4,district,city,state,pincode,office_no,contact_person_name,contact_person_mobile,contact_email_id,inst_type,due_diligence,status,cgst_rate,cgst_amt,sgst_rate,sgst_amt,cs_total,igst_rate,igst_amt,igst_total,date\n";//Column headers
		
		
		$query = $this->db->query("SELECT exam_invoice.invoice_no,`inst_name`, `estb_year`,`main_address1`, `main_address2`, `main_address3`, `main_address4`, `main_district`, `main_city`, `main_state`, `main_pincode`, `inst_phone`, `inst_fax_no`, `inst_website`, `inst_head_name`, `inst_head_contact_no`, `inst_head_email`, `location_name`, `location_address`, `address1`, `address2`, `address3`, `address4`, `district`, `city`, `state`, `pincode`, `office_no`, `contact_person_name`, `contact_person_mobile`, `email_id`, `inst_type`, `due_diligence`, dra_inst_registration.status,`cgst_rate`,`cgst_amt`,`sgst_rate`,`sgst_amt`,`cs_total`,`igst_rate`,`igst_amt`,`igst_total`,DATE_FORMAT(payment_transaction.date,'%Y-%m-%d') AS date
		FROM dra_inst_registration
        LEFT JOIN payment_transaction on payment_transaction.ref_id = dra_inst_registration.id
        LEFT JOIN exam_invoice on exam_invoice.receipt_no = payment_transaction.receipt_no AND exam_invoice.transaction_no = payment_transaction.transaction_no
		WHERE dra_inst_registration.status = 1 AND payment_transaction.pay_type = 12 AND payment_transaction.status = 1 and exam_invoice.app_type = 'A'");
		
		
		$result = $query->result_array(); 
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.= $record['invoice_no'].','.$record['inst_name'].','.$record['estb_year'].',"'.$record['main_address1'].'","'.$record['main_address2'].'","'.$record['main_address3'].'",'.$record['main_address4'].','.$record['main_district'].',"'.$record['main_city'].'","'.$record['main_state'].'","'.$record['main_pincode'].'","'.$record['inst_phone'].'","'.$record['inst_fax_no'].'",'.$record['inst_website'].','.$record['inst_head_name'].','.$record['inst_head_contact_no'].','.$record['inst_head_email'].','.$record['location_name'].','.$record['location_address'].','.$record['address1'].','.$record['address2'].','.$record['address3'].','.$record['address4'].','.$record['district'].','.$record['city'].','.$record['state'].','.$record['pincode'].','.$record['office_no'].','.$record['contact_person_name'].','.$record['contact_person_mobile'].','.$record['email_id'].','.$record['inst_type'].','.$record['due_diligence'].','.$record['status'].','.$record['cgst_rate'].','.$record['cgst_amt'].','.$record['sgst_rate'].','.$record['sgst_amt'].','.$record['cs_total'].','.$record['igst_rate'].','.$record['igst_amt'].','.$record['igst_total'].','.$record['date']."\n";
		}
		    
        $filename = "DRA_registration.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}
	public function AMP_Data()
	{
		$data = '';
		if(isset($_POST['btnSearch']))
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if(!empty($from_date))
			{
				$select = 'DISTINCT(a.id)';
				//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');
				$this->db->join('amp_payment_transaction b','b.ref_id=a.id','LEFT');
				if($from_date!='' && $to_date!='')
				{
					//$this->db->where('DATE(date) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 1 AND a.isactive = "1" ');
				}
				else if($from_date!='' & $to_date=='')
				{
					//$this->db->where('DATE(date) = "'.$from_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 12 AND a.isactive = "1" ');
				}
				$data = $this->UserModel->getRecordCount("amp_candidates a", '', '',$select);
				
				//$this->load->view('admin/dupcert_dashboard/dupcert_statistic',$data);
			}
		}
			if(empty($data))
				{
					$data = 'No data';
				}
			$total_count = $this->UserModel->getRecordCount("amp_candidates");
			$success_data = $this->master_model->getRecords('amp_candidates',array('isactive'=>'1'));
			//echo $this->db->last_query();exit;
			$result = array('data'=>$data,'total_count'=>$total_count,'success_data'=>$success_data);
			//echo '<pre>',print_r($result),'</pre>';exit;
			$this->load->view('admin/dupcert_dashboard/amp_statistic',$result);
	}
	public function download_CSV_amp()
	{
		  //exit; 
		 $csv = "invoice_no,inst_name,establish_year,main_address1,main_address2,main_address3,main_address4,main_district,main_city,main_state,main_pincode,inst_phone,inst_fax_no,inst_website,inst_head_name,inst_head_contact_no,inst_head_email,location_name,location_address,address1,address2,address3,address4,district,city,state,pincode,office_no,contact_person_name,contact_person_mobile,contact_email_id,inst_type,due_diligence,status,cgst_rate,cgst_amt,sgst_rate,sgst_amt,cs_total,igst_rate,igst_amt,igst_total,date\n";//Column headers
		
		
		$query = $this->db->query("SELECT exam_invoice.invoice_no,`inst_name`, `estb_year`,`main_address1`, `main_address2`, `main_address3`, `main_address4`, `main_district`, `main_city`, `main_state`, `main_pincode`, `inst_phone`, `inst_fax_no`, `inst_website`, `inst_head_name`, `inst_head_contact_no`, `inst_head_email`, `location_name`, `location_address`, `address1`, `address2`, `address3`, `address4`, `district`, `city`, `state`, `pincode`, `office_no`, `contact_person_name`, `contact_person_mobile`, `email_id`, `inst_type`, `due_diligence`, dra_inst_registration.status,`cgst_rate`,`cgst_amt`,`sgst_rate`,`sgst_amt`,`cs_total`,`igst_rate`,`igst_amt`,`igst_total`,DATE_FORMAT(payment_transaction.date,'%Y-%m-%d') AS date
		FROM dra_inst_registration
        LEFT JOIN payment_transaction on payment_transaction.ref_id = dra_inst_registration.id
        LEFT JOIN exam_invoice on exam_invoice.receipt_no = payment_transaction.receipt_no AND exam_invoice.transaction_no = payment_transaction.transaction_no
		WHERE dra_inst_registration.status = 1 AND payment_transaction.pay_type = 12 AND payment_transaction.status = 1 and exam_invoice.app_type = 'A'");
		
		
		$result = $query->result_array(); 
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.= $record['invoice_no'].','.$record['inst_name'].','.$record['estb_year'].',"'.$record['main_address1'].'","'.$record['main_address2'].'","'.$record['main_address3'].'",'.$record['main_address4'].','.$record['main_district'].',"'.$record['main_city'].'","'.$record['main_state'].'","'.$record['main_pincode'].'","'.$record['inst_phone'].'","'.$record['inst_fax_no'].'",'.$record['inst_website'].','.$record['inst_head_name'].','.$record['inst_head_contact_no'].','.$record['inst_head_email'].','.$record['location_name'].','.$record['location_address'].','.$record['address1'].','.$record['address2'].','.$record['address3'].','.$record['address4'].','.$record['district'].','.$record['city'].','.$record['state'].','.$record['pincode'].','.$record['office_no'].','.$record['contact_person_name'].','.$record['contact_person_mobile'].','.$record['email_id'].','.$record['inst_type'].','.$record['due_diligence'].','.$record['status'].','.$record['cgst_rate'].','.$record['cgst_amt'].','.$record['sgst_rate'].','.$record['sgst_amt'].','.$record['cs_total'].','.$record['igst_rate'].','.$record['igst_amt'].','.$record['igst_total'].','.$record['date']."\n";
		}
		    
        $filename = "AMP_registration.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}
}
