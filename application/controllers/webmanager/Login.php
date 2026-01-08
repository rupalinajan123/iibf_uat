<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Login extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			//$this->load->model('LoginModel');
			//$this->load->model('master_model');
			//$this->load->model('CareerAdminModel');
			//$this->load->helper('general_helper');
			$this->load->library('session');    
		}
		
		public function index()
		{
			$data['error'] = "";
			if($this->session->userdata('username')) { redirect(site_url('webmanager/examdashboard')); }
			
			if(isset($_POST['submit'])) 
			{ 
				$this->form_validation->set_rules('Username', 'Username', 'trim|required|alpha_numeric|max_length[30]|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('Password', 'Password', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));
				
				if($this->form_validation->run())
				{
					if($this->input->post('Username') == 'iibfadmin' || 
					$this->input->post('Username') == 'DeanPeters' || 
					$this->input->post('Username') == 'ShobitJain' || 
					$this->input->post('Username') == 'RajdeepBose' || 
					$this->input->post('Username') == 'AKshrivastava' || 
					$this->input->post('Username') == 'PritamKhar' ||
					$this->input->post('Username') == 'ITteam' && 
					$this->input->post('Password') == 'iibfadmin')
					{
						$sessionData['username'] = 'test';
						$sessionData['password'] = 'supp0rt';
						$sessionData['active'] = '1';
						$sessionData['deleted'] = '0';
						$this->session->set_userdata($sessionData);						
						redirect(site_url('webmanager/examdashboard'));
					}	
					else
					{
						$data['error'] = 'Invalid Credentials';
					}
				}
			} 
			
			$this->load->view('webmanager/login',$data);		
		}
		
		public function Logout()
		{
			$sessionData = $this->session->userdata('username');
			$this->session->unset_userdata('username');
			redirect(site_url('webmanager/login'));
		} 
	}	