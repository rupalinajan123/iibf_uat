<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Contact_class extends CI_Controller 
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
			$count = $course_name = $course_period = $from_date = $to_date = '';
			
			$data['course_data'] = $course_data = $this->master_model->getRecords('contact_classes_cource_master', array('isactive'=>1), 'course_code, course_name', array('course_name'=>'ASC'));
						
			if(isset($_POST['submit']))
			{
				$this->form_validation->set_rules('course_name', 'Course Name', 'trim|required', array('required'=>"Please select the %s"));
				$this->form_validation->set_rules('course_period', 'Course Period', 'trim|required', array('required'=>"Please select the %s"));
				if($this->form_validation->run() == TRUE)
				{
					$course_name = $_POST['course_name'];
					$course_period = $_POST['course_period'];
					if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
					if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }				
					
					$this->db->where('pay_status','1'); 
					$this->db->where('program_code',$course_name); 
					$this->db->where('program_prd',$course_period); 
					
					if($from_date != "") { $this->db->where('createdon >=', $from_date." 00:00:00"); }
					if($to_date != "") { $this->db->where('createdon <=', $to_date." 23:59:59"); } 
					
					$count = $this->master_model->getRecordCount('contact_classes_registration');					
				}
			}
			
			$data['course_period_data'] = $this->master_model->getRecords('dashboard_course_period', '', 'course_period', array('course_period'=>'DESC'));
			$data['count'] = $count;
			$data['course_name'] = $course_name;
			$data['course_period'] = $course_period;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$this->load->view('webmanager/contact_class',$data);
		}
		
		function add_new_course_period_ajax()
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$new_course_period = trim($this->input->post('new_course_period'));
				$this->db->where('course_period',$new_course_period); 
				$chk_exist = $this->master_model->getRecordCount('dashboard_course_period');
				
				if($chk_exist == 0)
				{				
					$result['flag'] = "success";
					
					$add_data['course_period'] = $new_course_period;
					$this->master_model->insertRecord('dashboard_course_period', $add_data, true); 
					
					$result['message'] = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>Course period successfully added</div>';
					$course_period_data = $this->master_model->getRecords('dashboard_course_period', '', 'course_period', array('course_period'=>'DESC'));
					
					$sel_course_prd = trim($this->input->post('sel_course_prd'));
					
					$course_period_sel = '<select name="course_period" id="course_period" class="form-control chosen-select">
																<option value="">Select Course Period</option>';
																if(count($course_period_data) > 0)
																{
																	foreach($course_period_data as $row)
																	{
																		$selected_val = '';
																		if($sel_course_prd != "" && $sel_course_prd == $row['course_period']) { $selected_val = 'selected'; }$course_period_sel .= '<option value="'.$row['course_period'].'" '.$selected_val.'>'.$row['course_period'].'</option>';	
																	}
																}
					$course_period_sel .= '</select>';
												
					$result['course_period_sel'] = $course_period_sel;
				}
				else
				{
					$result['flag'] = "error";
					$result['message'] = "Course period already exist in system";
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