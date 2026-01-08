<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StateMaster extends CI_Controller {
	public $UserID;
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}
		
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}
				
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->UserID=$this->session->id;
		$this->load->helper('master_helper');
		$this->load->helper('upload_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	
	public function index() {
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Manage State</li>
		</ol>';
		$data["state_list"] = $this->Master_model->getRecords("state_master");
		$this->load->view('admin/masters/state_list',$data);
	}
	
	public function getList(){
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
		if($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		$this->db->where('state_delete',0);	
		$total_row = $this->UserModel->getRecordCount("state_master",$field,$value);
		$url = base_url()."admin/StateMaster/getList/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$this->db->where('state_delete',0);
		$res = $this->UserModel->getRecords("state_master", '', $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		if($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			foreach($result as $row) {
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/StateMaster/edit/'.$row['id'].'">Edit |</a><a href="'.base_url().'admin/StateMaster/delete/'.$row['id'].'" onclick="'.$confirm.'">Delete </a>';
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
	public function add() {
		$data = array();
		if(isset($_POST['btnSubmit'])) {
			$this->form_validation->set_rules('state_code','State Code','trim|required|max_length[3]|xss_clean');
			$this->form_validation->set_rules('state_name','State Name','trim|required|xss_clean');
			$this->form_validation->set_rules('start_pin','Start Pin','trim|numeric|max_length[6]|xss_clean');
			$this->form_validation->set_rules('end_pin','End Pin','trim|numeric|max_length[6]|xss_clean');
			$this->form_validation->set_rules('zone_code','Zone Code','trim|max_length[2]|xss_clean');
			$this->form_validation->set_rules('state_no','State No','trim|numeric|max_length[2]|xss_clean');
			if($this->form_validation->run()==TRUE) {
				$insert_data = array(	
					'state_code'	=>filter($this->input->post('state_code')),
					'state_name'	=>filter($this->input->post('state_name')),
					'start_pin'		=>filter($this->input->post('start_pin')),
					'end_pin'		=>filter($this->input->post('end_pin')),
					'zone_code'		=>filter($this->input->post('zone_code')),
					'state_no'		=>filter($this->input->post('state_no'))
				);
				if($this->master_model->insertRecord('state_master',$insert_data)) {
					$logs_data = array(
						'date' => date('Y-m-d H:i:s'),
						'title' => 'Add State Successful',
						'description'=>serialize($insert_data),
						'userid' => $this->UserID,
						'ip' => $this->input->ip_address()
					);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'admin/StateMaster');
				}
				else {
					$logs_data = array(
						'date'=>date('Y-m-d H:i:s'),
						'title'=>'Add State Unsuccessful',
						'description'=>serialize($insert_data),
						'userid'=>$this->UserID,
						'ip'=>$this->input->ip_address()
					);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'admin/StateMaster/add');
				}
			}
			else {
				$data['validation_errors'] = validation_errors(); 
			}
		}
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage State Master</a></li>
			<li class="active">Add</li>
		</ol>';
		$this->load->view('admin/masters/state_add',$data);
	}
	
	public function edit() {
		$data = array();
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id)) {
			$stateRes = $this->master_model->getRecords('state_master',array('id'=>$id));
			if(count($stateRes)) {
				$data['stateRes'] = $stateRes[0];
			}
			else
			{
				redirect(base_url().'admin/StateMaster');
			}
		}
		else
		{
			redirect(base_url().'admin/StateMaster');
		}
		if(isset($_POST['btnSubmit'])) {
			$this->form_validation->set_rules('state_code','State Code','trim|required|max_length[3]|xss_clean');
			$this->form_validation->set_rules('state_name','State Name','trim|required|xss_clean');
			$this->form_validation->set_rules('start_pin','Start Pin','trim|numeric|max_length[6]|xss_clean');
			$this->form_validation->set_rules('end_pin','End Pin','trim|numeric|max_length[6]|xss_clean');
			$this->form_validation->set_rules('zone_code','Zone Code','trim|max_length[2]|xss_clean');
			$this->form_validation->set_rules('state_no','State No','trim|numeric|max_length[2]|xss_clean');
			
			if($this->form_validation->run()==TRUE) {
				$update_data = array(	
					'state_code'	=>filter($this->input->post('state_code')),
					'state_name'	=>filter($this->input->post('state_name')),
					'start_pin'		=>filter($this->input->post('start_pin')),
					'end_pin'		=>filter($this->input->post('end_pin')),
					'zone_code'		=>filter($this->input->post('zone_code')),
					'state_no'		=>filter($this->input->post('state_no'))
				);
				if($this->master_model->updateRecord('state_master', $update_data, array('id'=>$id))) {
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $stateRes[0];
					$logs_data = array(
						'date'=> date('Y-m-d H:i:s'),
						'title' => 'Edit State Successful',
						'description' => serialize($desc),
						'userid' => $this->UserID,
						'ip'=>$this->input->ip_address()
					);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'admin/StateMaster');
				}
				else {
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $stateRes[0];
					$logs_data = array(
						'date' => date('Y-m-d H:i:s'),
						'title' => 'Edit State Unsuccessful',
						'description' => serialize($desc),
						'userid' => $this->UserID,
						'ip' => $this->input->ip_address()
					);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'admin/StateMaster/edit/'.$id);
				}
			}
			else {
				$data['validation_errors'] = validation_errors(); 
			}
		}
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage State Master</a></li>
			<li class="active">Edit</li>
	   </ol>';
		$this->load->view('admin/masters/state_edit',$data);
	}
	
	public function delete() {
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id)) {
			$update_data = array('state_delete'=>1);
			if($this->master_model->updateRecord('state_master', $update_data, array('id'=>$id))) {
				$logs_data = array(
					'date' => date('Y-m-d H:i:s'),
					'title' => 'Delete State Successful',
					'description' => serialize(array('id'=>$id)),
					'userid' => $this->UserID,
					'ip' => $this->input->ip_address()
				);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/StateMaster');
			}
			else {
				$logs_data = array(
					'date'=>date('Y-m-d H:i:s'),
					'title'=>'Delete State Unsuccessful',
					'description'=>serialize(array('id'=>$id)),
					'userid'=>$this->UserID,
					'ip'=>$this->input->ip_address()
				);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/StateMaster');
			}
		}
	}
	
	public function getUserInfo(){
		$data['AdminUser'] = $this->UserModel->getUserInfo($this->UserID);
		return $data;
	}	
	
	public function search(){
		$this->load->view('admin/'.$page,$data);
	}
	
	public function import(){
		$this->load->helper(array('form', 'url'));
		$data = $this->getUserInfo();
		$data['error'] = "";
		$data['success'] = "";
		$target_file = "";
		if ($this->input->post('btnSubmit')) {			
			if (isset($_FILES["masterfile"])) {
				if ($_FILES["masterfile"]["type"] == "text/plain") {
					if ($_FILES["masterfile"]["size"] <= (1024*1024*2)) {  // Max 2MB
						$target_file = "uploads/admin/masters_imported_files/"."STATE_MASTER_".$this->UserID."_".date('Y-m-d_his').".txt";
						// move uploaded file in file logs dir
						move_uploaded_file($_FILES["masterfile"]["tmp_name"], $target_file);
						
						$handle = fopen($target_file, "r");
						$firstlineflag = 0;
						if ($handle) {
							while (($line = fgets($handle)) !== false) {
								// process the txt file line by line
								if($firstlineflag == 1)
								{
									$raw_array = explode("|" ,$line);
									//insert in DB
									$insert_data = array(	
										'state_code'	=> $raw_array[0],
										'state_name'	=> $raw_array[1],
										'start_pin'		=> $raw_array[2],
										'end_pin'		=> $raw_array[3],
										'zone_code'		=> $raw_array[4],
										'created_by'	=> $this->UserID,
										'created_on'	=> date('Y-m-d H:i:s'),
									);
									$this->master_model->insertRecord('state_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==5)
									{	
										$firstlineflag = 1;  // Skip first header line
										master_db_backup($basetable = "state_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'admin/StateMaster/import');
									}
								}
							}
							fclose($handle);
						} else {
							$data['error'] = "Error in processing of uploaded file";
						}
						// create log for file import
						$data['success'] = "File imported successfully"; 
					}
					else
					{
						$data['error'] = "The selected file is not valid, only txt file of maximum 2MB is allowed"; 
					}
					
				}
				else
				{
					$data['error'] = "Please upload valid txt file";
				}
			}
			else
			{
				$data['error'] = "Please upload txt file";
			}
			
			if ($data['success'] != "")
			{
				logadminactivity($log_title = "State master import sucessfull", $log_message = "New State master file import sucessfull file=".$target_file);
			}
			else
			{
				logadminactivity($log_title = "State master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}
				
		}
		$data['menu_title'] = 'Manage State Master'; 
		$data['title'] = 'Import State Master';
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage State</a></li>
								<li class="active">Import</li>
							</ol>';
		$data['sample_file'] = 'STATE_MASTER_ALL_112.TXT';
		$this->load->view('admin/masters/master_import', $data);
	}
	
	public function download(){
		$data = "State Code|State Name|Start Pin|End Pin|Zone Code\n";
		$this->db->where('state_delete',0);
		$state_list = $this->Master_model->getRecords("state_master");
		foreach($state_list as $id => $state_details) {
		  $data .= $state_details['state_code']."|".$state_details['state_name']."|".$state_details['start_pin']."|".$state_details['end_pin']."|".$state_details['zone_code']."\n";
		}
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="state_master.txt.gz"');
		echo gzencode($data, 9); exit();
	}
}