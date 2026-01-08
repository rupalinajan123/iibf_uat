<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FeeMaster extends CI_Controller {
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
									<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Exam Fee</a></li>
							   </ol>';
		
		$this->load->view('admin/masters/fee_list',$data);
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
		$this->db->where('fee_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		//$this->db->join('exam_period_master c','a.exam_period=c.exam_period_value','LEFT');			
		$total_row = $this->UserModel->getRecordCount("fee_master a",$field,$value);
		
		$url = base_url()."admin/FeeMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id fee_id,a.exam_period,a.exam_code,part_no,syllabus_code,member_category,group_code,fee_amount,b.id exam_id,b.description';
		$this->db->where('fee_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		//$this->db->join('exam_period_master c','a.exam_period=c.exam_period_value','LEFT');				
		$res = $this->UserModel->getRecords("fee_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'admin/FeeMaster/edit/'.$row['fee_id'].'">Edit | </a> <a href="'.base_url().'admin/FeeMaster/delete/'.$row['fee_id'].'" onclick="'.$confirm.'">Delete</a>';
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
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('part_no','Part No.','trim|required|numeric');
			$this->form_validation->set_rules('syllabus_code','Syllabus Code','trim|required');
			$this->form_validation->set_rules('member_category','Member Category','trim|required');
			$this->form_validation->set_rules('group_code','Group Code','trim|required');
			$this->form_validation->set_rules('fee_amount','Fee Amount','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$insert_data = array(	
							'exam_code'				=>$this->input->post('exam_code'),
							'exam_period'			=>$this->input->post('exam_period'),
							'part_no'				=>$this->input->post('part_no'),
							'syllabus_code'			=>$this->input->post('syllabus_code'),
							'member_category'		=>$this->input->post('member_category'),
							'group_code'			=>$this->input->post('group_code'),
							'fee_amount'			=>$this->input->post('fee_amount'),
							'created_by'			=>$this->UserID,
							'created_on'			=>date('Y-m-d H:i:s'),
						);
				
				if($this->master_model->insertRecord('fee_master',$insert_data))
				{
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Add Exam Fee successful',
									'description'=>serialize($insert_data),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'admin/FeeMaster');
				}
				else
				{
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Add Exam Fee Unsuccessful',
									'description'=>serialize($insert_data),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'admin/FeeMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Exam Fee</a></li>
					<li class="active">Add</li>
				</ol>';
				
		$data['feeRes'] = array(	'exam_code'			=>'',
									'exam_period'		=>'',
									'part_no'			=>'',
									'syllabus_code'		=>'',
									'member_category'	=>'',
									'group_code'		=>'',
									'fee_amount'		=>''
								);
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		//$data["exam_period"] = $this->Master_model->getRecords("exam_period_master");
		$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("misc_master");
		
		$this->load->view('admin/masters/fee_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['feeRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$feeRes = $this->master_model->getRecords('fee_master',array('id'=>$id));
			if(count($feeRes))
			{
				$data['feeRes'] = $feeRes[0];
			}
			else
			{
				redirect(base_url().'admin/FeeMaster');
			}
		}
		else
		{
			redirect(base_url().'admin/FeeMaster');
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_code','Exam Code','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('part_no','Part No.','trim|required|numeric');
			$this->form_validation->set_rules('syllabus_code','Syllabus Code','trim|required');
			$this->form_validation->set_rules('member_category','Member Category','trim|required');
			$this->form_validation->set_rules('group_code','Group Code','trim|required');
			$this->form_validation->set_rules('fee_amount','Fee Amount','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$update_data = array(	
							'exam_code'				=>$this->input->post('exam_code'),
							'exam_period'			=>$this->input->post('exam_period'),
							'part_no'				=>$this->input->post('part_no'),
							'syllabus_code'			=>$this->input->post('syllabus_code'),
							'member_category'		=>$this->input->post('member_category'),
							'group_code'			=>$this->input->post('group_code'),
							'fee_amount'			=>$this->input->post('fee_amount'),
							'modified_by'			=>$this->UserID,
							'modified_on'			=>date('Y-m-d H:i:s'),
						);
				
				if($this->master_model->updateRecord('fee_master',$update_data,array('id'=>$id)))
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $feeRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Exam Fee successful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'admin/FeeMaster');
				}
				else
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $feeRes[0];
					
					$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Edit Exam Fee Unsuccessful',
									'description'=>serialize($desc),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
					$this->master_model->insertRecord('adminlogs',$logs_data);
					
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'admin/FeeMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Exam Fee</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		//$data["exam_period"] = $this->Master_model->getRecords("exam_period_master");
		$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("misc_master");
		
		$this->load->view('admin/masters/fee_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('fee_delete'=>1);
			
			if($this->master_model->updateRecord('fee_master', $update_data, array('id'=>$id)))
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Exam Fee successful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'admin/FeeMaster');
			}
			else
			{
				$logs_data = array(
									'date'=>date('Y-m-d H:i:s'),
									'title'=>'Delete Exam Fee Unsuccessful',
									'description'=>serialize(array('id'=>$id)),
									'userid'=>$this->UserID,
									'ip'=>$this->input->ip_address()
								);
				$this->master_model->insertRecord('adminlogs',$logs_data);
				
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'admin/FeeMaster');
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
			if (isset($_FILES["masterfile"]))
			{
				if ($_FILES["masterfile"]["type"] == "text/plain") 
				{
					if ($_FILES["masterfile"]["size"] <= (1024*1024*2))  // Max 2MB
					{
						$target_file = "uploads/admin/masters_imported_files/"."FEE_MASTER_".$this->UserID."_".date('Y-m-d_his').".txt"; 
						
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
													'exam_code'			=>$raw_array[0],
													'exam_period'		=>$raw_array[1],
													'part_no'			=>$raw_array[2],
													'syllabus_code'		=>$raw_array[3],
													'member_category'	=>$raw_array[4],
													'group_code'		=>$raw_array[5],
													'fee_amount'		=>$raw_array[6],
													'created_by'		=>$this->UserID,
													'created_on'		=>date('Y-m-d H:i:s'),
												);
				
									$this->master_model->insertRecord('fee_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==7)
									{	
										$firstlineflag = 1;  // Skip first header line
										master_db_backup($basetable = "fee_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'admin/FeeMaster/import');
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
				logadminactivity($log_title = "Fee master import sucessfull", $log_message = "New Fee master file import sucessfull file=".$target_file);
			}
			else
			{
				logadminactivity($log_title = "Fee master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}
				
		}
		$data['menu_title'] = 'Manage Exam Fee Master'; 
		$data['title'] = 'Import Exam Fee Master';
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Manage Exam Fee</a></li>
								<li class="active">Import</li>
							</ol>';
		$data['sample_file'] = 'FEE_MASTER_ALL_112.txt';
		$this->load->view('admin/masters/master_import', $data);
	}
	
	public function download(){
		
		$data = "Exam Name|Exam Period|Part No|Syllabus Code|Member Category|Group Code|Fee Amount\n";
		
		$this->db->where('fee_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		//$this->db->join('exam_period_master c','a.exam_period=c.exam_period_value','LEFT');		
		$fee_list = $this->Master_model->getRecords("fee_master a");

		foreach($fee_list as $id => $fee_details) {
		  $data .= $fee_details['exam_code']."|".$fee_details['exam_period']."|".$fee_details['part_no']."|".$fee_details['syllabus_code']."|".$fee_details['member_category']."|".$fee_details['group_code']."|".$fee_details['fee_amount']."\n";
		}
		
		logadminactivity($log_title = "Exam Fee master downloaded", $log_message = "");
		
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="fee_master.txt.gz"');
		echo gzencode($data, 9); exit();
	}
	
	public function search(){
		
		$this->load->view('admin/'.$page,$data);
	}
	
}