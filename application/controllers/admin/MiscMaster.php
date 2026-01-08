<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MiscMaster extends CI_Controller {
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
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master","","exam_name,exam_code,description");
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Misc</a></li>
							</ol>';
		
		$this->load->view('admin/masters/misc_list',$data);
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
		
		
		$this->db->where('misc_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master','exam_master.exam_code=misc_master.exam_code','LEFT');		
		$total_row = $this->UserModel->getRecordCount("misc_master",$field,$value);
		//$data['query'] = $this->db->last_query();
		
		$url = base_url()."admin/MiscMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id misc_id,a.exam_period,a.exam_month,a.trg_value,b.description,b.exam_code';
		$this->db->where('misc_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		$res = $this->UserModel->getRecords("misc_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();exit;
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/MiscMaster/edit/'.$row['misc_id'].'">Edit | </a> <a href="'.base_url().'admin/MiscMaster/delete/'.$row['misc_id'].'" onclick="'.$confirm.'">Delete</a>';
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
			$this->form_validation->set_rules('exam_code','Exam Code','trim|required');
			//is_validunique[misc_master.exam_code.id.'.$this->session->userdata('regid').'.isactive.1]
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('exam_month','Exam Month','trim|required');
			$this->form_validation->set_rules('trg_value','TRG Value','trim');
			if($this->form_validation->run()==TRUE)
			{
				$cnt = $this->master_model->getRecordCount('misc_master',array('exam_code'=>$this->input->post('exam_code'),'misc_delete'=>0));
				if($cnt == 0)
				{
					$insert_data = array(	'exam_code'		=>$this->input->post('exam_code'),
											'exam_period'	=>$this->input->post('exam_period'),
											'exam_month'	=>$this->input->post('exam_month'),
											'trg_value'		=>$this->input->post('trg_value'),
										);
					
					if($this->master_model->insertRecord('misc_master',$insert_data))
					{
						$logs_data = array(
											'date'=>date('Y-m-d H:i:s'),
											'title'=>'Add Misc Successful',
											'description'=>serialize($insert_data),
											'userid'=>$this->UserID,
											'ip'=>$this->input->ip_address()
										);
						$this->master_model->insertRecord('adminlogs',$logs_data);
						
						$this->session->set_flashdata('success','Record added successfully');
						redirect(base_url().'admin/MiscMaster');
					}
					else
					{
						$logs_data = array(
											'date'=>date('Y-m-d H:i:s'),
											'title'=>'Add Misc Unsuccessful',
											'description'=>serialize($insert_data),
											'userid'=>$this->UserID,
											'ip'=>$this->input->ip_address()
										);
						$this->master_model->insertRecord('adminlogs',$logs_data);
						
						$this->session->set_flashdata('error','Error occured while adding record');
						redirect(base_url().'admin/MiscMaster/add');
					}
				}
				else
				{
					$this->session->set_flashdata('error','Exam code already exist');
					redirect(base_url().'admin/MiscMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Misc</a></li>
					<li class="active">Add</li>
				</ol>';
				
		$data['miscRes'] = array(	'exam_code'		=>'',
									'exam_period'	=>'',
									'exam_month'	=>'',
									'trg_value'		=>'',
								);
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$this->load->view('admin/masters/misc_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['miscRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$miscRes = $this->master_model->getRecords('misc_master',array('id'=>$id));
			if(count($miscRes))
			{
				$data['miscRes'] = $miscRes[0];
			}
			else
			{
				redirect(base_url().'admin/MiscMaster');
			}
		}
		else
		{
			redirect(base_url().'admin/MiscMaster');
		}
		
		if(isset($_POST['btnSubmit']))
		{
			/*$this->form_validation->set_rules('exam_code','Exam Code','trim|required');*/
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('exam_month','Exam Month','trim|required');
			$this->form_validation->set_rules('trg_value','TRG Value','trim');
			if($this->form_validation->run()==TRUE)
			{
				$update_data = array(	/*'exam_code'		=>$this->input->post('exam_code'),*/
										'exam_period'	=>$this->input->post('exam_period'),
										'exam_month'	=>$this->input->post('exam_month'),
										'trg_value'		=>$this->input->post('trg_value'),
									);
				
				if($this->master_model->updateRecord('misc_master', $update_data, array('id'=>$id)))
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $miscRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Misc Successful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'admin/MiscMaster');
				}
				else
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $miscRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Misc Unsuccessful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'admin/MiscMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Misc</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$this->load->view('admin/masters/misc_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('misc_delete'=>1);
			
			if($this->master_model->updateRecord('misc_master', $update_data, array('id'=>$id)))
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Misc Successful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/MiscMaster');
			}
			else
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Misc Unsuccessful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/MiscMaster');
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
		$target_file = '';

		if ($this->input->post('btnSubmit'))
		{			
			if (isset($_FILES["miscmasterfile"]))
			{
				if ($_FILES["miscmasterfile"]["type"] == "text/plain")
				{
					if ($_FILES["miscmasterfile"]["size"] <= (1024*1024*2))  // Max 2MB
					{
						$target_file = "uploads/admin/masters_imported_files/"."MISC_MASTER_".$this->UserID."_".date('Y-m-d_his').".txt";
						
						// move uploaded file in file logs dir
						move_uploaded_file($_FILES["miscmasterfile"]["tmp_name"], $target_file);
						
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
										'exam_code'		=>$raw_array[0],
										'exam_period'	=>$raw_array[1],
										'exam_month'	=>$raw_array[2],
										'trg_value'		=>$raw_array[3],
									);
									
									$this->master_model->insertRecord('misc_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==4)
									{	
										$firstlineflag = 1;  // Skip first header line
										master_db_backup($basetable = "misc_master"); // keep old and new master in DB
										
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'admin/MiscMaster/import');
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
				logadminactivity($log_title = "Misc master import sucessful", $log_message = "New Misc master file import sucessfull file=".$target_file);
			}
			else
			{
				logadminactivity($log_title = "Misc master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}
		}
		
		$this->load->view('admin/masters/misc_import', $data);
	}
	
	public function download(){
		
		$data = "Exam Code|Exam Period|Exam Month|Trg Value\n";
		$misc_list = $this->Master_model->getRecords("misc_master",array('misc_delete'=>0,'exam_delete'=>0));

		foreach($misc_list as $id => $misc_details) {
		  $data .= $misc_details['exam_code']."|".$misc_details['exam_period']."|".$misc_details['exam_month']."|".$misc_details['trg_value']."\n";
		}
		
		logadminactivity($log_title = "Misc master downloaded sucessfully", $log_message = "");
		
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="MISC_MASTER.txt.gz"');
		echo gzencode($data, 9); exit();
	}
	
	public function search(){
		
		$this->load->view('admin/'.$page,$data);
	}
	
}