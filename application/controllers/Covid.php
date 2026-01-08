<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	class Covid extends CI_Controller
	{		
    public function __construct()
    {       
			//exit;
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			$this->load->helper('blended_invoice_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			//die;
			/*exit; 
				$tdate = date('Y-m-d');
				if($tdate > '2021-01-13'){
				exit;
			}*/
			
			$this->chk_exam_period = '224';			
			$this->chk_exam_code = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'));			
		}
		
		/* Showing Reschedule Form */
		public function index()
		{		
				$tdate = date('Y-m-d');
				if($tdate > '2022-03-14'){
					exit;
				}
			/*exit;
				
				$tdate = date('Y-m-d');
				if($tdate > '2021-01-13'){
				exit;
				}
				
				$tdate = date('Y-m-d');
				if($tdate == '2021-01-14'){
				exit;
				}	
			*/
			$data = array();
			$var_errors = '';
			$row = '';
			$sql = '';
			$exname = '';
			$compulsory_subjects = '';
			$scannedphoto_file = '';
			
			$this->load->helper('captcha');
      $this->session->set_userdata("regcaptcha", rand(1, 100000));
      $vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => base_url() . 'uploads/applications/'
      );
      $cap = create_captcha($vals);
      $_SESSION["regcaptcha"] = $cap['word'];			
			
			if(isset($_POST['btnGetDetails']))
			{
				$regnumber =  $this->input->post('regnumber');
				
				$this->db->where('mem_mem_no',$regnumber);
				$this->db->where('exm_prd',$this->chk_exam_period);
				$chk_already_apply = $this->master_model->getRecords('reschedule_221_jaiib','','id');
				
				if(count($chk_already_apply) > 0)
				{
					$row = array("msg" => "You have already reschedule exam.");
				}
				else
				{
					$this->db->where('mem_mem_no',$regnumber);
					$this->db->where('exm_prd',$this->chk_exam_period);
					$this->db->where_in('exm_cd',$this->chk_exam_code);
					$this->db->where('remark',1);
					$sql = $this->master_model->getRecords('admit_card_details','','mam_nam_1,center_code,center_name,exm_cd,exm_prd,sub_cd,sub_dsc,venueid,venue_name,venueadd1,venueadd2,venueadd3,venueadd4,venueadd5,mem_mem_no');
					
					if($regnumber == '')
					{
						$row = array("msg" => "Please enter member number.");
					}
					else
					{
						if(count($sql) > 0)
						{
							$this->db->where('exam_code',$sql[0]['exm_cd']);
							$exam_name = $this->master_model->getRecords('exam_master','','description');
							$exname = $exam_name[0]['description'];							
							
							$this->db->where('mem_mem_no',$regnumber);
							$this->db->where('exm_cd',$sql[0]['exm_cd']);
							$this->db->where('exm_prd',$sql[0]['exm_prd']);
							$this->db->where('remark',1);
							$compulsory_subjects = $this->master_model->getRecords('admit_card_details','','sub_cd,sub_dsc,center_code,center_name,venueid,exam_date,time,mem_mem_no',array('sub_cd'=>'ASC'));
						}
						else
						{
							$row = array("msg" => "you are not eligible.");
						}
					}
				}
			}
			
			if (isset($_POST['btnSubmit']))
			{ 
				$this->form_validation->set_rules('candidate_email','Email ID','trim|required|valid_email|xss_clean|callback_check_emailduplication');
				$this->form_validation->set_rules('contact_no','Contact Number','trim|required|numeric|max_length[10]|xss_clean|callback_check_mobileduplication');
				$this->form_validation->set_rules('disability','Disability','trim|required|xss_clean');
				$this->form_validation->set_rules('covid_certificate','Covid certificate','file_required|file_allowed_type[pdf]|file_size_max[1024]|callback_scannedphoto_upload');
								
				if($this->form_validation->run()==TRUE)
				{			   
					if(isset($_FILES['covid_certificate']['name']) &&($_FILES['covid_certificate']['name']!=''))
					{ 
						$img = "covid_certificate";
						$date=date('Y-m-d h:i:s');
						$tmp_nm = strtotime($date).rand(0,100);
						$new_filename = 'covid_doc_'.$tmp_nm.'_'.$this->input->post('mem_mem_no');
						$config=array('upload_path'=>'./uploads/reschudule',
						'allowed_types'=>'pdf',//jpg|jpeg|
						'file_name'=>$new_filename,);	  
						$this->upload->initialize($config);
						
						$size = @filesize($_FILES['covid_certificate']['tmp_name']);
						
						if($size)
						{ 	
							if($this->upload->do_upload($img))
							{
							  $dt=$this->upload->data();
							  $file=$dt['file_name'];
								$scannedphoto_file = $dt['file_name'];
								$outputphoto1 = base_url()."uploads/reschudule/".$scannedphoto_file;
							}
							else
							{
								$var_errors.=$this->upload->display_errors();
							}
						}
						else
						{
							$row = array("msg" => "Please upload file only in pdf format.");
						}
					}
					
					$el_subject = array();
					if(isset($_POST['el_subject']))
					{
						$el_subject = $_POST['el_subject'];
					}					
					
				  $user_data=array(	
					'mem_mem_no'=>$this->input->post('mem_mem_no'),
					'candidate_name'=>$this->input->post('candidate_name'),
					'excd'=>$this->input->post('exm_cd'),
					'exam_name'=>$this->input->post('exam_name'),
					'center_name'=>$this->input->post('center_name'),
					'center_code'=>$this->input->post('center_code'),
					'el_subject'=>$el_subject,
					'candidate_email'=>$this->input->post('candidate_email'),
					'contact_no'=>$this->input->post('contact_no'),
					'disability'=>$this->input->post('disability'),
					'covid_certificate' => $scannedphoto_file
					);
					//print_r($user_data); exit;
				  $this->session->set_userdata('reschedule_examinfo',$user_data);
				  redirect(base_url().'Covid/preview');
				}
			}			
			
			$data = array(
			'middle_content' => 'covid_exam', 'var_errors' => $var_errors, 'image' => $cap['image'],'row' => $row,'result'=>$sql,'exam_name'=>$exname,'compulsory_subjects'=>$compulsory_subjects
			);
      $this->load->view('common_view_fullwidth', $data);
		}		
		
		public function preview()
		{			
			// exit;
			
			// $tdate = date('Y-m-d');
			// if($tdate > '2021-01-13'){
			// 	exit;
			// }
			
			
			// $tdate = date('Y-m-d');
			// if($tdate == '2021-01-14'){
			// exit;
			// }
			
			if(!$this->session->userdata('reschedule_examinfo'))
			{
				redirect(base_url().'Covid');
			}
			
			
			$data = array(
			'middle_content' => 'reschedule_preview'
			);
			$this->load->view('common_view_fullwidth', $data);
		}
		
		public function add_record()
		{			
			// 	exit;
			
			// 	$tdate = date('Y-m-d');
			// 	if($tdate > '2021-01-13'){
			// 		exit;
			// 	}
			
			// 	$tdate = date('Y-m-d');
			// if($tdate == '2021-01-14'){
			// 	exit;
			// }
			
			if(!$this->session->userdata('reschedule_examinfo'))
			{
				redirect(base_url().'Covid');
			}
			
			$insert_info = array();
			foreach($this->session->userdata['reschedule_examinfo']['el_subject'] as $key=>$val)
			{
				$this->db->where('subject_code',$key);
				$sql = $this->master_model->getRecords('subject_master','','subject_description,exam_date');
				
				$insert_info = array(
				'mem_mem_no'=>$this->session->userdata['reschedule_examinfo']['mem_mem_no'],
				'candidate_name'=>$this->session->userdata['reschedule_examinfo']['candidate_name'],
				'contact_no'=>$this->session->userdata['reschedule_examinfo']['contact_no'],
				'candidate_email'=>$this->session->userdata['reschedule_examinfo']['candidate_email'],
				'exm_cd'=>$this->session->userdata['reschedule_examinfo']['excd'],
				'exm_prd'=>$this->chk_exam_period, 
				'sub_cd'=>$key,
				'sub_dsc'=>$sql[0]['subject_description'],
				'exam_date'=>$sql[0]['exam_date'],
				'center_code'=>$this->session->userdata['reschedule_examinfo']['center_code'],
				'center_name'=>$this->session->userdata['reschedule_examinfo']['center_name'],
				'disability'=>$this->session->userdata['reschedule_examinfo']['disability'],
				'covid_certificate'=>$this->session->userdata['reschedule_examinfo']['covid_certificate'],
				);
				
				$this->master_model->insertRecord('reschedule_221_jaiib',$insert_info,true);
			}			
			
			$final_str = 'Hello Sir/Madam <br/><br/>';
			$final_str.= 'Your application successfully submitted';   
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
			
			$info_arr=array('to'=>$this->session->userdata['reschedule_examinfo']['candidate_email'],
			'from'=>'noreply@iibf.org.in',
			'subject'=>'Reschedule Examination',
			'message'=>$final_str
			);
			$this->Emailsending->mailsend($info_arr);			
			
			redirect(base_url().'Covid/success');
		}		
		
		public function success()
		{			
			// 	exit;
			
			// 	$tdate = date('Y-m-d');
			// 	if($tdate > '2021-01-13'){
			// 		exit;
			// 	}
			
			// 	$tdate = date('Y-m-d');
			// if($tdate == '2021-01-14'){
			// 	exit;
			// }
			
			$data = array(
			'middle_content' => 'reschedule_thankyou'
			);
			$this->load->view('common_view_fullwidth', $data);
			
			$this->session->unset_userdata('reschedule_examinfo');	
		}		
		
		public function check_emailduplication($email)
		{
			if($email!="")
			{
				$prev_count=$this->master_model->getRecordCount('reschedule_221_jaiib',array('candidate_email'=>$email)); 
				if($prev_count==0)
				{
					return true;	
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		public function check_mobileduplication($mobile)
		{
			if($mobile!="")
			{
				$prev_count=$this->master_model->getRecordCount('reschedule_221_jaiib',array('contact_no'=>$mobile));
				if($prev_count==0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}		
		
		public function mobileduplication() 
		{
			$mobile = $_POST['mobile'];
			if ($mobile != "") 
			{				
				$prev_count = $this->master_model->getRecordCount('reschedule_221_jaiib', array('contact_no' => $mobile,));
				if ($prev_count == 0) 
				{
					$data_arr = array('ans' => 'ok','str'=>'');
					echo json_encode($data_arr);
				}
				else 
				{
					$data_arr = array('ans' => 'exists');
					echo json_encode($data_arr);
				}
			} 
			else 
			{
				echo 'error';
			}
		}
		
		public function emailduplication() 
		{
			$email = $_POST['email'];
			if ($email != "") 
			{				
				$prev_count = $this->master_model->getRecordCount('reschedule_221_jaiib', array('candidate_email' => $email,));
				if ($prev_count == 0) 
				{
					$data_arr = array('ans' => 'ok','str'=>'');
					echo json_encode($data_arr);
				}
				else 
				{
					$data_arr = array('ans' => 'exists');
					echo json_encode($data_arr);
				}
			} 
			else 
			{
				echo 'error';
			}
		}
		
		public function scannedphoto_upload()
		{
			if($_FILES['covid_certificate']['size'] != 0)
			{
				return true;				
			}  
			else
			{
				$this->form_validation->set_message('scannedphoto_upload', "No pdf file selected");
				return false;
			}
		}		
		
    public function ajax_check_captcha() 
		{
			$code = $_POST['code'];
			// check if captcha is set -
			if ($code == '' || $_SESSION["regcaptcha"] != $code) 
			{
				$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
				echo 'false';
			} 
			else if ($_SESSION["regcaptcha"] == $code) 
			{
				echo 'true';
			}
		}
		
		/* Captcha Validations */
		public function check_captcha_userreg($code)
		{
			if (isset($_SESSION["regcaptcha"])) 
			{
				if ($code == '' || $_SESSION["regcaptcha"] != $code) 
				{
					$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
					return false;
				}
				
				if ($_SESSION["regcaptcha"] == $code) 
				{
					return true;
				}
			} 
			else 
			{
				return false;
			}
		}		
		
		/* Captcha Validations */
		public function ajax_check_captcha_code()
		{
			$code = $_POST['code'];
			if ($code == '' || $_SESSION["regcaptcha"] != $code) 
			{
				echo 'failure';
			} 
			else if ($_SESSION["regcaptcha"] == $code) 
			{
				echo 'success';
			}
		}
		
		/* Generate Validations */
		public function generatecaptchaajax()
		{
			$this->load->helper('captcha');
			$this->session->unset_userdata("regcaptcha");
			$this->session->set_userdata("regcaptcha", rand(1, 100000));
			$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => base_url() . 'uploads/applications/'
			);
			$cap = create_captcha($vals);
			$data = $cap['image'];
			$_SESSION["regcaptcha"] = $cap['word'];
			echo $data;
		}
	}		