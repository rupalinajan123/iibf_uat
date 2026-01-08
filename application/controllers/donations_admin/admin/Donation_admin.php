<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Donation_admin extends CI_Controller {	
	public $UserID;			
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('donation_admin')) 
		{
			//$this->session->set_flashdata('error','Invalid');
			redirect('donations_admin/admin/Login');
		}
		else
		{
			$UserData = $this->session->userdata('donation_admin');
			
			/*if($UserData['admin_user_type'] == 'Cheker')
			{
				redirect('careers_admin/admin/Login');
			}*/
		}
		$this->UserData = $this->session->userdata('donation_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
		$this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->library('upload');
		$this->load->library('m_pdf');
	}
	
	public function donation_admin_list()
	{	
    $data['result']  = array();
    $data['action']  = array();
    $data['links']   = '';
    $data['success'] = '';
    $field           = '';
    $value           = '';
    $sortkey         = '';
    $sortval         = '';
    $per_page        = '';
    $limit           = 10;
    $start           = 0;
    $data_arr        = array();
    $edu_arr         = array();
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		//$data = $this->getUserInfo();
		$act_stat = 1;
		$res_arr  = array();
		$data["breadcrumb"] = '<ol class="breadcrumb"> 
		<li><a href="'.base_url().'donations_admin/admin/Donation_admin/donation_admin_list">
		<i class="fa fa-home"></i> Candidate List</a></li>
		<li class="active"><a href="'.base_url().'donations_admin/admin/Donation_admin/donation_admin_list">Donation Admin</a></li>
		</ol>';		
   
		$donationadminuserdata = $this->session->userdata('donation_admin');
		$id = $donationadminuserdata['id'];
		$this->db->where('id',$id);
		$res_arr = $this->master_model->getRecords("tbl_donation");
		
		foreach($res_arr as $rec)
   		{  

		  $data_arr['name']       = $rec['name'];
		  $data_arr['username']        = $rec['username'];
		  $data_arr['password']       = $rec['password'];
		  $data_arr['no_of_days']         = $rec['no_of_days'];
		  $data_arr['donate_salary']            = $rec['donate_salary'];
		  $data_arr['amount']= $rec['amount'];
		  $data_arr['donation_type']= $rec['donation_type'];
		  $data_arr['isactive']= $rec['isactive'];
		  
		
		}
		
		$data['member_data'] = $data_arr;	
		
		 
		/*$length = 10;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, $charactersLength - 1)];	
		}
		$this->db->limit(1);
		$res_arr1 = $this->master_model->getRecords("tbl_donation");
		$donation_data = array('password'=>$randomString,'pass_up'=>'1');
		$this->db->limit(1);
		$this->master_model->updateRecord('tbl_donation',$donation_data,array('pass_up'=>'0'));*/
			
		
		$this->load->view('donations_admin/admin/donation_admin_list',$data);
	}

	
	public function update_details()
	{
		
		if (isset($_POST['btnSubmit'])) 
		{
			if(($_POST['donation_type'] == 'number_of_days_type' && $_POST["no_of_days"] == 0) || ($_POST['donation_type'] == 'amount_type' && $_POST["amount"] == 0))
			{
				$data['err_sms'] = "Please enter valid value";
			}
			else
			{
				$id = $_POST["id"];
				$no_of_days = $_POST["no_of_days"];
				$current_date  = date("Y-m-d");
				$isactive = 5;
				$donate_salary = $_POST["donate_salary"];
				$user_IP_address = $_SERVER['REMOTE_ADDR'];
				$amount = $_POST['amount'];
				$donation_type= $_POST['donation_type'];
				
				if($donate_salary == 'No')
				{
					$no_of_days = 0;
					$amount = 0;
					$donation_type = 'NA';
					$donate_salary == 'No';
				}
				
				$donation_data = array('donation_type'=>$donation_type,'amount'=>$amount,'donate_salary' => $donate_salary,'no_of_days'=>$no_of_days, 'submit_date' => $current_date,'isactive'=>$isactive,'user_IP_address'=>$user_IP_address);
				$this->master_model->updateRecord('tbl_donation',$donation_data,array('id'=>$id));
				if($donate_salary == 'Yes')
				{
					$data['sms'] = "<h1>Thank you for your contribution</h1>";
				}
			}
		}
		
		$donationadminuserdata = $this->session->userdata('donation_admin');
		$id = $donationadminuserdata['id'];
		$this->db->where('id',$id);
		$res_arr = $this->master_model->getRecords("tbl_donation");
		
		foreach($res_arr as $rec)
   		{  
			$data_arr['name']       = $rec['name'];
			$data_arr['username']        = $rec['username'];
			$data_arr['password']       = $rec['password'];
			$data_arr['no_of_days']         = $rec['no_of_days'];
			$data_arr['donate_salary']            = $rec['donate_salary'];
			$data_arr['amount']= $rec['amount'];
			$data_arr['donation_type']= $rec['donation_type'];
			$data_arr['isactive']= $rec['isactive'];
		}
		$data['member_data'] = $data_arr;	
		$this->load->view('donations_admin/admin/donation_admin_list',$data);
	} 
} 
?>