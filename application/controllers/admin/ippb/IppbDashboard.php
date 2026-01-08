<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
defined('BASEPATH') OR exit('No direct script access allowed');
class IppbDashboard extends CI_Controller
{
    public function __construct(){
		// exit
        parent::__construct();
		if($this->session->id==""){
			redirect('ippb_login/admin/Login'); 
		}
		
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
		$this->load->model('log_model');
        $this->load->model('KYC_Log_model');
		
    }
    
    public function index()
	{

		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');

		$data = $this->getUserInfo();
		
		$this->load->view('admin/ippb_dashboard/member_list',$data);

		
    }

	public function getList()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		

		/*$this->db->where('a.isdeleted =', '0');
		$total_row = $this->UserModel->getRecordCount("member_registration_ippb a",$field,$value);*/

		//added by pooja mane on 23-05-2023
		$countquery = $this->db->query("SELECT count(DISTINCT(emp_id)) as count FROM `member_registration_ippb` `a` WHERE `a`.`isdeleted` = '0'");
		$count = $countquery->result_array();
		$total_row = $count[0]['count'];
		//code end pooja mane on 23-05-2023
		
		$url = base_url()."admin/ippb/IppbDashboard/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		if($sortkey=='' && $sortval=='')
			$this->db->order_by('regid','DESC');

		$this->db->where('a.isdeleted =', '0');
		$this->db->group_by('emp_id');
		$select = 'a.regid, a.firstname,a.middlename,a.lastname,a.email,a.mobile,a.emp_id,a.branch,a.circle,a.createdon';
		$res = $this->UserModel->getRecords("member_registration_ippb a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		// echo $this->db->last_query(); exit;
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/ippb/IppbDashboard/edit/'.$row['regid'].'">Edit | </a> <a href="'.base_url().'admin/ippb/IppbDashboard/delete/'.$row['regid'].'" onclick="'.$confirm.'">Delete</a>';
				$data['action'][] = $action;
			}
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}

	public function download_CSV()
	{
		$csv = " IPPB member registration details \n\n";
		$csv.= "Sr no.,regid,regnumber,First Name,Last Name,Email,Mobile,Employee Id,Branch,Circle,Created date \n";//Column headers
	

		$subquery = $this->db->query("SELECT a.regid,b.regnumber,a.firstname,a.lastname,a.email,a.mobile,a.emp_id,a.branch as ippb_branch,a.circle,a.createdon FROM `member_registration_ippb` as a LEFT JOIN member_registration as b ON b.mobile=a.mobile WHERE a.isdeleted='0' GROUP BY emp_id");
		//echo $this->db->last_query(); exit;
		$result = $subquery->result_array();
		//echo count($result);die;

		if(!empty($result))
		{
			$i=1;
			foreach($result as $record)
			{					
				// print_r($record);exit;
				$csv.= $i.','.$record['regid'].','.$record['regnumber'].','.$record['firstname'].',"'.$record['lastname'].'",'.$record['email'].','.$record['mobile'].','.$record['emp_id'].','.$record['ippb_branch'].','.$record['circle'].','.$record['createdon']."\n";
				$i++;
			}
		}
		$filename = "IPPB_member_registration_details.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite ($csv_handler,$csv);
		fclose ($csv_handler);
	}

	


	public function add()
	{
		$data = array();
		if(isset($_POST['btnSubmit']))
		{
			// print_r($this->input->post()); exit;
		
			$this->form_validation->set_rules('firstname','First Name','trim|required');
			$this->form_validation->set_rules('middlename','Middle Name','trim');
			$this->form_validation->set_rules('lastname','Last Name','trim|required');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('emp_id','Employee id','trim|required|callback_check_emp_id_duplication');
			/*$this->form_validation->set_rules('branch','Branch','trim|required');
			$this->form_validation->set_rules('circle','Circle','trim|required');*/
			//echo 'string';die;
			if($this->form_validation->run()==TRUE)
			{	//echo 'run';//die;
				$insert_data = array(	
							'firstname'				=>$this->input->post('firstname'),
							'middlename'			=>$this->input->post('middlename'),
							'lastname'				=>$this->input->post('lastname'),
							'email'					=>$this->input->post('email'),
							'mobile'				=>$this->input->post('mobile'),
							'emp_id'				=>$this->input->post('emp_id'),
							//'branch'				=>$this->input->post('branch'),
							//'circle'				=>$this->input->post('circle'),
							'createdon'				=>date('Y-m-d H:i:s'),
							'addedby'				=>$this->session->userdata['id'],
						);
				//print_r($insert_data);die;
				if($this->master_model->insertRecord('member_registration_ippb',$insert_data))
				{ //echo $this->db->last_query();die;
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Add Ippb data successful from Ippb admin',
									'description'=>serialize($insert_data),
									'userid'=>$this->db->insert_id(),
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'admin/ippb/IppbDashboard');
				}
				else
				{
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Add Ippb data Unsuccessful from Ippb admin',
									'description'=>serialize($insert_data),
									'userid'=>$this->db->insert_id(),
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'admin/ippb/IppbDashboard/add');
				}
			}
			else
			{
				//echo validation_errors();//die; 
				$data['validation_errors'] = validation_errors(); 
			}
		}
			//echo 'errr';echo validation_errors();die; 
			$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/ippb/IppbDashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/ippb/'.$this->router->fetch_class().'">Manage IPPB records</a></li>
					<li class="active">Add</li>
				</ol>';
				
			$data['feeRes'] = array(	'firstname'			=>'',
									'middlename'		=>'',
									'lastname'			=>'',
									'email'				=>'',
									'emp_id'			=>'',
									'mobile'			=>'',
									'branch'			=>'',
									'member_category'	=>'',
									'group_code'		=>'',
									'fee_amount'		=>''
								);
		
		$this->load->view('admin/masters/ippb_add',$data);
	}
	
	public function edit(){

		// print_r(); exit;
		$data = array();
		$data['mem_info'] = array();
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		$data['regid'] = $id;
		// print_r($id); exit;

		if(isset($id)){
			
			$this->db->where('member_registration_ippb.regid =', $id);
			$this->db->where('member_registration.excode =', '997');
			$this->db->where('member_registration.isactive =', '1');
			$this->db->join('member_registration', 'member_registration.mobile = member_registration_ippb.mobile','left');
			$mem_info_count = $this->master_model->getRecordCount('member_registration_ippb','','member_registration.regnumber');
			// $mem_info = $this->master_model->getRecords('member_registration_ippb','','member_registration_ippb.firstname,member_registration_ippb.middlename,member_registration_ippb.lastname,member_registration_ippb.email,member_registration_ippb.mobile,member_registration_ippb.emp_id,member_registration_ippb.branch,member_registration_ippb.circle,member_registration.regnumber');
			
			if($mem_info_count > 0)
			{
				$this->db->where('member_registration_ippb.regid =', $id);
				$this->db->where('member_registration.regnumber !=', '');
				// $this->db->where('member_registration.email =', 'member_registration_ippb.email');
				$this->db->join('member_registration', 'member_registration.mobile = member_registration_ippb.mobile','left');
				$mem_info = $this->master_model->getRecords('member_registration_ippb','','member_registration.firstname,member_registration.middlename,member_registration.lastname,member_registration.email,member_registration.mobile,member_registration_ippb.emp_id,member_registration_ippb.branch,member_registration_ippb.circle,member_registration.regnumber');
				// echo $this->db->last_query();print_r($mem_info); exit;

				$data['mem_info'] = $mem_info[0];
				
			}
			else
			{
				$this->db->where('member_registration_ippb.regid =', $id);
				$mem_info = $this->master_model->getRecords('member_registration_ippb','','member_registration_ippb.firstname,member_registration_ippb.middlename,member_registration_ippb.lastname,member_registration_ippb.email,member_registration_ippb.mobile,member_registration_ippb.emp_id,member_registration_ippb.branch,member_registration_ippb.circle');

				$data['mem_info'] = $mem_info[0];
			}
			
			if(isset($_POST['btnSubmit']))
			{

				// print_r($this->input->post()); exit;
				$this->form_validation->set_rules('firstname','First Name','trim|required');
				$this->form_validation->set_rules('middlename','Middle Name','trim');
				//$this->form_validation->set_rules('lastname','Last Name','trim|required');
				$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
				$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_edit_mobileduplication');
				//$this->form_validation->set_rules('emp_id','Employee id','trim|required|numeric|callback_check_edit_emp_id_duplication');
				/*$this->form_validation->set_rules('branch','Branch','trim|required');
				$this->form_validation->set_rules('circle','Circle','trim|required');*/
				if($this->form_validation->run()==TRUE)
				{
					
					$update_data = array(	
						'firstname'				=>$this->input->post('firstname'),
						'middlename'			=>$this->input->post('middlename'),
						'lastname'				=>$this->input->post('lastname'),
						'email'					=>$this->input->post('email'),
						'mobile'				=>$this->input->post('mobile'),
						//'emp_id'				=>$this->input->post('emp_id'),
						//'branch'				=>$this->input->post('branch'),
						//'circle'				=>$this->input->post('circle'),
						'isdeleted'				=>0,
						'editedby'				=>$this->session->userdata['id'],
						'editedon'				=>date('Y-m-d H:i:s'),
					);

					if($this->master_model->updateRecord('member_registration_ippb',$update_data,array('regid'=>$id)))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $mem_info[0];
						
						$logs_data = array(
										'date'=>date('Y-m-d H:i:s'),
										'title'=>'Edit ippb data successfully by admin',
										'description'=>serialize($desc),
										'userid'=>$this->session->userdata['id'],
										'ip'=>$this->input->ip_address()
									);
						$this->master_model->insertRecord('adminlogs',$logs_data);
						
						$this->session->set_flashdata('success','Record updated successfully');
						redirect(base_url().'admin/ippb/IppbDashboard');
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $feeRes[0];
						
						$logs_data = array(
										'date'=>date('Y-m-d H:i:s'),
										'title'=>'Edit ippb data Unsuccessful',
										'description'=>serialize($desc),
										'userid'=>$this->session->userdata['id'],
										'ip'=>$this->input->ip_address()
									);
						$this->master_model->insertRecord('adminlogs',$logs_data);
						
						$this->session->set_flashdata('error','Error occured while updating record');
						redirect(base_url().'admin/ippb/IppbDashboard/edit/'.$id);
					}
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}
			}
			
			$this->load->view('admin/masters/ippb_add',$data);
		}
		
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(isset($id) && is_numeric($id))
		{
			// echo $id; exit;

			$this->db->where('member_registration_ippb.regid =', $id);
			$this->db->where('member_registration.excode =', '997');
			$this->db->where('member_registration.isactive =', '1');
			$this->db->join('member_registration', 'member_registration.mobile = member_registration_ippb.mobile','left');
			$mem_info_count = $this->master_model->getRecordCount('member_registration_ippb','','member_registration.regnumber');
			if($mem_info_count > 0)
			{
				$logs_data = array(
					'date'=>date('Y-m-d H:i:s'),
					'title'=>'Delete Ippb record Unsuccessful this is active member',
					'description'=>serialize(array('id'=>$id)),
					'userid'=>$this->session->userdata['id'],
					'ip'=>$this->input->ip_address()
				);
				$this->master_model->insertRecord('adminlogs',$logs_data);

				$this->session->set_flashdata('error','Delete Ippb record Unsuccessful, this is active member');
				redirect(base_url().'admin/ippb/IppbDashboard');

			}

			$update_data = array('isdeleted'=>1,'editedby'=>$this->session->userdata['id']);

			if($this->master_model->updateRecord('member_registration_ippb',$update_data,array('regid'=>$id)))
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Ippb record successful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->session->userdata['id'],
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/ippb/IppbDashboard');
			}
			else
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Ippb record Unsuccessful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->session->userdata['id'],
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/ippb/IppbDashboard');
			}
		}
	}
	
	
	public function getUserInfo(){
		$data['AdminUser']=$this->UserModel->getUserInfo($this->session->userdata['id']);
		return $data;
	}
	
	
	## check Email Id in member registration and Ippb member registration tables
	public function check_emailduplication($email)
	{
		// echo "check_emailduplication >> in".$email; exit;
		if($email!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			$prev_count_ippb=$this->master_model->getRecordCount('member_registration_ippb',array('email'=>$email));
			//echo $this->db->last_query();
			// echo "prev_count >> in"; print_r($prev_count);
			// echo "prev_count_ippb >> in";  print_r($prev_count_ippb);
			if($prev_count == 0 || $prev_count_ippb == 0)
			{	
				// echo "true >> in"; exit;
				return true;	
			}else
			{
				// echo "false >> in"; exit;

				$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];

				if($username == ''){
					$user_info=$this->master_model->getRecords('member_registration_ippb',array('email'=>$email),'firstname,middlename,lastname');
					$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered email ID= '.$email.' already exist for member name '.$userfinalstrname.'';
				
				}else{
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered email ID= '.$email.' already exist for registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				}

				$this->form_validation->set_message('check_emailduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function check_edit_emailduplication($email)
	{
		// echo "check_emailduplication >> in".$email; exit;
		if($email!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			$prev_count_ippb=$this->master_model->getRecordCount('member_registration_ippb',array('email'=>$email));
			//echo $this->db->last_query();
			// print_r($prev_count);
			// print_r($prev_count_ippb);exit;
			// if($prev_count == 0 || $prev_count_ippb == 0)
			if($prev_count <= 1 || $prev_count_ippb <= 1)
			{	
				return true;			
			}else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];

				if($username == ''){
					$user_info=$this->master_model->getRecords('member_registration_ippb',array('email'=>$email),'firstname,middlename,lastname');
					$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered email ID= '.$email.' already exist for member name '.$userfinalstrname.'';
				
				}else{
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered email ID= '.$email.' already exist for registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				}

				$this->form_validation->set_message('check_edit_emailduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			$prev_count_ippb=$this->master_model->getRecordCount('member_registration_ippb',array('email'=>$email));
			//echo $this->db->last_query();
			if($prev_count == 0 || $prev_count_ippb == 0)
			{	
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);}
			else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				if($username == ''){
					$user_info=$this->master_model->getRecords('member_registration_ippb',array('email'=>$email),'firstname,middlename,lastname');
					$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered email ID= '.$email.' and mobile no already exist for member name '.$userfinalstrname.'';
				
				}else{
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered email ID= '.$email.' and mobile no already exist for registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				}
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
				
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	//call back for mobile duplication
	public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
		
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			$prev_count_ippb=$this->master_model->getRecordCount('member_registration_ippb',array('mobile'=>$mobile,'isdeleted'=>'0'));

			// echo $this->db->last_query();
			if($prev_count == 0 || $prev_count_ippb == 0)
			{
				$this->form_validation->set_message('check_mobileduplication', ''); 
				return true;
			}else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];

				if($username == ''){
					$user_info=$this->master_model->getRecords('member_registration_ippb',array('mobile'=>$mobile),'firstname,middlename,lastname');
					$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered mobile no= '.$mobile.' already exist for member Name '.$userfinalstrname.'';
				}else{
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered mobile no= '.$mobile.' already exist for registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				}


				$this->form_validation->set_message('check_mobileduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//call back for mobile duplication
	public function check_edit_mobileduplication($mobile)
	{
		if($mobile!="")
		{
		
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			$prev_count_ippb=$this->master_model->getRecordCount('member_registration_ippb',array('mobile'=>$mobile,'isdeleted'=>'0'));

			//echo $this->db->last_query();
			if($prev_count <= 1 || $prev_count_ippb <= 1)
			{
				return true;
			}else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];

				if($username == ''){
					$user_info=$this->master_model->getRecords('member_registration_ippb',array('mobile'=>$mobile),'firstname,middlename,lastname');
					$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered mobile no= '.$mobile.' already exist for member Name '.$userfinalstrname.'';
				}else{
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$str='The entered mobile no= '.$mobile.' already exist for registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				}


				$this->form_validation->set_message('check_edit_mobileduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//call back for emp id duplication
	public function check_emp_id_duplication($emp_id)
	{
		if($emp_id!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration_ippb',array('emp_id'=>$emp_id,'isdeleted'=>'0'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				return true;	
			}
			else
			{
				$user_info=$this->master_model->getRecords('member_registration_ippb',array('emp_id'=>$emp_id),'firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered Employee ID= '.$emp_id.' already exist for membership name '.$userfinalstrname.'';
				
				$this->form_validation->set_message('check_emp_id_duplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//call back for emp id duplication
	public function check_edit_emp_id_duplication($emp_id)
	{
		if($emp_id!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration_ippb',array('emp_id'=>$emp_id));
			//echo $this->db->last_query();
			// echo $prev_count; exit;

			if($prev_count <= 1)
			{	
				return true;	
			}
			else
			{
				$user_info=$this->master_model->getRecords('member_registration_ippb',array('emp_id'=>$emp_id),'firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered Employee ID= '.$emp_id.' already exist for membership name '.$userfinalstrname.'';
				
				$this->form_validation->set_message('check_edit_emp_id_duplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function examReg()
	{
		//echo 'SITE IS UNDER MAINTAINACE, WILL BE BACK IN SOMETIME';
		if($this->session->userdata('roleid')!=14){
			redirect('admin/ippb/IppbDashboard');	
		}
		
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/ippb/IppbDashboard"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Member List</li>
							   </ol>';
		
		$this->load->view('admin/ippb_dashboard/exam_details',$data);
		//$this->load->view('admin/ippb_dashboard/exam_details_pooja',$data);
	}

	public function getExamReport()
	{
		//return 1;
		//echo "string";
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		/*Get date range filter Pratibha B : 23/7/22*/
		$from_date ='';
		$to_date = '';

		if(isset($_POST['from_date']) && isset($_POST['to_date'])){
			$from_date = $_POST['from_date'];
			$to_date = $_POST['to_date'];
			$this->session->set_userdata('from_date', $from_date);
			$this->session->set_userdata('to_date', $to_date);
		}else if($this->session->userdata('from_date') != '' && $this->session->userdata('to_date') != '') {
			$from_date = $this -> session -> userdata('from_date');
			$to_date = $this -> session -> userdata('to_date');
		}

		// print_r($from_date); exit;
		/*End Get date range filter Pratibha B : 23/7/22*/		

		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
			/*Add date range filter POOJA MANE : 14/7/22*/
			// $from_date = $session_arr['from_date'];
			// $to_date = $session_arr['to_date'];
			/*Add date range filter POOJA MANE : 14/7/22*/	
		}
		
		
		switch($field)
		{
			case '01'	:	$field = 'c.exam_code'; $value = '997';
							break;
			case '02'	:	$field = 'e.mobile';
							break;
			case '03'	:	$field = 'c.exam_center_code';
							break;
			case '04'	:	$field = 'ac.mem_mem_no ';
							break;
			case '05'	:	$field = 'transaction_no';
							break;
			/*POOJA MANE : 12/7/2022*/				
			case '06'	:	$field = 'e.emp_id';
							break;
			/*POOJA MANE : 12/7/2022*/								
			case '07'	:	$field = 'all';
							break;	
		}
		
		$field = 'ac.mam_nam_1,c.exam_code, ac.mem_mem_no , transaction_no, e.emp_id, ac.exam_date,b.transaction_details,b.date,e.mobile,c.selected_vendor';
		
		$this->db->where('pay_type',2);
		//	$this->db->where('remark',1);
			$this->db->where('pay_status',1);
			$this->db->where('c.exam_code','997');
			if(!empty($from_date &&  $to_date))
			{
					
					$this->db->where("date(b.date) BETWEEN '$from_date' AND '$to_date'");
			} 
		
			
	    //$this->db->join('exam_master d','d.exam_code=c.exam_code','LEFT');
		$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
		$this->db->join('employee_data_ippb e',' e.regnumber=c.regnumber ','LEFT');
		$this->db->join('admit_card_details ac','ac.mem_exam_id= c.id','LEFT');		
		$this->db->group_by('b.transaction_no');
		//$total_row = $this->UserModel->getRecordCount("member_exam c", $field, $value,'b.transaction_no' );
		$res1 = $this->UserModel->getRecords("member_exam c", $select, $field, $value);
		$total_row = count($res1->result_array());
		//echo  $total_row.'=='.$this->db->last_query();exit;
		/*$res1 = $this->db->query("SELECT b.transaction_no FROM member_exam c  LEFT JOIN exam_master d ON d.exam_code=c.exam_code LEFT JOIN payment_transaction b ON b.ref_id=c.id LEFT JOIN employee_data_ippb e ON  e.regnumber=c.regnumber ".$where." GROUP BY b.transaction_no");
		echo $this->db->last_query();exit;
        $total_row = count($res1->result_array());*/
        //print_r($total_row) ;exit;
		 //
		 $select = 'b.transaction_no,c.regnumber,b.status,b.date,c.exam_medium,c.exam_fee,d.description,c.exam_center_code,b.transaction_details,e.emp_id,b.ref_id,mem_mem_no,admitcard_image,ac.exam_date,mam_nam_1, c.selected_vendor';
		
			//$this->db->where('a.isactive','1');
			//$this->db->where('a.isdeleted',0);
			$this->db->where('pay_type',2);
		//	$this->db->where('remark',1);
			$this->db->where('pay_status',1);
			$this->db->where('c.exam_code','997');
	  if(!empty($from_date &&  $to_date))
	  {
			
			$this->db->where("date(b.date) BETWEEN '$from_date' AND '$to_date'");
      } 
		
	  if($sortkey=='' && $sortval=='')
		$this->db->order_by('status','DESC');
		
		
	
		$this->db->join('exam_master d','d.exam_code=c.exam_code','LEFT');
		$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
		$this->db->join('employee_data_ippb e',' e.regnumber=c.regnumber ','LEFT');
		$this->db->join('admit_card_details ac','ac.mem_exam_id= c.id','LEFT');		
		$this->db->group_by('b.transaction_no');
		$res = $this->UserModel->getRecords("member_exam c", $select, $field, $value, $sortkey, $sortval, $per_page, $start);

		//echo 'here='.$this->db->last_query();exit;
		$url = base_url()."admin/ippb/IppbDashboard/getExamReport/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
	//	echo $this->db->last_query();
		//$data['query'] = $this->db->last_query();
		 
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach($result as $currKey=>$row)
			{
				$getMemberDetails = $this->master_model->getRecords("member_registration",array('regnumber'=>$row['regnumber']),'regid,CONCAT(firstname , " ", lastname  )as firstname,lastname,mobile,gender,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,namesub');
				
				if(count($getMemberDetails))
				{
					$result[$i]['regid'] = $getMemberDetails[0]['regid'];
					$result[$i]['firstname'] = $getMemberDetails[0]['firstname'];
					$result[$i]['lastname'] = $getMemberDetails[0]['lastname'];
					$result[$i]['mobile'] = $getMemberDetails[0]['mobile'];
					$result[$i]['gender'] = $getMemberDetails[0]['gender'];
					$result[$i]['dateofbirth'] = $getMemberDetails[0]['dateofbirth'];
					$result[$i]['createdon'] = $getMemberDetails[0]['createdon'];
					$result[$i]['namesub'] = $getMemberDetails[0]['namesub'];
				}

				/*$getEmpDetails = $this->master_model->getRecords("member_registration_ippb",array('mobile'=>$result[$i]['mobile']),'emp_id');
				
				if(count($getMemberDetails))
				{
					$result[$i]['emp_id'] = $getEmpDetails[0]['emp_id'];
					//$result[$i]['mobile'] = $getEmpDetails[0]['mobile'];
				}*/

				/*$getAdmitCardDetails = $this->master_model->getRecords("admit_card_details",array('mem_exam_id'=>$row['ref_id']),'mem_mem_no,admitcard_image,exam_date');
				
				if(count($getAdmitCardDetails))
				{
					$result[$i]['exam_date'] = $getAdmitCardDetails[0]['exam_date'];
					$result[$i]['mem_mem_no'] = $getAdmitCardDetails[0]['mem_mem_no'];
					$result[$i]['admitcard_image'] =$row['admitcard_image']= $getAdmitCardDetails[0]['admitcard_image'];
				}
			*/
				if($row['status']==1)
					$result[$i]['status'] = 'Completed';
				else if($row['status']==2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';
					
				if($row['status']==1)
				{
					$regnumber = '<a href="'.base_url().'admin/Report/preview/'.base64_encode($row['regid']).'">'.$row['regnumber'].'</a>';
					$confirm = "return confirm('Are you sure to delete this record?');";
					$action = '<a href="'.base_url().'admin/Report/reg_edit/'.base64_encode($row['regid']).'">Edit </a>';
					$confirm = 'Do you want to re-send registration mail?';
					$send_mail = '<a href="'.base_url().'admin/Report/send_mail/'.base64_encode($row['regid']).'/'.base64_encode($row['regnumber']).'/0" onclick="return confirmMailSend();">Send Mail</a>';
				}
				else
				{
					$regnumber = '';
					$action = '';
					$send_mail = '';
				}
				
				$center = $this->master_model->getRecords("center_master",array('center_code'=>$row['exam_center_code']),'center_name,center_code');
				
				if(count($center))
				{
					$result[$i]['center_name'] = $center[0]['center_name']."<br>(".$center[0]['center_code'].")";
				}
				else
				{
					$result[$i]['center_name'] = '';
				}
				$Success = 'SUCCESS';
				if($row['transaction_details']!='') {
					$transaction_details = strtoupper($row['transaction_details']); 
				}
				if (strpos($transaction_details, $Success) !== false && $row['admitcard_image']!='') {
				   
					$result[$i]['admitcard_image']="<a href=".base_url()."/uploads/admitcardpdf/".$row['admitcard_image']." target='_new'>Admit Card </a>";
				}else{
					$result[$i]['admitcard_image']= '';
				}
				
				$medium = $this->master_model->getRecords("medium_master",array('medium_code'=>$row['exam_medium']),'medium_description');
				
				if(count($medium))
				{
					$result[$i]['medium_description'] = $medium[0]['medium_description'];
				}
				else
				{
					$result[$i]['medium_description'] = '';
				}
				
				//Customized columns
				//$result[$i]['regnumber'] = $regnumber;
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				$result[$i]['createdon'] = date('d-m-Y',strtotime($row['createdon']));
				
				$result[$i]['selected_vendor'] = strtoupper($row['selected_vendor']);

				if($result[$i]['selected_vendor'] =='')
					$result[$i]['selected_vendor'] ='CSC';
				$i++;
			}
			
			$data['result'] = $result;
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
	public function getExamDetailsToPrint()
	{
		return 1;
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$total_row = 0;
		
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
		}
		//echo $sortkey."***".$sortval;
		
		switch($field)
		{
			case '01'	:	$field = 'c.exam_code';$value = '997';
							break;
			case '02'	:	$field = 'trim(d.description)';
							break;
			case '03'	:	$field = 'c.exam_center_code';
							break;
			case '04'	:	$field = 'a.regnumber';
							break;
			case '05'	:	$field = 'transaction_no';
							break;
			/*POOJA MANE : 12/7/2022*/				
			case '06'	:	$field = 'e.emp_id';
							break;
			/*POOJA MANE : 12/7/2022*/								
			case '07'	:	$field = 'all';
							break;						
		}
		if($field == "all")
		{
			$field = 'c.exam_code, d.description, c.exam_center_code, c.regnumber, transaction_no, , e.emp_id';
		}
		
		//$this->db->where('a.isactive','1');
		//$this->db->where('a.isdeleted',0);
		$this->db->where('pay_type',2);
		$this->db->where('pay_status',1);
		$this->db->where('c.exam_code','997');
		$this->db->order_by('status','DESC');
		
		// $select = 'b.transaction_no,a.regid,a.regnumber,namesub,firstname,lastname, dateofbirth,createdon,b.status,b.date,c.exam_medium,c.exam_fee,gender,d.description,c.exam_center_code,b.transaction_details';
		$select = 'b.transaction_no,c.regnumber,b.status,b.date,c.exam_medium,c.exam_fee,d.description,c.exam_center_code,b.transaction_details,e.emp_id,ac.mem_mem_no,ac.mam_nam_1';
		//DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth
	  //$this->db->join('member_registration a','a.regnumber=c.regnumber','RIGHT');
		$this->db->join('exam_master d','d.exam_code=c.exam_code','LEFT');
		$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
		// $this->db->join('member_registration_ippb e','e.mobile=a.mobile','LEFT');
		$this->db->join('employee_data_ippb e','e.regnumber=c.regnumber ','LEFT');
		$this->db->join('admit_card_details ac','ac.mem_exam_id= c.id','LEFT');
		//$this->db->join('member_registration a','a.regnumber=b.member_regnumber','RIGHT');
		$this->db->group_by('b.transaction_no');
		if($field=='regnumber')$field = 'c.regnumber';
		$res = $this->UserModel->getRecords("member_exam c", $select, $field, $value);
		
		////$data['query'] = $this->db->last_query();
		 
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach($result as $row)
			{
				$getMemberDetails = $this->master_model->getRecords("member_registration",array('regnumber'=>$row['regnumber']),'regid,firstname,lastname,mobile,gender,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,createdon,namesub');
				
				if(count($getMemberDetails))
				{
					$result[$i]['regid'] = $getMemberDetails[0]['regid'];
					$result[$i]['firstname'] = $getMemberDetails[0]['firstname'];
					$result[$i]['lastname'] = $getMemberDetails[0]['lastname'];
					$result[$i]['mobile'] = $getMemberDetails[0]['mobile'];
					$result[$i]['gender'] = $getMemberDetails[0]['gender'];
					$result[$i]['dateofbirth'] = $getMemberDetails[0]['dateofbirth'];
					$result[$i]['createdon'] = $getMemberDetails[0]['createdon'];
					$result[$i]['namesub'] = $getMemberDetails[0]['namesub'];
				}
				if($row['status']==1)
					$result[$i]['status'] = 'Completed';
				else if($row['status']==2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';
					
				
				$regnumber = '';
				$action = '';
				$send_mail = '';
				$center = $this->master_model->getRecords("center_master",array('center_code'=>$row['exam_center_code']),'center_name');
				
				if(count($center))
				{
					$result[$i]['center_name'] = $center[0]['center_name'];
				}
				else
				{
					$result[$i]['center_name'] = '';
				}
				
				$medium = $this->master_model->getRecords("medium_master",array('medium_code'=>$row['exam_medium']),'medium_description');
				
				if(count($medium))
				{
					$result[$i]['medium_description'] = $medium[0]['medium_description'];
				}
				else
				{
					$result[$i]['medium_description'] = '';
				}
				
				//Customized columns
				$data['action'][] = $action;
				$result[$i]['send_mail'] = $send_mail;
				$result[$i]['createdon'] = date('d-m-Y',strtotime($row['createdon']));
				
				$i++;
			}
			
			$data['result'] = $result;
			
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
		}

		$json_res = json_encode($data);
		echo $json_res;
	}

	/*DOWNLOAD CSV ON DATE FILTER POOJA MANE : 21/07/2022*/
	public function csv()
	{
		
		if(isset($_POST['submit']))
		{
			/*GET DATES FROM FORM INPUT*/
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			/*POOJA MANE : 25/07/2022 FOR SELECTED DROP DOWN*/
			$field = $this->input->post('searchBy');
			$value = $this->input->post('SearchVal');

		switch($field)
		{
			case '01'	:	$field = 'c.exam_code'; $value = '997';
							break;
			case '02'	:	$field = 'e.mobile';
							break;
			case '03'	:	$field = 'c.exam_center_code';
							break;
			case '04'	:	$field = 'c.regnumber';
							break;
			case '05'	:	$field = 'transaction_no';
							break;
			/*POOJA MANE : 12/7/2022*/				
			case '06'	:	$field = 'e.emp_id';
							break;
			/*POOJA MANE : 12/7/2022*/								
			case '07'	:	$field = 'all';
							break;	
		}
		
		if($field == "all")
		{
			$field = 'c.exam_code, c.exam_center_code, c.regnumber, transaction_no, e.emp_id,e.mobile';
		}

		if(!empty($from_date &&  $to_date))
	  	{
			$where = "date(b.date) BETWEEN '$from_date' AND '$to_date' AND";
      	} 

			/*CSV HEADERS COLUMN*/
			$csv = "Registration No,Employee Id,Name,Mobile,Exam Name,Exam Fee,Exam Medium,Center Name,Exam Date,Transaction No,Payment Status,Transaction Time\n";

				$query = $this->db->query("SELECT b.transaction_no,c.regnumber,b.status,b.date,c.exam_medium,c.exam_fee,d.description,c.exam_center_code,b.transaction_details,e.emp_id,b.ref_id,mem_mem_no,admitcard_image,ac.exam_date,mam_nam_1,cm.center_name,e.mobile
				FROM member_exam c  
				LEFT JOIN exam_master d ON d.exam_code=c.exam_code 
				LEFT JOIN payment_transaction b ON b.ref_id=c.id 
				LEFT JOIN `employee_data_ippb` `e` ON `e`.`regnumber`=`c`.`regnumber`
				LEFT JOIN admit_card_details ac ON ac.mem_exam_id= c.id
				LEFT JOIN center_master cm ON cm.center_code = c.exam_center_code
				WHERE c.exam_code = '997' AND pay_type = 2 AND `pay_status` = 1  AND  remark = 1 AND ".$where." ".$field." = '".$value."'
				GROUP BY b.transaction_no");
				//echo $this->db->last_query();//exit;
				$result = $query->result_array();
			
			foreach($result as $record)
			{	
				/*CSV COLUMN VALUES*/ 
				/*Registration No,Employee Id,Name,Exam Name,Exam Fee,Exam Medium,	Center Name,Exam Date,Transaction No,Payment Status,Transaction Time*/

				//MEDIUM DESCIPTION
			    $medium = $record['exam_medium'];
				$where = "medium_code = '$medium' ";
				$qry = $this->db->query("SELECT medium_description FROM medium_master WHERE ".$where." ");
				$medium = $qry->result_array();

				//MOBILE DETAILS
				/*$regnumber = $record['regnumber'];
				$where = "regnumber = '$regnumber' ";
				$qry = $this->db->query("SELECT mobile FROM member_registration WHERE ".$where." ");
				$mobile = $qry->result_array();
				$record['mobile'] = $mobile[0]['mobile'];

				//EMP DETAILS
				$mobile = $mobile['mobile'];
				$where = "mobile = '$mobile' ";
				$qry = $this->db->query("SELECT emp_id FROM member_registration_ippb WHERE ".$where." ");
				$emp_id = $qry->result_array();
				$record['emp_id'] = $emp_id[0]['emp_id'];*/
				
				if(count($medium))
				{
					$record['medium_description'] = $medium[0]['medium_description'];
				}
				else
				{
					$record['medium_description'] = '';
				}
				
				 $csv.= $record['regnumber'].','.$record['emp_id'].','.$record['mam_nam_1'].',"'.$record['mobile'].'","'.$record['description'].'","'.$record['exam_fee'].'","'.$record['medium_description'].'","'.$record['center_name'].'","'.$record['exam_date'].'","'.$record['transaction_no'].'","'.$record['transaction_details'].'",'.$record['date']."\n";
				 //print_r($csv);die;
			}
		
	        $filename = "IPPB_Exam_Registrations.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			$csv_handler = fopen('php://output', 'w');
	 		fwrite ($csv_handler,$csv);
	 		fclose ($csv_handler);
		}

	}
	/*DOWNLOAD CSV FILTER END POOJA MANE : 21/07/2022*/

	/*CHECK EMP ID EXIST POOJA MANE : 19-10-2022*/
	public function check_empid_exist_ajax()
    {
       
        if ($_POST['emp_id'] != '') {
            $emp_id = $_POST['emp_id'];

            $prev_count = $this->master_model->getRecordCount('member_registration_ippb', array('emp_id' => $emp_id), 'id');

            if ($prev_count == 0) {
                echo "true";
            } else {

                echo "false";
            }
        } else {
            echo "false";
        }
    }
    /*CHECK EMP ID EXIST END POOJA MANE : 19-10-2022*/

	public function test(){
		$this->load->view('admin/ippb_dashboard/test');
	}

	public function registered_member_search_form(){

		// print_r(); exit;
		$data = array();
		$data['mem_info'] = array();
		
		
		// print_r($id); exit;

		if(isset($_GET) && !empty($_GET)){
			$id = $_GET['regnumber'];
			
			$this->db->where('member_registration.regnumber  =', $id);
			$this->db->where('member_registration.excode =', '997');
			$this->db->where('member_registration.isactive =', '1');
			$this->db->join('member_registration', 'member_registration.mobile = member_registration_ippb.mobile');
			$mem_info = $this->master_model->getRecords('member_registration_ippb');
			//echo $this->db->last_query();exit;
			if(!empty($mem_info))
			{
				
				$data['mem_info'] = $mem_info;
				
			}
			
		}
		$this->load->view('admin/ippb_dashboard/registered_member_search_form',$data);
	}

	public function edit_registered_member($id) {
		$id=base64_decode($id);

		if(isset($_POST) && !empty($_POST)) {
		//	echo'<pre>';print_r($_POST);exit;
			$this->form_validation->set_rules('sel_namesub', 'Sub Name', 'trim|required');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required');

			$firstname    = $this->input->post("firstname");
			$sel_namesub  = $this->input->post("sel_namesub");
			$middlename   = $this->input->post("middlename");
			$lastname     = $this->input->post("lastname");
			$dob     = $this->input->post("dob1");

			$update_data	=	array();
			$update_data['firstname']           = $firstname;
			$update_data['middlename']           = $middlename;
			$update_data['lastname']           = $lastname;
			$update_data['kyc_edit']            = '1';
			$update_data['kyc_status']          = '0';
			$update_data['dateofbirth']        = date('Y-m-d', strtotime($dob));
			$update_data['editedon'] = date('Y-m-d H:i:s');
            $update_data['editedby'] = 'ippbadmin';
			$this->master_model->updateRecord('member_registration', $update_data, array(
				'regid'     => $id,
			));

			$mam_nam_1=trim($firstname.' '.$middlename.' '.$lastname);
			$update_admitcard_data['mam_nam_1'] = $mam_nam_1;
			$this->master_model->updateRecord('admit_card_details', $update_admitcard_data, array(
				'mem_mem_no'     => $this->input->post("regnumber"),
				'exm_cd'		=>	997,
				'remark'=>1
			));
                        
		//	echo $this->db->last_query();exit;
		}
		
		$this->db->where('member_registration.regid  =', $id);
		$this->db->where('member_registration.excode =', '997');
		$this->db->where('member_registration.isactive =', '1');
		$this->db->join('member_registration', 'member_registration.mobile = member_registration_ippb.mobile');
		$mem_info = $this->master_model->getRecords('member_registration_ippb');
		//echo $this->db->last_query();exit;
		if(!empty($mem_info))
		{
			//echo'<pre>';print_r($mem_info);exit;
			
			$data['regData'] = $mem_info[0];
			$this->load->view('admin/ippb_dashboard/edit_registered_member',$data);
			
		}
	}

	public function re_generate_admit_card ($id) {
		$this->load->helper('custom_admitcard_helper');
		$exam_code = 997;     
		$exam_period = 851;   
		$array=array($id); //array(510532859);
		
		//echo $id;exit;
		foreach($array as $rec){ 
			
			$admitcard_url= genarate_admitcard_ippb($rec,$exam_code,$exam_period).'';  
			//echo'<script>window.open("'.$admitcard_url.'", "_blank");</script>';
			redirect(base_url() . $admitcard_url);
		}
	
	}
	// ##---------Edit Images(Vrushali)-----------##
	public function editimages($regid,$reg_no)
	{
		$kyc_update_data = array();
		$kyc_edit_flag = 0;
	
		$applicationNo = $reg_no;
		$reg_id=base64_decode($regid);
		$reg_no=base64_decode($reg_no);

		if ($reg_id != '' && $reg_no != '') {
			if (is_numeric($reg_id)) {
				$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = '';
				$member_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id,'isactive'=>'1'), 'scannedphoto,scannedsignaturephoto,	idproofphoto, declaration, regnumber,registrationtype');
				
				if (isset($_POST['btnSubmit'])) {
					if ($_FILES['scannedphoto']['name'] == '' && $_FILES['scannedsignaturephoto']['name'] == '' && $_FILES['idproofphoto']['name'] == '' && $_FILES['declaration']['name'] == '') {
					
						$this->session->set_flashdata('error', 'Please Change atleast One Value');
						redirect(base_url() . 'admin/ippb/IppbDashboard/edit_registered_member/' . base64_encode($reg_id) );
					}
					if ($_FILES['scannedphoto']['name'] != '') {
						$this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[50]');
					}
					if ($_FILES['scannedsignaturephoto']['name'] != '') {
						$this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[50]');
					}
					if ($_FILES['idproofphoto']['name'] != '') {
						$this->form_validation->set_rules('idproofphoto', 'id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
					}
					if ($_FILES['declaration']['name'] != '') {
						$this->form_validation->set_rules('declaration', 'declaration', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
					}

					if ($this->form_validation->run() == TRUE) {
						$prev_edited_on = '';
						$prev_photo_flg = "N";
						$prev_signature_flg = "N";
						$prev_id_flg = "N";
						$prev_declaration_flg = "N";
						$prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg,declaration_flg');
						if (count($prev_edited_on_qry)) {
							$prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
							$prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
							$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
							$prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
							$prev_declaration_flg = $prev_edited_on_qry[0]['declaration_flg'];

							if ($prev_edited_on != date('Y-m-d')) {
								$this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N', 'declaration_flg' => 'N'), array('regid' => $reg_id));
							}
						}


						$scannedphoto_file = '';
						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$photo_flg = 'N';
						} else {
							$photo_flg = $prev_photo_flg;
						}
						$edited = '';
						if (isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name'] != '') {

							$path = "./uploads/photograph";
							$date = date_create();
						
							$new_filename = 'p_' . $applicationNo;
							$uploadData = upload_file('scannedphoto', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_photo'] = 1;
								// No need to unlink as it overwrites file 
								//@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);
								$scannedphoto_file = $uploadData['file_name'];
								$photo_flg = 'Y';
								$edited .= 'PHOTO || ';
							} else {
								$scannedphoto_file = $this->input->post('scannedphoto1_hidd');
							}
						} else {
							$scannedphoto_file = $this->input->post('scannedphoto1_hidd');
						}

						// Upload DOB Proof
						$scannedsignaturephoto_file = '';
						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$signature_flg = 'N';
						} else {
							$signature_flg = $prev_signature_flg;
						}

						if ($_FILES['scannedsignaturephoto']['name'] != '') {

							$path = "./uploads/scansignature";
							$date = date_create();
							//$timestamp = date_timestamp_get($date);
							//$new_filename = 'sign_'.rand(1,99999);
							$new_filename = 's_' . $applicationNo;
							$uploadData = upload_file('scannedsignaturephoto', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_sign'] = 1;
								$scannedsignaturephoto_file = $uploadData['file_name'];
								$signature_flg = 'Y';
								$edited .= 'SIGNATURE || ';
							} else {
								$scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
							}
						} else {
							$scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
						}

						// Upload Education Certificate
						$idproofphoto_file = '';

						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$id_flg = 'N';
						} else {
							$id_flg = $prev_id_flg;
						}

						if ($_FILES['idproofphoto']['name'] != '') {
							$path = "./uploads/idproof";
							$date = date_create();
							$new_filename = 'pr_' . $applicationNo;
							$uploadData = upload_file('idproofphoto', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_proof'] = 1;
								$idproofphoto_file = $uploadData['file_name'];
								$id_flg = 'Y';
								$edited .= 'PROOF || ';
							} else {
								$idproofphoto_file = $this->input->post('idproofphoto1_hidd');
							}
						} else {
							$idproofphoto_file = $this->input->post('idproofphoto1_hidd');
						}



						// Upload Declaration Certificate, this code added by pratibha borse on 22 april 22
						$declaration_file = '';

						if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
							$declaration_flg = 'N';
						} else {
							$declaration_flg = $prev_declaration_flg;
						}

						if ($_FILES['declaration']['name'] != '') {
							$path = "./uploads/declaration";
							$date = date_create();
							$new_filename = 'pr_' . $applicationNo;
							$uploadData = upload_file('declaration', $path, $new_filename, '', '', TRUE);
							if ($uploadData) {
								$kyc_edit_flag = 1;
								$kyc_update_data['edited_mem_declaration'] = 1;
								$declaration_file = $uploadData['file_name'];
								$declaration_flg = 'Y';
								$edited .= 'DECLARATION || ';
							} else {
								$declaration_file = $this->input->post('declaration_hidd');
							}
						} else {
							$declaration_file = $this->input->post('declaration_hidd');
						}

						$update_info = array(
							'scannedphoto' => $scannedphoto_file,
							'scannedsignaturephoto' => $scannedsignaturephoto_file,
							'idproofphoto' => $idproofphoto_file,
							'declaration' => $declaration_file,
							'images_editedon' => date('Y-m-d H:i:s'),
							'images_editedby' => $this->session->userdata('username'),
							'images_editedbyadmin' => $this->session->userdata['id'],
							'photo_flg' => $photo_flg,
							'signature_flg' => $signature_flg,
							'id_flg' => $id_flg,
							'declaration_flg' => $declaration_flg,
							'kyc_edit' => $kyc_edit_flag,
							'kyc_status' => '0'
						);

						if ($this->master_model->updateRecord('member_registration', $update_info, array('regid' => $reg_id, 'regnumber' => $reg_no))) {
							$finalStr = '';
							if ($edited != '') {
								$edit_data = trim($edited);
								$finalStr = rtrim($edit_data, "||");
							}
							//log_profile_admin($log_title = "Profile images updated successfully", $finalStr, 'image', $reg_id, $reg_no);

							if ($kyc_edit_flag == 1) {
								$kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $reg_no), '', array('kyc_id' => 'DESC'), '0', '1');
								if (count($kycmemdetails) > 0) {
									$kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
									$kyc_update_data['kyc_state'] = '2';
									$kyc_update_data['kyc_status'] = '0';

									$this->db->like('allotted_member_id', $reg_no);
									$this->db->or_like('original_allotted_member_id', $reg_no);
									$this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
									$check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
									// $check_duplicate_entry=$this->master_model->getRecords('admin_kyc_users',array('list_type'=>'New'));
									if (count($check_duplicate_entry) > 0) {
										foreach ($check_duplicate_entry as $row) {
											$allotted_member_id = $this->removeFromString($row['allotted_member_id'], $reg_no);
											$original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $reg_no);
											$admin_update_data = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);

											$this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));
										}
									}

									//$kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
									if ($kycmemdetails[0]['kyc_status'] == '0') {
										$this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));
										$this->KYC_Log_model->create_log('kyc member edited images', '', '', $reg_no, serialize($update_info));
									}


									//check membership count
									$check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
									if (count($check_membership_cnt) > 0) {
										//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$reg_no);
										/* update dowanload count 8-8-2017 */
										$this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $reg_no));
										/* Close update dowanload count */
										/* User Log Activities : Pooja */
										$uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $reg_no), 'regid');
										$user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
										$log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
										$log_message = serialize($user_info);
										$rId = $uerlog[0]['regid'];
										$regNo = $this->session->userdata('regnumber');
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										/* Close User Log Actitives */
									}
								}

								//echo $this->db->last_query();exit;
								//change by pooja godse for  memebersgip id card  dowanload count reset
								//check membership count
								$check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
								if (count($check_membership_cnt) > 0) {
									//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$reg_no);
									/* update dowanload count 8-8-2017 */
									$this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $reg_no));
									/* Close update dowanload count */
									/* User Log Activities : Pooja */
									$uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $reg_no), 'regid');
									$user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $reg_no));
									$log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
									$log_message = serialize($user_info);
									$rId = $uerlog[0]['regid'];
									$regNo = $this->session->userdata('regnumber');
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									/* Close User Log Actitives */
								}

								//logadminactivity($log_title = "kyc member edited images id : " . $reg_id, $description = serialize($update_info));
							}



							$this->session->set_flashdata('success', 'Profile has been updated successfully !!');
							redirect(base_url() . 'admin/ippb/IppbDashboard/edit_registered_member/' . base64_encode($reg_id));

						
						} else {
							$this->session->set_flashdata('error', 'Error while updating profile !!');
							redirect(base_url() . 'admin/ippb/IppbDashboard/edit_registered_member/' . base64_encode($reg_id));
						}
					} else {
						$data['validation_errors'] = validation_errors();
					}
				}
				$data['member_info'] = $member_info;
				$this->load->view('admin/ippb_dashboard/reg_edit_images', $data);
			} else {
				redirect(base_url() . 'admin/ippb/ippb_dashboard/registered_member_search_form');
			}
		} else {
			redirect(base_url() . 'admin/ippb/ippb_dashboard/registered_member_search_form');
		}
	}

}
