<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller 
{
	function __construct ()
    {
        parent::__construct();
        // Check authentication
		if(!isset($this->session->adminid) || empty($this->session->adminid))
		{
            redirect('admin/login');
        }
	}
}
