<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstitutionMaster extends CI_Controller {
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
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-dashboard"></i> Masters</a></li>
									<li><a href="'.base_url().'bulk/admin/'.$this->router->fetch_class().'">Manage Institution</a></li>
							   </ol>';
		
		$this->load->view('bulk/admin/masters/institution_list',$data);
	}
	
	public function getList()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = 'institute_name';
		$sortval = 'ASC';
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
		$total_row = $this->UserModel->getRecordCount("bulk_accerdited_master a",$field,$value);
		$url = base_url()."bulk/admin/InstitutionMaster/getList/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$this->db->where('accerdited_delete',0);
		$res = $this->UserModel->getRecords("bulk_accerdited_master a", '', $field, $value, $sortkey, $sortval, $per_page, $start);
		
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			foreach($result as $row)
			{
				$action = '';
				/*$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'bulk/admin/InstitutionMaster/edit/'.$row['id'].'">Edit | </a> <a href="'.base_url().'bulk/admin/InstitutionMaster/delete/'.$row['id'].'" onclick="'.$confirm.'">Delete</a>';*/
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
			$this->form_validation->set_rules('address1','Addressline1','trim|max_length[30]|required|xss_clean');
			 
			 if(isset($_POST['address2']) && $_POST['address2']!='')
				{
					$this->form_validation->set_rules('address2','Addressline2','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['address3']) && $_POST['address3']!='')
				{
					$this->form_validation->set_rules('address3','Addressline3','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['address4']) && $_POST['address4']!='')
				{
					$this->form_validation->set_rules('address4','Addressline4','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				$this->form_validation->set_rules('address5','District','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				
				$this->form_validation->set_rules('address6','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				
				$this->form_validation->set_rules('ste_code','State ','trim|required|xss_clean');
				if($this->input->post('ste_code')!='')
				{
					$state=$this->input->post('ste_code');
				}
				
				$this->form_validation->set_rules('pin_code','Pincode/Zipcode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
				
				$this->form_validation->set_rules('phone','phone','trim|xss_clean');
				$this->form_validation->set_rules('mobile','Mobile no','trim|xss_clean');
				$this->form_validation->set_rules('email','Email id','trim|required|xss_clean');
				$this->form_validation->set_rules('zone_code','Zone code','trim|xss_clean');
				$this->form_validation->set_rules('coord_name','Contact Person Name','trim|xss_clean');
				$this->form_validation->set_rules('designation','Contact Person Designation','trim|xss_clean');
				$this->form_validation->set_rules('gstin_no','GSTIN No','trim|xss_clean');
			
			
			if($this->form_validation->run()==TRUE)
			{
			
			 //create password
		     $en_password=$this->generate_random_password();	
			 include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			 $key = $this->config->item('pass_key');
			 $aes = new CryptAES();
			 $aes->set_key(base64_decode($key));
			 $aes->require_pkcs5();
			 $password = $aes->encrypt($en_password);
			 $institute_code = $this->input->post('institute_code');
				
				$insert_data = array(	
								'institute_code'		=>$institute_code,
								'institute_name'		=>ucwords($this->input->post('institute_name')),
								//'category_code'			=>$this->input->post('category_code'),
								'address1'		=>$this->input->post('address1'),
								'address2'		=>$this->input->post('address2'),
								'address3'		=>$this->input->post('address3'),
								'address4'		=>$this->input->post('address4'),
								'address5'		=>$this->input->post('address5'),
								'address6'		=>$this->input->post('address6'),
								'ste_code'		=>$this->input->post('ste_code'),
								'pin_code'		=>$this->input->post('pin_code'),
								'phone'		=>$this->input->post('phone'),
								'mobile'		=>$this->input->post('mobile'),
								'email'		=>$this->input->post('email'),
								'zone_code'		=>$this->input->post('zone_code'),
								'coord_name'		=>$this->input->post('coord_name'),
								'designation'		=>$this->input->post('designation'),
								'gstin_no'		=>$this->input->post('gstin_no'),
								'password'		=>$password,
								'created_by'			=>$this->UserID,
								'created_on'			=>date('Y-m-d H:i:s'),
							);
						//	print_r($insert_data);exit;
				if($this->master_model->insertRecord('bulk_accerdited_master',$insert_data))
				{
					log_bulk_admin($log_title = "Capacity available", $log_message = serialize($insert_data));
					$this->session->set_flashdata('success','Record added successfully ! Bank login username = '.$institute_code.' and  password ='.$en_password.'');
					redirect(base_url().'bulk/admin/InstitutionMaster');
				}
				else
				{
					log_bulk_admin($log_title = "Capacity available", $log_message = serialize($insert_data));
					$this->session->set_flashdata('error','Error occured while adding record');
					redirect(base_url().'bulk/admin/InstitutionMaster/add');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="'.base_url().'bulk/admin/'.$this->router->fetch_class().'">Manage Accredited Institution</a></li>
					<li class="active">Add</li>
				</ol>'; 
			
		$data['institutionRes'] = array('institute_code'=>'',
										'institute_name'	=>'',
										'category_code'		=>'',
									);
		
		$this->db->where('state_master.state_delete','0');
		$states=$this->master_model->getRecords('state_master');
		
		$data['states']= $states;
		
		$this->load->view('bulk/admin/masters/institution_add',$data);
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
	
	public function check_checkpin($pincode,$statecode)
	{
		if($statecode!="" && $pincode!='')
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	$str='Please enter Valid Pincode';
				$this->form_validation->set_message('check_checkpin', $str); 
				return false;}
			else
			$this->form_validation->set_message('error', "");
			{return true;}
		}
		else
		{
			$str='Pincode/State field is required.';
			$this->form_validation->set_message('check_checkpin', $str); 
			return false;
		}
	}

	##---------check pincode/zipcode alredy exist or not (Tejasvi)-----------##
	public function checkpin_inst_addr()
	{
		$statecode=$_POST['statecode'];
		$pincode=$_POST['pincode'];
		
		if($statecode!="")
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			//exit;
			if($prev_count==0)
			{echo 'false';}
			else
			{echo 'true';}
		}
		else
		{
			echo 'false';
		}
	} 
	##---------check institute_code alredy exist or not (Tejasvi)-----------##
	public function checkcode_inst()
	{
		$institute_code=$_POST['institute_code'];
		
		if($institute_code!="")
		{
			
		 	$prev_count=$this->master_model->getRecordCount('bulk_accerdited_master',array('institute_code'=>$institute_code));
			//echo $this->db->last_query();
		    //exit;
			if($prev_count==0)
			{echo 'true';}
			else
			{echo 'false';}
		}
		else
		{
			echo 'false';
		}
	} 
	
	function generate_random_password($length = 8, $level = 2) // function to generate new password
	{
		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));
		$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
		$password = "";
		$counter = 0;
		while ($counter < $length) {
		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
		if (!strstr($password, $actChar)) {
			$password .= $actChar;
				$counter++;
			}
		}
		return $password;
	}
	
}