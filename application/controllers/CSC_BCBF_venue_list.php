<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	class CSC_BCBF_venue_list extends CI_Controller
	{
    public function __construct()
    //exit;
    {
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			//$this->load->helper('exam_invoice_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
		}
		
    /* Showing CSC_BCBF_venue_list Form */
    public function index()
    {
				$center_listing=array();
				$states = $this->master_model->getRecords('state_master');
				
				$this->db->where('exam_name',991);
				$this->db->where('exam_period',998);
				$this->db->where('center_delete', '0');
				$centers  = $this->master_model->getRecords('center_master');
				
				
				if (isset($_POST['btnSubmit'])) {
				    $this->form_validation->set_rules('main_state', 'State', 'trim|required|xss_clean');
                    $this->form_validation->set_rules('main_center', 'Center', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == TRUE) {
                        
                        $centerCode= $_POST['main_center'];
                		$state_code= $_POST['main_state'];
                		
                		$query='(exam_date = "0000-00-00" OR exam_date = "")';
    					$this->db->where($query);           
    					$this->db->where('session_time=','');
    					//$this->db->group_by('venue_code');
    					$center_listing=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code,venue_flag');
    					//print_r($data['center_listing']);die;
    					
    					$this->db->where('exam_name',991);
				        $this->db->where('exam_period',998);
				        $this->db->where('center_delete', '0');
				        $this->db->where('state_code',$state_code);
				        $centers  = $this->master_model->getRecords('center_master');
				
				
                    }
				}
				
			
			    	$data = array(
					'middle_content' => 'CSC_BCBF_venue_list_reg',
					'states' => $states,
					'centers'=>$centers,
					'center_listing' => $center_listing
					);
					$this->load->view('CSC_BCBF_venue_list', $data);
				
		}
    	public function getCenter() 
	{
        if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
		{
            $state_code = $this->security->xss_clean($this->input->post('state_code'));
            $result = $this->master_model->getRecords('center_master', array('state_code' => $state_code,'center_delete' => 0, 'exam_name' => 991,'exam_period' => 998,));
			if ($result) 
			{
				echo '<option value="">- Select - </option>';
				foreach ($result AS $data) 
				{
					if ($data) 
					{
						echo '<option value="' . $data['center_code'] . '">' . $data['center_name'] . '</option>';
					}
				}
			} 
			else 
			{
				echo '<option value="">Center Not Available, Please select other state</option>';
			}
            
        }
    }
    } 	