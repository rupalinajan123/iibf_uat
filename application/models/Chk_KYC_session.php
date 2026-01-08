<?php if(!defined('BASEPATH')) exit('No direct script access allowed.');

class Chk_KYC_session extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
/*	public function chk_admin_session()
	{
		if($this->session->userdata('admin_id')!='' && $this->session->userdata('admin_email')!='' &&  $this->session->userdata('admin_name')!='')
		{
			return true;
		}
		else
		{
			redirect(admin_url());
		}
	}*/
//recommender login	
	
	public function chk_recommender_session()
	{
		if($this->session->userdata('kyc_id')=='' || $this->session->userdata('role')=='' || $this->session->userdata('role')!='recommender')
		{
		  redirect(base_url().'admin/kyc/login');
		}
		else
		{
			return true;
		}
	}

//approver loging 
	public function chk_approver_session()
	{
		
		if($this->session->userdata('kyc_id')=='' || $this->session->userdata('role')=='' || $this->session->userdata('role')!='approver')
		{
		  redirect(base_url().'admin/kyc/login');
		}
		else
		{
			return true;
		}
	}



	
	
	
	
}