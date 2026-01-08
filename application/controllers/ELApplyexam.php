<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ELApplyexam extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		redirect(base_url().'RELApplyexam/exapplylogin'); 	
	}

	
	
	public function exapplylogin1(){
		exit;
		redirect(base_url().'RELApplyexam/exapplylogin');
	}
	
	
}
