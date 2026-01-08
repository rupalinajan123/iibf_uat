<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Member_renewal extends CI_Controller 
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
			$registration_type = array();
			
			if(isset($_POST['submit']))
			{
				$this->form_validation->set_rules('registration_type[]', 'Registration Type', 'trim|required', array('required'=>"Please select the %s"));
				if ($this->form_validation->run() == TRUE)
				{
					$registration_type = $_POST['registration_type'];
					if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
					if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
					
					$this->db->where('isactive','1'); 
					$this->db->where('isdeleted','0'); 
					$this->db->where('is_renewal','1'); 
					
					if(isset($registration_type) && count($registration_type) > 0) { $this->db->where_in('registrationtype',$registration_type); }
					if($from_date != "") { $this->db->where('createdon >=', $from_date." 00:00:00"); }
					if($to_date != "") { $this->db->where('createdon <=', $to_date." 23:59:59"); }
					$count = $this->master_model->getRecordCount('member_registration');			
				}
			}
			
			$data['registration_type_data'] = $this->master_model->getRecords('dashboard_registration_type', '', 'registration_type', array('registration_type'=>'ASC'));
			$data['count'] = $count;
			$data['registration_type'] = $registration_type;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$this->load->view('webmanager/member_renewal',$data);
		}
	}		