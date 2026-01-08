<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
		if ($this->session->userdata('username')) {
			redirect(site_url('count_dashboard/Count_list'));
		}

		if (isset($_POST['submit'])) {
			$this->form_validation->set_rules('Username', 'Username', 'trim|required|alpha_numeric|max_length[30]|xss_clean', array('required' => 'Please enter the %s'));
			$this->form_validation->set_rules('Password', 'Password', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));

			if ($this->form_validation->run()) {
				if ($this->input->post('Username') == 'countadmin' && $this->input->post('Password') == 'countadmin') {
					$sessionData['username'] = 'countadmin';
					$sessionData['password'] = 'countadmin';
					$sessionData['active'] = '1';
					$sessionData['deleted'] = '0';
					$sessionData['user_type'] = 'admin';
					$this->session->set_userdata('sessionData', $sessionData);
					redirect(site_url('count_dashboard/Count_list'));
				} else {
					$sessionData['username'] = $this->input->post('Username');
					$sessionData['password'] = $this->input->post('Password');
					$sessionData['active'] = '1';
					$sessionData['deleted'] = '0';
					$sessionData['user_type'] = 'vendor';
					$this->session->set_userdata('sessionData', $sessionData);
					redirect(site_url('count_dashboard/Count_list/SearchQry'));
					//$data['error'] = 'Invalid Credentials';
				}
			}
		}
		$this->load->model('Captcha_model');
		$data['captcha_img'] = $this->Captcha_model->generate_captcha_img('LOGIN_COUNT_FORM');

		$this->load->view('count_dashboard/login', $data);
	}

	public function Logout()
	{
		$sessionData = $this->session->userdata('username');
		$this->session->unset_userdata('username');
		redirect(site_url('count_dashboard/login'));
	}
}
