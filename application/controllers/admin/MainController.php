<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MainController extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}		
		$this->load->model('UserModel');
		$this->UserID=$this->session->id;
		//$this->load->helper('TAPortal');
	}
	
	
	public function index()
	{ 
		$data = array();
		//echo $this->session->userdata('roleid');exit;
		if(($this->session->userdata('roleid')) == 16){
			redirect(base_url().'admin/Garp/examReg');
		} 
		if(($this->session->userdata('roleid')) == 21){
			redirect(base_url().'admin/Center_change/examReg');
		} 
		if(($this->session->userdata('roleid')) == 20){
			redirect(base_url().'admin/Cfp/examReg');
		} 

		//echo "****".$this->session->userdata('id');
		if($this->session->userdata('roleid') == 3)
		{
			$this->Page('dashboard/query_dashboard',$data);
			//redirect(base_url().'admin/query_dashboard');
		}else if($this->session->userdata('roleid') == 14){
			redirect(base_url().'admin/ippb/IppbDashboard');
		}
		else if($this->session->userdata('roleid') == 15){
			redirect(base_url().'admin/venue_master/CSCVenueDashboard'); 
		}
		else
		{
			$current_date = date("Y-m-d");
			
			$data['total_reg_mem_1'] = '';
			$data['total_reg_mem_2'] = '';
			$data['total_reg_mem_3'] = '';
			$data['total_reg_mem_4'] = '';
			$data['total_reg_mem_5'] = '';
			$data['total_reg_mem_6'] = '';
			
			$data['total_dup_icard_1'] = '';
			$data['total_dup_icard_2'] = '';
			$data['total_dup_icard_3'] = '';
			$data['total_dup_icard_4'] = '';
			
			$data['total_reg_exam_1'] = '';
			$data['total_reg_exam_2'] = '';
			$data['total_reg_exam_3'] = '';
			$data['total_reg_exam_4'] = '';
			$data['total_reg_exam_5'] = '';
			$data['total_reg_exam_6'] = '';
			
			$data['total_reg_exam_7'] = '';
			$data['total_reg_exam_8'] = '';
			$data['total_reg_exam_9'] = '';
			
			$data['total_mem_edit_1'] = '';
			
			
			
			/*$data['total_reg_mem_1'] = $this->total_reg_mem($action = 'success', $from_date = '2013-06-01', $to_date = $current_date);
			$data['total_reg_mem_2'] = $this->total_reg_mem($action = 'success', $from_date = $current_date, $to_date = $current_date);
			$data['total_reg_mem_3'] = $this->total_reg_mem($action = 'failure', $from_date = '2013-06-01', $to_date = $current_date);
			$data['total_reg_mem_4'] = $this->total_reg_mem($action = 'failure', $from_date = $current_date, $to_date = $current_date);
			$data['total_reg_mem_5'] = $this->total_reg_mem_new($action = 'success', $from_date = '2016-12-29', $to_date = $current_date);
			$data['total_reg_mem_6'] = $this->total_reg_mem_new($action = 'failure', $from_date = '2016-12-29', $to_date = $current_date);
			
			$data['total_dup_icard_1'] = $this->total_dup_icard($action = 'success');
			$data['total_dup_icard_2'] = $this->total_dup_icard($action = 'failure');
			$data['total_dup_icard_3'] = $this->total_dup_icard_datewise($action = 'success', $from_date = '2016-12-29', $to_date = $current_date);
			$data['total_dup_icard_4'] = $this->total_dup_icard_datewise($action = 'failure', $from_date = '2016-12-29', $to_date = $current_date);
			
			$data['total_reg_exam_1'] = $this->total_reg_exam($action = 'success', $from_date = '2015-01-01', $to_date = $current_date);
			$data['total_reg_exam_2'] = $this->total_reg_exam($action = 'success', $from_date = $current_date, $to_date = $current_date);
			$data['total_reg_exam_3'] = $this->total_reg_exam($action = 'failure', $from_date = '2015-01-01', $to_date = $current_date);
			$data['total_reg_exam_4'] = $this->total_reg_exam($action = 'failure', $from_date = $current_date, $to_date = $current_date);
			$data['total_reg_exam_5'] = $this->total_reg_exam($action = 'open', $from_date = '2015-01-01', $to_date = $current_date);
			$data['total_reg_exam_6'] = $this->total_reg_exam($action = 'open', $from_date = $current_date, $to_date = $current_date);
			
			$data['total_reg_exam_7'] = $this->total_reg_exam($action = 'success', $from_date = '2016-12-29', $to_date = $current_date);
			$data['total_reg_exam_8'] = $this->total_reg_exam($action = 'failure', $from_date = '2016-12-29', $to_date = $current_date);
			$data['total_reg_exam_9'] = $this->total_reg_exam($action = 'open', $from_date = '2016-12-29', $to_date = $current_date);
			
			$data['total_mem_edit_1'] = $this->total_mem_edit($from_date = '2016-12-29', $to_date = $current_date);*/
			
				
			if($this->session->userdata('roleid') == 1)	//Super Admin
			{
				$this->load->view('admin/dashboard/dashboard',$data);
			}
				
			else	//Report Admin
			{
				$this->load->view('admin/dashboard/dashboard',$data);
			}
				
		}
	}
	
	public function getUserInfo(){
		$data['AdminUser']=$this->UserModel->getUserInfo($this->UserID);
		return $data;
	}	
	
	public function Page($page){
		if($this->session->userdata('roleid')==2 && $page != 'dashboard/dashboard') 
		{
			redirect(base_url().'admin/MainController');
		}
		
		$data=$this->getUserInfo();		
		$perm=TRUE;	
		switch(strtolower($page))
		{
			case 'roles':	//$perm=hasPermission($this->UserID,'Roles','View Roles');
							$data['Roles']=$this->UserModel->getRoles();					
							break;
			case 'users':	//$perm=hasPermission($this->UserID,'Users','View Users');
							$data['Users']=$this->UserModel->getUsers();	
							$data['ActiveRoles']=$this->UserModel->getActiveRoles();					
							break;
		}
		
		if($perm===TRUE)
		{
			$this->load->view('admin/'.$page,$data);
			//echo 'admin/'.$page;
		}
		else
			$this->load->view('admin/NoAccess',$data);	
	}
	
	//General
	//http://127.0.0.1/hiringportal/MainController/changeStatus/Positions/1
	public function changeStatus($tbl, $field, $rowid){
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}
		
		$perm=true;
		switch($tbl){
			case 'Roles':
				//$perm=hasPermission($this->UserID,'Roles','Change Role Status');
				break;	
			case 'Users':
				//$perm=hasPermission($this->UserID,'Users','Change User Status');
				break;					
		}	
		if(!$perm){
			$this->session->set_flashdata('error', "You don't have permissions to change status.");
			redirect('admin/MainController/Page/'.$tbl);
			die;
		}
			$data = array('id'=>$rowid);
			$is=$this->UserModel->changeStatus($tbl,$field,$rowid);
			if($is==TRUE)
			{
				logadminactivity($log_title = $tbl." status changed successfully", $log_message = serialize($data));
				$this->session->set_flashdata('success', 'Status Changed');
			}
			else
			{
				logadminactivity($log_title = "Error while changing ".$tbl." status", $log_message = serialize($data));
				$this->session->set_flashdata('error', 'Error Occured');
			}
		redirect('admin/MainController/Page/'.$tbl);
		
	}
	//End General
	
	public function deleteRecord($tbl, $field, $rowid){
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}
		
		$perm=true;
		if(!$perm){
			$this->session->set_flashdata('error', "You don't have permissions to delete.");
			redirect('admin/MainController/Page/'.$tbl);
			die;
		}
		if($tbl == 'administrators')
		{
			$data = array('id'=>$rowid);
			$is=$this->UserModel->deleteUser($rowid);
			if($is==TRUE)
			{
				logadminactivity($log_title = "Admin user deleted successfully", $log_message = serialize($data));
				$this->session->set_flashdata('success', 'Record Deleted');
			}
			else
			{
				logadminactivity($log_title = "Error while deleting admin user", $log_message = serialize($data));
				$this->session->set_flashdata('error', 'Error Occured');
			}
			redirect('admin/MainController/Page/Users');
		}
	}
	
	//Role
	public function addRole(){
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}
		$RoleID=$this->input->post('RoleID');
		$perm=TRUE;
		if(empty($RoleID)){
			//$perm=hasPermission($this->UserID,'Roles','Add Role');
			$st='Add';
		}else{
			//$perm=hasPermission($this->UserID,'Roles','Update Role');
			$st='Update';
		}		
		if(!$perm){
			$this->session->set_flashdata('error', "You don't have permissions to $st Role");
			redirect('admin/MainController/Page/Roles');die;
		}
				
		$config = array(
			array(
					'field' => 'Role',
					'label' => 'Role',
					'rules' => 'trim|required|alpha_numeric_spaces|is_unique[role_master.Role]|max_length[100]'
			),
		);
		
		$this->form_validation->set_rules($config);
		$data=array(
			'role'=>$this->input->post('Role')
		);
		
		
		if ($this->form_validation->run() == FALSE){
			$data=$this->getUserInfo();				
			$data['Roles']=$this->UserModel->getRoles();						
        	$this->load->view('admin/Roles',$data);
						
		}else{
			if(!empty($RoleID)){
				$is=$this->UserModel->updateRole($RoleID,$data);
				$m='updated';
			}else{	
				$is=$this->UserModel->addRole($data);
				$m='inserted';
			}
			
			if($is==TRUE){
				$this->session->set_flashdata('success', 'Your data '.$m.' Successfully..');
			}else{
				$this->session->set_flashdata('error', 'Error Occured');
			}			
       	 	redirect('admin/MainController/Page/Roles');
		}
	}
	//End Role
//user management
	public function addUser()
	{
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}
		$perm = TRUE;
		$id=$this->input->post('id');
		if(empty($UserID)){
			//$perm=hasPermission($this->UserID,'Users','Add User');
			$st='Add';
		}else{
			//$perm=hasPermission($this->UserID,'Users','Update User');
			$st='Update';
		}		
		if(!$perm){
			$this->session->set_flashdata('error', "You don't have permissions to $st User");
			redirect('admin/MainController/Page/Users');die;
		}
		$config = array(
			array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|alpha_numeric|max_length[30]|is_unique[administrators.username]'
			),
			array(
					'field' => 'name',
					'label' => 'Name',
					'rules' => 'trim|required|regex_match[/^[A-Z ]+$/i]|max_length[30]',
			),
			array(
					'field' => 'emailid',
					'label' => 'Email ID',
				//	'rules' => 'trim|required|valid_email|is_unique[administrators.emailid]|max_length[30]'
					'rules' => 'trim|required|valid_email|max_length[30]'
			),
			array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]',
			),
			array(
					'field' => 'confirmPassword',
					'label' => 'ConfirmPassword',
					'rules' => 'trim|required|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]|matches[password]'
			),
			array(
				'field' => 'roleid',
				'label' => 'Role',
				'rules' => 'trim|required|is_natural|max_length[5]',
			),	
		);		
		
			$configUpdate = array(
					array(
						'field' => 'username',
						'label' => 'Username',
						'rules' => 'trim|required|alpha_numeric|max_length[30]|is_unique[administrators.username.id.'.$id.']'
					),
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|regex_match[/^[A-Z ]+$/i]|max_length[30]',
					),
					array(
							'field' => 'emailid',
							'label' => 'Email ID',
						//	'rules' => 'trim|required|valid_email|max_length[30]|is_unique[administrators.emailid.id.'.$id.']'
						'rules' => 'trim|required|valid_email|max_length[30]'
					),
					array(
							'field' => 'password',
							'label' => 'Password',
							'rules' => 'trim|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]',
					),
					array(
							'field' => 'confirmPassword',
							'label' => 'ConfirmPassword',
							'rules' => 'trim|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]|matches[password]'
					),
					array(
							'field' => 'roleid',
							'label' => 'Role',
							'rules' => 'trim|required|is_natural|max_length[5]',
					),
				);			
				
				if(!empty($id)){
					$this->form_validation->set_rules($configUpdate);
				}else{
					$this->form_validation->set_rules($config);
				}
				
				
				$data=array(
					'username'=> $this->input->post('username'),
					'name'=> $this->input->post('name'),
					'roleid'=> $this->input->post('roleid'),
					'emailid'=> $this->input->post('emailid'),			
				);
				
				if($this->input->post('password')!=''){
					//$data['password']=md5($this->input->post('password'));
					$data['password']=$this->input->post('password');
				}
				
				
				if ($this->form_validation->run() == FALSE){
					$data=$this->getUserInfo();				
					$data['Users']=$this->UserModel->getUsers();
					$data['error']=TRUE;
				
					$data['ActiveRoles']=$this->UserModel->getActiveRoles();	
									
		        	$this->load->view('admin/Users',$data);
								
				}else{
					
					if(!empty($id)){
						$is=$this->UserModel->updateUser($id,$data);
						$m='updated';
						if($is)
						{
							logadminactivity($log_title = "Admin user updated successfully", $log_message = serialize($data));
						}
					}else{	
						$is=$this->UserModel->addUser($data);
						$m='added';
						if($is)
						{
							logadminactivity($log_title = "Admin user added successfully", $log_message = serialize($data));
						}
						
					}			
						
					if($is==TRUE){
						$this->session->set_flashdata('success', 'User '.$m.' Successfully..');
					}else{
						$this->session->set_flashdata('error', 'Error Occured');
					}	
		       	 	redirect('admin/MainController/Page/Users');
				}
	}
	
	public function deleteUser($UserID){
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}	
	
		if(!hasPermission($this->UserID,'Users','Delete User')){
			$this->session->set_flashdata('error', "You don't have permissions to Delete User");
			redirect('admin/MainController/Page/Users');die;	
		}
		$data = array('id'=>$UserID);
		$is=$this->UserModel->deleteUser($UserID);
		if($is==TRUE)
		{
			logadminactivity($log_title = "Admin user deleted successfully", $log_message = serialize($data));
			$this->session->set_flashdata('success', 'User Deleted');
		}
		else
		{
			logadminactivity($log_title = "Error while deleting admin user", $log_message = serialize($data));
			$this->session->set_flashdata('error', 'Error Occured');
		}	
		redirect('admin/MainController/Page/Users');
	}	
	//user management
	
	//change password
	public function changePassword(){
		$config = array(
			array(
					'field' => 'Password',
					'label' => 'Password',
					'rules' => 'trim|required|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]',
			),
			array(
					'field' => 'NewPassword',
					'label' => 'New Password',
					'rules' => 'trim|required|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]',
			),
			array(
					'field' => 'ConfirmPassword',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|regex_match[/^\S*(?=\S{6,})(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]|matches[NewPassword]'
			),
		);	
		
		$this->form_validation->set_rules($config);
		
		/*$Password=md5($this->input->post('Password'));
		$NewPassword=md5($this->input->post('NewPassword'));
		*/
		
		$Password=$this->input->post('Password');
		$NewPassword=$this->input->post('NewPassword');
		
		$upd_data = array('password'=>$Password,'new_password'=>$NewPassword);
		
		if($this->form_validation->run()==FALSE){
			$data=$this->getUserInfo();					
			$this->load->view('admin/ChangePassword',$data);
		}else{
			$is=$this->UserModel->changePassword($this->UserID,$Password,$NewPassword);	
			if($is==TRUE)
			{
				logadminactivity($log_title = "Admin user password changed successfully", $log_message = serialize($upd_data));
				$this->session->set_flashdata('success', 'Password Changed Successfully!');
			}
			else
			{
				logadminactivity($log_title = "error while updating admin user password", $log_message = serialize($upd_data));
				$this->session->set_flashdata('error', 'Error Occured');
			}
			redirect('admin/MainController/Page/ChangePassword');		
		}		
	}
	//end change password
	
	// function to get dashboard stats, added by Bhagwan Sahane
	public function total_reg_mem($action = 'success', $from_date = '', $to_date = '')
	{
		$total_count = 0;
		
		$where = "(DATE(payment_transaction.date) BETWEEN '".$from_date."' AND '".$to_date."') AND pay_type = 1";	// pay_type = 1 for member registration
		if($action == "success")
		{
			$where .= " AND status = 1";
		}
		elseif($action == "failure")
		{
			$where .= " AND status = 0";	
		}
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$res = $this->UserModel->getRecords("payment_transaction", $select, '', '', '', '', '', '');
		
		//echo $this->db->last_query(); echo "<br>";
		
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	
	// function to get dashboard stats, added by Vrushali Ugale
	public function total_reg_mem_new($action = 'success', $from_date = '', $to_date = '')
	{
		$total_count = 0;
		
		$where = "(DATE(member_registration.createdon) BETWEEN '".$from_date."' AND '".$to_date."') AND isdeleted = 0";
		if($action == "success")
		{
			$where .= " AND isactive = '1' ";
		}
		elseif($action == "failure")
		{
			$where .= " AND isactive = '0' ";	
		}
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$res = $this->UserModel->getRecords("member_registration", $select, '', '', '', '', '', '');
		
		//echo $this->db->last_query(); echo "<br>";
		
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	
	// function to get dashboard stats, added by Vrushali Ugale
	public function total_mem_edit( $from_date = '', $to_date = '')
	{
		$total_count = 0;
		
		$where = "(DATE(member_registration.editedon) BETWEEN '".$from_date."' AND '".$to_date."') AND isactive = '1'";	
	
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		//$this->db->join('payment_transaction','ref_id = regid','LEFT');
		$res = $this->UserModel->getRecords("member_registration", $select, '', '', '', '', '', '');
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	public function total_dup_icard($action = 'success')
	{
		$total_count = 0;
		
		$where = "pay_type = 3";	// pay_type = 3 for duplicate icard	
		if($action == "success")
		{
			$where .= " AND status = 1 AND pay_status = '1'";
		}
		elseif($action == "failure")
		{
			$where .= " AND status = 0 AND pay_status = '0'";	
		}
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$this->db->join('payment_transaction','ref_id = did','LEFT');
		$res = $this->UserModel->getRecords("duplicate_icard ", $select, '', '', '', '', '', '');
		//$res = $this->UserModel->getRecords("payment_transaction ", $select, '', '', '', '', '', '');
		//echo $this->db->last_query();echo "<br>";
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	public function total_dup_icard_datewise($action = 'success',$from_date = '', $to_date = '')
	{
		$total_count = 0;
		
		$where = "(DATE(payment_transaction.date) BETWEEN '".$from_date."' AND '".$to_date."') AND pay_type = 3 ";	// pay_type = 3 for duplicate icard	
		if($action == "success")
		{
			$where .= " AND status = 1 AND pay_status = '1'";
		}
		elseif($action == "failure")
		{
			$where .= " AND status = 0 AND pay_status = '0'";	
		}
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$this->db->join('payment_transaction','ref_id = did','LEFT');
		$res = $this->UserModel->getRecords("duplicate_icard ", $select, '', '', '', '', '', '');
		//echo $this->db->last_query();echo "<br>";
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	public function total_reg_exam($action = 'success', $from_date = '', $to_date = '')
	{
		/*$total_count = 0;
		
		$where = "(DATE(payment_transaction.date) BETWEEN '".$from_date."' AND '".$to_date."') AND pay_type = 2 ";	// pay_type = 2 for exam registration	
		if($action == "success")
		{
			$where .= " AND status = 1 AND pay_status = '1'";	
		}
		elseif($action == "failure")
		{
			$where .= " AND status = 0 AND pay_status = '0'";	
		}
		elseif($action == "open")
		{
			$where .= " AND status = 2";	
		}
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$this->db->join('payment_transaction','payment_transaction.ref_id = member_exam.id','LEFT');
		$res = $this->UserModel->getRecords("member_exam", $select, '', '', '', '', '', '');
		
		echo $this->db->last_query();echo "<br>";
		
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;*/
		
		$total_count = 0;
		
		$where = "(DATE(payment_transaction.date) BETWEEN '".$from_date."' AND '".$to_date."') AND pay_type = 2";	// pay_type = 2 for exam registration	
		if($action == "success")
		{
			$where .= " AND status = 1";	
		}
		elseif($action == "failure")
		{
			$where .= " AND status = 0";	
		}
		elseif($action == "open")
		{
			$where .= " AND status = 2";	
		}
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$res = $this->UserModel->getRecords("payment_transaction", $select, '', '', '', '', '', '');
		
		//echo $this->db->last_query(); echo "<br>";
		
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}

	/*DOWNLOAD ADMIN USER DATA FUNCTIONALITY DEVELOPED BY Pooja Mane : 8-9-2023*/
	public function Userdownload()
	{
		$csv = "User Management Details\n\n";
		$csv.= "Sr.No.,Name,Username,Password,Email Id,Role,Status\n";//Column headers
				
				$data['Users']=$this->UserModel->getUsers();	
				$data['ActiveRoles']=$this->UserModel->getActiveRoles();

				if(!empty($data['Users']))
				{
					$i=1;
						foreach($data['Users'] as $user)
						{		
							$user = (array)$user;
							if($user['active'] == 1)
							{	$active = 'Active' ; }
							else
							{	$active = 'Inactive';   }

							$csv.= $i.','.$user['name'].','.$user['username'].','.$user['password'].','.$user['emailid'].','.$user['role'].','.$active."\n";
							$i++;
						}
				}
				$filename = "User Management Details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;
	}
	/*DOWNLOAD ADMIN USER DATA FUNCTIONALITY end - Pooja Mane : 8-9-2023*/
	
}