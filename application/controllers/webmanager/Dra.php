<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Dra extends CI_Controller 
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
			if(!$this->session->userdata('username')) { redirect(site_url('webmanager/login/logout')); }			
			$count = $from_date = $to_date = '';
			
			if(isset($_POST['submit']))
			{
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
				
        $this->db->join('dra_payment_transaction dpt', 'dpt.receipt_no = ei.receipt_no', 'INNER');
				$this->db->where('ei.exam_period','199');
				$this->db->where('ei.exam_code','45');
				$this->db->where('ei.invoice_no !=','');
				$this->db->where('ei.app_type','I');				
				if($from_date != "") { $this->db->where('ei.date_of_invoice >=', $from_date." 00:00:00"); }
				if($to_date != "") { $this->db->where('ei.date_of_invoice <=', $to_date." 23:59:59"); }
				
				$count = $this->master_model->getRecordCount('exam_invoice ei');
			}
			
			$data['count'] = $count;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$this->load->view('webmanager/dra',$data);
		}
	}		