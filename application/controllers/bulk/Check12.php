<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Check12 extends CI_Controller {
	public function __construct()
	{
		parent::__construct(); 
		
}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function index()
	 {
		echo 'in';	
	}

	public function applicationForm()
	{
		print_r($this->session->all_userdata());
		echo '**'.$this->session->userdata('examcode_name');
	}
	
}
