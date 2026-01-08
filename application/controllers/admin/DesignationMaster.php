<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DesignationMaster extends CI_Controller {
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
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	
	public function index()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data = $this->getUserInfo();
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Designation</a></li>
							   </ol>';
		
		$this->load->view('admin/masters/designation_list',$data);
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
		
		//$this->db->join('administrators','administrators.id=exam_master.id','LEFT');
		$this->db->where('designation_delete',0);		
		$total_row = $this->UserModel->getRecordCount("designation_master a",$field,$value);
		
		$url = base_url()."admin/DesignationMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id designation_id,a.dcode,a.dname,a.level';
		$this->db->where('designation_delete',0);				
		$res = $this->UserModel->getRecords("designation_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/DesignationMaster/edit/'.$row['designation_id'].'">Edit | </a> <a href="'.base_url().'admin/DesignationMaster/delete/'.$row['designation_id'].'" onclick="'.$confirm.'">Delete</a>';
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
	
	
	public function add(){
		$data = array();
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('dcode','Designation Code','trim|required');
			$this->form_validation->set_rules('dname','Designation Name','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$insert_data = array(	
								 'dcode'      => $this->input->post('dcode'),
								 'dname'      => $this->input->post('dname'),
								 'level'      => $this->input->post('level'),
								 'created_by' => $this->UserID,
								 'created_on' => date("Y-m-d H:i:s")
							   );
				
				if($this->master_model->insertRecord('designation_master',$insert_data))
				{
					$this->session->set_flashdata('success','Record added successfully');
					logadminactivity($log_title = "Designation master - Record added successfully", $log_message = "New Designation recorded added. dcode= ".$this->input->post('dcode'));
					redirect(base_url().'admin/DesignationMaster');
				}
				else
				{
					$this->session->set_flashdata('error','Error occured while adding record');
					logadminactivity($log_title = "Designation master - Record insertion failed", $log_message = "New Designation recorded add failed. dcode= ".$this->input->post('dcode'));
					redirect(base_url().'admin/DesignationMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Designation</a></li>
					<li class="active">Add</li>
				</ol>';
				
		$data['designationRes'] = array(
									'dcode' =>'',
									'dname'	=>'',
									'level'	=>''
								  );
		
		$this->load->view('admin/masters/designation_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['designationRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$designationRes = $this->master_model->getRecords('designation_master',array('id'=>$id));
			if(count($designationRes))
			{
				$data['designationRes'] = $designationRes[0];
			}
			else
			{
				redirect(base_url().'admin/DesignationMaster');
			}
		}
		else
		{
			redirect(base_url().'admin/DesignationMaster');
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('dcode','Designation Code','trim|required');
			$this->form_validation->set_rules('dname','Designation Name','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$update_data = array(	
								 'dcode'       => $this->input->post('dcode'),
								 'dname'       => $this->input->post('dname'),
								 'level'       => $this->input->post('level'),
								 'modified_by' => $this->UserID
							   );
				
				if($this->master_model->updateRecord('designation_master',$update_data,array('id'=>$id)))
				{
					$this->session->set_flashdata('success','Record updated successfully');
					logadminactivity($log_title = "Designation master - Record edited successfully", $log_message = "id= ".$id);
					redirect(base_url().'admin/DesignationMaster');
				}
				else
				{
					$this->session->set_flashdata('error','Error occured while updating record');
					logadminactivity($log_title = "Designation master - Record edit failed", $log_message = "id= ".$id);
					redirect(base_url().'admin/DesignationMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Designation</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$this->load->view('admin/masters/designation_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('designation_delete'=>1, 'modified_by' => $this->UserID);
			
			if($this->master_model->updateRecord('designation_master', $update_data, array('id'=>$id)))
			{
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/DesignationMaster');
			}
			else
			{
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/DesignationMaster');
			}
		}
	}
	
	
	
	public function getUserInfo(){
		$data['AdminUser']=$this->UserModel->getUserInfo($this->UserID);
		return $data;
	}	

	public function import(){
		
		$this->load->helper(array('form', 'url'));
		$data = $this->getUserInfo();
		$data['error'] = "";
		$data['success'] = "";
		$target_file = "";

		if ($this->input->post('btnSubmit'))
		{			
			if (isset($_FILES["designationmasterfile"]))
			{
				if ($_FILES["designationmasterfile"]["type"] == "text/plain")
				{
					if ($_FILES["designationmasterfile"]["size"] <= (1024*1024*2))  // Max 2MB
					{
						$target_file = "uploads/admin/masters_imported_files/"."DESIGNATION_MASTER_".$this->UserID."_".date('Y-m-d_his').".txt";
						
						// move uploaded file in file logs dir
						move_uploaded_file($_FILES["designationmasterfile"]["tmp_name"], $target_file);
						
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
										"dcode" => $raw_array[0],  // dcode
										"dname" => $raw_array[1],  // dname
										"level" => $raw_array[2],  // level

										"created_by" => $this->UserID,
										"created_on" => date("Y-m-d H:i:s")
									);
									$this->master_model->insertRecord('designation_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==3)
									{	
										$firstlineflag = 1;  // Skip first header line
										master_db_backup($basetable = "designation_master"); // keep old and new masters in DB 
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'admin/DesignationMaster/import');
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
				logadminactivity($log_title = "Designation master import sucessfull", $log_message = "New Designation master file import sucessfull file=".$target_file);
			}
			else
			{
				logadminactivity($log_title = "Designation master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}	
		}

		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Designation</a></li>
								<li class="active">Import</li>
							</ol>';
		
		$this->load->view('admin/masters/designation_import', $data);
	}
	
	public function download(){
		
		$data = "Series No|dcode|dname|level\n";
		
		$this->db->where('designation_delete',0);
		$designation_list = $this->Master_model->getRecords("designation_master a");

		foreach($designation_list as $id => $designation_details) {
		  $data .= $designation_details['id']."|".$designation_details['dcode']."|".$designation_details['dname']."|".$designation_details['level']."\n";
		}
		
		logadminactivity($log_title = "Designation master downloaded", $log_message = "");

		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="DESIGNATION_MASTER.txt.gz"');
		echo gzencode($data, 9); exit();
	}
	
	public function search(){
		
		$this->load->view('admin/'.$page,$data);
	}
	
}