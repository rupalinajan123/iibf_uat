<?php	
  /********************************************************************
  * Description: Controller for E-learning separate module data for client. Download member subject data using date filter
  * Created BY: Sagar Matale
  * Created On: 19-07-2021
  * Update By: 
  * Updated on:  
	********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Elearning_spm_data extends CI_Controller 
	{	
		public function __construct()
		{
			parent::__construct();
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			$this->load->model('master_model');		
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			$this->load->model('chk_session');
			$this->load->helper('cookie');
			$this->load->model('log_model');
			$this->load->model('KYC_Log_model'); 
			$this->chk_session->Check_mult_session();
			//exit; 
		}	
    
    public function index($from_date='', $to_date='', $regnumber='')
		{
			if($from_date == '') { $from_date = date("Y-m-d"); }
			if($to_date == '') { $to_date = date("Y-m-d"); }
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['regnumber'] = $regnumber;
			
			$data['result_data'] = $this->get_exam_data_common($from_date, $to_date, $regnumber); 
			$this->load->view('apply_elearning/elearning_spm_data_listing', $data);
			
			/* if(count($qry)> 0)
			{
				echo '<style>table { border-collapse:collapse; }  td, th { border:1px solid #000; padding:4px 10px; }</style>
							<br><br>
							<table>
								<thead><th>Sr No</th><th>Date</th><th>Receipt Number</th><th>Payment Status</th><th>Payment Transaction No</th><th>SBI Status</th><th>SBI Transaction No</th><th>Description</th><th>Callback</th></thead>
								<tbody>';
				
				$i = 1;
				foreach($qry as $res)
				{
					$responsedata = sbiqueryapi($res['receipt_no']);
					
					$sbi_status = $responsedata[2];
					if($sbi_status != 'SUCCESS') { $bg_color = 'background:#ccc'; } else { $bg_color = ''; }
					echo '<tr style="'.$bg_color.'">';
					echo '<td>'.$i.'</td>';
					echo '<td>'.$res['created_on'].'</td>';
					echo '<td>'.$res['receipt_no'].'</td>';
					echo '<td>'.$res['p_status'].'</td>';
					echo '<td>'.$res['transaction_no'].'</td>';
					echo '<td>'.$sbi_status.'</td>';
					echo '<td>'.$responsedata[1].'</td>';
					echo '<td>'.$responsedata[8].'</td>';
					echo '<td>'.$res['callback'].'</td>';
					echo '</tr>';
					
					$i++;
				}
				
				echo '	</tbody>
							</table>';
			} */ 		
		}
		
		function get_exam_data_common($from_date='', $to_date='', $regnumber='')
		{
			if($from_date != "" && $to_date != "")
			{
				$this->db->join('payment_transaction pt', 'pt.id = ms.pt_id', 'LEFT', FALSE);
				$this->db->join('spm_elearning_registration er', 'er.regid = ms.regid', 'LEFT', FALSE);
				$this->db->where("DATE(ms.created_on) BETWEEN '$from_date' AND '$to_date'");
				if($regnumber != "") { $this->db->where('ms.regnumber', $regnumber); }
				$this->db->order_by('created_on ASC'); 
				$result_data = $this->master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1','pt.status' => '1', 'pt.pay_type'=>'20'), 'er.namesub, er.firstname, er.middlename, er.lastname, er.email, er.mobile, er.state, ms.regnumber, ms.exam_code, ms.subject_code, ms.subject_description, ms.created_on'); 
			}
			else { $result_data = array(); }
			
			return $result_data;
		}
		
		public function elearning_spm_data_download_CSV($from_date='', $to_date='', $regnumber='')
		{
			if($from_date == '') { $from_date = date("Y-m-d"); }
			if($to_date == '') { $to_date = date("Y-m-d"); }
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			
			$result_data = $this->get_exam_data_common($from_date, $to_date, $regnumber);
			
			$csv = "Sr. No, Name Sub, First Name, Middle Name, Last Name, Email, Mobile, State, Regnumber, Exam Code, Subject Code, Subject Name, Date \n";
			if(count($result_data) > 0)
			{		
				$sr_no = 1;
				foreach($result_data as $row)
				{	
					 $csv.= $sr_no.",".$row['namesub'].",".$row['firstname'].",".$row['middlename'].",".$row['lastname'].",".$row['email'].",".$row['mobile'].",".$row['state'].",".$row['regnumber'].",".$row['exam_code'].",".$row['subject_code'].",".$row['subject_description'].",".date("Y-m-d h:ia", strtotime($row['created_on']))."\n"; 
					 $sr_no++;
				}
			}
					
			$filename = "elearning_spm_data_".date("YmdHis").".csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite ($csv_handler,$csv);
			fclose ($csv_handler);
		}
		
	} 			