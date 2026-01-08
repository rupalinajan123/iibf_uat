<?php if(!defined('BASEPATH')) exit('No direct script access allowed.');

class Chk_session extends CI_Model
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
	
	public function chk_user_session()
	{
		if($this->session->userdata('regid')!='')
		{
			$status=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'isactive');
		   if(count($status) >0)
		   {
			if($status[0]['isactive']=='0')
			{
				$user_data=array('regid'=>'','regnumber'=>'','firstname'=>'','timer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Your account has been blocked.');
				redirect(base_url().'login/');
			}
			else
			{
				return true;
			}
		}
		else
		{
			$user_data=array('regid'=>'','regnumber'=>'','firstname'=>'','timer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');
			redirect(base_url().'login/');
		}
		}else
		{
			redirect(base_url().'login/');
		}
		
		
	}
	
	public function chk_member_session()
	{
		//echo $this->session->userdata('regid');
		if($this->session->userdata('regid')!='')
		{
			$userexist=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'isactive');
		   if(count($userexist) >0)
		   {
			if($userexist[0]['isactive']=='0')
			{
				$user_data=array('regid'=>'','regnumber'=>'','firstname'=>'','timer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Your account has been blocked.');
				redirect(base_url().'login/');
			}
			else
			{
				return true;
			}
		}
		else 
		{
			$user_data=array('regid'=>'','regnumber'=>'','firstname'=>'','timer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');


			$current_date=date('ymdhis');
				$cron_file_dir = "./uploads/billdeskprocess/"; 
				$file1 = "chk_user_session_expire-" . $current_date . ".txt";
				$fp1 = fopen($cron_file_dir . '/' . $file1, 'a');
				fwrite($fp1, "\n***** session expired1 - ***** \n");

			redirect(base_url().'login/');
		}
		}else
		{
				$current_date=date('ymdhis');
				$cron_file_dir = "./uploads/billdeskprocess/"; 
				$file1 = "chk_user_session_expire-" . $current_date . ".txt";
				$fp1 = fopen($cron_file_dir . '/' . $file1, 'a');
				fwrite($fp1, "\n***** session expired2 - ***** \n");

			redirect(base_url().'login/');
		}
	}
	
	public function chk_non_member_session()
	{
		if($this->session->userdata('nmregid')!='')
		{
		   $userexist=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid')),'isactive');
		   if(count($userexist) >0)
		   {
				if($userexist[0]['isactive']=='0')
				{
				$user_data=array('nmregid'=>'','nmregnumber'=>'','nmfirstname'=>'','nmtimer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Invalid credential.');
				//redirect(base_url().'nonreg/examlist/?Extype='.base64_encode('1').'&Mtype='.base64_encode('NM'));
				redirect(base_url());
				}
				else
				{
					return true;
				}
		}
		else
		{
			$user_data=array('nmregid'=>'','nmregnumber'=>'','nmfirstname'=>'','nmtimer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');
			redirect(base_url());
		}
		}else
		{
			redirect(base_url());
		}
	}
	
	####CSC session check########
/*	public function chk_cscnon_member_session()
	{ 
		if($this->session->userdata('cscnmregid')!='')
		{
		   $userexist=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid')),'isactive');
		   if(count($userexist) >0)
		   {
				if($userexist[0]['isactive']=='0')
				{
				$user_data=array('cscnmregid'=>'','cscnmregnumber'=>'','cscnmfirstname'=>'','cscnmtimer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Invalid credential.');
				//redirect(base_url().'nonreg/examlist/?Extype='.base64_encode('1').'&Mtype='.base64_encode('NM'));
				redirect(base_url());
				}
				else
				{
					return true;
				}
		}
		else
		{
			$user_data=array('cscnmregid'=>'','cscnmregnumber'=>'','cscnmfirstname'=>'','cscnmtimer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');
			redirect(base_url());
		}
		}else
		{
			redirect(base_url());
		}
	}*/
	
	public function chk_cscnon_member_session()
	{ 
		if($this->session->userdata('cscnmregid')!='')
		{
		   $userexist=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid')),'isactive');
		   if(count($userexist) >0)
		   {
				if($userexist[0]['isactive']=='0')
				{
				$user_data=array('cscnmregid'=>'','cscnmregnumber'=>'','cscnmfirstname'=>'','cscnmtimer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Invalid credential.');
				//redirect(base_url().'nonreg/examlist/?Extype='.base64_encode('1').'&Mtype='.base64_encode('NM'));
				redirect(base_url());
				}
				else
				{
					return true;
				}
		}
		else
		{
			$user_data=array('cscnmregid'=>'','cscnmregnumber'=>'','cscnmfirstname'=>'','cscnmtimer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');
			redirect(base_url());
		}
		}else
		{
			redirect(base_url());
		}
	}
	
	//if member/non member logged in redirect to dashbored, if these are access from outer pages
	public function checklogin()
	{
		if($this->session->userdata('regid')!='')
		{
		   $userexist=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'isactive');
		   if(count($userexist) >0)
		   {
		  	if($userexist[0]['isactive']=='1')
			{
				redirect(base_url().'Home/dashboard');
			}
		  }
		}
	 }
	
	
	//if member/non member logged in redirect to dashbored, if these are access from outer pages
	public function checklogin_external()
	{
		if($this->session->userdata('mregid_applyexam')!='')
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val)
			{
				$this->session->unset_userdata($key);    
			}
			redirect(base_url());
		}
	}
	
	//check CSC user logged in
	public function checklogin_CSCexternal()
	{
		if($this->session->userdata('cscregid_applyexam')!='')
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val)
			{
				$this->session->unset_userdata($key);    
			}
			redirect(base_url());
		}
	}
	
	//ELearning if member/non member logged in redirect to dashbored, if these are access from outer pages
	public function checklogin_Elearning()
	{
		if($this->session->userdata('eregid')!='')
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val)
			{
				$this->session->unset_userdata($key);    
			}
			redirect(base_url());
		}
	}
	
	//Check for multiple session login for external
	public function Mem_checklogin_external_user()
	{
		return true;
		/*if($this->session->userdata('mregid_applyexam')!='')
		{
			if ($this->session->userdata('regid')!='')
            {
	      	  redirect(base_url());
            }
			else
			{
				return true;
			}
		}
		else
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
			redirect(base_url());
		}*/
	}
	
	//Check for multiple session login for CSC  usert
	public function Mem_checklogin_external_CSCuser()
	{
		return true;
		/*if($this->session->userdata('mregid_applyexam')!='')
		{
			if ($this->session->userdata('regid')!='')
            {
	      	  redirect(base_url());
            }
			else
			{
				return true;
			}
		}
		else
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
			redirect(base_url());
		}*/
	}
	
	#####ELearning####
	public function ELearn_Mem_checklogin_external_user()
	{
		if($this->session->userdata('eregid')!='')
		{
				return true;
		}
		else
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
			redirect(base_url());
		}
	}
	
	
	//check multiple session alive
	public function Check_mult_session()
	{
		$cnt=0;
		if($this->session->userdata('mregid_applyexam')!=''){$cnt=$cnt+1;}
		if ($this->session->userdata('regid')!=''){$cnt=$cnt+1; }
		if ($this->session->userdata('nmregid')!=''){$cnt=$cnt+1;}
		if ($this->session->userdata('dbregid')!=''){$cnt=$cnt+1;}
		if ($this->session->userdata('eregid')!=''){$cnt=$cnt+1;}
		if ($this->session->userdata('cscnmregid')!=''){$cnt=$cnt+1;}
		if($cnt>1)
		{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val)
			{
				$this->session->unset_userdata($key);    
			}
			redirect(base_url());
		}
		else
		{
			return true;
		}
	}
	
	
	
	//if user profile photo not exist
	public function checkphoto()
	{
	$user_images=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'),'scannedphoto,scannedsignaturephoto,idproofphoto');
	if(count($user_images) > 0)
		{
			 if(!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) && !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) && !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']))
			{
				redirect(base_url().'Home/profile/');
			}
		}
	}
	
	//check dbf user session
	public function chk_dbf_member_session()
	{
		if($this->session->userdata('dbregid')!='')
		{
		   $userexist=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid')),'isactive');
		   if(count($userexist) >0)
		   {
				if($userexist[0]['isactive']=='0')
				{
				$user_data=array('dbregid'=>'','dbregnumber'=>'','dbfirstname'=>'','dbtimer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Invalid credential.');
				//redirect(base_url().'nonreg/examlist/?Extype='.base64_encode('1').'&Mtype='.base64_encode('NM'));
				redirect(base_url());
				}
				else
				{
					return true;
				}
		}
		else
		{
			$user_data=array('dbregid'=>'','dbregnumber'=>'','dbfirstname'=>'','dbtimer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');
			redirect(base_url());
		}
		}else
		{
			redirect(base_url());
		}
	}

/// check bank login session
	public function chk_bank_login_session()
	{
		if($this->session->userdata('institute_id')!='')
		{
			$userexist=$this->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$this->session->userdata('institute_id')),'accerdited_delete');
		   if(count($userexist) >0)
		   {
			if($userexist[0]['accerdited_delete']=='1')
			{
				$user_data=array('institute_id'=>'','institude_name'=>'','timer'=>'');
				$this->session->unset_userdata($user_data);
				$this->session->set_flashdata('error_message','Your account has been blocked.');
				redirect(base_url().'bulk/Banklogin/');
			}
			else
			{
				return true;
			}
		}
		else
		{
			$user_data=array('institute_id'=>'','institude_name'=>'','timer'=>'');
			$this->session->unset_userdata($user_data);
			$this->session->set_flashdata('error_message','Invalid credential.');
			redirect(base_url().'bulk/Banklogin/');
		}
		}else
		{
			redirect(base_url().'bulk/Banklogin/');
		}
	}	
}