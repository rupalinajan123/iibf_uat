<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EligibleMaster extends CI_Controller {
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

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Eligible</a></li>
							   </ol>';
		
		$this->load->view('iibfdra/admin/masters/eligible_list',$data);
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
		
		$this->db->where('eligible_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('dra_exam_master b','b.exam_code=a.exam_code','LEFT');
		$total_row = $this->UserModel->getRecordCount("dra_eligible_master a",$field,$value);
		
		$url = base_url()."iibfdra/admin/EligibleMaster/getList/";
		
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id eligible_id,b.description,a.member_no,a.exam_status,a.remark';
		$this->db->where('eligible_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('dra_exam_master b','b.exam_code=a.exam_code','LEFT');
		$res = $this->UserModel->getRecords("dra_eligible_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		//echo $this->db->last_query();
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'iibfdra/admin/EligibleMaster/edit/'.$row['eligible_id'].'">Edit | </a> <a href="'.base_url().'iibfdra/admin/EligibleMaster/delete/'.$row['eligible_id'].'" onclick="'.$confirm.'">Delete</a>';
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
			$this->form_validation->set_rules('exam_code','Exam Name','trim|required');
			$this->form_validation->set_rules('eligible_period','Eligible Period','trim|required');
			$this->form_validation->set_rules('institute_code','Training Institute','required');
			$this->form_validation->set_rules('training_from','Training From','trim|required');
			$this->form_validation->set_rules('training_to','Training To','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$insert_data = array(	
								 'exam_code'       => $this->input->post('exam_code'),
								 'eligible_period' => $this->input->post('eligible_period'),
								 'part_no'	       => $this->input->post('part_no'),
								 'member_no'       => $this->input->post('member_no'),
								 'member_type'	   => $this->input->post('member_type'),
								 'exam_status'	   => $this->input->post('exam_status'),
								 'app_category'    => $this->input->post('app_category'),
								 'fees'	           => $this->input->post('fees'),
								 'institute_code'  => $this->input->post('institute_code'),
								 'training_from'   => $this->input->post('training_from'),
								 'training_to'	   => $this->input->post('training_to'),
								 'remark'	       => $this->input->post('remark'),

								 'created_by'     => $this->UserID,
								 'created_on'     => date("Y-m-d H:i:s")
							   );
				
				if($this->master_model->insertRecord('dra_eligible_master',$insert_data))
				{
					$this->session->set_flashdata('success','Record added successfully');
					log_dra_admin($log_title = "DRA Eligible master - Record added successfully", $log_message = serialize($insert_data));
					redirect(base_url().'iibfdra/admin/EligibleMaster');
				}
				else
				{
					$this->session->set_flashdata('error','Error occured while adding record');
					log_dra_admin($log_title = "DRA Eligible master - Record insertion failed", $log_message =serialize($insert_data));
					redirect(base_url().'iibfdra/admin/EligibleMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Eligible</a></li>
					<li class="active">Add</li>
				</ol>';
		$data['eligibleRes'] = array(
									'exam_code'       => '',
									'eligible_period' => '',
									'part_no'	      => '',
									'member_no'       => '',
									'member_type' 	  => '',
									'exam_status'	  => '',
									'app_category'    => '',
									'fees'	          => '',
									'institute_code'  => '',
									'training_from'	  => '',
									'training_to'	  => '',
									'remark'	      => ''
							  );
		$data["exam_list"] = $this->Master_model->getRecords("dra_exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		$data["exam_institutes"] = $this->Master_model->getRecords("dra_accerdited_master",array('accerdited_delete'=>0),"id, institute_code, institute_name");
		$this->load->view('iibfdra/admin/masters/eligible_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['eligibleRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$eligibleRes = $this->master_model->getRecords('dra_eligible_master',array('id'=>$id));
			if(count($eligibleRes))
			{
				$data['eligibleRes'] = $eligibleRes[0];
			}
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_code','Exam Name','trim|required');
			$this->form_validation->set_rules('eligible_period','Eligible Period','trim|required');
			$this->form_validation->set_rules('institute_code','Training Institute','required');
			$this->form_validation->set_rules('training_from','Training From','trim|required');
			$this->form_validation->set_rules('training_to','Training To','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$update_data = array(	
								 'exam_code'       => $this->input->post('exam_code'),
								 'eligible_period' => $this->input->post('eligible_period'),
								 'part_no'	       => $this->input->post('part_no'),
								 'member_no'       => $this->input->post('member_no'),
								 'member_type'	   => $this->input->post('member_type'),
								 'exam_status'	   => $this->input->post('exam_status'),
								 'app_category'    => $this->input->post('app_category'),
								 'fees'	           => $this->input->post('fees'),
								 'institute_code'  => $this->input->post('institute_code'),
								 'training_from'   => $this->input->post('training_from'),
								 'training_to'	   => $this->input->post('training_to'),
								 'remark'	       => $this->input->post('remark'),
								 'modified_by'     => $this->UserID
							   );
				if($this->master_model->updateRecord('dra_eligible_master',$update_data,array('id'=>$id)))
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $eligibleRes[0];
					$this->session->set_flashdata('success','Record updated successfully');
					log_dra_admin($log_title = "DRA Eligible master - Record edited successfully", $log_message = serialize($desc));
					redirect(base_url().'iibfdra/admin/EligibleMaster/');
				}
				else
				{
					$this->session->set_flashdata('error','Error occured while updating record');
					log_dra_admin($log_title = "DRA Eligible master - Record edit failed", $log_message = serialize($desc) );
					redirect(base_url().'iibfdra/admin/EligibleMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Eligible</a></li>
					<li class="active">Edit</li>
				</ol>';
				
		$data["exam_list"] = $this->Master_model->getRecords("dra_exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		$data["exam_institutes"] = $this->Master_model->getRecords("dra_accerdited_master",array('accerdited_delete'=>0),"id, institute_code, institute_name");
		$this->load->view('iibfdra/admin/masters/eligible_add',$data);
	}
	
	public function delete(){
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('eligible_delete'=>1, 'modified_by' => $this->UserID);
			
			if($this->master_model->updateRecord('dra_eligible_master', $update_data, array('id'=>$id)))
			{
				log_dra_admin($log_title = "Delete DRA Eligible Record Successful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'iibfdra/admin/EligibleMaster');
			}
			else
			{
				log_dra_admin($log_title = "Delete DRA Eligible Record Unsuccessful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'iibfdra/admin/EligibleMaster');
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
			if (isset($_FILES["eligiblemasterfile"]))
			{
				if ($_FILES["eligiblemasterfile"]["type"] == "text/plain")
				{
					//if ($_FILES["eligiblemasterfile"]["size"] <= (1024*1024*2)) { // Max 2MB
					if ($_FILES["eligiblemasterfile"]["size"]) {
						$target_file = "uploads/admin/masters_imported_files/"."DRA_ELG_MASTER_".$this->UserID."_".date('Y-m-d_hia').".txt";
						
						// move uploaded file in file logs dir
						move_uploaded_file($_FILES["eligiblemasterfile"]["tmp_name"], $target_file);
						
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
										"exam_code"       => $raw_array[0],
										"eligible_period" => $raw_array[1],
										"part_no"         => $raw_array[2],
										"member_no"       => $raw_array[3],
										"member_type"     => $raw_array[4],
										"exam_status"	  => $raw_array[5],
										"app_category"    => $raw_array[6],
										"fees"            => $raw_array[7],
										"subject"         => $raw_array[8],
										"med_cd"          => $raw_array[9],
										"remark"          => $raw_array[10],
										"institute_code"  => $raw_array[11],
										"training_from"   => date_format(date_create($raw_array[12]),"Y-m-d"),
										"training_to"     => date_format(date_create($raw_array[13]),"Y-m-d"),
										"created_by"      => $this->UserID,
										"created_on"      => date("Y-m-d H:i:s")
									);
									$this->master_model->insertRecord('dra_eligible_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									//echo count($raw_array);exit;
									//if(count($raw_array) == 14 || count($raw_array) == 15)
									if(count($raw_array) == 14 || count($raw_array) == 15 || count($raw_array) == 16)
									{	
										$firstlineflag = 1;  // Skip first header line
										//master_db_backup($basetable = "dra_eligible_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'iibfdra/admin/EligibleMaster/import');
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
				log_dra_admin($log_title = "DRA Eligible master import sucessfull", $log_message = "New Eligible master file import sucessfull file=".$target_file);
			}
			else
			{
				log_dra_admin($log_title = "DRA Eligible master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}	
		}

		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Eligible</a></li>
								<li class="active">Import</li>
							</ol>';
		
		$this->load->view('iibfdra/admin/masters/eligible_import', $data);
	}
	
	/*public function download(){
		
		$data = "Series No|dcode|dname|level\n";
		
		$this->db->where('eligible_delete',0);
		$eligible_list = $this->Master_model->getRecords("dra_eligible_master a");

		foreach($eligible_list as $id => $eligible_details) {
		  $data .= $eligible_details['id']."|".$eligible_details['dcode']."|".$eligible_details['dname']."|".$eligible_details['level']."\n";
		}
		
		log_dra_admin($log_title = "DRA Eligible master downloaded", $log_message = "");

		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="DRA_ELIGIBLE_MASTER.txt.gz"');
		echo gzencode($data, 9); exit();
	}*/
}