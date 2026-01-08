<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Monthlycount extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
    }
    public function index(){ //echo "hello"; die;
		
		$this->load->view('admin/MonthlyCountDashboard/module_list');
    }
	public function date_count()//echo "hello"; die;
	{
		$currentdate = $this->uri->segment(5);
		$module_info = $this->master_model->getRecords('pay_type_master');
		//$module_info = $this->master_model->getRecords('pay_type_master');
		$data['module_info'] = $module_info;
		$data['currentdate'] = $currentdate;
		$this->load->view('admin/MonthlyCountDashboard/count_list',$data);
	}
 }  