<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Fedai extends CI_Controller 
	{		
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('master_model');		
	}

  function fedai_api_curl_qa($exam_code=0, $exam_period=0, $member_number=0)
  {
    $res = $this->master_model->fedai_api_curl($exam_code, $exam_period, $member_number);
    echo json_encode($res);
  }

  function fedai_institute_api_curl_qa($exam_code=0, $exam_period=0)
  {
    $res = $this->master_model->fedai_institute_api_curl($exam_code, $exam_period);
    // print_r($res); exit;
    echo json_encode($res);
  }
}	