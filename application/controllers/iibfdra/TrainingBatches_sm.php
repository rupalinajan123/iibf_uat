<?php
	defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
	class TrainingBatches_sm extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			if(!$this->session->userdata('dra_institute')) {
				redirect('iibfdra/InstituteLogin');
			}
			$this->load->library('upload');	
			$this->load->library('session');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->model('UserModel');
			$this->load->model('master_model');	
			$this->load->helper('pagination_helper');
			$this->load->library('pagination');	
			$this->load->model('log_model');
			$this->load->helper('general_helper');
			$this->load->helper('dra_seatallocation_helper');	
		}
		
		//view batch list
		public function index()
		{
			$login_agency=$this->session->userdata('dra_institute');
			$agency_id=$login_agency['dra_inst_registration_id'];
			
			$this->db->select('agency_batch.*,agency_center.location_name,city_master.city_name');
			$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','left');
			$this->db->join('city_master','city_master.id=agency_center.location_name','left');
			$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
			$batchDetails = $this->master_model->getRecords('agency_batch',array('agency_batch.agency_id'=>$agency_id),'',array('agency_batch.batch_from_date'=>'DESC'));
			
			$data['middle_content']	= 'drabatch_list';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$data['batchDetails'] = $batchDetails;
			$this->load->view('iibfdra/common_view',$data);
		}
		
		//add new batch againt agency
		public function add_batches()
		{
			$data['error_msg'] = '';
			//login agency details
			$this->load->helper('general_agency_helper');// added by MANOJ
			
			$this->form_validation->set_rules('ccity','City','required');	
			$this->form_validation->set_rules('cdistrict','District','required');
			$this->form_validation->set_rules('state','State','required');
			$this->form_validation->set_rules('cpincode','Pincode','required');			
			$this->form_validation->set_rules('batch_type','Batch Type','required');
			$this->form_validation->set_rules('batch_type','Batch Type','required');
			$this->form_validation->set_rules('hours','Batch Hours','trim|required');
			$this->form_validation->set_rules('center_id','Center name','trim|required');
			// $this->form_validation->set_rules('inspector_id','Inspector Name','trim|required');
			$this->form_validation->set_rules('batch_name','Batch Name','trim|required');
			$this->form_validation->set_rules('batch_from_date','Batch From Date','trim|required');
			$this->form_validation->set_rules('timing_from','Timimg From','trim|required');
			$this->form_validation->set_rules('timing_to','Timing Name','trim|required');
			$this->form_validation->set_rules('training_medium','Training Medium','trim');
			$this->form_validation->set_rules('total_candidates','Total Number Of Candidates','trim|required');
			$this->form_validation->set_rules('faculty_name','Faculty Name','trim|required');
			$this->form_validation->set_rules('faculty_qualification','Faculty Qualification','trim|required');
			$this->form_validation->set_rules('contact_person_name','Contact Person Name','trim|required');
			$this->form_validation->set_rules('contact_person_phone','Contact Person Phone','trim|required');
			$this->form_validation->set_rules('name_of_bank','Name Of Bank','trim|required');
			
			########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
			$this->form_validation->set_rules('batch_online_offline_flag','Online / Offline Batch','trim|required'); 
			$batch_online_offline_flag = $this->input->post("batch_online_offline_flag");
			if($batch_online_offline_flag == 1)
			{
				$this->form_validation->set_rules('batch_online_login_ids[]','Online / Offline Batch Login Ids','trim|required'); 
				$this->form_validation->set_rules('batch_online_login_pass[]','Online / Offline Batch Password','trim|required'); 
				$this->form_validation->set_rules('online_training_platform','Name of the on-line training platform used','trim|required'); 
			}
			########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
			/*--------------------code by Pooja Godse-----------------*/			
			
			$this->form_validation->set_rules('batch_to_date','batch To Date','trim|required|xss_clean|callback_check_todate');
			if(isset($_POST['btnSubmit'])) 
			{		
				if($this->input->post('state')!='')
				{
					$state=$this->input->post('state');
				}
				
				$this->form_validation->set_rules('cpincode','Pincode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
				/*--------------------end code by Pooja Godse-----------------*/
				//do not keep last name field compulsory - 21-01-2017
				//$this->form_validation->set_rules('lastname','Last Name','trim|required');
				
				$this->form_validation->set_rules('addressline1','Address Line1','trim');
				$this->form_validation->set_rules('addressline2','Address Line2','trim');
				$this->form_validation->set_rules('addressline3','Address Line3','trim');
				$this->form_validation->set_rules('addressline4','Address Line4','trim');				
				
				//START : CODE ADDED BY SAGER ON 15-12-2020 FOR SERVER SIDE VALIDATION FOR DATE
				$chk_date_flag = 0;
				$current_date = date("Y-m-d");
				$date_check = date('Y-m-d', strtotime("+2days", strtotime($current_date)));
				if($date_check >= $this->input->post('batch_from_date') || $date_check >= $this->input->post('batch_to_date'))
				{
					$chk_date_flag = 1;
					$data['error_msg'] = 'Invalid date selection';					
				}
				//END : CODE ADDED BY SAGER ON 15-12-2020 FOR SERVER SIDE VALIDATION FOR DATE
				
				if($this->form_validation->run() == TRUE && $chk_date_flag == 0) 
				{
					$login_agency=$this->session->userdata('dra_institute');
					$agency_id=$login_agency['dra_inst_registration_id'];					
					
					$batch_type = $this->input->post('batch_type');
					$center_id = $this->input->post('center_id');
					// $inspector_id = $this->input->post('inspector_id');
					$batch_name = $this->input->post('batch_name');
					$batch_from_date = $this->input->post('batch_from_date');
					$batch_to_date = $this->input->post('batch_to_date');
					$timing_from = $this->input->post('timing_from');
					$timing_to = $this->input->post('timing_to');
					$training_medium = $this->input->post('training_medium');
					$total_candidates = $this->input->post('total_candidates');
					$faculty_name = $this->input->post('faculty_name');
					$faculty_qualification = $this->input->post('faculty_qualification');
					// new added by Manoj 
					$faculty_name2 = $this->input->post('faculty_name2');
					$faculty_qualification2 = $this->input->post('faculty_qualification2');
					// 
					$contact_person_name = $this->input->post('contact_person_name');
					$contact_person_phone = $this->input->post('contact_person_phone');
					$name_of_bank = $this->input->post('name_of_bank');
					$addressline1 = $this->input->post('addressline1');
					$addressline2 = $this->input->post('addressline2');
					$addressline3 = $this->input->post('addressline3');
					$addressline4 = $this->input->post('addressline4');
					$remarks 	  = $this->input->post('remarks');
					$city=$this->input->post('ccity');
					$district=$this->input->post('cdistrict');
					$state_code=$this->input->post('state');
					$pincode=$this->input->post('cpincode');
					
					if($batch_type == 'C') { $hours=100; }
					else { $hours = $this->input->post('hours'); }
					
					########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
					if($batch_online_offline_flag == 1) { $online_training_platform = $this->input->post('online_training_platform'); }
					else { $online_training_platform = ""; }
					########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
					
					$data=array(
					'batch_type' => $batch_type,
					'hours' => $hours,
					'agency_id' => $agency_id,
					'center_id' => $center_id,
					// 'inspector_id' => $inspector_id,
					'batch_code' => 'BTCH1'.rand(1,900),
					'batch_name' => $batch_name,
					'batch_from_date' => $batch_from_date,
					'batch_to_date' => $batch_to_date,
					'timing_from' => $timing_from,
					'timing_to' => $timing_to,
					'training_medium' => $training_medium,
					'total_candidates' => $total_candidates,
					'faculty_name' => $faculty_name,
					'faculty_qualification' => $faculty_qualification,
					'faculty_name2' => $faculty_name2,
					'faculty_qualification2' => $faculty_qualification2,
					'contact_person_name' => $contact_person_name,
					'contact_person_phone' => $contact_person_phone,
					'name_of_bank' => $name_of_bank,
					'addressline1' => $addressline1,
					'addressline2' => $addressline2,
					'addressline3' => $addressline3,
					'addressline4' => $addressline4,
					'remarks' 	   => $remarks,
					
					//'city' => $city,
					//'district' => $district,
					//'state_code' => $state_code,
					'pincode' => $pincode,
					'batch_online_offline_flag' => $batch_online_offline_flag,
					'online_training_platform' => $online_training_platform,
					'created_on' 	=> date('Y-m-d H:i:s'),
					
					);
					//print_r($data);die;
					
					if( $this->master_model->insertRecord('agency_batch',$data) ) 
					{
						$batch_id = $this->db->insert_id();
						
						########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
						if($batch_online_offline_flag == 1) // IF BATCH IS ONLINE THEN ADD ONLINE USER DETAILS IN TABLE 'agency_online_batch_user_details'
						{
							$online_user_login_ids = $this->input->post('batch_online_login_ids');
							$online_user_password = $this->input->post('batch_online_login_pass');
							
							for($i=0; $i < count($online_user_login_ids); $i++)
							{
								$add_user_data['agency_id'] = $agency_id;
								$add_user_data['batch_id'] = $batch_id;
								$add_user_data['login_id'] = $online_user_login_ids[$i];
								$add_user_data['password'] = base64_encode($online_user_password[$i]);
								$add_user_data['created_on'] = date('Y-m-d H:i:s');
								$this->master_model->insertRecord('agency_online_batch_user_details',$add_user_data);
							}
						}
						########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
						
						//log_dra_agency_center_detail($log_title = "Center Rejected",$center_id,serialize($log_data));
						$batch_config = config_batch_code($batch_id); //batch_code
						$batch_code = 'BTCH1'.$batch_config;					
						$update_data = array('batch_code' => $batch_code);					
						$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
						log_dra_user($log_title = "Add Agency Batch Successfully", $log_message = serialize($data));
						$this->session->set_flashdata('success','Record added successfully');
						redirect(base_url().'iibfdra/TrainingBatches/');
						} else {
						log_dra_user($log_title = "Add Egency Batch Unsuccessfully", $log_message = serialize($data));
						$this->session->set_flashdata('error','Error occured while adding record');
						redirect(base_url().'iibfdra/TrainingBatches/add_batches');
					}
					
				}//check validations
				else 
				{
					$data['validation_errors'] = validation_errors();
				}
				
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				$institute_code=$login_agency['institute_code'];				
				
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$this->db->where('state_master.state_delete', '0');
				$states = $this->master_model->getRecords('state_master');
				$this->db->where('city_master.city_delete', '0');
				$cities = $this->master_model->getRecords('city_master');				
				
				
				/*-----------------Code by pooja godse---------------*/
				$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
				$this->db->group_by('agency_center.center_id');		
				$agency_center = $this->master_model->getRecords('agency_center',array('institute_code'=>$_SESSION['dra_institute']['institute_code']),'',array('center_status'=>'ASC'));
				
				$resultarr = array();
				if(count($agency_center) > 0)
				{
					foreach ($agency_center as $agency_center) 
					{						
						$where = "FIND_IN_SET('".$agency_center['center_id']."', centers_id)";  
						
						$this->db->where($where);
						$this->db->where_in('centers_id',$agency_center['center_id']);
						$this->db->order_by('agency_renew_id',"desc");
						$this->db->limit(1);
						$agency_center_renew = $this->db->get('agency_center_renew');
						$agency_center_renew =$agency_center_renew->result_array();
						if(count($agency_center_renew) > 0)
						{							
							$agency_center['renew_pay_status'] = $agency_center_renew[0]['pay_status'];
							$agency_center['renew_type'] = $agency_center_renew[0]['renew_type'];
							$resultarr[] = $agency_center;
						}
						else
						{
							$agency_center['renew_pay_status'] = '';
							$agency_center['renew_type'] = "";
							$resultarr[] = $agency_center;
						}
					}
				}				
				
				$this->db->select('dra_inst_registration.*,state_master.state_name');
				$this->db->where('dra_inst_registration.id',$_SESSION['dra_institute']['dra_inst_registration_id']);
				$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT'); //by manoj 
				$inst_registration_info = $this->master_model->getRecords('dra_inst_registration');				
				
				$data['inst_registration_info'] = $inst_registration_info;
				$data['agency_center'] = $resultarr;				
				
				/*-----------------end Code by pooja godse---------------*/				
				//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
				$this->db->not_like('name','Election Voters card');
				$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
				$medium_master = $this->master_model->getRecords('agency_batch_medium_master');				
				$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
				$center_master = $this->master_model->getRecords('dra_center_master',array(),'',array('center_name'=>'ASC'));
				
				$this->load->helper('captcha');
				$this->session->unset_userdata("draexamcaptcha");
				$this->session->set_userdata("draexamcaptcha", rand(1, 100000));
				$vals = array('img_path' => './uploads/applications/','img_url' => '../../../uploads/applications/');
				$cap = create_captcha($vals);
				$_SESSION["draexamcaptcha"] = $cap['word']; 
				$data['states'] = $states;
				$data['image'] = $cap['image'];
				$data['idtype_master'] = $idtype_master;
				$data['medium_master'] = $medium_master;
				//$data['center_master'] = $centers->result_array();
				$data["middle_content"] = 'drabatch_add';
				$data["active_exams"] = $res;
				$this->load->view('iibfdra/common_view',$data);			
			}
			else
			{
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$institute_code=$login_agency['institute_code'];
				
				// $this->db->select('agency_center.*');
				//       $this->db->where('agency_center.agency_id',$agency_id);
				//       $this->db->where('agency_center.institute_code',$institute_code);
				
				
				//       $centers = $this->db->get('agency_center');
				//print_r($query->result_array()); die;
				
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$this->db->where('state_master.state_delete', '0');
				$states = $this->master_model->getRecords('state_master');
				
				//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
				$this->db->not_like('name','Election Voters card');
				$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));				
				
				$medium_master = $this->master_model->getRecords('agency_batch_medium_master');
				
				$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
				$center_master = $this->master_model->getRecords('dra_center_master',array(),'',array('center_name'=>'ASC'));				
				
				/*-----------------Code by pooja godse---------------*/
				$this->db->group_by('agency_center.center_id');
				$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); //by manoj
				$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
				$agency_center = $this->master_model->getRecords('agency_center',array('institute_code'=>$_SESSION['dra_institute']['institute_code']),'',array('center_status'=>'ASC'));
				
				$resultarr = array();
				if(count($agency_center) > 0)
				{
					foreach ($agency_center as $agency_center) 
					{
						$where = "FIND_IN_SET('".$agency_center['center_id']."', centers_id)";  
						
						$this->db->where($where);
						$this->db->order_by('agency_renew_id',"desc");
						$this->db->limit(1);
						$agency_center_renew = $this->db->get('agency_center_renew');
						$agency_center_renew =$agency_center_renew->result_array();
						if(count($agency_center_renew) > 0)
						{							
							$agency_center['renew_pay_status'] = $agency_center_renew[0]['pay_status'];
							$agency_center['renew_type'] = $agency_center_renew[0]['renew_type'];
							$resultarr[] = $agency_center;
						}
						else
						{
							$agency_center['renew_pay_status'] = '';
							$agency_center['renew_type'] = '';
							$resultarr[] = $agency_center;
						}
					}
				}				
				
				/*$inst_registration_info = $this->master_model->getRecords('dra_inst_registration',array('id'=>$_SESSION['dra_institute']['dra_inst_registration_id']));*/
				// Modify by manoj MMM
				$this->db->select('dra_inst_registration.*,state_master.state_name');
				$this->db->where('dra_inst_registration.id',$_SESSION['dra_institute']['dra_inst_registration_id']);
				$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT'); //by manoj 				
				$inst_registration_info = $this->master_model->getRecords('dra_inst_registration');
				
				$data['inst_registration_info'] = $inst_registration_info;
				$data['agency_center'] = $resultarr;								
				/*-----------------end Code by pooja godse---------------*/				
				
				$this->load->helper('captcha');
				$this->session->unset_userdata("draexamcaptcha");
				$this->session->set_userdata("draexamcaptcha", rand(1, 100000));
				$vals = array('img_path' => './uploads/applications/','img_url' => '../../../uploads/applications/');
				$cap = create_captcha($vals);
				$_SESSION["draexamcaptcha"] = $cap['word']; 
				$data['states'] = $states;
				$data['image'] = $cap['image'];
				$data['idtype_master'] = $idtype_master;
				$data['medium_master'] = $medium_master;
				//$data['center_master'] = $centers->result_array();
				$data["middle_content"] = 'drabatch_add';
				$data["active_exams"] = $res;
				$this->load->view('iibfdra/common_view',$data);	
			}			
		}
		
		//get details of center after selection
		public function getcenterDetails()
		{ 
			$postData = $this->input->post('center_id');
			//echo $postData; die;
			$login_agency=$this->session->userdata('dra_institute');
			$agency_id=$login_agency['dra_inst_registration_id'];
			// get data
			$where = array('center_id'=>$postData,'agency_id'=>$agency_id);
			$this->db->select('*');
			$this->db->where($where);
			$centers = $this->db->get('agency_center');
			$data = $centers->result_array();
			
			$this->db->select('agency_center.*,city_master.city_name,state_master.state_code,state_master.state_name');
			$this->db->where('center_id',$postData);
			$this->db->where('agency_id',$agency_id);
			$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
			$this->db->join('state_master','state_master.state_code=agency_center.state','LEFT');
			$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
			$res_arr = $this->master_model->getRecords("agency_center");
			$res_value = array();
			
			if(count($res_arr))
			{			
				foreach($res_arr as $row_val)
				{
					
					if( $row_val['center_validity_from'] != ''){	
						
						$row_val['validity_chk_from'] =(date('Y-m-d',strtotime("+6 day", strtotime($row_val['center_validity_from']))));
						
						}else{
						$row_val['validity_chk_from'] =(date('Y-m-d',strtotime("+6 day", strtotime($row_val['center_validity_from']))));
						
					}
					
					
					$res_value[] = $row_val;
				}			
			}
			
			// print_r($centers->result_array());
			// echo json_encode($data); 
			echo json_encode($res_value); 
		}
		
		//get inspector details againt center
		public function getinspectorDetails()
		{ 
			$center_id = $this->input->post('center_id');
			//echo $postData; die;
			$login_agency=$this->session->userdata('dra_institute');
			$agency_id=$login_agency['dra_inst_registration_id'];
			// get data
			
			
			// $this->db->select('agency_batch.*, agency_inspector_master.*');
			// $this->db->join('agency_inspector_master', 'agency_inspector_master.id = agency_batch.inspector_id', 'left');    
			$this->db->where('center_id',$center_id);
			$this->db->where('agency_id',$agency_id);
			$sql = $this->db->get('agency_batch');
			// print_r($sql->result_array()); die;
			foreach($sql->result_array() as $sql){
				$arr[] = $sql['inspector_id'];
			}
			$this->db->join('agency_inspector_master', 'agency_inspector_master.id = agency_inspector_center.inspector_id', 'left');   
			$this->db->where('agency_inspector_center.center_id',$center_id);
			$this->db->where('agency_inspector_master.is_active',1);
			$this->db->where_not_in('agency_inspector_center.inspector_id',$arr);
			$inspectors = $this->master_model->getRecords('agency_inspector_center');
			//print_r($inspectors); die;
			echo json_encode($inspectors); 
			
		}
		
		//view batch details
		public function view() 
		{
			
			if($this->uri->segment(4) ) {
				
				$batchid = trim($this->uri->segment(4) ); 
				$batch_id = base64_decode($batchid);
				$batchId = intval($batch_id);
				
				$batch_reject_text = array();
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$this->db->select('agency_batch.*,agency_center.location_name,agency_center.state,agency_center.district,agency_center.city,agency_center.pincode,agency_inspector_master.inspector_name,state_master.state_name,city_master.city_name');		
				$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','left');
				$this->db->join('state_master','agency_center.state=state_master.state_code','left');
				$this->db->join('city_master','city_master.id=agency_center.location_name','left');
				$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
				//$this->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','left');
				$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_batch.inspector_id','left');
				$batchDetails = $this->master_model->getRecords('agency_batch',array('agency_batch.id'=>$batchId));
				// print_r($batchDetails); die;
				
				/*$this->db->select('agency_batch_rejection.rejection'); 
					$this->db->order_by("agency_batch_rejection.created_on", "DESC");
					$this->db->limit(1);			
				$batch_reject_text = $this->master_model->getRecords('agency_batch_rejection', array('agency_batch_rejection.batch_id' => $batch_id ));*/
				$this->db->select('agency_batch_rejection.rejection'); 
				$this->db->order_by("agency_batch_rejection.created_on", "DESC");
				$this->db->limit(1);	
				$reason = $this->master_model->getValue('agency_batch_rejection',array('batch_id'=>$batchId), 'rejection');
				
				########## START : CODE ADDED BY SAGAR ON 04-09-2020 ###################
				$online_batch_user_details = array();
				if(isset($batchDetails[0]['batch_online_offline_flag']) && $batchDetails[0]['batch_online_offline_flag'] != "")
				{
					if($batchDetails[0]['batch_online_offline_flag'] == 1)
					{
						$this->db->where('agency_id', $agency_id);
						$this->db->where('batch_id', $batchId);
						$online_batch_user_details = $this->master_model->getRecords('agency_online_batch_user_details');
					}
				}
				$data['online_batch_user_details'] = $online_batch_user_details;
				########## END : CODE ADDED BY SAGAR ON 04-09-2020 ###################
				
				$data['middle_content']	= 'drabatch_view';
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$data['active_exams'] = $res;
				$data['batchDetails'] = $batchDetails;
				$data['reason'] = $reason;
				$this->load->view('iibfdra/common_view',$data);
			}
		}
		
		//edit batch details
		public function edit() {
			$data['error_msg'] = '';
			if($this->uri->segment(4) ) {
				
				$batchid = trim($this->uri->segment(4) ); 
				$batch_id = base64_decode($batchid);
				$batchId = intval($batch_id);
				
				
				$old = $this->master_model->getRecords('agency_batch',array('id'=>$batchId));
				
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				$institute_code=$login_agency['institute_code'];
				
				if(isset($_POST['btnSubmit'])) {
					
					$this->form_validation->set_rules('batch_type','Batch Type','required');
					$this->form_validation->set_rules('hours','Batch Hours','trim|required');
					$this->form_validation->set_rules('center_id','Center name','trim|required');
					// $this->form_validation->set_rules('inspector_id','Inspector Name','trim|required');
					$this->form_validation->set_rules('batch_name','Batch Name','trim|required');
					$this->form_validation->set_rules('batch_from_date','Batch From Date','trim|required');
					$this->form_validation->set_rules('timing_from','Timimg From','trim|required');
					$this->form_validation->set_rules('timing_to','Timing Name','trim|required');
					$this->form_validation->set_rules('training_medium','Training Medium','trim');
					$this->form_validation->set_rules('total_candidates','Total Number Of Candidates','trim|required');
					$this->form_validation->set_rules('faculty_name','Faculty Name','trim|required');
					$this->form_validation->set_rules('faculty_qualification','Faculty Qualification','trim|required');
					$this->form_validation->set_rules('contact_person_name','Contact Person Name','trim|required');
					$this->form_validation->set_rules('contact_person_phone','Contact Person Phone','trim|required');
					$this->form_validation->set_rules('name_of_bank','Name Of Bank','trim|required');
					
					########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
					$this->form_validation->set_rules('batch_online_offline_flag','Offline / Online Batch','trim|required');
					$batch_online_offline_flag = $this->input->post("batch_online_offline_flag");
					if($batch_online_offline_flag == 1)
					{
						$this->form_validation->set_rules('batch_online_login_ids[]','Online / Offline Batch Login Ids','trim|required'); 
						$this->form_validation->set_rules('batch_online_login_pass[]','Online / Offline Batch Password','trim|required'); 
						$this->form_validation->set_rules('online_training_platform','Name of the on-line training platform used','trim|required'); 
					}
					########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
					/*--------------------code by Pooja Godse-----------------*/
					
					
					$this->form_validation->set_rules('batch_to_date','batch To Date','trim|required|xss_clean|callback_check_todate');
					
					
					/*if($this->input->post('cstate')!='')
						{
						$state=$this->input->post('cstate');
					}*/
					
					if($this->input->post('state')!='')
					{
						$state=$this->input->post('state');
					}
					
					
					
					$this->form_validation->set_rules('cpincode','Pincode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
					/*--------------------end code by Pooja Godse-----------------*/
					//do not keep last name field compulsory - 21-01-2017
					//$this->form_validation->set_rules('lastname','Last Name','trim|required');
					$this->form_validation->set_rules('addressline1','Address Line1','trim');
					$this->form_validation->set_rules('addressline2','Address Line2','trim');
					$this->form_validation->set_rules('addressline3','Address Line3','trim');
					$this->form_validation->set_rules('addressline4','Address Line4','trim');
					
					
					//START : CODE ADDED BY SAGER ON 15-12-2020 FOR SERVER SIDE VALIDATION FOR DATE
					$chk_date_flag = 0;
					$current_date = date('Y-m-d', strtotime($old[0]['created_on']));
					$date_check = date('Y-m-d', strtotime("+2days", strtotime($current_date)));
					if($date_check >= $this->input->post('batch_from_date') || $date_check >= $this->input->post('batch_to_date'))
					{
						$chk_date_flag = 1;
						$data['error_msg'] = 'Invalid date selection';					
					}
					//END : CODE ADDED BY SAGER ON 15-12-2020 FOR SERVER SIDE VALIDATION FOR DATE
					
					if($this->form_validation->run() == TRUE && $chk_date_flag == 0)
					{
						$login_agency=$this->session->userdata('dra_institute');
						$agency_id=$login_agency['dra_inst_registration_id'];
						
						
						$batch_type = $this->input->post('batch_type');
						$center_id = $this->input->post('center_id');
						// $inspector_id = $this->input->post('inspector_id');
						$batch_name = $this->input->post('batch_name');
						$batch_from_date = $this->input->post('batch_from_date');
						$batch_to_date = $this->input->post('batch_to_date');
						$timing_from = $this->input->post('timing_from');
						$timing_to = $this->input->post('timing_to');
						$training_medium = $this->input->post('training_medium');
						$total_candidates = $this->input->post('total_candidates');
						$faculty_name = $this->input->post('faculty_name');
						$faculty_qualification = $this->input->post('faculty_qualification');
						// new added by Manoj 
						$faculty_name2 = $this->input->post('faculty_name2');
						$faculty_qualification2 = $this->input->post('faculty_qualification2');
						$contact_person_name = $this->input->post('contact_person_name');
						$contact_person_phone = $this->input->post('contact_person_phone');
						$name_of_bank = $this->input->post('name_of_bank');
						$addressline1 = $this->input->post('addressline1');
						$addressline2 = $this->input->post('addressline2');
						$addressline3 = $this->input->post('addressline3');
						$addressline4 = $this->input->post('addressline4');
						$remarks 	  = $this->input->post('remarks');
						
						$pincode=$this->input->post('cpincode');
						
						if($batch_type == 'C'){
							$hours=100;
						}
						else {
							$hours = $this->input->post('hours');
						}
						
						########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
						if($batch_online_offline_flag == 1) 
						{ 
							$online_training_platform = $this->input->post('online_training_platform'); 
							$batch_online_user_ids = $this->input->post('batch_online_user_ids');
							
							if(count($batch_online_user_ids) > 0)
							{
								$online_user_login_ids = $this->input->post('batch_online_login_ids');
								$online_user_password = $this->input->post('batch_online_login_pass');
								
								$i=0;
								foreach($batch_online_user_ids as $res)
								{	
									if($res == "") { $user_mode = 'add'; }
									else
									{
										if($this->master_model->getRecordCount("agency_online_batch_user_details",array('agency_id'=>$agency_id, 'batch_id'=>$batchId)) == 0) { $user_mode = 'add'; }
										else { $user_mode = 'update'; }
									}
									
									$add_user_data['agency_id'] = $agency_id;
									$add_user_data['batch_id'] = $batch_id;
									$add_user_data['login_id'] = $online_user_login_ids[$i];
									$add_user_data['password'] = base64_encode($online_user_password[$i]);
									
									if($user_mode == "add")
									{
										$add_user_data['created_on'] = date('Y-m-d H:i:s');
										$this->master_model->insertRecord('agency_online_batch_user_details',$add_user_data);
									}
									else
									{
										$add_user_data['updated_on'] = date('Y-m-d H:i:s');
										$this->master_model->updateRecord('agency_online_batch_user_details',$add_user_data,array("user_id"=>$batch_online_user_ids[$i]));
									}										
									$i++;
								}
							}
						}
						else 
						{ 
							$online_training_platform = "";							
							$this->db->where('agency_id', $agency_id);
							$this->db->where('batch_id', $batchId);
							$this->db->delete('agency_online_batch_user_details');
						}
						########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
						
						$data=array(
						'batch_type' => $batch_type,
						'hours' => $hours,
						'center_id' => $center_id,
						// 'inspector_id' => $inspector_id,
						'pincode' => $pincode,
						'batch_name' => $batch_name,
						'batch_from_date' => $batch_from_date,
						'batch_to_date' => $batch_to_date,
						'timing_from' => $timing_from,
						'timing_to' => $timing_to,
						'training_medium' => $training_medium,
						'total_candidates' => $total_candidates,
						'faculty_name' => $faculty_name,
						'faculty_qualification' => $faculty_qualification,
						'faculty_name2' => $faculty_name2,
						'faculty_qualification2' => $faculty_qualification2,
						'contact_person_name' => $contact_person_name,
						'contact_person_phone' => $contact_person_phone,
						'name_of_bank' => $name_of_bank,
						'addressline1' => $addressline1,
						'addressline2' => $addressline2,
						'addressline3' => $addressline3,
						'addressline4' => $addressline4,
						'remarks' 	   => $remarks,					
						'batch_online_offline_flag' => $batch_online_offline_flag,					
						'online_training_platform' => $online_training_platform,					
						'batch_status' => 'IR',
						'updated_on' => date('Y-m-d H:i:s')
						);
						
						if( $this->master_model->updateRecord('agency_batch',$data,array('id'=>$batchId)) ) {
							$desc['updated_data'] = $data;
							$desc['old_data'] = $old[0];
							log_dra_user($log_title = "Edit Agency Batch Successfully", $log_message = serialize($desc));
							$this->session->set_flashdata('success','Record updated successfully');
							redirect(base_url().'iibfdra/TrainingBatches/');
							} else {
							$desc['updated_data'] = $data;
							$desc['old_data'] = $old[0];
							log_dra_user($log_title = "Edit Agency Batch Unsuccessfully", $log_message = serialize($data));
							$this->session->set_flashdata('error','Error occured while adding record');
							redirect(base_url().'iibfdra/TrainingBatches/add_batches');
						}
						
					}//check validations
					else {
						$data['validation_errors'] = validation_errors();
						
					}
				}
				
				$this->db->select('agency_batch.*,agency_center.location_name,agency_center.state,agency_center.district,agency_center.city,agency_center.pincode,agency_inspector_master.inspector_name,agency_batch_medium_master.medium_code,agency_batch_medium_master.medium_description,state_master.state_name,city_master.city_name');
				$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','left');
				$this->db->join('agency_batch_medium_master','agency_batch.training_medium=agency_batch_medium_master.medium_code','left');
				$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_batch.inspector_id','left');
				$this->db->join('state_master','agency_center.state=state_master.state_code','left');
				$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');	
				$batchDetails = $this->master_model->getRecords('agency_batch',array('agency_batch.id'=>$batchId));
				
				
				$this->db->select('agency_center.*');
				$this->db->where('agency_center.agency_id',$agency_id);
				$this->db->where('agency_center.institute_code',$institute_code);
				$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	      
				
				$centers = $this->db->get('agency_center');
				//print_r($query->result_array()); die;
				/*-----------------Code by pooja godse---------------*/
				$this->db->select('agency_center.*,city_master.city_name');
				$this->db->group_by('agency_center.center_id');
				$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');	
				$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list		
				$agency_center = $this->master_model->getRecords('agency_center',array('institute_code'=>$_SESSION['dra_institute']['institute_code']),'',array('center_status'=>'ASC'));
				
				if(count($agency_center) > 0){
					foreach ($agency_center as $agency_center) {
						
						$where = "FIND_IN_SET('".$agency_center['center_id']."', centers_id)";  
						
						$this->db->where($where);
						$this->db->where_in('centers_id',$agency_center['center_id']);
						$this->db->order_by('agency_renew_id',"desc");
						$this->db->limit(1);
						$agency_center_renew = $this->db->get('agency_center_renew');
						$agency_center_renew =$agency_center_renew->result_array();
						if(count($agency_center_renew) > 0){
							
							$agency_center['renew_pay_status'] = $agency_center_renew[0]['pay_status'];
							$agency_center['renew_type'] = $agency_center_renew[0]['renew_type'];
							$resultarr[] = $agency_center;
						}
						else{
							$agency_center['renew_pay_status'] = '';
							$agency_center['renew_type'] = "";
							$resultarr[] = $agency_center;
						}
					}
				}
				
				
				$this->db->select('dra_inst_registration.*,state_master.state_name');
				$this->db->join('state_master','dra_inst_registration.main_state=state_master.state_code','LEFT'); //by manoj 
				$this->db->where('dra_inst_registration.id',$_SESSION['dra_institute']['dra_inst_registration_id']);
				$inst_registration_info = $this->master_model->getRecords('dra_inst_registration');
				
				$data['inst_registration_info'] = $inst_registration_info;
				$data['agency_center'] = $resultarr;
				
				
				/*-----------------end Code by pooja godse---------------*/
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				
				$this->db->where('city_master.city_delete', '0');
				$this->db->where('city_master.id',$batchDetails[0]['city']);
				$cities = $this->master_model->getRecords('city_master');
				
				$this->db->where('state_master.state_delete', '0');
				$states = $this->master_model->getRecords('state_master');
				
				$medium_master = $this->master_model->getRecords('agency_batch_medium_master');
				
				$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
				$center_master = $this->master_model->getRecords('dra_center_master',array(),'',array('center_name'=>'ASC'));
				
				$this->load->helper('captcha');
				$this->session->unset_userdata("draexamcaptcha");
				$this->session->set_userdata("draexamcaptcha", rand(1, 100000));
				$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => '../../../uploads/applications/'
				);
				$cap = create_captcha($vals);
				$_SESSION["draexamcaptcha"] = $cap['word']; 
				$data['states'] = $states;
				$data['cities'] = $cities;
				$data['image'] = $cap['image'];
				$data['medium_master'] = $medium_master;
				$data['center_master'] = $centers->result_array();
				$data["active_exams"] = $res;
				$data['middle_content']	= 'drabatch_edit';
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$data['active_exams'] = $res;
				$data['batchDetails'] = $batchDetails;
				
				########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################
				$online_batch_user_details = array();
				if(isset($batchDetails[0]['batch_online_offline_flag']) && $batchDetails[0]['batch_online_offline_flag'] != "")
				{
					if($batchDetails[0]['batch_online_offline_flag'] == 1)
					{
						$this->db->where('agency_id', $agency_id);
						$this->db->where('batch_id', $batchId);
						$online_batch_user_details = $this->master_model->getRecords('agency_online_batch_user_details');
					}
				}
				$data['online_batch_user_details'] = $online_batch_user_details;
				########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################
				
				$this->load->view('iibfdra/common_view',$data);
			}
			else {
				echo "Opess..Error!!";
			}
		}
		
		//add applicants against batch
		public function addApplication() {
			
			if($this->uri->segment(4) ) {
				$batchid = trim($this->uri->segment(4) ); 
				$batch_id = base64_decode($batchid);
				$batchId = intval($batch_id);
				$data['bid']=$batchId;
				//echo $batchId; die;
				/* form submit logic */
				if(isset($_POST['btnSubmit'])) { //print_r($_POST); exit;
					//echo $this->input->post('state'); die;
					$regno = $this->input->post('reg_no');
					$membertype = $this->input->post('membertype');
					$flag = 1; $message = '';
					//if( empty( $regno ) ) { 
					//Keep files required in case of re-attempt also - 23-01-2017
					
					$is_images_exists = TRUE;
					
					// validate images for new member registartion, Added by Bhagwan Sahane on 26-04-2017 -
					if( empty( $regno ) || ( !empty( $regno ) && $membertype == 'normal_member' ))
					{
						// $this->form_validation->set_rules('drascannedphoto','Photograph of Candidate','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_drascannedphoto_upload');
						
						// $this->form_validation->set_rules('drascannedsignature','Signature of Candidate','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_drascannedsignature_upload');
						
						// $this->form_validation->set_rules('draidproofphoto','Proof of Identity','file_required|file_allowed_type[jpg,jpeg]|file_size_max[25]|callback_draidproofphoto_upload');
						
						// $this->form_validation->set_rules('qualicertificate','Qualification Certificate','file_required|file_allowed_type[jpg,jpeg]|file_size_min[50]|file_size_max[100]|callback_qualicertificate_upload');
						
						// $this->form_validation->set_rules('trainingcertificate','Training Certificate','file_required|file_allowed_type[jpg,jpeg]|file_size_min[50]|file_size_max[100]|callback_trainingcertificate_upload');
					}
					
					else	//check if images exists in case of re-attempt, Added by Bhagwan Sahane on 26-04-2017
					{
						// get dra members image path -
						$memRes = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
						$memRes = $memRes[0];
						
						// check for state, qualification & idproof in re-attempt (get details) case -
						$_POST['state'] = $memRes['state'];
						$_POST['edu_quali'] = $memRes['qualification'];
						$_POST['idproof'] = $memRes['idproof'];
						// eof code
						
						$scannedphoto = '';
						$scannedsignaturephoto = '';
						$idproofphoto = '';
						$quali_certificate = '';
						$training_certificate = '';
						
						$old_image_path = 'uploads'.$memRes['image_path'];
						
						$new_image_path = 'uploads/iibfdra/';
						
						if($memRes['scannedphoto'] == '')
						{
							if(file_exists($old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg'))
							{
								$scannedphoto = base_url().$old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['scannedphoto']))
							{
								$scannedphoto = base_url().$new_image_path . $memRes['scannedphoto'];
							}
						}
						
						if($memRes['scannedsignaturephoto'] == '')
						{
							if(file_exists($old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg'))
							{
								$scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg';	
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['scannedsignaturephoto']))
							{
								$scannedsignaturephoto = base_url().$new_image_path . $memRes['scannedsignaturephoto'];
							}
						}
						
						if($memRes['idproofphoto'] == '')
						{
							if(file_exists($old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg'))
							{
								$idproofphoto = base_url().$old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['idproofphoto']))
							{
								$idproofphoto = base_url().$new_image_path . $memRes['idproofphoto'];	
							}
						}
						
						if($memRes['quali_certificate'] == '')
						{
							if(file_exists($old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg'))
							{
								$quali_certificate = base_url().$old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['quali_certificate']))
							{
								$quali_certificate = base_url().$new_image_path . $memRes['quali_certificate'];
							}
						}
						
						
						/*
							// This field to removed by manoj as per discussion with sonal on 4 Apr 2019
							if($memRes['training_certificate'] == '')
							{
							if(file_exists($old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg'))
							{
							$training_certificate = base_url().$old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg';
							}
							}
							else
							{
							if(file_exists($new_image_path . $memRes['training_certificate']))
							{
							$training_certificate = base_url().$new_image_path . $memRes['training_certificate'];
							}
						}*/
						
						
						
						// check if images missing
						// Removed : || $training_certificate == ''
						if($scannedphoto == '' || $scannedsignaturephoto == '' || $idproofphoto == '' || $quali_certificate == '' )
						{
							// image(s) not available
							$is_images_exists = FALSE;
							
							//$this->session->set_flashdata('error','Images are missing in your profile, kindly apply again with new application.');	
						}
					}
					//eof code
					$this->form_validation->set_rules('firstname','First Name','trim|required');
					$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
					$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
					//do not keep last name field compulsory - 21-01-2017
					//$this->form_validation->set_rules('lastname','Last Name','trim|required');
					$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[50]');
					$this->form_validation->set_rules('addressline2','Address Line2','trim|max_length[50]');
					$this->form_validation->set_rules('city','City','trim|required|max_length[30]');
					$this->form_validation->set_rules('district','District','trim|required|max_length[30]');
					
					$this->form_validation->set_rules('state','State','trim');
					
					$this->form_validation->set_rules('pincode','Pin Code','trim|required|max_length[6]');
					$this->form_validation->set_rules('dob1','Date of Birth','trim|required');
					$this->form_validation->set_rules('gender','Gender','required');
					$this->form_validation->set_rules('mobile','Mobile No.','required|max_length[10]|min_length[10]');
					$this->form_validation->set_rules('email','Email','valid_email|required|trim|max_length[80]');
					
					
					$this->form_validation->set_rules('edu_quali','Qualification');
					
					$this->form_validation->set_rules('idproof','Id Proof');
					
					$this->form_validation->set_rules('declaration1','Declaration','required');
					// $this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapply');
					$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
					$this->form_validation->set_rules('phone','Phone No','max_length[8]');
					
					$outputphoto1 = $outputsign1 = $outputidproof1 = $outputtcertificate1 = $outputqualicertificate1 = '';
					$photofnm = $signfnm = $idfnm = $trgfnm = $qualifnm = '';
					$photo_flg = $signature_flg = $id_flg = $tcertificate_flg = $qualicertificate_flg = 'N';
					
					$is_images_exists = TRUE;
					
					if($this->form_validation->run() == TRUE && $is_images_exists) 
					{
						$date = date('Y-m-d h:i:s');
						
						$image_size_error = 0;
						$image_size_error_message = array();
						
						//if( !empty($input) ) {
						if($this->input->post('hiddenphoto') != '')
						{
							$size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
							if($size)
							{
								$input = $this->input->post('hiddenphoto');
								
								$tmp_nm = strtotime($date).rand(0,100);
								$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
								$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
								file_put_contents($outputphoto, file_get_contents($input));
								$photofnm = "p_".$tmp_nm.".jpg";
								$photo_flg = 'Y';
							}
							else
							{
								$image_size_error = 1;
								$image_size_error_message[] = 'Invalid photo file uploaded.';	
							}
						}
						
						// generate dynamic scan signature
						
						//if( !empty($inputsignature) ) {
						if($this->input->post('hiddenscansignature') != '')
						{
							$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
							if($size)
							{
								$inputsignature = $_POST["hiddenscansignature"];
								
								$tmp_signnm = strtotime($date).rand(0,100);
								$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
								$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
								file_put_contents($outputsign, file_get_contents($inputsignature));
								$signfnm = "s_".$tmp_signnm.".jpg";
								$signature_flg = 'Y';
							}
							else
							{
								$image_size_error = 1;
								$image_size_error_message[] = 'Invalid signature file uploaded.';
							}
						}
						
						// generate dynamic id proof
						
						//if( !empty($inputidproofphoto) ) {
						if($this->input->post('hiddenidproofphoto') != '')
						{
							$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
							if($size)
							{
								$inputidproofphoto = $_POST["hiddenidproofphoto"];
								
								$tmp_inputidproof = strtotime($date).rand(0,100);
								$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
								$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
								file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
								$idfnm = "pr_".$tmp_inputidproof.".jpg";
								$id_flg = 'Y';
							}
							else
							{
								$image_size_error = 1;
								$image_size_error_message[] = 'Invalid id proof file uploaded.';
							}
						}
						
						// generate dynamic training certificate 
						
						//if( !empty($input_tcertificatephoto) ) {
						
						/*	
							// This field to removed by manoj as per discussion with sonal on 4 Apr 2019
							if($this->input->post('hiddentrainingcertificate') != '')
							{
							$size = @getimagesize($_FILES['trainingcertificate']['tmp_name']);
							if($size)
							{
							$input_tcertificatephoto = $_POST["hiddentrainingcertificate"];
							
							$tmp_tcertificate = strtotime($date).rand(0,100);
							$outputtcertificate = getcwd()."/uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
							$outputtcertificate1 = base_url()."uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
							file_put_contents($outputtcertificate, file_get_contents($input_tcertificatephoto));
							$trgfnm = "traing_".$tmp_tcertificate.".jpg";
							$tcertificate_flg = 'Y';
							}
							else
							{
							$image_size_error = 1;	
							$image_size_error_message[] = 'Invalid training certificate file uploaded.';
							}
						}*/
						
						
						// generate dynamic qualification certificate
						
						//if( !empty($input_qualicertificate) ) {
						if($this->input->post('hiddenqualicertificate') != '')
						{
							$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
							if($size)
							{
								$input_qualicertificate = $_POST["hiddenqualicertificate"];
								
								$tmp_qualicertificate = strtotime($date).rand(0,100);
								$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
								$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
								file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
								$qualifnm = "degre_".$tmp_qualicertificate.".jpg";
								$qualicertificate_flg = 'Y';
							}
							else
							{
								$image_size_error = 1;
								$image_size_error_message[] = 'Invalid qualification certificate file uploaded.';	
							}
						}
						// eof file upload code
						
						// check if invalid image error
						if($image_size_error == 1)
						{
							$this->session->set_flashdata('error',implode('<br>', $image_size_error_message));
							redirect(base_url().'iibfdra/DraExam/add/?exCd='.base64_encode($examcd));
						}
						//eof code
						
						$exam_fees = 0;
						$exam_period = $exam_date = $exam_time = '';
						
						$instdata = $this->session->userdata('dra_institute');
						$drainstdata = $this->session->userdata('dra_institute');
						if( $drainstdata ) {
							$institute_name = $drainstdata['institute_name'];	
							$institute_code = $drainstdata['institute_code'];
						}
						//new candidate
						$regno = $this->input->post('reg_no');
						$membertype = $this->input->post('membertype');
						
						if( empty( $regno ) || ( !empty( $regno ) && $membertype == 'normal_member' ) ) 
						{
							//insert record for ordinary member / non-member and new candidate in dra_members
							if(empty( $regno )){
								$registrationtype = 'NM';
								}else{
								$registrationtype = $this->input->post('memtype');
							}
							// if(empty( $regno )){
							//echo $this->input->post('addressline1'); die;
							
							// 'training_certificate' => $trgfnm,
							
							$insert_data = array(	
							'namesub'		=> $this->input->post('sel_namesub'),
							'batch_id'		=>$batchId,
							'IsNew'		    =>1,
							'firstname'		=> $this->input->post('firstname'),
							'middlename'	=> $this->input->post('middlename'),
							'lastname'		=> $this->input->post('lastname'),
							'address1'		=> $this->input->post('addressline1'),
							'address2'		=> $this->input->post('addressline2'),
							'address3'		=> $this->input->post('addressline3'),
							'address4'		=> $this->input->post('addressline4'),
							'city'			=> $this->input->post('city'),
							'state'			=> $this->input->post('state'),
							'district'		=> $this->input->post('district'),
							'pincode'		=> $this->input->post('pincode'),
							'dateofbirth'	=> $this->input->post('dob1'),
							'gender'		=> $this->input->post('gender'),
							'stdcode'		=> $this->input->post('stdcode'),
							'phone'			=> $this->input->post('phone'),
							'mobile'		=> $this->input->post('mobile'),
							'inst_code'		=> $institute_code,
							'associatedinstitute'		=> $institute_name,
							'email' 		=> $this->input->post('email'),
							'qualification'	=> $this->input->post('edu_quali'),
							'idproof'		=> $this->input->post('idproof'),
							'registrationtype' => $registrationtype,//'NM',
							'aadhar_no' 	=> $this->input->post('aadhar_no'), // added by Bhagwan Sahane, on 06-05-2017
							'createdon' 	=> date('Y-m-d H:i:s'),
							'scannedphoto' 	=> $photofnm,
							'scannedsignaturephoto' => $signfnm,
							'idproofphoto' 	=> $idfnm,
							'quali_certificate' => $qualifnm,
							'photo_flg' 	=> $photo_flg,
							'signature_flg' => $signature_flg,
							'id_flg' 		=> $id_flg,
							'tcertificate_flg' => $tcertificate_flg,
							'qualicertificate_flg' => $qualicertificate_flg,
							'entered_regnumber' => $regno 
							);
							// }else{
							
							//                                 $memRes = $this->master_model->getRecords('member_registration',array('member_registration.regnumber'=>$regno));
							
							
							// 	if( count( $memRes ) > 0 ) {
							
							// 		$memRes = $memRes[0];
							
							// 		$insert_data = array(	
							// 		'namesub'		=> $memRes['namesub'],
							// 		'batch_id'		=> $batchId,
							// 		'firstname'		=> $memRes['firstname'],
							// 		'middlename'	=> $memRes['middlename'],
							// 		'lastname'		=> $memRes['lastname'],
							// 		'address1'		=> $memRes['address1'],
							// 		'address2'		=> $memRes['address2'],
							// 		'address3'		=> $memRes['address3'],
							// 		'address4'		=> $memRes['address4'],
							// 		'city'			=> $memRes['city'],
							// 		'state'			=> $memRes['state'],
							// 		'district'		=> $memRes['district'],
							// 		'pincode'		=> $memRes['pincode'],
							// 		'dateofbirth'	=> $memRes['dateofbirth'],
							// 		'gender'		=> $memRes['gender'],
							// 		'stdcode'		=> $memRes['stdcode'],
							// 		'phone'			=> $memRes['office_phone'],
							// 		'mobile'		=> $memRes['mobile'],
							// 		'inst_code'		=> $institute_code,
							// 		'associatedinstitute'		=> $institute_name,
							// 		'email' 		=> $memRes['email'],
							// 		'qualification'	=> $memRes['qualification'],
							// 		'idproof'		=> $memRes['idproof'],
							// 		'registrationtype' => 'O',
							// 		'aadhar_no' 	=> $this->input->post('aadhar_no'), // added by Bhagwan Sahane, on 06-05-2017
							// 		'createdon' 	=> date('Y-m-d H:i:s'),
							// 		'scannedphoto' 	=> $photofnm,
							// 		'scannedsignaturephoto' => $signfnm,
							// 		'idproofphoto' 	=> $idfnm,
							// 		'training_certificate' => $trgfnm,
							// 		'quali_certificate' => $qualifnm,
							// 		'photo_flg' 	=> $photo_flg,
							// 		'signature_flg' => $signature_flg,
							// 		'id_flg' 		=> $id_flg,
							// 		'tcertificate_flg' => $tcertificate_flg,
							// 		'qualicertificate_flg' => $qualicertificate_flg 
							// 	  );
							
							// 	}
							// }
							
							//echo "<pre>"; print_r($insert_data); exit;
							if($this->master_model->insertRecord('dra_members',$insert_data)) {
								
								$regid = $this->db->insert_id();
								
								$history =array(
								'regid' => $regid,
								'batch_id'		=>$batchId,
								'agency_id'		=> $institute_code,
								
								);
								$this->master_model->insertRecord('agency_member_batch_history',$history);
								
								
								log_dra_user($log_title = "Add DRA Member Successful", $log_message = serialize($insert_data));
								$this->session->set_flashdata('success','Record added successfully.');
								redirect(base_url().'iibfdra/TrainingBatches/allcandidates');
							}
							else {
								log_dra_user($log_title = "Add New Applicant Unsuccessful", $log_message = serialize($insert_data));
								$this->session->set_flashdata('error','Error occured while adding record');
								redirect(base_url().'iibfdra/TrainingBatches');
							}
							
						}
						elseif( !empty( $regno ) && $membertype == 'dra_member')
						{
							
							$membertype = $this->input->post('membertype');
							$registrationtype = $this->input->post('memtype');
							
							$updatecandinfoarr = array(
							'batch_id'		=>$batchId,
							'editedon' => date('Y-m-d H:i:s'),
							're_attempt' => 0,
							);
							
							
							if($this->master_model->updateRecord('dra_members',$updatecandinfoarr,  array('regnumber'=>$regno))) {
								$member_details = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
								$member=$member_details[0];
								$history =array(
								'regid' => $member['regid'],
								'batch_id'		=>$batchId,
								'agency_id'		=> $institute_code,
								
								);
								$this->master_model->insertRecord('agency_member_batch_history',$history);
								
								$regid = $regno;
								$update_data = array(
								'regid' => $regid,
								'batch_id'		=>$batchId,
								'editedon' => date('Y-m-d H:i:s'),
								're_attempt' => 0,
								);
								
								log_dra_user($log_title = "Update DRA Member Successful", $log_message = serialize($update_data));
								$this->session->set_flashdata('success','Record updated successfully.');
								redirect(base_url().'iibfdra/TrainingBatches/allcandidates');
							}
							else 
							{
								log_dra_user($log_title = "Update DRA Member Unsuccessful", $log_message = serialize($update_data));
								$this->session->set_flashdata('error','Error occured while updating record');
								redirect(base_url().'iibfdra/TrainingBatches');
							}
							
						}						
						else
						{
							
							$membertype = $this->input->post('membertype');
							$registrationtype = $this->input->post('memtype');
							
							$updatecandinfoarr = array(
							'batch_id'		=>$batchId,
							'editedon' => date('Y-m-d H:i:s'),
							//'re_attempt' => 1,
							);
							
							
							if($this->master_model->updateRecord('dra_members',$updatecandinfoarr,  array('regnumber'=>$regno))) {
								$member_details = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
								$member=$member_details[0];
								$history =array(
								'regid' => $member['regid'],
								'batch_id'		=>$batchId,
								'agency_id'		=> $institute_code,
								
								);
								$this->master_model->insertRecord('agency_member_batch_history',$history);
								
								$regid = $regno;
								$update_data = array(
								'regid' => $regid,
								'batch_id'		=>$batchId,
								'editedon' => date('Y-m-d H:i:s'),
								//'re_attempt' => 1,
								);
								
								log_dra_user($log_title = "Update DRA Member Successful", $log_message = serialize($update_data));
								$this->session->set_flashdata('success','Record updated successfully.');
								redirect(base_url().'iibfdra/TrainingBatches/allcandidates');
							}
							else {
								log_dra_user($log_title = "Update DRA Member Unsuccessful", $log_message = serialize($update_data));
								$this->session->set_flashdata('error','Error occured while updating record');
								redirect(base_url().'iibfdra/TrainingBatches');
							}
						}	
					}
					else {
						$data['validation_errors'] = validation_errors();
						/* Get required data for DRA exam */
						$this->db->where('state_master.state_delete', '0');
						$states = $this->master_model->getRecords('state_master');
						//$states = $this->master_model->getRecords('state_master');
						
						//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
						$this->db->not_like('name','Election Voters card');
						$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
						
						$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
						$medium_master = $this->master_model->getRecords('dra_medium_master');
						
						$this->db->select('agency_batch.*, agency_center.*');
						$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
						$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
						$this->db->where('agency_batch.id',$batchId);
						$batch_details = $this->master_model->getRecords('agency_batch');
						
						$this->load->helper('captcha');
						$this->session->unset_userdata("draexamcaptcha");
						$this->session->set_userdata("draexamcaptcha", rand(1, 100000));
						$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => '../../../uploads/applications/'
						);
						$cap = create_captcha($vals);
						$_SESSION["draexamcaptcha"] = $cap['word']; 
						$data['states'] = $states;
						$data['image'] = $cap['image'];
						$data['idtype_master'] = $idtype_master;
						$data['medium_master'] = $medium_master;
						$data['batch_details'] = $batch_details;
						$data['data_examcode'] = '';
						$data["middle_content"] = 'draapplication_add';
						//get exam date and training period limit from subject master and misc master
						$examdt = '';
						$traininglimit = '';
						$data["examdt"] = $examdt;
						$data["traininglimit"] = $traininglimit;
						/* send active exams for display in sidebar */
						$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
						$res = $this->master_model->getRecords("dra_exam_master a");
						$data['active_exams'] = $res;
						$this->load->view('iibfdra/common_view',$data);
					}
					
					
				}
				$this->db->where('city_master.city_delete', '0');
				$cities = $this->master_model->getRecords('city_master');
				/* Get required data for DRA exam */
				$this->db->where('state_master.state_delete', '0');
				$states = $this->master_model->getRecords('state_master');
				
				//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
				$this->db->not_like('name','Election Voters card');
				$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
				//get Medium
				$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
				$medium_master = $this->master_model->getRecords('dra_medium_master');
				
				$this->db->select('agency_batch.*, agency_center.*, city_master.*');
				$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
				$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
				$this->db->where('agency_batch.id',$batchId);
				$batch_details = $this->master_model->getRecords('agency_batch');
				
				$this->load->helper('captcha');
				$this->session->unset_userdata("draexamcaptcha");
				$this->session->set_userdata("draexamcaptcha", rand(1, 100000));
				$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => '../../../uploads/applications/'
				);
				$cap = create_captcha($vals);
				$_SESSION["draexamcaptcha"] = $cap['word']; 
				$data['cities'] = $cities;
				$data['states'] = $states;
				$data['image'] = $cap['image'];
				$data['idtype_master'] = $idtype_master;
				$data['medium_master'] = $medium_master;
				$data['batch_details'] = $batch_details;
				$data['data_examcode'] = '';
				$data["middle_content"] = 'draapplication_add';
				//get exam date and training period limit from subject master and misc master
				$examdt = '';
				$traininglimit = '';
				$data["examdt"] = $examdt;
				$data["traininglimit"] = $traininglimit;
				/* send active exams for display in sidebar */
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$data['active_exams'] = $res;
				$this->load->view('iibfdra/common_view',$data);
				
				
			}
			
			
		}
		
		//get all candidates against batch
		public function allcandidates()
		{			
			/* $login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
			$instcode = $login_agency['institute_code']; */
			
			/* $this->db->select('agency_batch.*,dra_members.*,agency_center.location_name,city_master.city_name');
				$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
				//$this->db->join('dra_member_exam','dra_member_exam.regid=dra_members.regid ','left');
				$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','left');
				$this->db->join('city_master','city_master.id=agency_center.location_name','left');
				
			$batchDetails = $this->master_model->getRecords('dra_members',array('agency_batch.agency_id'=>$agency_id,'dra_members.inst_code'=>$instcode, 'isdeleted'=>0),'',array('dra_members.regid'=>'DESC')); */
			//echo $this->db->last_query(); //exit;
			
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			//$data['result'] = $batchDetails;
			
			$data['middle_content']	= 'drabatch_candidates';
			$this->load->view('iibfdra/common_view',$data);
		}
		
		public function get_candidates_data_ajax()
		{
			$login_agency=$this->session->userdata('dra_institute');
			$agency_id=$login_agency['dra_inst_registration_id'];
			$instcode = $login_agency['institute_code'];
			
			$table = 'dra_members m';
			$column_order = array('m.regid', 'cm.city_name', 'batch_code', 'ab.batch_name', 'm.regnumber', 'REPLACE(CONCAT(TRIM(m.firstname), " ", TRIM(m.middlename), " ", TRIM(m.lastname)), "  "," ") AS DispName', 'm.dateofbirth', 'm.email', 'ab.batch_from_date', 'ab.batch_to_date'); //SET COLUMNS FOR SORT 			
			
			$column_search = array('cm.city_name', 'batch_code', 'ab.batch_name', 'm.regnumber', 'REPLACE(CONCAT(TRIM(m.firstname), " ", TRIM(m.middlename), " ", TRIM(m.lastname)), "  "," ")', 'm.dateofbirth', 'm.email', 'ab.batch_from_date', 'ab.batch_to_date'); //SET COLUMN FOR SEARCH			
			$order_by = "ORDER BY m.regid DESC"; //;array('b.created_on' => 'DESC', 'b.updated_on' => 'DESC'); // DEFAULT ORDER
			
			$WhereForTotal 	= "WHERE ab.agency_id = '".$agency_id."' AND m.inst_code = '".$instcode."' AND m.isdeleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS
			$Where = "WHERE ab.agency_id = '".$agency_id."' AND m.inst_code = '".$instcode."' AND m.isdeleted = 0 "; //DEFAULT WHERE CONDITION 
			if($_POST['search']['value']) // DATATABLE SEARCH
			{
				$Where .= " AND (";
				for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( $this->custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
				$Where = substr_replace( $Where, "", -3 );
				$Where .= ')';
			}			
			
			$Order = ""; //DATATABLE SORT
			if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
			else if(isset($order_by)) 
			{ 
				$Order = $order_by; 
			}
			
			$Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
			
			$join_qry = " LEFT JOIN agency_batch ab ON ab.id = m.batch_id";
			$join_qry .= " LEFT JOIN agency_center ac ON ac.center_id = ab.center_id";
			$join_qry .= " LEFT JOIN city_master cm ON cm.id = ac.location_name";
			
			$print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
			$Result = $this->db->query($print_query);  
			$Rows = $Result->result_array();
			
			$TotalResult = $this->numRow($column_order[0],$table." ".$join_qry,$WhereForTotal);
			$FilteredResult = $this->numRow($column_order[0],$table." ".$join_qry,$Where);
			
			$data = array();
			$no = $_POST['start'];
			
			foreach ($Rows as $Res) 
			{
				$training_from = $Res['batch_from_date'];
				$training_to =$Res['batch_to_date'] ;
				
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $Res['city_name'];
				$row[] = $Res['batch_code'];
				$row[] = $Res['batch_name'];
				$row[] = $Res['regnumber'];
				$row[] = $Res['DispName'];
				$row[] = date("d-M-Y", strtotime($Res['dateofbirth']));
				$row[] = $Res['email'];
				
				$action_str = '';
				if($training_from <= date('Y-m-d') && $training_to >=  date('Y-m-d'))
				{
					$onclick_fun = "confirm('Are you confirm to delete this record?');";
					$action_str .= '<a href="'.base_url().'iibfdra/TrainingBatches/editCandidate/'.$Res['regid'].'">Edit | <a onclick="return '.$onclick_fun.'" href="'.base_url().'iibfdra/TrainingBatches/deleteCandidate/'.$Res['regid'].'">Delete | ';
				}
				
				$action_str .= '<a href="'.base_url().'iibfdra/TrainingBatches/viewApplicant/'.$Res['regid'].'">View';				
				$row[] = $action_str;
				$data[] = $row; 
			}      
			
			$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $TotalResult, //All result count
			"recordsFiltered" => $FilteredResult, //Disp result count
			"Query" => $print_query,
			"data" => $data,
			);
			//output to json format
			echo json_encode($output);
		}
		
		function numRow($select,$table,$where,$order_by=null) // GET NUMBER OF ROWS
		{
			$q = "select $select from $table $where $order_by";
			$query=$this->db->query($q);
			return $query->num_rows();
		}
		
		function custom_safe_string($str="")
		{
			$str = str_replace('"',"&quot;",$str);
			$str = str_replace("'","&apos;",$str);
			return $str;
		}
		
		//get all candidates against exam
		public function allapplicants() 
		{
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1"); 
			
			$s_batch_code = '';
			$data['dispLimit'] = $dispLimit = 2000;
			
			if(isset($_GET['sBtCd'])&& $_GET['sBtCd'] != "") { $s_batch_code = $_GET['sBtCd']; }
			
			if(isset( $_GET['exCd'])) 
			{	
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$examcode = trim( $_GET['exCd'] );
				$decdexamcode = base64_decode($examcode);
				if(!intval($decdexamcode))
				{
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				
				$decdexamcode = intval($decdexamcode);
				//check if exam exists or not
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));				
				if( $examcount > 0 )
				{
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
					//if(count($examact) > 0) 
					{
						//$comp_currdate = date('Y-m-d H:i:s');
						//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
						//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
						
						$exam_period =  $examact[0]['exam_period'];
						$comp_currdate = date('Y-m-d');
						$comp_frmdate = $examact[0]['exam_from_date'];
						$comp_todate = $examact[0]['exam_to_date'];
						//if(strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate))
						{
							$data['middle_content']	= 'draexam_candidates_sm';							
							$data['examperiods']	= $examact[0]['exam_period'];
							$data['examcode']	= $decdexamcode;
							$data['s_batch_code']	= $s_batch_code;
							
							$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
							$data['active_exams'] = $res = $this->master_model->getRecords("dra_exam_master a");
							$this->load->view('iibfdra/common_view',$data);
						} 
						/* else
						{//if exam is not active
							$this->session->set_flashdata('error','This exam is not active');
							redirect(base_url().'iibfdra/InstituteHome/dashboard');	
						} */
					} 
					/* else
					{ //if exam not found in exam activation master then redirect to home
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					} */
				} 
				else
				{ // if exam does not exists redirect to dashboard
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}				
			} 
			else 
			{
				$this->session->set_flashdata('error','URL is edited. Please try again');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		}	
		
		function getTableDataAjax()	//with sort checkbox on top
		{
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1"); 
			
			if(isset($_POST) && count($_POST) > 0)
			{
				$data['examcode'] = $examcode = $this->input->post('examcode', TRUE);				
				$data['i_val'] = $i_val = $this->input->post('i_val', TRUE);
				$data['chk_val'] = $chk_val = $this->input->post('chk_val', TRUE);
				$data['s_batch_code'] = $s_batch_code = $this->input->post('s_batch_code', TRUE);
				$data['dispLimit'] = $dispLimit = $this->input->post('dispLimit', TRUE);
				$temp_qry_cnt = 0;
				
				if($examcode != "" && $i_val!= "" && $chk_val!= "" )
				{
					$decdexamcode = $examcode;
					if(!intval($decdexamcode))
					{
						$result['flag'] = "error";
					}
					else
					{						
						$decdexamcode = intval($decdexamcode);
						$search_str = $search_codes = '';
						
						$instdata = $this->session->userdata('dra_institute');
						$instcode = $instdata['institute_code'];
						
						$login_agency=$this->session->userdata('dra_institute');
						$agency_id=$login_agency['dra_inst_registration_id'];
						
						if($s_batch_code != "")
						{
							$explode_arr = explode(",", $s_batch_code);
							if(count($explode_arr) > 0)
							{																		
								foreach($explode_arr as $batch_code)
								{
									$search_codes .= "'".trim($batch_code)."',";
								}
								$search_str = " AND a.batch_code IN (".rtrim($search_codes,",").")";
							}
						} 
						
						$query = $this->db->query("SELECT a.batch_name, a.batch_code, a.batch_from_date, a.batch_to_date, d.firstname, d.batch_id, d.middlename, d.regid, d.lastname, d.regnumber, d.dateofbirth, d.email, d.registrationtype, '' AS utr_no
						FROM agency_batch a
						LEFT JOIN dra_members d ON a.id = d.batch_id AND d.inst_code = $instcode 							
						
						WHERE d.isdeleted = 0 AND d.batch_id != 0  AND d.re_attempt < 3 AND d.inst_code = $instcode   AND a.agency_id = $agency_id  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
						FROM dra_eligible_master el WHERE el.exam_status IN('F','P') AND el.member_no = d.regnumber AND el.member_no !='') ".$search_str." 
						order by d.regid DESC") ;
						$res = $query->result_array();
						//$result['qry1'] = $this->db->last_query();
						
						//echo "<br>".count($res);
						//echo "<pre>"; print_r($res); echo "</pre>"; //die;
						//echo $this->db->last_query();die;
						//echo 'in'; exit;
						//fetch fail member record from eligible master  
						
						$eligible = $this->db->query("SELECT e.id, e.exam_code, e.eligible_period, e.member_no, e.training_from, e.training_to,  d.regnumber, d.firstname, d.middlename, d.lastname, d.dateofbirth, d.email, d.registrationtype, d.batch_id, d.excode, d.re_attempt, d.regid, a.batch_to_date, a.batch_from_date, a.batch_name, a.batch_code, s.exam_date, em.trg_value, '' AS utr_no
						FROM dra_eligible_master e
						LEFT JOIN dra_members d ON e.member_no = d.regnumber AND e.exam_code = d.excode						
						LEFT JOIN agency_batch a ON a.id = d.batch_id
						LEFT JOIN dra_subject_master s ON s.exam_code = e.exam_code
						LEFT JOIN dra_misc_master em ON em.exam_code = e.exam_code        
						
						where d.isdeleted = 0 AND d.batch_id != 0 AND e.app_category != '' AND d.re_attempt < 3 AND d.inst_code = $instcode  AND e.exam_code = $decdexamcode AND e.exam_status = 'F' AND a.batch_status='A' AND e.member_no !='' ".$search_str."
						GROUP BY s.exam_code, e.member_no
						order by d.regid desc");						
						$eligible_result1 = $eligible->result_array();
						//$result['qry2'] = $this->db->last_query();
						
						//  $resultc = array_merge($res,$eligible_result); 
						//echo "<br>".count($eligible_result1);
						//echo "<pre>"; print_r($eligible_result1); echo "</pre>"; //die;
						//echo $this->db->last_query();die;
						
						$exam_period = $comp_currdate = $comp_frmdate = $comp_todate = '';
						$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
						if( count($examact) > 0 ) 
						{
							$exam_period =  $examact[0]['exam_period'];
							$comp_currdate = date('Y-m-d');
							$comp_frmdate = $examact[0]['exam_from_date'];
							$comp_todate = $examact[0]['exam_to_date'];
						}
						
						$data['exam_period'] = $data['examperiods'] = $examperiods = $exam_period;
						$data['comp_currdate'] = $comp_currdate;
						$data['comp_frmdate'] = $comp_frmdate;
						$data['comp_todate'] = $comp_todate;
						
						$data['form_data'] = $form_data = array_merge($res,$eligible_result1); //array_merge($resultarr,$eligible_result);
						//array_merge($resultarr,$eligible_result);                  	
						/* $result['ApplicantResponse'] = $this->load->view('iibfdra/incExamApplicationAjax_sm', $data, true); */
						
						//echo '<pre>'; print_r($form_data);  echo '<pre>';  exit;
						//$result['all_data_cnt'] = count($form_data);
						
						//*************************************************
						$valid_data = array();
						if(count($form_data) > 0)
						{
							$this->db->select('t1.id, t1.regid, t1.exam_center_code, t1.exam_fee, t1.pay_status, t1.id as mem_examid, t1.created_on as ecreated_on, t1.exam_medium, t1.batchId, t1.exam_period');
							$AllExamDetailsRaw = $this->master_model->getRecords('dra_member_exam t1','', '', array('t1.id'=>'DESC'));
							$AllExamDetails = array();
							if(count($AllExamDetailsRaw) > 0)
							{
								foreach($AllExamDetailsRaw as $raw)
								{
									$AllExamDetails[$raw['regid']][] = $raw;
								}
							}
							
							$trg_value=0;							
							foreach($form_data as $row)
							{	
								$dispRowFlag = $dispCheckboxFlag = 0;
								/* $response_res = array();
								if(isset($row['eligible_period']))
								{
									$where = array('dra_member_exam.exam_period'=>$row['eligible_period'], 'dra_member_exam.regid'=>$row['regid']);
								}
								else
								{
									$where = array('dra_member_exam.regid'=>$row['regid']);
								}												
								
								$exam_details=array();
								$this->db->select('exam_center_code, exam_fee, pay_status, id as mem_examid, created_on as ecreated_on,exam_medium,batchId,exam_period');
								$examdetails = $this->master_model->getRecords('dra_member_exam',$where,'',array('id'=>'DESC')); */
																
								$response_res = $where = $exam_details = $examdetails = array();											
								$chkEligiblePeriod = '';
								if(isset($row['eligible_period'])) { $chkEligiblePeriod = $row['eligible_period']; }									
								
								if(array_key_exists($row['regid'], $AllExamDetails))
								{
									$chkArryNew = $AllExamDetails[$row['regid']];
									foreach($chkArryNew as $chk_arr)
									{												
										if($chkEligiblePeriod != "")
										{
											if($chk_arr['exam_period'] == $chkEligiblePeriod)
											{
												$examdetails[0] = $chk_arr;
												break;
											}
										}
										else
										{
											$examdetails[0] = $chk_arr;
											break;
										}
									}
								}										
								
								if(count($examdetails) > 0 )
								{
									$exam_details = $examdetails[0];
									
									if($exam_details['pay_status'] == 3 || $exam_details['pay_status'] == 2) 
									{ //if payment mode is NEFT and pending for approval by iibf
										$memexamid = $exam_details['mem_examid'];
										//added for neft case
										$this->db->order_by("ptid", "desc");
										$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
										
										$temp_qry_cnt = $temp_qry_cnt + 1;
										
										if( $transid ) 
										{
											$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');  
											$exam_details['utr_no'] = $utrno; 
											$temp_qry_cnt = $temp_qry_cnt + 1;
										}					
									}
									else 
									{ // pay_status is fail-0 or pending-2
										$exam_details['utr_no'] ='';  
									}
								}
								else
								{
									$exam_details['exam_center_code'] = '';
									$exam_details['exam_fee'] = '';
									$exam_details['pay_status'] = '';
									$exam_details['mem_examid'] = '';
									$exam_details['ecreated_on'] = '';
									$exam_details['exam_medium'] = '';
									$exam_details['exam_period'] = '';
									$exam_details['utr_no'] = '';
								}
								
								$training_from    = $row['batch_from_date'];
								$training_to    = $row['batch_to_date'];
								$registrationtype = $row['registrationtype'];
								$batch_id  = $row['batch_id'];
								if(isset($row['trg_value'])){$trg_value = $row['trg_value'] + 1;}
								$Todate = date_create($row['batch_to_date']);
								date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
								
								if((isset($row['exam_date'])) )//check 290days < and 3attempt < alert already exist in exam list
								{                             
									if(($row['exam_date'] < date_format($Todate, "Y-m-d")) && $row['re_attempt'] < 3 && (($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 1;
									}					
								}
								else
								{
									if((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  &&  ($exam_details['exam_period'] == $examperiods || $exam_details['exam_period']=='') && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09')) 
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 1;					
									} 
									elseif((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == '') && $exam_details['pay_status'] != 4 ) &&  ($exam_details['exam_period'] != $examperiods) && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 0;
									}
								}
								
								if($dispRowFlag == 1)
								{ 
									$row1 = '<td align="center">';
									$temp_key = 0;
									if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')) && $dispCheckboxFlag == 1)
									{
										if(($exam_details['pay_status'] == 2 || $exam_details['pay_status'] == '') && ($exam_details['exam_center_code']!='' &&  $exam_details['exam_medium']!='')) 
										{ 
											$temp_key = 1;
											$row1 .= '<span class="hide">1</span>
											<input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="'.$exam_details['mem_examid'].'" data-attr="'.$row['regid'].'"/>';
										}
									}
									$row1 .='</td>';
									$response_res[] = $row1;
									$response_res[] = '<td></td>';
									$response_res[] = '<td>'.$row['batch_code'].'</td>';
									$response_res[] = '<td>'.$row['batch_name'].'</td>';
									$response_res[] = '<td>'.$row['regnumber'].'</td>';
									$response_res[] = '<td>'.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'].'</td>';
									$response_res[] = '<td>'.date("d-M-Y", strtotime($row['dateofbirth'])).'</td>';
									$response_res[] = '<td>'.$row['email'].'</td>';
									
									$row2 = '<td class="fee'.$row['regid'].'">';
									
									if($dispCheckboxFlag == 1)
									{
										if($exam_details['exam_fee'] <= 0 || $exam_details['exam_fee'] == ""){ $row2 .= '0.00'; } else { $row2 .= $exam_details['exam_fee']; }
									}
									else 
									{	
										if( $exam_details['pay_status'] == '0' ) { $row2 .= '0.00';} 
										else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row2 .= '0.00'; } 
										else if($exam_details['pay_status'] == '3') { $row2 .= $exam_details['exam_fee'];} 
									}
									$row2 .='</td>';
									$response_res[] = $row2;
									
									$row3 = '<td class="status'.$row['regid'].'">';
									if( $exam_details['pay_status'] == '0' ) { $row3 .= 'Fail';} 
									else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row3 .= 'Pending'; } 
									else if($exam_details['pay_status'] == '3') { $row3 .= 'Payment For Approve By IIBF';}
									$row3 .='</td>';
									$response_res[] = $row3;
									
									$row4 = '<td>';
									if(isset($exam_details['utr_no'])){ $row4 .= $exam_details['utr_no'];}
									$row4 .='</td>';
									$response_res[] = $row4;
									
									$row5 ='<td style="width: 117px;">';
									if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
									{ 
										$this->db->select('center_name');
										$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
										$center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$exam_details['exam_center_code']),'',array('center_name'=>'ASC'));
										if(count($center_name) > 0)
										{
											$row5 .= $center_name[0]['center_name'];
										}
										$temp_qry_cnt = $temp_qry_cnt + 1;
									} 
									else { $row5 .= '-'; }
									
									$row5 .='<input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="'.set_value('center_code').'" autocomplete="off" readonly>
									</td>';                    
									$response_res[] = $row5;
									
									$row6 = '<td style="width: 117px;">';
									if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
									{ 
										$this->db->select('medium_description');
										$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
										$medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$exam_details['exam_medium']));
										if(count($medium_name) > 0)
										{
											$row6 .= $medium_name[0]['medium_description'];
										}
										$temp_qry_cnt = $temp_qry_cnt + 1;
									} else { $row6 .= '-'; }
									$row6 .='</td>';
									$response_res[] = $row6;
									
									$row7 = '<td>
									<input type="hidden" value="'.$batch_id.'" id="batch_id'.$row['regid'].'">
									<input type="hidden" value="'.$registrationtype.'" id="memtype'.$row['regid'].'">
									<input type="hidden" value="'.$training_from.'" id="training_from'.$row['regid'].'">
									<input type="hidden" value="'.$training_to.'" id="training_to'.$row['regid'].'">';
									
									if( $exam_details['pay_status'] == 0 || $exam_details['pay_status'] == 2 ) 
									{ 
										$row7 .='<a class="mbtn btn-xs btn-warning2" target="_blank" href="'.base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'].'">Edit</a>';
										
										if((isset($exam_details['exam_medium']) || isset($exam_details['exam_center_code'])) && $dispCheckboxFlag == 1)
										{
											if($exam_details['exam_medium'] != '' || $exam_details['exam_center_code'] != 0 )
											{ 
												$row7 .='<a href="javascript:void(0)" class="'.$row['regid'].' mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title="Clear Submit" onclick="clearexam('.$row['regid'].')" > | Clear</a>';
											}
										} 
									} else { $row7 .= '-';}
									$row7 .='</td>';
									$response_res[] = $row7;
									
									$response_res['sort'] = $temp_key;
									$valid_data[] = $response_res;                    
									
								}
							}// Foreach End               
						}                
						//echo '<pre>'; print_r($valid_data);  echo '</pre>'; 
						//$result['valid_data_cnt'] = count($valid_data);
						
						$sort_var = array();
						foreach ($valid_data as $key => $row)
						{
							$sort_var[$key] = $row['sort'];
						}
						array_multisort($sort_var, SORT_DESC, $valid_data);
						
						$sort_data = $valid_data;
						$data['total_sort_data_cnt'] = $total_sort_data_cnt = count($sort_data);
						//$result['sort_data_cnt'] = count($sort_data);
						//echo '<pre>'; print_r($sort_data);  echo '</pre>';
						//*************************************************
						
						$data = array();
						if(count($sort_data) > 0)
						{
							$chkCnt = 1;
							//foreach($sort_data as $row)
							for($i = ($i_val-1); $i < $chk_val; $i++)
							{	
								if($i < $total_sort_data_cnt) 
								{								
									$response_res = array();
									$row = $sort_data[$i];
									$response_res[] = $row[0];
									$response_res[] = ($i+1);
									$response_res[] = $row[2];
									$response_res[] = $row[3];
									$response_res[] = $row[4];
									$response_res[] = $row[5];
									$response_res[] = $row[6];
									$response_res[] = $row[7];
									$response_res[] = $row[8];
									$response_res[] = $row[9];
									$response_res[] = $row[10];
									$response_res[] = $row[11];
									$response_res[] = $row[12];
									$response_res[] = $row[13];
									$data[] = $response_res;
									
									//if($chkCnt > $chk_val) { break; }
								}								
								$chkCnt++;
							}// Foreach End               
						}
						//echo '<pre>'; print_r($data);  echo '</pre>';
						$result['temp_qry_cnt'] = $temp_qry_cnt;
						$result['ApplicantResponse'][] = $data;
						
						$new_i_val = $i_val + $dispLimit;
						$new_chk_val = $chk_val + $dispLimit;
						
						if($new_i_val <= $total_sort_data_cnt)
						{ 
							$disp_css = '';
							//if($for_all_cnt < $total_sort_data_cnt){ } else { $disp_css = 'style = " display:none; "'; }
							
							$onclickFunShowMore = "getTableDataAjax('".$new_i_val."', '".$new_chk_val."')";
							$result['ShowMoreBtn'] = '<br><div class="col-md-12 ButtonAllWebinars text-center" id="showMoreBtn" '.$disp_css.'>
							<a href="javascript:void(0)" class="btn btn-info" onclick="'.$onclickFunShowMore.'">Show More</a>				
							</div>';
						}
						else {  $result['ShowMoreBtn'] = ""; }
						
						$result['flag'] = "success";
						$result['total_sort_data_cnt'] = $total_sort_data_cnt;
						$result['chkCnt'] = $chkCnt;						
						//$result['new_data'] = $new_data;
					}
				}
				else
				{
					$result['flag'] = "error";
				}
			}
			else
			{
				$result['flag'] = "error";	
			}
			
			echo json_encode($result);
		}
		
		//get all candidates against exam
		public function allapplicants_sm()  
		{
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1"); 
			
			$s_batch_code = $s_member_no = '';
			$data['dispLimit'] = $dispLimit = 1000;
			
			if(isset($_GET['sBtCd'])&& $_GET['sBtCd'] != "") { $s_batch_code = $_GET['sBtCd']; }
			if(isset($_GET['sMeNo'])&& $_GET['sMeNo'] != "") { $s_member_no = $_GET['sMeNo']; }
			
			if(isset( $_GET['exCd'])) 
			{	
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$examcode = trim( $_GET['exCd'] );
				$decdexamcode = base64_decode($examcode);
				if(!intval($decdexamcode))
				{
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				
				$decdexamcode = intval($decdexamcode);
				//check if exam exists or not
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));				
				if( $examcount > 0 )
				{
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
					//if(count($examact) > 0) 
					{
						//$comp_currdate = date('Y-m-d H:i:s');
						//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
						//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
						
						$exam_period =  $examact[0]['exam_period'];
						$comp_currdate = date('Y-m-d');
						$comp_frmdate = $examact[0]['exam_from_date'];
						$comp_todate = $examact[0]['exam_to_date'];
						//if(strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate))
						{
							$data['middle_content']	= 'draexam_candidates_smm';							
							$data['examperiods']	= $examact[0]['exam_period'];
							$data['examcode']	= $examcode = $decdexamcode;
							$data['s_batch_code']	= $s_batch_code;
							$data['s_member_no']	= $s_member_no;
							
							########################################################################################
							{
								{						
									$search_str = $search_codes = $search_member_no = '';
									
									$instdata = $this->session->userdata('dra_institute');
									$instcode = $instdata['institute_code'];
									
									$login_agency=$this->session->userdata('dra_institute');
									$agency_id=$login_agency['dra_inst_registration_id'];
									
									if($s_batch_code != "")
									{
										$explode_arr = explode(",", $s_batch_code);
										if(count($explode_arr) > 0)
										{																		
											foreach($explode_arr as $batch_code)
											{
												$search_codes .= "'".trim($batch_code)."',";
											}
											$search_str = " AND a.batch_code IN (".rtrim($search_codes,",").")";
										}
									}
									
									if($s_member_no != "")
									{
										$explode_arr2 = explode(",", $s_member_no);
										if(count($explode_arr2) > 0)
										{																		
											foreach($explode_arr2 as $member_no)
											{
												$search_member_no .= "'".trim($member_no)."',";
											}
											$search_str .= " AND d.regnumber IN (".rtrim($search_member_no,",").")";
										}
									} 
									
									$query = $this->db->query("SELECT a.batch_name, a.batch_code, a.batch_from_date, a.batch_to_date, d.firstname, d.batch_id, d.middlename, d.regid, d.lastname, d.regnumber, d.dateofbirth, d.email, d.registrationtype, '' AS utr_no
									FROM agency_batch a
									LEFT JOIN dra_members d ON a.id = d.batch_id AND d.inst_code = $instcode 							
									
									WHERE d.isdeleted = 0 AND d.batch_id != 0  AND d.re_attempt < 3 AND d.inst_code = $instcode   AND a.agency_id = $agency_id  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
									FROM dra_eligible_master el WHERE el.exam_status IN('F','P') AND el.member_no = d.regnumber AND el.member_no !='') ".$search_str." 
									order by d.regid DESC") ; 
									$res = $query->result_array();
									//echo $result['qry1'] = $this->db->last_query(); exit;
									
									$eligible = $this->db->query("SELECT e.id, e.exam_code, e.eligible_period, e.member_no, e.training_from, e.training_to,  d.regnumber, d.firstname, d.middlename, d.lastname, d.dateofbirth, d.email, d.registrationtype, d.batch_id, d.excode, d.re_attempt, d.regid, a.batch_to_date, a.batch_from_date, a.batch_name, a.batch_code, s.exam_date, em.trg_value, '' AS utr_no
									FROM dra_eligible_master e
									LEFT JOIN dra_members d ON e.member_no = d.regnumber AND e.exam_code = d.excode						
									LEFT JOIN agency_batch a ON a.id = d.batch_id
									LEFT JOIN dra_subject_master s ON s.exam_code = e.exam_code
									LEFT JOIN dra_misc_master em ON em.exam_code = e.exam_code        
									
									where d.isdeleted = 0 AND d.batch_id != 0 AND e.app_category != '' AND d.re_attempt < 3 AND d.inst_code = $instcode  AND e.exam_code = $decdexamcode AND e.exam_status = 'F' AND a.batch_status='A' AND e.member_no !='' ".$search_str." 
									GROUP BY s.exam_code, e.member_no
									order by d.regid desc");						
									$eligible_result1 = $eligible->result_array();
									//echo "<br><br>".$result['qry2'] = $this->db->last_query();
									
									/* $new_query = $this->db->query("SELECT el.id, el.exam_code, el.eligible_period, el.training_from, el.training_to, a.batch_name, a.batch_code, a.batch_from_date, a.batch_to_date, d.firstname, d.batch_id, d.middlename, d.regid, d.lastname, d.regnumber, d.dateofbirth, d.email, d.registrationtype, '' AS utr_no FROM agency_batch a LEFT JOIN dra_members d ON a.id = d.batch_id AND d.inst_code = $instcode left join dra_eligible_master el on d.regnumber=el.member_no LEFT JOIN dra_subject_master s ON s.exam_code = el.exam_code LEFT JOIN dra_misc_master em ON em.exam_code = el.exam_code WHERE d.isdeleted = 0 AND d.batch_id != 0 AND d.re_attempt < 3 AND d.inst_code = $instcode AND a.agency_id = $agency_id AND a.batch_status='A' AND d.excode IN(0,45) and (el.exam_status='F' AND el.app_category !='' and el.exam_code=45 and el.member_no = d.regnumber OR el.member_no IS NULL) ".$search_str." 
									group by d.regid ORDER BY `d`.`regid` ASC") ; 
									$new_res = $new_query->result_array(); */
									
									$exam_period = $comp_currdate = $comp_frmdate = $comp_todate = '';
									$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
									if( count($examact) > 0 ) 
									{
										$exam_period =  $examact[0]['exam_period'];
										$comp_currdate = date('Y-m-d');
										$comp_frmdate = $examact[0]['exam_from_date'];
										$comp_todate = $examact[0]['exam_to_date'];
									}
									
									$data['exam_period'] = $data['examperiods'] = $examperiods = $exam_period;
									$data['comp_currdate'] = $comp_currdate;
									$data['comp_frmdate'] = $comp_frmdate;
									$data['comp_todate'] = $comp_todate;
									
									$data['form_data'] = $form_data = array_merge($res,$eligible_result1); /* $result['ApplicantResponse'] = $this->load->view('iibfdra/incExamApplicationAjax_sm', $data, true); */
									
									//echo '<pre>'; print_r($form_data);  echo '<pre>';  //exit; 
									//echo '<br><br> Total data : '.count($form_data); //exit; XXX
									//echo $result['all_data_cnt'] = count($form_data); exit;
									
									//*************************************************
									$valid_data = array();
									if(count($form_data) > 0)
									{
										$this->db->select('t1.id, t1.regid, t1.exam_center_code, t1.exam_fee, t1.pay_status, t1.id as mem_examid, t1.created_on as ecreated_on, t1.exam_medium, t1.batchId, t1.exam_period');
										$AllExamDetailsRaw = $this->master_model->getRecords('dra_member_exam t1','', '', array('t1.id'=>'DESC'));
										$AllExamDetails = array();
										if(count($AllExamDetailsRaw) > 0)
										{
											foreach($AllExamDetailsRaw as $raw)
											{
												$AllExamDetails[$raw['regid']][] = $raw;
											}
										}//echo '<pre>'; print_r($AllExamDetails); echo '</pre>'; exit;										
																					
										$trg_value=0;
										foreach($form_data as $row)
										{	
											$dispRowFlag = $dispCheckboxFlag = 0;
											$response_res = $where = $exam_details = $examdetails = array();
											
											$chkEligiblePeriod = '';
											if(isset($row['eligible_period'])) { $chkEligiblePeriod = $row['eligible_period']; }									
											
											/* if(isset($row['eligible_period']))
											{
												$where = array('dra_member_exam.exam_period' => $row['eligible_period'], 'dra_member_exam.regid' => $row['regid']);
											}
											else { $where = array('dra_member_exam.regid'=>$row['regid']); }
											
											$this->db->select('id, regid, exam_center_code, exam_fee, pay_status, id as mem_examid, created_on as ecreated_on,exam_medium,batchId,exam_period');
											$this->db->limit(1);
											$examdetails = $this->master_model->getRecords('dra_member_exam',$where,'',array('id'=>'DESC')); */
											
											if(array_key_exists($row['regid'], $AllExamDetails))
											{
												$chkArryNew = $AllExamDetails[$row['regid']];
												foreach($chkArryNew as $chk_arr)
												{												
													if($chkEligiblePeriod != "")
													{
														if($chk_arr['exam_period'] == $chkEligiblePeriod)
														{
															$examdetails[0] = $chk_arr;
															break;
														}
													}
													else
													{
														$examdetails[0] = $chk_arr;
														break;
													}
												}
											}
											
											//if(count($examdetails) > 0) { echo '<br>'; print_r($examdetails[0]['id']);  }
											
											if(count($examdetails) > 0 )
											{
												$exam_details = $examdetails[0];
												
												if($exam_details['pay_status'] == 3 || $exam_details['pay_status'] == 2) 
												{ //if payment mode is NEFT and pending for approval by iibf
													$memexamid = $exam_details['mem_examid'];
													//added for neft case
													
													$this->db->order_by("ptid", "desc");
													$this->db->limit(1);
													$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
													//echo "<br>".$this->db->last_query(); exit;
													
													if( $transid ) 
													{
														$this->db->limit(1);
														$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');  
														$exam_details['utr_no'] = $utrno; 
													}
													//$exam_details['utr_no'] = ''; 
												}
												else 
												{ // pay_status is fail-0 or pending-2
													$exam_details['utr_no'] ='';  
												}
											}
											else
											{
												$exam_details['exam_center_code'] = '';
												$exam_details['exam_fee'] = '';
												$exam_details['pay_status'] = '';
												$exam_details['mem_examid'] = '';
												$exam_details['ecreated_on'] = '';
												$exam_details['exam_medium'] = '';
												$exam_details['exam_period'] = '';
												$exam_details['utr_no'] = '';
											}
											
											$training_from    = $row['batch_from_date'];
											$training_to    = $row['batch_to_date'];
											$registrationtype = $row['registrationtype'];
											$batch_id  = $row['batch_id'];
											if(isset($row['trg_value'])){$trg_value = $row['trg_value'] + 1;}
											$Todate = date_create($row['batch_to_date']);
											date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
											
											if((isset($row['exam_date'])) )//check 290days < and 3attempt < alert already exist in exam list
											{                             
												if(($row['exam_date'] < date_format($Todate, "Y-m-d")) && $row['re_attempt'] < 3 && (($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
												{ 
													$dispRowFlag = 1;
													$dispCheckboxFlag = 1;
												}					
											}
											else
											{
												if((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  &&  ($exam_details['exam_period'] == $examperiods || $exam_details['exam_period']=='') && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09')) 
												{ 
													$dispRowFlag = 1;
													$dispCheckboxFlag = 1;					
												} 
												elseif((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == '') && $exam_details['pay_status'] != 4 ) &&  ($exam_details['exam_period'] != $examperiods) && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
												{ 
													$dispRowFlag = 1;
													$dispCheckboxFlag = 0;
												}
											}
											
											if($dispRowFlag == 1 && $dispCheckboxFlag == 1)
											{ 
												$row1 = '<td align="center">';
												$temp_key = 0;
												if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')) && $dispCheckboxFlag == 1)
												{
													if(($exam_details['pay_status'] == 2 || $exam_details['pay_status'] == '') && ($exam_details['exam_center_code']!='' &&  $exam_details['exam_medium']!='')) 
													{ 
														$temp_key = 1;
														$row1 .= '<span class="hide">1</span>
														<input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="'.$exam_details['mem_examid'].'" data-attr="'.$row['regid'].'"/>';
													}
												}
												$row1 .='</td>';
												$response_res[] = $row1;
												$response_res[] = '<td></td>';
												$response_res[] = '<td>'.$row['batch_code'].'</td>';
												$response_res[] = '<td>'.$row['batch_name'].'</td>';
												$response_res[] = '<td>'.$row['regnumber'].'</td>';
												$response_res[] = '<td>'.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'].'</td>';
												$response_res[] = '<td>'.date("d-M-Y", strtotime($row['dateofbirth'])).'</td>';
												$response_res[] = '<td>'.$row['email'].'</td>';
												
												$row2 = '<td class="fee'.$row['regid'].'">';
												
												if($dispCheckboxFlag == 1)
												{
													if($exam_details['exam_fee'] <= 0 || $exam_details['exam_fee'] == ""){ $row2 .= '0.00'; } else { $row2 .= $exam_details['exam_fee']; }
												}
												else 
												{	
													if( $exam_details['pay_status'] == '0' ) { $row2 .= '0.00';} 
													else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row2 .= '0.00'; } 
													else if($exam_details['pay_status'] == '3') { $row2 .= $exam_details['exam_fee'];} 
												}
												$row2 .='</td>';
												$response_res[] = $row2;
												
												$row3 = '<td class="status'.$row['regid'].'">';
												if( $exam_details['pay_status'] == '0' ) { $row3 .= 'Fail';} 
												else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row3 .= 'Pending'; } 
												else if($exam_details['pay_status'] == '3') { $row3 .= 'Payment For Approve By IIBF';}
												$row3 .='</td>';
												$response_res[] = $row3;
												
												$row4 = '<td>';
												if(isset($exam_details['utr_no'])){ $row4 .= $exam_details['utr_no'];}
												$row4 .='</td>';
												$response_res[] = $row4;
												
												$row5 ='<td style="width: 117px;">';
												if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
												{ 
													/* $this->db->select('center_name');
													$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
													$center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$exam_details['exam_center_code']),'',array('center_name'=>'ASC'));
													if(count($center_name) > 0)
													{
														$row5 .= $center_name[0]['center_name'];
													} */
												} 
												else { $row5 .= '-'; }
												
												$row5 .='<input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="'.set_value('center_code').'" autocomplete="off" readonly>
												</td>';                    
												$response_res[] = $row5;
												
												$row6 = '<td style="width: 117px;">';
												if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
												{ 
													/* $this->db->select('medium_description');
													$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
													$medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$exam_details['exam_medium']));
													if(count($medium_name) > 0)
													{
														$row6 .= $medium_name[0]['medium_description'];
													} */
												} else { $row6 .= '-'; }
												$row6 .='</td>';
												$response_res[] = $row6;
												
												$row7 = '<td>
												<input type="hidden" value="'.$batch_id.'" id="batch_id'.$row['regid'].'">
												<input type="hidden" value="'.$registrationtype.'" id="memtype'.$row['regid'].'">
												<input type="hidden" value="'.$training_from.'" id="training_from'.$row['regid'].'">
												<input type="hidden" value="'.$training_to.'" id="training_to'.$row['regid'].'">';
												
												if( $exam_details['pay_status'] == 0 || $exam_details['pay_status'] == 2 ) 
												{ 
													$row7 .='<a class="mbtn btn-xs btn-warning2" target="_blank" href="'.base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'].'">Edit</a>';
													
													if((isset($exam_details['exam_medium']) || isset($exam_details['exam_center_code'])) && $dispCheckboxFlag == 1)
													{
														if($exam_details['exam_medium'] != '' || $exam_details['exam_center_code'] != 0 )
														{ 
															$row7 .='<a href="javascript:void(0)" class="'.$row['regid'].' mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title="Clear Submit" onclick="clearexam('.$row['regid'].')" > | Clear</a>';
														}
													} 
												} else { $row7 .= '-';}
												$row7 .='</td>';
												$response_res[] = $row7;
												
												$response_res['sort'] = $temp_key;
												$valid_data[] = $response_res;                    
												
											}
										}// Foreach End               
									}                
									//echo '<pre>'; print_r($valid_data);  echo '</pre>'; exit;
									//$result['valid_data_cnt'] = count($valid_data);
									//echo '<br> Valid data : '.count($valid_data); //exit;
									
									$sort_var = array();
									foreach ($valid_data as $key => $row)
									{
										$sort_var[$key] = $row['sort'];
									}
									array_multisort($sort_var, SORT_DESC, $valid_data);
									
									$sort_data = $valid_data;
									$data['total_sort_data_cnt'] = $total_sort_data_cnt = count($sort_data);
									//$result['sort_data_cnt'] = count($sort_data);
									//echo '<pre>'; print_r($sort_data);  echo '</pre>'; //exit;
									//*************************************************
									
									//echo '<br> Sort data : '.count($sort_data); //exit;  XXX
									$data['sort_data'] = $sort_data;
								}
							}
							########################################################################################
							
							
							$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
							$data['active_exams'] = $res = $this->master_model->getRecords("dra_exam_master a");
							$this->load->view('iibfdra/common_view',$data);
						} 
						/* else
						{//if exam is not active
							$this->session->set_flashdata('error','This exam is not active');
							redirect(base_url().'iibfdra/InstituteHome/dashboard');	
						} */
					} 
					/* else
					{ //if exam not found in exam activation master then redirect to home
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					} */
				} 
				else
				{ // if exam does not exists redirect to dashboard
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}			
			} 
			else 
			{
				$this->session->set_flashdata('error','URL is edited. Please try again');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		}
		
		public function allapplicants_sm_new_qry()  
		{
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1"); 
			
			$s_batch_code = $s_member_no = '';
			$data['dispLimit'] = $dispLimit = 1000;
			
			if(isset($_GET['sBtCd'])&& $_GET['sBtCd'] != "") { $s_batch_code = $_GET['sBtCd']; }
			if(isset($_GET['sMeNo'])&& $_GET['sMeNo'] != "") { $s_member_no = $_GET['sMeNo']; }
			
			if(isset( $_GET['exCd'])) 
			{	
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$examcode = trim( $_GET['exCd'] );
				$decdexamcode = base64_decode($examcode);
				if(!intval($decdexamcode))
				{
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				
				$decdexamcode = intval($decdexamcode);
				//check if exam exists or not
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));				
				if( $examcount > 0 )
				{
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
					if(count($examact) > 0) 
					{
						//$comp_currdate = date('Y-m-d H:i:s');
						//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
						//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
						
						$exam_period =  $examact[0]['exam_period'];
						$comp_currdate = date('Y-m-d');
						$comp_frmdate = $examact[0]['exam_from_date'];
						$comp_todate = $examact[0]['exam_to_date'];
						if(strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate))
						{
							$data['middle_content']	= 'draexam_candidates_smm';							
							$data['examperiods']	= $examact[0]['exam_period'];
							$data['examcode']	= $examcode = $decdexamcode;
							$data['s_batch_code']	= $s_batch_code;
							$data['s_member_no']	= $s_member_no;
							
							########################################################################################
							{
								{						
									$search_str = $search_codes = $search_member_no = '';
									
									$instdata = $this->session->userdata('dra_institute');
									$instcode = $instdata['institute_code'];
									
									$login_agency=$this->session->userdata('dra_institute');
									$agency_id=$login_agency['dra_inst_registration_id'];
									
									if($s_batch_code != "")
									{
										$explode_arr = explode(",", $s_batch_code);
										if(count($explode_arr) > 0)
										{																		
											foreach($explode_arr as $batch_code)
											{
												$search_codes .= "'".trim($batch_code)."',";
											}
											$search_str = " AND a.batch_code IN (".rtrim($search_codes,",").")";
										}
									}
									
									if($s_member_no != "")
									{
										$explode_arr2 = explode(",", $s_member_no);
										if(count($explode_arr2) > 0)
										{																		
											foreach($explode_arr2 as $member_no)
											{
												$search_member_no .= "'".trim($member_no)."',";
											}
											$search_str .= " AND d.regnumber IN (".rtrim($search_member_no,",").")";
										}
									} 
									
									/* $query = $this->db->query("SELECT a.batch_name, a.batch_code, a.batch_from_date, a.batch_to_date, d.firstname, d.batch_id, d.middlename, d.regid, d.lastname, d.regnumber, d.dateofbirth, d.email, d.registrationtype, '' AS utr_no
									FROM agency_batch a
									LEFT JOIN dra_members d ON a.id = d.batch_id AND d.inst_code = $instcode 							
									
									WHERE d.isdeleted = 0 AND d.batch_id != 0  AND d.re_attempt < 3 AND d.inst_code = $instcode   AND a.agency_id = $agency_id  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
									FROM dra_eligible_master el WHERE el.exam_status IN('F','P') AND el.member_no = d.regnumber AND el.member_no !='') ".$search_str." 
									order by d.regid DESC") ; 
									$res = $query->result_array(); */
									//echo $result['qry1'] = $this->db->last_query(); exit;
									
									/* $eligible = $this->db->query("SELECT e.id, e.exam_code, e.eligible_period, e.member_no, e.training_from, e.training_to,  d.regnumber, d.firstname, d.middlename, d.lastname, d.dateofbirth, d.email, d.registrationtype, d.batch_id, d.excode, d.re_attempt, d.regid, a.batch_to_date, a.batch_from_date, a.batch_name, a.batch_code, s.exam_date, em.trg_value, '' AS utr_no
									FROM dra_eligible_master e
									LEFT JOIN dra_members d ON e.member_no = d.regnumber AND e.exam_code = d.excode						
									LEFT JOIN agency_batch a ON a.id = d.batch_id
									LEFT JOIN dra_subject_master s ON s.exam_code = e.exam_code
									LEFT JOIN dra_misc_master em ON em.exam_code = e.exam_code        
									
									where d.isdeleted = 0 AND d.batch_id != 0 AND e.app_category != '' AND d.re_attempt < 3 AND d.inst_code = $instcode  AND e.exam_code = $decdexamcode AND e.exam_status = 'F' AND a.batch_status='A' AND e.member_no !='' ".$search_str." 
									GROUP BY s.exam_code, e.member_no
									order by d.regid desc");						
									$eligible_result1 = $eligible->result_array(); */
									//echo "<br><br>".$result['qry2'] = $this->db->last_query();
									
									$new_query = $this->db->query("SELECT el.id, el.exam_code, el.eligible_period, el.training_from, el.training_to, a.batch_name, a.batch_code, a.batch_from_date, a.batch_to_date, d.firstname, d.batch_id, d.middlename, d.regid, d.lastname, d.regnumber, d.dateofbirth, d.email, d.registrationtype, '' AS utr_no FROM agency_batch a LEFT JOIN dra_members d ON a.id = d.batch_id AND d.inst_code = $instcode left join dra_eligible_master el on d.regnumber=el.member_no LEFT JOIN dra_subject_master s ON s.exam_code = el.exam_code LEFT JOIN dra_misc_master em ON em.exam_code = el.exam_code WHERE d.isdeleted = 0 AND d.batch_id != 0 AND d.re_attempt < 3 AND d.inst_code = $instcode AND a.agency_id = $agency_id AND a.batch_status='A' AND d.excode IN(0,45) and (el.exam_status='F' AND el.app_category !='' and el.exam_code=45 and el.member_no = d.regnumber OR el.member_no IS NULL) ".$search_str." 
									group by d.regid ORDER BY `d`.`regid` ASC") ; 
									$new_res = $new_query->result_array();
									
									$exam_period = $comp_currdate = $comp_frmdate = $comp_todate = '';
									$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
									if( count($examact) > 0 ) 
									{
										$exam_period =  $examact[0]['exam_period'];
										$comp_currdate = date('Y-m-d');
										$comp_frmdate = $examact[0]['exam_from_date'];
										$comp_todate = $examact[0]['exam_to_date'];
									}
									
									$data['exam_period'] = $data['examperiods'] = $examperiods = $exam_period;
									$data['comp_currdate'] = $comp_currdate;
									$data['comp_frmdate'] = $comp_frmdate;
									$data['comp_todate'] = $comp_todate;
									
									$data['form_data'] = $form_data = $new_res; //array_merge($res,$eligible_result1); /* $result['ApplicantResponse'] = $this->load->view('iibfdra/incExamApplicationAjax_sm', $data, true); */
									
									//echo '<pre>'; print_r($form_data);  echo '<pre>';  //exit; 
									echo '<br><br> Total data : '.count($form_data); //exit;
									//echo $result['all_data_cnt'] = count($form_data); exit;
									
									//*************************************************
									$valid_data = array();
									if(count($form_data) > 0)
									{
										$trg_value=0;
										foreach($form_data as $row)
										{	
											$dispRowFlag = $dispCheckboxFlag = 0;
											$response_res = array();
											if(isset($row['eligible_period']))
											{
												$where = array('dra_member_exam.exam_period'=>$row['eligible_period'], 'dra_member_exam.regid'=>$row['regid']);
											}
											else
											{
												$where = array('dra_member_exam.regid'=>$row['regid']);
											}												
											
											$exam_details=array();
											$this->db->select('exam_center_code, exam_fee, pay_status, id as mem_examid, created_on as ecreated_on,exam_medium,batchId,exam_period');
											$this->db->limit(1);
											$examdetails = $this->master_model->getRecords('dra_member_exam',$where,'',array('id'=>'DESC')); 
											//$examdetails = array();
											
											if(count($examdetails) > 0 )
											{
												$exam_details = $examdetails[0];
												
												if($exam_details['pay_status'] == 3 || $exam_details['pay_status'] == 2) 
												{ //if payment mode is NEFT and pending for approval by iibf
													$memexamid = $exam_details['mem_examid'];
													//added for neft case
													
													$this->db->order_by("ptid", "desc");
													$this->db->limit(1);
													$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
													//echo "<br>".$this->db->last_query(); exit;
													
													if( $transid ) 
													{
														$this->db->limit(1);
														$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');  
														$exam_details['utr_no'] = $utrno; 
													}
													//$exam_details['utr_no'] = ''; 
												}
												else 
												{ // pay_status is fail-0 or pending-2
													$exam_details['utr_no'] ='';  
												}
											}
											else
											{
												$exam_details['exam_center_code'] = '';
												$exam_details['exam_fee'] = '';
												$exam_details['pay_status'] = '';
												$exam_details['mem_examid'] = '';
												$exam_details['ecreated_on'] = '';
												$exam_details['exam_medium'] = '';
												$exam_details['exam_period'] = '';
												$exam_details['utr_no'] = '';
											}
											
											$training_from    = $row['batch_from_date'];
											$training_to    = $row['batch_to_date'];
											$registrationtype = $row['registrationtype'];
											$batch_id  = $row['batch_id'];
											if(isset($row['trg_value'])){$trg_value = $row['trg_value'] + 1;}
											$Todate = date_create($row['batch_to_date']);
											date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
											
											if((isset($row['exam_date'])) )//check 290days < and 3attempt < alert already exist in exam list
											{                             
												if(($row['exam_date'] < date_format($Todate, "Y-m-d")) && $row['re_attempt'] < 3 && (($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
												{ 
													$dispRowFlag = 1;
													$dispCheckboxFlag = 1;
												}					
											}
											else
											{
												if((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  &&  ($exam_details['exam_period'] == $examperiods || $exam_details['exam_period']=='') && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09')) 
												{ 
													$dispRowFlag = 1;
													$dispCheckboxFlag = 1;					
												} 
												elseif((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == '') && $exam_details['pay_status'] != 4 ) &&  ($exam_details['exam_period'] != $examperiods) && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
												{ 
													$dispRowFlag = 1;
													$dispCheckboxFlag = 0;
												}
											}
											
											if($dispRowFlag == 1 && $dispCheckboxFlag == 1)
											{ 
												$row1 = '<td align="center">';
												$temp_key = 0;
												if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')) && $dispCheckboxFlag == 1)
												{
													if(($exam_details['pay_status'] == 2 || $exam_details['pay_status'] == '') && ($exam_details['exam_center_code']!='' &&  $exam_details['exam_medium']!='')) 
													{ 
														$temp_key = 1;
														$row1 .= '<span class="hide">1</span>
														<input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="'.$exam_details['mem_examid'].'" data-attr="'.$row['regid'].'"/>';
													}
												}
												$row1 .='</td>';
												$response_res[] = $row1;
												$response_res[] = '<td></td>';
												$response_res[] = '<td>'.$row['batch_code'].'</td>';
												$response_res[] = '<td>'.$row['batch_name'].'</td>';
												$response_res[] = '<td>'.$row['regnumber'].'</td>';
												$response_res[] = '<td>'.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'].'</td>';
												$response_res[] = '<td>'.date("d-M-Y", strtotime($row['dateofbirth'])).'</td>';
												$response_res[] = '<td>'.$row['email'].'</td>';
												
												$row2 = '<td class="fee'.$row['regid'].'">';
												
												if($dispCheckboxFlag == 1)
												{
													if($exam_details['exam_fee'] <= 0 || $exam_details['exam_fee'] == ""){ $row2 .= '0.00'; } else { $row2 .= $exam_details['exam_fee']; }
												}
												else 
												{	
													if( $exam_details['pay_status'] == '0' ) { $row2 .= '0.00';} 
													else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row2 .= '0.00'; } 
													else if($exam_details['pay_status'] == '3') { $row2 .= $exam_details['exam_fee'];} 
												}
												$row2 .='</td>';
												$response_res[] = $row2;
												
												$row3 = '<td class="status'.$row['regid'].'">';
												if( $exam_details['pay_status'] == '0' ) { $row3 .= 'Fail';} 
												else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row3 .= 'Pending'; } 
												else if($exam_details['pay_status'] == '3') { $row3 .= 'Payment For Approve By IIBF';}
												$row3 .='</td>';
												$response_res[] = $row3;
												
												$row4 = '<td>';
												if(isset($exam_details['utr_no'])){ $row4 .= $exam_details['utr_no'];}
												$row4 .='</td>';
												$response_res[] = $row4;
												
												$row5 ='<td style="width: 117px;">';
												if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
												{ 
													/* $this->db->select('center_name');
													$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
													$center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$exam_details['exam_center_code']),'',array('center_name'=>'ASC'));
													if(count($center_name) > 0)
													{
														$row5 .= $center_name[0]['center_name'];
													} */
												} 
												else { $row5 .= '-'; }
												
												$row5 .='<input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="'.set_value('center_code').'" autocomplete="off" readonly>
												</td>';                    
												$response_res[] = $row5;
												
												$row6 = '<td style="width: 117px;">';
												if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
												{ 
													/* $this->db->select('medium_description');
													$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
													$medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$exam_details['exam_medium']));
													if(count($medium_name) > 0)
													{
														$row6 .= $medium_name[0]['medium_description'];
													} */
												} else { $row6 .= '-'; }
												$row6 .='</td>';
												$response_res[] = $row6;
												
												$row7 = '<td>
												<input type="hidden" value="'.$batch_id.'" id="batch_id'.$row['regid'].'">
												<input type="hidden" value="'.$registrationtype.'" id="memtype'.$row['regid'].'">
												<input type="hidden" value="'.$training_from.'" id="training_from'.$row['regid'].'">
												<input type="hidden" value="'.$training_to.'" id="training_to'.$row['regid'].'">';
												
												if( $exam_details['pay_status'] == 0 || $exam_details['pay_status'] == 2 ) 
												{ 
													$row7 .='<a class="mbtn btn-xs btn-warning2" target="_blank" href="'.base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'].'">Edit</a>';
													
													if((isset($exam_details['exam_medium']) || isset($exam_details['exam_center_code'])) && $dispCheckboxFlag == 1)
													{
														if($exam_details['exam_medium'] != '' || $exam_details['exam_center_code'] != 0 )
														{ 
															$row7 .='<a href="javascript:void(0)" class="'.$row['regid'].' mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title="Clear Submit" onclick="clearexam('.$row['regid'].')" > | Clear</a>';
														}
													} 
												} else { $row7 .= '-';}
												$row7 .='</td>';
												$response_res[] = $row7;
												
												$response_res['sort'] = $temp_key;
												$valid_data[] = $response_res;                    
												
											}
										}// Foreach End               
									}                
									//echo '<pre>'; print_r($valid_data);  echo '</pre>'; exit;
									//$result['valid_data_cnt'] = count($valid_data);
									//echo '<br> Valid data : '.count($valid_data); //exit;
									
									$sort_var = array();
									foreach ($valid_data as $key => $row)
									{
										$sort_var[$key] = $row['sort'];
									}
									array_multisort($sort_var, SORT_DESC, $valid_data);
									
									$sort_data = $valid_data;
									$data['total_sort_data_cnt'] = $total_sort_data_cnt = count($sort_data);
									//$result['sort_data_cnt'] = count($sort_data);
									//echo '<pre>'; print_r($sort_data);  echo '</pre>'; //exit;
									//*************************************************
									
									echo '<br> Sort data : '.count($sort_data); exit; 
									$data['sort_data'] = $sort_data;
								}
							}
							########################################################################################
							
							
							$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
							$data['active_exams'] = $res = $this->master_model->getRecords("dra_exam_master a");
							$this->load->view('iibfdra/common_view',$data);
						} 
						else
						{//if exam is not active
							$this->session->set_flashdata('error','This exam is not active');
							redirect(base_url().'iibfdra/InstituteHome/dashboard');	
						}
					} 
					else
					{ //if exam not found in exam activation master then redirect to home
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					}
				} 
				else
				{ // if exam does not exists redirect to dashboard
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}				
			} 
			else 
			{
				$this->session->set_flashdata('error','URL is edited. Please try again');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		}
		
		
		function getTableDataAjax_bk_28_01_2021()		
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$data['examcode'] = $examcode = $this->input->post('examcode', TRUE);				
				$data['i_val'] = $i_val = $this->input->post('i_val', TRUE);
				$data['chk_val'] = $chk_val = $this->input->post('chk_val', TRUE);
				$data['s_batch_code'] = $s_batch_code = $this->input->post('s_batch_code', TRUE);
				$data['dispLimit'] = $dispLimit = $this->input->post('dispLimit', TRUE);
				
				if($examcode != "" && $i_val!= "" && $chk_val!= "" )
				{
					$decdexamcode = $examcode;
					if(!intval($decdexamcode))
					{
						$result['flag'] = "error";
					}
					else
					{						
						$decdexamcode = intval($decdexamcode);
						$search_str = $search_codes = '';
						
						$instdata = $this->session->userdata('dra_institute');
						$instcode = $instdata['institute_code'];
						
						$login_agency=$this->session->userdata('dra_institute');
						$agency_id=$login_agency['dra_inst_registration_id'];
						
						if($s_batch_code != "")
						{
							$explode_arr = explode(",", $s_batch_code);
							if(count($explode_arr) > 0)
							{																		
								foreach($explode_arr as $batch_code)
								{
									$search_codes .= "'".trim($batch_code)."',";
								}
								$search_str = " AND a.batch_code IN (".rtrim($search_codes,",").")";
							}
						} 
						
						$query = $this->db->query("SELECT a.batch_name, a.batch_code, a.batch_from_date, a.batch_to_date, d.firstname, d.batch_id, d.middlename, d.regid, d.lastname, d.regnumber, d.dateofbirth, d.email, d.registrationtype, '' AS utr_no
						FROM agency_batch a
						LEFT JOIN dra_members d ON a.id = d.batch_id AND d.inst_code = $instcode 							
						
						WHERE d.isdeleted = 0 AND d.batch_id != 0  AND d.re_attempt < 3 AND d.inst_code = $instcode   AND a.agency_id = $agency_id  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
						FROM dra_eligible_master el WHERE el.exam_status IN('F','P') AND el.member_no = d.regnumber AND el.member_no !='') ".$search_str." 
						order by d.regid DESC") ;
						$res = $query->result_array();
						//$result['qry1'] = $this->db->last_query();
						
						//echo "<br>".count($res);
						//echo "<pre>"; print_r($res); echo "</pre>"; //die;
						//echo $this->db->last_query();die;
						//echo 'in'; exit;
						//fetch fail member record from eligible master  
						
						$eligible = $this->db->query("SELECT e.id, e.exam_code, e.eligible_period, e.member_no, e.training_from, e.training_to,  d.regnumber, d.firstname, d.middlename, d.lastname, d.dateofbirth, d.email, d.registrationtype, d.batch_id, d.excode, d.re_attempt, d.regid, a.batch_to_date, a.batch_from_date, a.batch_name, a.batch_code, s.exam_date, em.trg_value, '' AS utr_no
						FROM dra_eligible_master e
						LEFT JOIN dra_members d ON e.member_no = d.regnumber AND e.exam_code = d.excode						
						LEFT JOIN agency_batch a ON a.id = d.batch_id
						LEFT JOIN dra_subject_master s ON s.exam_code = e.exam_code
						LEFT JOIN dra_misc_master em ON em.exam_code = e.exam_code        
						
						where d.isdeleted = 0 AND d.batch_id != 0 AND e.app_category != '' AND d.re_attempt < 3 AND d.inst_code = $instcode  AND e.exam_code = $decdexamcode AND e.exam_status = 'F' AND a.batch_status='A' AND e.member_no !='' ".$search_str."
						GROUP BY s.exam_code, e.member_no
						order by d.regid desc");						
						$eligible_result1 = $eligible->result_array();
						//$result['qry2'] = $this->db->last_query();
						
						//  $resultc = array_merge($res,$eligible_result); 
						//echo "<br>".count($eligible_result1);
						//echo "<pre>"; print_r($eligible_result1); echo "</pre>"; //die;
						//echo $this->db->last_query();die;
						
						$exam_period = $comp_currdate = $comp_frmdate = $comp_todate = '';
						$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
						if( count($examact) > 0 ) 
						{
							$exam_period =  $examact[0]['exam_period'];
							$comp_currdate = date('Y-m-d');
							$comp_frmdate = $examact[0]['exam_from_date'];
							$comp_todate = $examact[0]['exam_to_date'];
						}
						
						$data['exam_period'] = $data['examperiods'] = $examperiods = $exam_period;
						$data['comp_currdate'] = $comp_currdate;
						$data['comp_frmdate'] = $comp_frmdate;
						$data['comp_todate'] = $comp_todate;
						
						$data['form_data'] = $form_data = array_merge($res,$eligible_result1); //array_merge($resultarr,$eligible_result);
						//$data['total_form_data'] = $total_form_data = count($form_data); //array_merge($resultarr,$eligible_result);                  	
						/* $result['ApplicantResponse'] = $this->load->view('iibfdra/incExamApplicationAjax_sm', $data, true); */
						
						//*************************************************
						$new_data = array();
						if(count($form_data) > 0)
						{
							$chkCnt = $for_all_cnt = 1;
							$trg_value=0;
							foreach($form_data as $row)
							{	
								$dispRowFlag = $dispCheckboxFlag = 0;
								$response_res = array();
								if(isset($row['eligible_period']))
								{
									$where = array('dra_member_exam.exam_period'=>$row['eligible_period'], 'dra_member_exam.regid'=>$row['regid']);
								}
								else
								{
									$where = array('dra_member_exam.regid'=>$row['regid']);
								}												
								
								$exam_details=array();
								$this->db->select('exam_center_code, exam_fee, pay_status, id as mem_examid, created_on as ecreated_on,exam_medium,batchId,exam_period');
								$examdetails = $this->master_model->getRecords('dra_member_exam',$where,'',array('id'=>'DESC'));
								
								if(count($examdetails) > 0 )
								{
									$exam_details = $examdetails[0];
									
									if($exam_details['pay_status'] == 3 || $exam_details['pay_status'] == 2) 
									{ //if payment mode is NEFT and pending for approval by iibf
										$memexamid = $exam_details['mem_examid'];
										//added for neft case
										$this->db->order_by("ptid", "desc");
										$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
										
										if( $transid ) 
										{
											$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');  
											$exam_details['utr_no'] = $utrno; 
										}					
									}
									else 
									{ // pay_status is fail-0 or pending-2
										$exam_details['utr_no'] ='';  
									}
								}
								else
								{
									$exam_details['exam_center_code'] = '';
									$exam_details['exam_fee'] = '';
									$exam_details['pay_status'] = '';
									$exam_details['mem_examid'] = '';
									$exam_details['ecreated_on'] = '';
									$exam_details['exam_medium'] = '';
									$exam_details['exam_period'] = '';
									$exam_details['utr_no'] = '';
								}
								
								$training_from    = $row['batch_from_date'];
								$training_to    = $row['batch_to_date'];
								$registrationtype = $row['registrationtype'];
								$batch_id  = $row['batch_id'];
								if(isset($row['trg_value'])){$trg_value = $row['trg_value'] + 1;}
								$Todate = date_create($row['batch_to_date']);
								date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
								
								if((isset($row['exam_date'])) )
								{  //check 290days < and 3attempt < alert already exist in exam list
									
									if(($row['exam_date'] < date_format($Todate, "Y-m-d")) && $row['re_attempt'] < 3 && (($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 1;
									}					
								}
								else
								{//echo "swati"; exit;
									if((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  &&  ($exam_details['exam_period'] == $examperiods || $exam_details['exam_period']=='') && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09')) 
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 1;					
									} 
									elseif((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == '') && $exam_details['pay_status'] != 4 ) &&  ($exam_details['exam_period'] != $examperiods) && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 0;
									}
								}
								
								if($dispRowFlag == 1)
								{
									//if($chkCnt >= $i_val && $chkCnt <= $chk_val) 
									{ 
										$row1 = '<td align="center">';
										$temp_key = 0;
										if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')) && $dispCheckboxFlag == 1)
										{
											if(($exam_details['pay_status'] == 2 || $exam_details['pay_status'] == '') && ($exam_details['exam_center_code']!='' &&  $exam_details['exam_medium']!='')) 
											{ 
												$temp_key = 1;
												$row1 .= '<span class="hide">1</span>
												<input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="'.$exam_details['mem_examid'].'" data-attr="'.$row['regid'].'"/>';
											}
										}
										$row1 .='</td>';
										$response_res[] = $row1;
										$response_res[] = '<td>'.$chkCnt.'</td>';
										$response_res[] = '<td>'.$row['batch_code'].'</td>';
										$response_res[] = '<td>'.$row['batch_name'].'</td>';
										$response_res[] = '<td>'.$row['regnumber'].'</td>';
										$response_res[] = '<td>'.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'].'</td>';
										$response_res[] = '<td>'.date("d-M-Y", strtotime($row['dateofbirth'])).'</td>';
										$response_res[] = '<td>'.$row['email'].'</td>';
										
										$row2 = '<td class="fee'.$row['regid'].'">';
										
										if($dispCheckboxFlag == 1)
										{
											if($exam_details['exam_fee'] <= 0 || $exam_details['exam_fee'] == ""){ $row2 .= '0.00'; } else { $row2 .= $exam_details['exam_fee']; }
										}
										else 
										{	
											if( $exam_details['pay_status'] == '0' ) { $row2 .= '0.00';} 
											else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row2 .= '0.00'; } 
											else if($exam_details['pay_status'] == '3') { $row2 .= $exam_details['exam_fee'];} 
										}
										$row2 .='</td>';
										$response_res[] = $row2;
										
										$row3 = '<td class="status'.$row['regid'].'">';
										if( $exam_details['pay_status'] == '0' ) { $row3 .= 'Fail';} 
										else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row3 .= 'Pending'; } 
										else if($exam_details['pay_status'] == '3') { $row3 .= 'Payment For Approve By IIBF';}
										$row3 .='</td>';
										$response_res[] = $row3;
										
										$row4 = '<td>';
										if(isset($exam_details['utr_no'])){ $row4 .= $exam_details['utr_no'];}
										$row4 .='</td>';
										$response_res[] = $row4;
										
										$row5 ='<td style="width: 117px;">';
										if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
										{ 
											$this->db->select('center_name');
											$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
											$center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$exam_details['exam_center_code']),'',array('center_name'=>'ASC'));
											if(count($center_name) > 0)
											{
												$row5 .= $center_name[0]['center_name'];
											}
										} 
										else { $row5 .= '-'; }
										
										$row5 .='<input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="'.set_value('center_code').'" autocomplete="off" readonly>
										</td>';                    
										$response_res[] = $row5;
										
										$row6 = '<td style="width: 117px;">';
										if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
										{ 
											$this->db->select('medium_description');
											$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
											$medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$exam_details['exam_medium']));
											if(count($medium_name) > 0)
											{
												$row6 .= $medium_name[0]['medium_description'];
											}
										} else { $row6 .= '-'; }
										$row6 .='</td>';
										$response_res[] = $row6;
										
										$row7 = '<td>
										<input type="hidden" value="'.$batch_id.'" id="batch_id'.$row['regid'].'">
										<input type="hidden" value="'.$registrationtype.'" id="memtype'.$row['regid'].'">
										<input type="hidden" value="'.$training_from.'" id="training_from'.$row['regid'].'">
										<input type="hidden" value="'.$training_to.'" id="training_to'.$row['regid'].'">';
										
										if( $exam_details['pay_status'] == 0 || $exam_details['pay_status'] == 2 ) 
										{ 
											$row7 .='<a class="mbtn btn-xs btn-warning2" target="_blank" href="'.base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'].'">Edit</a>';
											
											if((isset($exam_details['exam_medium']) || isset($exam_details['exam_center_code'])) && $dispCheckboxFlag == 1)
											{
												if($exam_details['exam_medium'] != '' || $exam_details['exam_center_code'] != 0 )
												{ 
													$row7 .='<a href="javascript:void(0)" class="'.$row['regid'].' mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title="Clear Submit" onclick="clearexam('.$row['regid'].')" > | Clear</a>';
												}
											} 
										} else { $row7 .= '-';}
										$row7 .='</td>';
										$response_res[] = $row7;
										
										$response_res['sort'] = $temp_key;
										$new_data[] = $response_res;                    
									}
									
									//if($chkCnt > $chk_val) { break; }
									//$chkCnt++;
								}
								
								//$for_all_cnt++;
							}// Foreach End               
						}                
						//echo '<pre>'; print_r($new_data);  echo '</pre>'; 
						
						$sort_var = array();
						foreach ($new_data as $key => $row)
						{
							$sort_var[$key] = $row['sort'];
						}
						array_multisort($sort_var, SORT_DESC, $new_data);
						$data['total_form_data'] = $total_form_data = count($new_data);
						//echo '<pre>'; print_r($new_data);  echo '</pre>';
						//*************************************************
						
						$data = array();
						if(count($new_data) > 0)
						{
							$chkCnt = $for_all_cnt = 1;
							$trg_value=0;
							foreach($new_data as $row)
							{	
								$dispRowFlag = $dispCheckboxFlag = 0;
								$result = array();
								if(isset($row['eligible_period']))
								{
									$where = array('dra_member_exam.exam_period'=>$row['eligible_period'], 'dra_member_exam.regid'=>$row['regid']);
								}
								else
								{
									$where = array('dra_member_exam.regid'=>$row['regid']);
								}												
								
								$exam_details=array();
								$this->db->select('exam_center_code, exam_fee, pay_status, id as mem_examid, created_on as ecreated_on,exam_medium,batchId,exam_period');
								$examdetails = $this->master_model->getRecords('dra_member_exam',$where,'',array('id'=>'DESC'));
								
								if(count($examdetails) > 0 )
								{
									$exam_details = $examdetails[0];				
									$result = array();
									
									if($exam_details['pay_status'] == 3 || $exam_details['pay_status'] == 2) 
									{ //if payment mode is NEFT and pending for approval by iibf
										$memexamid = $exam_details['mem_examid'];
										//added for neft case
										$this->db->order_by("ptid", "desc");
										$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
										
										if( $transid ) 
										{
											$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');  
											$exam_details['utr_no'] = $utrno; 
										}					
									}
									else 
									{ // pay_status is fail-0 or pending-2
										$exam_details['utr_no'] ='';  
									}
								}
								else
								{
									$exam_details['exam_center_code'] = '';
									$exam_details['exam_fee'] = '';
									$exam_details['pay_status'] = '';
									$exam_details['mem_examid'] = '';
									$exam_details['ecreated_on'] = '';
									$exam_details['exam_medium'] = '';
									$exam_details['exam_period'] = '';
									$exam_details['utr_no'] = '';
								}
								
								$training_from    = $row['batch_from_date'];
								$training_to    = $row['batch_to_date'];
								$registrationtype = $row['registrationtype'];
								$batch_id  = $row['batch_id'];
								if(isset($row['trg_value'])){$trg_value = $row['trg_value'] + 1;}
								$Todate = date_create($row['batch_to_date']);
								date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
								
								if((isset($row['exam_date'])) )
								{  //check 290days < and 3attempt < alert already exist in exam list
									
									if(($row['exam_date'] < date_format($Todate, "Y-m-d")) && $row['re_attempt'] < 3 && (($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 1;
									}					
								}
								else
								{//echo "swati"; exit;
									if((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == ''))  &&  ($exam_details['exam_period'] == $examperiods || $exam_details['exam_period']=='') && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09')) 
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 1;					
									} 
									elseif((($exam_details['pay_status'] != 1 || $exam_details['pay_status'] == '') && $exam_details['pay_status'] != 4 ) &&  ($exam_details['exam_period'] != $examperiods) && ($exam_details['ecreated_on'] == "" || $exam_details['ecreated_on'] > '2018-01-09'))
									{ 
										$dispRowFlag = 1;
										$dispCheckboxFlag = 0;
									}
								}
								
								
								if($dispRowFlag == 1)
								{
									if($chkCnt >= $i_val && $chkCnt <= $chk_val) 
									{ 
										$row1 = '<td align="center">';
										if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')) && $dispCheckboxFlag == 1)
										{
											if(($exam_details['pay_status'] == 2 || $exam_details['pay_status'] == '') && ($exam_details['exam_center_code']!='' &&  $exam_details['exam_medium']!='')) 
											{ 
												$row1 .= '<span class="hide">1</span>
												<input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="'.$exam_details['mem_examid'].' data-attr="'.$row['regid'].'"/>';
											}
										}
										$row1 .='</td>';
										$result[] = $row1;
										$result[] = '<td>'.$chkCnt.'</td>';
										$result[] = '<td>'.$row['batch_code'].'</td>';
										$result[] = '<td>'.$row['batch_name'].'</td>';
										$result[] = '<td>'.$row['regnumber'].'</td>';
										$result[] = '<td>'.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'].'</td>';
										$result[] = '<td>'.date("d-M-Y", strtotime($row['dateofbirth'])).'</td>';
										$result[] = '<td>'.$row['email'].'</td>';
										
										$row2 = '<td class="fee'.$row['regid'].'">';
										
										if($dispCheckboxFlag == 1)
										{
											if($exam_details['exam_fee'] <= 0 || $exam_details['exam_fee'] == ""){ $row2 .= '0.00'; } else { $row2 .= $exam_details['exam_fee']; }
										}
										else 
										{	
											if( $exam_details['pay_status'] == '0' ) { $row2 .= '0.00';} 
											else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row2 .= '0.00'; } 
											else if($exam_details['pay_status'] == '3') { $row2 .= $exam_details['exam_fee'];} 
										}
										$row2 .='</td>';
										$result[] = $row2;
										
										$row3 = '<td class="status'.$row['regid'].'">';
										if( $exam_details['pay_status'] == '0' ) { $row3 .= 'Fail';} 
										else if( $exam_details['pay_status'] == '2' ||  $exam_details['pay_status']=='') { $row3 .= 'Pending'; } 
										else if($exam_details['pay_status'] == '3') { $row3 .= 'Payment For Approve By IIBF';}
										$row3 .='</td>';
										$result[] = $row3;
										
										$row4 = '<td>';
										if(isset($exam_details['utr_no'])){ $row4 .= $exam_details['utr_no'];}
										$row4 .='</td>';
										$result[] = $row4;
										
										$row5 ='<td style="width: 117px;">';
										if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
										{ 
											$this->db->select('center_name');
											$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
											$center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$exam_details['exam_center_code']),'',array('center_name'=>'ASC'));
											if(count($center_name) > 0)
											{
												$row5 .= $center_name[0]['center_name'];
											}
										} 
										else { $row5 .= '-'; }
										
										$row5 .='<input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="'.set_value('center_code').'" autocomplete="off" readonly>
										</td>';                    
										$result[] = $row5;
										
										$row6 = '<td style="width: 117px;">';
										if($exam_details['pay_status']!='' && $dispCheckboxFlag == 1)
										{ 
											$this->db->select('medium_description');
											$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
											$medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$exam_details['exam_medium']));
											if(count($medium_name) > 0)
											{
												$row6 .= $medium_name[0]['medium_description'];
											}
										} else { $row6 .= '-'; }
										$row6 .='</td>';
										$result[] = $row6;
										
										$row7 = '<td>
										<input type="hidden" value="'.$batch_id.'" id="batch_id'.$row['regid'].'">
										<input type="hidden" value="'.$registrationtype.'" id="memtype'.$row['regid'].'">
										<input type="hidden" value="'.$training_from.'" id="training_from'.$row['regid'].'">
										<input type="hidden" value="'.$training_to.'" id="training_to'.$row['regid'].'">';
										
										if( $exam_details['pay_status'] == 0 || $exam_details['pay_status'] == 2 ) 
										{ 
											$row7 .='<a class="mbtn btn-xs btn-warning2" target="_blank" href="'.base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'].'">Edit</a>';
											
											if((isset($exam_details['exam_medium']) || isset($exam_details['exam_center_code'])) && $dispCheckboxFlag == 1)
											{
												if($exam_details['exam_medium'] != '' || $exam_details['exam_center_code'] != 0 )
												{ 
													$row7 .='<a href="javascript:void(0)" class="'.$row['regid'].' mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title="Clear Submit" onclick="clearexam('.$row['regid'].')" > | Clear</a>';
												}
											} 
										} else { $row7 .= '-';}
										$row7 .='</td>';
										$result[] = $row7;
										
										$data[] = $result;                    
									}
									
									if($chkCnt > $chk_val) { break; }
									$chkCnt++;
								}
								
								$for_all_cnt++;
							}// Foreach End               
						}            
						$result['ApplicantResponse'][] = $data;
						
						$new_i_val = $i_val+$dispLimit;
						$new_chk_val = $chk_val + $dispLimit;
						
						if($new_i_val < $total_form_data)
						{ 
							$disp_css = '';
							if($for_all_cnt < $total_form_data){ } else { $disp_css = 'style = " display:none; "'; }
							
							$onclickFunShowMore = "getTableDataAjax('".$new_i_val."', '".$new_chk_val."')";
							$result['ShowMoreBtn'] = '<br><div class="col-md-12 ButtonAllWebinars text-center" id="showMoreBtn" '.$disp_css.'>
							<a href="javascript:void(0)" class="btn btn-info" onclick="'.$onclickFunShowMore.'">Show More</a>				
							</div>';
						}
						else {  $result['ShowMoreBtn'] = ""; }
						$result['flag'] = "success";
						$result['total_form_data'] = $total_form_data;
						$result['chkCnt'] = $chkCnt;
					}
				}
				else
				{
					$result['flag'] = "error";
				}
			}
			else
			{
				$result['flag'] = "error";	
			}
			
			echo json_encode($result);
		}
		
		//get all candidates against exam
		public function allapplicants_bk_13_11_2020() {
			if( isset( $_GET['exCd'] ) ) {
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$examcode = trim( $_GET['exCd'] );
				$decdexamcode = base64_decode($examcode);
				if(!intval($decdexamcode))
				{
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				$decdexamcode = intval($decdexamcode);
				//check if exam exists or not
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));
				if( $examcount > 0 ) 
				{
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
					if( count($examact) > 0 ) {
						//$comp_currdate = date('Y-m-d H:i:s');
						//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
						//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
						
						$exam_period =  $examact[0]['exam_period'];
						$comp_currdate = date('Y-m-d');
						$comp_frmdate = $examact[0]['exam_from_date'];
						$comp_todate = $examact[0]['exam_to_date'];
						if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) 
						{
							$data['result'] = array();
							$per_page = 50;
							$last = $this->uri->total_segments();
							$start = 0;
							$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
							$searchText = '';
							$searchBy = '';
							$field = $value = $sortkey = $sortval = '';
							if($page!=0) {	
								$start = $page-1;	
							}
							$instdata = $this->session->userdata('dra_institute');
							$instcode = $instdata['institute_code'];
							
							$query = $this->db->query("SELECT a.batch_name,a.batch_code,a.agency_id,a.batch_from_date,a.batch_to_date,d.firstname,d.batch_id,d.inst_code, d.middlename, d.regid,d.lastname,d.regnumber, d.dateofbirth, d.email, d.registrationtype,d.state,d.pincode,d.city,d.district,d.qualification,d.scannedphoto,d.scannedsignaturephoto,d.idproof,d.idproofphoto,d.training_certificate,d.quali_certificate,d.image_path,ac.location_name
							FROM agency_batch a
							LEFT JOIN dra_members d
							ON a.id = d.batch_id AND d.inst_code = $instcode  
							
							
							LEFT JOIN agency_center ac
							ON a.center_id = ac.center_id
							
							where d.isdeleted = 0 AND d.batch_id != 0  AND d.re_attempt < 3 AND d.inst_code = $instcode   AND a.agency_id = $agency_id  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
							FROM   dra_eligible_master el
							WHERE el.exam_status IN('F','P') AND el.member_no = d.regnumber AND el.member_no !='') 
							order by d.regid DESC 
							
							") ;
							
							$res = $query->result_array();
							// print_r($res); die;
							echo $this->db->last_query();die;
							
							//fetch fail member record from eligible master 
							
							$eligible = $this->db->query("SELECT e.*,d.regnumber,d.firstname,d.middlename,d.lastname,d.dateofbirth, d.email, d.registrationtype,d.batch_id,d.excode,d.re_attempt,d.inst_code,d.regid,d.state,d.pincode,d.city,d.district,d.qualification,d.scannedphoto,d.scannedsignaturephoto,d.idproof,d.idproofphoto,d.training_certificate,d.quali_certificate,d.image_path,a.batch_to_date,a.batch_from_date,a.batch_name,a.batch_code,s.exam_date,em.trg_value
							FROM dra_eligible_master e
							LEFT JOIN dra_members d
							ON e.member_no = d.regnumber
							AND e.exam_code = d.excode
							
							
							
							LEFT JOIN agency_batch a
							ON a.id = d.batch_id
							
							LEFT JOIN dra_subject_master s
							ON s.exam_code = e.exam_code
							
							LEFT JOIN dra_misc_master em
							ON em.exam_code = e.exam_code        
							
							where d.isdeleted = 0 AND d.batch_id != 0 AND e.app_category != '' AND d.re_attempt < 3 AND d.inst_code = $instcode  AND e.exam_code = $decdexamcode AND e.exam_status = 'F' AND a.batch_status='A' AND e.member_no !=''
							GROUP BY s.exam_code, e.member_no
							order by d.regid desc");
							// echo $this->db->last_query();die;
							$eligible_result1 = $eligible->result_array();
							//  $resultc = array_merge($res,$eligible_result); 
							//print_r($eligible_result1); die;
							// echo $this->db->last_query();die;
							
							
							
							
							$resultarr = array();
							if( $res ) {
								foreach( $res as $result ) {
									//if( $result['pay_status'] != 1 ) { //do not include applicants in listing whoes payment is successful
									
									$result['utr_no'] = '';
									$resultarr[] = $result; 
									// if( $result['pay_status'] == 3 || $result['pay_status']=='NULL') { //if payment mode is NEFT and pending for approval by iibf
									// 	$memexamid = $result['mem_examid'];
									// 	//added for neft case
									// 	$this->db->order_by("ptid", "desc");
									// 	$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
									// 	if( $transid ) {
									// 		$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');	
									// 		$result['utr_no'] = $utrno;
									// 		$resultarr[] = $result; 
									// 	}
									// } else { // pay_status is fail-0 or pending-2
									// 	$result['utr_no'] = '';
									// 	$resultarr[] = $result; 
									// }
									//} 	
								}	
							}
							
							
							$eligible_result = array();
							if( $eligible_result1 ) {
								foreach( $eligible_result1 as $result ) {
									//if( $result['pay_status'] != 1 ) { //do not include applicants in listing whoes payment is successful
									$result['utr_no'] = '';
									$resultarr[] = $result; 
									// if( $result['pay_status'] == 3 || $result['pay_status']=='NULL') { //if payment mode is NEFT and pending for approval by iibf
									// 	$memexamid = $result['mem_examid'];
									// 	//added for neft case
									// 	$this->db->order_by("ptid", "desc");
									// 	$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
									// 	if( $transid ) {
									// 		$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');	
									// 		$result['utr_no'] = $utrno;
									// 		$eligible_result[] = $result; 
									// 	}
									// } else { // pay_status is fail-0 or pending-2
									// 	$result['utr_no'] = '';
									// 	$eligible_result[] = $result; 
									// }
									//} 	
								}	
							}
							/*	echo '<pre>';
								print_r($resultarr);
							exit;*/
							//echo $decdexamcode; die;
							
							
							$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
							$medium_master = $this->master_model->getRecords('dra_medium_master',array('dra_medium_master.exam_code'=>$decdexamcode));
							//echo $this->db->last_query();exit;
							$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
							$center_master = $this->master_model->getRecords('dra_center_master',array('exam_name'=>$decdexamcode),'',array('center_name'=>'ASC'));
							$data['startidx'] = 1;
							/* Removed pagination on 21-01-2017 */ 
							$data['info'] = $data['links'] = '';
							
							//$data['result'] = $resultarr;
							//$data['eligible'] = $eligible_result;
							$data['eligible']=array_merge($resultarr,$eligible_result);
							//print_r($data['eligible']); die;
							$data['middle_content']	= 'draexam_candidates';
							$data['medium_master'] = $medium_master;
							$data['center_master'] = $center_master;
							$data['examperiods']	= $examact[0]['exam_period'];;
							$data['examcode']	= $decdexamcode;
							/* send active exams for display in sidebar */
							//$this->db->select('a.id,a.description,a.exam_code');
							$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
							$res = $this->master_model->getRecords("dra_exam_master a");
							$data['active_exams'] = $res;
							$this->load->view('iibfdra/common_view',$data);
						} 
						else 
						{//if exam is not active
							$this->session->set_flashdata('error','This exam is not active');
							redirect(base_url().'iibfdra/InstituteHome/dashboard');	
						}
					} 
					else 
					{ //if exam not found in exam activation master then redirect to home
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					}
				} 
				else 
				{ // if exam does not exists redirect to dashboard
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				
			} 
			else 
			{
				$this->session->set_flashdata('error','URL is edited. Please try again');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		}
		
		//get all candidates against exam
		public function exam_182() {
			if( isset( $_GET['exCd'] ) ) {
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				$examcode = trim( $_GET['exCd'] );
				$decdexamcode = base64_decode($examcode);
				if(!intval($decdexamcode))
				{
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				$decdexamcode = intval($decdexamcode);
				//check if exam exists or not
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));
				if( $examcount > 0 ) {
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
					if( count($examact) > 0 ) {
						//$comp_currdate = date('Y-m-d H:i:s');
						//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
						//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
						
						$exam_period =  $examact[0]['exam_period'];
						$comp_currdate = date('Y-m-d');
						$comp_frmdate = $examact[0]['exam_from_date'];
						$comp_todate = $examact[0]['exam_to_date'];
						if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) 
						{
							$data['result'] = array();
							$per_page = 50;
							$last = $this->uri->total_segments();
							$start = 0;
							$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
							$searchText = '';
							$searchBy = '';
							$field = $value = $sortkey = $sortval = '';
							if($page!=0) {	
								$start = $page-1;	
							}
							$instdata = $this->session->userdata('dra_institute');
							$instcode = $instdata['institute_code'];
							
							$query = $this->db->query("SELECT e.regid as eregid, a.batch_name,a.batch_code,a.agency_id,a.batch_from_date,a.batch_to_date,d.firstname,d.batch_id,d.inst_code, d.middlename, d.regid,d.lastname,d.regnumber, d.dateofbirth, d.email, d.registrationtype,d.state,d.pincode,d.city,d.district,d.qualification,d.scannedphoto,d.scannedsignaturephoto,d.idproof,d.idproofphoto,d.training_certificate,d.quali_certificate,d.image_path,ac.location_name,e.exam_center_code, e.exam_fee, e.pay_status, e.id as mem_examid, e.created_on as ecreated_on,e.exam_medium,e.batchId,e.exam_period
							FROM agency_batch a
							LEFT JOIN dra_members d
							ON a.id = d.batch_id 
							
							
							LEFT JOIN dra_member_exam e
							ON e.id = (                                
							SELECT em.id FROM dra_member_exam em
							WHERE d.regid=em.regid
							ORDER BY em.id DESC LIMIT 1
							)
							
							LEFT JOIN agency_center ac
							ON a.center_id = ac.center_id
							
							where d.isdeleted = 0 AND d.batch_id != 0  AND d.inst_code = 182  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
							FROM   dra_eligible_master el
							WHERE el.exam_status='F' AND el.member_no = d.regnumber AND el.member_no != '' ) 
							
							order by d.regid DESC 
							limit 20
							") ;
							
							$res = $query->result_array();
							// print_r($res); die;
							//echo $this->db->last_query();die;
							
							//fetch fail member record from eligible master 
							
							
							
							
							
							$resultarr = array();
							if( $res ) {
								foreach( $res as $result ) {
									//if( $result['pay_status'] != 1 ) { //do not include applicants in listing whoes payment is successful
									if( $result['pay_status'] == 3 || $result['pay_status']=='NULL') { //if payment mode is NEFT and pending for approval by iibf
										$memexamid = $result['mem_examid'];
										//added for neft case
										$this->db->order_by("ptid", "desc");
										$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
										if( $transid ) {
											$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');	
											$result['utr_no'] = $utrno;
											$resultarr[] = $result; 
										}
										} else { // pay_status is fail-0 or pending-2
										$result['utr_no'] = '';
										$resultarr[] = $result; 
									}
									//} 	
								}	
							}
							
							
							
							/*	echo '<pre>';
								print_r($resultarr);
							exit;*/
							//echo $decdexamcode; die;
							
							
							$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
							$medium_master = $this->master_model->getRecords('dra_medium_master',array('dra_medium_master.exam_code'=>$decdexamcode));
							//echo $this->db->last_query();exit;
							$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
							$center_master = $this->master_model->getRecords('dra_center_master',array('exam_name'=>$decdexamcode),'',array('center_name'=>'ASC'));
							$data['startidx'] = 1;
							/* Removed pagination on 21-01-2017 */ 
							$data['info'] = $data['links'] = '';
							
							$data['eligible'] = $resultarr;
							
							//print_r($data['eligible']); die;
							$data['middle_content']	= 'draexam_candidates';
							$data['medium_master'] = $medium_master;
							$data['center_master'] = $center_master;
							$data['examperiods']	= $examact[0]['exam_period'];;
							$data['examcode']	= $decdexamcode;
							/* send active exams for display in sidebar */
							$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
							$res = $this->master_model->getRecords("dra_exam_master a");
							$data['active_exams'] = $res;
							$this->load->view('iibfdra/common_view',$data);
							} else {//if exam is not active
							$this->session->set_flashdata('error','This exam is not active');
							redirect(base_url().'iibfdra/InstituteHome/dashboard');	
						}
						} else { //if exam not found in exam activation master then redirect to home
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					}
					} else { // if exam does not exists redirect to dashboard
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				
				} else {
				$this->session->set_flashdata('error','URL is edited. Please try again');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		}
		
		// edit candidate details
		public function editApplicant()
		{
			$data = array();
			$data['examRes'] = array();
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);
			$decdexamcode =$_SESSION['excode'];
			
			//check if id is integer in url if not regdirect to home
			if(!intval($id)) 
			{
				$this->session->set_flashdata('error','No such applicant exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			
			$id = intval($id);
			$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_period, dra_exam_mode');
			
			$this->db->select('agency_batch.*,dra_members.*,agency_center.center_id,agency_center.location_name,city_master.*,dra_member_exam.exam_medium,dra_member_exam.exam_center_code');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
			$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
			$this->db->join('dra_member_exam','dra_member_exam.regid=dra_members.regid AND dra_member_exam.exam_period='.$examact[0]['exam_period'],'LEFT');
			$examRes = $this->master_model->getRecords('dra_members',array('dra_members.regid'=>$id,'dra_members.isdeleted' => 0));
			//print_r( $this->db->last_query() ); die();
			//print_r($examRes); die;
			if(count($examRes))
			{
				//print_r($examRes[0]); die;
				$data['examRes'] = $examRes[0];
				
				} else { //check entered id details are present in db if not redirect to home
				$this->session->set_flashdata('error','No such applicant exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			
			
			$data['chk_exam_mode'] = $chk_exam_mode = $examact[0]['dra_exam_mode']; //RPE or PHYSICAL
			if(isset($_POST['btnSubmit']))
			{				
				$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
				$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
				$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
				$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[50]');
				$this->form_validation->set_rules('addressline2','Address Line2','trim|max_length[50]');
				$this->form_validation->set_rules('city','City','trim|required|max_length[30]');
				$this->form_validation->set_rules('district','District','trim|required|max_length[30]');
				$this->form_validation->set_rules('state','State','trim|required');
				$this->form_validation->set_rules('pincode','Pin Code','trim|required|max_length[6]');
				$this->form_validation->set_rules('dob1','Date of Birth','trim|required');
				$this->form_validation->set_rules('gender','Gender','required');
				$this->form_validation->set_rules('mobile','Mobile No.','required|max_length[10]|min_length[10]');
				$this->form_validation->set_rules('email','Email','valid_email|required|trim');
				$this->form_validation->set_rules('exam_center','Exam Center','required|trim');
				$this->form_validation->set_rules('exam_medium','Exam medium','required|trim');
				$this->form_validation->set_rules('edu_quali','Qualification','required');
				$this->form_validation->set_rules('idproof','Id Proof','required');
				$this->form_validation->set_rules('declaration1','Declaration','required');
				//$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt');
				$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
				$this->form_validation->set_rules('phone','Phone No','max_length[8]');
				
				/********* START : FOR RPE MODE *************************/
				if($chk_exam_mode == 'RPE')
				{
					$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
					$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
					$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
				}
				/********* END : FOR RPE MODE *************************/
				
				if($this->form_validation->run()==TRUE)
				{
					$outputphoto1 = $outputsign1 = $outputidproof1 = $outputtcertificate1 = $outputqualicertificate1 = '';
					$photofnm = $signfnm = $idfnm = $trgfnm = $qualifnm = '';
					$photo_flg = $signature_flg = $id_flg = $tcertificate_flg = $qualicertificate_flg = 'N';
					
					$date = date('Y-m-d h:i:s');
					
					$image_size_error = 0;
					$image_size_error_message = array();
					
					//if( !empty($input) ) {
					if($this->input->post('hiddenphoto') != '')
					{
						$size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
						if($size)
						{
							$input = $this->input->post('hiddenphoto');
							
							$tmp_nm = strtotime($date).rand(0,100);
							$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
							$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
							file_put_contents($outputphoto, file_get_contents($input));
							$photofnm = "p_".$tmp_nm.".jpg";
							$photo_flg = 'Y';
						}
						else
						{
							$photofnm = $this->input->post('hiddenphoto');
						}
					}
					
					// generate dynamic scan signature
					
					//if( !empty($inputsignature) ) {
					if($this->input->post('hiddenscansignature') != '')
					{
						$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
						if($size)
						{
							$inputsignature = $_POST["hiddenscansignature"];
							
							$tmp_signnm = strtotime($date).rand(0,100);
							$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
							$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
							file_put_contents($outputsign, file_get_contents($inputsignature));
							$signfnm = "s_".$tmp_signnm.".jpg";
							$signature_flg = 'Y';
						}
						else
						{
							$signfnm = $this->input->post('hiddenscansignature');
						}
					}
					
					// generate dynamic id proof
					
					//if( !empty($inputidproofphoto) ) {
					if($this->input->post('hiddenidproofphoto') != '')
					{
						$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
						if($size)
						{
							$inputidproofphoto = $_POST["hiddenidproofphoto"];
							
							$tmp_inputidproof = strtotime($date).rand(0,100);
							$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
							$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
							file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
							$idfnm = "pr_".$tmp_inputidproof.".jpg";
							$id_flg = 'Y';
						}
						else
						{
							$idfnm = $this->input->post('hiddenidproofphoto');
						}
					}
					
					// generate dynamic training certificate 
					
					//if( !empty($input_tcertificatephoto) ) {
					
					/*if($this->input->post('hiddentrainingcertificate') != '')
						{
						$size = @getimagesize($_FILES['trainingcertificate']['tmp_name']);
						if($size)
						{
						$input_tcertificatephoto = $_POST["hiddentrainingcertificate"];
						
						$tmp_tcertificate = strtotime($date).rand(0,100);
						$outputtcertificate = getcwd()."/uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
						$outputtcertificate1 = base_url()."uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
						file_put_contents($outputtcertificate, file_get_contents($input_tcertificatephoto));
						$trgfnm = "traing_".$tmp_tcertificate.".jpg";
						$tcertificate_flg = 'Y';
						}
						else
						{
						$trgfnm = $this->input->post('hiddentrainingcertificate');
						}
					}*/
					
					// generate dynamic qualification certificate
					
					//if( !empty($input_qualicertificate) ) {
					if($this->input->post('hiddenqualicertificate') != '')
					{
						$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
						if($size)
						{
							$input_qualicertificate = $_POST["hiddenqualicertificate"];
							
							$tmp_qualicertificate = strtotime($date).rand(0,100);
							$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
							$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
							file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
							$qualifnm = "degre_".$tmp_qualicertificate.".jpg";
							$qualicertificate_flg = 'Y';
						}
						else
						{
							$qualifnm =$this->input->post('hiddenqualicertificate'); 	
						}
					}
					// eof file upload code
					
					// check if invalid image error
					
					//eof code
					
					/********* START : FOR RPE MODE *************************/
					if($chk_exam_mode == 'RPE')
					{
						$subject_arr=array();
						$venue=$this->input->post('venue');
						$date=$this->input->post('date');
						$time=$this->input->post('time');
						if(count($venue) >0 && count($date) && count($time) >0)	
						{ 
							foreach($venue as $k=>$v)
							{
								$compulsory_subjects_name=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$decdexamcode,'subject_delete'=>'0','group_code'=>'C','subject_code'=>$k),'subject_description');
								$subject_arr[$k]=array('venue'=>$v,'date'=>$date[$k],'session_time'=>$time[$k],'subject_name'=>$compulsory_subjects_name[0]['subject_description']);
							}
							
							#########check duplication of venue,date,time##########	
							$sub_flag=1;
							if(count($subject_arr) > 0)
							{	
								$msg='';
								$sub_capacity=1;
								foreach($subject_arr as $k=>$v)
								{
									foreach($subject_arr as $j=>$val)
									{
										if($k!=$j)
										{
											if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											{
												$sub_flag=0;
											}
										}
									}
									
									$capacity=dra_check_capacity($v['venue'],$v['date'],$v['session_time'],$_POST['exam_center']);
									if($capacity==0)
									{
										#########get message if capacity is full##########
										$msg=dra_getVenueDetails($v['venue'],$v['date'],$v['session_time'],$_POST['exam_center']);
									}
									
									if($msg!='')
									{
										
										$this->session->set_flashdata('error',$msg);
										redirect(base_url().'/iibfdra/TrainingBatches/editApplicant/'.$id);
									}
								}
							}
							
							if($sub_flag==0)
							{
								$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
								redirect(base_url().'/iibfdra/TrainingBatches/editApplicant/'.$id);
							}
						}
						
						$center_data=array(
						'excd'=>$decdexamcode,
						'txtCenterCode'=>$_POST['exam_center'],
						'subject_arr'=>$subject_arr,
						);
						$this->session->set_userdata('center_examinfo',$center_data);
					}
					/********* END : FOR RPE MODE *************************/					
					
					$dmemexam_id = $this->input->post('dmemexam_id');
					//'training_certificate' => $trgfnm, removed by Manoj
					$update_data = array(	
					'namesub' => $this->input->post('sel_namesub'),
					'firstname'		=>$this->input->post('firstname'),
					'middlename'		=>$this->input->post('middlename'),
					'lastname'		=>$this->input->post('lastname'),
					'address1'		=>$this->input->post('addressline1'),
					'address2'		=>$this->input->post('addressline2'),
					'address3'		=> $this->input->post('addressline3'),
					'address4'		=> $this->input->post('addressline4'),
					'city'				=>$this->input->post('city'),
					'state'				=>$this->input->post('state'),
					'district'			=>$this->input->post('district'),
					'pincode'				=>$this->input->post('pincode'),
					'dateofbirth'				=>$this->input->post('dob1'),
					'gender'				=>$this->input->post('gender'),
					'stdcode'			=>$this->input->post('stdcode'),
					'phone'			=>$this->input->post('phone'),
					'mobile'	=>$this->input->post('mobile'),
					'aadhar_no'	=> $this->input->post('aadhar_no'),	// added by Bhagwan Sahane, on 06-05-2017
					
					'email' => $this->input->post('email'),
					'qualification'	=>$this->input->post('edu_quali'),
					'idproof'	=>$this->input->post('idproof'),
					
					'scannedphoto' 	=> $photofnm,
					'scannedsignaturephoto' => $signfnm,
					'idproofphoto' 	=> $idfnm,
					
					'quali_certificate' => $qualifnm,
					'photo_flg' 	=> $photo_flg,
					'signature_flg' => $signature_flg,
					'id_flg' 		=> $id_flg,
					'tcertificate_flg' => $tcertificate_flg,
					'qualicertificate_flg' => $qualicertificate_flg 
					
					);
					//print_r($update_data);
					$regid = $examRes[0]['regid'];
					$batchId = $examRes[0]['batch_id'];
					$registrationtype = $examRes[0]['registrationtype'];
					$exam_medium=$this->input->post('exam_medium');
					$exam_center=$this->input->post('exam_center');
					$training_from=$examRes[0]['batch_from_date'];
					$training_to=$examRes[0]['batch_to_date'];
					
					$exam_status = $this->ApplyExam($regid,$batchId,$registrationtype,$decdexamcode,$exam_medium,$exam_center,$training_from,$training_to, $chk_exam_mode);
					
					//echo 'exam_status : '.$exam_status; exit;
						
					if($exam_status ==1)
					{						
						if($this->master_model->updateRecord('dra_members',$update_data,  array('regid'=>$regid)))
						{
							$desc['updated_data'] = $update_data;
							$desc['old_data'] = $examRes[0];
							log_dra_user($log_title = "Edit Applicant Successful", $log_message = serialize($desc));
							$this->session->set_flashdata('success','Record updated successfully');
							//redirect(base_url().'iibfdra/DraExam/edit/'.$dmemexam_id);
							redirect(base_url().$_SESSION['reffer']);
						}
						else
						{
							$desc['updated_data'] = $update_data;
							$desc['old_data'] = $examRes[0];
							log_dra_user($log_title = "Edit Applicant Unsuccessful", $log_message = serialize($desc));
							$this->session->set_flashdata('error','Error occured while updating record');
							redirect(base_url().'iibfdra/TrainingBatches/editApplicant/'.$regid);
						}
					}
					else
					{
						$this->session->set_flashdata('error','Error occurred while updating record');
						redirect(base_url().'iibfdra/TrainingBatches/editApplicant/'.$regid);
					}
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}
			}
			$this->db->select('id,state_code,state_name');
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			$this->db->where('city_master.city_delete', '0');
			$cities = $this->master_model->getRecords('city_master');
			
			//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
			$this->db->not_like('name','Election Voters card');
			$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
			
			$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
			$medium_master = $this->master_model->getRecords('dra_medium_master',array('dra_medium_master.exam_code'=>$decdexamcode));
			//echo $this->db->last_query();exit;
			$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
			$center_master = $this->master_model->getRecords('dra_center_master',array('exam_name'=>$decdexamcode),'',array('center_name'=>'ASC'));
			
			/********* START : FOR RPE MODE *************************/
			if($chk_exam_mode == 'RPE')
			{
				// query to get center deatil for selection start			
				$this->db->group_by('exam_code');
				$compulsory_subjects=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$decdexamcode,'subject_delete'=>'0','group_code'=>'C'),'',array('subject_code'=>'ASC'));
				// query to get center deatil for selection end
				$data['compulsory_subjects'] = $compulsory_subjects;
			}
			/********* END : FOR RPE MODE *************************/
			
			$data['medium_master'] = $medium_master;
			$data['center_master'] = $center_master; 
			
			$data['states'] = $states;
			$data['cities'] = $cities;
			$data['idtype_master'] = $idtype_master;
			
			
			$data["middle_content"] = 'dracandidate_edit_sm';
			$this->load->helper('captcha');
			$this->session->unset_userdata("draexamedtcaptcha");
			$this->session->set_userdata("draexamedtcaptcha", rand(1, 100000));
			$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => '../../../uploads/applications/',
			);
			$cap = create_captcha($vals);
			$_SESSION["draexamedtcaptcha"] = $cap['word']; 
			$data['image'] = $cap['image'];
			//get exam date and training period limit from subject master and misc master
			//$this->db->select('a.id,a.description,a.exam_code');
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);
		}
		
		// edit candidate details
		public function editCandidate()
		{
			$data = array();
			$data['examRes'] = array();
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);
			// $decdexamcode =$_SESSION['excode'];
			
			//check if id is integer in url if not regdirect to home
			if(!intval($id)) {
				$this->session->set_flashdata('error','No such applicant exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			$id = intval($id);
			// $examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_period');
			$this->db->select('agency_batch.*,dra_members.*,agency_center.center_id,agency_center.location_name,city_master.*,dra_member_exam.exam_medium,dra_member_exam.exam_center_code');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
			$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
			$this->db->join('dra_member_exam','dra_member_exam.regid=dra_members.regid','LEFT');
			$examRes = $this->master_model->getRecords('dra_members',array('dra_members.regid'=>$id,'dra_members.isdeleted' => 0));
			//print_r( $this->db->last_query() ); die();
			//print_r($examRes); die;
			if(count($examRes))
			{
				//print_r($examRes[0]); die;
				$data['examRes'] = $examRes[0];
				
				} else { //check entered id details are present in db if not redirect to home
				$this->session->set_flashdata('error','No such applicant exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			if(isset($_POST['btnSubmit']))
			{
				
				$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
				$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
				$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
				$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[50]');
				$this->form_validation->set_rules('addressline2','Address Line2','trim|max_length[50]');
				$this->form_validation->set_rules('city','City','trim|required|max_length[30]');
				$this->form_validation->set_rules('district','District','trim|required|max_length[30]');
				$this->form_validation->set_rules('state','State','trim|required');
				$this->form_validation->set_rules('pincode','Pin Code','trim|required|max_length[6]');
				$this->form_validation->set_rules('dob1','Date of Birth','trim|required');
				$this->form_validation->set_rules('gender','Gender','required');
				$this->form_validation->set_rules('mobile','Mobile No.','required|max_length[10]|min_length[10]');
				$this->form_validation->set_rules('email','Email','valid_email|required|trim');
				$this->form_validation->set_rules('edu_quali','Qualification','required');
				$this->form_validation->set_rules('idproof','Id Proof','required');
				$this->form_validation->set_rules('declaration1','Declaration','required');
				//$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt');
				$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
				$this->form_validation->set_rules('phone','Phone No','max_length[8]');
				
				if($this->form_validation->run()==TRUE)
				{
					$outputphoto1 = $outputsign1 = $outputidproof1 = $outputtcertificate1 = $outputqualicertificate1 = '';
					$photofnm = $signfnm = $idfnm = $trgfnm = $qualifnm = '';
					$photo_flg = $signature_flg = $id_flg = $tcertificate_flg = $qualicertificate_flg = 'N';
					
					$date = date('Y-m-d h:i:s');
					
					$image_size_error = 0;
					$image_size_error_message = array();
					
					//if( !empty($input) ) {
					if($this->input->post('hiddenphoto') != '')
					{
						$size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
						if($size)
						{
							$input = $this->input->post('hiddenphoto');
							
							$tmp_nm = strtotime($date).rand(0,100);
							$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
							$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
							file_put_contents($outputphoto, file_get_contents($input));
							$photofnm = "p_".$tmp_nm.".jpg";
							$photo_flg = 'Y';
						}
						else
						{
							$photofnm = $this->input->post('hiddenphoto');
						}
					}
					
					// generate dynamic scan signature
					
					//if( !empty($inputsignature) ) {
					if($this->input->post('hiddenscansignature') != '')
					{
						$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
						if($size)
						{
							$inputsignature = $_POST["hiddenscansignature"];
							
							$tmp_signnm = strtotime($date).rand(0,100);
							$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
							$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
							file_put_contents($outputsign, file_get_contents($inputsignature));
							$signfnm = "s_".$tmp_signnm.".jpg";
							$signature_flg = 'Y';
						}
						else
						{
							$signfnm = $this->input->post('hiddenscansignature');
						}
					}
					
					// generate dynamic id proof
					
					//if( !empty($inputidproofphoto) ) {
					if($this->input->post('hiddenidproofphoto') != '')
					{
						$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
						if($size)
						{
							$inputidproofphoto = $_POST["hiddenidproofphoto"];
							
							$tmp_inputidproof = strtotime($date).rand(0,100);
							$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
							$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
							file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
							$idfnm = "pr_".$tmp_inputidproof.".jpg";
							$id_flg = 'Y';
						}
						else
						{
							$idfnm = $this->input->post('hiddenidproofphoto');
						}
					}
					
					// generate dynamic training certificate 
					
					//if( !empty($input_tcertificatephoto) ) {
					/*
						// commented by manoj
						if($this->input->post('hiddentrainingcertificate') != '')
						{
						$size = @getimagesize($_FILES['trainingcertificate']['tmp_name']);
						if($size)
						{
						$input_tcertificatephoto = $_POST["hiddentrainingcertificate"];
						
						$tmp_tcertificate = strtotime($date).rand(0,100);
						$outputtcertificate = getcwd()."/uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
						$outputtcertificate1 = base_url()."uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
						file_put_contents($outputtcertificate, file_get_contents($input_tcertificatephoto));
						$trgfnm = "traing_".$tmp_tcertificate.".jpg";
						$tcertificate_flg = 'Y';
						}
						else
						{
						$trgfnm = $this->input->post('hiddentrainingcertificate');
						}
					}*/
					
					// generate dynamic qualification certificate
					
					//if( !empty($input_qualicertificate) ) {
					if($this->input->post('hiddenqualicertificate') != '')
					{
						$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
						if($size)
						{
							$input_qualicertificate = $_POST["hiddenqualicertificate"];
							
							$tmp_qualicertificate = strtotime($date).rand(0,100);
							$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
							$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
							file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
							$qualifnm = "degre_".$tmp_qualicertificate.".jpg";
							$qualicertificate_flg = 'Y';
						}
						else
						{
							$qualifnm =$this->input->post('hiddenqualicertificate'); 	
						}
					}
					// eof file upload code
					
					// check if invalid image error
					
					//eof code
					
					$dmemexam_id = $this->input->post('dmemexam_id');
					// 'training_certificate' => $trgfnm, removed by manoj
					$update_data = array(	
					'namesub' => $this->input->post('sel_namesub'),
					'firstname'		=>$this->input->post('firstname'),
					'middlename'		=>$this->input->post('middlename'),
					'lastname'		=>$this->input->post('lastname'),
					'address1'		=>$this->input->post('addressline1'),
					'address2'		=>$this->input->post('addressline2'),
					'address3'		=> $this->input->post('addressline3'),
					'address4'		=> $this->input->post('addressline4'),
					'city'				=>$this->input->post('city'),
					'state'				=>$this->input->post('state'),
					'district'			=>$this->input->post('district'),
					'pincode'				=>$this->input->post('pincode'),
					'dateofbirth'				=>$this->input->post('dob1'),
					'gender'				=>$this->input->post('gender'),
					'stdcode'			=>$this->input->post('stdcode'),
					'phone'			=>$this->input->post('phone'),
					'mobile'	=>$this->input->post('mobile'),
					'aadhar_no'	=> $this->input->post('aadhar_no'),	// added by Bhagwan Sahane, on 06-05-2017
					
					'email' => $this->input->post('email'),
					'qualification'	=>$this->input->post('edu_quali'),
					'idproof'	=>$this->input->post('idproof'),
					
					'scannedphoto' 	=> $photofnm,
					'scannedsignaturephoto' => $signfnm,
					'idproofphoto' 	=> $idfnm,
					
					'quali_certificate' => $qualifnm,
					'photo_flg' 	=> $photo_flg,
					'signature_flg' => $signature_flg,
					'id_flg' 		=> $id_flg,
					'tcertificate_flg' => $tcertificate_flg,
					'qualicertificate_flg' => $qualicertificate_flg 
					
					);
					//print_r($update_data);
					
					$regid = $examRes[0]['regid'];
					if($this->master_model->updateRecord('dra_members',$update_data,  array('regid'=>$regid)))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examRes[0];
						log_dra_user($log_title = "Edit Applicant Successful", $log_message = serialize($desc));
						$this->session->set_flashdata('success','Record updated successfully');
						//redirect(base_url().'iibfdra/DraExam/edit/'.$dmemexam_id);
						redirect(base_url().$_SESSION['reffer']);
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $examRes[0];
						log_dra_user($log_title = "Edit Applicant Unsuccessful", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating record');
						redirect(base_url().'iibfdra/TrainingBatches/editApplicant/'.$regid);
					}
					
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}
			}
			
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			$this->db->where('city_master.city_delete', '0');
			$cities = $this->master_model->getRecords('city_master');
			
			//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
			$this->db->not_like('name','Election Voters card');
			$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
			
			
			
			$data['states'] = $states;
			$data['cities'] = $cities;
			$data['idtype_master'] = $idtype_master;
			
			
			$data["middle_content"] = 'dracandidate_update';
			$this->load->helper('captcha');
			$this->session->unset_userdata("draexamedtcaptcha");
			$this->session->set_userdata("draexamedtcaptcha", rand(1, 100000));
			$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => '../../../uploads/applications/',
			);
			$cap = create_captcha($vals);
			$_SESSION["draexamedtcaptcha"] = $cap['word']; 
			$data['image'] = $cap['image'];
			//get exam date and training period limit from subject master and misc master
			
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);
		}
		
		##FUNCTION ADDED BY SAGAR ON 18-09-2020
		public function deleteCandidate($regid=0)
		{
			if($regid != "" && $regid != 0)
			{
				$dra_members_old_data = $this->master_model->getRecords('dra_members',array('regid'=>$regid));
				$dra_members_batch_history_old_data = $this->master_model->getRecords('agency_member_batch_history',array('regid'=>$regid));
				
				$this->master_model->deleteRecord('dra_members','regid',$regid);
				
				$this->db->where('regid',$regid);
				$this->db->limit(1);
				$this->db->order_by('id','DESC');
				$this->db->delete('agency_member_batch_history');
				
				log_dra_user($log_title = "Dra Candidate Deleted Successful", $log_message = serialize($dra_members_old_data[0]));
				log_dra_user($log_title = "Dra Candidate Batch History Deleted Successful", $log_message = serialize($dra_members_batch_history_old_data[0]));
				$this->session->set_flashdata('success','Candidate deleted successfully');
				redirect(base_url().'iibfdra/TrainingBatches/allcandidates');
			}
		}		
		
		public function viewApplicant(){
			$data = array();
			$data['examRes'] = array();
			$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);$last = $this->uri->total_segments();
			$id = $this->uri->segment($last);
			//check if id is integer in url if not regdirect to home
			if(!intval($id)) {
				$this->session->set_flashdata('error','No such applicant exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			$id = intval($id);
			$this->db->select('agency_batch.*,dra_members.*,agency_center.center_id,agency_center.location_name,city_master.city_name,state_master.state_name');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id', 'left');
			$this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');
			$this->db->join('state_master','state_master.state_code=dra_members.state','LEFT');
			$examRes = $this->master_model->getRecords('dra_members',array('regid'=>$id,'isdeleted' => 0));
			//print_r( $this->db->last_query() ); die();
			//print_r($examRes); die;
			if(count($examRes))
			{
				//print_r($examRes[0]); die;
				$data['examRes'] = $examRes[0];
				} else { //check entered id details are present in db if not redirect to home
				$this->session->set_flashdata('error','No such applicant exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			
			//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
			$this->db->not_like('name','Election Voters card');
			$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
			
			
			$data['states'] = $states;
			$data['idtype_master'] = $idtype_master;
			
			
			$data["middle_content"] = 'dracandidate_view';
			$this->load->helper('captcha');
			$this->session->unset_userdata("draexamedtcaptcha");
			$this->session->set_userdata("draexamedtcaptcha", rand(1, 100000));
			$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => '../../../uploads/applications/',
			);
			$cap = create_captcha($vals);
			$_SESSION["draexamedtcaptcha"] = $cap['word']; 
			$data['image'] = $cap['image'];
			//get exam date and training period limit from subject master and misc master
			
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);
		}
		
		
		//update candidate details againt exam
		public function ApplyExam($regid=NULL,$batchId=NULL,$registrationtype=NULL,$examcode=NULL,$exam_medium=NULL,$exam_center=NULL,$training_from=NULL,$training_to=NULL, $chk_exam_mode=NULL)
		{
			// $regid =$this->input->post('regid');
			// $batchId = $this->input->post('batchId');
			// $registrationtype = $this->input->post('memtype');
			// $examcode =$this->input->post('examcode');
			// $exam_medium =$this->input->post('exam_medium');
			// $exam_center =$this->input->post('exam_center');
			// $training_from =$this->input->post('training_from');
			// $training_to = $this->input->post('training_to');
			
			//echo $examcode; die;
			// echo json_encode($examcode); die;
			$data = array();
			
			// if(!empty($regid)) {
			// 	$this->session->set_flashdata('error','No such applicant exists');
			// 	redirect(base_url().'iibfdra/InstituteHome/dashboard');
			// }
			$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $examcode));
			if( $examcount > 0 ) {
				//check if exam is active or not
				$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time');
			}
			
			if($regid)
			{				
				/* Start Code to dyanamic fees - Bhushan */
				// Check the member is fresh or from eligible master 
				$GetRegnumber = "";
				$checkMemberFrom = $this->master_model->getRecords('dra_members', array('regid' => $regid, 'batch_id' => $batchId),'regnumber,inst_code');
				/* echo $this->db->last_query(); */
				$GetRegnumber = $checkMemberFrom[0]['regnumber'];
				$institute_code = $checkMemberFrom[0]['inst_code'];
				//echo $GetRegnumber; 
				// Get Group Code
				$group_code = "B1";
				if($GetRegnumber > 0 && $GetRegnumber != "")
				{
					$group_code_sql = $this->master_model->getRecords('dra_eligible_master', array('member_no' => $GetRegnumber),'app_category');
					//echo $this->db->last_query(); die;
					if(count($group_code_sql) > 0)
					{
						//echo "s";
						$group_code = $group_code_sql[0]['app_category'];
						if($group_code == "R"){
							$group_code = "B1";
						}
						else{
							//echo " M";
							$group_code=$group_code;
						}
					}
					else 
					{
						//echo " swa";
						$group_code = "B1";														
					}
				}
				/* Close Code to dyanamic fees - Bhushan */
				// echo $group_code; die;
				
				if($registrationtype == 'NM')
				{
					$this->db->select('dra_fee_master.*,dra_exam_master.*,dra_misc_master.*,dra_misc_master.exam_period as miscex_period, dra_subject_master.*');
					$this->db->where('dra_exam_master.exam_delete','0');
					$this->db->join('dra_fee_master','dra_fee_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_misc_master','dra_misc_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_subject_master','dra_subject_master.exam_code=dra_exam_master.exam_code');
					$this->db->where("dra_misc_master.misc_delete",'0');
					$this->db->where("dra_subject_master.subject_delete",'0');
					$this->db->where("dra_fee_master.fee_delete",'0');
					$this->db->where('dra_fee_master.member_category',$registrationtype);
					$this->db->where('dra_fee_master.group_code',$group_code);
					$examDet = $this->master_model->getRecords('dra_exam_master',array('dra_exam_master.exam_code'=>$examcode));
					//echo $this->db->last_query(); exit; 
					$drainstdata = $this->session->userdata('dra_institute');
					if( count($examDet) > 0 ) {
						$result = $examDet[0];
						// code added for GST, By Bhagwan Sahane, on 03-07-2017
						//$exam_fees = $result['fee_amount'];
						if($drainstdata['ste_code'] == 'MAH')
						{
							$exam_fees = $result['cs_tot'];
							$loop = 1;
						}
						else
						{
							$exam_fees = $result['igst_tot'];
							$loop = 2;
						}
						
						$exam_fees_free_paid_data = $this->get_member_exam_fees($regid, $exam_fees, $examDet[0]['exam_code'], $examDet[0]['exam_period']);
						/* print_r($exam_fees_free_paid_data); exit;  */
						$exam_fees = $exam_fees_free_paid_data['exam_fees'];
						
						// eof code added for GST
						$exam_period = $result['miscex_period'];
						$exam_date = $result['exam_date'];
						$exam_time = $result['exam_time'];
					}
				} 
				else if($registrationtype == 'O')
				{
					
					$this->db->select('dra_fee_master.*,dra_exam_master.*,dra_misc_master.*,dra_misc_master.exam_period as miscex_period, dra_subject_master.*');
					$this->db->where('dra_exam_master.exam_delete','0');
					$this->db->join('dra_fee_master','dra_fee_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_misc_master','dra_misc_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_subject_master','dra_subject_master.exam_code=dra_exam_master.exam_code');
					$this->db->where("dra_misc_master.misc_delete",'0');
					$this->db->where("dra_subject_master.subject_delete",'0');
					$this->db->where("dra_fee_master.fee_delete",'0');
					$this->db->where('dra_fee_master.member_category',$registrationtype);
					$this->db->where('dra_fee_master.group_code',$group_code);
					$examRes = $this->master_model->getRecords('dra_exam_master',array('dra_exam_master.exam_code'=>$examcode));
					//echo $this->db->last_query(); exit; 
					$drainstdata = $this->session->userdata('dra_institute');
					if( count($examRes) > 0 ) {
						$result = $examRes[0];
						// code added for GST, By Bhagwan Sahane, on 03-07-2017
						//$exam_fees = $result['fee_amount'];
						if($drainstdata['ste_code'] == 'MAH')
						{
							$exam_fees = $result['cs_tot'];
							$loop = 1;
						}
						else
						{
							$exam_fees = $result['igst_tot'];
							$loop = 2;
						}
						
						$exam_fees_free_paid_data = $this->get_member_exam_fees($regid, $exam_fees, $examRes[0]['exam_code'], $examRes[0]['exam_period']);
						$exam_fees = $exam_fees_free_paid_data['exam_fees'];
						
						// eof code added for GST
						//echo $loop.' - Inst. State : '.$instdata['ste_code'].' | Exam Fees : '.$exam_fees; die();
						$exam_period = $result['miscex_period'];
						$exam_date = $result['exam_date'];
						$exam_time = $result['exam_time'];
					}
					
				}
				else
				{
					echo json_encode("your information is incorrect.please contact to iibf admin."); 
				}
				
				if($chk_exam_mode == 'RPE')
				{
					$getExamDate[0]['exam_date'] = '';
					if(!empty($this->session->userdata['center_examinfo']['subject_arr']))
					{
						foreach($this->session->userdata['center_examinfo']['subject_arr'] as $k=>$v)
						{
							$getExamDate = $this->master_model->getRecords('dra_venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));						
							$insert_exam['exam_date'] = $getExamDate[0]['exam_date'];
						}
					}
				}
				else if($chk_exam_mode == 'PHYSICAL') { $insert_exam['exam_date'] = $exam_date; }
				
				$insert_exam['regid'] = $regid;
				$insert_exam['exam_code'] = $examcode;
				$insert_exam['batchId'] = $batchId;
				$insert_exam['exam_medium'] = $exam_medium;
				$insert_exam['exam_period'] = $exam_period;
				$insert_exam['exam_center_code'] = $exam_center;
				$insert_exam['exam_fee'] = $exam_fees;
				$insert_exam['exam_time'] = $exam_time;
				$insert_exam['training_from'] = $training_from;
				$insert_exam['training_to']	= $training_to;
				$insert_exam['fee_paid_flag'] = $exam_fees_free_paid_data['free_paid_flag'];
				$insert_exam['created_on'] = date('Y-m-d H:i:s');
				$insert_exam['pay_status'] = 2;
				
				$updatecandinfoarr = array(		
				'excode' => $examcode
				);
				
				$this->master_model->updateRecord('dra_members',$updatecandinfoarr,  array('regid'=>$regid));
				$q = $this->master_model->getRecords('dra_member_exam',array('regid'=>$regid,'exam_period'=>$exam_period),'',array('id'=>'DESC'),'',1);
				//echo $this->db->last_query();exit;
				
				if(count($q) > 0)
				{
					$updatedra_member_exam['exam_center_code'] = $exam_center;
					$updatedra_member_exam['exam_medium'] = $exam_medium;
					$updatedra_member_exam['exam_code'] = $examcode;
					$updatedra_member_exam['exam_fee'] = $exam_fees;
					$updatedra_member_exam['batchId'] = $batchId;
					$updatedra_member_exam['exam_period'] = $exam_period;
					if($chk_exam_mode == 'RPE') { $updatedra_member_exam['exam_date'] = $getExamDate[0]['exam_date']; }
					$updatedra_member_exam['pay_status'] = 2;
					
					if($this->master_model->updateRecord('dra_member_exam',$updatedra_member_exam,  array('id'=>$q[0]['id'])) ) 
					{		
						if($chk_exam_mode == 'RPE')
						{
							$getcenter=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$this->session->userdata['center_examinfo']['excd'],'center_code'=>$this->session->userdata['center_examinfo']['txtCenterCode'],'exam_period'=>$exam_period,'center_delete'=>'0'));
							
							if(!empty($this->session->userdata['center_examinfo']['subject_arr']))
							{
								foreach($this->session->userdata['center_examinfo']['subject_arr'] as $k=>$v)
								{
									$get_subject_details=$this->master_model->getRecords('dra_venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));
									
									// admit card insert array
									$admitcard_update_array=array(
									'center_code'=>$getcenter[0]['center_code'],
									'center_name'=>$getcenter[0]['center_name'],
									'venueid'=>$get_subject_details[0]['venue_code'],
									'venue_name'=>$get_subject_details[0]['venue_name'],
									'venueadd1'=>$get_subject_details[0]['venue_addr1'],
									'venueadd2'=>$get_subject_details[0]['venue_addr2'],
									'venueadd3'=>$get_subject_details[0]['venue_addr3'],
									'venueadd4'=>$get_subject_details[0]['venue_addr4'],
									'venueadd5'=>$get_subject_details[0]['venue_addr5'],
									'venpin'=>$get_subject_details[0]['venue_pincode'],
									'exam_date'=>$get_subject_details[0]['exam_date'],
									'time'=>$get_subject_details[0]['session_time'],
									'created_on'=>date('Y-m-d H:i:s'));
									
									$inser_id=$this->master_model->updateRecord('dra_admit_card_details',$admitcard_update_array,array('mem_exam_id'=>$q[0]['id']));
								}
							}
						}
						
						$history =array(
						'regid' => $regid,
						'batch_id'		=>$batchId,
						'agency_id'		=> $institute_code,
						'exam_period'		=> $exam_period,
						
						);
						$this->master_model->insertRecord('agency_memberexam_batch_history',$history);
						
						log_dra_user($log_title = "Update DRA Exam Applicant Successful", $log_message = serialize($updatedra_member_exam));
						// $this->session->set_flashdata('success','Record added successfully');
						return 1;
						
					} 
					else 
					{
						log_dra_user($log_title = "Update DRA Exam Applicant Unsuccessful", $log_message = serialize($updatedra_member_exam));
						// $this->session->set_flashdata('error','Error occured while adding record');
						return 0;
					}
				}
				else
				{
					$dra_member_exam_id = $this->master_model->insertRecord('dra_member_exam',$insert_exam,true);
					if($dra_member_exam_id > 0 )
					{		
						if($chk_exam_mode == 'RPE')
						{
							$getcenter=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$this->session->userdata['center_examinfo']['excd'],'center_code'=>$this->session->userdata['center_examinfo']['txtCenterCode'],'exam_period'=>$exam_period,'center_delete'=>'0'));
							$password = random_password();	
							
							$dra_user_info=$this->master_model->getRecords('dra_members', array('regid' => $regid, 'batch_id' => $batchId));
							
							if($dra_user_info[0]['gender']=='male')
							{$gender='M';}
							else
							{$gender='F';}
							
							$username=$dra_user_info[0]['firstname'].' '.$dra_user_info[0]['middlename'].' '.$dra_user_info[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							
							$states=$this->master_model->getRecords('state_master',array('state_code'=>$dra_user_info[0]['state'],'state_delete'=>'0'));
							$state_name='';
							if(count($states) >0)
							{
								$state_name=$states[0]['state_name'];
							}
							
							if(!empty($this->session->userdata['center_examinfo']['subject_arr']))
							{
								foreach($this->session->userdata['center_examinfo']['subject_arr'] as $k=>$v)
								{
									$compulsory_subjects=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$this->session->userdata['center_examinfo']['excd'],'subject_delete'=>'0','group_code'=>'C','exam_period'=>$exam_period,'subject_code'=>$k),'subject_description');
									
									$get_subject_details=$this->master_model->getRecords('dra_venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));
									
									// admit card insert array
									$admitcard_insert_array=array(
									'mem_exam_id'=>$dra_member_exam_id,
									'batchId'=>$batchId,
									'institute_code'=>$institute_code,
									'center_code'=>$getcenter[0]['center_code'],
									'center_name'=>$getcenter[0]['center_name'],
									'mem_type'=>$registrationtype,
									'mem_mem_no'=>$GetRegnumber,
									'g_1'=>$gender,
									'mam_nam_1'=>$userfinalstrname,
									'mem_adr_1'=>$dra_user_info[0]['address1'],
									'mem_adr_2'=>$dra_user_info[0]['address2'],
									'mem_adr_3'=>$dra_user_info[0]['address3'],
									'mem_adr_4'=>$dra_user_info[0]['address4'],
									'mem_adr_5'=>$dra_user_info[0]['district'],
									'mem_adr_6'=>$dra_user_info[0]['city'],
									'mem_pin_cd'=>$dra_user_info[0]['pincode'],
									'state'=>$state_name,
									'exm_cd'=>$this->session->userdata['center_examinfo']['excd'],
									'exm_prd'=>$exam_period,
									'sub_cd '=>$k,
									'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
									'm_1'=>$exam_medium,
									//'inscd'=>$institute_id,
									//'insname'=>$institution_name,
									'venueid'=>$get_subject_details[0]['venue_code'],
									'venue_name'=>$get_subject_details[0]['venue_name'],
									'venueadd1'=>$get_subject_details[0]['venue_addr1'],
									'venueadd2'=>$get_subject_details[0]['venue_addr2'],
									'venueadd3'=>$get_subject_details[0]['venue_addr3'],
									'venueadd4'=>$get_subject_details[0]['venue_addr4'],
									'venueadd5'=>$get_subject_details[0]['venue_addr5'],
									'venpin'=>$get_subject_details[0]['venue_pincode'],
									'pwd'=>$password,
									'exam_date'=>$get_subject_details[0]['exam_date'],
									'time'=>$get_subject_details[0]['session_time'],
									'free_paid_flg'=>$exam_fees_free_paid_data['free_paid_flag'],															
									'mode'=>'Online',
									'vendor_code'=>$get_subject_details[0]['vendor_code'],
									'remark'=>2,
									'record_source'=>'Bulk',
									'created_on'=>date('Y-m-d H:i:s'));
									$inser_id=$this->master_model->insertRecord('dra_admit_card_details',$admitcard_insert_array);
								}
							}
						}
						
						$history =array(
						'regid' => $regid,
						'batch_id'	=>$batchId,
						'agency_id'	=> $institute_code,
						'exam_period'=> $exam_period,
						);
						$this->master_model->insertRecord('agency_memberexam_batch_history',$history);
						log_dra_user($log_title = "Add DRA Exam Applicant Successful", $log_message = serialize($insert_exam));
						return 1;
					}
					else
					{
						log_dra_user($log_title = "Add DRA Exam Applicant Unsuccessful", $log_message = serialize($insert_exam));
						return 0;
					}
				}
			}
			else
			{
				return 0;
			}
		}		
		
		public function get_member_exam_fees($regid=0, $exam_fees='', $exam_code='', $eligible_period='')
		{
			$result['exam_fees'] = '';
			$result['free_paid_flag'] = '';
			/* echo "<br> regid : ".$regid; */
			
			if($regid == 0) 
			{ 
				$result['exam_fees'] = $exam_fees;
				$result['free_paid_flag'] = 'P';
			}
			else
			{
				$regnumber = '';
				$dra_members_data = $this->master_model->getRecords('dra_members',array('regid'=>$regid));
				if(count($dra_members_data) > 0)
				{
					if($dra_members_data[0]['regnumber'] != "") { $regnumber = $dra_members_data[0]['regnumber']; }
					else { $regnumber = $dra_members_data[0]['entered_regnumber']; }
				}
				
				$this->db->order_by("id","DESC"); 
				$eligible_data = $this->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber,'exam_code'=>$exam_code,'eligible_period'=>$eligible_period));
				/* echo $this->db->last_query(); */
				if(count($eligible_data) > 0)
				{
					if($eligible_data[0]['fee_paid_flag'] == 'F')
					{
						$result['exam_fees'] = '0.00';
						$result['free_paid_flag'] = 'F';
					}
					else
					{
						$result['exam_fees'] = $exam_fees;
						$result['free_paid_flag'] = 'P';
					}
				}
				else
				{
					$result['exam_fees'] = $exam_fees;
					$result['free_paid_flag'] = 'P';
				}
			}
			
			return $result;
		}
		
		//update candidate details againt exam
		public function upadeApplicant()
		{
			$regid =$this->input->post('regid');
			$batchId = $this->input->post('batchId');
			$registrationtype = $this->input->post('memtype');
			$examcode =$this->input->post('examcode');
			$exam_medium =$this->input->post('exam_medium');
			$exam_center =$this->input->post('exam_center');
			$training_from =$this->input->post('training_from');
			$training_to = $this->input->post('training_to');
			
			//echo $examcode; die;
			// echo json_encode($examcode); die;
			$data = array();
			
			// if(!empty($regid)) {
			// 	$this->session->set_flashdata('error','No such applicant exists');
			// 	redirect(base_url().'iibfdra/InstituteHome/dashboard');
			// }
			$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $examcode));
			if( $examcount > 0 ) {
				//check if exam is active or not
				$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time');
			}
			
			if($regid)
			{
				/* Start Code to dyanamic fees - Bhushan */
				// Check the member is fresh or from eligible master 
				$GetRegnumber = "";
				$checkMemberFrom = $this->master_model->getRecords('dra_members', array('regid' => $regid, 'batch_id' => $batchId),'regnumber,inst_code');
				$GetRegnumber = $checkMemberFrom[0]['regnumber'];
				$institute_code = $checkMemberFrom[0]['inst_code'];
				
				// Get Group Code
				$group_code = "B1";
				if($GetRegnumber > 0 && $GetRegnumber != "")
				{
					$group_code_sql = $this->master_model->getRecords('dra_eligible_master', array('member_no' => $GetRegnumber),'app_category');
					if(count($group_code_sql) > 0)
					{
						$group_code = $group_code_sql[0]['app_category'];
						if($group_code == "R"){
							$group_code = "B1";
						}
						}else{
						$group_code = "B1";														
					}
				}
				/* Close Code to dyanamic fees - Bhushan */
				
				if($registrationtype == 'NM')
				{
					$this->db->select('dra_fee_master.*,dra_exam_master.*,dra_misc_master.*,dra_misc_master.exam_period as miscex_period, dra_subject_master.*');
					$this->db->where('dra_exam_master.exam_delete','0');
					$this->db->join('dra_fee_master','dra_fee_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_misc_master','dra_misc_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_subject_master','dra_subject_master.exam_code=dra_exam_master.exam_code');
					$this->db->where("dra_misc_master.misc_delete",'0');
					$this->db->where("dra_subject_master.subject_delete",'0');
					$this->db->where("dra_fee_master.fee_delete",'0');
					$this->db->where('dra_fee_master.member_category',$registrationtype);
					$this->db->where('dra_fee_master.group_code',$group_code);
					$examDet = $this->master_model->getRecords('dra_exam_master',array('dra_exam_master.exam_code'=>$examcode));
					$drainstdata = $this->session->userdata('dra_institute');
					if( count($examDet) > 0 ) {
						$result = $examDet[0];
						// code added for GST, By Bhagwan Sahane, on 03-07-2017
						//$exam_fees = $result['fee_amount'];
						if($drainstdata['ste_code'] == 'MAH'){
							$exam_fees = $result['cs_tot'];
							$loop = 1;
							}else{
							$exam_fees = $result['igst_tot'];
							$loop = 2;
						}
						// eof code added for GST
						$exam_period = $result['miscex_period'];
						$exam_date = $result['exam_date'];
						$exam_time = $result['exam_time'];
					}
				} 
				else if($registrationtype == 'O')
				{
					
					$this->db->select('dra_fee_master.*,dra_exam_master.*,dra_misc_master.*,dra_misc_master.exam_period as miscex_period, dra_subject_master.*');
					$this->db->where('dra_exam_master.exam_delete','0');
					$this->db->join('dra_fee_master','dra_fee_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_misc_master','dra_misc_master.exam_code=dra_exam_master.exam_code');
					$this->db->join('dra_subject_master','dra_subject_master.exam_code=dra_exam_master.exam_code');
					$this->db->where("dra_misc_master.misc_delete",'0');
					$this->db->where("dra_subject_master.subject_delete",'0');
					$this->db->where("dra_fee_master.fee_delete",'0');
					$this->db->where('dra_fee_master.member_category',$registrationtype);
					$this->db->where('dra_fee_master.group_code',$group_code);
					$examRes = $this->master_model->getRecords('dra_exam_master',array('dra_exam_master.exam_code'=>$examcode));
					
					$drainstdata = $this->session->userdata('dra_institute');
					if( count($examRes) > 0 ) {
						$result = $examRes[0];
						// code added for GST, By Bhagwan Sahane, on 03-07-2017
						//$exam_fees = $result['fee_amount'];
						if($drainstdata['ste_code'] == 'MAH')
						{
							$exam_fees = $result['cs_tot'];
							$loop = 1;
							}else{
							$exam_fees = $result['igst_tot'];
							$loop = 2;
						}
						// eof code added for GST
						//echo $loop.' - Inst. State : '.$instdata['ste_code'].' | Exam Fees : '.$exam_fees; die();
						$exam_period = $result['miscex_period'];
						$exam_date = $result['exam_date'];
						$exam_time = $result['exam_time'];
					}
					
				}
				else{
					echo json_encode(1); 
				}
				$insert_exam = array(
				'regid' => $regid,
				'exam_code' => $examcode,
				//'exam_mode' => $this->input->post('exam_mode'),
				'batchId' => $batchId,
				'exam_medium' =>$exam_medium,
				'exam_period' => $exam_period,
				'exam_center_code' => $exam_center,
				'exam_fee' => $exam_fees,
				'exam_date' => $exam_date,
				'exam_time' => $exam_time,
				'training_from'	=> $training_from,
				'training_to'	=> $training_to,
				'created_on' => date('Y-m-d H:i:s'),
				'pay_status' => 2
				);
				$updatecandinfoarr = array(		
				'excode' => $examcode
				);
				
				$this->master_model->updateRecord('dra_members',$updatecandinfoarr,  array('regid'=>$regid));
				$q = $this->master_model->getRecords('dra_member_exam',array('regid'=>$regid,'exam_period'=>$exam_period),'',array('id'=>'DESC'),'',1);
				//echo $this->db->last_query();exit;
				if(count($q) > 0)
				{
					$updatedra_member_exam=array('exam_center_code' => $exam_center,'exam_medium' =>$exam_medium,'exam_code'=>$examcode,'exam_fee'=> $exam_fees,'batchId' => $batchId,'exam_period' => $exam_period,'pay_status' => 2);
					if($this->master_model->updateRecord('dra_member_exam',$updatedra_member_exam,  array('id'=>$q[0]['id'])) ) {
						$history =array(
						'regid' => $regid,
						'batch_id'		=>$batchId,
						'agency_id'		=> $institute_code,
						'exam_period'		=> $exam_period,
						
						);
						$this->master_model->insertRecord('agency_memberexam_batch_history',$history);
						
						log_dra_user($log_title = "Update DRA Exam Applicant Successful", $log_message = serialize($updatedra_member_exam));
						$this->session->set_flashdata('success','Record added successfully');
						
						} else {
						log_dra_user($log_title = "Update DRA Exam Applicant Unsuccessful", $log_message = serialize($updatedra_member_exam));
						$this->session->set_flashdata('error','Error occured while adding record');
					}
				}
				else
				{
					if( $this->master_model->insertRecord('dra_member_exam',$insert_exam) ) {
						$history =array(
						'regid' => $regid,
						'batch_id'		=>$batchId,
						'agency_id'		=> $institute_code,
						'exam_period'		=> $exam_period,
						
						);
						$this->master_model->insertRecord('agency_memberexam_batch_history',$history);
						
						log_dra_user($log_title = "Add DRA Exam Applicant Successful", $log_message = serialize($insert_exam));
						$this->session->set_flashdata('success','Record added successfully!');
						
						} else {
						log_dra_user($log_title = "Add DRA Exam Applicant Unsuccessful", $log_message = serialize($insert_exam));
						$this->session->set_flashdata('error','Error occured while adding record');
					}
				}
				echo json_encode(2); 
				
			}
			else{
				echo json_encode(1); 
				
			}
			
		}
		
		//clear candidates details againt exam
		public function clearApplicant()
		{
			
			// $regid = $this->input->post('regid');
			$regid = $this->input->post('regid');
			$batchId = $this->input->post('batchId');
			$registrationtype = $this->input->post('memtype');
			$examcode = $this->input->post('examcode');
			$exam_medium = $this->input->post('exam_medium');
			$exam_center = $this->input->post('exam_center');
			$training_from =$this->input->post('training_from');
			$training_to = $this->input->post('training_to');
			$data = array();
			
			// if(!empty($regid)) {
			// 	$this->session->set_flashdata('error','No such applicant exists');
			// 	redirect(base_url().'iibfdra/InstituteHome/dashboard');
			// }
			if(isset($regid)){
				
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $examcode));
				if( $examcount > 0 ) {
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time');
				}
				
				if($regid)
				{	
					// added by manoj
					
					$GetRegnumber = "";
					$checkMemberFrom = $this->master_model->getRecords('dra_members', array('regid' => $regid),'regnumber,inst_code');
					
					//$institute_code = $checkMemberFrom[0]['inst_code'];
					
					// Get Group Code
					if(count($checkMemberFrom)>0)
					{
						$GetRegnumber = $checkMemberFrom[0]['regnumber'];
						if($GetRegnumber == ''){
							$clear_updatecandinfoarr = array(		
							'excode' => ''
							);
							$this->master_model->updateRecord('dra_members',$clear_updatecandinfoarr,  array('regid'=>$regid));	
							}else{
							$group_code_sql = $this->master_model->getRecords('dra_eligible_master', array('member_no' => $GetRegnumber),'app_category');
							if(count($group_code_sql) <= 0)
							{
								$clear_updatecandinfoarr = array(		
								'excode' => ''
								);
								$this->master_model->updateRecord('dra_members',$clear_updatecandinfoarr,  array('regid'=>$regid));	
							}
							
						}
					}
					
					
					
					$q=$this->master_model->getRecords('dra_member_exam',array('regid'=>$regid),'',array('id'=>'DESC'),'',1);
					if(count($q) > 0)
					{
						
						// added by manoj
						$clearr_updatedra_member_exam = array('exam_center_code' => '','exam_medium' =>'','exam_code'=>'','exam_fee'=> '','pay_status'=> '2');
						// pay Status changes to 2 on Cancel batch on 19 march 2019 by manoj
						
						if($this->master_model->updateRecord('dra_member_exam',$clearr_updatedra_member_exam,  array('id'=>$q[0]['id'])) ) {
							log_dra_user($log_title = "Exam details of the selected application is cleared", $log_message = serialize($clearr_updatedra_member_exam));
							$this->session->set_flashdata('success',' Exam details of the selected application is cleared.');
							
							} else {
							log_dra_user($log_title = "Update DRA Exam Applicant Unsuccessful", $log_message = serialize($clearr_updatedra_member_exam));
							$this->session->set_flashdata('error','Error occured while clearing record');
						}
					}
					else
					{
						$this->session->set_flashdata('error','Exam details of the selected application is Not avalable');
						
					}
					echo json_encode("success"); 
					
					}else{
					$this->session->set_flashdata('error','Exam details of the selected application is Not avalable');
					echo json_encode('fail'); 
				}
			}
			else{
				$this->session->set_flashdata('error','Exam details of the selected application is Not avalable');
				echo json_encode('fail'); 
				
			}
			
		}
		
		/*Added*///check insitute code condition
		public function check_insitutecode($regno=NULL)
		{			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$memRes = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
			
			if( $memRes[0]['inst_code'] != $inst_code) {
				return 0;
				}else{
				return 1;
			}
			
		}
		
		//check pass fail condition
		public function check_fail($regno=NULL)
		{
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.*,agency_batch.id,agency_batch.batch_status,agency_batch.batch_to_date,dra_subject_master.exam_date,dra_misc_master.trg_value');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('dra_subject_master','dra_eligible_master.exam_code=dra_subject_master.exam_code','left');
			$this->db->join('dra_misc_master','dra_eligible_master.exam_code=dra_misc_master.exam_code','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			
			
			$memRes = $memRes[0];
			if( $memRes['exam_status'] == 'P' ||  $memRes['exam_status'] == 'p') {
				return 0;
				}else{
				return 1;
			}
			
		}
		
		//check apsent condition for mem
		public function check_V($regno=NULL)
		{
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.*,agency_batch.id,agency_batch.batch_status,agency_batch.batch_to_date,dra_subject_master.exam_date,dra_misc_master.trg_value');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('dra_subject_master','dra_eligible_master.exam_code=dra_subject_master.exam_code','left');
			$this->db->join('dra_misc_master','dra_eligible_master.exam_code=dra_misc_master.exam_code','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			
			
			$memRes = $memRes[0];
			if( $memRes['exam_status'] == 'V' || $memRes['exam_status'] == 'v') {
				return 0;
				}else{
				return 1;
			}
			
		}
		
		//check empty app category
		public function check_category($regno=NULL)
		{
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.*,agency_batch.id,agency_batch.batch_status,agency_batch.batch_to_date,dra_subject_master.exam_date,dra_misc_master.trg_value');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('dra_subject_master','dra_eligible_master.exam_code=dra_subject_master.exam_code','left');
			$this->db->join('dra_misc_master','dra_eligible_master.exam_code=dra_misc_master.exam_code','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			
			
			$memRes = $memRes[0];
			if( $memRes['app_category'] == '') {
				return 0;
				}else{
				return 1;
			}
			
		}
		
		//check empty debard
		public function check_debard($regno=NULL)
		{
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.*,agency_batch.id,agency_batch.batch_status,agency_batch.batch_to_date,dra_subject_master.exam_date,dra_misc_master.trg_value');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('dra_subject_master','dra_eligible_master.exam_code=dra_subject_master.exam_code','left');
			$this->db->join('dra_misc_master','dra_eligible_master.exam_code=dra_misc_master.exam_code','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			
			
			$memRes = $memRes[0];
			if( $memRes['exam_status'] == 'D' || $memRes['exam_status'] == 'd') {
				return 0;
				}else{
				return 1;
			}
			
		}
		
		//check 290days and 3 attempt
		public function check_attempt($regno=NULL,$batchId=NULL)
		{
			//echo "<br>regno : ".$regno;
			//echo "<br>batchId : ".$batchId;
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.*,agency_batch.id,agency_batch.batch_status,agency_batch.batch_to_date,dra_subject_master.exam_date,dra_misc_master.trg_value');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$this->db->join('dra_subject_master','dra_eligible_master.exam_code=dra_subject_master.exam_code','left');
			$this->db->join('dra_misc_master','dra_eligible_master.exam_code=dra_misc_master.exam_code','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			/* echo $this->db->last_query(); */
			$memRes = $memRes[0];
			
			$trg_value = $memRes['trg_value'] + 1;
			$Todate = date_create($memRes['batch_to_date']);
			date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
			
			if( $memRes['batch_id'] != $batchId &&  $memRes['batch_id'] != 0 && $memRes['batch_status'] != 'A') 
			{			
				// if($memRes['exam_date'] < date_format($Todate, "Y-m-d") && $memRes['re_attempt'] < 3 ){ //check 290days < and 3attempt < alert already exist in exam list
				//                    return 0;
				// }else{
				return 1;
				// }
			}
			else if( $memRes['batch_id'] != $batchId &&  $memRes['batch_id'] != 0 && $memRes['batch_status'] == 'A') 
			{				
				if($memRes['exam_date'] < date_format($Todate, "Y-m-d") && $memRes['re_attempt'] < 3 )
				{ //check 290days < and 3attempt < alert already exist in exam list
					return 0;
				}
				else
				{
					return 1;
				}
			} 
			elseif( $memRes['batch_id'] == 0)
			{			
				if($memRes['re_attempt'] < 3 ){ //check 290days < and 3attempt < alert already exist in exam list
					return 0;
					}else{
					return 1;
				}
			}
			elseif($memRes['exam_date'] < date_format($Todate, "Y-m-d") && $memRes['re_attempt'] < 3 )
			{ //check 290days < and 3attempt < alert already exist in exam list
				
				return 0;
			}
			else
			{	
				return 1;
			}
			
		}
		
		//check batch diffrant condition
		public function check_diffbatch($regno=NULL,$batchId=NULL)
		{
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			$this->db->select('dra_member_exam.*, dra_members.*,agency_batch.id,agency_batch.batch_status');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			$memRes = $memRes[0];
			
			if( $memRes['batch_id'] != $batchId &&  $memRes['batch_id'] != 0) {
				//echo json_encode($memRes[0]['batch_status']);
				//$batch = $this->master_model->getRecords('agency_batch',array('id'=>$memRes[0]['batch_id']));
				if($memRes['batch_status'] == 'A'){
					return 0;
					}else{
					return 1;
				}
				}  else{
				return 1;
			}
			
		}
		
		//check same batch condition
		public function check_samebatch($regno=NULL,$batchId=NULL)
		{
			
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			
			$this->db->select('dra_member_exam.*, dra_members.*,agency_batch.id,agency_batch.batch_status');
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
			$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
			$memRes = $memRes[0];
			
			if($memRes['batch_id'] == $batchId){
				return 0;
				}else{
				return 1;
				
			}
			
		}
		
		/* Ajax callback function for get details */
		public function get_memdetails() {
			
			$batchId =(isset($_POST['batch_id'])) ? $_POST['batch_id'] : '';
			$regno =(isset($_POST['regno'])) ? $_POST['regno'] : '';
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			if( !empty( $regno ) ) 
			{				
				$memres = $this->db->query("SELECT m.*
				FROM member_registration m
				where  m.regnumber = ".$regno." AND
				NOT EXISTS (SELECT d.regnumber,d.regid
				FROM   dra_members d
				WHERE  d.regnumber=m.regnumber) ");
				$memRes = $memres->result_array();
				//echo $this->db->last_query();
				
				// $this->db->select('member_registration.*, dra_members.regnumber as dra_regno,dra_members.regid as dra_id');
				// $this->db->join('dra_members','dra_members.regnumber=member_registration.regnumber AND dra_members.regid="" ','left');
				// $memRes = $this->master_model->getRecords('member_registration',array('member_registration.regnumber'=>$regno));
				
				$data = array();
				if( count( $memRes ) > 0 ) 
				{					
					$memRes = $memRes[0];
					$data['membertype'] = 'normal_member';
					$data['sel_namesub'] = $memRes['namesub']; 
					$data['firstname'] = $memRes['firstname']; 
					$data['middlename'] = $memRes['middlename']; 
					$data['lastname'] = $memRes['lastname']; 
					$data['addressline1'] = $memRes['address1'];
					$data['addressline2'] = $memRes['address2'];
					$data['addressline3'] = $memRes['address3'];
					$data['addressline4'] = $memRes['address4'];
					$data['city'] = $memRes['city'];
					$data['district'] = $memRes['district'];
					$data['state'] = $memRes['state'];
					$data['pincode'] = $memRes['pincode'];
					$data['dateofbirth'] = $memRes['dateofbirth'];
					$data['gender'] = $memRes['gender']; //check it
					$data['stdcode'] = $memRes['stdcode'];
					$data['phone'] = $memRes['office_phone'];
					$data['mobile'] = $memRes['mobile'];
					$data['email'] = $memRes['email'];
					$data['idproof'] = $memRes['idproof'];
					$data['memtype'] = $memRes['registrationtype'];
					$data['edu_quali'] = $memRes['qualification'];
					$data['error'] = 0;	
					
				}
				else 
				{				
					$memRes = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
					$data = array();
					
					if( count( $memRes ) > 0 ) 
					{			
						$check1=$this->check_insitutecode($regno);
						
						if($check1 == 0) {
							$data['error_message'] = 'Number you have entered is not your agency member.Please enter valid number';
							echo json_encode($data);
							die();
						}
						
						// if( $memRes[0]['inst_code'] != $inst_code) {
						// 	  $data = 2;
						// echo json_encode($data);
						//die();
						// 	}
						
						
						//echo 'djkhkj'; die();  exist in eligible master
						$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.*,agency_batch.id,agency_batch.batch_status,agency_batch.batch_to_date,dra_subject_master.exam_date,dra_misc_master.trg_value');
						$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
						$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
						$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
						$this->db->join('dra_subject_master','dra_eligible_master.exam_code=dra_subject_master.exam_code','left');
						$this->db->join('dra_misc_master','dra_eligible_master.exam_code=dra_misc_master.exam_code','left');
						$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
						//echo $this->db->last_query(); die();
						//echo json_encode($memRes[0]);
						if( count( $memRes ) > 0 ) 
						{							
							$check2=$this->check_fail($regno);
							
							if($check2 == 0) {
								$data['error_message'] = 'You have already passed this exam';
								echo json_encode($data);
								die();
							}
							
							$check6=$this->check_V($regno);
							
							if($check6 == 0) {
								$data['error_message'] = 'Valid application exist, Attempt remaining.';
								echo json_encode($data);
								die();
							}
							
							$check7=$this->check_category($regno);
							
							if($check7 == 0) {
								$data['error_message'] = 'Fee is not defined for this candidate, Please contact to IIBF.';
								echo json_encode($data);
								die();
							}
							$check8=$this->check_debard($regno);
							
							if($check8 == 0) {
								$data['error_message'] = 'You Are Debarred For this Exam';
								echo json_encode($data);
								die();
							}
							
							
							
							$memRes = $memRes[0];
							
							$trg_value = $memRes['trg_value'] + 1;
							$Todate = date_create($memRes['batch_to_date']);
							date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));
							
							
							$check3=$this->check_attempt($regno,$batchId);
							
							if($check3 == 0) {  //xxx
								$data['error_message'] = 'You have already exist in exam menu.Please follow exam procedure';
								echo json_encode($data);
								die();
							}
							
							// if( $memRes['batch_id'] != $batchId &&  $memRes['batch_id'] != 0) {
							
							//                      		if($memRes['exam_date'] < date_format($Todate, "Y-m-d") || $memRes['re_attempt'] < 3 ){ //check 290days < and 3attempt < alert already exist in exam list
							//                                $data = 6;
							//                                echo json_encode($data);
							//                                die();       
							//                      		}
							
							
							
							//     }elseif($memRes['exam_date'] < date_format($Todate, "Y-m-d") || $memRes['re_attempt'] < 3 ){ //check 290days < and 3attempt < alert already exist in exam list
							//                                    $data = 6;
							//                                    echo json_encode($data);
							//                                    die();       
							//                    }
							
							$data['membertype'] = 'dra_member';
							$data['sel_namesub'] = $memRes['namesub']; 
							$data['firstname'] = $memRes['firstname']; 
							$data['middlename'] = $memRes['middlename']; 
							$data['lastname'] = $memRes['lastname']; 
							$data['addressline1'] = $memRes['address1'];
							$data['addressline2'] = $memRes['address2'];
							$data['addressline3'] = $memRes['address3'];
							$data['addressline4'] = $memRes['address4'];
							$data['city'] = $memRes['city'];
							$data['district'] = $memRes['district'];
							$data['state'] = $memRes['state'];
							$data['pincode'] = $memRes['pincode'];
							$data['dateofbirth'] = $memRes['dateofbirth'];
							$data['gender'] = $memRes['gender']; //check it
							$data['stdcode'] = $memRes['stdcode'];
							$data['phone'] = $memRes['phone'];
							$data['mobile'] = $memRes['mobile'];
							$data['email'] = $memRes['email'];
							$data['idproof'] = $memRes['idproof'];
							$data['memtype'] = $memRes['registrationtype'];
							$data['exam_center'] = $memRes['exam_center_code'];
							$data['center_code'] = $memRes['exam_center_code'];
							$data['exam_medium'] = $memRes['exam_medium'];
							//$data['training_from'] = $memRes['trainingfrm'];
							//$data['training_to'] = $memRes['trainingto'];
							$data['exam_mode'] = $memRes['exam_mode'];
							$data['edu_quali'] = $memRes['qualification'];
							$data['aadhar_no'] = $memRes['aadhar_no'];	// added by Bhagwan Sahane, on 06-05-2017
							$data['error'] = 0;	
							
							$scannedphoto = '';
							$scannedsignaturephoto = '';
							$idproofphoto = '';
							$quali_certificate = '';
							$training_certificate = '';
							
							// get images -
							$old_image_path = 'uploads'.$memRes['image_path'];
							
							$new_image_path = 'uploads/iibfdra/';
							
							if($memRes['scannedphoto'] == '')
							{
								if(file_exists($old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg'))
								{
									$scannedphoto = base_url().$old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['scannedphoto']))
								{
									$scannedphoto = base_url().$new_image_path . $memRes['scannedphoto'];
								}
							}
							
							if($memRes['scannedsignaturephoto'] == '')
							{
								if(file_exists($old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg'))
								{
									$scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg';	
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['scannedsignaturephoto']))
								{
									$scannedsignaturephoto = base_url().$new_image_path . $memRes['scannedsignaturephoto'];
								}
							}
							
							if($memRes['idproofphoto'] == '')
							{
								if(file_exists($old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg'))
								{
									$idproofphoto = base_url().$old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['idproofphoto']))
								{
									$idproofphoto = base_url().$new_image_path . $memRes['idproofphoto'];	
								}
							}
							
							if($memRes['quali_certificate'] == '')
							{
								if(file_exists($old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg'))
								{
									$quali_certificate = base_url().$old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['quali_certificate']))
								{
									$quali_certificate = base_url().$new_image_path . $memRes['quali_certificate'];
								}
							}
							
							if($memRes['training_certificate'] == '')
							{
								if(file_exists($old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg'))
								{
									$training_certificate = base_url().$old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['training_certificate']))
								{
									$training_certificate = base_url().$new_image_path . $memRes['training_certificate'];
								}
							}
							
							/*if($memRes['scannedphoto'] == '')
								{
								$image_path = $memRes['image_path'];
								
								$scannedphoto = $image_path . "p_" . $memRes['registration_no'];
								$scannedsignaturephoto = $image_path . "s_" . $memRes['registration_no'];
								$idproofphoto = $image_path . "pr_" . $memRes['registration_no'];
								$quali_certificate = $image_path . "degre_" . $memRes['registration_no'];
								}
								else
								{
								$image_path = base_url().'uploads/iibfdra/';
								
								$scannedphoto = $image_path . $memRes['scannedphoto'];
								$scannedsignaturephoto = $image_path . $memRes['scannedsignaturephoto'];
								$idproofphoto = $image_path . $memRes['idproofphoto'];
								$quali_certificate = $image_path . $memRes['quali_certificate'];	
							}*/
							
							$data['scannedphoto'] = $scannedphoto;
							$data['scannedsignaturephoto'] = $scannedsignaturephoto;
							$data['idproofphoto'] = $idproofphoto;
							$data['quali_certificate'] = $quali_certificate;
							$data['training_certificate'] = $training_certificate;
							
						} 
						else 
						{ // if not found in dra eligible master
							//$data['error'] = 1;	
							$this->db->select('dra_member_exam.*, dra_members.*,agency_batch.id,agency_batch.batch_status');
							$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
							$this->db->join('agency_batch','agency_batch.id=dra_members.batch_id','left');
							$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
							//echo $this->db->last_query();
							//echo json_encode($memRes[0]);
							if( count( $memRes ) > 0 ) 
							{								
								$check4=$this->check_diffbatch($regno,$batchId);
								
								if($check4 == 0) 
								{
									$data['error_message'] = 'Number you have enterd is not member of this batch.please enter valid Number';
									echo json_encode($data);
									die();
								}
								
								// if( $memRes[0]['batch_id'] != $batchId &&  $memRes[0]['batch_id'] != 0) {
								// 	//echo json_encode($memRes[0]['batch_status']);
								// 	//$batch = $this->master_model->getRecords('agency_batch',array('id'=>$memRes[0]['batch_id']));
								//       if($memRes[0]['batch_status'] == 'A'){
								//     	$data = 3;
								//       echo json_encode($data);
								//       die();
								//  }
								
								// }
								
								$check5=$this->check_samebatch($regno,$batchId);
								
								if($check5 == 0) 
								{
									$data['error_message'] = 'You have already applied to exam';
									echo json_encode($data);
									die();
								} 
								// if($memRes[0]['batch_id'] == $batchId){
								// 	    $data = 4;
								//                      echo json_encode($data);
								//                      die();
								// }
								$memRes = $memRes[0];
								
								$data['membertype'] = 'dra_member';
								$data['sel_namesub'] = $memRes['namesub']; 
								$data['firstname'] = $memRes['firstname']; 
								$data['middlename'] = $memRes['middlename']; 
								$data['lastname'] = $memRes['lastname']; 
								$data['addressline1'] = $memRes['address1'];
								$data['addressline2'] = $memRes['address2'];
								$data['addressline3'] = $memRes['address3'];
								$data['addressline4'] = $memRes['address4'];
								$data['city'] = $memRes['city'];
								$data['district'] = $memRes['district'];
								$data['state'] = $memRes['state'];
								$data['pincode'] = $memRes['pincode'];
								$data['dateofbirth'] = $memRes['dateofbirth'];
								$data['gender'] = $memRes['gender']; //check it
								$data['stdcode'] = $memRes['stdcode'];
								$data['phone'] = $memRes['phone'];
								$data['mobile'] = $memRes['mobile'];
								$data['email'] = $memRes['email'];
								$data['idproof'] = $memRes['idproof'];
								$data['memtype'] = $memRes['registrationtype'];
								$data['exam_center'] = $memRes['exam_center_code'];
								$data['center_code'] = $memRes['exam_center_code'];
								$data['exam_medium'] = $memRes['exam_medium'];
								//$data['training_from'] = $memRes['trainingfrm'];
								//$data['training_to'] = $memRes['trainingto'];
								$data['exam_mode'] = $memRes['exam_mode'];
								$data['edu_quali'] = $memRes['qualification'];
								$data['aadhar_no'] = $memRes['aadhar_no'];	// added by Bhagwan Sahane, on 06-05-2017
								$data['error'] = 0;	
								
								$scannedphoto = '';
								$scannedsignaturephoto = '';
								$idproofphoto = '';
								$quali_certificate = '';
								$training_certificate = '';
								
								// get images -
								$old_image_path = 'uploads'.$memRes['image_path'];
								
								$new_image_path = 'uploads/iibfdra/';
								
								if($memRes['scannedphoto'] == '')
								{
									if(file_exists($old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg'))
									{
										$scannedphoto = base_url().$old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['scannedphoto']))
									{
										$scannedphoto = base_url().$new_image_path . $memRes['scannedphoto'];
									}
								}
								
								if($memRes['scannedsignaturephoto'] == '')
								{
									if(file_exists($old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg'))
									{
										$scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg';	
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['scannedsignaturephoto']))
									{
										$scannedsignaturephoto = base_url().$new_image_path . $memRes['scannedsignaturephoto'];
									}
								}
								
								if($memRes['idproofphoto'] == '')
								{
									if(file_exists($old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg'))
									{
										$idproofphoto = base_url().$old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['idproofphoto']))
									{
										$idproofphoto = base_url().$new_image_path . $memRes['idproofphoto'];	
									}
								}
								
								if($memRes['quali_certificate'] == '')
								{
									if(file_exists($old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg'))
									{
										$quali_certificate = base_url().$old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['quali_certificate']))
									{
										$quali_certificate = base_url().$new_image_path . $memRes['quali_certificate'];
									}
								}
								
								if($memRes['training_certificate'] == '')
								{
									if(file_exists($old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg'))
									{
										$training_certificate = base_url().$old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['training_certificate']))
									{
										$training_certificate = base_url().$new_image_path . $memRes['training_certificate'];
									}
								}
								
								$data['scannedphoto'] = $scannedphoto;
								$data['scannedsignaturephoto'] = $scannedsignaturephoto;
								$data['idproofphoto'] = $idproofphoto;
								$data['quali_certificate'] = $quali_certificate;
								$data['training_certificate'] = $training_certificate;
							}
							
						}
					}
				}	
				echo json_encode($data);
			}
		}
		
		//call back for checkpin
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
		
		public function check_todate($selected_date)
		{
			
			if($selected_date!='')
			{
				$agency_center_todate = $this->master_model->getRecords('agency_center',array('institute_code'=>$_SESSION['dra_institute']['institute_code'],'center_id'=>$_POST['center_id']),'',array('center_status'=>'ASC'));
				if(!empty($agency_center_todate))
				{
					
					$center_todate=$agency_center_todate[0]['center_validity_to'].' 00:00:00';
					$selected_todate=$selected_date.' 00:00:00';
					
					
					if($selected_todate>$center_todate)
					{
						$str='You can only select Batch Training Period date between <strong>'.$agency_center_todate[0]['center_validity_from'].' To '.$agency_center_todate[0]['center_validity_to'].'</strong>';
						$this->form_validation->set_message('check_todate', $str); 
						return false;
						
					}else
					{return true;}
					
				}
			}
			else
			{
				$str='Batch to Date field is required.';
				$this->form_validation->set_message('check_checkpin', $str); 
				return false;
			}
		}
		
		##make payment-toatal amount to be pay.(Prafull)
		public function payment() {
			$last = $this->uri->total_segments();
			$enexamcode = $this->uri->segment($last);
			$examcode = base64_decode($enexamcode);
			if( !empty( $enexamcode ) && is_numeric( $examcode ) ) {
				$regnoarr = $this->input->post('chkmakepay');
				$data['result'] = array();
				if( is_array($regnoarr) && count( $regnoarr ) > 0 ) {
					foreach( $regnoarr as $id ) {
						//print_r("hiiii");
						$this->db->where('dra_members.isdeleted',0);
						$this->db->join('dra_members','dra_member_exam.regid = dra_members.regid');
						$this->db->where('dra_member_exam.dra_memberexam_delete',0);
						$this->db->where('dra_member_exam.id',$id);
						//$res = $this->master_model->getRecords("dra_member_exam",array('dra_member_exam.exam_code'=>$examcode));
						$res = $this->master_model->getRecords("dra_member_exam");
						//echo $this->db->last_query().'<br>';
						
						if($res) {
							$data['result'][] = $res['0'];
						}
					}
					/*echo '<pre>';
						print_r($res );
					exit;*/
					$regnostr = implode('|',$regnoarr);
					$regnostrencd = base64_encode($regnostr);
					$data['regnostrencd'] = $regnostrencd;	
					$data['examcode'] = $examcode;
					// TO do: Candidate list
					$data["middle_content"] = 'training_batch_payment_page';
					/* send active exams for display in sidebar */
					$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
					$res = $this->master_model->getRecords("dra_exam_master a");
					$data['active_exams'] = $res;
					$this->load->view('iibfdra/common_view',$data);
					} else {
					$this->session->set_flashdata('error','Please select at least one candidate to pay');
					redirect(base_url().'iibfdra/TrainingBatches/allapplicants/?exCd='.$enexamcode);
				}
				} else {
				redirect(base_url().'iibfdra/');
			}
		}
		
		##make neft-.(Prafull)
		public function make_neft() {
			
			// TO do:
			$state_code=$GsTIN_no=$centre_id='';
			$state_code=$GsTIN_no='';
			if(isset($_POST['processPayment']) && $_POST['processPayment'])
			{
				
				$this->form_validation->set_rules('utr_no','NEFT / RTGS (UTR) Number','required|trim');
				$this->form_validation->set_rules('payment_date','Payment Date','required');
				$this->form_validation->set_rules('utr_slip','UTR Slip','required');
				$this->form_validation->set_rules('center_id','Center GsTIN no','required');
				if($this->form_validation->run()==TRUE) 
				{	
					// upload UTR slip
					$outpututrslip1 = '';
					if( isset( $_POST["hiddenutrslip"] ) && !empty($_POST["hiddenutrslip"]) ) 
					{
						$date = date('Y-m-d h:i:s');
						$inpututrslip = $_POST["hiddenutrslip"];
						$tmp_utrslip = strtotime($date).rand(0,100);
						$outpututrslip = getcwd()."/uploads/iibfdra/utrslip_".$tmp_utrslip.".jpg";
						$outpututrslip1 = base_url()."uploads/iibfdra/utrslip_".$tmp_utrslip.".jpg";
						file_put_contents($outpututrslip, file_get_contents($inpututrslip));
					}
					//$dra_mem_list = "441021@447980@446046";
					//$dra_mem_array = explode("@", "441021@447980@446046");
					$amount =  base64_decode($_POST['tot_fee']);
					$dra_mem_list = base64_decode($_POST['regNosToPay']);
					$dra_mem_array = explode("|", $dra_mem_list);
					
					$examcode = base64_decode($_POST['exam_code']);
					$examperiod = base64_decode($_POST['exam_period']);
					$instdata = $this->session->userdata('dra_institute');
					$inst_code = $instdata['institute_code']; 
					
					//code added by swati for gstin no sate chnage
					$center_data=$this->input->post('center_id');
					if($center_data == "-")
					{
						$GsTIN_no='-';
						$state_code=$instdata['ste_code'];
					}
					elseif(empty($center_data))
					{
						$GsTIN_no='-';
						$state_code=$instdata['ste_code'];
					}
					elseif($center_data == "Institute")
					{
						$GsTIN_no=$instdata['gstin_no'];
						$state_code=$instdata['ste_code'];
					}
					else
					{
						$c_data=explode('_', $center_data);
						if($c_data[0] != '')
						{
							$GsTIN_no=$c_data[0];
						}
						else
						{
							$GsTIN_no=$instdata['gstin_no'];
						}
						$centre_id=$c_data[1];
						if($c_data[1] != '')
						{
							$this->db->select('agency_center.*,city_master.city_name');
							$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
							$this->db->join('city_master','city_master.id=agency_center.city','left');
							$this->db->group_by('agency_center.center_id');		
							$agency_center = $this->master_model->getRecords('agency_center',array('center_id'=>$c_data[1]));
							
							$state_code=$agency_center[0]['state'];
						}
						else
						{
							$state_code=$instdata['ste_code'];
						}
					}
					//end
					
					//by swati-----------
					
					$regnumber_arr = array();
					$total_member_cnt = count($dra_mem_array);
					$free_member_cnt = 0;
					foreach($dra_mem_array as $memexamid)
					{
						$this->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
						$dra_member_exam = $this->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber, fee_paid_flag'); //'dra_member_exam.exam_fee !='=>0,
						//echo $this->db->last_query();
						
						if($dra_member_exam[0]['fee_paid_flag'] != 'F') //CONDITION ADDED BY SAGAR ON 07-10-2020
						{ 
							$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
						}
						else { $free_member_cnt++; }
					}
					//print_r($regnumber_arr); die;
					$app_category = array();
					
					$unit_R=$base_total_R=0;
					$unit_S1=$base_total_S1=0;
					$unit_B1=$base_total_B1=0;
					if(count($regnumber_arr) > 0)
					{
						foreach($regnumber_arr as $regnumber_arr)
						{						
							if($regnumber_arr != '')
							{
								$dra_eligible_master = $this->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$examcode,'eligible_period'=>$examperiod),'app_category');
								
								if(count($dra_eligible_master) > 0)
								{								
									if($dra_eligible_master[0]['app_category'] == 'R' || $dra_eligible_master[0]['app_category'] == 'B1')
									{
										$unit_R=$unit_R+1;
										
									}
									elseif($dra_eligible_master[0]['app_category'] == 'S1')
									{
										$unit_S1=$unit_S1+1	;
									}
									else
									{
										$unit_R=$unit_R+1;
									}
								}
								else
								{								
									$unit_R=$unit_R+1;
								}
							}
							else
							{
								$unit_R=$unit_R+1;
							}
							
							if(isset($dra_eligible_master[0]['app_category']))
							{
								$app_category[] = $dra_eligible_master[0]['app_category'];
							}
							else
							{
								$app_category[] = 'B1';
							}
						}
					}
					//end swati=============				
					// Create transaction
					$insert_data = array(
					'amount'           => $amount,
					'gateway'          => 1,  // 1= NEFT / RTGS
					'UTR_no'		   => $this->input->post('utr_no'),
					'UTR_slip_file'    => $outpututrslip1,
					'inst_code'		   => $inst_code,
					'date'             => $this->input->post('payment_date'),
					'pay_count'        => count($dra_mem_array),
					'exam_code'        => $examcode,  // TO DO:
					'exam_period'      => $examperiod,  // TO DO:
					'status'           => '3' //applied for approval by dra admin
					);
					$pt_id = $this->master_model->insertRecord('dra_payment_transaction', $insert_data, true);
					//$pt_id = "DP9878280".$pt_id;
					$update_data = array(
					'receipt_no' => $pt_id
					);
					//print_r($update_data); exit;
					$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('id'=>$pt_id));
					
					/* Start Dyanamic Fees allocation - Bhushan */
					// get logged in institute details from session
					$instdata = $this->session->userdata('dra_institute');
					$instStateCode = $instdata['ste_code'];
					
					//get state name, state_no from state master by state code
					$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$state_code,'state_delete'=>'0'));
					
					$totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = $cess = 0;
					
					// insert the dra member id in 'dra_member_payment_transaction' table
					foreach ($dra_mem_array as $dra_mem_id)
					{
						$insert_mpt_data = array(
						'memexamid' => $dra_mem_id,
						'ptid'  => $pt_id
						);
						$this->master_model->insertRecord('dra_member_payment_transaction', $insert_mpt_data);
						//update status in dra_member_exam table
						$updtmemexam_data = array('pay_status'=>3);
						$this->master_model->updateRecord('dra_member_exam',$updtmemexam_data,array('id'=>$dra_mem_id));
						
						/* Get reg id   */
						$this->db->where('id', $dra_mem_id);
						$getRegId = $this->master_model->getRecords('dra_member_exam', '', 'regid, fee_paid_flag');
						$RegId = $getRegId[0]['regid'];
						$fee_paid_flag = $getRegId[0]['fee_paid_flag'];						
						
						/* Get Member Number  */
						$registrationtype = "NM";
						$this->db->where('regid', $RegId);
						$getMemberNo = $this->master_model->getRecords('dra_members', '', 'regnumber,registrationtype');
						$member_no = $getMemberNo[0]['regnumber']; 
						$registrationtype = $getMemberNo[0]['registrationtype']; // NM,O
						
						/* Get Group Code */
						$group_code = "B1";
						if($member_no != 0 && $member_no != "")
						{
							$this->db->where('member_no', $member_no);
							$getGrpCd = $this->master_model->getRecords('dra_eligible_master', '', 'app_category');
							if(count($getGrpCd)>0)
							{
								$group_code = $getGrpCd[0]['app_category']; 
								if($group_code != "")
								{
									if($group_code == "R")
									{
										$group_code = "B1";
									}
								}
							}
							else
							{
								$group_code = "B1";
							}
						}
						
						if($fee_paid_flag != 'F')
						{
							//get fees details from fee master
							$this->db->select('dra_fee_master.*');
							$this->db->where('dra_fee_master.member_category',$registrationtype);
							$this->db->where('dra_fee_master.group_code',$group_code);
							$this->db->where('dra_fee_master.exempt','NE'); 
							$this->db->where('dra_fee_master.exam_code',$examcode);
							$this->db->where('dra_fee_master.exam_period',$examperiod);
							$dra_fee_master=$this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
							/* echo $this->db->last_query(); exit; */
							$totol_amt = $totol_amt + $dra_fee_master[0]['fee_amount'];
							
							if($state_code == 'MAH')
							{
								$total_cgst_amt = $total_cgst_amt + $dra_fee_master[0]['cgst_amt'];
								$total_sgst_amt = $total_sgst_amt + $dra_fee_master[0]['sgst_amt'];
							}
							else
							{
								$total_igst_amt = $total_igst_amt + $dra_fee_master[0]['igst_amt'];
							}
						}						
					}
					
					$fee_amt = $totol_amt; // Total amount without any GST
					//echo $fee_amt ; die;
					$tax_type = '';
					if($state_code == 'MAH')
					{
						//set a rate (e.g 9%,9% or 18%)
						$cgst_rate = $this->config->item('cgst_rate');
						$sgst_rate = $this->config->item('sgst_rate');
						//set an amount as per rate
						$cgst_amt = $total_cgst_amt;
						$sgst_amt = $total_sgst_amt;
						$cs_total = $amount;
						$tax_type = 'Intra';
					}
					else
					{
						//set a rate (e.g 9%,9% or 18%)
						$igst_rate = $this->config->item('igst_rate');
						$igst_amt = $total_igst_amt;
						$igst_total = $amount;
						$tax_type = 'Inter';
					}	
					$no_of_members_payment = count($dra_mem_array);
					/* Dyanamic Fees allocation End - Bhushan */
					
					if($amount > 0) //CONDITION ADDED BY SAGAR ON 07-10-2020
					{
						//$fee_amt = $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = 0;
						$invoice_insert_array = array(
						'pay_txn_id' => $pt_id,
						'receipt_no' => $pt_id,
						'exam_code' => $examcode,
						'exam_period' => $examperiod,
						'center_code' => $centre_id,
						'state_of_center' => $state_code,
						'institute_code' => $instdata['institute_code'],
						'institute_name' => $instdata['institute_name'],
						'app_type' => 'I', // I for DRA Exam Invoice
						'tax_type' => $tax_type,
						'service_code' => $this->config->item('exam_service_code'),
						'gstin_no' => $GsTIN_no,
						'qty' => count($dra_mem_array) - $free_member_cnt,
						'state_code' => $draInstState[0]['state_no'],
						'state_name' => $draInstState[0]['state_name'],
						'fresh_fee' => 1500,
						'rep_fee' => 1200,
						'fresh_count' => $unit_R,
						'rep_count' => $unit_S1,
						'fee_amt' => $fee_amt,
						'cgst_rate' => $cgst_rate,
						'cgst_amt' => $cgst_amt,
						'sgst_rate' => $sgst_rate,
						'sgst_amt' => $sgst_amt,
						'igst_rate' => $igst_rate,
						'igst_amt' => $igst_amt,
						'cs_total' => $cs_total,
						'igst_total' => $igst_total,
						'cess' => $cess,
						'exempt' => $draInstState[0]['exempt'],
						'transaction_no' => $this->input->post('utr_no'),
						'created_on' => date('Y-m-d H:i:s')
						);
						/*echo "<pre>";
							print_r($invoice_insert_array);
						exit;*/					
						$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
						
						log_dra_user($log_title = "Add DRA Exam Invoice Successful", $log_message = serialize($invoice_insert_array));
					}
					
					// manage NEFT/RTGS transaction
					//$this->load->view('pg_sbi_form',$data);
					if( $pt_id ) 
					{
						$this->session->set_flashdata('success','NEFT/RTGS Payment is added and sent for approval');
						redirect(base_url().'iibfdra/TrainingBatches/draexamapplicants/?exCd='.base64_encode($examcode));
					}
				} 
				else 
				{
					$data['validation_errors'] = validation_errors(); 
				}
			}
			else
			{
				/*-----------------Code by swati---------------*/
				$this->db->select('agency_center.*,city_master.city_name');
				$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
				$this->db->join('city_master','city_master.id=agency_center.city','left');
				$this->db->group_by('agency_center.center_id');		
				$agency_center = $this->master_model->getRecords('agency_center',array('institute_code'=>$_SESSION['dra_institute']['institute_code']),'',array('center_status'=>'ASC'));
				
				$resultarr = array();
				//print_r($agency_center); die;
				if(count($agency_center) > 0)
				{
					foreach ($agency_center as $agency_center) 
					{						
						$where = "FIND_IN_SET('".$agency_center['center_id']."', centers_id)";  
						
						$this->db->where($where);
						$this->db->where_in('centers_id',$agency_center['center_id']);
						$this->db->order_by('agency_renew_id',"desc");
						$this->db->limit(1);
						$agency_center_renew = $this->db->get('agency_center_renew');
						$agency_center_renew =$agency_center_renew->result_array();
						if(count($agency_center_renew) > 0)
						{							
							$agency_center['renew_pay_status'] = $agency_center_renew[0]['pay_status'];
							$agency_center['renew_type'] = $agency_center_renew[0]['renew_type'];
							$resultarr[] = $agency_center;
						}
						else
						{
							$agency_center['renew_pay_status'] = '';
							$agency_center['renew_type'] = "";
							$resultarr[] = $agency_center;
						}
					}
				}
				
				
				$this->db->select('dra_accerdited_master.*');
				$this->db->where('dra_accerdited_master.institute_code',$_SESSION['dra_institute']['institute_code']);
				$inst_registration_info = $this->master_model->getRecords('dra_accerdited_master');
				//print_r($inst_registration_info); die;
				$data['inst_registration_info'] = $inst_registration_info;
				
				$data['agency_center'] = $resultarr;
				
				/*-----------------end Code by swati---------------*/
				
				
				
				if( isset( $_POST['regNosToPay'] ) && isset( $_POST['tot_fee'] ) && isset( $_POST['exam_code'] ) && isset( $_POST['exam_period'] ) ) 
				{
					$data["regNosToPay"] = $this->input->post('regNosToPay');
					$data["tot_fee"] = $this->input->post('tot_fee');
					$data["exam_code"] = $this->input->post('exam_code');
					$data["exam_period"] = $this->input->post('exam_period');
					$data["middle_content"] = 'training_make_neftpayment_page';
					/* send active exams for display in sidebar */
					$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
					$res = $this->master_model->getRecords("dra_exam_master a");
					$data['active_exams'] = $res;
					$this->load->view('iibfdra/common_view',$data);
				} 
				else 
				{
					redirect(base_url().'iibfdra/TrainingBatches/make_neft');
				}
			}
		}
		
		public function getcount_utrno()
		{
			$utr_no = $this->input->post('utr_no');
			$exists = $this->master_model->getRecords('dra_payment_transaction',array('UTR_no'=>$utr_no));
			//echo $this->db->last_query();
			$count = count($exists);
			// echo $count 
			if (empty($count)) 
			{
				echo "success";
			} 
			else 
			{
				echo "error";
			}
		}
		
		##make online payment.(Prafull)
		public function make_payment() {
			
			// TO do:
			//print_r($_POST);
			if(isset($_POST['processPayment']) && $_POST['processPayment'])
			{
				include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				
				$key          = $this->config->item('sbi_m_key');
				$merchIdVal   = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				
				$pg_success_url = base_url()."iibfdra/DraExam/sbitranssuccess";
				$pg_fail_url    = base_url()."iibfdra/DraExam/sbitransfail";
				
				$amount =  base64_decode($_POST['tot_fee']);
				//$dra_mem_list = "441021@447980@446046";
				$dra_mem_list = base64_decode($_POST['regNosToPay']);
				//$dra_mem_array = explode("@", "441021@447980@446046");
				$dra_mem_array = explode("|", $dra_mem_list);
				$examcode = base64_decode($_POST['exam_code']);
				$examperiod = base64_decode($_POST['exam_period']);
				$instdata = $this->session->userdata('dra_institute');
				$inst_code = $instdata['institute_code']; 
				
				//$MerchantOrderNo = generate_dra_order_id();
				
				//Apply DRA exam
				//Ref1 = orderid
				//Ref2 = iibfexam
				//Ref3 = iibfdra
				//Ref4 = exam_code + examyear + exammonth ex (101201602)
				$exam_month_year = $this->master_model->getRecords('dra_misc_master',array('exam_code'=>$examcode,'exam_period'=>$examperiod));
				
				if( count($exam_month_year) > 0 ) {				
					$ref4 = $examcode.$exam_month_year[0]['exam_month'];
				}
				else
				{
					$ref4 = "";
				}
				//by swati-----------
				
				$regnumber_arr = array();
				foreach($dra_mem_array as $memexamid){
					$this->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
					$dra_member_exam = $this->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber');
					$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
				}
				//print_r($regnumber_arr); die;
				$app_category = array();
				
				$unit_R=$base_total_R=0;
				$unit_S1=$base_total_S1=0;
				$unit_B1=$base_total_B1=0;
				foreach($regnumber_arr as $regnumber_arr){
					
					if($regnumber_arr != ''){
						
						
						$dra_eligible_master = $this->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$examcode,'eligible_period'=>$examperiod),'app_category');
						
						
						if(count($dra_eligible_master) > 0){
							
							if($dra_eligible_master[0]['app_category'] == 'R' || $dra_eligible_master[0]['app_category'] == 'B1'){
								$unit_R=$unit_R+1;
								
								}elseif($dra_eligible_master[0]['app_category'] == 'S1'){
								$unit_S1=$unit_S1+1	;
								}else{
								$unit_R=$unit_R+1;
								
							}
							
						}
						else{
							
							$unit_R=$unit_R+1;
							
						}
					}
					else{
						$unit_R=$unit_R+1;
					}
					
					if(isset($dra_eligible_master[0]['app_category'])){
						$app_category[] = $dra_eligible_master[0]['app_category'];
						}else{
						$app_category[] = 'B1';
					}
				}
				//end swati=============
				// Create transaction
				$insert_data = array(
				'amount'           => $amount,
				'gateway'          => 2,  // 2 = SBI ePay
				'inst_code'		   => $inst_code,
				'date'             => date('Y-m-d h:i:s'),
				'pay_count'        => count($dra_mem_array),
				'exam_code'        => $examcode,  // TO DO:
				'exam_period'      => $examperiod,  // TO DO:
				'status'           => '2',
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>		'iibfdra',
				//'pg_other_details'=>$custom_field
				
				);
				//print_r($insert_data); die("test");
				
				$pt_id = $this->master_model->insertRecord('dra_payment_transaction', $insert_data, true);
				
				$MerchantOrderNo = dra_sbi_order_id($pt_id);
				
				$custom_field = $MerchantOrderNo."^iibfexam^iibfdra^".$ref4;
				
				// update receipt no. in dra payment transaction -
				$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
				$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('id'=>$pt_id));
				
				/* Start Dyanamic Fees allocation - Bhushan */
				// get logged in institute details from session
				$instdata = $this->session->userdata('dra_institute');
				$instStateCode = $instdata['ste_code'];
				
				//get state name, state_no from state master by state code
				$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$instStateCode,'state_delete'=>'0'));
				
				$totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = $cess = 0;
				
				// insert the dra member id in 'dra_member_payment_transaction' table
				foreach ($dra_mem_array as $dra_mem_id)
				{
					$insert_mpt_data = array(
					'memexamid' => $dra_mem_id,
					'ptid'  => $pt_id
					);	
					$this->master_model->insertRecord('dra_member_payment_transaction', $insert_mpt_data);
					
					/* Get reg id   */
					$this->db->where('id', $dra_mem_id);
					$getRegId = $this->master_model->getRecords('dra_member_exam', '', 'regid');
					$RegId = $getRegId[0]['regid'];
					
					/* Get Member Number  */
					$registrationtype = "NM";
					$this->db->where('regid', $RegId);
					$getMemberNo = $this->master_model->getRecords('dra_members', '', 'regnumber,registrationtype');
					$member_no = $getMemberNo[0]['regnumber']; 
					$registrationtype = $getMemberNo[0]['registrationtype']; // NM,O
					
					/* Get Group Code */
					$group_code = "B1";
					if($member_no != 0 && $member_no != ""){
						$this->db->where('member_no', $member_no);
						$getGrpCd = $this->master_model->getRecords('dra_eligible_master', '', 'app_category');
						if(count($getGrpCd)>0){
							$group_code = $getGrpCd[0]['app_category']; 
							if($group_code != ""){
								if($group_code == "R"){
									$group_code = "B1";
								}
							}
							}else{
							$group_code = "B1";
						}
					}
					//get fees details from fee master
					$this->db->select('dra_fee_master.*');
					$this->db->where('dra_fee_master.member_category',$registrationtype);
					$this->db->where('dra_fee_master.group_code',$group_code);
					$this->db->where('dra_fee_master.exempt','NE'); 
					$this->db->where('dra_fee_master.exam_code',$examcode);
					$this->db->where('dra_fee_master.exam_period',$examperiod);
					$dra_fee_master=$this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
					
					$totol_amt = $totol_amt + $dra_fee_master[0]['fee_amount'];
					
					if($instdata['ste_code'] == 'MAH'){
						$total_cgst_amt = $total_cgst_amt + $dra_fee_master[0]['cgst_amt'];
						$total_sgst_amt = $total_sgst_amt + $dra_fee_master[0]['sgst_amt'];
					}
					else{
						$total_igst_amt = $total_igst_amt + $dra_fee_master[0]['igst_amt'];
					}
					
				}
				$fee_amt = $totol_amt; // Total amount without any GST
				
				$tax_type = '';
				if($instdata['ste_code'] == 'MAH'){
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					//set an amount as per rate
					$cgst_amt = $total_cgst_amt;
					$sgst_amt = $total_sgst_amt;
					$cs_total = $amount;
					$tax_type = 'Intra';
					}else{
					//set a rate (e.g 9%,9% or 18%)
					$igst_rate = $this->config->item('igst_rate');
					$igst_amt = $total_igst_amt;
					$igst_total = $amount;
					$tax_type = 'Inter';
				}	
				$no_of_members_payment = count($dra_mem_array);
				/* Dyanamic Fees allocation End - Bhushan */
				
				$invoice_insert_array = array(
				'pay_txn_id' => $pt_id,
				'receipt_no' => $MerchantOrderNo,
				'exam_code' => $examcode,
				'exam_period' => $examperiod,
				'state_of_center' => $instStateCode,
				'institute_code' => $instdata['institute_code'],
				'institute_name' => $instdata['institute_name'],
				'app_type' => 'I', // I for DRA Exam Invoice
				'tax_type' => $tax_type, 
				'service_code' => $this->config->item('exam_service_code'),
				'gstin_no' => $instdata['gstin_no'],
				'qty' => $no_of_members_payment,
				'state_code' => $draInstState[0]['state_no'],
				'state_name' => $draInstState[0]['state_name'],
				'fresh_fee' => 1500,
				'rep_fee' => 1200,
				'fresh_count' => $unit_R,
				'rep_count' => $unit_S1,
				'fee_amt' => $fee_amt,
				'cgst_rate' => $cgst_rate,
				'cgst_amt' => $cgst_amt,
				'sgst_rate' => $sgst_rate,
				'sgst_amt' => $sgst_amt,
				'igst_rate' => $igst_rate,
				'igst_amt' => $igst_amt,
				'cs_total' => $cs_total,
				'igst_total' => $igst_total,
				'cess' => $cess,
				'exempt' => $draInstState[0]['exempt'],
				'created_on' => date('Y-m-d H:i:s')
				);
				
				$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
				
				log_dra_user($log_title = "Add DRA Exam Invoice Successful", $log_message = serialize($invoice_insert_array));
				
				$MerchantCustomerID = "12345";  // exam code
				$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
				$data["merchIdVal"]  = $merchIdVal;
				
				/* 
					requestparameter=
					MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
					PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
					MerchantCustomerID | Paymode | Accesmedium | TransactionSource
					
					Ex.
					requestparameter
					=1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
				*/
				
				$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
				
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				
				$EncryptTrans = $aes->encrypt($EncryptTrans);
				
				$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				//exit;//added for testing
				$this->load->view('pg_sbi_form',$data);
			}
			else
			{
				//$data["regNosToPay"] = '441021@447980@446046@';
				//$data["tot_fee"] = 'Mw==';
				//$data["t"] = 't';
				//die('test '.);
				if( isset( $_POST['regNosToPay'] ) && isset( $_POST['tot_fee'] ) && isset( $_POST['exam_code'] ) && isset( $_POST['exam_period'] ) ) {
					$data["regNosToPay"] = $this->input->post('regNosToPay');
					$data["tot_fee"] = $this->input->post('tot_fee');
					$data["exam_code"] = $this->input->post('exam_code');
					$data["exam_period"] = $this->input->post('exam_period');
					$this->load->view('iibfdra/make_payment_page',$data);
					} else {
					redirect(base_url().'iibfdra/');
				}
			}
		}
		
		##list number of candidate after the NEFT
		public function draexamapplicants() {
			if( isset( $_GET['exCd'] ) ) {
				
				$login_agency=$this->session->userdata('dra_institute');
				$agency_id=$login_agency['dra_inst_registration_id'];
				
				
				$examcode = trim( $_GET['exCd'] );
				$decdexamcode = base64_decode($examcode);
				if(!intval($decdexamcode))
				{
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				$decdexamcode = intval($decdexamcode);
				//check if exam exists or not
				$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));
				if( $examcount > 0 ) {
					//check if exam is active or not
					$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time,exam_period');
					if( count($examact) > 0 ) {
						//$comp_currdate = date('Y-m-d H:i:s');
						//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
						//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
						$comp_currdate = date('Y-m-d');
						$comp_frmdate = $examact[0]['exam_from_date'];
						$comp_todate = $examact[0]['exam_to_date'];
						if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) 
						{
							$data['result'] = array();
							$per_page = 50;
							$last = $this->uri->total_segments();
							$start = 0;
							$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
							$searchText = '';
							$searchBy = '';
							$field = $value = $sortkey = $sortval = '';
							if($page!=0) {	
								$start = $page-1;	
							}
							$instdata = $this->session->userdata('dra_institute');
							$instcode = $instdata['institute_code'];
							//added
							
							
							
							$query = $this->db->query("SELECT a.batch_name,a.batch_code,a.agency_id,a.batch_from_date,a.batch_to_date,d.firstname,d.batch_id,d.inst_code, d.middlename, d.regid,d.lastname,d.regnumber, d.dateofbirth, d.email, d.registrationtype,d.state,d.pincode,d.city,d.district,d.qualification,d.scannedphoto,d.scannedsignaturephoto,d.idproof,d.idproofphoto,d.training_certificate,d.quali_certificate,d.image_path,ac.location_name
							FROM agency_batch a
							LEFT JOIN dra_members d
							ON a.id = d.batch_id AND d.inst_code = $instcode  
							
							
							LEFT JOIN agency_center ac
							ON a.center_id = ac.center_id
							
							where d.isdeleted = 0 AND d.batch_id != 0  AND d.re_attempt < 3 AND d.inst_code = $instcode   AND a.agency_id = $agency_id  AND a.batch_status='A'  AND d.excode IN(0,".$decdexamcode.") AND NOT EXISTS (SELECT el.member_no
							FROM   dra_eligible_master el
							WHERE el.exam_status IN('F','P') AND el.member_no = d.regnumber AND el.member_no !='' ) 
							order by d.regid DESC 
							
							") ;
							
							$res = $query->result_array();
							// print_r($res); die;
							//echo $this->db->last_query();die;
							
							//fetch fail member record from eligible master 
							
							$eligible = $this->db->query("SELECT e.*,d.regnumber,d.firstname,d.middlename,d.lastname,d.dateofbirth, d.email, d.registrationtype,d.batch_id,d.excode,d.re_attempt,d.inst_code,d.regid,d.state,d.pincode,d.city,d.district,d.qualification,d.scannedphoto,d.scannedsignaturephoto,d.idproof,d.idproofphoto,d.training_certificate,d.quali_certificate,d.image_path,a.batch_to_date,a.batch_from_date,a.batch_name,a.batch_code,s.exam_date,em.trg_value
							FROM dra_eligible_master e
							LEFT JOIN dra_members d
							ON e.member_no = d.regnumber
							AND e.exam_code = d.excode
							
							
							
							LEFT JOIN agency_batch a
							ON a.id = d.batch_id
							
							LEFT JOIN dra_subject_master s
							ON s.exam_code = e.exam_code
							
							LEFT JOIN dra_misc_master em
							ON em.exam_code = e.exam_code        
							
							where d.isdeleted = 0 AND d.batch_id != 0 AND e.app_category != '' AND d.re_attempt < 3 AND d.inst_code = $instcode  AND e.exam_code = $decdexamcode AND e.exam_status = 'F' AND a.batch_status='A' AND e.member_no !=''
							GROUP BY s.exam_code, e.member_no
							order by d.regid desc");
							// echo $this->db->last_query();die;
							$eligible_result1 = $eligible->result_array();
							
							//  $resultc = array_merge($res,$eligible_result); 
							// print_r($resultc);
							// echo $this->db->last_query();die;
							
							
							
							
							
							$resultarr = array();
							if( $res ) {
								foreach( $res as $result ) {
									//if( $result['pay_status'] != 1 ) { //do not include applicants in listing whoes payment is successful
									
									$result['utr_no'] = '';
									$resultarr[] = $result; 
									// if( $result['pay_status'] == 3 || $result['pay_status']=='NULL') { //if payment mode is NEFT and pending for approval by iibf
									// 	$memexamid = $result['mem_examid'];
									// 	//added for neft case
									// 	$this->db->order_by("ptid", "desc");
									// 	$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
									// 	if( $transid ) {
									// 		$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');	
									// 		$result['utr_no'] = $utrno;
									// 		$resultarr[] = $result; 
									// 	}
									// } else { // pay_status is fail-0 or pending-2
									// 	$result['utr_no'] = '';
									// 	$resultarr[] = $result; 
									// }
									//} 	
								}	
							}
							
							
							$eligible_result = array();
							if( $eligible_result1 ) {
								foreach( $eligible_result1 as $result ) {
									//if( $result['pay_status'] != 1 ) { //do not include applicants in listing whoes payment is successful
									$result['utr_no'] = '';
									$resultarr[] = $result; 
									// if( $result['pay_status'] == 3 || $result['pay_status']=='NULL') { //if payment mode is NEFT and pending for approval by iibf
									// 	$memexamid = $result['mem_examid'];
									// 	//added for neft case
									// 	$this->db->order_by("ptid", "desc");
									// 	$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
									// 	if( $transid ) {
									// 		$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');	
									// 		$result['utr_no'] = $utrno;
									// 		$eligible_result[] = $result; 
									// 	}
									// } else { // pay_status is fail-0 or pending-2
									// 	$result['utr_no'] = '';
									// 	$eligible_result[] = $result; 
									// }
									//} 	
								}	
							}
							//print_r($resultarr);
							$data['startidx'] = 1;
							/* Removed pagination on 21-01-2017 */ 
							$data['info'] = $data['links'] = '';
							
							$data['result'] = $resultarr;
							$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
							$medium_master = $this->master_model->getRecords('dra_medium_master',array('dra_medium_master.exam_code'=>$decdexamcode));
							//echo $this->db->last_query();exit;
							$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
							$center_master = $this->master_model->getRecords('dra_center_master',array('exam_name'=>$decdexamcode),'',array('center_name'=>'ASC'));
							$data['startidx'] = 1;
							/* Removed pagination on 21-01-2017 */ 
							$data['info'] = $data['links'] = '';
							
							//$data['result'] = $resultarr;
							//$data['eligible'] = $eligible_result;
							$data['eligible']=array_merge($resultarr,$eligible_result);
							$data['middle_content']	= 'draexam_candidates';
							$data['medium_master'] = $medium_master;
							$data['center_master'] = $center_master;
							$data['examperiods']	= $examact[0]['exam_period'];;
							$data['examcode']	= $decdexamcode;
							/* send active exams for display in sidebar */
							$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
							$res = $this->master_model->getRecords("dra_exam_master a");
							$data['active_exams'] = $res;
							$this->load->view('iibfdra/common_view',$data);
							} else {//if exam is not active
							$this->session->set_flashdata('error','This exam is not active');
							redirect(base_url().'iibfdra/InstituteHome/dashboard');	
						}
						} else { //if exam not found in exam activation master then redirect to home
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					}
					} else { // if exam does not exists redirect to dashboard
					$this->session->set_flashdata('error','This exam does not exists');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');
				}
				
				} else {
				$this->session->set_flashdata('error','URL is edited. Please try again');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		}
		
		/*GET VALUES OF CITY */
		public function getCity() 
		{
			if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
			{
				$state_code = $this->security->xss_clean($this->input->post('state_code'));
				$result = $this->master_model->getRecords('city_master', array('state_code' => $state_code));
				if ($result) 
				{
					echo '<option value="">- Select - </option>';
					foreach ($result AS $data) 
					{
						if ($data) 
						{
							echo '<option value="' . $data['city_name'] . '">' . $data['city_name'] . '</option>';
						}
					}
				} 
				else 
				{
					echo '<option value="">City Not Available, Please select other state</option>';
				}
				
			}
		}	
		
		//import batch data into agency_batch
		public function import_(){
			$this->load->helper('general_agency_helper');// added by MANOJ	
			$ok =1;
			$file = 'uploads/agency_batch.csv';
			$handle = fopen($file, "r");
			$firstlineflag = 0;
			if ($file == NULL) {
				error(_('Please select a file to import'));
				redirect(page_link_to('admin_export'));
			}
			else {
				
				
				while (($filesop = fgetcsv($handle)) !== false) 
				{
					if($firstlineflag > 0)
					{
						$agency_id = $filesop[0];
						$center_id = $filesop[1];
						$batch_type = $filesop[3];
						$hours = $filesop[4];
						$batch_name = $filesop[5];
						$contact_person_name = $filesop[6];
						$contact_person_phone = $filesop[7];
						$batch_from_date = $filesop[8];
						$batch_to_date = $filesop[9];
						$timing_from = $filesop[10];
						$timing_to = $filesop[11];
						$total_candidates = $filesop[12];
						$faculty_name = $filesop[13];
						$faculty_qualification = $filesop[14];
						$name_of_bank = $filesop[15];
						$training_medium = $filesop[16];
						$addressline1 = $filesop[17];
						$addressline2 = $filesop[18];
						$addressline3 = $filesop[19];
						$addressline4 = $filesop[20];
						$pincode = $filesop[21];
						$batch_active_period = $filesop[22];
						$created_on = $filesop[23];
						// example error handling. We can add more as required for the database.
						
						
						// If the tests pass we can insert it into the database.       
						if ($ok ) {
							
							$batch_count = $this->master_model->getRecords('agency_batch', array('agency_id' => $agency_id,'center_id' => $center_id,'batch_from_date' => $batch_from_date,'batch_to_date' => $batch_to_date),'id, batch_name, batch_code');
							
							if(count($batch_count) > 0){
								
								$update_data = array(
								'batch_type'=>$batch_type,
								'hours'=>$hours,
								'batch_name'=>$batch_name,
								'contact_person_name'=>$contact_person_name,
								'contact_person_phone'=>$contact_person_phone,
								'timing_from'=>$timing_from,
								'timing_to'=>$timing_to,
								'total_candidates'=>$total_candidates,
								'faculty_name'=>$faculty_name,
								'faculty_qualification'=>$faculty_qualification,
								'name_of_bank'=>$name_of_bank,
								'training_medium'=>$training_medium,
								'addressline1'=>$addressline1,
								'addressline2'=>$addressline2,
								'addressline3'=>$addressline3,
								'addressline4'=>$addressline4,
								'pincode'=>$pincode,
								'batch_active_period'=>$batch_active_period,
								'updated_on'=>date('Y-m-d H:i:s')
								
								);
								
								$sql= $this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_count[0]['id']));
								
								}else{
								
								$sql = $this->db->query("
								INSERT INTO `agency_batch` SET
								`agency_id`='" .($agency_id) . "',
								`center_id`='" .($center_id) . "',
								`batch_type`='" .($batch_type) . "',
								`hours`='" .($hours) . "',
								`batch_name`='" .($batch_name) . "',
								`contact_person_name`='" .($contact_person_name) . "',
								`contact_person_phone`='" .($contact_person_phone) . "',
								`batch_from_date`='" .($batch_from_date) . "',
								`batch_to_date`='" .($batch_to_date) . "',
								`timing_from`='" .($timing_from) . "',
								`timing_to`='" .($timing_to) . "',
								`total_candidates`='" .($total_candidates) . "',
								`faculty_name`='" .($faculty_name) . "',
								`faculty_qualification`='" .($faculty_qualification) . "',
								`name_of_bank`='" .($name_of_bank) . "',
								`training_medium`='" .($training_medium) . "',
								`addressline1`='" .($addressline1) . "',
								`addressline2`='" .($addressline2) . "',
								`addressline3`='" .($addressline3) . "',
								`addressline4`='" .($addressline4) . "',
								`pincode`='" .($pincode) . "',
								`batch_active_period`='" .($batch_active_period) . "',
								`batch_status`='A',
								`created_on`='" .date('Y-m-d H:i:s') . "'");
								
								$batch_id = $this->db->insert_id();
								//log_dra_agency_center_detail($log_title = "Center Rejected",$center_id,serialize($log_data));
								$batch_config = config_batch_code($batch_id); //batch_code
								$batch_code = 'BTCH1'.$batch_config;					
								$update_data = array('batch_code' => $batch_code);					
								$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
							} }
					}$firstlineflag++;
				}fclose($handle);
				
				if ($sql) {
					echo "You database has imported successfully!";
					//redirect(page_link_to('admin_export'));
					} else {
					echo 'Sorry! There is some problem in the import file.';
					// redirect(page_link_to('admin_export'));
				}
			}
			
		}
		
		//import member data in dra_members
		public function mem_import_(){
			
			
			$ok =1;
			$file = 'uploads/dra_members.txt';
			$handle = fopen($file, "r");
			$firstlineflag = 0;
			if ($file == NULL) {
				echo  'Please select a file to import';
			}
			else {
				
				
				while (($line = fgets($handle)) !== false) 
				{
					if($firstlineflag > 0)
					{
						$filesop = explode("|" ,$line);
						$registration_no = $filesop[0];
						$regnumber = $filesop[1];
						$batch_id = $filesop[2];
						$namesub = $filesop[3];
						$firstname = $filesop[4];
						$middlename = $filesop[5];
						$lastname = $filesop[6];
						$contactdetails = $filesop[7];
						$address1 = $filesop[8];
						$address2 = $filesop[9];
						$address3 = $filesop[10];
						$address4 = $filesop[11];
						$district = $filesop[12];
						$city = $filesop[13];
						$state = $filesop[14];
						$pincode = $filesop[15];
						$dateofbirth = $filesop[16];
						$gender = $filesop[17];
						$qualification = $filesop[18];
						$associatedinstitute = $filesop[19];
						$inst_code = $filesop[20];
						$email = $filesop[21];
						$registrationtype = $filesop[22];
						$stdcode = $filesop[23];
						$phone = $filesop[24];
						$mobile = $filesop[25];
						$aadhar_no = $filesop[26];
						$scannedphoto = $filesop[27];
						$scannedsignaturephoto = $filesop[28];
						$idproof = $filesop[29];
						$idproofphoto = $filesop[30];
						$training_certificate = $filesop[31];
						$quali_certificate = $filesop[32];
						$declaration = $filesop[33];
						$excode = $filesop[34];
						$re_attempt = $filesop[35];
						$createdon = $filesop[36];
						$photo_flg = $filesop[37];
						$signature_flg = $filesop[38];
						$id_flg = $filesop[39];
						$tcertificate_flg = $filesop[40];
						$qualicertificate_flg = $filesop[41];
						$image_path = $filesop[42];
						$training_period_from = $filesop[43];
						$training_period_to = $filesop[44];
						$membership_no = $filesop[45];
						$exam_code_temp = $filesop[46];
						$exam_period_temp = $filesop[47];
						$medium_of_exam = $filesop[48];
						$centre_code = $filesop[49];
						
						// example error handling. We can add more as required for the database.
						
						
						// If the tests pass we can insert it into the database.       
						if ($ok ) {
							
							if($regnumber !=''){
								$update=array(
								're_attempt' => $re_attempt,
								'batch_id' => $batch_id,
								'editedon'=>date('Y-m-d H:i:s') 
								);
								
								$sql = $this->master_model->updateRecord('dra_members',$update,array('regnumber' => $regnumber));
								}else{
								
								$sql = $this->db->query("
								INSERT INTO `dra_members` SET
								`registration_no`='" .($registration_no) . "',
								`regnumber`='" .($regnumber) . "',
								`batch_id`='" .($batch_id) . "',
								`namesub`='" .($namesub) . "',
								`firstname`='" .($firstname) . "',
								`middlename`='" .($middlename) . "',
								`lastname`='" .($lastname) . "',
								`contactdetails`='" .($contactdetails) . "',
								`address1`='" .($address1) . "',
								`address2`='" .($address2) . "',
								`address3`='" .($address3) . "',
								`address4`='" .($address4) . "',
								`district`='" .($district) . "',
								`city`='" .($city) . "',
								`state`='" .($state) . "',
								`pincode`='" .($pincode) . "',
								`dateofbirth`='" .($dateofbirth) . "',
								`gender`='" .($gender) . "',
								`qualification`='" .($qualification) . "',
								`associatedinstitute`='" .($associatedinstitute) . "',
								`inst_code`='" .($inst_code) . "',
								`email`='" .($email) . "',
								`registrationtype`='" .($registrationtype) . "',
								`stdcode`='" .($stdcode) . "',
								`phone`='" .($phone) . "',
								`mobile`='" .($mobile) . "',
								`aadhar_no`='" .($aadhar_no) . "',
								`scannedphoto`='" .($scannedphoto) . "',
								`scannedsignaturephoto`='" .($scannedsignaturephoto) . "',
								`idproof`='" .($idproof) . "',
								`idproofphoto`='" .($idproofphoto) . "',
								`training_certificate`='" .($training_certificate) . "',
								`quali_certificate`='" .($quali_certificate) . "',
								`declaration`='" .($declaration) . "',
								`excode`='" .($excode) . "',
								`re_attempt`='" .($re_attempt) . "',
								`createdon`='" .date('Y-m-d H:i:s') . "',
								`image_path`='" .($image_path) . "'
								");
							}
						}
					}$firstlineflag++;
				}fclose($handle);
				
				if ($sql) {
					echo "You database has imported successfully!";
					//redirect(page_link_to('admin_export'));
					} else {
					echo 'Sorry! There is some problem in the import file.';
					// redirect(page_link_to('admin_export'));
				}
			}
			
		}
		
		//update date of birth wich are 0000.00.00
		public function dateofbirth_import(){
			
			
			$ok =1;
			$file = 'uploads/dra_members.txt';
			$handle = fopen($file, "r");
			$firstlineflag = 0;
			if ($file == NULL) {
				echo  'Please select a file to import';
			}
			else {
				
				
				while (($line = fgets($handle)) !== false) 
				{
					if($firstlineflag > 0)
					{
						$filesop = explode("|" ,$line);
						$namesub = $filesop[0];
						$firstname = $filesop[1];
						$middlename = $filesop[2];
						$lastname = $filesop[3];
						$dateofbirth = $filesop[4];
						$inst_code = $filesop[5];
						
						
						// example error handling. We can add more as required for the database.
						
						
						// If the tests pass we can insert it into the database.       
						if ($ok ) {
							
							$members_count = $this->master_model->getRecords('dra_members', array('namesub' => $namesub,'firstname' => $firstname,'lastname' => $lastname,'inst_code' => $inst_code,'batch_id!=' => ''),'regid');
							
							if(count($members_count) > 0){
								
								$update=array(
								'dateofbirth' => $dateofbirth
								);
								
								$sql = $this->master_model->updateRecord('dra_members',$update,array('regid' => $members_count[0]['regid']));
								}else{
								$sql = 0;
							}
						}
					}$firstlineflag++;
				}fclose($handle);
				
				if ($sql) {
					echo "You database has imported successfully!";
					//redirect(page_link_to('admin_export'));
					} else {
					echo 'Sorry! There is some problem in the import file.';
					// redirect(page_link_to('admin_export'));
				}
			}
			
		}
		
		//check members from dra_members and dra_exam-members which are pending in pay status for old period.
		public function check_double_members(){
			
			
			$this->load->dbutil();
			$this->load->helper('file');
			$this->load->helper('download');
			$delimiter = ",";
			$newline = "\r\n";
			$filename = "DRA_pending_members_.csv";
			$this->db->select('dra_member_exam.regid as d_regid,dra_member_exam.pay_status,dra_member_exam.exam_period,dra_members.regid,dra_members.regnumber');
			$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid','left');
			$this->db->where("dra_member_exam.pay_status!=",'1');
			$this->db->where("dra_member_exam.regid!=",'0');
			$memberData = $this->db->get('dra_member_exam');
			//$query = "SELECT * FROM center_stat"; 
			// $result1 = $this->db->query($query);
			$data = $this->dbutil->csv_from_result($memberData, $delimiter, $newline);
			//$this->db->empty_table('center_stat'); 
			force_download($filename, $data);
		}
		
		//insert centers into agency_center	
		Public function insert_centers(){
			//$cityDetails = $this->master_model->getRecords('city_master');
			$cityDetails1 =$this->db->query("SELECT * FROM `city_master` where NOT EXISTS (SELECT el.location_name FROM agency_center el WHERE el.institute_code=182 AND el.location_name = city_master.id )");
			$cityDetails = $cityDetails1->result_array();
			
			// Get Group Code
			if(count($cityDetails)>0)
			{
				foreach ($cityDetails as $key) {
					$data=array(
					'agency_id' =>205,
					"institute_code" => 735,
					'location_name' => $key['id'],
					'location_address'=> $key['city_name'],
					'address1'=> $key['city_name'],
					'district' =>$key['city_name'],
					'city' => $key['id'],
					'state' => $key['state_code'],
					'inst_type' =>'R',
					"due_diligence" => 'Yes',
					'center_type' =>'R',
					"center_status" => 'A',
					'center_validity_from' =>'2019-04-01',
					'center_validity_to' =>'2020-03-31',
					'center_display_status' => 1,
					'record_period' =>'Offline'
					);
					$center_count = $this->master_model->getRecords('agency_center', array('location_name' => $key['id'], 'institute_code'=> 735,'agency_id'=>205),'center_id');
					
					if(count($center_count) <= 0){
						$center = $this->master_model->insertRecord('agency_center',$data);
						
						
					}
					if($center){
						echo "success<br>";
						
					}
					else{
						echo "fail<br>";
					}
					
				}
			}
		}
		
		//import batch data into agency_batch
		public function change_document(){
			$this->load->helper('general_agency_helper');// added by MANOJ	
			$ok =1;
			$file = 'uploads/member_doc.csv';
			$handle = fopen($file, "r");
			$firstlineflag = 0;
			if ($file == NULL) {
				error(_('Please select a file to import'));
				redirect(page_link_to('admin_export'));
			}
			else {
				
				
				while (($filesop = fgetcsv($handle)) !== false) 
				{
					if($firstlineflag > 0)
					{
						$regnumber = $filesop[0];
						$sacnphoto = $filesop[2];
						$idproof = $filesop[1];
						
						// example error handling. We can add more as required for the database.
						
						
						// If the tests pass we can insert it into the database.       
						if ($ok ) {
							
							if($regnumber != ""){
								
								
								$update_data = array(
								'scannedphoto'=>$sacnphoto,
								'idproofphoto'=>$idproof,
								);
								
								$sql= $this->master_model->updateRecord('dra_members',$update_data,array('regnumber' => $regnumber));
							}
						}
					}$firstlineflag++;
				}fclose($handle);
				
				if ($sql) {
					echo "You database has imported successfully!";
					//redirect(page_link_to('admin_export'));
					} else {
					echo 'Sorry! There is some problem in the import file.';
					// redirect(page_link_to('admin_export'));
				}
			}
			
		}
		
		public function reason_search(){
			$data['middle_content']	= 'dracandidate_reason';
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data['active_exams'] = $res;
			$this->load->view('iibfdra/common_view',$data);
		}
		
		public function member_search(){
			
			$regnumber = $this->input->post('member_no');//897089852;
			
			//   $check1=$this->check_insitutecode($regnumber);
			
			// if($check1 == 0) {
			// 	$data = 0;
			
			//                  echo json_encode($data);
			//                  die();
			// } 
			
			//echo $postData; die;
			$login_agency=$this->session->userdata('dra_institute');
			$agency_id=$login_agency['dra_inst_registration_id'];
			$instcode = $login_agency['institute_code'];
			
			$query = $this->db->query("SELECT e.regid as eregid, a.batch_name,a.batch_code,a.agency_id,a.batch_from_date,a.batch_to_date,a.batch_status,d.firstname,d.batch_id,d.inst_code,d.re_attempt,d.isdeleted, d.middlename,d.excode, d.regid,d.lastname,d.regnumber,  e.exam_fee, e.pay_status, e.id as mem_examid,e.exam_period,s.exam_date,el.*
			FROM dra_members d
			LEFT JOIN agency_batch a
			ON a.id = d.batch_id
			
			LEFT JOIN dra_member_exam e
			ON e.regid = (                                
			SELECT em.id FROM dra_member_exam em
			WHERE d.regid=em.regid
			ORDER BY em.id LIMIT 1
			)
			
			LEFT JOIN dra_eligible_master el
			ON el.member_no = d.regnumber
			
			LEFT JOIN dra_subject_master s
			ON s.exam_code = d.excode
			
			where  d.regnumber = $regnumber 
			order by d.regid DESC 
			");
			
			$res = $query->result_array();
			
			
			
			// print_r($centers->result_array());
			// echo json_encode($data); 
			echo json_encode($res); 
			
		}
		
		//performance invocie
		public function performance_invocie(){
			$fresh_count = $rep_count = $wordamt ='';
			$login_agency=$this->session->userdata('dra_institute');
			$agency_id=$login_agency['dra_inst_registration_id'];
			
			$fresh_count = $this->uri->segment(4);
			$rep_count = $this->uri->segment(5); 
			$institute_info = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id'=>$agency_id),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
			//echo $this->db->last_query(); die;
			$state_info = $this->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
			
			$total_fresh_fee=$fresh_count * 1500;
			$total_rep_fee=$rep_count * 1200;
			$cgst_rate = '9.00';
			$sgst_rate = '9.00';
			$igst_rate = '18.00';
			$cgst = '0.09';
			$sgst = '0.09';
			$igst = '0.18';
			$cs_total = $igst_total =$final_total =$sgst_amt = $cs_amnt= '';
			
			
			
			if($institute_info[0]['ste_code'] == 'MAH'){
				$cgst = '0.09';
				$sgst = '0.09';
				$cs_amnt = ($total_fresh_fee + $total_rep_fee) * ('0.09');
				$sgst_amt = ($total_fresh_fee + $total_rep_fee) * ('0.09');
				$final_total = $cs_amnt + $total_fresh_fee + $total_rep_fee + $sgst_amt;
				$wordamt = $this->pb_amtinword(intval($final_total));
				}elseif($institute_info[0]['ste_code'] != 'MAH'){
				$igst = '0.18';
				$igst_total = ($total_fresh_fee + $total_rep_fee) * ('0.18'); 
				$final_total = $igst_total + $total_fresh_fee + $total_rep_fee ;
				$wordamt = $this->pb_amtinword(intval($final_total));
			}
			
			$date_of_invoice = date("d-m-Y"); 
			$address = $institute_info[0]['address1']." ".$institute_info[0]['address2']." ".$institute_info[0]['address3']." ".$institute_info[0]['address4']." ".$institute_info[0]['address5']." ".$institute_info[0]['address6'];
			
			$data = array('wmt'=>$wordamt,'invoice_no'=>'TEMP_INVOICE_NO','date_of_invoice'=>$date_of_invoice,'transaction_no'=>'TEMP_TRN_NO','recepient_name'=>$institute_info[0]['institute_name'],'address'=>$address,'institute_state'=>$state_info[0]['state_name'],'institute_state_code'=>$state_info[0]['state_no'],'institute_gstn'=>$institute_info[0]['gstin_no'],'fresh_fee_amount'=>'1500.00','rep_fee_amount'=>'1200.00','discount_amt'=>'-','net_amt'=>'-','ste_code'=>$institute_info[0]['ste_code'],'cgst_rate'=>$cgst_rate,'cgst_amt'=>$cs_amnt,'sgst_rate'=>$sgst_rate,'sgst_amt'=>$sgst_amt,'final_total'=>$final_total,'igst_total'=>$igst_total,'invoice_number'=>'TEMP_INVOICE_NO','igst_rate'=>$igst_rate,'total_fresh_fee'=>$total_fresh_fee,'total_rep_fee'=>$total_rep_fee,'fresh_count'=>$fresh_count,'rep_count'=>$rep_count); 
			$this->load->view('iibfdra/transaction/print_inst_receipt_proforma',$data);			
			
		}
		
		function pb_amtinword($amt){
			$number = $amt;
			$no = round($number);
			$point = round($number - $no, 2) * 100;
			$hundred = null;
			$digits_1 = strlen($no);
			$i = 0;
			$str = array();
			$words = array('0' => '', '1' => 'One', '2' => 'Two',
			'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
			'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
			'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
			'13' => 'Thirteen', '14' => 'Fourteen',
			'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
			'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
			'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
			'60' => 'Sixty', '70' => 'Seventy',
			'80' => 'Eighty', '90' => 'Ninety');
			$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
			while ($i < $digits_1) {
				$divider = ($i == 2) ? 10 : 100;
				$number = floor($no % $divider);
				$no = floor($no / $divider);
				$i += ($divider == 10) ? 1 : 2;
				if ($number) {
					$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
					$hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
					$str [] = ($number < 21) ? $words[$number] .
					" " . $digits[$counter] . $plural . " " . $hundred
					:
					$words[floor($number / 10) * 10]
					. " " . $words[$number % 10] . " "
					. $digits[$counter] . $plural . " " . $hundred;
				} else $str[] = null;
			}
			$str = array_reverse($str);
			$result = implode('', $str);
			$points = ($point) ?
			"." . $words[$point / 10] . " " . 
			$words[$point = $point % 10] : '';
			
			return $result;
		}
		
	}
?>