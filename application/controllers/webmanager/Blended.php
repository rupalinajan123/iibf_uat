<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Blended extends CI_Controller 
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
			$count = $batch_code = $from_date = $to_date = '';
						
			if(isset($_POST['submit']))
			{
				$this->form_validation->set_rules('batch_code', 'Batch Code', 'trim|required', array('required'=>"Please select the %s"));
				if ($this->form_validation->run() == TRUE)
				{
					$batch_code = $_POST['batch_code'];
					if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
					if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }				
					
					$this->db->where('pay_status','1');	
					$this->db->where('batch_code',$batch_code);
					if($from_date != "") { $this->db->where('createdon >=', $from_date." 00:00:00"); }
					if($to_date != "") { $this->db->where('createdon <=', $to_date." 23:59:59"); }
					
					$count = $this->master_model->getRecordCount('blended_registration');
				}
			}
			
			$data['batch_code_data'] = $this->master_model->getRecords('dashboard_batch_code', '', 'batch_code', array('batch_code'=>'DESC'));
			$data['count'] = $count;
			$data['batch_code'] = $batch_code;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$this->load->view('webmanager/blended',$data);
		}
		
		function add_new_batch_code_ajax()
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$new_batch_code = trim($this->input->post('new_batch_code'));
				$this->db->where('batch_code',$new_batch_code); 
				$chk_exist = $this->master_model->getRecordCount('dashboard_batch_code');
				
				if($chk_exist == 0)
				{				
					$result['flag'] = "success";
					
					$add_data['batch_code'] = $new_batch_code;
					$this->master_model->insertRecord('dashboard_batch_code', $add_data, true); 
					
					$result['message'] = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>Batch Code successfully added</div>';
					$batch_code_data = $this->master_model->getRecords('dashboard_batch_code', '', 'batch_code', array('batch_code'=>'DESC'));
					
					$sel_batch_code = trim($this->input->post('sel_batch_code'));
					
					$batch_code_sel = '<select name="batch_code" id="batch_code" class="form-control chosen-select">
																<option value="">Select Batch Code</option>';
																if(count($batch_code_data) > 0)
																{
																	foreach($batch_code_data as $row)
																	{
																		$selected_val = '';
																		if($sel_batch_code != "" && $sel_batch_code == $row['batch_code']) { $selected_val = 'selected'; }$batch_code_sel .= '<option value="'.$row['batch_code'].'" '.$selected_val.'>'.$row['batch_code'].'</option>';	
																	}
																}
					$batch_code_sel .= '</select>';
												
					$result['batch_code_sel'] = $batch_code_sel;
				}
				else
				{
					$result['flag'] = "error";
					$result['message'] = "Batch Code already exist in system";
				}
			}
			else
			{
				$result['flag'] = "error";
				$result['message'] = "";
			}
			echo json_encode($result);
		}
	}		