<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstitutionMaster extends CI_Controller {
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
									<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Institution</a></li>
							   </ol>';
		
		$this->load->view('iibfdra/admin/masters/institution_list',$data);
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
		$this->db->where('accerdited_delete',0);		
		$total_row = $this->UserModel->getRecordCount("dra_accerdited_master a",$field,$value);
		$url = base_url()."iibfdra/admin/InstitutionMaster/getList/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$this->db->where('accerdited_delete',0);
		$res = $this->UserModel->getRecords("dra_accerdited_master a", '', $field, $value, $sortkey, $sortval, $per_page, $start);
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'iibfdra/admin/InstitutionMaster/edit/'.$row['id'].'">Edit | </a> <a href="'.base_url().'iibfdra/admin/InstitutionMaster/delete/'.$row['id'].'" onclick="'.$confirm.'">Delete</a>';
				$data['action'][] = $action;
				$data['checklist'][] = '<input type="checkbox" name="check_list[]" id="check_list_'.$row['id'].'" value="'.$row['id'].'" class="chk">';
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
			$this->form_validation->set_rules('institute_code','Institute Code','trim|required');
			$this->form_validation->set_rules('institute_name','Institute Name','trim|required');
			$this->form_validation->set_rules('category_code','Category Code','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				
				$insert_data = array(	
								'institute_code'		=>$this->input->post('institute_code'),
								'institute_name'		=>ucwords($this->input->post('institute_name')),
								'category_code'			=>$this->input->post('category_code'),
								'created_by'			=>$this->UserID,
								'created_on'			=>date('Y-m-d H:i:s'),
							);
				if($this->master_model->insertRecord('dra_accerdited_master',$insert_data))
				{
					log_dra_admin($log_title = "Add DRA Institute Successful", $log_message = serialize($insert_data));
					$this->session->set_flashdata('success','Record added successfully');
					redirect(base_url().'iibfdra/admin/InstitutionMaster');
				}
				else
				{
					log_dra_admin($log_title = "Add DRA Institute Unsuccessful", $log_message = serialize($insert_data));
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'iibfdra/admin/InstitutionMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Accredited Institution</a></li>
					<li class="active">Add</li>
				</ol>';
			
		$data['institutionRes'] = array('institute_code'=>'',
										'institute_name'	=>'',
										'category_code'		=>'',
									);
		$this->load->view('iibfdra/admin/masters/institution_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['institutionRes'] = array();
		
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$institutionRes = $this->master_model->getRecords('dra_accerdited_master',array('id'=>$id));
			if(count($institutionRes))
			{
				$data['institutionRes'] = $institutionRes[0];
			}
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('institute_code','Institute Code','trim|required');
			$this->form_validation->set_rules('institute_name','Institute Name','trim|required');
			$this->form_validation->set_rules('category_code','Category Code','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				
				$update_data = array(	
								'institute_code'		=>$this->input->post('institute_code'),
								'institute_name'		=>ucwords($this->input->post('institute_name')),
								'category_code'			=>$this->input->post('category_code'),
								'modified_by'			=>$this->UserID,
								'modified_on'			=>date('Y-m-d H:i:s'),
							);
				
				if($this->master_model->updateRecord('dra_accerdited_master',$update_data,array('id'=>$id)))
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $institutionRes[0];
					log_dra_admin($log_title = "Edit DRA Institute Successful", $log_message = serialize($desc));
					$this->session->set_flashdata('success','Record updated successfully');
					redirect(base_url().'iibfdra/admin/InstitutionMaster');
				}
				else
				{
					log_dra_admin($log_title = "Edit DRA Institute Unsuccessful", $log_message = serialize($desc));
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'iibfdra/admin/InstitutionMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Institution</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$this->load->view('iibfdra/admin/masters/institution_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('accerdited_delete'=>1);
			if($this->master_model->updateRecord('dra_accerdited_master', $update_data, array('id'=>$id)))
			{
				log_dra_admin($log_title = "Delete DRA Institute Successful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'iibfdra/admin/InstitutionMaster');
			}
			else
			{
				log_dra_admin($log_title = "Delete DRA Institute Unsuccessful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'iibfdra/admin/InstitutionMaster');
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
						$target_file = "uploads/admin/masters_imported_files/"."DRA_INSTITUTE_MASTER_".$this->UserID."_".date('Y-m-d_hia').".txt";
						
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
													'institute_code'	=>$raw_array[0],
													'institute_name'	=>$raw_array[1],
													'institute_secname'	=>$raw_array[2],
													'address1'			=>$raw_array[3],
													'address2'			=>$raw_array[4],
													'address3'			=>$raw_array[5],
													'address4'			=>$raw_array[6],
													'address5'			=>$raw_array[7],
													'address6'			=>$raw_array[8],
													'ste_code'			=>$raw_array[9],
													'pin_code'			=>$raw_array[10],
													'phone'				=>$raw_array[11],
													'mobile'			=>$raw_array[12],
													'email'				=>$raw_array[13],
													'a_i_flag'			=>$raw_array[14],
													'zone_code'			=>$raw_array[15],
													'coord_name'		=>$raw_array[16],
													'designation'		=>$raw_array[17],
													'created_by'		=>$this->UserID,
													'created_on'		=>date('Y-m-d H:i:s'),
												);
				
									$this->master_model->insertRecord('dra_accerdited_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==18)
									{	
										$firstlineflag = 1;  // Skip first header line
										//master_db_backup($basetable = "dra_accerdited_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'iibfdra/admin/InstitutionMaster/import');
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
				log_dra_admin($log_title = "DRA Accredited Institution master import sucessfull", $log_message = "New Accredited Institution master file import sucessfull file=".$target_file);
			}
			else
			{
				log_dra_admin($log_title = "DRA Accredited Institution master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}
		}
		$data['menu_title'] = 'Manage Accredited Institution Master';
		$data['title'] = 'Import Accredited Institution Master';
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'iibfdra/admin/'.$this->router->fetch_class().'">Manage Accredited Institution</a></li>
								<li class="active">Import</li>
							</ol>';
		$data['sample_file'] = 'DRA_ACCREDITED_INSTUTION_ALL_212.TXT';
		$this->load->view('iibfdra/admin/masters/master_import', $data);
	}
	public function download(){
		$data = "INS_INS_CD|INS_INS_NAM|CATEGORY_CODE\n";
		$this->db->where('accerdited_delete',0);
		$inst_list = $this->Master_model->getRecords("dra_accerdited_master a");
		foreach($inst_list as $id => $row_details) {
		  $data .= $row_details['institute_code']."|".$row_details['institute_name']."|".$row_details['category_code']."\n";
		}
		log_dra_admin($log_title = "DRA Accredited Institution master downloaded", $log_message = "");
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="dar_accerdited_institution_master.txt.gz"');
		echo gzencode($data, 9); exit();
	}
	
}