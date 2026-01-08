<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CenterMaster extends CI_Controller {
	public $UserID;
	public $UserData;		
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
	}
	
	public function index()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		$data = $this->getUserInfo();
		$data["exam_list"] = $this->Master_model->getRecords("dra_exam_master","","exam_name,exam_code,description");
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Center</a></li>
							   </ol>';
		
		$this->load->view('iibfdra/admin/masters/center_list',$data);
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
		$this->db->where('center_delete',0);
		$this->db->join('dra_exam_master b','b.exam_code=a.exam_name','LEFT');
		$this->db->where('exam_delete',0);	
		$total_row = $this->UserModel->getRecordCount("dra_center_master a",$field,$value);
		
		$url = base_url()."iibfdra/admin/CenterMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id center_id,a.exam_period,a.exam_name,exammode,center_name,center_code,state_code,state_description,b.id exam_id,b.description';
		$this->db->where('center_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('dra_exam_master b','b.exam_code=a.exam_name','LEFT');	
		$res = $this->UserModel->getRecords("dra_center_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'iibfdra/admin/CenterMaster/edit/'.$row['center_id'].'">Edit | </a> <a href="'.base_url().'iibfdra/admin/CenterMaster/delete/'.$row['center_id'].'" onclick="'.$confirm.'">Delete</a>';
				$data['action'][] = $action;
				$data['checklist'][] = '<input type="checkbox" name="check_list[]" id="check_list_'.$row['center_id'].'" value="'.$row['center_id'].'" class="chk">';
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
			$this->form_validation->set_rules('exam_name','Exam Name','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('center_name','Center Name','trim|required');
			$this->form_validation->set_rules('center_code','Center Code','trim|required');
			$this->form_validation->set_rules('state_code','State Code','trim|required');
			$this->form_validation->set_rules('state_description','State Description','trim|required');
			$this->form_validation->set_rules('exammode','Exam mode','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$insert_data = array(	
								'exam_name'				=>$this->input->post('exam_name'),
								'exam_period'			=>$this->input->post('exam_period'),
								'center_name'			=>strtoupper($this->input->post('center_name')),
								'center_code'			=>$this->input->post('center_code'),
								'state_code'			=>strtoupper($this->input->post('state_code')),
								'state_description'		=>strtoupper($this->input->post('state_description')),
								'exammode'				=>$this->input->post('exammode'),
								'created_by'			=>$this->UserID,
								'created_on'			=>date('Y-m-d H:i:s'),
							);
				
				if($this->master_model->insertRecord('dra_center_master',$insert_data))
				{
					log_dra_admin($log_title = "Add DRA Center successful", $log_message = serialize($insert_data));
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'iibfdra/admin/CenterMaster');
				}
				else
				{
					log_dra_admin($log_title = "Add DRA Center Unsuccessful", $log_message = serialize($insert_data));
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'iibfdra/admin/CenterMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Center</a></li>
					<li class="active">Add</li>
				</ol>';
				
		$data['centerRes'] = array('exam_name'				=>'',
									'exam_period'			=>'',
									'center_name'			=>'',
									'center_code'			=>'',
									'state_code'			=>'',
									'state_description'		=>'',
									'exammode'				=>'',
								);
		
		$data["exam_list"] = $this->Master_model->getRecords("dra_exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("dra_misc_master",array('misc_delete'=>0));
		
		$this->load->view('iibfdra/admin/masters/center_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['centerRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$centerRes = $this->master_model->getRecords('dra_center_master',array('id'=>$id));
			if(count($centerRes))
			{
				$data['centerRes'] = $centerRes[0];
			}
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_name','Exam Name','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('center_name','Center Name','trim|required');
			$this->form_validation->set_rules('center_code','Center Code','trim|required');
			$this->form_validation->set_rules('state_code','State Code','trim|required');
			$this->form_validation->set_rules('state_description','State Description','trim|required');
			$this->form_validation->set_rules('exammode','Exam mode','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$update_data = array(	
								'exam_name'				=>$this->input->post('exam_name'),
								'exam_period'			=>$this->input->post('exam_period'),
								'center_name'			=>strtoupper($this->input->post('center_name')),
								'center_code'			=>$this->input->post('center_code'),
								'state_code'			=>strtoupper($this->input->post('state_code')),
								'state_description'		=>strtoupper($this->input->post('state_description')),
								'exammode'				=>$this->input->post('exammode'),
								'modified_by'			=>$this->UserID,
								'modified_on'			=>date('Y-m-d H:i:s')
							);
				
				if($this->master_model->updateRecord('dra_center_master',$update_data,array('id'=>$id)))
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $centerRes[0];
					log_dra_admin($log_title = "Edit DRA Center successful", $log_message = serialize($desc));
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'iibfdra/admin/CenterMaster/');
				}
				else
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $centerRes[0];
					log_dra_admin($log_title = "Edit DRA Center unsuccessful", $log_message = serialize($desc));
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'iibfdra/admin/CenterMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Center</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$data["exam_list"] = $this->Master_model->getRecords("dra_exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("dra_misc_master",array('misc_delete'=>0));
		
		$this->load->view('iibfdra/admin/masters/center_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('center_delete'=>1);
			if($this->master_model->updateRecord('dra_center_master', $update_data, array('id'=>$id)))
			{
				log_dra_admin($log_title = "Delete DRA Center Successful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'iibfdra/admin/CenterMaster');
			}
			else
			{
				log_dra_admin($log_title = "Delete DRA Center Unsuccessful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'iibfdra/admin/CenterMaster');
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
						$target_file = "uploads/admin/masters_imported_files/"."DRA_CENTER_MASTER_".$this->UserID."_".date('Y-m-d_hia').".txt";
						
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
													'exam_name'				=>$raw_array[0],
													'exam_period'			=>$raw_array[1],
													'center_code'			=>$raw_array[2],
													'center_name'			=>$raw_array[3],
													'state_code'			=>$raw_array[4],
													'state_description'		=>$raw_array[5],
													'exammode'				=>$raw_array[6],
													'created_by'   			=>$this->UserID,
													'created_on'    		=>date("Y-m-d H:i:s")
												);
				
									$this->master_model->insertRecord('dra_center_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==7)
									{	
										$firstlineflag = 1;  // Skip first header line
										//master_db_backup($basetable = "dra_center_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'iibfdra/admin/CenterMaster/import');
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
				log_dra_admin($log_title = "DRA center master import sucessfull", $log_message = "New Center master file import sucessfull file=".$target_file);
			}
			else
			{
				log_dra_admin($log_title = "DRA center master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}	
		}
		$data['menu_title'] = 'Manage Center Master';
		$data['title'] = 'Import Center Master';
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Center</a></li>
								<li class="active">Import</li>
							</ol>';
		$data['sample_file'] = 'DRA_CENTER_MASTER_ALL_112.TXT';
		$this->load->view('iibfdra/admin/masters/master_import', $data);
	}
	
	public function download(){
		$data = "Exam Name|Exam Period|Center Code|Center Name|State Code|State Description|Exam Mode\n";
		$this->db->where('center_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('dra_exam_master b','b.exam_code=a.exam_name','LEFT');	
		$center_list = $this->Master_model->getRecords("dra_center_master a");
		foreach($center_list as $id => $center_details) {
		  $data .= $center_details['exam_code']."|".$center_details['exam_period']."|".$center_details['center_code']."|".$center_details['center_name']."|".$center_details['state_code']."|".$center_details['state_description']."|".$center_details['exammode']."\n";
		}
		
		log_dra_admin($log_title = "DRA Center master downloaded", $log_message = "");
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="dra_center_master.txt.gz"');
		echo gzencode($data, 9); exit();
	}
}