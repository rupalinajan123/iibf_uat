<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SubjectMaster extends CI_Controller {
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
									<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Subject</a></li>
							   </ol>';
		
		$this->load->view('admin/masters/subject_list',$data);
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
		$this->db->where('subject_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		//$this->db->join('exam_period_master c','a.exam_period=c.exam_period_value','LEFT');			
		$total_row = $this->UserModel->getRecordCount("subject_master a",$field,$value);
		
		$url = base_url()."admin/SubjectMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id subject_id,a.exam_period,a.exam_code,part_no,syllabus_code,subject_code,subject_description,group_code,exam_date,exam_time,b.id exam_id,b.description';
		$this->db->where('subject_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		//$this->db->join('exam_period_master c','a.exam_period=c.exam_period_value','LEFT');				
		$res = $this->UserModel->getRecords("subject_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();
		if($res)
		{
			$result = $res->result_array();
			$i = 0;
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/SubjectMaster/edit/'.$row['subject_id'].'">Edit | </a> <a href="'.base_url().'admin/SubjectMaster/delete/'.$row['subject_id'].'" onclick="'.$confirm.'">Delete</a>';
				$data['action'][] = $action;
				
				if($row['exam_date'] != '' && $row['exam_date']!= '0000-00-00')
					$result[$i]['exam_date'] = date('d-m-Y',strtotime($row['exam_date']));
				else
					$result[$i]['exam_date'] = "";
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
	
	
	public function add(){
		$data = array();
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_code','Exam Code','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('part_no','Part No.','trim|required');
			$this->form_validation->set_rules('syllabus_code','Syllabus Code','trim|required');
			$this->form_validation->set_rules('subject_code','Subject Code','trim|required');
			$this->form_validation->set_rules('subject_description','Description','trim|required');
			$this->form_validation->set_rules('group_code','Group Code','trim|required');
			$this->form_validation->set_rules('exam_date','Exam Date','trim|required');
			$this->form_validation->set_rules('exam_time','Exam Time','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$new_date = str_replace('/','-',$_POST['exam_date']);
				$exam_date = date('Y-m-d',strtotime($new_date));
				$insert_data = array(	
								'exam_code'				=>$this->input->post('exam_code'),
								'exam_period'			=>$this->input->post('exam_period'),
								'part_no'				=>$this->input->post('part_no'),
								'syllabus_code'			=>$this->input->post('syllabus_code'),
								'subject_code'			=>$this->input->post('subject_code'),
								'subject_description'	=>$this->input->post('subject_description'),
								'group_code'			=>$this->input->post('group_code'),
								'exam_date'				=>$exam_date,
								'exam_time'				=>$this->input->post('exam_time'),
							);
				
				if($this->master_model->insertRecord('subject_master',$insert_data))
				{
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Add Subject Successful',
									'description'=>serialize($insert_data),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'admin/SubjectMaster');
				}
				else
				{
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Add Subject Unsuccessful',
									'description'=>serialize($insert_data),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'admin/SubjectMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Subject</a></li>
					<li class="active">Add</li>
				</ol>';
				
		$data['subjectRes'] = array('exam_code'				=>'',
									'exam_period'			=>'',
									'part_no'				=>'',
									'syllabus_code'			=>'',
									'subject_code'			=>'',
									'subject_description'	=>'',
									'group_code'			=>'',
									'exam_date'				=>'',
									'exam_time'				=>'',
								);
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("misc_master");
		
		$this->load->view('admin/masters/subject_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['subjectRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$subjectRes = $this->master_model->getRecords('subject_master',array('id'=>$id));
			if(count($subjectRes))
			{
				$data['subjectRes'] = $subjectRes[0];
			}
			else
			{
				redirect(base_url().'admin/SubjectMaster');
			}
		}
		else
		{
			redirect(base_url().'admin/SubjectMaster');
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_code','Exam Code','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('part_no','Part No.','trim|required');
			$this->form_validation->set_rules('syllabus_code','Syllabus Code','trim');
			$this->form_validation->set_rules('subject_code','Subject Code','trim');
			$this->form_validation->set_rules('subject_description','Description','trim');
			$this->form_validation->set_rules('group_code','Group Code','trim');
			$this->form_validation->set_rules('exam_date','Exam Date','trim');
			$this->form_validation->set_rules('exam_time','Exam Time','trim');
			if($this->form_validation->run()==TRUE)
			{
				$new_date = str_replace('/','-',$_POST['exam_date']);
				$exam_date = date('Y-m-d',strtotime($new_date));
				$update_data = array(	
								'exam_code'				=>$this->input->post('exam_code'),
								'exam_period'			=>$this->input->post('exam_period'),
								'part_no'				=>$this->input->post('part_no'),
								'syllabus_code'			=>$this->input->post('syllabus_code'),
								'subject_code'			=>$this->input->post('subject_code'),
								'subject_description'	=>$this->input->post('subject_description'),
								'group_code'			=>$this->input->post('group_code'),
								'exam_date'				=>$exam_date,
								'exam_time'				=>$this->input->post('exam_time'),
							);
				
				if($this->master_model->updateRecord('subject_master',$update_data,array('id'=>$id)))
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $subjectRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Subject Successful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'admin/SubjectMaster');
				}
				else
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $subjectRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Subject Unsuccessful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'admin/SubjectMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Subject</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		//$data["exam_period"] = $this->Master_model->getRecords("exam_period_master");
		
		$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("misc_master");
		//print_r($data["exam_period"]);
		
		$this->load->view('admin/masters/subject_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('subject_delete'=>1);
			
			if($this->master_model->updateRecord('subject_master', $update_data, array('id'=>$id)))
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Subject Successful',
									'description'=>serialize( array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/SubjectMaster');
			}
			else
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Subject Unsuccessful',
									'description'=>serialize( array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/SubjectMaster');
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
			if (isset($_FILES["subjectmasterfile"]))
			{
				if ($_FILES["subjectmasterfile"]["type"] == "text/plain")
				{
					if ($_FILES["subjectmasterfile"]["size"] <= (1024*1024*2))  // Max 2MB
					{
						$target_file = "uploads/admin/masters_imported_files/"."SUBJECT_MASTER_".$this->UserID."_".date('Y-m-d_his').".txt";
						
						// move uploaded file in file logs dir
						move_uploaded_file($_FILES["subjectmasterfile"]["tmp_name"], $target_file);
												
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
													'exam_code'				=>$raw_array[0],
													'exam_period'			=>$raw_array[1],
													'part_no'				=>$raw_array[2],
													'syllabus_code'			=>$raw_array[3],
													'subject_code'			=>$raw_array[4],
													'subject_description'	=>$raw_array[5],
													'group_code'			=>$raw_array[6],
													'exam_date'				=>date('Y-m-d',strtotime($raw_array[7])),
													'exam_time'				=>$raw_array[8],
												);
				
									$this->master_model->insertRecord('subject_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==9)
									{	
										$firstlineflag = 1;  // Skip first header line
										master_db_backup($basetable = "subject_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'admin/SubjectMaster/import');
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
				logadminactivity($log_title = "Subject master import sucessfull", $log_message = "New Subject master file import sucessfull file=".$target_file);
			}
			else
			{
				logadminactivity($log_title = "Subject master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}	
		}
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Subject</a></li>
								<li class="active">Import</li>
							</ol>';
		$this->load->view('admin/masters/subject_import', $data);
	}
	
	public function download(){
		
		$data = "Exam Name|Exam Period|Part No|Syllabus Code|Subject Code|Subject Description|Group Code|Exam Date|Exam Time\n";
		
		$this->db->where('subject_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		//$this->db->join('exam_period_master c','a.exam_period=c.exam_period_value','LEFT');		
		$subject_list = $this->Master_model->getRecords("subject_master a");

		foreach($subject_list as $id => $subject_details) {
		  $data .= $subject_details['exam_code']."|".$subject_details['exam_period']."|".$subject_details['part_no']."|".$subject_details['syllabus_code']."|".$subject_details['subject_code']."|".$subject_details['subject_description']."|".$subject_details['group_code']."|".$subject_details['exam_date']."|".$subject_details['exam_time']."\n";
		}
		
		logadminactivity($log_title = "Subject master downloaded successfully", $log_message = "");
		
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="subject_master.txt.gz"');
		echo gzencode($data, 9); exit();
	}
	
	public function search(){
		
		$this->load->view('admin/'.$page,$data);
	}
	
}