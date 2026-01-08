<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExamMaster extends CI_Controller {
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
	
	// By VSU 
	public function index(){
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li>Manage Exam</li>
							</ol>';
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master");
		
		$this->load->view('admin/masters/exam_list',$data);
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
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		$this->db->join('exam_type','exam_type.id=exam_master.exam_type','LEFT');	
		$this->db->where('exam_delete',0);	
		$total_row = $this->UserModel->getRecordCount("exam_master",$field,$value);
		
		$url = base_url()."admin/ExamMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		
		$select = 'a.id,a.exam_name,a.exam_code,a.description,a.qualifying_exam1,a.qualifying_exam2,a.qualifying_exam3,a.qualifying_part1,a.qualifying_part2,a.qualifying_part3,b.type';
		$this->db->join('exam_type b','b.id=a.exam_type','LEFT');
		$this->db->where('exam_delete',0);
		$res = $this->UserModel->getRecords("exam_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/ExamMaster/edit/'.$row['id'].'">Edit |</a><a href="'.base_url().'admin/ExamMaster/delete/'.$row['id'].'" onclick="'.$confirm.'">Delete </a>';
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
	
	
	public function getUserInfo(){
		$data['AdminUser']=$this->UserModel->getUserInfo($this->UserID);
		return $data;
	}	
	
	public function add()
	{
		$data = array();
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_code','Exam Code','trim|required|is_unique[exam_master.exam_code]');
			$this->form_validation->set_rules('description','Description','trim|required|is_unique[exam_master.description]');
			$this->form_validation->set_rules('qualifying_exam1','Qualifying Exam1','trim');
			$this->form_validation->set_rules('qualifying_part1','Qualifying Part1','trim');
			$this->form_validation->set_rules('qualifying_exam2','Qualifying Exam2','trim');
			$this->form_validation->set_rules('qualifying_part2','Qualifying Part2','trim');
			$this->form_validation->set_rules('qualifying_exam3','Qualifying Exam3','trim');
			$this->form_validation->set_rules('qualifying_part3','Qualifying Part3','trim');
			$this->form_validation->set_rules('exam_type','Exam Type','trim|required');
			$this->form_validation->set_rules('examdescshr','Exam Description SHR','trim');
			$this->form_validation->set_rules('exam_instruction_file','Exam instruction file','file_allowed_type[pdf]|file_size_max[2000]');
			$this->form_validation->set_rules('member_instruction','Member Instruction','trim');
			$this->form_validation->set_rules('nonmember_instruction','NonMember Instruction','trim');
			
			if($this->form_validation->run()==TRUE)
			{
				$upload_flg = 0;
				// Upload exam instruction file
				$exam_instruction_file = '';
				if($_FILES['exam_instruction_file']['name']!='')
				{
					$path = "./uploads/exam_instruction";
					$filename = str_replace('%', '_', $_FILES['exam_instruction_file']['name']);
					
					$date = date('ym/d');
					$timestamp = strtotime($date);
					$new_filename = rand(1,99999).'_exam';
					
					$uploadData = upload_file('exam_instruction_file', $path, $new_filename);
					if($uploadData)
					{
						$exam_instruction_file = $uploadData['file_name'];
						$upload_flg = 1;
					}
				}
				else
				{
					$upload_flg = 2;
				}
				
				if($upload_flg == 1 || $upload_flg == 2)
				{
					$insert_data = array(	'exam_code'				=>filter($this->input->post('exam_code')),
										'description'			=>filter($this->input->post('description')),
										'qualifying_exam1'		=>filter($this->input->post('qualifying_exam1')),
										'qualifying_part1'		=>filter($this->input->post('qualifying_part1')),
										'qualifying_exam2'		=>filter($this->input->post('qualifying_exam2')),
										'qualifying_part2'		=>filter($this->input->post('qualifying_part2')),
										'qualifying_exam3'		=>filter($this->input->post('qualifying_exam3')),
										'qualifying_part3'		=>filter($this->input->post('qualifying_part3')),
										'exam_type'				=>filter($this->input->post('exam_type')),
										'examprty'				=>filter($this->input->post('examprty')),
										'examdescshr'			=>filter($this->input->post('examdescshr')),
										'elg_mem_o'				=>filter($this->input->post('elg_mem_o')),
										'elg_mem_a'				=>filter($this->input->post('elg_mem_a')),
										'elg_mem_f'				=>filter($this->input->post('elg_mem_f')),
										'elg_mem_nm'			=>filter($this->input->post('elg_mem_nm')),
										'elg_mem_db'			=>filter($this->input->post('elg_mem_db')),
										'exam_instruction_file'	=>$exam_instruction_file,
										'member_instruction'	=>htmlspecialchars($this->input->post('member_instruction')),
										'nonmember_instruction'	=>htmlspecialchars($this->input->post('nonmember_instruction')),
									);
				
					if($this->master_model->insertRecord('exam_master',$insert_data))
					{
						$logs_data = array(
										'date'=>date('Y-m-d H:i:s'),
										'title'=>'Add Exam Successful',
										'description'=>serialize($insert_data),
										'userid'=>$this->UserID,
										'ip'=>$this->input->ip_address()
									);
						$this->master_model->insertRecord('adminlogs',$logs_data);
						
						$this->session->set_flashdata('success','Record added successfully');
						redirect(base_url().'admin/ExamMaster');
					}
					else
					{
						$logs_data = array(
										'date'=>date('Y-m-d H:i:s'),
										'title'=>'Add Exam Unsuccessful',
										'description'=>serialize($insert_data),
										'userid'=>$this->UserID,
										'ip'=>$this->input->ip_address()
									);
						$this->master_model->insertRecord('adminlogs',$logs_data);
						
						$this->session->set_flashdata('error','Error occured while adding record');
						redirect(base_url().'admin/ExamMaster');
					}
				}
				else if($upload_flg == 0)
				{
					$logs_data = array(
										'date'=>date('Y-m-d H:i:s'),
										'title'=>'Add Exam Unsuccessful,Invalid file',
										'description'=>'Exam instruction file not uploaded',
										'userid'=>$this->UserID,
										'ip'=>$this->input->ip_address()
									);
						$this->master_model->insertRecord('adminlogs',$logs_data);
					
					//$this->session->set_flashdata('error','Error occured while uploading a file');
					redirect(base_url().'admin/ExamMaster');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Exam Master</a></li>
					<li class="active">Add</li>
				</ol>';
				
		
		$data["exam_type_list"] = $this->Master_model->getRecords("exam_type");
		
		$this->load->view('admin/masters/exam_add',$data);
	}
	
	public function edit()
	{
		$data = array();
		$data['examRes'] = array();
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$examRes = $this->master_model->getRecords('exam_master',array('id'=>$id));
			if(count($examRes))
			{
				$data['examRes'] = $examRes[0];
			}
			else
			{
				redirect(base_url().'admin/ExamMaster');
			}
		}
		else
		{
			redirect(base_url().'admin/ExamMaster');
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_code','Exam Code','trim|required|is_unique[exam_master.exam_code.id.'.$id.']');
			$this->form_validation->set_rules('description','Description','trim|required');
			$this->form_validation->set_rules('qualifying_exam1','Qualifying Exam1','trim');
			$this->form_validation->set_rules('qualifying_part1','Qualifying Part1','trim');
			$this->form_validation->set_rules('qualifying_exam2','Qualifying Exam2','trim');
			$this->form_validation->set_rules('qualifying_part2','Qualifying Part2','trim');
			$this->form_validation->set_rules('qualifying_exam3','Qualifying Exam3','trim');
			$this->form_validation->set_rules('qualifying_part3','Qualifying Part3','trim');
			$this->form_validation->set_rules('exam_type','Exam Type','trim|required');
			$this->form_validation->set_rules('examdescshr','Exam Description SHR','trim');
			$this->form_validation->set_rules('exam_instruction_file','Exam instruction file','file_allowed_type[pdf]|file_size_max[2000]');
			$this->form_validation->set_rules('member_instruction','Member Instruction','trim');
			$this->form_validation->set_rules('nonmember_instruction','NonMember Instruction','trim');
			
			if($this->form_validation->run()==TRUE)
			{
				// Upload exam instruction file
				$exam_instruction_file = '';
				if($_FILES['exam_instruction_file']['name']!='')
				{
					$path = "./uploads/exam_instruction";
					$filename = str_replace('%', '_', $_FILES['exam_instruction_file']['name']);
					
					$date = date('ym/d');
					$timestamp = strtotime($date);
					$new_filename = rand(1,99999).'_exam';
					
					$uploadData = upload_file('exam_instruction_file', $path, $new_filename);
					if($uploadData)
					$exam_instruction_file = $uploadData['file_name'];
				}
				else
				{
					$exam_instruction_file = $this->input->post('exam_instruction_file_hidden');
				}
				
				$update_data = array(	'exam_code'				=>filter($this->input->post('exam_code')),
										'description'			=>filter($this->input->post('description')),
										'qualifying_exam1'		=>filter($this->input->post('qualifying_exam1')),
										'qualifying_part1'		=>filter($this->input->post('qualifying_part1')),
										'qualifying_exam2'		=>filter($this->input->post('qualifying_exam2')),
										'qualifying_part2'		=>filter($this->input->post('qualifying_part2')),
										'qualifying_exam3'		=>filter($this->input->post('qualifying_exam3')),
										'qualifying_part3'		=>filter($this->input->post('qualifying_part3')),
										'exam_type'				=>filter($this->input->post('exam_type')),
										'examprty'				=>filter($this->input->post('examprty')),
										'examdescshr'			=>filter($this->input->post('examdescshr')),
										'elg_mem_o'				=>filter($this->input->post('elg_mem_o')),
										'elg_mem_a'				=>filter($this->input->post('elg_mem_a')),
										'elg_mem_f'				=>filter($this->input->post('elg_mem_f')),
										'elg_mem_nm'			=>filter($this->input->post('elg_mem_nm')),
										'elg_mem_db'			=>filter($this->input->post('elg_mem_db')),
										'exam_instruction_file'	=>$exam_instruction_file,
										'member_instruction'	=>htmlspecialchars($this->input->post('member_instruction')),
										'nonmember_instruction'	=>htmlspecialchars($this->input->post('nonmember_instruction')),
									);
				
				if($this->master_model->updateRecord('exam_master',$update_data,  array('id'=>$id)))
				{
					if($_FILES['exam_instruction_file']['name']!='')
					{
						if(isset($_POST['exam_instruction_file_hidden']) && $_POST['exam_instruction_file_hidden']!='')
						{
							$old_file = $this->input->post('exam_instruction_file_hidden');
							@unlink('uploads/exam_instruction/'.$old_file);
						}
					}
					
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $examRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Exam Successful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'admin/ExamMaster/edit/'.$id);
				}
				else
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $examRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Exam Unsuccessful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'admin/ExamMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Exam Master</a></li>
									<li class="active">Edit</li>
							   </ol>';
				
		
		$data["exam_type_list"] = $this->Master_model->getRecords("exam_type");
		
		$this->load->view('admin/masters/exam_edit',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('exam_delete'=>1);
			
			if($this->master_model->updateRecord('exam_master', $update_data, array('id'=>$id)))
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Exam Successful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/ExamMaster');
			}
			else
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Exam Unsuccessful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/ExamMaster');
			}
		}
	}
	
	public function import(){
		
		$this->load->helper(array('form', 'url'));
		$data = $this->getUserInfo();
		$data['error'] = "";
		$data['success'] = "";
		$target_file = "";

		if ($this->input->post('btnSubmit'))
		{			
			if (isset($_FILES["exammasterfile"]))
			{
				if ($_FILES["exammasterfile"]["type"] == "text/plain")
				{
					if ($_FILES["exammasterfile"]["size"] <= (1024*1024*2))  // Max 2MB
					{
						$target_file = "uploads/admin/masters_imported_files/"."EXAM_MASTER_".$this->UserID."_".date('Y-m-d_his').".txt";
						
						// move uploaded file in file logs dir
						move_uploaded_file($_FILES["exammasterfile"]["tmp_name"], $target_file);
						
						//master_db_backup($basetable = "exam_master"); // keep old and new master in DB
						
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
										"exam_name"        => $raw_array[0],
										"exam_code"        => $raw_array[1],
										"description"      => $raw_array[2],
										"exam_type"      => $raw_array[3],
										"exam_mode"      => $raw_array[4],
										"exam_medium"      => $raw_array[5],
										"exam_frequency"      => $raw_array[6],
										"qualifying_exam_code"      => $raw_array[7],
										"open_to_member"      => $raw_array[8],
										"qualifying_exam1" => $raw_array[9],
										"qualifying_part1" => $raw_array[10],
										"qualifying_exam2" => $raw_array[11],
										"qualifying_part2" => $raw_array[12],
										"qualifying_exam3" => $raw_array[13],
										"qualifying_part3" => $raw_array[14],
										"examprty"			=> $raw_array[15],
										"examdescshr"			=> $raw_array[16],
										"elg_mem_o"			=> $raw_array[17],
										"elg_mem_a"			=> $raw_array[18],
										"elg_mem_f"			=> $raw_array[19],
										"elg_mem_nm"			=> $raw_array[20],
										"elg_mem_db"			=> $raw_array[21],
										"created_by"       => $this->UserID,
										"created_on"       => date("Y-m-d H:i:s")
									);
									$this->master_model->insertRecord('exam_master',$insert_data);
								}
								else
								{
									/*$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==22)
									{	$firstlineflag = 1;  }	// Skip first header line
									else
									{
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'admin/ExamMaster/import');
									}*/
									$firstlineflag = 1; 	// Skip first header line
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
				logadminactivity($log_title = "Exam master import sucessfull", $log_message = "New exam master file import sucessfull file=".$target_file);
			}
			else
			{
				logadminactivity($log_title = "Exam master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}	
		}
		
		$this->load->view('admin/masters/exam_import', $data);
	}

	public function download(){
		
		$data = "Exam Name|Exam Code|Description|Exam Type|Exam Mode|Exam Medium|Exam Frequency|Qualifying Exam Code|Open to Member|Qualifying Exam1|Qualifying Part1|Qualifying Exam2|Qualifying Part1|Qualifying Exam3|Qualifying Part3|examprty|examdescshr|elg_mem_o|elg_mem_a|elg_mem_f|elg_mem_nm|elg_mem_db
\n";
		$this->db->where('exam_delete',0);
		$exam_list = $this->Master_model->getRecords("exam_master");

		foreach($exam_list as $id => $exam_details) {
		  $data .= $exam_details['exam_name']."|";
		  $data .= $exam_details['exam_code']."|";
		  $data .= $exam_details['description']."|";
		  $data .= $exam_details['exam_type']."|";
		  $data .= $exam_details['exam_mode']."|";
		  $data .= $exam_details['exam_medium']."|";
		  $data .= $exam_details['exam_frequency']."|";
		  $data .= $exam_details['qualifying_exam_code']."|";
		  $data .= $exam_details['open_to_member']."|";
		  $data .= $exam_details['qualifying_exam1']."|";
		  $data .= $exam_details['qualifying_part1']."|";
		  $data .= $exam_details['qualifying_exam2']."|";
		  $data .= $exam_details['qualifying_part2']."|";
		  $data .= $exam_details['qualifying_exam3']."|";
		  $data .= $exam_details['qualifying_part3']."|";
		  $data .= $exam_details['examprty']."|";
		  $data .= $exam_details['examdescshr']."|";
		  $data .= $exam_details['elg_mem_o']."|";
		  $data .= $exam_details['elg_mem_a']."|";
		  $data .= $exam_details['elg_mem_f']."|";
		  $data .= $exam_details['elg_mem_nm']."|";
		  $data .= $exam_details['elg_mem_db']."\n";
		}

		logadminactivity($log_title = "Exam master downloaded", $log_message = "");
		
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="EXAM_MASTER.txt.gz"');
		echo gzencode($data, 9); exit();
	}
}