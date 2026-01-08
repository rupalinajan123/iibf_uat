<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BlendedData extends CI_Controller
{
    public $UserID;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
    }
    public function index()
    {
          $this->db->where('pay_status',1);
		  $mem_info = $this->master_model->getRecords('blended_registration','','', array('blended_id' => 'desc'));
		  $data['mem_info'] = $mem_info;
		  $this->load->view('admin/blendedDashboard/member_list',$data);
    }
   	public function memberList()
    {
	  $this->db->where('pay_status',1);
      $mem_info = $this->master_model->getRecords('blended_registration','','', array('blended_id' => 'desc'));
	  $data['mem_info'] = $mem_info;
	  $this->load->view('admin/blendedDashboard/member_list',$data);
	    
    }
	public function getMembers()
    {
		if (isset($_POST['btnSearch'])) 
		{
			$program_code = $_POST["program_code"];
			$batch_code = $_POST["batch_code"];
			$training_type = $_POST['training_type'];
			$zone_code = $_POST['zone_code'];
			$center_code = $_POST['center_code'];
			
			if($program_code != ""){$this->db->where('program_code', $program_code);}
			if($zone_code != ""){$this->db->where('zone_code', $zone_code);}
			if($center_code != ""){$this->db->where('center_code', $center_code);}
			if($batch_code != ""){$this->db->where('batch_code', $batch_code);}
			if($training_type != ""){$this->db->where('training_type', $training_type);}
			
			$this->db->where('pay_status',1);
			$mem_info = $this->master_model->getRecords('blended_registration');
			//echo $this->db->last_query();
			$data['mem_info'] = $mem_info;
			$this->load->view('admin/blendedDashboard/member_list',$data);
		}
		else
		{
			$this->db->where('pay_status',1);
		  	$mem_info = $this->master_model->getRecords('blended_registration','','', array('blended_id' => 'desc'));
		  	$data['mem_info'] = $mem_info;
		  	$this->load->view('admin/blendedDashboard/member_list',$data);
		}
		
    }
}
