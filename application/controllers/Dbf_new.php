<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dbf_new extends CI_Controller {
	public function __construct()

	{
		parent::__construct();

		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('chk_session');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->chk_session->chk_dbf_member_session();
		/*if($this->router->fetch_method()!='comApplication' && $this->router->fetch_method()!='preview' && $this->router->fetch_method()!='Msuccess' && $this->router->fetch_method()!='details' && $this->router->fetch_method()!='editmobile' && $this->router->fetch_method()!='editemailduplication')
		{*/
		if($this->router->fetch_method()!='comApplication' && $this->router->fetch_method()!='preview' && $this->router->fetch_method()!='Msuccess' && $this->router->fetch_method()!='editmobile' && $this->router->fetch_method()!='editemailduplication' && $this->router->fetch_method()!='setExamSession' && $this->router->fetch_method()!='details' && $this->router->fetch_method()!='applydetails' && $this->router->fetch_method()!='saveexam' && $this->router->fetch_method()!='exampdf' && $this->router->fetch_method()!='printexamdetails' && $this->router->fetch_method()!='sbi_make_payment' && $this->router->fetch_method()!='sbitranssuccess' && $this->router->fetch_method()!='sbitransfail')
		{
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			if($this->session->userdata('examcode'))
			{
				$this->session->unset_userdata('examcode');
			}
		}
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function index()
	{
		redirect(base_url().'Dbf/dashboard');
	}
	public function applicationForm()
	{
		$this->load->view('application_block');
	}
	##------------------ Member dashboard (PRAFULL)---------------##
	public function dashboard()
	{
		
	/*	$examcode=$this->input->get('ExId');
		$flag=1;
		$checkqualifyflag=0;
		if($examcode!='')
		{
		   $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>base64_decode($examcode)));
		   if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
			{
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$examcode,$check_qualify_exam[0]['qualifying_part1']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
			}
			if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
			{	
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$examcode,$check_qualify_exam[0]['qualifying_part2']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
			}
			if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
			{	
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$examcode,$check_qualify_exam[0]['qualifying_part3']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
				}
			if($checkqualifyflag==0)
			{
			$check=$this->examapplied($this->session->userdata('nmregnumber'),$examcode);
			if($check)
			{
				$message='You have already applied for the examination';
				$flag=0;
			}else
			{
			$check_date=$this->examdate($this->session->userdata('nmregnumber'),$examcode);
			if($check_date)
			{
				$message=$this->get_alredy_applied_examname($this->session->userdata('nmregnumber'),$examcode);
				$flag=0;
			}
		}
		}
		}
		if($flag==1 && $checkqualifyflag==0)
		{
			$data=array('middle_content'=>'Dbf/dashboard');
			$this->load->view('nonmember/nm_common_view',$data);
		}
		else
		{
			$data=array('middle_content'=>'nonmember/not_eligible','check_eligibility'=>$message);
			$this->load->view('nonmember/nm_common_view',$data);
		}*/
		$data=array('middle_content'=>'dbf/dashboard');
		$this->load->view('dbf/dbf_common_view',$data);
	}
	
	
		##------------------ Member dashboard (PRAFULL)---------------##
	public function showexam()
	{
		$examcode=$this->input->get('ExId');
		$flag=1;
		$checkqualifyflag=0;
		if($examcode!='')
		{
		   $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>base64_decode($examcode)));
		   if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
			{
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$examcode,$check_qualify_exam[0]['qualifying_part1']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
			}
			if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
			{	
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$examcode,$check_qualify_exam[0]['qualifying_part2']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
			}
			if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
			{	
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$examcode,$check_qualify_exam[0]['qualifying_part3']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
				}
			if($checkqualifyflag==0)
			{
			$check=$this->examapplied($this->session->userdata('dbregnumber'),$examcode);
			if($check)
			{
				/*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($examcode),'misc_master.misc_delete'=>'0'),'exam_month');
				$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
				$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
				$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
									
				$message='You have already applied for the examination';
				$flag=0;
			}else
			{
			$check_date=$this->examdate($this->session->userdata('dbregnumber'),$examcode);
			if($check_date)
			{
				$message=$this->get_alredy_applied_examname($this->session->userdata('dbregnumber'),$examcode);
				$flag=0;
			}
		}
		}
		}
		if($flag==1 && $checkqualifyflag==0)
		{
			redirect(base_url().'Dbf/examdetails/?excode2='.$examcode); 
		}
		else
		{
			$data=array('middle_content'=>'dbf/not_eligible','check_eligibility'=>$message);
			$this->load->view('dbf/dbf_common_view',$data);
		}
	}
	
	
  // ##---------End user profile (prafull)-----------##
   public function profile()
   {
		$prevData = array();
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));
		if(count($user_info))
		{
			$prevData = $user_info[0];
		}
		 $scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file ='';
		 if(isset($_POST['btnSubmit']))  	
		 {
			$this->form_validation->set_rules('addressline1','Addressline1','trim|max_length[30]|required');
			$this->form_validation->set_rules('district','District','trim|max_length[30]|required');
			$this->form_validation->set_rules('city','City','trim|max_length[30]|required');
			$this->form_validation->set_rules('state','State','trim|required');
			$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required');
			//$this->form_validation->set_rules('dob','Date of Birth','trim|required');
			//$this->form_validation->set_rules('gender','Gender','trim|required');
			//$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[member_registration.email.regid.'.$this->session->userdata('nmregid').']|xss_clean');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_validunique[member_registration.email.regid.'.$this->session->userdata('dbregid').'.isactive.1.registrationtype.'.$this->session->userdata('memtype').']|xss_clean');
			
			$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric');
			$this->form_validation->set_rules('optedu','Qualification','trim|required');
			if($_POST['optedu']=='U')
			{
				$this->form_validation->set_rules('eduqual1','Please specify','trim|required');
			}
			else if($_POST['optedu']=='G')
			{
				$this->form_validation->set_rules('eduqual2','Please specify','trim|required');
			}
			else if($_POST['optedu']=='P')
			{
				$this->form_validation->set_rules('eduqual3','Please specify','trim|required');
			}
			
			if($this->form_validation->run()==TRUE)
			{
				$addressline1= strtoupper($this->input->post('addressline1'));
				$addressline2 = strtoupper($this->input->post('addressline2'));
				$addressline3 = strtoupper($this->input->post('addressline3'));
				$addressline4 = strtoupper($this->input->post('addressline4'));
				$district= strtoupper($this->input->post('district'));
				$city = strtoupper($this->input->post('city'));
				$state= $this->input->post('state');
				$pincode= $this->input->post('pincode');
				//$gender =$this->input->post('gender');
				$optedu= $this->input->post('optedu');
				if($optedu=='U')
				{

					$specify_qualification=$this->input->post('eduqual1');
				}
				elseif($optedu=='G')
				{
					$specify_qualification=$this->input->post('eduqual2');
				}
				else if($optedu=='P')
				{
					$specify_qualification=$this->input->post('eduqual3');
				}
				
				/*$dob1 = $this->input->post('dob');
				$dob = str_replace('/','-',$dob1);*/
				
				$email = $this->input->post('email');
				$stdcode = $this->input->post('stdcode');
				$phone = $this->input->post('phone');
				$mobile = $this->input->post('mobile');
				$idproof = $this->input->post('idproof');
				$optnletter = $this->input->post('optnletter');
				$declaration1 = $this->input->post("declaration1");
					
					// Check if value is edited
				$update_data = array();
				if(count($prevData))
				{
					if($prevData['address1'] != $addressline1)
					{	$update_data['address1'] = $addressline1;	}
					
					if($prevData['address2'] != $addressline2)
					{	$update_data['address2'] = $addressline2;	}
					
					if($prevData['address3'] != $addressline3)
					{	$update_data['address3'] = $addressline3;	}
					
					if($prevData['address4'] != $addressline4)
					{	$update_data['address4'] = $addressline4;	}
					
					if($prevData['district'] != $district)
					{	$update_data['district'] = $district;	}
					
					if($prevData['city'] != $city)
					{	$update_data['city'] = $city;	}
					
					if($prevData['state'] != $state)
					{	$update_data['state'] = $state;	}
					
					if($prevData['pincode'] != $pincode)
					{	$update_data['pincode'] = $pincode;	}
					
					/*if(date('Y-m-d',strtotime($prevData['dateofbirth'])) != date('Y-m-d',strtotime($dob)))
					{	$update_data['dateofbirth'] = date('Y-m-d',strtotime($dob));	}*/
					
					/*if($prevData['gender'] != $gender)
					{	$update_data['gender'] = $gender;	}*/
					
					if($prevData['qualification'] != $optedu)
					{	$update_data['qualification'] = $optedu;	}
					
					if($prevData['specify_qualification'] != $specify_qualification)
					{	$update_data['specify_qualification'] = $specify_qualification;	}
					
					if($prevData['email'] != $email)
					{	$update_data['email'] = $email;	}
					
					if($prevData['stdcode'] != $stdcode)
					{	$update_data['stdcode'] = $stdcode;	}
					
					if($prevData['office_phone'] != $phone)
					{	$update_data['office_phone'] = $phone;	}
					
					if($prevData['mobile'] != $mobile)
					{	$update_data['mobile'] = $mobile;	}
					
					/*if($prevData['idproof'] != $idproof)
					{	$update_data['idproof'] = $idproof;	}
					
					if($prevData['idNo'] != $idNo)
					{	$update_data['idNo'] = $idNo;	}*/
					
					if($prevData['optnletter'] != $optnletter)
					{	$update_data['optnletter'] = $optnletter;	}
				}	
				
				$edited = array();
				$edited = '';
				if(count($update_data))
				{
					foreach($update_data as $key => $val)
					{
						$edited .= strtoupper($key)." = ".strtoupper($val)." && ";
					}
					
					$update_data['editedon'] = date('Y-m-d H:i:s');
					$update_data['editedby'] = 'candidate';
					//$update_data['editedbyadmin'] = $this->UserID;
					
					
					//$personalInfo = filter($personal_info);
					if($this->master_model->updateRecord('member_registration',$update_data,array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'))))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $user_info[0];
						//profile update logs
						log_profile_user($log_title = "Profile updated successfully", $edited,'data',$this->session->userdata('dbregid'),$this->session->userdata('dbregnumber'));
						
						log_nm_activity($log_title = "Profile updated id:".$this->session->userdata('dbregid'), $description = serialize($desc));
						
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
						if(count($emailerstr) > 0)
						{
							$newstring = str_replace("#application_num#", "".$this->session->userdata('dbregnumber')."",  $emailerstr[0]['emailer_text']);
							$final_str= str_replace("#password#", "".base64_decode($this->session->userdata('nmpassword'))."",  $newstring);
							$info_arr=array(
														'to'=>$email,
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str
													);
													
							if($this->Emailsending->mailsend($info_arr))
							{
								//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								redirect(base_url('Dbf/acknowledge/'));
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								redirect(base_url('Dbf/profile/'));
							}
						}
						else
						{
							$this->session->set_flashdata('error','Error while sending email !!');
							redirect(base_url('Dbf/profile/'));
						}
					}
					else
					{
						$this->session->set_flashdata('error','Error While Adding Your Information !!');
						$last = $this->uri->total_segments();
						$post = $this->uri->segment($last);
						redirect(base_url().$post);	
					}
				}
				else
				{
					$this->session->set_flashdata('error','Change atleast one field');
					redirect(base_url('Dbf/profile/'));	
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
			 
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$states=$this->master_model->getRecords('state_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		$data=array('middle_content'=>'dbf/userprofile','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'user_info'=>$user_info,'idtype_master'=>$idtype_master);
		$this->load->view('dbf/dbf_common_view',$data);
   }

	// ##---------Thank you page for user (prafull)-----------##
   public function acknowledge()
   {
		
		$data=array('middle_content'=>'dbf/edif_profile_thankyou','application_number'=>$this->session->userdata('dbregnumber'),'password'=>base64_decode($this->session->userdata('dbpassword')));
		$this->load->view('dbf/dbf_common_view',$data);
		
	}
	

	
	// ##---------Edit Images(Prafull)-----------##
	public function editimages()
	{
			$flag=1;
			$member_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid')),'scannedphoto,scannedsignaturephoto,idproofphoto');
			$applicationNo = $this->session->userdata('dbregnumber');
			if(isset($_POST['btnSubmit']))  	
			 {
				
				if($_FILES['scannedphoto']['name']=='' && $_FILES['scannedsignaturephoto']['name']=='' && $_FILES['idproofphoto']['name']=='')
				{
					$this->form_validation->set_rules('scannedphoto','Please Change atleast One Value','file_required');
				}
				if($_FILES['scannedphoto']['name']!='')
				{
					$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg]|file_size_max[20]');
				}
				if($_FILES['scannedsignaturephoto']['name']!='')
				{
					$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg]|file_size_max[20]');
				}
				if($_FILES['idproofphoto']['name']!='')
				{
					$this->form_validation->set_rules('idproofphoto','id proof','file_required|file_allowed_type[jpg]|file_size_max[25]');
				}
				if($this->form_validation->run()==TRUE)
				{
					$date=date('Y-m-d h:i:s');
					$photo_flg = 'N';
					$edited = '';
					if(isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name']!='')
					{
						@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);
						$path = "./uploads/photograph";
						//$new_filename = 'photo_'.strtotime($date).rand(1,99999);
						$new_filename = 'p_'.$applicationNo;
						$uploadData = upload_file('scannedphoto', $path, $new_filename,'','',TRUE);
						if($uploadData)
						{
							//Overwrites file so no need to unlink
							//@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);
							$scannedphoto_file = $uploadData['file_name'];
							$photo_flg = 'Y';
							$edited .= 'PHOTO || ';
						}
						else
						{
							$flag=0;
							$scannedphoto_file=$this->input->post('scannedphoto1_hidd');
						}
					}
					else
					{
						  $scannedphoto_file=$this->input->post('scannedphoto1_hidd');
					}
						
					// Upload DOB Proof
					$signature_flg = 'N';
					if($_FILES['scannedsignaturephoto']['name']!='')
					{
						@unlink('uploads/photograph/'.$member_info[0]['scannedsignaturephoto']);
						$path = "./uploads/scansignature";
						//$new_filename = 'sign_'.strtotime($date).rand(1,99999); 
						$new_filename = 's_'.$applicationNo;
						$uploadData = upload_file('scannedsignaturephoto', $path, $new_filename,'','',TRUE);
						if($uploadData)
						{
							$scannedsignaturephoto_file = $uploadData['file_name'];
							$signature_flg = 'Y';
							$edited .= 'SIGNATURE || ';
						}
						else
						{
							$flag=0;
							$scannedsignaturephoto_file=$this->input->post('scannedsignaturephoto1_hidd');
						}
					}
					else
					{	
						$scannedsignaturephoto_file=$this->input->post('scannedsignaturephoto1_hidd');
					}
					
					// Upload Education Certificate
					$id_flg = 'N';
					if($_FILES['idproofphoto']['name']!='')
					{
						@unlink('uploads/photograph/'.$member_info[0]['idproofphoto']);
						$path = "./uploads/idproof";
						//$new_filename = 'idproof_'.strtotime($date).rand(1,99999);
						$new_filename = 'pr_'.$applicationNo;
						$uploadData = upload_file('idproofphoto', $path, $new_filename,'','',TRUE);
						if($uploadData)
						{
							$idproofphoto_file = $uploadData['file_name'];
							$id_flg = 'Y';
							$edited .= 'PROOF || ';
						}
						else
						{
							$flag=0;
							$idproofphoto_file=$this->input->post('idproofphoto1_hidd');
						}
					}
					else
					{
						$idproofphoto_file=$this->input->post('idproofphoto1_hidd');
					}
				
					if($flag==1)
					{
						$update_info = array(
												'scannedphoto'=>$scannedphoto_file,
												'scannedsignaturephoto'=>$scannedsignaturephoto_file,
												'idproofphoto'=>$idproofphoto_file,
												'editedon'=>date('Y-m-d H:i:s'),
												'photo_flg'=>$photo_flg,
												'signature_flg'=>$signature_flg,
												'id_flg'=>$id_flg,
												'editedon'=>date('Y-m-d H:i:s'),
												'editedby'=>'candidate',
											);		
										
						//$personalInfo = filter($personal_info);
						if($this->master_model->updateRecord('member_registration',$update_info,array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'))))
						{
							$desc['updated_data'] = $update_info; 
							$desc['old_data'] = $member_info[0];
							log_nm_activity($log_title ="Member Edit Images", $log_message = serialize($desc));
							
							$finalStr = '';
							if($edited!='')
							{
								$edit_data = trim($edited);
								$finalStr = rtrim($edit_data,"||");
							}
							log_profile_user($log_title = "Profile updated successfully", $finalStr,'image',$this->session->userdata('dbregid'),$this->session->userdata('dbregnumber'));
							
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
							if(count($emailerstr) > 0)
							{
								$member_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid')),'email');
								$newstring = str_replace("#application_num#", "".$this->session->userdata('dbregnumber')."",  $emailerstr[0]['emailer_text']);
								$final_str= str_replace("#password#", "".base64_decode($this->session->userdata('nmpassword'))."",  $newstring);
								$info_arr=array(
															'to'=>$member_info[0]['email'],
															'from'=>$emailerstr[0]['from'],
															'subject'=>$emailerstr[0]['subject'],
															'message'=>$final_str
														);
														
								if($this->Emailsending->mailsend($info_arr))
								{
									//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
									redirect(base_url('Dbf/acknowledge/'));
								}
								else
								{
									$this->session->set_flashdata('error','Error while sending email !!');
									redirect(base_url('Dbf/editimages/'));
								}
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								redirect(base_url('Dbf/editimages/'));
							}
						}
						else
						{
							$desc['updated_data'] = $update_info;
							$desc['old_data'] = $member_info[0];
							log_nm_activity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
							
							$this->session->set_flashdata('error','Error While Adding Your Information !!');
							$last = $this->uri->total_segments();
							$post = $this->uri->segment($last);
							//redirect(base_url().$post);
							redirect(base_url().'Dbf/profile');		
						}
			
					}
					else
					{
							$this->session->set_flashdata('error','Please follow the instruction while uploading image(s)!!');
							redirect(base_url('Dbf/editimages/'));
					}
				}
				else
				{
					$data['validation_errors'] = validation_errors();
			 	}
			 }
		$data=array('middle_content'=>'dbf/edit_images','member_info'=>$member_info);
		$this->load->view('dbf/dbf_common_view',$data);
	}
	
	
	
	//##---------check mobile number alredy exist or not on edit page(Prafull)-----------## 
	 public function editmobile()
	{
		$mobile = $_POST['mobile'];
		$regid = $_POST['regid'];
		if($mobile!="" && $regid!="")
		{
			$where="( registrationtype='DB')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'regid !='=>$regid,'isactive'=>'1'));
			if($prev_count==0)
			{echo 'ok';}
			else
			{echo 'exists';}
		}
		else
		{
			echo 'error';
		}
	}
	
	//##---------check mail alredy exist or not on edit page(Prafull)-----------## 
	 public function editemailduplication()
	{
		$email = $_POST['email'];
		$regid = $_POST['regid'];
		if($email!="" && $regid!="")
		{
			$where="( registrationtype='DB')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'regid !='=>$regid,'isactive'=>'1'));
			if($prev_count==0)
			{echo 'ok';}
			else
			{echo 'exists';}
		}
		else
		{
			echo 'error';
		}
	}
	
	##------------------ chenge password (PRAFULL)---------------##
	public function changepass()
	{
		$data['error']='';
		if(isset($_POST['btn_password']))
		{
			$this->form_validation->set_rules('current_pass','Current Password','required|xss_clean');
			$this->form_validation->set_rules('txtnpwd','New Password','required|xss_clean');
			$this->form_validation->set_rules('txtrpwd','Re-type new password','required|xss_clean|matches[txtnpwd]');
			if($this->form_validation->run())
			{
				$current_pass=$this->input->post('current_pass');
				$new_pass=$this->input->post('txtnpwd');
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encPass = $aes->encrypt($new_pass);
				$curr_encrypass = $aes->encrypt(trim($current_pass));
				
				$row=$this->master_model->getRecordCount('member_registration',array('usrpassword'=>$curr_encrypass,'regid'=>$this->session->userdata('dbregid')));
				if($row==0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'Dbf/changepass/');
				}
				else
				{
					if($current_pass!=$new_pass)
					{
					$input_array=array('usrpassword'=>$encPass);
					$this->master_model->updateRecord('member_registration',$input_array,array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));
					$this->session->unset_userdata('nmpassword');
					$this->session->set_userdata("nmpassword",base64_encode($new_pass));
					$this->session->set_flashdata('success','Password Changed successfully.'); 
					redirect(base_url().'Dbf/changepass/');
					}
					else
					{
						$this->session->set_flashdata('error','Current password and New password cannot be same.'); 
						redirect(base_url().'Dbf/changepass/');
					}
				}
			}
		}
		$data=array('middle_content'=>'dbf/change_pass',$data);
		$this->load->view('dbf/dbf_common_view',$data);
	}
	
	##------------------ Exam list for logged in user(Vrushali)---------------##
	public function examlist()
	{
			$user_images=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'),'isactive'=>'1'),'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');
		/* if(!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto'])
		 ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']==''
		 ||$user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
			{*/
			/*if(!is_file(get_img_name($this->session->userdata('dbregnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('dbregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('dbregnumber'),'p')))*/
			if(!is_file(get_img_name($this->session->userdata('dbregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('dbregnumber'),'p')))
			{
				redirect(base_url().'Dbf/notification');
			}
			
		 $today_date=date('Y-m-d');
		 $flag=1;
		 $exam_list=array();
		 
		/* $this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
		 $this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
		 $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.	exam_period');
			
		 $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		 $this->db->where("exam_activation_master.exam_activation_delete","0");
		 $this->db->where("misc_master.misc_delete",'0');
		 $this->db->where('medium_delete','0');
		 $this->db->group_by('medium_master.exam_code');
		 $exam_list=$this->master_model->getRecords('exam_master',array('elg_mem_db'=>'Y'));*/
		 
		 $this->db->join('subject_master','subject_master.exam_code=exam_master.exam_code');
		 $this->db->join('center_master','center_master.exam_name=exam_master.exam_code');
		 $this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
		 $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.	exam_period');
		  $this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
		 $this->db->where('medium_delete','0');
		 $this->db->where("misc_master.misc_delete",'0');
		 $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		 $this->db->where("exam_activation_master.exam_activation_delete","0");
		 $this->db->group_by('medium_master.exam_code');
		  $exam_list=$this->master_model->getRecords('exam_master',array('elg_mem_db'=>'Y'));
		 
		 $data=array('middle_content'=>'dbf/dbf_exam_list','exam_list'=>$exam_list);
		 $this->load->view('dbf/dbf_common_view',$data);
			
	}
	
	##------------------ Specific Exam Details for logged in user(Vrushali)---------------##
	/*public function examdetails()
	{
		$applied_exam_info=array();
		$flag=1;
		$examcode=base64_decode($this->input->get('excode2'));
		//Query to check selected exam details
		$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		if(count($check_qualify_exam) > 0)
		{
			//Condition to check the qualifying id exist
			if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0')
			{
					//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$check_qualify_exam[0]['qualifying_exam1'],'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('nmregnumber')),'exam_status,remark');
					if(count($check_qualify_exam_eligibility) > 0)
					{
						if($check_qualify_exam_eligibility[0]['exam_status']=='P')
						{
								//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
								$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('nmregnumber')));
								if(count($check_eligibility_for_applied_exam) > 0)
								{
									if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v')
									{
										$flag=0;
										$message=$check_eligibility_for_applied_exam[0]['remark'];
									}
									else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
									{
										$flag=1;
									}
								}
								else
								{
									//CAIIB apply directly
									$flag=1;
								}
						}
						else
						{
							$flag=0;
							$message=$check_qualify_exam_eligibility[0]['remark'];
						}
					}
					else
					{
						//show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
						$flag=0;
						if(isset($check_qualify_exam[0]['qualifying_exam1']))
						{
							$get_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$check_qualify_exam[0]['qualifying_exam1']),'description');	
							if(count($get_exam) > 0)
							{
								$message='You must apply/clear <strong>'.$get_exam[0]['description'].'</strong> exam before applying <strong> '.$check_qualify_exam[0]['description'].'</strong> exam.';
							}
						}
					}
			}
			else
			{
		
				//check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('nmregnumber')));
					if(count($check_eligibility_for_applied_exam) > 0)
					{
						if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v')
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
						
						}
						else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
						{
							$check=$this->examapplied($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
							if(!$check)
							{
								$check_date=$this->examdate($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
								if(!$check_date)
								{
								//CAIIB apply directly
								$flag=1;
								}
								else
								{
									$message='Exam fall in same date';
									$flag=0;
								}
							}
							else
							{
								$message='You have already applied for the examination';
								$flag=0;
							}
						}
					}
					else
					{
						$check=$this->examapplied($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
						if(!$check)
						{
							$check_date=$this->examdate($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
							if(!$check_date)
							{
							//CAIIB apply directly
							$flag=1;
							}
							else
							{
								$message='Exam fall in same date';
								$flag=0;
							}
						}
						else
						{
							$message='You have already applied for the examination';
							$flag=0;
						}
						
					}
			}
		}
		else
		{
			$flag=1;
		}
		
		//Query to check where exam applied successfully or not with transaction
		$is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$this->session->userdata('nmregnumber')));
		
	 if($is_transaction_doone >0)
	 {
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.exam_status,exam_master.description');
		$this->db->where('exam_master.elg_mem_db','Y');
		//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		//$this->db->where('payment_transaction.status','1');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('nmregnumber')));
	}
		
	
		if($flag==0)
		{
			$data=array('middle_content'=>'nonmember/not_eligible','check_eligibility'=>$message);
			$this->load->view('nonmember/nm_common_view',$data);
		}
		else if(count($applied_exam_info) > 0)
		{
			$data=array('middle_content'=>'nonmember/already_apply','check_eligibility'=>'You have already applied for the examination.');
			$this->load->view('nonmember/nm_common_view',$data);
		}
		else 
		{
			
			$exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
			$data=array('middle_content'=>'nonmember/cms_page','exam_info'=>$exam_info);
			$this->load->view('nonmember/nm_common_view',$data);
		}
	}*/
	
	##----------- Show error msg for non-eligible
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
		$data=array('middle_content'=>'dbf/not_eligible','check_eligibility'=>$message);
		$this->load->view('dbf/dbf_common_view',$data);
	}
	
	
	##------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
	public function examdetails()
	{			
			$flag=$this->checkusers(base64_decode($this->input->get('excode2')));
			if($flag==0)
			{
				redirect(base_url().'Dbf/accessdenied/');
			}
			
			$message='';
			$applied_exam_info=array();
			$flag=1;$checkqualifyflag=0;
			$examcode=base64_decode($this->input->get('excode2'));
		
			//$check=$this->examapplied($this->session->userdata('regnumber'),$this->input->get('excode2'));
			//if(!$check)
			//{
				
				//Query to check selected exam details
				$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
				if(count($check_qualify_exam) > 0)
				{
					//Condition to check the qualifying id exist
					if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
					{
						$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$examcode,$check_qualify_exam[0]['qualifying_part1']);
						$flag=$qaulifyarry['flag'];
						$message=$qaulifyarry['message'];
						if($flag==0)
						{
							$checkqualifyflag=1;
						}
					}
					if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
					{	
						$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$examcode,$check_qualify_exam[0]['qualifying_part2']);
						$flag=$qaulifyarry['flag'];
						$message=$qaulifyarry['message'];
						if($flag==0)
						{
							$checkqualifyflag=1;
						}
					}
					if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
					{	
						$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$examcode,$check_qualify_exam[0]['qualifying_part3']);
						$flag=$qaulifyarry['flag'];
						$message=$qaulifyarry['message'];
						if($flag==0)
						{
							$checkqualifyflag=1;
						}
						}
					else if($flag==1 && $checkqualifyflag==0)
					{
						//check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
						$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
							$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('dbregnumber')));
							if(count($check_eligibility_for_applied_exam) > 0)
							{
								if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
								{
									$flag=0;
									$message=$check_eligibility_for_applied_exam[0]['remark'];
								}
								else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
								{
									$check=$this->examapplied($this->session->userdata('dbregnumber'),$this->input->get('excode2'));
									if(!$check)
									{
										$check_date=$this->examdate($this->session->userdata('dbregnumber'),$this->input->get('excode2'));
										if(!$check_date)
										{
										//CAIIB apply directly
										$flag=1;
										}
										else
										{
											$message=$this->get_alredy_applied_examname($this->session->userdata('dbregnumber'),$this->input->get('excode2'));
											//$message='Exam fall in same date';
											$flag=0;
										}
									}
									else
									{
										/*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('excode2')),'misc_master.misc_delete'=>'0'),'exam_month');
										$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
										$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
										$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
										$message='You have already applied for the examination';
										$flag=0;
									}
								}
							}
							else
							{
							
								$check=$this->examapplied($this->session->userdata('dbregnumber'),$this->input->get('excode2'));
							
								if(!$check)
								{
									
									$check_date=$this->examdate($this->session->userdata('dbregnumber'),$this->input->get('excode2'));
									
									if(!$check_date)
									{
									//CAIIB apply directly
									$flag=1;
									}
									else
									{
										$message=$this->get_alredy_applied_examname($this->session->userdata('dbregnumber'),$this->input->get('excode2'));
										//$message='Exam fall in same date';
										$flag=0;
									}
								}
								else
								{
										/*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('excode2')),'misc_master.misc_delete'=>'0'),'exam_month');
										$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
										$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
										$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
										$message='You have already applied for the examination';
									$flag=0;
								}
								
							}
					}
				}
				else
				{
					$flag=1;
				}
				
				//Query to check where exam applied successfully or not with transaction
				$is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$this->session->userdata('dbregnumber'),'status'=>'1'));
				
			 if($is_transaction_doone >0)
			 {
				$today_date=date('Y-m-d');
				$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
				$this->db->where('exam_master.elg_mem_db','Y');
				//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('member_exam.pay_status','1');
				$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('dbregnumber')));
			}
				
			
				if($flag==0)
				{
					$data=array('middle_content'=>'dbf/not_eligible','check_eligibility'=>$message);
					$this->load->view('dbf/dbf_common_view',$data);
				}
				else if(count($applied_exam_info) > 0)
				{
						/*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
						$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
						$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
					$message='You have already applied for the examination';
					$data=array('middle_content'=>'dbf/already_apply','check_eligibility'=>$message);
					$this->load->view('dbf/dbf_common_view',$data);
				}
				else 
				{
					$exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
					$data=array('middle_content'=>'dbf/cms_page','exam_info'=>$exam_info);
					$this->load->view('dbf/dbf_common_view',$data);
				}
			
			//}
			/*else
			{
				$data=array('middle_content'=>'already_apply','check_eligibility'=>'You have already applied for the examination.');
				 $this->load->view('common_view',$data);	
			}*/
				
		}
		
		
	##-------------- check qualify exam pass/fail
	public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)
	{
		$flag=0;
		$check_qualify=array();
		$message='Pre qualifying exam not found';
		$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
		$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('dbregnumber')),'exam_status,remark');
		if(count($check_qualify_exam_eligibility) > 0)
		{
			if($check_qualify_exam_eligibility[0]['exam_status']=='P')
			{
					//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('dbregnumber')));
					if(count($check_eligibility_for_applied_exam) > 0)
					{
						if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
						}
						else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
						{
							$flag=1;
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
					}
					else
					{
						//CAIIB apply directly
						$flag=1;
						$check_qualify=array('flag'=>$flag,'message'=>$message);
						return $check_qualify;
					}
			}
			else
			{
				$flag=0;
				$message=$check_qualify_exam_eligibility[0]['remark'];
				$check_qualify=array('flag'=>$flag,'message'=>$message);
				return $check_qualify;
			}
		}
		else
		{
			//show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
			$flag=0;
			if($qualify_id)
			{
				$get_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');	
				if(count($get_exam) > 0)
				{
					$message='You must apply/clear <strong>'.$get_exam[0]['description'].'</strong> exam before applying <strong> '.$check_qualify_exam[0]['description'].'</strong> exam.';
				}
			}
			
			$check_qualify=array('flag'=>$flag,'message'=>$message);
			return $check_qualify;
		}
					
	}
		
	##------------------ CMS Page for logged in user(PRAFULL)---------------##
	/*public function comApplication()
	{
		//Considering B1 as group code in query (By Prafull)
		if($this->session->userdata('examcode')=='')
		{
			redirect(base_url().'Home/examlist/');	
		}
	 	$where="CASE WHEN eligible_master.app_category ='' THEN  fee_master.group_code='B1' ELSE fee_master.group_code=eligible_master.app_category END";
		//$this->db->select("fee_master.*,eligible_master.*,exam_master.*");
		$this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code");
		$this->db->join("eligible_master","eligible_master.exam_code=fee_master.exam_code");
		$this->db->where("eligible_master.member_no",$this->session->userdata('nmregnumber'));
		$this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
		$this->db->where($where,'',false);
		//$this->db->where('fee_master.group_code','B1');
		$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
		$examinfo=$this->master_model->getRecords('exam_master');
		//echo $this->db->last_query();
		if(count($examinfo)==0)
		{
			$this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code");
			$this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
			$this->db->where('fee_master.group_code','B1');
			$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
			$examinfo = $this->master_model->getRecords('exam_master');
		}
		
		
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		//To-do use exam-code wirh medium master
		$medium=$this->master_model->getRecords('medium_master');
		//get center as per exam
		$this->db->where('exam_name',$this->session->userdata('examcode'));
		$center=$this->master_model->getRecords('center_master');
		//user information
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')));
		$data=array('middle_content'=>'nonmember/comApplication','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center);
		$this->load->view('nonmember/nm_common_view',$data);
		
	}*/
	
	
	##------------------ CMS Page for logged in user(PRAFULL)---------------##
	public function comApplication()
	{
		
		//Considering B1 as group code in query (By Prafull)
		if($this->session->userdata('examcode')=='')
		{
			redirect(base_url().'Dbf/examlist/');	
		}
		/*$where="CASE WHEN eligible_master.app_category ='' THEN  fee_master.group_code='B1' ELSE fee_master.group_code=eligible_master.app_category END";
		//$where="fee_master.group_code=eligible_master.app_category";
		$this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code",'left');
		$this->db->join("eligible_master","eligible_master.exam_code=fee_master.exam_code AND eligible_master.eligible_period=fee_master.exam_period",'left');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=eligible_master.eligible_period','left');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where("eligible_master.member_no",$this->session->userdata('nmregnumber'));
		$this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
		$this->db->where($where,'',false);
		$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
		$examinfo=$this->master_model->getRecords('exam_master');*/
		
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
		$this->db->join("eligible_master",'eligible_master.exam_code=misc_master.exam_code AND misc_master.exam_period=eligible_master.eligible_period','left');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where("eligible_master.member_no",$this->session->userdata('dbregnumber'));
		$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
		$examinfo=$this->master_model->getRecords('exam_master');
		//center for eligible member
		$this->db->join("eligible_master","eligible_master.exam_code=center_master.exam_name AND eligible_master.eligible_period=center_master.exam_period");
		$this->db->where('center_master.exam_name',$this->session->userdata('examcode'));
		$this->db->where("eligible_master.member_no",$this->session->userdata('dbregnumber'));
		$this->db->where("center_delete",'0');
		$center=$this->master_model->getRecords('center_master');
		
		//Below code, if member is new member
		if(count($examinfo) <=0)
		{
			$this->db->select('fee_master.*,exam_master.*,misc_master.*,fee_master.fee_amount as fees');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code");
 			$this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
 			$this->db->where('fee_master.group_code','B1');
			$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
 			$examinfo = $this->master_model->getRecords('exam_master');
			//echo $this->db->last_query();exit;
			//get center
			/*$this->db->join('misc_master','misc_master.exam_code=center_master.exam_name AND misc_master.exam_period=center_master.exam_period');
			$this->db->where("center_delete",'0');
			$this->db->where('exam_name',$this->session->userdata('examcode'));
			$center=$this->master_model->getRecords('center_master');*/
			$this->db->join('misc_master','misc_master.exam_code=center_master.exam_name AND misc_master.exam_period=center_master.exam_period');
			$this->db->where("center_delete",'0');
			$this->db->where('exam_name',$this->session->userdata('examcode'));
			$this->db->group_by('center_master.center_name');
			$center=$this->master_model->getRecords('center_master');
		
		}
		
		if(count($examinfo)<=0)
		{
			redirect(base_url().'Dbf/examlist');
		}
		
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		//To-do use exam-code wirh medium master
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',$this->session->userdata('examcode'));
		$medium=$this->master_model->getRecords('medium_master');
		//get center as per exam
		
		//user information
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));
		if(count($user_info) <=0)
		{
			redirect(base_url().'Dbf/dashboard');
		}
		
		//subject information
		$subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E'));
		
		$data=array('middle_content'=>'dbf/comApplication','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'subjects'=>$subjects);
		$this->load->view('dbf/dbf_common_view',$data);
		
	}
	
	
	##------------------ Set applied exam value in session for logged in user(PRAFULL)---------------##
	public function setExamSession()
	{
		$outputphoto1=$outputsign1=$photo_name=$sign_name='';
		if($this->session->userdata('examinfo'))
		{
			$this->session->unset_userdata('examinfo');
		}
		//Generate dynamic photo
		$date=date('Y-m-d h:i:s');
		$applicationNo = $this->session->userdata('dbregnumber');
		if($_POST["hiddenphoto"]!='')
		{
			$input = $_POST["hiddenphoto"];
			$tmp_nm = 'p_'.$this->session->userdata('dbregnumber').'.jpg';
			$outputphoto = getcwd()."/uploads/photograph/".$tmp_nm;
			$outputphoto1 = base_url()."uploads/photograph/".$tmp_nm;
			file_put_contents($outputphoto, file_get_contents($input));
			$photo_name = $tmp_nm;
			
			/*$path = "./uploads/photograph";
			//$new_filename = 'photo_'.strtotime($date).rand(1,99999);
			$new_filename = 'p_'.$applicationNo;
			$uploadData = upload_file('hiddenphoto', $path, $new_filename,'100','120',TRUE);
			if($uploadData)
			{
				$photo_name = $uploadData['file_name'];
			}*/
		}
		
		// generate dynamic Signature
		if($_POST["hiddenscansignature"]!='')
		{
			$inputsignature = $_POST["hiddenscansignature"];
			$tmp_signnm = 's_'.$this->session->userdata('dbregnumber').'.jpg';
			$outputsign = getcwd()."/uploads/scansignature/".$tmp_signnm;
			$outputsign1 = base_url()."uploads/scansignature/".$tmp_signnm;
			file_put_contents($outputsign, file_get_contents($inputsignature));
			$sign_name = $tmp_signnm;
			
			/*$path = "./uploads/scansignature";
			//$new_filename = 'sign_'.strtotime($date).rand(1,99999);
			$new_filename = 's_'.$applicationNo;
			$uploadData = upload_file('hiddenscansignature', $path, $new_filename,'100','120',TRUE);
			if($uploadData)
			{
				$sign_name = $uploadData['file_name'];
			}*/
		}
		

		$user_data=array(	'email'=>$_POST["email"],	
							'mobile'=>$_POST["mobile"],	
							'photo'=>$photo_name,
							'signname'=>$sign_name,
							'medium'=>$_POST['medium'],
							'selCenterName'=>$_POST['selCenterName'],
							'optmode'=>$_POST['optmode'],
							'extype'=>$_POST['extype'],
							'exname'=>$_POST['exname'],
							'excd'=>$_POST['excd'],
							'eprid'=>$_POST['eprid'],
							'fee'=>$_POST['fee'],
							'txtCenterCode'=>$_POST['txtCenterCode'],
							'insert_id'=>'',
							'selected_elect_subcode'=>$_POST['selSubcode'],
							'selected_elect_subname'=>$_POST['selSubName1'],
							'placeofwork'=>$_POST['placeofwork'],
							'state_place_of_work'=>$_POST['state_place_of_work'],
							'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
							'elected_exam_mode'=>$_POST['elected_exam_mode']
						);
		$this->session->set_userdata('examinfo',$user_data);
		return 'true';
	}
	
	##------------------ Preview for applied exam,for logged in user(PRAFULL)---------------##
	public function preview()
	{
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url());
		}
		$check=$this->examapplied($this->session->userdata('dbregnumber'),$this->session->userdata['examinfo']['excd']);
		if(!$check)
		{		
			$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$medium=$this->master_model->getRecords('medium_master');
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$center=$this->master_model->getRecords('center_master','','center_name');
			//echo $this->db->last_query();exit;
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));
			$misc=$this->master_model->getRecords('misc_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_delete'=>'0'));
			
			$data=array('middle_content'=>'dbf/exam_preview','user_info'=>$user_info,'medium'=>$medium,'center'=>$center,'misc'=>$misc);
			$this->load->view('dbf/dbf_common_view',$data);
		}
		else
		{
						/*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_master.misc_delete'=>'0'),'exam_month');
						$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
						$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
						$message='You have already applied for the examination';
			 $data=array('middle_content'=>'dbf/already_apply','check_eligibility'=>$message);
			 $this->load->view('dbf/dbf_common_view',$data);	
		}
	}
	
	
	##------------------Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
	/*public function Msuccess()
	{
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'NonMember/dashboard/');
		}
		if(isset($_POST['btnPreview']))
		{
			$inser_array=array(	'regnumber'=>$this->session->userdata('nmregnumber'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$this->session->userdata['examinfo']['fee'],
											'pay_status'=>'1',
											'created_on'=>date('y-m-d h:i:sa')
											);
			if($last_exam_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				 $update_array=array();
				
				//update an array for images
				if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')),'scannedsignaturephoto');
					$singname=$sing_name[0]['scannedphoto'];
				}
				
				//check if email is unique
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email']));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));	
				}
				// check if mobile is unique
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile']));
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));	
				}
				if(count($update_array) > 0)
				{
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')));
					if($photoname!='')
						@unlink('uploads/photograph/'.$photoname);
					if($singname!='')
						@unlink('uploads/scansignature/'.$singname);
					log_nm_activity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
				}
				
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');
				
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
				$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$last_exam_id,'member_exam.regnumber'=>$this->session->userdata('nmregnumber')),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				if($exam_info[0]['exam_mode']=='ON')
				{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
				{$mode='Offline';}
				else{$mode='';}
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				//Get Medium
				$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
				$this->db->where('exam_period',$exam_info[0]['exam_period']);
				$this->db->where('medium_code',$exam_info[0]['exam_medium']);
				$this->db->where('medium_delete','0');
				$medium=$this->master_model->getRecords('medium_master','','medium_description');
				
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'nonmember_exam_enrollment_nofee '));
				$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('nmregnumber')."",$newstring1);
				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
				$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
				$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
				$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
				$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
				$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
				$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
				$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
				$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
				$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
				$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
				$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
				$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
				$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
				$newstring17 = str_replace("#AMOUNT#", "".'-'."",$newstring16);
				$final_str = str_replace("#MODE#", "".$mode."",$newstring17);
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				$this->Emailsending->mailsend($info_arr);
				redirect(base_url().'NonMember/applydetails/');
			}
		}
		else
		{
			redirect(base_url().'NonMember/dashboard/');
		}
	}*/
	
	
	public function details($order_no=NULL,$excd=NULL)
	{
	//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('dbregnumber')));
		
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_db','Y');	

		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($excd),'regnumber'=>$this->session->userdata('dbregnumber')));
		if(count($applied_exam_info)<=0)
		{
			redirect(base_url().'Dbf/dashboard');
		}
			
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',base64_decode($excd));
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		$this->db->where('exam_name',base64_decode($excd));
		$this->db->where("center_delete",'0');
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$center=$this->master_model->getRecords('center_master');
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'Dbf/dashboard/');
		}
		
		$data=array('middle_content'=>'dbf/exam_applied_success','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info,'payment_info'=>$payment_info);
		$this->load->view('dbf/dbf_common_view',$data);
	
	}
	
	//Detail page for non member without pay
	public function applydetails()
	{
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'Dbf/dashboard/');
		}
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_db','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'regnumber'=>$this->session->userdata('dbregnumber')));
		
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		$this->db->join('misc_master','misc_master.exam_code=center_master.exam_name AND misc_master.exam_period=center_master.exam_period');
		$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$this->db->where("center_delete",'0');
		$center=$this->master_model->getRecords('center_master');
		
		
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'Dbf/dashboard/');
		}
		$data=array('middle_content'=>'dbf/exam_applied_success_withoutpay','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info);
		$this->load->view('dbf/dbf_common_view',$data);
	
	}
	
	/*public function examapplied($regnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$today_date=date('Y-m-d');
		$this->db->like('exam_master.elg_mem_db','Y');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		//$this->db->where('payment_transaction.status','1');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber));
		return count($applied_exam_info);
	}*/
	
	
	public function examapplied($nmregnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$today_date=date('Y-m-d');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$this->db->where('exam_master.elg_mem_db','Y');
		$this->db->where('pay_status','1');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$nmregnumber));
		
		//echo $this->db->last_query();exit;
		return count($applied_exam_info);
	}
	
	//check whether applied exam date fall in same date of other exam date(Prafull)
	public function examdate($nmregnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$today_date=date('Y-m-d');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$nmregnumber,'pay_status'=>'1'),'member_exam.exam_code');
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
									break;
								}
							}
						}
					}
				}
		}
		return $flag;
	}
	
	
	//get applied exam name which is fall on same date(Prafull)
	public function get_alredy_applied_examname($nmregnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$msg='';
		$today_date=date('Y-m-d');
		
		$this->db->select('subject_master.*,exam_master.description');
		$this->db->join('exam_master','exam_master.exam_code=subject_master.exam_code');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$nmregnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong> examination.";
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
										$msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong> examination.";
									break;
								}
							}
						}
					}
				}
			}
		return $msg;
	}
	
	
	
	
	
	##------------------Insert data in member_exam table for applied exam,for logged in user Without Payment(PRAFULL)---------------##
	public function saveexam()
	{
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'Dbf/dashboard/');
		}
		if(isset($_POST['btnPreview']))
		{
			$inser_array=array(	'regnumber'=>$this->session->userdata('dbregnumber'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$this->session->userdata['examinfo']['fee'],
											'pay_status'=>'1',
											'created_on'=>date('y-m-d h:i:sa')
											);
			if($last_exam_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				 $update_array=array();
				 $this->session->userdata['examinfo']['insert_id']=$last_exam_id;
				//update an array for images
				if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'scannedsignaturephoto');
					$singname=$sing_name[0]['scannedsignaturephoto'];
				}
				
				//check if email is unique
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email']));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));	
				}
				// check if mobile is unique
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile']));
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));	
				}
				if(count($update_array) > 0)
				{
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));
					/*if($photoname!='')
						@unlink('uploads/photograph/'.$photoname);
					if($singname!='')
						@unlink('uploads/scansignature/'.$singname);*/
					log_nm_activity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
				}
				
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');
				
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
				$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$last_exam_id,'member_exam.regnumber'=>$this->session->userdata('dbregnumber')),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				
				if($exam_info[0]['exam_mode']=='ON')
				{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
				{$mode='Offline';}
				else{$mode='';}
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				//Get Medium
				$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
				$this->db->where('exam_period',$exam_info[0]['exam_period']);
				$this->db->where('medium_code',$exam_info[0]['exam_medium']);
				$this->db->where('medium_delete','0');
				$medium=$this->master_model->getRecords('medium_master','','medium_description');
				
				
				
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'nonmember_exam_enrollment_nofee '));
				$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('dbregnumber')."",$newstring1);
				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
				$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
				$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
				$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
				$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
				$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
				$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
				$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
				$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
				$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
				$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
				$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
				$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
				$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
				$newstring17 = str_replace("#AMOUNT#", "".'-'."",$newstring16);
				$final_str = str_replace("#MODE#", "".$mode."",$newstring17);
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
			
		
				
				$this->Emailsending->mailsend($info_arr);
				redirect(base_url().'Dbf/applydetails/');
			}
		}
		else
		{
			redirect(base_url().'Dbf/dashboard/');
		}
	}
	
	
	##------------------NON MEMBER Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
	public function Msuccess()
	{
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'Dbf/dashboard/');
		}
		if(isset($_POST['btnPreview']))
		{
			$inser_array=array(	'regnumber'=>$this->session->userdata('dbregnumber'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$this->session->userdata['examinfo']['fee'],
											'created_on'=>date('y-m-d h:i:sa')
											);
			if($insert_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				//echo $this->session->userdata['examinfo']['fee'];
				$this->session->userdata['examinfo']['insert_id']=$insert_id;
				$update_array=array();
				//update an array for images
				if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'scannedsignaturephoto');
					$singname=$sing_name[0]['scannedsignaturephoto'];
				}
				
				//check if email is unique
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email'],'isactive'=>'1'));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));	
				}
				// check if mobile is unique
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile'],'isactive'=>'1'));
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));	
				}
				if(count($update_array) > 0)
				{
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));
					//@unlink('uploads/photograph/'.$photoname);
					//@unlink('uploads/scansignature/'.$singname);
					
				logactivity($log_title ="Dbf update profile during exam apply", $log_message = serialize($update_array));
					
				}
				if($this->config->item('exam_apply_gateway')=='sbi')
				{
					redirect(base_url().'Dbf/sbi_make_payment/');
				}
				else
				{
					redirect(base_url().'Dbfpayment/make_payment/');
				}
			}
		}
		else
		{
			redirect(base_url().'Dbf/dashboard/');
		}
	}
	
	
	##------------------Exam apply with SBI Payment Gate-way(PRAFULL)---------------##
	public function sbi_make_payment()
	{
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url().'Dbfpayment/dashboard/');
		}
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$regno = $this->session->userdata('dbregnumber');//$this->session->userdata('regnumber');
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Dbf/sbitranssuccess";
			$pg_fail_url    = base_url()."Dbf/sbitransfail";
			
			if($this->config->item('sb_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				$amount=$this->session->userdata['examinfo']['fee'];
			}
			$amount=1;
			//$MerchantOrderNo = generate_order_id("sbi_exam_order_id");

			// With Login DBF
			// Non memeber / DBF Apply exam
			// Ref1 = orderid
			// Ref2 = iibfexam
			// Ref3 = member_regno
			// Ref4 = exam_code + exam year + exam month ex (101201602)
 			
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'exam_period'=>$this->session->userdata['examinfo']['eprid']),'exam_month');
			
			if(base64_decode($this->session->userdata['examinfo']['excd'])==340)
			{
				$exam_code=34;		
			}
			elseif(base64_decode($this->session->userdata['examinfo']['excd'])==580)
			{
				$exam_code=58;	
			}
			else
			{
				$exam_code=base64_decode($this->session->userdata['examinfo']['excd']);	
			}
			
			$ref4=($exam_code).$yearmonth[0]['exam_month'];
			
			// Create transaction
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $this->session->userdata['examinfo']['insert_id'],
				'description'      => $this->session->userdata['examinfo']['exname'],
				'status'           => '2',
				'exam_code'        => base64_decode($this->session->userdata['examinfo']['excd']),
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>'IIBF_EXAM_DB_EXAM',
				//'pg_other_details'=>$custom_field
			);
				
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$this->session->userdata('dbregnumber')."^".$ref4;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			$MerchantCustomerID = $regno;
			$custom_field = "^iibfexam^^";

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

			$this->load->view('pg_sbi_form',$data);
		}
		else
		{
			$this->load->view('pg_sbi/make_payment_page');
		}
	}
	
	//SBI Success 
	public function sbitranssuccess()
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
		// Handle transaction success case 
		$q_details = sbiqueryapi($MerchantOrderNo);
		if ($q_details)
		{
			if ($q_details[2] == "SUCCESS")
			{	
				// Handle transaction success case 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
				if($get_user_regnum[0]['status']==2)
				{
					if(count($get_user_regnum) > 0)
					{
						$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
					}
					
					$update_data = array('pay_status' => '1');
					$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');
					
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					//Query to get Medium	
					$this->db->where('exam_code',$exam_info[0]['exam_code']);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
			
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
					$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
					$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
					$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
					$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
					$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
					$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
					$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
					$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
					$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
					$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
					$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
					$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
					$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
					$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
					$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring19);
				
					$info_arr=array('to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
										
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					}
			}
		}
		//MAIN CODE
		$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
	redirect(base_url().'Dbf/details/'.base64_encode($MerchantOrderNo).'/'.base64_encode($exam_info[0]['exam_code']));	
					
						
	}
	
	//SBI Failure
	public function sbitransfail()
	{
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
			
			// SBI CALLBACK B2B
			// Handle transaction fail case 
			$get_user_regnum=$this->aster_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			if($get_user_regnum[0]['status']==2)
			{
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
				$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
				
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
					
			   // Handle transaction 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
			
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
				$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
				$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
				$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
				
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				
				$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
				$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->Emailsending->mailsend($info_arr);
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
			//End Of SBI CallBack	
				
			redirect(base_url().'Dbf/fail/'.base64_encode($MerchantOrderNo));			
			//echo 'transaction fail';exit;
						
		//echo 'transaction fail';exit;
		}
		else
		{
			die("Please try again...");
		}
	}
	
	
	//Show acknowlodgement to to user after transaction Failure
	public function fail($order_no=NULL)
	{
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('dbregnumber')));
		if(count($payment_info) <=0)
		{
			redirect(base_url().'Dbf/dashboard/');
		}
		$data=array('middle_content'=>'dbf/exam_applied_fail','payment_info'=>$payment_info);
		$this->load->view('dbf/dbf_common_view',$data);
	
	}
	##---------Forcefully Update profile mesage to user(prafull)-----------##
	public function notification()
	{
		$msg='';
		$flag=1;
		$user_images=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'),'isactive'=>'1'),'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');
		
		  if(!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']=='')
		 {
			 $flag=0;
			$msg.='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'Dbf/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>';
		 }
		 if($user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
		 {
			 $flag=0;
			$msg.='<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'Dbf/profile/">click here</a> to update the, email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
		}
		
/*		if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']) && ($user_images[0]['mobile']=='' ||$user_images[0]['email']==''))
		{
			$flag=0;
			$msg='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'NonMember/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>
<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'NonMember/profile/">click here</a> to update the, then email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
		}*/
		 
		if($flag)
		{
			redirect(base_url().'Dbf/profile/');
		}
		$data=array('middle_content'=>'dbf/dbfmember_notification','msg'=>$msg);
		$this->load->view('dbf/dbf_common_view',$data);
	}
	
	//print user edit profile (Prafull)
	public function printUser()
	{
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			//$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			//$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			//$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'),'isactive'=>'1'));
			
		
			if(count($user_info) <=0)
			{
				redirect(base_url().'Dbf/dashboard');
			}
			if(count($user_info) < 0)
			{
				redirect(base_url());
			}
			
			if($user_info[0]['qualification']=='U')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'UG'),'name as qname','','',1);
			}
			else if($user_info[0]['qualification']=='G')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'GR'),'name as qname','','',1);
			}else if($user_info[0]['qualification']=='P')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'PG'),'name as qname','','',1);
			}
		
			
			$this->db->where('id',$user_info[0]['idproof']);
			$idtype_master=$this->master_model->getRecords('idtype_master','','name');
			$data=array('middle_content'=>'dbf/print_non_member_profile','user_info'=>$user_info,'qualification'=>$qualification,'idtype_master'=>$idtype_master);
			$this->load->view('dbf/dbf_common_view',$data);
	}
	
	// ##-------download pdf (Prafull)
	public function downloadeditprofile()
	{
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			//$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			//$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			//$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'),'isactive'=>'1'));
			if(count($user_info) < 0)
			{
				redirect(base_url());
			}
			if($user_info[0]['qualification']=='U')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'UG'),'name as qname','','',1);
			}
			else if($user_info[0]['qualification']=='G')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'GR'),'name as qname','','',1);
			}else if($user_info[0]['qualification']=='P')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'PG'),'name as qname','','',1);
			}
			$this->db->where('id',$user_info[0]['idproof']);
			$idtype_master=$this->master_model->getRecords('idtype_master','','name');
			
			  $username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			  $userfinalstrname;
			 if($user_info[0]['gender']=='female'){ $gender='Female';}
            if($user_info[0]['gender']=='male'){$gender= 'Male';}
			if($user_info[0]['qualification']=='U'){$memqualification=  'Under Graduate';}
		  	if($user_info[0]['qualification']=='G'){$memqualification=  'Graduate';}
			if($user_info[0]['qualification']=='P'){$memqualification=  'Post Graduate';}
			
			if($user_info[0]['optnletter']=='Y'){$optnletter=  'Yes';}
         	if($user_info[0]['optnletter']=='N'){$optnletter=  'No';}
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
			if($user_info[0]['address2']!='')
			{
				 $user_info[0]['address2']=','.$user_info[0]['address2'].'*';
			}
			if($user_info[0]['address3']!='')
			{
				 $user_info[0]['address3']=','.$user_info[0]['address3'];
			}
			if($user_info[0]['address4']!='')
			{
				$user_info[0]['address4']=','.$user_info[0]['address4'];
			}
			$string1=$user_info[0]['address1'].$user_info[0]['address2'].$user_info[0]['address3'].$user_info[0]['address4'];
			$finalstr1= str_replace("*","<br>",$string1);
		   $string2=','.$user_info[0]['district'].','.$user_info[0]['city'].'*'.$user_info[0]['state_name'].','.$user_info[0]['pincode'];
		   $finalstr2=str_replace("*",",<br>",$string2);
		   $useradd=$finalstr1.$finalstr2;
			$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
    border: 1px solid #000; padding:25px;
  ">         

<tbody><tr> <td colspan="4" align="left">&nbsp;</td> </tr>

<tr>

	<td colspan="4" align="center" height="25">
		<span id="1001a1" class="alert">
		</span>
	</td>
</tr>

<tr style="border-bottom:solid 1px #000;"> 
	<td colspan="4" height="1"><img src="'.base_url().'assets/images/logo1.png"></td>
</tr>
		   
<tr>
	<td colspan="4">
	</hr>

	<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
					<tbody><tr>
			<td class="tablecontent2" width="51%">Membership No : </td>
			<td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info[0]['regnumber'].'</td>
			<td class="tablecontent" rowspan="4" valign="top">
			<img src="'.base_url().'uploads/photograph/'.$user_info[0]['scannedphoto'].'" height="100" width="100" >
			</td>
		</tr>
				<tr>
			<td class="tablecontent2">Password :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">'.$decpass.' </td>
		</tr>

		<tr>
			<td class="tablecontent2">Full Name :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">'.$userfinalstrname.'
			</td>
		</tr>

				<tr>
				<td class="tablecontent2">Name as to appear on Card :</td>
					<td colspan="2" class="tablecontent2" nowrap="nowrap">
					'.$user_info[0]['displayname'].'
				</td>
		</tr>


		<tr>
			<td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
			<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
				'. $useradd.'			</td>
		</tr>
				
		<tr>
			<td class="tablecontent2">Date of Birth :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.date('d-m-Y',strtotime($user_info[0]['dateofbirth'])).'</td>
		</tr>	

		<tr>
			<td class="tablecontent2">Gender :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$gender.' </td>
		</tr>			  			
				
		<tr>
			<td class="tablecontent2">Qualification :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$memqualification.' </td>
		</tr>

		<tr>
			<td class="tablecontent2">Specify :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$qualification[0]['qname'].' </td>
		</tr>


		<tr>
			<td class="tablecontent2">Email :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'. $user_info[0]['email'].' </td>
		</tr>
				
		
		<tr>
			<td class="tablecontent2">Mobile :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['mobile'].' </td>
		</tr>

		<tr>

			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$idtype_master[0]['name'].'</td>
		</tr>

		<tr>
			<td class="tablecontent2">ID No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['idNo'].'</td>
		</tr>
				

		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="'.base_url().'uploads/idproof/'.$user_info[0]['idproofphoto'].'"  height="180" width="100"></td>
		</tr>

		<tr>
			<td class="tablecontent2">Signature :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="'.base_url().'uploads/scansignature/'.$user_info[0]['scannedsignaturephoto'].'" height="100" width="100"></td>
		</tr>

		<tr>
			<td class="tablecontent2">Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				'.date('d-m-Y h:i:s A',strtotime($user_info[0]['createdon'])).'		</td>
		</tr>

		</tbody></table>
	</td>
</tr>
	
</tbody></table>';


		 
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'iibf'.'.pdf';
			//load mPDF library
			$this->load->library('m_pdf');
			//actually, you can pass mPDF parameter on this load() function
			$pdf = $this->m_pdf->load();
			//$pdf->SetHTMLHeader($header);
			$pdf->SetHTMLHeader(''); 
			$pdf->SetHTMLFooter('');
			$stylesheet = '/*Table with outline Classes*/
								 .tablecontent2 {
								  background-color: #ffffff;
								  bottom: 5px;
								  color: #000000;
								  font-family: Tahoma;
								  font-size: 11px;
								  font-weight: normal;
								  height: 10px;
								  left: 5px;
								  padding: 5px;
								  right: 5px;
								  top: 5px;
								}
								.img{ width:100%; height:auto; padding:15px;}';
			 header('Content-Type: application/pdf'); 
             header('Content-Description: inline; filename.pdf');
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath,'D');
	
	}
	
	// ##---------Download pdf(Prafull)-----------##
	public function pdf()
	{$html='<div class="content-wrapper">
		<section class="content-header">
	   <center>    <h3>
		 INDIAN INSTITUTE OF BANKING & FINANCE
		  </h3>
		 <p><span class="box-header with-border"> (AN ISO 9001:2008 Certified ) </span></p></center>
		</section>
		<section class="content">
		  <div class="row">
			<div class="col-md-12">
			  <div class="box box-info">
				<div class="box-header with-border">
				</div>
				<div class="box-body">
				   <div class="form-group">
					<label for="roleid" class="col-sm-3 control-label"></label>
						<center>
						<div class="col-sm-2">
						Your application saved successfully.<br><br><strong>Your Membership No is</strong> '.$this->session->userdata('dbregnumber').' <strong>and Your password is </strong>'.base64_decode($this->session->userdata('nmpassword')).'<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
						</div>
						</center>
					</div>
				 </div>
			  </div> 
			</div>
		  </div>
		</section>
	 </div>';
		 
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'iibf'.'.pdf';
			//load mPDF library
			$this->load->library('m_pdf');
			//actually, you can pass mPDF parameter on this load() function
			$pdf = $this->m_pdf->load();
			//$pdf->SetHTMLHeader($header);
			$pdf->SetHTMLHeader(''); 
			$pdf->SetHTMLFooter('');
			$stylesheet = '/*Table with outline Classes*/
								table.tbl-2 { outline: none; width: 100%; border-right:1px solid #cccaca; border-top: 1px solid #cccaca;}
								table.tbl-2 th { background: #222D3A; border-bottom: 1px solid #cccaca; border-left:1px solid #dbdada; color: #fff; padding: 5px; text-align: center;}
								table.tbl-2 th.head { background: #CECECE; text-align:left;}
								table.tbl-2 td.tda2 { background: #f7f7f7; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tdb2 { background: #ebeaea; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tda2 a { color: #0d64a0;}
								table.tbl-2 td.tda2 a:hover{ color: #0d64a0; text-decoration:none;}
								table.tbl-2 td.tdb2 a { color: #0d64a0;}
								table.tbl-2 td.tdb2 a:hover{ color: #0d64a0; text-decoration:none;}
								.align_class_table{text-align:center !important;}
								.align_class_table_right{text-align:right !important;}';
			 header('Content-Type: application/pdf'); 
             header('Content-Description: inline; filename.pdf');
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath,'D');
	}
	
	// ##---------Download exam applied pdf(Prafull)-----------##
	public function exampdf()
	{
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'Dbf/dashboard/');
		}
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->where('state_master.state_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'),'isactive'=>'1'));
			if(count($user_info) < 0)
			{
				redirect(base_url());
			}
			if($user_info[0]['qualification']=='U')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'UG'),'name as qname','','',1);
			}
			else if($user_info[0]['qualification']=='G')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'GR'),'name as qname','','',1);
			}else if($user_info[0]['qualification']=='P')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'PG'),'name as qname','','',1);
			}
			$this->db->where('id',$user_info[0]['idproof']);
			$idtype_master=$this->master_model->getRecords('idtype_master','','name');
			
			$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			$userfinalstrname;
			if($user_info[0]['gender']=='female'){ $gender='Female';}
			if($user_info[0]['gender']=='male'){$gender= 'Male';}
			if($user_info[0]['qualification']=='U'){$memqualification=  'Under Graduate';}
			if($user_info[0]['qualification']=='G'){$memqualification=  'Graduate';}
			if($user_info[0]['qualification']=='P'){$memqualification=  'Post Graduate';}
			
			if($user_info[0]['optnletter']=='Y'){$optnletter=  'Yes';}
			if($user_info[0]['optnletter']=='N'){$optnletter=  'No';}
			
				
			if($user_info[0]['address2']!='')
			{
				 $user_info[0]['address2']=','.$user_info[0]['address2'];
			}
			if($user_info[0]['address3']!='')
			{
				 $user_info[0]['address3']=','.$user_info[0]['address3'].'*';
			}
			if($user_info[0]['address4']!='')
			{
				$user_info[0]['address4']=','.$user_info[0]['address4'];
			}
			$string1=$user_info[0]['address1'].$user_info[0]['address2'].$user_info[0]['address3'].$user_info[0]['address4'];
			$finalstr1= str_replace("*","<br>",$string1);
		   $string2=','.$user_info[0]['district'].','.$user_info[0]['city'].'*'.$user_info[0]['state_name'].','.$user_info[0]['pincode'];
		   $finalstr2=str_replace("*",",<br>",$string2);
		   $useradd=$finalstr1.$finalstr2;
		   
		   
			$today_date=date('Y-m-d');
			$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
			$this->db->where('elg_mem_db','Y');	
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'regnumber'=>$this->session->userdata('dbregnumber')));
		
	
			
			$this->db->where('medium_delete','0');
			$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master');
		
			$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
			$exam_period=date('F',strtotime($month))."-".substr($applied_exam_info['0']['exam_month'],0,-2);
			if($applied_exam_info[0]['exam_mode']=='ON')
			{$mode='Online';}
			else if($applied_exam_info[0]['exam_mode']=='OF')
			{$mode='Offline';}
		
				$html='
			<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;border: 1px solid #000; padding:25px;">         
				<tbody>
				<tr> <td colspan="4" align="left">&nbsp;</td> </tr>
				<tr>
					<td colspan="4" align="center" height="25"><span id="1001a1" class="alert"></span></td>
				</tr>
				<tr style="border-bottom:solid 1px #000;"> 
					<td colspan="4" height="1"><img src="'.base_url().'assets/images/logo1.png"></td>
				</tr>
				<tr></tr>
		    <tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>	   
			<tr><br></tr>
		<tr>
			<td colspan="4">
			</hr>
			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
			<tbody><tr>
			<td class="tablecontent2" width="51%">Membership No : </td>
			<td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info[0]['regnumber'].'</td>
			<td class="tablecontent" rowspan="4" valign="top">
			<img src="'.base_url().get_img_name($this->session->userdata('dbregnumber'),'p').'" height="100" width="100" >
			</td>
		</tr>
		<tr>
			<td class="tablecontent2">Full Name :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">'.$userfinalstrname.'
			</td>
		</tr>
		<tr>
			<td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
			<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">'. $useradd.'</td>
		</tr>
		<tr>
			<td class="tablecontent2">Date of Birth :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.date('d-m-Y',strtotime($user_info[0]['dateofbirth'])).'</td>
		</tr>	
		<tr>
			<td class="tablecontent2">Gender :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$gender.' </td>
		</tr>			  			
		<tr>
			<td class="tablecontent2">Qualification :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$memqualification.' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Specify :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$qualification[0]['qname'].' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Email :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'. $user_info[0]['email'].' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Mobile :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['mobile'].' </td>
		</tr>
		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$idtype_master[0]['name'].'</td>
		</tr>
		<tr>
			<td class="tablecontent2">ID No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['idNo'].'</td>
		</tr>
                <tr>
			<td class="tablecontent2">Exam Name :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$applied_exam_info[0]['description'].'</td>
		</tr>
          <tr>
			<td class="tablecontent2">Amount :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$applied_exam_info[0]['exam_fee'].'</td>
		</tr>
      <tr>
			<td class="tablecontent2">Exam Preiod :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$exam_period.'</td>
		</tr>
         <tr>
			<td class="tablecontent2">Mode :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$mode.'</td>
		</tr>
            <tr>
			<td class="tablecontent2">Medium :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$medium[0]['medium_description'].'</td>
		</tr>
		<tr>
			<td class="tablecontent2">Centre Name :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$center[0]['center_name'].'</td>
		</tr>
		<tr>
			<td class="tablecontent2">Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				'.date('d-m-Y h:i:s A').'		</td>
		</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>';
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.'.pdf';
			//load mPDF library
			$this->load->library('m_pdf');
			//actually, you can pass mPDF parameter on this load() function
			$pdf = $this->m_pdf->load();
			//$pdf->SetHTMLHeader($header);
			$pdf->SetHTMLHeader(''); 
			$pdf->SetHTMLFooter('');
			$stylesheet = '/*Table with outline Classes*/
								 .tablecontent2 {
								  background-color: #ffffff;
								  bottom: 5px;
								  color: #000000;
								  font-family: Tahoma;
								  font-size: 11px;
								  font-weight: normal;
								  height: 10px;
								  left: 5px;
								  padding: 5px;
								  right: 5px;
								  top: 5px;
								}
								.img{ width:100%; height:auto; padding:15px;}';
			 header('Content-Type: application/pdf'); 
             header('Content-Description: inline; filename.pdf');
			$pdf->WriteHTML($stylesheet,1);

			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath,'D');
	
	
	}
	
	//##----Print Exam Details pdf (Prafull)
	public function printexamdetails()
	{
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'Dbf/dashboard/');
		}
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->where('state_master.state_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber'),'isactive'=>'1'));
			if(count($user_info) < 0)
			{
				redirect(base_url());
			}
			if($user_info[0]['qualification']=='U')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'UG'),'name as qname','','',1);
			}
			else if($user_info[0]['qualification']=='G')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'GR'),'name as qname','','',1);
			}else if($user_info[0]['qualification']=='P')
			{
				$this->db->where('qid',$user_info[0]['specify_qualification']);
				$qualification=$this->master_model->getRecords('qualification',array('type'=>'PG'),'name as qname','','',1);
			}
			$this->db->where('id',$user_info[0]['idproof']);
			$idtype_master=$this->master_model->getRecords('idtype_master','','name');
			
		   
			$today_date=date('Y-m-d');
			$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
			$this->db->where('elg_mem_db','Y');	
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'regnumber'=>$this->session->userdata('dbregnumber')));
		
			
			$this->db->where('medium_delete','0');
			$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master');
		
			$data=array('middle_content'=>'dbf/print_dbf_applied_exam_details','user_info'=>$user_info,'qualification'=>$qualification,'idtype_master'=>$idtype_master,'applied_exam_info'=>$applied_exam_info,'center'=>$center,'medium'=>$medium);
	$this->load->view('dbf/dbf_common_view',$data);
			
	}
	
	
	##---------check pincode/zipcode alredy exist or not (prafull)-----------##
	 public function checkpin()
	{
		$statecode=$_POST['statecode'];
		$pincode=$_POST['pincode'];
		if($statecode!="")
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
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
	
		//dbf admit card
		public function getadmitcard(){
		//To Do-- validate as per admin admit card setting(Need to Do)
		try{
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$member_id = $this->session->userdata('dbregnumber');
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
			$record = $this->db->get();
			$result = $record->row();
			$exam_code = $result->exm_cd;
			$medium_code = $result->m_1;
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$this->db->select('subject_description,date,time');
			$this->db->from('subject_master');
			$this->db->join('admitcard_info', 'subject_master.subject_code = admitcard_info.sub_cd');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
			$subject = $this->db->get();
			$subject_result = $subject->result();
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = $examdate[1]." 20".$examdate[2];
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code));
			$medium_result = $medium->row();
			
			
			
			
			//$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description);
			//$this->load->view('welcome_message', $data);
			
			//echo $result->center_code;
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate);
			//load the view and saved it into $html variable
			$this->load->view('dbf/admitcard', $data);
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
		public function getadmitcardpdf(){
		//To Do-- validate as per admin admit card setting(Need to Do)
		try{
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$member_id = $this->session->userdata('dbregnumber');
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
			$record = $this->db->get();
			$result = $record->row();
			$exam_code = $result->exm_cd;
			$medium_code = $result->m_1;
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$this->db->select('subject_description,date,time');
			$this->db->from('subject_master');
			$this->db->join('admitcard_info', 'subject_master.subject_code = admitcard_info.sub_cd');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
			$subject = $this->db->get();
			$subject_result = $subject->result();
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = $examdate[1]." 20".$examdate[2];
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code));
			$medium_result = $medium->row();
			
			//$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description);
			//$this->load->view('welcome_message', $data);
			
			//echo $result->center_code;
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate);
			//load the view and saved it into $html variable
			$html=$this->load->view('dbf/admitcardpdf', $data, true);
			//this the the PDF filename that user will get to download
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			//generate the PDF from the given html
			$pdf->WriteHTML($html);
			//download it.
			$pdf->Output($pdfFilePath, "D");  
			
			
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	##---------End user logout (Vrushali)-----------##//
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		//redirect(base_url().'nonreg/memlogin/?Extype=MQ==&Mtype=Tk0='); 
		redirect(base_url()); 
	}
	
	
	##------- check eligible user----------##
	public function checkusers($examcode=NULL)
	{
		$flag=0;
		if($examcode!=NULL)
		{
			 $exam_code = array(33,47,51,52);
		 	 if(in_array($examcode,$exam_code))
			{
				 $this->db->where_in('eligible_master.exam_code', $exam_code);
				 $valid_member_list=$this->master_model->getRecords('eligible_master',array('eligible_period'=>'117','member_type'=>'DB'),'member_no');
				if(count($valid_member_list) > 0)
				{
					foreach($valid_member_list as $row)
					{
						$memberlist_arr[]=$row['member_no'];
					}
					 if(in_array($this->session->userdata('dbregnumber'),$memberlist_arr))
					{
						$flag=1;
					}
					else
					{
						$flag=0;
					}
				}
				else
				{
					$flag=0;
				}
			}
			else
			{
				$flag=1;
			}
		}
		return $flag;
		
	}
	
}

