<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Search extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}		
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
	}
	
	public function search_success()
	{
		$this->session->set_userdata('searchBy','');
		$this->session->set_userdata('searchText','');
		if($this->session->flashdata('error')!='')
		{ 
			$this->session->set_flashdata('error','Error while sending email !!');
		}
		else if($this->session->flashdata('success')!='')
		{ 
			$this->session->set_flashdata('success','Email sent successfully !!');
		}
		redirect(base_url().'admin/Search/success');
	}
	
	public function search_failure()
	{
		$this->session->set_userdata('searchBy','');
		$this->session->set_userdata('searchText','');
		if($this->session->flashdata('error')!='')
		{ 
			$this->session->set_flashdata('error','Error while sending email !!');
		}
		else if($this->session->flashdata('success')!='')
		{ 
			$this->session->set_flashdata('success','Email sent successfully !!');
		}
		redirect(base_url().'admin/Search/failure');
	}
	
	
	// Success Member Registrations
	public function success()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$data['result'] = array();
		$per_page = 10;
		$last = $this->uri->total_segments();
		$start = 0;
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$searchText = '';
		$searchBy = '';
		
		if($page!=0)
		{	$start = $page-1;	}

		$where = " `isactive` = '1' AND `isdeleted` = 0 AND `pay_type` = 1 AND `b`.`status` = 1";
		
		// Set session for the Search crieteria 
		if($this->session->userdata('searchBy')=='')
		{
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
				$searchBy = trim($_POST['searchBy']);
			else
				$searchBy = '';
			$this->session->set_userdata('searchBy',$searchBy);
		}
		else
		{
			if(isset($_POST['searchBy']) && $_POST['searchBy'] != $this->session->userdata('searchBy'))
			{
				$searchBy = trim($_POST['searchBy']);
				$this->session->set_userdata('searchBy',$searchBy);
			}
		}
		$searchBy = $this->session->userdata('searchBy');
		
		if($this->session->userdata('searchText')=='')
		{
			if(isset($_POST['searchText']) && $_POST['searchText']!='')
				$searchText = trim($_POST['searchText']);
			else
				$searchText = '';
			$this->session->set_userdata('searchText',$searchText);
		}
		else
		{
			if(isset($_POST['searchText']) && $_POST['searchText'] != $this->session->userdata('searchText'))
			{
				$searchText = trim($_POST['searchText']);
				$this->session->set_userdata('searchText',$searchText);
			}
		}
		$searchText = $this->session->userdata('searchText');
	
		
		if($this->session->userdata('searchBy')!='' && $this->session->userdata('searchText')!='')
		{
			$searcharr = array();
			$searchBy = $this->session->userdata('searchBy');
			$search_val = trim($this->session->userdata('searchText'),',');
			if(strpos($search_val,',') !== false)
			{
				$searcharr = explode(',',$search_val);	
			}
			if(count($searcharr))	//For Comma separated values
			{
				//echo $this->session->userdata('searchBy');exit;
				if($this->session->userdata('searchBy') == 'name') 
				{	
					$where .= " AND ( ";
					for($i=0;$i<count($searcharr);$i++)
					{
						if(strpos($searcharr[$i],' ') !== false)
						{
							$searcharr1 = explode(' ',$searcharr[$i]);
							if(count($searcharr1))
							{
								for($j=0;$j<count($searcharr1);$j++)
								{
									$where .= " `firstname` LIKE '%".$searcharr1[$j]."%' OR `middlename` LIKE  '%".$searcharr1[$j]."%' OR `lastname` LIKE  '%".$searcharr1[$j]."%' OR";	
								}
								
							}
							else
							{
								$where .= " `firstname` LIKE '%".$searcharr[$i]."%' OR `middlename` LIKE  '%".$searcharr[$i]."%' OR `lastname` LIKE  '%".$searcharr[$i]."%' OR";	
							}
						}
						else
						{
							$where .= " `firstname` LIKE '%".$searcharr[$i]."%' OR `middlename` LIKE  '%".$searcharr[$i]."%' OR `lastname` LIKE  '%".$searcharr[$i]."%' OR";
						}
					}
					$where  = rtrim($where,'OR');
					$where .= ")";
				}
				else
				{	
					$where .= " AND ( ";
					for($i=0;$i<count($searcharr);$i++)
					{	if($searcharr[$i]){$where .= " ".$searchBy." = '".$searcharr[$i]."' OR";}	}
					$where  = rtrim($where,'OR');
					$where .= ")";
				}
			}
			else		//For Single value
			{
				if($this->session->userdata('searchBy') == 'name')
				{	
					$where .= " AND ( ";
					if(strpos($search_val,' ') !== false)
					{
						$searcharr1 = explode(' ',$search_val);
						if(count($searcharr1))
						{
							for($j=0;$j<count($searcharr1);$j++)
							{
								$where .= " `firstname` LIKE '%".$searcharr1[$j]."%' OR `middlename` LIKE  '%".$searcharr1[$j]."%' OR `lastname` LIKE  '%".$searcharr1[$j]."%' OR";	
							}
						}
						else
						{
							$where .= " `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%' OR";	
						}
					}
					else
					{
						$where .= " `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%' OR";
					}
					//$where .= " AND ( `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%')";
					$where  = rtrim($where,'OR');
					$where .= ")";
				}
				else
				{	$where .= " AND ".$searchBy." = '".$search_val."'";	 }
			}
		}
		
		$this->db->join('payment_transaction b','b.ref_id=a.regid AND b.member_regnumber=a.regnumber','RIGHT');
		$this->db->where($where);
		//$total_row = $this->master_model->getRecordCount("member_registration a",array('isactive'=>'1','isdeleted'=>0,'pay_type'=>1,'b.status'=>1),'regid');
		$total_row = $this->master_model->getRecordCount("member_registration a",'','regid');
		$url = base_url()."admin/Search/success/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'regid,regnumber,namesub,firstname,middlename,lastname,usrpassword,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.transaction_no,b.date';
		$this->db->join('payment_transaction b','b.ref_id=a.regid AND b.member_regnumber=a.regnumber','RIGHT');
		$this->db->where($where);
		//$members = $this->master_model->getRecords("member_registration a",array('isactive'=>'1','isdeleted'=>0,'pay_type'=>1,'b.status'=>1), $select, array('regid'=>'ASC'), $start, $per_page);
		$members = $this->master_model->getRecords("member_registration a", "", $select, array('regid'=>'ASC'), $start, $per_page);
		
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();
		if(count($members))
		{
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			for($i=0;$i<count($members);$i++)
			{
				$decpass = $aes->decrypt(trim($members[$i]['usrpassword']));
				$members[$i]['usrpassword'] = $decpass;
			}
			$data['result'] = $members;
		}
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		
		if(($start+$per_page)>$total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start+$per_page;
		
		if($total_row)
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries'; 
		else
			$data['info'] = 'Showing 0 to '.$end_of_total.' of '.$total_row.' entries'; 
		
		$data['index'] = $start+1;
							   
		$this->load->view('admin/reg_trans_list',$data);
	}
	
	// Failed Member Registrations
	public function failure()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$data['result'] = array();
		$per_page = 10;
		$last = $this->uri->total_segments();
		$start = 0;
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$searchText = '';
		$searchBy = '';
		
		if($page!=0)
		{	$start = $page-1;	}

		$where = " `isactive` = '0' AND `isdeleted` = 0 AND `pay_type` = 1 AND `b`.`status` = 0 ";
		
		// Set session for the Search crieteria
		if($this->session->userdata('searchBy')=='')
		{
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
				$searchBy = trim($_POST['searchBy']);
			else
				$searchBy = '';
			$this->session->set_userdata('searchBy',$searchBy);
		}
		else
		{
			if(isset($_POST['searchBy']) && $_POST['searchBy'] != $this->session->userdata('searchBy'))
			{
				$searchBy = trim($_POST['searchBy']);
				$this->session->set_userdata('searchBy',$searchBy);
			}
		}
		$searchBy = $this->session->userdata('searchBy');
		
		if($this->session->userdata('searchText')=='')
		{
			if(isset($_POST['searchText']) && $_POST['searchText']!='')
				$searchText = trim($_POST['searchText']);
			else
				$searchText = '';
			$this->session->set_userdata('searchText',$searchText);
		}
		else
		{
			if(isset($_POST['searchText']) && $_POST['searchText'] != $this->session->userdata('searchText'))
			{
				$searchText = trim($_POST['searchText']);
				$this->session->set_userdata('searchText',$searchText);
			}
		}
		$searchText = $this->session->userdata('searchText');
	
		// Apply filters
		if($this->session->userdata('searchBy')!='' && $this->session->userdata('searchText')!='')
		{
			$searcharr = array();
			$searchBy = $this->session->userdata('searchBy');
			$search_val = trim($this->session->userdata('searchText'),',');
			if(strpos($search_val,',') !== false)
			{
				$searcharr = explode(',',$search_val);	
			}
			if(count($searcharr))	//For Comma separated values
			{
				if($this->session->userdata('searchBy') == 'name') 
				{	
					$where .= " AND ( ";
					for($i=0;$i<count($searcharr);$i++)
					{
						if(strpos($searcharr[$i],' ') !== false)
						{
							$searcharr1 = explode(' ',$searcharr[$i]);
							if(count($searcharr1))
							{
								for($j=0;$j<count($searcharr1);$j++)
								{
									$where .= " `firstname` LIKE '%".$searcharr1[$j]."%' OR `middlename` LIKE  '%".$searcharr1[$j]."%' OR `lastname` LIKE  '%".$searcharr1[$j]."%' OR";	
								}
								
							}
							else
							{
								$where .= " `firstname` LIKE '%".$searcharr[$i]."%' OR `middlename` LIKE  '%".$searcharr[$i]."%' OR `lastname` LIKE  '%".$searcharr[$i]."%' OR";	
							}
						}
						else
						{
							$where .= " `firstname` LIKE '%".$searcharr[$i]."%' OR `middlename` LIKE  '%".$searcharr[$i]."%' OR `lastname` LIKE  '%".$searcharr[$i]."%' OR";
						}
					}
					$where  = rtrim($where,'OR');
					$where .= ")";
				}
				else
				{	
					$where .= " AND ( ";
					for($i=0;$i<count($searcharr);$i++)
					{	if($searcharr[$i]){$where .= " ".$searchBy." = '".$searcharr[$i]."' OR";}	}
					$where  = rtrim($where,'OR');
					$where .= ")";
				}
			}
			else		//For Single value
			{
				if($this->session->userdata('searchBy') == 'name')
				{	
					$where .= " AND ( ";
					if(strpos($search_val,' ') !== false)
					{
						$searcharr1 = explode(' ',$search_val);
						if(count($searcharr1))
						{
							for($j=0;$j<count($searcharr1);$j++)
							{
								$where .= " `firstname` LIKE '%".$searcharr1[$j]."%' OR `middlename` LIKE  '%".$searcharr1[$j]."%' OR `lastname` LIKE  '%".$searcharr1[$j]."%' OR";	
							}
						}
						else
						{
							$where .= " `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%' OR";	
						}
					}
					else
					{
						$where .= " `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%' OR";
					}
					//$where .= " AND ( `firstname` LIKE '%".$search_val."%' OR `middlename` LIKE  '%".$search_val."%' OR `lastname` LIKE  '%".$search_val."%')";
					$where  = rtrim($where,'OR');
					$where .= ")";
				}
				else
				{	$where .= " AND ".$searchBy." = '".$search_val."'";	 }
			}
		}
		
		$this->db->join('payment_transaction b','b.ref_id=a.regid','RIGHT');
		$this->db->where($where);
		//$total_row = $this->master_model->getRecordCount("member_registration a",array('isactive'=>'1','isdeleted'=>0,'pay_type'=>1,'b.status'=>1),'regid');
		$total_row = $this->master_model->getRecordCount("member_registration a",'','regid');
		$url = base_url()."admin/Search/failure/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'regid,namesub,firstname,middlename,lastname,usrpassword,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,mobile,createdon,b.status,b.transaction_no,b.date';
		$this->db->join('payment_transaction b','b.ref_id=a.regid','RIGHT');
		$this->db->where($where);
		//$members = $this->master_model->getRecords("member_registration a",array('isactive'=>'1','isdeleted'=>0,'pay_type'=>1,'b.status'=>1), $select, array('regid'=>'ASC'), $start, $per_page); 
		$members = $this->master_model->getRecords("member_registration a", "", $select, array('regid'=>'ASC'), $start, $per_page);
		
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();
		if(count($members))
		{
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			for($i=0;$i<count($members);$i++)
			{
				$decpass = $aes->decrypt(trim($members[$i]['usrpassword']));
				$members[$i]['usrpassword'] = $decpass;
			}
			$data['result'] = $members;
		}
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		
		if(($start+$per_page)>$total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start+$per_page;
		
		if($total_row)
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries'; 
		else
			$data['info'] = 'Showing 0 to '.$end_of_total.' of '.$total_row.' entries'; 
		$data['index'] = $start+1;
							   
		$this->load->view('admin/reg_trans_failure_list',$data);
	}
}