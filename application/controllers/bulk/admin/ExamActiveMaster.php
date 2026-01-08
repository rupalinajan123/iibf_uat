<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExamActiveMaster extends CI_Controller {
	public $UserID;
	public $UserData;	
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('bulk_admin')) {
			redirect('bulk/admin/Login');
		}
		$this->UserData = $this->session->userdata('bulk_admin');
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
		$data["exam_list"] = $this->Master_model->getRecords("exam_master","","exam_name,exam_code,description");
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-dashboard"></i> Masters</a></li>
									<li><a href="'.base_url().'bulk/admin/'.$this->router->fetch_class().'">Exam Activation Master</a></li>
							   </ol>';
		
		$this->load->view('bulk/admin/masters/exam_active_list',$data);
	}
	public function getExamPeriod()
	{
		$exam_periodData = '';
		$exam_code = $this->input->post('exam_code');
		
		//$this->db->distinct();
		$this->db->select('exam_period');
		$exam_periodArr = $this->Master_model->getRecords("exam_activation_master",array('exam_activation_delete'=>0,'exam_code' => $exam_code));
		
		$exam_periodData .= "<select class='form-control' id='exam_period' name='exam_period'>";
        $exam_periodData .= "<option value=''>- Select Exam Period -</option>";
		foreach ($exam_periodArr as $ckey => $cValue) {
			$exam_periodData .= "<option value=" . $cValue['exam_period'] . ">" . $cValue['exam_period'] . "</option>";
		}
		$exam_periodData .= "</select><span class='error'>". form_error('exam_to_date'). "</span>";
		
		echo $exam_periodData;
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
		
		$this->db->where('exam_activation_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');
		$total_row = $this->UserModel->getRecordCount("bulk_exam_activation_master a",$field,$value);
		$url = base_url()."bulk/admin/ExamActiveMaster/getList/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$select = 'a.id exam_active_id,a.exam_period,a.exam_code,exam_from_date,exam_from_time,exam_to_date,exam_to_time,b.id exam_id,b.description,a.tds,a.discount,c.institute_code,c.institute_name';
		$this->db->where('exam_activation_delete',0);
		$this->db->where('exam_delete',0);
		$this->db->join('exam_master b','b.exam_code=a.exam_code','LEFT');	
		$this->db->join('bulk_accerdited_master c','a.institute_code = c.institute_code','LEFT');
		$res = $this->UserModel->getRecords("bulk_exam_activation_master a", $select, $field, $value, $sortkey, $sortval, $per_page, $start);
		if($res)
		{
			$result = $res->result_array();
			
			$i = 0;
			foreach($result as $row)
			{
				//$confirm = "return confirm('Are you sure to delete this record?');";
				//$action = '<a href="'.base_url().'bulk/admin/ExamActiveMaster/edit/'.$row['exam_active_id'].'">Edit | </a> <a href="'.base_url().'bulk/admin/ExamActiveMaster/delete/'.$row['exam_active_id'].'" onclick="'.$confirm.'">Delete</a>';
				$action = '<a href="'.base_url().'bulk/admin/ExamActiveMaster/edit/'.$row['exam_active_id'].'">Edit</a>';
				$data['action'][] = $action;
				$data['checklist'][] = '<input type="checkbox" name="check_list[]" id="check_list_'.$row['exam_active_id'].'" value="'.$row['exam_active_id'].'" class="chk">';
				$result[$i]['exam_from_date'] = date('Y-m-d',strtotime($row['exam_from_date']));
				$result[$i]['exam_to_date'] = date('Y-m-d',strtotime($row['exam_to_date']));
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
			$this->form_validation->set_rules('institute_code','Institute Name','trim|required');
			$this->form_validation->set_rules('exam_code','Exam Name','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('exam_from_date','Exam From Date','trim|required');
			//$this->form_validation->set_rules('exam_from_time','Exam From Time','trim|required');
			$this->form_validation->set_rules('exam_to_date','Exam To Date','trim|required');
			//$this->form_validation->set_rules('exam_to_time','Exam To Time','trim|required');
			//$this->form_validation->set_rules('tds','TDS','trim|required');
			$this->form_validation->set_rules('discount','Discount','trim|required');
			if($this->form_validation->run()==TRUE)
			{
				$from_date = str_replace('/','-',$_POST['exam_from_date']);
				$exam_from_date = date('Y-m-d',strtotime($from_date));
				
				$to_date = str_replace('/','-',$_POST['exam_to_date']);
				$exam_to_date = date('Y-m-d',strtotime($to_date));
				
				$from_time = strtotime($from_date);
				$to_time = strtotime($to_date);
				if($from_time < $to_time)
				{
					$institute_code = $this->input->post('institute_code');	
					$exam_code = $this->input->post('exam_code');
					$exam_period = $this->input->post('exam_period');
					
					// check already added records
					$exam_added = $this->Master_model->getRecords("bulk_exam_activation_master",array('institute_code'=>$institute_code,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_from_date' => $exam_from_date,'exam_to_date'=> $exam_to_date,'exam_activation_delete'=>0));	
					
					if(empty($exam_added))
					{
						$insert_data = array(
										'institute_code'        =>$institute_code,	
										'exam_code'				=>$exam_code,
										'exam_period'			=>$exam_period,
										'exam_from_date'		=>$exam_from_date,
										//'exam_from_time'		=>$this->input->post('exam_from_time'),
										'exam_to_date'			=>$exam_to_date,
										//'exam_to_time'			=>$this->input->post('exam_to_time'),
										'created_by'			=>$this->UserID,
										'created_on'			=>date('Y-m-d H:i:s'),
										//'tds'					=>$this->input->post('tds'),
										'discount'				=>$this->input->post('discount'),
								);
					
						if($this->master_model->insertRecord('bulk_exam_activation_master',$insert_data))
						{
							log_bulk_admin($log_title = "Add Bulk Exam Activation Successfull", $log_message = serialize($insert_data));
							$this->session->set_flashdata('success','Record added successfully');
							redirect(base_url().'bulk/admin/ExamActiveMaster');
						}
						else
						{
							$this->master_model->insertRecord('adminlogs',$logs_data);
							log_bulk_admin($log_title = "Add Bulk Exam Activation Unsuccessfull", $log_message = serialize($insert_data));
							$this->session->set_flashdata('error','Error occured while adding record');
							redirect(base_url().'bulk/admin/ExamActiveMaster/add');
						}
					}
					else
					{
						$this->session->set_flashdata('error','Recored is already Added..!');
						redirect(base_url().'bulk/admin/ExamActiveMaster/add/');
					}
					
				}
				else
				{
					$this->session->set_flashdata('error','From Date should be less than To Date');
					redirect(base_url().'bulk/admin/ExamActiveMaster/add/');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-dashboard"></i> Masters</a></li>
					<li><a href="'.base_url().'bulk/admin/'.$this->router->fetch_class().'">Exam Activation Master</a></li>
					<li class="active">Add</li>
				</ol>';
				
		$data['examActiveRes'] = array(	
										'institute_code'	=>'',
										'exam_code'			=>'',
										'exam_period'		=>'',
										'exam_from_date'	=>'',
										//'exam_from_time'	=>'',
										'exam_to_date'		=>'',
										//'exam_to_time'		=>'',
										//'tds'				=>'',
										'discount'			=>'',
								);
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$data["institute_list"] = $this->Master_model->getRecords("bulk_accerdited_master",array('accerdited_delete'=>0),"institute_name,institute_code",array('institute_name'=>'ASC'));
	
		/*$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("misc_master",array('misc_delete'=>0));*/
		
		$this->load->view('bulk/admin/masters/exam_active_add',$data);
	}
	
	public function edit(){
		$data = array();
		$data['examActiveRes'] = array();
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$examActiveRes = $this->master_model->getRecords('bulk_exam_activation_master',array('id'=>$id));
			if(count($examActiveRes))
			{
				$data['examActiveRes'] = $examActiveRes[0];
			}
		}
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('institute_code','Institute Name','trim|required');
			$this->form_validation->set_rules('exam_code','Exam Name','trim|required');
			$this->form_validation->set_rules('exam_period','Exam Period','trim|required');
			$this->form_validation->set_rules('exam_from_date','Exam From Date','trim|required');
			//$this->form_validation->set_rules('exam_from_time','Exam From Time','trim|required');
			$this->form_validation->set_rules('exam_to_date','Exam To Date','trim|required');
			//$this->form_validation->set_rules('exam_to_time','Exam To Time','trim|required');
			//$this->form_validation->set_rules('tds','TDS','trim|required');
			$this->form_validation->set_rules('discount','Discount','trim|required');
			
			
			if($this->form_validation->run() == TRUE)
			{
				$from_date = str_replace('/','-',$_POST['exam_from_date']);
				$exam_from_date = date('Y-m-d',strtotime($from_date));
				$to_date = str_replace('/','-',$_POST['exam_to_date']);
				$exam_to_date = date('Y-m-d',strtotime($to_date));
				
				$from_time = strtotime($from_date);
				$to_time = strtotime($to_date);
				if($from_time < $to_time)
				{
					$update_data = array(
										'institute_code'		=>$this->input->post('institute_code'),
										'exam_code'				=>$this->input->post('exam_code'),
										'exam_period'			=>$this->input->post('exam_period'),
										'exam_from_date'		=>$exam_from_date,
										//'exam_from_time'		=>$this->input->post('exam_from_time'),
										'exam_to_date'			=>$exam_to_date,
										//'exam_to_time'			=>$this->input->post('exam_to_time'),
										//'tds'					=>$this->input->post('exam_code'),
										'modified_by'			=>$this->UserID,
										'modified_on'			=>date('Y-m-d H:i:s'),
										//'tds'					=>$this->input->post('tds'),
										'discount'				=>$this->input->post('discount'),
								);
					
					if($this->master_model->updateRecord('bulk_exam_activation_master',$update_data, array('id'=>$id)))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examActiveRes[0];
						log_bulk_admin($log_title = "Edit Bulk Exam Activation Successfull", $log_message = serialize($desc));
						$this->session->set_flashdata('success','Record updated successfully');
						redirect(base_url().'bulk/admin/ExamActiveMaster');
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examActiveRes[0];
						log_bulk_admin($log_title = "Edit Bulk Exam Activation Unsuccessfull", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating record');
						redirect(base_url().'bulk/admin/ExamActiveMaster/edit/'.$id);
					}
				}
				else
				{
					$this->session->set_flashdata('error','From Date should be less than To Date');
					redirect(base_url().'bulk/admin/ExamActiveMaster/edit/'.$id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-dashboard"></i> Masters</a></li>
					<li><a href="'.base_url().'bulk/admin/'.$this->router->fetch_class().'">Exam Activation Master</a></li>
					<li class="active">Edit</li>
				</ol>';
		
		$data["exam_list"] = $this->Master_model->getRecords("exam_master",array('exam_delete'=>0),"exam_name,exam_code,description");
		
		$data["institute_list"] = $this->Master_model->getRecords("bulk_accerdited_master",array('accerdited_delete'=>0),"institute_name,institute_code");
		
		/*$this->db->distinct();
		$this->db->select('exam_period');
		$data["exam_period"] = $this->Master_model->getRecords("misc_master",array('misc_delete'=>0));*/
		
		$this->load->view('bulk/admin/masters/exam_active_add',$data);
	}
	
	public function delete()
	{
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		if(is_numeric($id))
		{
			$update_data = array('exam_activation_delete'=>1);
			
			if($this->master_model->updateRecord('bulk_exam_activation_master', $update_data, array('id'=>$id)))
			{
				log_bulk_admin($log_title = "Delete Bulk Exam Activation Successfull", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'bulk/admin/ExamActiveMaster');
			}
			else
			{
				log_bulk_admin($log_title = "Delete Bulk Exam Activation Unsuccessfull", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'bulk/admin/ExamActiveMaster');
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
						$target_file = "uploads/admin/masters_imported_files/"."EXAM_ACTIVATION_MASTER_".$this->UserID."_".date('Y-m-d_hia').".txt";
						
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

									$exam_from_date = date('Y-m-d',strtotime($raw_array[2]));
									$exam_from_time = date('H:i:s',strtotime($raw_array[2]));
									$exam_to_date = date('Y-m-d',strtotime($raw_array[3]));
									$exam_to_time = date('H:i:s',strtotime($raw_array[3]));
									//insert in DB
									$insert_data = array(	
													'exam_code'			=>$raw_array[0],
													'exam_period'		=>$raw_array[1],
													'exam_from_date'	=>$exam_from_date,
													//'exam_from_time'	=>$exam_from_time,
													'exam_to_date'		=>$exam_to_date,
													//'exam_to_time'		=>$exam_to_time
												);
									$this->master_model->insertRecord('bulk_exam_activation_master',$insert_data);
								}
								else
								{
									$raw_array = explode("|" ,$line);
									// Check for valid column count
									if(count($raw_array)==4)
									{	
										$firstlineflag = 1;  // Skip first header line
										//master_db_backup($basetable = "bulk_exam_activation_master"); // keep old and new master in DB
									}	
									else
									{
										@unlink($target_file);
										$this->session->set_flashdata('error','Please upload valid file');
										redirect(base_url().'bulk/admin/ExamActiveMaster/import');
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
				log_bulk_admin($log_title = "Bulk Exam Activation master import sucessfull", $log_message = "New Bulk Exam Activation master file import sucessfull file=".$target_file);
			}
			else
			{
				log_bulk_admin($log_title = "Bulk Exam Activation master import failed", $log_message = "Error = ".$data['error']."  file = ".$target_file);
			}	
		}
		$data['menu_title'] = 'Manage Exam Activation Master';
		$data['title'] = 'Import Exam Activation Master';
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
								<li><a href="'.base_url().'bulk/admin/'.$this->router->fetch_class().'">Exam Activation Master</a></li>
								<li class="active">Import</li>
							</ol>';
		$data['sample_file'] = 'BULK_EXAM_ACTIVATE.TXT';
		$this->load->view('bulk/admin/masters/master_import', $data);
	}
	
	public function download(){
		$data = "Exam Name|Exam Period|Exam From Date|Exam To Date\n";
		$this->db->where('exam_activation_delete',0);
		$exam_active_list = $this->Master_model->getRecords("bulk_exam_activation_master a");
		foreach($exam_active_list as $id => $row_details) {
		  $data .= $row_details['exam_code']."|".$row_details['exam_period']."|".date('d-M-Y',strtotime($row_details['exam_from_date']))." ".$row_details['exam_from_time']."|".date('d-M-Y',strtotime($row_details['exam_to_date']))." ".$row_details['exam_to_time']."\n";
		}
		log_bulk_admin($log_title = "Bulk Exam Activation master downloaded", $log_message = "");
		header("Content-type: application/x-gzip");
		header('Content-Disposition: attachement; filename="bulk_exam_activation_master.txt.gz"');
		echo gzencode($data, 9); exit();
	}
}