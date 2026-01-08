<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DupCert extends CI_Controller
{
	public $UserID;
			
	public function __construct()
	{
			parent::__construct();
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			 $this->load->model('billdesk_pg_model');
		//exit;

	}
	public function index()
	{
		//exit;
		//getting designations
		//$this->db->where('exam_master.exam_delete','0');
				$exam_array=array(31,35,48,49,75,76,77,98,55,990);
		$this->db->where_not_in('exam_code',$exam_array);
		
		//$this->db->where('exam_master.exam_delete','0');
		$exams=$this->master_model->getRecords('exam_master','','',array('description'=>'ASC'));
		
		//$exams=$this->master_model->getRecords('exam_master','','',array('description'=>'ASC'));
		
		
		$this->load->model('Captcha_model');
		$captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
	
		$data=array('middle_content'=>'dup_cert/dup_cert_reg','exams'=>$exams,'image' => $captcha_image);
		$this->load->view('dup_cert/dup_cert_common_view',$data);
	}
	public function getdetails()
	{
		if(empty($_POST))
		{
			redirect(base_url().'DupCert');
		}
		if($this->session->userdata('userinfo'))
		{
			$this->session->unset_userdata('userinfo');
		}
		$data['validation_errors'] = $sel_exam = '';
		
		
					 $member_no = ltrim(rtrim($_POST['member_no'])); 
					$validmem = $this->master_model->getRecords('duplicate_cert_eligible',array('member_no' => $member_no ),'member_no,exam_id');
					 $exam_id = $validmem[0]['exam_id'];
					if(!empty($validmem))
					{
							if($exam_id != '45' && $exam_id != '57')			
							{
								$is_member = $this->master_model->getRecordCount('member_registration',array('regnumber'=>$member_no));
								
								if($is_member > 0)
								{
									$result_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_no,'isactive'=>'1'));
									
									$result_data[0]['is_dra_mem']='1';		
								}
								else if($is_member == 0)
								{
									
									$result_data = $this->master_model->getRecords('member_registration_dbf_dupcert',array('regnumber'=>$member_no));
									
									$result_data[0]['is_dra_mem']='1';
								}
								else {										
										
									$this->session->set_flashdata('error','Your Member Data is not Available.');	
									redirect(base_url().'DupCert');			
									}
								
							}
							
							else             
							{
								
								$is_dra_member = $this->master_model->getRecordCount('dra_members',array('regnumber'=>$member_no));
							
								if($is_dra_member > 0)
								{
									$result_data = $this->master_model->getRecords('dra_members',array('regnumber'=>$member_no));
									//,'isactive'=>'1'
									$result_data[0]['is_dra_mem']='2';
								}						
								else if(empty($is_dra_member))
								{
										$result_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_no,'isactive'=>'1'));

										$result_data[0]['is_dra_mem']='1';
								}
								else {										
										
									$this->session->set_flashdata('error','Your Member Data is not Available.');	
									redirect(base_url().'DupCert');			
									}
								
							}
					
					}
					else 
					{
						$this->session->set_flashdata('error','You are not eligible to apply for Duplicate certificate');
						redirect(base_url().'DupCert');
					}
		if(isset($_POST['btn_Submit']))
		{
		    if(!empty($_POST['member_no']) )
			{
				$config = array(
							array(
									'field' => 'member_no',
									'label' => 'Registration/Membership No.',
									'rules' => 'trim|required'
							),
							array(
									'field' => 'code',
									'label' => 'Code',
									'rules' => 'trim|required|callback_check_captcha_userreg',
							),
						);
		$this->form_validation->set_rules($config);
			$dataarr=array(
				'regnumber'=> mysql_real_escape_string($this->security->xss_clean($this->input->post('member_no'))),
				'isactive'=>'1',
				'isdeleted'=>'0'
			);
				 $request_cnt = $_POST['request_cnt'] + 1;
				
				if($this->form_validation->run()==TRUE)
				{
				}
			else{
				$this->session->set_flashdata('error','Invalid Membership no. or Captcha.');
					redirect(base_url().'DupCert');
			}
			
		}
		}
		if(isset($_POST['btnSubmit']))
		{
			//print_r($_POST);exit;
			 $date=date('Y-m-d h:i:s');
			// $this->form_validation->set_rules('namesub','Candidate Name','trim|max_length[30]|required|xss_clean');
			 $this->form_validation->set_rules('firstname','Candidate Name','trim|max_length[30]|required|xss_clean');
			 if(isset($_POST['middlename']) && $_POST['middlename']!='')
			 {
				$this->form_validation->set_rules('middlename','Candidate Name','trim|max_length[30]|required|xss_clean');
			 }
			  if(isset($_POST['lastname']) && $_POST['lastname']!='')
			 {
				$this->form_validation->set_rules('lastname','Candidate Name','trim|max_length[30]|required|xss_clean');
			 } 
			 $this->form_validation->set_rules('sel_exam','Examination (select the correct name)','trim|required|xss_clean');
			 $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
			 $this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean');
			/* $this->form_validation->set_rules('addressline1','Address line1','trim|max_length[30]|required|xss_clean');
			 if(isset($_POST['addressline2']) && $_POST['addressline2']!='')
			{
				$this->form_validation->set_rules('addressline2','Address line2','trim|max_length[30]|required|xss_clean');
			}
			if(isset($_POST['addressline3']) && $_POST['addressline3']!='')
			{
				$this->form_validation->set_rules('addressline3','Address line3','trim|max_length[30]|required|xss_clean');
			}
			if(isset($_POST['addressline4']) && $_POST['addressline4']!='')
			{
				$this->form_validation->set_rules('addressline4','Address line4','trim|max_length[30]|required|xss_clean');
			}
			$this->form_validation->set_rules('district','District','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('city','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('state_ds','State','trim|required|xss_clean');
			if($this->input->post('state_ds')!='')
			{
				$state=$this->input->post('state_ds');
			}
			//echo $this->input->post('state'); die;
			$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
			*/
			$this->form_validation->set_rules('fees','Fees','trim|required|xss_clean');
			$this->form_validation->set_rules('code','Security Code','trim|required|xss_clean|callback_check_captcha_userreg');
			
			if($this->form_validation->run()==TRUE)
			{
				if(!empty($_POST["member_no"]))
				{
					if(!empty($_POST["state_ds"]))
					{
						if($_POST['state_ds'] =='MAH')
						{  
							$fees = $this->config->item('Dup_cert_cs_total');
						}
						else
						{
							$fees = $this->config->item('Dup_cert_igst_tot');
						}
					}
					
					if(!empty($_POST["sel_exam"]))
					{
						$sel_exam_rs = $this->master_model->getRecords('exam_master',array('exam_code'=>$_POST["sel_exam"]),'description');
						@$sel_exam_name =  strip_tags(@$sel_exam_rs[0]['description']);

//////added by swati
						$sel_exam = $_POST["sel_exam"];
						if($_POST["sel_exam"] == '101')
						{


							$is_member = $this->master_model->getRecordCount('member_exam',array('regnumber'=>$member_no , 'exam_code'=>101));
							$is_member_1 = $this->master_model->getRecordCount('member_exam',array('regnumber'=>$member_no , 'exam_code'=>991));
					
							if($is_member > 0 && $is_member_1 > 0){
								$sel_exam = '101';
							}elseif($is_member > 0)
							{
								$sel_exam = '101';
							}elseif($is_member_1 > 0){
								$sel_exam = '991';
							}else{
								$sel_exam = $_POST["sel_exam"];
							}

							$sel_exam_rs = $this->master_model->getRecords('exam_master',array('exam_code'=>$sel_exam),'description');
						   @$sel_exam_name =  strip_tags(@$sel_exam_rs[0]['description']);
						}
//////added by swati end		
					}
					$member_no = $_POST["member_no"];
					$exp = explode("##",$_POST["sel_exam"]);
					if(!empty($exp))
					{
						$this->db->where('exam_code',$exp[1]);
						$dup_cert_exam_check = $this->master_model->getRecords('duplicate_certificate',array('regnumber'=>$member_no , 'pay_status'=>'1','created_on >' => '2020-12-22'));
						if(!empty($dup_cert_exam_check))
						{
							$this->session->set_flashdata('error','You have already applied for the Duplicate certificate for the said examination.');
					        redirect(base_url().'DupCert');
						}
					}
					$data = array(
						'member_no'=>trim($_POST["member_no"]),
						'registrationtype'=>$_POST["registrationtype"],
						'namesub'=>$_POST["namesub"],
						'firstname'=>$_POST["firstname"],
						'middlename'=>$_POST["middlename"],
						'lastname'=>$_POST["lastname"],
						'sel_exam'=>$exp[1],
						'exam_name'=>$exp[0],
						'email'=>$_POST["email"],
						'mobile'=>$_POST["mobile"],
						//'addressline1'=>$_POST["addressline1"],
						//'addressline2'=>$_POST["addressline2"],
						//'addressline3'=>$_POST["addressline3"],
						//'addressline4'=>$_POST["addressline4"],
						//'district'=>$_POST["district"],
						//'city'=>$_POST["city"],
						'state'=>$_POST["state_ds"],
						'fees'=>$fees,
						//'pincode'=>$_POST["pincode"],
						'is_dra_mem'=>$_POST['is_dra_mem']
						
					);
					//print_r($data); die;
					
					$this->session->set_userdata('userinfo',$data);
					$this->form_validation->set_message('error', "");
					redirect(base_url().'DupCert/preview');
				}
				
			}
		}
		$exam_name ='';
		$exam_name = $this->get_exam_name($_POST["member_no"]);
		
		//getting states
		$this->db->where('state_master.state_delete','0');
		$states = $this->master_model->getRecords('state_master');
		
		//getting exams
		//$this->db->where('exam_master.exam_delete','0');
		$exam_array=array(31,35,48,49,75,76,77,98,55,990);
		$this->db->where_not_in('exam_code',$exam_array);
		$exams=$this->master_model->getRecords('exam_master','','',array('description'=>'ASC'));
		
		//cpatcha generation
		/* $this->load->helper('captcha');
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$_SESSION["regcaptcha"] = $cap['word']; */
		$this->load->model('Captcha_model');
		 $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
		//print_r($exam_name); die;
		if(!empty($result_data))
		{	
			$data = array('middle_content'=>'dup_cert/dup_cert_reg','result'=>$result_data,'exams'=>$exams,'exam_name'=>$exam_name,'states'=>$states,'image' =>$captcha_image); 
			 
			$this->load->view('common_view_fullwidth',$data); 	
		}
		else
		{
			//$this->session->set_flashdata('error','Membership number not exist');
			$this->session->set_flashdata('error','You are not eligible to apply for Duplicate certificate');
			redirect(base_url().'DupCert');
		}
	}
	
	public function get_exam_name_old($member)
	{
		//get exam name and exam count
		//$member = $_POST["member_no"];
		$member = trim($member);
		$exam_name = array();
		$exam_count = $this->master_model->getRecordCount('member_exam' , array('regnumber' =>$member , 'pay_status' => '1'));
		//echo $this->db->last_query(); exit;
		if($exam_count > 0)
		{
			$exam_code = $this->master_model->getRecords('member_exam' , array('regnumber' =>$member , 'pay_status' => '1'),'exam_code');
			if(!empty($exam_code) && ($member != '400106600' && $member != '500088088' ))
			{
				foreach($exam_code as $exres)
				{
					$exam_code = $exres['exam_code'];
					$exam_name[] = $this->master_model->getRecords('exam_master' , array('exam_code' =>$exam_code) , 'description , exam_code');
				}
			}
			else
			{
				$exam_code = $this->master_model->getRecords('duplicate_cert_eligible' , array('member_no' =>$member),'exam_id');
				if(!empty($exam_code))
				{
				foreach($exam_code as $exres)
				{
					$exam_code = $exres['exam_id'];
					$exam_name[] = $this->master_model->getRecords('exam_master' , array('exam_code' =>$exam_code) , 'description , exam_code');
				}
				}
				
			}
		}
		else
		{
			$exam_count = $this->master_model->getRecords('dra_members' , array('regnumber' =>$member ,'isactive'=>'1') , 'regid');
			if(!empty($exam_count))
			{
				$regid = $exam_count[0]['regid'];
				$exam_code = $this->master_model->getRecords('dra_member_exam' , array('regid' => $regid,'pay_status'=>'1') ,'exam_code');
				foreach($exam_code as $exres)
				{
					$exam_code = $exres['exam_code'];
					$exam_name[] = $this->master_model->getRecords('exam_master' , array('exam_code' =>$exam_code) , 'description , exam_code');
					
				}
			}
			else
			{
				$exam_code = $this->master_model->getRecords('duplicate_cert_eligible' , array('member_no' =>$member),'exam_id');
				if(!empty($exam_code))
				{
				foreach($exam_code as $exres)
				{
					$exam_code = $exres['exam_id'];
					$exam_name[] = $this->master_model->getRecords('exam_master' , array('exam_code' =>$exam_code) , 'description , exam_code');
				}
				}
				else
				{
				$this->session->set_flashdata('error','You have not passed the exam.');
				redirect(base_url().'DupCert');
				}
			}
			
			
		} 
		return $exam_name;
	}
	
	//Exam name as per IIBF eligible
	public function get_exam_name($member)
	{
		//get exam name and exam count
		//$member = $_POST["member_no"];
		$member = trim($member);
		$exam_name = array();
		$exam_count = $this->master_model->getRecordCount('duplicate_cert_eligible' , array('member_no' =>$member));
		//echo $this->db->last_query(); exit;
		if($exam_count > 0)
		{
     			$exam_code = $this->master_model->getRecords('duplicate_cert_eligible' , array('member_no' =>$member),'exam_id,exam_part_no');
				if(!empty($exam_code))
				{
					foreach($exam_code as $exres)
					{
						$exam_code = $exres['exam_id'];
						$exam_part = $exres['exam_part_no'];
						if($exam_code == '1' && $exam_part == '2' )
						{
							$exam_code = '555';
							$exam_name[] = $this->master_model->getRecords('exam_master' , array('exam_code' =>$exam_code) , 'description , exam_code');
						}
						else{
						$exam_name[] = $this->master_model->getRecords('exam_master' , array('exam_code' =>$exam_code) , 'description , exam_code');
						}
					}
				}
				
			
		}
		return $exam_name;
	}
	
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		/* $this->load->helper('captcha');
		$this->session->unset_userdata("regcaptcha");
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["regcaptcha"] = $cap['word'];
		echo $data; */
		$this->load->model('Captcha_model');
		echo $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
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
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
	{
		if(isset($_SESSION["regcaptcha"]))
		{
		if($code == '' || $_SESSION["regcaptcha"] != $code )
		{
			$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
			//$this->session->set_userdata("regcaptcha", rand(1,100000));
			return false;
		}
		if($_SESSION["regcaptcha"] == $code)
		{
			return true;
		}
		}
		else
		{
			return false;
		}
	}
	public function preview()
    {
		$amount = 0;
		if(!$this->session->userdata('userinfo'))
		{
			redirect(base_url());
		}
		
		if($this->session->userdata['userinfo']['is_dra_mem'] == '1')
		{
		    $user_details= $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata['userinfo']['member_no'],'isactive'=>'1'));
			$state = '';
			if(count($user_details)>0)
			{
				if(isset($user_details[0]['state']))
				{
					$state = $user_details[0]['state'];
				}
				
			}
			else{
				$user_details= $this->master_model->getRecords('member_registration_dbf_dupcert',array('regnumber'=>$this->session->userdata['userinfo']['member_no']));
				if(count($user_details)>0)
				{
					if(isset($user_details[0]['state']))
					{
						$state = $user_details[0]['state'];
					}
				}
			}
			
			
		    if($state != '')
				{
					if($state == 'MAH')
					{
					   $amount = $this->config->item('Dup_cert_cs_total');
					}
					else
					{
						$amount = $this->config->item('Dup_cert_igst_tot');
					}
			    $this->session->userdata['userinfo']['fees'] = $amount ;
				}
				
		}
		elseif($this->session->userdata['userinfo']['is_dra_mem'] == '2')
		{
		    $user_details = array();
		}
		
		//getting states
		$this->db->where('state_master.state_delete','0');
		$states = $this->master_model->getRecords('state_master');//array('state_code'=>$state)
		
		//getting designations
		//$this->db->where('exam_master.exam_delete','0');
		$exam_name = '';
		$exam_name = $this->get_exam_name($this->session->userdata['userinfo']['member_no']);
		$exams=$this->master_model->getRecords('exam_master','','',array('description'=>'ASC'));
		
		$data=array('middle_content'=>'dup_cert/preview_dup_cert_reg','states'=>$states,'exams'=>$exams,'exam_name'=>$exam_name,'user_details'=>$user_details);
		$this->load->view('common_view_fullwidth',$data);
		
	}
	public function register()
	{
		//if(!$this->session->userdata['userinfo'])
		if(empty($this->session->userdata['userinfo']))
		{
			redirect(base_url());
		}
		
		//if member is DRA member
		if(empty($user_details))
		{
			$user_details = $this->master_model->getRecords('dra_members',array('regnumber'=>$this->session->userdata['userinfo']['member_no']));//,'isactive'=>'1'
			$record_source = '2';
		}
		
		if($this->session->userdata['userinfo']['is_dra_mem'] == '1')
		{ 
		    $record_source = '1';
		    $user_details= $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata['userinfo']['member_no'],'isactive'=>'1'));
			if(!empty($user_details))
			{
			   // print_r($user_details[0]['registrationtype']);
				$date=date('Y-m-d H:i:s');
				$member_no = $this->session->userdata['userinfo']['member_no'];
				$registrationtype = $user_details[0]['registrationtype'];
				$namesub = strtoupper($user_details[0]['namesub']);
				$firstname = strtoupper($user_details[0]['firstname']);
				$middlename = strtoupper($user_details[0]['middlename']);
				$lastname = strtoupper($user_details[0]['lastname']);
				$exam_code = $this->session->userdata['userinfo']['sel_exam'];
				if($exam_code == '555')
				{
					$exam_code = '1';
				}
				$exam_name = $this->session->userdata['userinfo']['exam_name'];
				$email = $this->session->userdata['userinfo']['email'];
				$mobile = $user_details[0]['mobile'];
				$addressline1 = strtoupper($user_details[0]['address1']);
				$addressline2 = strtoupper($user_details[0]['address2']);
				$addressline3 = strtoupper($user_details[0]['address3']);
				$addressline4 = strtoupper($user_details[0]['address4']);
				$district= strtoupper($user_details[0]['district']);
				$city = strtoupper($user_details[0]['city']);
				$state = $user_details[0]['state'];
				$pincode = $user_details[0]['pincode'];
				$fee= $this->session->userdata['userinfo']['fees'];
				
			}
			## added by chaitali on 2021-10-27
			else{
				
				$user_details= $this->master_model->getRecords('member_registration_dbf_dupcert',array('regnumber'=>$this->session->userdata['userinfo']['member_no']));
				
				// print_r($user_details[0]['registrationtype']);
				$date=date('Y-m-d H:i:s');
				$member_no = $this->session->userdata['userinfo']['member_no'];
				$registrationtype = $user_details[0]['registrationtype'];
				$namesub = strtoupper($user_details[0]['namesub']);
				$firstname = strtoupper($user_details[0]['firstname']);
				$middlename = strtoupper($user_details[0]['middlename']);
				$lastname = strtoupper($user_details[0]['lastname']);
				$exam_code = $this->session->userdata['userinfo']['sel_exam'];
				if($exam_code == '555')
				{
					$exam_code = '1';
				}
				$exam_name = $this->session->userdata['userinfo']['exam_name'];
				$email = $this->session->userdata['userinfo']['email'];
				$mobile = $user_details[0]['mobile'];
				$addressline1 = strtoupper($user_details[0]['address1']);
				$addressline2 = strtoupper($user_details[0]['address2']);
				$addressline3 = strtoupper($user_details[0]['address3']);
				$addressline4 = strtoupper($user_details[0]['address4']);
				$district= strtoupper($user_details[0]['district']);
				$city = strtoupper($user_details[0]['city']);
				$state = $user_details[0]['state'];
				$pincode = $user_details[0]['pincode'];
				$fee= $this->session->userdata['userinfo']['fees'];
			}
		}
		elseif($this->session->userdata['userinfo']['is_dra_mem'] == '2')
		{
			$record_source = '2';
		    $user_details= $this->master_model->getRecords('dra_members',array('regnumber'=>$this->session->userdata['userinfo']['member_no']));
			if(!empty($user_details))
			{
				$date=date('Y-m-d H:i:s');
				$member_no = $this->session->userdata['userinfo']['member_no'];
				$registrationtype = $this->session->userdata['userinfo']['registrationtype'];
				$namesub = strtoupper($this->session->userdata['userinfo']['namesub']);
				$firstname = strtoupper($this->session->userdata['userinfo']['firstname']);
				$middlename = strtoupper($this->session->userdata['userinfo']['middlename']);
				$lastname = strtoupper($this->session->userdata['userinfo']['lastname']);
				$exam_code = $this->session->userdata['userinfo']['sel_exam'];
				if($exam_code == '555')
				{
					$exam_code = '1';
				}
				$exam_name = $this->session->userdata['userinfo']['exam_name'];
				$email = $this->session->userdata['userinfo']['email'];
				$mobile = $this->session->userdata['userinfo']['mobile'];
				$addressline1 = strtoupper($user_details[0]['address1']);
				$addressline2 = strtoupper($user_details[0]['address2']);
				$addressline3 = strtoupper($user_details[0]['address3']);
				$addressline4 = strtoupper($user_details[0]['address4']);
				$district= strtoupper($user_details[0]['district']);
				$city = strtoupper($user_details[0]['city']);
				$state= $user_details[0]['state'];
				$pincode= $user_details[0]['pincode'];
				$fee= $this->session->userdata['userinfo']['fees'];	
			}
		}
		
		$insert_arr = array(
					'regnumber'=>$member_no,
					'registrationtype'=>$registrationtype,
					'namesub'=>$namesub,
					'firstname'=>$firstname,
					'middlename'=>$middlename,
					'lastname'=>$lastname,
					'exam_code'=>$exam_code,
					'exam_name'=>$exam_name,
					'email'=>$email,
					'mobile'=>$mobile,
					'address1'=>$addressline1,
					'address2'=>$addressline2,
					'address3'=>$addressline3,
					'address4'=>$addressline4,
					'district'=>$district,
					'city'=>$city,
					'state'=>$state,
					'pincode'=>$pincode,
					'fee'=>$fee,
					'record_source' => $record_source,
					'created_on'=>$date
					);
					//print_r($insert_arr); exit;
					if($last_id = $this->master_model->insertRecord('duplicate_certificate',$insert_arr,true))
					{
						
							$userarr=array('regno'=>$last_id,
											'member_no'=>$member_no); 
											//'email'=>$email
							$this->session->set_userdata('memberdata', $userarr); 
							
							redirect(base_url()."DupCert/make_payment");
							
					}
					else
					{
						$userarr=array('regno'=>'',
									   'member_no'=>'');
						$this->session->set_userdata('memberdata', $userarr); 
						//$this->make_payment();
						$this->session->set_flashdata('error','Error while Applying for Duplicate Certificate.please try again!');
						redirect(base_url());
					}
	}
	public function make_payment()
	{
		//echo 'do payment';exit;
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		$flag=1;
		
		$regno = $this->session->userdata['memberdata']['regno'];
		$member_no = $this->session->userdata['memberdata']['member_no'];
		
		if(!empty($regno))
		{
			//getting state
			$member_state = $this->master_model->getRecords('duplicate_certificate',array('id'=>$regno,'pay_status'=>'0'),array('state','exam_code','exam_name'));
			$checkuser = $this->master_model->getRecords('duplicate_certificate',array('id'=>$regno,'pay_status!='=>'0'));
			if(count($checkuser)>0)
			{
				redirect('http://iibf.org.in');
			}
		}
		if(isset($_POST['submit']) && $_POST['submit'])
		{
			    $pg_name = $this->input->post('pg_name');
				
				
				
			
				$state = $member_state[0]['state'];
				if(!empty($state))
				{
					if($state == 'MAH')
					{
					   $amount = $this->config->item('Dup_cert_cs_total');
					}
					else
					{
						$amount = $this->config->item('Dup_cert_igst_tot');
					}
					/*else if($state == 'JAM')
					{
					   $amount = $this->config->item('Dup_cert_apply_fee');
					}*/
				}
				if(!empty($state))
				{ 
					//get state code,state name,state number.
					$getstate = $this->master_model->getRecords('state_master',array('state_code'=>$state,'state_delete'=>'0'));
				}
				if($state == 'MAH')
				{
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate=$this->config->item('Dup_cert_cgst_rate');
					$sgst_rate=$this->config->item('Dup_cert_sgst_rate');
					//set an amount as per rate
					$cgst_amt=$this->config->item('Dup_cert_cgst_amt');
					$sgst_amt=$this->config->item('Dup_cert_sgst_amt');
					 //set an total amount
					$cs_total=$amount;
					$tax_type='Intra';     
				}
				else
				{
					$igst_rate=$this->config->item('Dup_cert_igst_rate');
					$igst_amt=$this->config->item('Dup_cert_igst_amt');
					$igst_total=$amount; 
					$tax_type='Inter';
				}
				            
				/*if($getstate[0]['exempt']=='E')
				{
					 $cgst_rate=$sgst_rate=$igst_rate='';	
					 $cgst_amt=$sgst_amt=$igst_amt='';
					 $igst_total=$this->config->item('Dup_cert_apply_fee');
				     $amount =$this->config->item('Dup_cert_apply_fee');					 
				}*/
				$exam_code = $member_state[0]['exam_code'];
				$exam_name = $member_state[0]['exam_name'];
				// Create transaction
				$insert_data = array(
			    'member_regnumber' => $member_no,
				'gateway'     => "sbiepay",
				'amount'      => $amount,
				'exam_code'   =>$exam_code,
				'date'        => date('Y-m-d H:i:s'),
				'ref_id'	  =>  $regno,	
				'description' => $exam_name,
				'pay_type'    => 4,
				'status'      => 2,
				//'receipt_no'  => $MerchantOrderNo,
				'pg_flag'=>'iibfdupcer',
				//'pg_other_details'=>$custom_field
			);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction',$insert_data,true);
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			//Ref1 = order_id
			//Ref2 = iibfexam
			//Ref3 = iibfdupcer
			//Ref4 = member_no
			//e.g='900001266^iibfexam^iibfdupcer^51000000';
			$custom_field = $MerchantOrderNo."^iibfexam^iibfdupcer^".$member_no;
			$custom_field_billdesk = $MerchantOrderNo."-iibfexam" . "-iibfdupcer" . "-" .$member_no;
			
			/* $billdesk_additional_info    = $MerchantOrderNo . "-iibfexam" . "-iibfrec" . "-" . $regnumber */;

			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
				
			
			    //print_r($member_deatails);exit;
				/* Duplicate Certificate invoice*/
				$invoice_insert_array=array('pay_txn_id'=>$pt_id,
											'receipt_no'=>$MerchantOrderNo,
											'exam_code'=>$exam_code,
											'state_of_center'=>$state,
											'member_no'=>$member_no, 
											'app_type'=>'C',  //DC for Duplicate Certificate
											'service_code'=>$this->config->item('Dup_cert_service_code'),
											'qty'=>'1',
											'state_code'=>$getstate[0]['state_no'],
											'state_name'=>$getstate[0]['state_name'],
											'tax_type'=>$tax_type,
											'fee_amt'=>$this->config->item('Dup_cert_apply_fee'),
											'cgst_rate'=>$cgst_rate,
											'cgst_amt'=>$cgst_amt,
											'sgst_rate'=>$sgst_rate,
											'sgst_amt'=>$sgst_amt,
											'igst_rate'=>$igst_rate,
											'igst_amt'=>$igst_amt,
											'cs_total'=>$cs_total,
											'igst_total'=>$igst_total,
											'exempt'=>$getstate[0]['exempt'],
											'created_on'=>date('Y-m-d H:i:s')
										);
								
			    $inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
          				
				 $MerchantCustomerID = $regno;
				
					if ($pg_name == 'sbi') 
					{ 
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('sbi_m_key');
						$merchIdVal = $this->config->item('sbi_merchIdVal');
						$AggregatorId = $this->config->item('sbi_AggregatorId');
						
						$pg_success_url = base_url()."DupCert/sbitranssuccess";
						$pg_fail_url    = base_url()."DupCert/sbitransfail";
						//exit;
						$MerchantCustomerID  = $inser_id;
						$data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
						$data["merchIdVal"]  = $merchIdVal;
						$EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
						$aes                 = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$EncryptTrans         = $aes->encrypt($EncryptTrans);
						$data["EncryptTrans"] = $EncryptTrans;
						$this->load->view('pg_sbi_form', $data);
					} 
					elseif ($pg_name == 'billdesk') 
					{
						$update_payment_data = array('gateway' =>'billdesk');
						$this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
					
						$billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'DupCert/handle_billdesk_response', '', '', '', $custom_field_billdesk);
						 /*  echo '<pre>';
						  print_r($billdesk_res); */
 				 		
						if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') 
						{
							$data['bdorderid'] = $billdesk_res['bdorderid'];
							$data['token']     = $billdesk_res['token'];
							$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
							$data['returnUrl'] = $billdesk_res['returnUrl']; 
							$this->load->view('pg_billdesk/pg_billdesk_form', $data);
						}
						else
						{ 
							$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
							redirect(base_url() . 'DupCert');
						}
					}
		}
		else
		{
			//$data["regno"] = $_REQUEST['regno'];
			$data['show_billdesk_option_flag'] = 1; // if issue occures make it = 0.
			$this->load->view('pg_sbi/make_payment_page', $data);
		}
		
	}
	
	public function handle_billdesk_response()
	{
		/* ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL); */
		$selected_invoice_id = $attachpath = $invoiceNumber = '';
		//$selected_invoice_id = $this->session->userdata['memberdata']['regno']; // Seleted Invoice Id
		
		
		if (isset($_REQUEST['transaction_response'])) 
		{			
			$response_encode = $_REQUEST['transaction_response'];
			$bd_response     = $this->billdesk_pg_model->verify_res($response_encode);
			
			$responsedata           = $bd_response['payload'];
			$attachpath             = $invoiceNumber             = '';
			$MerchantOrderNo        = $responsedata['orderid'];
			$transaction_no         = $responsedata['transactionid'];
			$transaction_error_type = $responsedata['transaction_error_type'];
			$transaction_error_desc = $responsedata['transaction_error_desc'];
			$bankid                 = $responsedata['bankid'];
			$txn_process_type       = $responsedata['txn_process_type'];
			$merchIdVal             = $responsedata['mercid'];
			$Bank_Code              = $responsedata['bankid'];
			$encData                = $_REQUEST['transaction_response'];
			$auth_status = $responsedata['auth_status'];	
			$get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
			if (empty($get_user_regnum_info)) {
				redirect(base_url() . 'DupCert');
			}
			$new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
			$member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
			//Query to get Payment details
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');
			
			
			/* Transaction Log */
			$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
			$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
			/* Update Exam Invoice */
			$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			if($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) 
			{
					
					$update_data  = array(
					'transaction_no'      => $transaction_no,
					'status'              => 1,
					'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
					'gateway'             =>'billdesk',
					'auth_code'           => '0300',
					'bankcode'            => $bankid,
					'paymode'             => $txn_process_type,
					'callback'            => 'B2B',
					);
					$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

				$exam_invoice_data = array('pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $MerchantOrderNo, 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no);
				$this->master_model->updateRecord('exam_invoice', $exam_invoice_data, array('invoice_id' => $new_invoice_id, 'member_no' => $member_regnumber));
				/* Update Pay Status */
				$dup_cert_data = array('pay_status' => 1, 'modified_on' => date('Y-m-d H:i:s'));
				$this->master_model->updateRecord('duplicate_certificate', $dup_cert_data, array('id' => $new_invoice_id, 'regnumber' => $member_regnumber));
				//echo $this->db->last_query(); die;
				/* Email */
				$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'duplicate_cert'), '', '', '1');
				if (!empty($member_regnumber)) {
					$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile', '', '', '1');
					//echo 'in if'.$this->db->last_query(); die;
				}
				//echo $user_info[0]['email']; die;
				if (count($emailerstr) > 0) {
					/* Set Email sending options */
					$info_arr = array(
					 'to'      => $user_info[0]['email'],
					 'from'    => $emailerstr[0]['from'],
					'subject' => $emailerstr[0]['subject'],
					'message' => $emailerstr[0]['emailer_text'],
					);
					/* Invoice Number Genarate Functinality */
					$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
					//$getinvoice_number[0]['invoice_id'];
					if ($getinvoice_number != '') {
						$invoiceNumber = generate_duplicate_cert_invoice_number($getinvoice_number[0]['invoice_id']); 
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix').$invoiceNumber;
						}
						
						$update_data33 = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
						$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
						$this->master_model->updateRecord('exam_invoice',$update_data33,array('receipt_no'=>$MerchantOrderNo));
						
						//echo $this->db->last_query(); die;
						/* Invoice Create Function */
						
						$attachpath=genarate_duplicatecert_invoice($getinvoice_number[0]['invoice_id']);
						/* User Log Activities  */
						$log_title   = "Dup-cert-Invoice Genarate";
						$log_message = serialize($update_data);
						$rId         = $new_invoice_id;
						$regNo       = $member_regnumber;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
					if ($attachpath != '') { 
						if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
							redirect(base_url() . 'DupCert/acknowledge/');
							} else { 
							redirect(base_url() . 'DupCert/acknowledge/');
						}
						} else {
						redirect(base_url() . 'DupCert/acknowledge/');
					}
				}
			} 
			elseif ($auth_status=='0002') {
				$update_data22 = array('transaction_no' => $transaction_no,'status' => 2,'transaction_details' => $transaction_error_type." - ".$transaction_error_desc,'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'B2B');
				$this->master_model->updateRecord('payment_transaction',$update_data22,array('receipt_no'=>$MerchantOrderNo)); 
					
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=B2B";
				$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
					
				$this->session->set_flashdata('flsh_msg', 'Transaction pending...!');
				redirect(base_url() . 'DupCert');
			}
			else /* if ($transaction_error_type == 'payment_authorization_error') */ 
			{				
				
				$update_data22 = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $transaction_error_type." - ".$transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'B2B');
				$this->master_model->updateRecord('payment_transaction',$update_data22,array('receipt_no'=>$MerchantOrderNo)); 
					
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=B2B";
				$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
					
				$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
				redirect(base_url() . 'DupCert');
			}
		} 
		else 
		{
			die("Please try again...");
		}
	}
	
	//if sbi transaction success
	public function sbitranssuccess()
	{
		exit();
		//delete_cookie('regid');
		if(isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			$attachpath=$invoiceNumber='';
			if (isset($_REQUEST['merchIdVal']))
			{
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code']))
			{
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData']))
			{
				$encData = $_REQUEST['pushRespData'];
			}
			//Sbi B2B callback
			//check sbi payment status with MerchantOrderNo 
			$q_details = sbiqueryapi($MerchantOrderNo);
			if ($q_details)
					{
						if ($q_details[2] == "SUCCESS")
						{
							
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							if($get_user_regnum[0]['status']==2)
							{
								
								if(count($get_user_regnum) > 0)
								{
									$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email,address1,address2,address3,address4,district,city,state,pincode,mobile');
									
									//if member is DRA member
									//print_r($_SESSION); echo $this->session->userdata['userinfo']['member_no']; exit;
									if(empty($user_info))
									{
										$user_info = $this->master_model->getRecords('dra_members',array('regnumber'=>$this->session->userdata['userinfo']['member_no']));//,'isactive'=>'1'
										//print_r($user_info); exit;
									}
								}
						
								$update_data = array('pay_status' => '1');
								
								$this->master_model->updateRecord('duplicate_certificate',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
								
						$old_email = $user_info[0]['email']; 
						$new_email = $this->session->userdata['userinfo']['email'];
						$old_mobile = $user_info[0]['mobile']; 
						$new_mobile = $this->session->userdata['userinfo']['mobile'];
						/*$old_address3 = $user_info[0]['address3'];
						$old_address4 = $user_info[0]['address4'];
						$addressline1 = $this->session->userdata['userinfo']['addressline1']; 
						$addressline2 = $this->session->userdata['userinfo']['addressline2'];
						$addressline3 = $this->session->userdata['userinfo']['addressline3'];
						$addressline4 = $this->session->userdata['userinfo']['addressline4'];
						$old_district = $user_info[0]['district'] ;
						$old_city     = $user_info[0]['city'];
						$old_state    = $user_info[0]['state'];
						$old_pincode  = $user_info[0]['pincode'];
						$district     = $this->session->userdata['userinfo']['district'];
						$city         = $this->session->userdata['userinfo']['city'];
						$state        = $this->session->userdata['userinfo']['state'];
						$pincode      = $this->session->userdata['userinfo']['pincode'];*/
						$member_no    = $this->session->userdata['userinfo']['member_no'];
						//&& $old_address1 == $addressline1 && $old_address2 == $addressline2 && $old_address3 == $addressline3 && $old_address4 == $addressline4 && $old_district == $district && $old_city == $city && $old_state == $state && $old_pincode == $pincode
						if($new_email == $old_email )
						{ 
							
						}
						else
						{
							$insert_arr = array('regnumber'=>$member_no,
												'old_email'=> $old_email,
												'new_email'=> $new_email,
												'old_mobile' =>$old_mobile,
												'new_mobile' =>$new_mobile,
												/*'old_address3' =>$old_address3,
												'old_address4' =>$old_address4,
												'new_address1' =>$addressline1,
												'new_address2' =>$addressline2,
												'new_address3' =>$addressline3,
												'new_address4' =>$addressline4,
												'old_district' =>$old_district, 
												'old_city'     =>$old_city,
												'old_state'    =>$old_state,
												'old_pincode'  =>$old_pincode,
												'district'     =>$district,
												'city'         =>$city,
												'state'        =>$state,
												'pincode'      =>$pincode,*/
												'created_on'=>date('Y-m-d H:i:s')
												 );
							$this->master_model->insertRecord('dra_mem_logs',$insert_arr,true);
							//echo $this->db->last_query(); 
						}
					
								//,'address1' => $this->session->userdata['userinfo']['addressline1'],'address2' => $this->session->userdata['userinfo']['addressline2'],'address3' => $this->session->userdata['userinfo']['addressline3'],'address4' => $this->session->userdata['userinfo']['addressline4']
								$update_data = array('email' => $this->session->userdata['userinfo']['email'] ,'mobile'=>$this->session->userdata['userinfo']['mobile']);
								
								 $this->master_model->updateRecord('dra_members',$update_data,array('regnumber'=>$this->session->userdata['userinfo']['member_no']));
								//echo $this->db->last_query();
								//die;
								//logs
								$dramem_update_query=$this->db->last_query();
								$log_message = serialize($dramem_update_query);
								$titlt = "Dra member updation".$this->session->userdata['userinfo']['member_no'];
								$logs = array(
								'title' =>$titlt,
								'description' =>$log_message);
								$this->master_model->insertRecord('dup_cert_logs', $logs,true);
								
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
								
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								
								//logs 
								$payment_update_query=$this->db->last_query();
								$log_message = serialize($payment_update_query);
								$titlt = "Duplicate cert Payment updation".$this->session->userdata['userinfo']['member_no'];
								$logs = array(
								'title' =>$titlt,
								'description' =>$log_message);
								$this->master_model->insertRecord('dup_cert_logs', $logs,true);
								
								//Manage Log
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=B2B"; 
								$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
								//$this->db->last_query();exit;
								//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									
								
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
							    if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									//Query to get user details
									/*$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
									$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
									$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);*/
									//print_r($new_email); exit;
									$final_str = $emailerstr[0]['emailer_text'];
									$info_arr = array('to'=>$new_email,'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
									
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
					 
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{ 
											/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_duplicate_cert_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix_jammu').$invoiceNumber;
												}
											}
											else
											{}*/
												$invoiceNumber = generate_duplicate_cert_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix').$invoiceNumber;
												}
											
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_duplicatecert_invoice($getinvoice_number[0]['invoice_id']);
									}
								
								if($attachpath!='')
								{	
									//if($this->Emailsending->mailsend($info_arr))
							
									//if($this->Emailsending->mailsend_attch_temp($info_arr,$attachpath)) 
									if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
									{
										$this->session->set_flashdata('success','Request for duplicate Certificate has been placed successfully !!');
										//$pay_status=array();
										//$regnumber = $this->session->userdata('member_no');
										
										//redirect(base_url('DupCert/acknowledge/'));
										//print_r($MerchantOrderNo);
										//print_r(base_url().'DupCert/acknowledge/'.base64_encode($MerchantOrderNo));
										//echo base_url().'DupCert/acknowledge/'.base64_encode($MerchantOrderNo);exit;
										redirect(base_url().'DupCert/acknowledge/'.base64_encode($MerchantOrderNo));
									}
									else
									{
										redirect(base_url('DupCert/acknowledge/'));
									}
								}
								else
								{
									redirect(base_url('DupCert/acknowledge/'));
								}	
							
						}
					}
				}
			}
			///End of SBI B2B callback 
			redirect(base_url().'DupCert/acknowledge/');
			
		}
		else
		{
			die("Please try again...");
		}
	}
	public function sbitransfail()
	{
		exit();
		if (isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no  = $responsedata[1];
			
			$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
			if($get_user_regnum_info[0]['status']!=0 && $get_user_regnum_info[0]['status']==2)
			{	
				if (isset($_REQUEST['merchIdVal']))
				{
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code']))
				{
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData']))
				{
					$encData = $_REQUEST['pushRespData'];
				}
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=B2B";
		    $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					
			}
			$this->session->set_flashdata('error','Transaction has been fail, please try again!!');
			redirect(base_url('DupCert'));
		}
		else
		{
			die("Please try again...");
		}
	}
	public function acknowledge($MerchantOrderNo=NULL)
	{
		if(!empty($MerchantOrderNo))
		{ 
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'member_regnumber,transaction_no,date,amount,status');
		}
		if(count(@$payment_info) <= 0)
		{redirect(base_url());}
		
		$data=array();
		if($this->session->userdata('memberdata')=='')
		{
			redirect(base_url());
		}
		if($this->session->userdata('userinfo'))
		{
			$this->session->unset_userdata('userinfo');
		}
		
		//getting designations
		//$this->db->where('exam_master.exam_delete','0');
		$exams=$this->master_model->getRecords('exam_master','','',array('description'=>'ASC'));
		
		$user_info = $this->master_model->getRecords('duplicate_certificate',array('id'=>$this->session->userdata['memberdata']['regno']));
		
		$data=array('middle_content'=>'dup_cert/duplicate_cert_thankyou','application_number'=>$payment_info[0]['member_regnumber'],'user_info'=>$user_info,'payment_info'=>$payment_info,'exams'=>$exams);
		$this->load->view('common_view_fullwidth',$data);
		/*$data=array('middle_content'=>'dup_cert/duplicate_cert_thankyou','application_number','application_number'=>$user_info[0]['member_no']);
		$this->load->view('common_view',$data);*/
	}
	public function send_custom_invoice_mail()
	{
		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
		if(count($emailerstr) > 0)
		{
			$final_str = $emailerstr[0]['emailer_text'];
			$info_arr = array('to'=>'senapathi@rbi.org.in','from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);

		}
	    $attachpath = custome_genarate_duplicatecert_invoice('875197');
		if($attachpath!='')
		{
			if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
			{
                 echo "mail send successfully";
		    }
			else
			{
				echo "Error while mail sending";
			}
		}	
	    
	}
}