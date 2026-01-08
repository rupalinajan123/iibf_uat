<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class PrizeDashboard extends CI_Controller 
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
			$data = '';
			$this->db->order_by('id','DESC');
			$success_data = $this->Master_model->getRecords('prizewinners_registration','');
			
			//total count
			//$this->db->order_by('id','DESC');
			$total_count = $this->Master_model->getRecordCount('prizewinners_registration','');
			
			//echo $this->db->last_query(); exit;
			$data['success_data'] = $success_data;
			$data['total_count'] = $total_count;
			//print_r($data['success_data']); exit;
			$this->load->view('prize_winner_module/prize_dashboard',$data);
		}

public function search_record()
{
			if(isset($_POST['btnSearch']))
			{			
			$from_date = $to_date = '';			
			if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
			//$data = '';
			//$from_date =$this->input->post('from_date');
			//$to_date =$this->input->post('to_date');
			//$this->db->order_by('id','DESC');
			$this->db->where('created_on BETWEEN "'. $from_date.'" and "'.$to_date.'"');
			$success_data = $this->Master_model->getRecords('prizewinners_registration');
			//total count
			$this->db->where('created_on BETWEEN "'. $from_date.'" and "'.$to_date.'"');
			$total_count = $this->Master_model->getRecordCount('prizewinners_registration','');
			$data['success_data'] = $success_data;
			$data['total_count'] = $total_count;
			//print_r($data['success_data']); exit;
			$this->load->view('prize_winner_module/prize_dashboard',$data);
			
	
		}
}
Public function download_csv()
{
 //csv download

				if(isset($_POST['search_on_fields']))
				{
						$from_date = $to_date = '';		
						if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
						if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }

						$this->db->where('created_on BETWEEN "'. $from_date.'" and "'.$to_date.'"');
						$success_data = $this->Master_model->getRecords('prizewinners_registration');
						//$csv .="\n\n\n\n";
						$csv .= "Regnumber, Namesub, Firstname, Lastname, Moblie, Email, Bankname, Branchname, Ifs_code, Account_type, Account_no, Created_on \n";
				
				
							foreach($success_data as $record)
							{
								$csv.= $record['regnumber'].",".$record['namesub'].",".$record['firstname'].",".$record['lastname'].",".$record['moblie'].",".$record['email'].",".$record['bankname'].",".$record['branchname'].",".$record['ifs_code'].",".$record['account_type'].",".$record['account_no'].",".$record['created_on']."\n";
							}
					//echo $final_str; exit;
						$filename = "prize-winner".date("YmdHis").".csv";
						header('Content-type: application/csv');
						header('Content-Disposition: attachment; filename='.$filename);
						$csv_handler = fopen('php://output', 'w');
						fwrite ($csv_handler,$csv);
						fclose ($csv_handler);	 

				}
						
}




}
	