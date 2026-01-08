<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Examtraining extends CI_Controller {
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
		$this->chk_session->Check_mult_session();
		$this->sms_template_id = '';
		//$this->load->model('chk_session');
	    //$this->chk_session->chk_member_session();
		
		//echo "HE";exit;
	}

	 ##---------default userlogin (prafull)-----------##
	public function memlogin()
	{
		$data=array();
		$data['error']='';
		
		$Extype = $this->input->get('Extype');
		$Mtype = $this->input->get('Mtype');
		if(isset($_POST['btnLogin']))
		{
			$config = array(
			array(
					'field' => 'Username',
					'label' => 'Username',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'code',
					'label' => 'Code',
					'rules' => 'trim|required|callback_check_captcha_userlogin',
			),
		);
		
		$this->form_validation->set_rules($config);
			$dataarr=array(
				'regnumber'=> $this->input->post('Username'),
				'registrationtype'=>'NM',
			);
			if ($this->form_validation->run() == TRUE)
			{
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info))
				{ 
					 if($user_info[0]['isactive']==1)
					 {
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
				
						$mysqltime=date("H:i:s");
						$user_data=array('nmregid'=>$user_info[0]['regid'],
													'nmregnumber'=>$user_info[0]['regnumber'],
													'nmfirstname'=>$user_info[0]['firstname'],
													'nmmiddlename'=>$user_info[0]['middlename'],
													'nmlastname'=>$user_info[0]['lastname'],
													'nmtimer'=>base64_encode($mysqltime),
													'memtype'=>$user_info[0]['registrationtype'],
													'nmpassword'=>base64_encode($decpass));
						$this->session->set_userdata($user_data);
						$sess = $this->session->userdata();
						redirect(base_url().'examtraining/showexam/?ExId='.$this->input->get('ExId').'&Extype='.$Extype);
						 }
					  else if($user_info[0]['isactive']==0)
					  {
							$data['error']='<span style="">Invalid Credentials</span>'; 
					  }
					   else
					  {
							$data['error']='<span style="">This account is suspended</span>'; 
					  }
				}
				else
				{
					$data['error']='<span style="">Invalid Credentials</span>';
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors();
			}
		}
		
		$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('nonmemlogincaptcha', $cap['word']);
		$this->load->view('examtraining/nonmember_login',$data);

	}
	
	##---------check captcha userlogin (vrushali)-----------##
	public function check_captcha_userlogin($code) 
	{
		//return true;
		
		if(!isset($this->session->nonmemlogincaptcha) && empty($this->session->nonmemlogincaptcha))
		{
			return false;
		}
		
		if($code == '' || $this->session->nonmemlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
			$this->session->set_userdata("nonmemlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->userlogincaptcha == $code)
		{
			$this->session->set_userdata('nonmemlogincaptcha','');
			$this->session->unset_userdata("nonmemlogincaptcha");
			return true;
		}
	}
	
	
	public function examlist()
	{
		//accedd denied due to GST
		//$this->master_model->warning();
		$examcodes=array('528', '529', '530', '531', '534','991');
		$today_date=date('Y-m-d');
		$flag=1;
		$exam_list=array();
		$Extype = base64_decode($this->input->get('Extype'));
		$Mtype = base64_decode($this->input->get('Mtype'));
		if($Mtype!='O' && $Mtype!='A' && $Mtype!='F' && $Mtype!='DB' && $Mtype!='NM')
		{
			$flag=0;		
		}
		if($flag==1)
		{
			if($Mtype=='O')
			{
				$this->db->where('elg_mem_o','Y');	
			}
			if($Mtype=='A')
			{
				$this->db->where('elg_mem_a','Y');	
			}
			if($Mtype=='F')
			{
				$this->db->where('elg_mem_f','Y');	
			}
			if($Mtype=='DB')
			{
				$this->db->where('elg_mem_db','Y');	
			}
			if($Mtype=='NM')
			{
				$this->db->where('elg_mem_nm','Y');	
			}
		//New do not allow any new member to apply for below examcode
		 //$ignore_exam_code = array(33,47,51,52);
		 
	 	 $this->db->join('subject_master','subject_master.exam_code=exam_master.exam_code');
		 $this->db->join('center_master','center_master.exam_name=exam_master.exam_code');
		 $this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
		 $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.	exam_period');
		  $this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
		 $this->db->where('medium_delete','0');
		 $this->db->where('exam_type',trim($Extype));	
		 $this->db->where("misc_master.misc_delete",'0');
		 $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		 $this->db->where("exam_activation_master.exam_activation_delete","0");
		 //$this->db->where_not_in('exam_activation_master.exam_code', $ignore_exam_code);
		 $this->db->where_not_in('exam_master.exam_code', $examcodes);
		 $this->db->group_by('medium_master.exam_code');
		 //this->db->order_by('exam_activation_master.id','DESC');
		 $this->db->order_by('exam_master.description','ASC');
		 $exam_list=$this->master_model->getRecords('exam_master');
		 
	

			/*$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
			$this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.	exam_period');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$this->db->where('medium_delete','0');
			$this->db->where('exam_type',trim($Extype));	
			$this->db->where('exam_activation_master.exam_activation_delete','0');	
			$this->db->group_by('medium_master.exam_code');
			$exam_list=$this->master_model->getRecords('exam_master');*/
			$exam_type_name=$this->master_model->getRecords('exam_type',array('id'=>trim($Extype)));
			//echo $this->db->last_query();exit;
		}
		$data=array('exam_list' => $exam_list,'Extype'=>base64_encode($Extype),'Mtype'=>base64_encode($Mtype),'exam_type_name'=>$exam_type_name);
		$this->load->view('examtraining/examlist',$data);
	}
	 
	 
	 public function accessdenied()
	{
			$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
			$data=array('middle_content'=>'examtraining/access-denied-registration','check_eligibility'=>$message);
			$this->load->view('examtraining/common_view_fullwidth',$data);
	}
	
	public function member()
	{
	
	
		//http://iibf.teamgrowth.net/Examtraining/member/?Mtype=Tk0=&ExId=MTkx
		$valcookie= applyexam_get_cookie();
		if($valcookie){
			delete_cookie('examid');
		}
		$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password=$var_errors='';
		$data['validation_errors'] = '';
		
		$ExId = base64_decode($this->input->get('ExId'));
		$Mtype = base64_decode($this->input->get('Mtype'));
		
		
		
		$ignore_exam_code = array(33,47,51,52);
		
		if(in_array($ExId,$ignore_exam_code)){
		
			redirect(base_url().'Examtraining/accessdenied?Mtype='.$this->input->get('Mtype').'&ExId='.$this->input->get('ExId'));
		}
		
		/* Check Exam Activation */
		$check_exam_activation = check_exam_activate($ExId);
		if($check_exam_activation==0){
		
			redirect(base_url().'Examtraining/accessdenied?Mtype='.$this->input->get('Mtype').'&ExId='.$this->input->get('ExId'));
		}
		
		$flag=1;
		if(isset($_POST['btnSubmit']))  	
	 	{
			//echo '<pre>',print_r($_POST),'</pre>';exit;
			$scribe_flag='N';
			$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';
			$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
			$this->form_validation->set_rules('firstname','First Name','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('addressline1','Addressline1','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('district','District','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('city','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('state','State','trim|required|xss_clean');
			if($this->input->post('state')!=''){
				$state=$this->input->post('state');
			}
			$examcode=base64_decode($this->input->get('ExId'));
			$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
			$this->form_validation->set_rules('dob1','Date of Birth','trim|required|xss_clean');
			
			$this->form_validation->set_rules('gender','Gender','trim|required|xss_clean');
			$this->form_validation->set_rules('optedu','Qualification','trim|required|xss_clean');
			
			if(isset($_POST['middlename']) && $_POST['middlename']!=''){
				$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
			}
			if(isset($_POST['lastname']) && $_POST['lastname']!=''){
				$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
			}
			if(isset($_POST['optedu']) && $_POST['optedu']=='U'){
				$this->form_validation->set_rules('eduqual1','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');
			}
			else if(isset($_POST['optedu']) && $_POST['optedu']=='G'){
				$this->form_validation->set_rules('eduqual2','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');
			}
			else if(isset($_POST['optedu']) && $_POST['optedu']=='P')
			{
				$this->form_validation->set_rules('eduqual3','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');
			}
			if(isset($_POST['addressline2']) && $_POST['addressline2']!=''){
				$this->form_validation->set_rules('addressline2','Addressline2','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			}
			if(isset($_POST['addressline3']) && $_POST['addressline3']!=''){
				$this->form_validation->set_rules('addressline3','Addressline3','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			}
			if(isset($_POST['addressline4']) && $_POST['addressline4']!=''){
				$this->form_validation->set_rules('addressline4','Addressline4','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			}
			if(isset($_POST['stdcode']) && $_POST['stdcode']!=''){
				$this->form_validation->set_rules('stdcode','STD Code','trim|max_length[4]|required|numeric|xss_clean');
			}
			if(isset($_POST['phone']) && $_POST['phone']!=''){
				$this->form_validation->set_rules('phone',' Phone No','trim|required|numeric|xss_clean');
			}
			$this->form_validation->set_rules('institutionworking','Bank/Institution working','trim|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('designation','Designation','trim|required|xss_clean');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
			$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
			
			$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
			$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
			$this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');
			$this->form_validation->set_rules('idNo','ID No','trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');
			
			if(base64_decode($this->input->get('ExId'))!=101 || $this->input->post('aadhar_card') != '' ){
				if($this->input->post('aadhar_card')!=''){
				$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');
				}
			}
			$this->form_validation->set_rules('idproofphoto','Id proof','file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
			$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
			$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
			
			/* Blended Validation */
			$this->form_validation->set_rules('training_type', 'Training Type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('program', 'Course', 'trim|required|xss_clean');
			$this->form_validation->set_rules('training_date', 'Training Date', 'trim|required|xss_clean');
			
			if($this->form_validation->run()==TRUE)
			{
				$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state=$outputphoto1=$outputsign1=$outputsign1=$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file='';
				
				
 				/* Get program_code,batch_code as per Exam Code */
				$exam_code = base64_decode($this->input->get('ExId'));
				$this->db->where('isActive','1');
				$this->db->where('exam_code', $exam_code);
				$exam_trg_exam_cd = $this->master_model->getRecords('exam_trg_exam_cd', '', 'program_code,batch_code');

				$program_code = $exam_trg_exam_cd[0]["program_code"];
				$batch_code = $exam_trg_exam_cd[0]["batch_code"];
				
				/* Set Default Values */
				$blended_center_name  = 'Mumbai';
				$blended_center_code = '306';
				$program_code = $program_code;
				$batch_code = $batch_code;
				$training_type = $_POST['training_type'];
				//echo $batch_code;
				
				/* Get Fees */
				$blended_fee_amount = 0;
				$this->db->where('fee_delete', 0);
				$this->db->where('program_code', $program_code);
				$this->db->where('batch_code', $batch_code);
				$this->db->where('training_type', $training_type);
				$FeesArr = $this->master_model->getRecords('blended_fee_master', '', 'fee_amount');
				foreach ($FeesArr as $Fkey => $FValue) {	
					if($FValue["fee_amount"] != '0'){
						$blended_fee_amount = $FValue["fee_amount"];
					}
					else{
						$blended_fee_amount = $FValue["fee_amount"];
					}
				}
				
				/* Get Program Name */
				$programArr = $centerArr = $venueArr = array();
				$programQry = $this->db->query("SELECT program_name FROM blended_program_master WHERE program_code = '" . $_POST['program'] . "' AND isdeleted = 0 LIMIT 1 ");
				$programArr   = $programQry->row_array();
				$program_name = $programArr['program_name'];
				
				/* Get Venue Details for Blended */
				$venueQry   = $this->db->query("SELECT venue_name,start_date,end_date,venue_code FROM blended_venue_master WHERE  program_code='" . $program_code . "' AND center_code='" . $blended_center_code . "' AND  batch_code='" . $batch_code . "' AND training_type ='" .$training_type . "'  AND isdeleted = 0 LIMIT 1");
				$venueArr           = $venueQry->row_array();
				$venue_code    = $venueArr['venue_code'];
				$venue_name    = $venueArr['venue_name'];
				$start_date    = $venueArr['start_date'];
				$end_date      = $venueArr['end_date'];
				
				
				
				$enduserinfo = $this->session->userdata('enduserinfo');
				if(count($enduserinfo)){
					$this->session->unset_userdata('enduserinfo');
				}
				
				$eduqual1=$eduqual2=$eduqual3='';
				if($_POST['optedu']=='U'){
					$eduqual1=$_POST["eduqual1"];
				}
				else if($_POST['optedu']=='G'){
					$eduqual2=$_POST["eduqual2"];
				}
				else if($_POST['optedu']=='P'){
					$eduqual3=$_POST["eduqual3"];
				}
				
				$date=date('Y-m-d h:i:s');
				//Generate dynamic photo
				
				$input = $_POST["hiddenphoto"];
				if(isset($_FILES['scannedphoto']['name']) &&($_FILES['scannedphoto']['name']!=''))
				{
					$img = "scannedphoto";
					$tmp_nm = strtotime($date).rand(0,100);
					$new_filename = 'non_mem_photo_'.$tmp_nm;
					$config=array('upload_path'=>'./uploads/photograph',
								  'allowed_types'=>'jpg|jpeg',
								  'file_name'=>$new_filename,);
							  
					$this->upload->initialize($config);
					$size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
					if($size)
					{
						if($this->upload->do_upload($img)){
							 $dt=$this->upload->data();
							 $file=$dt['file_name'];
							 $scannedphoto_file = $dt['file_name'];
							 $outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
						}
						else{
							$this->session->set_flashdata('error','Scanned Photograph :'.$this->upload->display_errors());
						}
					}
					else
					{
						$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');
					}
					
				}

				// generate dynamic scan signature
				$inputsignature = $_POST["hiddenscansignature"];
				if(isset($_FILES['scannedsignaturephoto']['name']) &&($_FILES['scannedsignaturephoto']['name']!=''))
				{
					$img = "scannedsignaturephoto";
					$tmp_signnm = strtotime($date).rand(0,100);
					$sign_new_filename = 'non_mem_sign_'.$tmp_signnm;
					$config=array('upload_path'=>'./uploads/scansignature',
										   'allowed_types'=>'jpg|jpeg',
										  'file_name'=>$sign_new_filename,);
							  
					$this->upload->initialize($config);
					$size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
					if($size){
						if($this->upload->do_upload($img)){
							  $dt=$this->upload->data();
							 $scannedsignaturephoto_file=$dt['file_name'];
							 $outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
						}
						else{
							$this->session->set_flashdata('error','Scanned Signature :'.$this->upload->display_errors());
								
						}
					}
					else{
						$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');
					}
				}
				
				// generate dynamic id proof
				$inputidproofphoto = $_POST["hiddenidproofphoto"];
				if(isset($_FILES['idproofphoto']['name']) &&($_FILES['idproofphoto']['name']!=''))
				{
						$img = "idproofphoto";
						$tmp_inputidproof = strtotime($date).rand(0,100);
						$new_filename = 'non_mem_idproof_'.$tmp_inputidproof;
						$config=array('upload_path'=>'./uploads/idproof',
								  'allowed_types'=>'jpg|jpeg',
								  'file_name'=>$new_filename,);
								  
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
						if($size)
						{
							if($this->upload->do_upload($img)){
								  $dt=$this->upload->data();
								  $idproof_file=$dt['file_name'];
								  $outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
							}
							else{
								$this->session->set_flashdata('error','Id proof :'.$this->upload->display_errors());
									
							}
						}
						else
						{
							$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');
							
						}
				}
				
				$dob1= $_POST["dob1"];
				$dob = str_replace('/','-',$dob1);
				$dateOfBirth = date('Y-m-d',strtotime($dob));

				if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
				{
					$user_data=array('firstname' => $_POST["firstname"],
									'sel_namesub' => $_POST["sel_namesub"],
									'addressline1' => $_POST["addressline1"],
									'addressline2' => $_POST["addressline2"],
									'addressline3' => $_POST["addressline3"],
									'addressline4' => $_POST["addressline4"],
									'city'	=>$_POST["city"],	
									'code'	=>trim($_POST["code"]),
									'district'	=>$_POST["district"],	
									'dob' =>$dateOfBirth,
									'eduqual'=>$_POST["eduqual"],	
									'eduqual1' => $eduqual1,	
									'eduqual2' => $eduqual2,	
									'eduqual3' => $eduqual3,
									'institution'=>trim($_POST["institutionworking"]),
									'designation'=>$_POST["designation"],
									'email'	 => $_POST["email"],	
									'gender' => $_POST["gender"],	
									'idNo'	 => $_POST["idNo"],	
									'idproof' => $_POST["idproof"],	
									'lastname' => $_POST["lastname"],	
									'middlename' => $_POST["middlename"],	
									'mobile'	 => $_POST["mobile"],	
									'optedu' => $_POST["optedu"],	
									'optnletter' => $_POST["optnletter"],	
									'phone'	 => $_POST["phone"],	
									'pincode' => $_POST["pincode"],	
									'state'	 => $_POST["state"],	
									'stdcode' => $_POST["stdcode"],
									'scannedphoto' => $outputphoto1,
									'scannedsignaturephoto'=>$outputsign1,
									'idproofphoto' => $outputidproof1,
									'photoname' => $scannedphoto_file,
									'signname' => $scannedsignaturephoto_file,
									'idname' => $idproof_file,
									'selCenterName'	=>'Mumbai',
									'txtCenterCode' => '306',
									'optmode' => 'ON',
									'exid'	 => $_POST["examcode"],
									'mtype'	 => 'NM',
									'memtype' => 'NM',
									'eprid'	 => $_POST["eprid"],
									'excd'	 => $_POST["examcode"],
									'exname' => $_POST["exname"],
									'fee' => '0',
									'medium' => 'E',
									'aadhar_card' => $_POST['aadhar_card'],
									'grp_code' => 'B1_1',
									'scribe_flag'=>'N',
									'program_code' => $program_code,
									'program_name' => $program_name,
									'batch_code' => $batch_code,
									'training_type' => $training_type,
									'blended_center_code' => $blended_center_code,
									'blended_center_name' => $blended_center_name,
									'venue_code' => $venue_code,
									'venue_name' => $venue_name,
									'start_date' => $start_date,
									'end_date' => $end_date,
									'blended_fee_amount' => $blended_fee_amount);
									
									//echo "<pre>";
									//print_r($user_data);
									
					$this->session->set_userdata('enduserinfo',$user_data);
					redirect(base_url().'Examtraining/preview');
				}
			}
	 	}
	
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$states=$this->master_model->getRecords('state_master');
		
		$this->db->not_like('name','Declaration Form');
		$this->db->not_like('name','college');
		$this->db->not_like('name','Aadhaar id');
		$this->db->not_like('name','Election Voters card');
		$idtype_master=$this->master_model->getRecords('idtype_master');
	
		$this->db->select('exam_master.*,misc_master.*');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period');//added on 5/6/2017
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where('exam_master.exam_code',$ExId);
		$examinfo = $this->master_model->getRecords('exam_master');
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_master.exam_code',$ExId);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master');
	
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where("center_delete",'0');
		$this->db->where('exam_name',$ExId);
		$this->db->group_by('center_master.center_name');
		$center=$this->master_model->getRecords('center_master');
		
		if(!count($examinfo) > 0 || !count($medium) > 0 ||  !count($center) > 0 ){
			$flag=0;
		}
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemlogincaptcha");
		$this->session->set_userdata("nonmemlogincaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$_SESSION["nonmemlogincaptcha"] = $cap['word']; 
		
		if($flag==1){
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=subject_master.exam_code AND exam_activation_master.exam_period=subject_master.exam_period');
			$compulsory_subjects=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$ExId,'subject_delete'=>'0','group_code'=>'C'),'',array('subject_code'=>'ASC'));
		
			/* Get program_code,batch_code as per Exam Code */
			$this->db->where('isActive', '1');
			$this->db->where('exam_code', $ExId);
			$exam_trg_exam_cd = $this->master_model->getRecords('exam_trg_exam_cd', '', 'program_code,batch_code');
			$program_code = $exam_trg_exam_cd[0]["program_code"];
			$batch_code = $exam_trg_exam_cd[0]["batch_code"];
				
			/* Get Program Show/Hide from blended table */
			$current_date  = date("Y-m-d H:i:s");
			$this->db->join('blended_program_activation_master', 'blended_program_activation_master.program_code=blended_program_master.program_code','left');
			$this->db->where('program_reg_from_date <=', $current_date);
			$this->db->where('program_reg_to_date >=', $current_date);
			$this->db->where('blended_program_master.isdeleted', 0);
			$this->db->where('blended_program_master.program_code', $program_code);
			$this->db->group_by('blended_program_master.program_code');
			$program = $this->master_model->getRecords('blended_program_master');
			
			/* institution master */
			$instiarray=array(21,30,31,57,160,171,179,192,397,628,725,755,774,968,1010,1012,1454,1484,1665,27518,620,627,668,911,764,793,898,939,946,1449,1456,1458,1459,1460,1464,1465,1469,1470,1471,1472,1476,1487,1490,1491,1497,1506,1511,1513,1522,1525,1526,1527,1528,1530,1538,1539,1540,1541,1549,1567,1570,1571,1573,1574,1575,1576,1581,1584,1587,1589,1591,1592,1593,1594,1598,1602,1607,1608,1609,1612,1616,1617,1620,1625,1626,1627,1628,1629,1630,1635,1643,1644,1646,1648);
			$this->db->where_not_in('institude_id', $instiarray);
			$this->db->where('institution_master.institution_delete','0');
			$institution_master = $this->master_model->getRecords('institution_master','','',array('name'=>'asc'));
		
			/* Designation */
			$this->db->where('designation_master.designation_delete','0');
			$designation=$this->master_model->getRecords('designation_master');
		
						
			$data=array('middle_content'=>'examtraining/non_mem_reg','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'compulsory_subjects'=>$compulsory_subjects,'program'=>$program ,'institution_master'=>$institution_master,'designation'=>$designation);

			$this->load->view('examtraining/common_view_fullwidth',$data);
		}
		else{
			echo "Link Close";exit;
			$this->load->view('access_denied',$data);
		}
	}
	
	//call back for e-mail duplication
	 public function check_emailduplication($email)
	{
		if($email!="")
		{
			$where="(registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			if($prev_count==0)
			{	
				return true;
			}
			else
			{
				$str='The entered email ID already exist';
				$this->form_validation->set_message('check_emailduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
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
	
	//call back for mobile duplication
	public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
			$where="(registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				return true;
				}
			else
			{
				$str='The entered Mobile no already exist';
				$this->form_validation->set_message('check_mobileduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	//check aadhar card
	public function check_aadhar($aadhar_card)
	{
		
		if($aadhar_card!="")
		{
			$where="registrationtype='NM'";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('aadhar_card'=>$aadhar_card,'isactive'=>'1'));
			//echo $this->db->last_query();
			//exit;
			if($prev_count==0)
			{
				return true;
			}
			else
			{
				$str='The entered Aadhar card number already exist';
				$this->form_validation->set_message('check_aadhar', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	//callback to validate photo
	function scannedphoto_upload(){
	      if($_FILES['scannedphoto']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('scannedphoto_upload', "No Scanned Photograph file selected");
	        return false;
	    }
	}
	
	//callback to validate scannedsignaturephoto
	function scannedsignaturephoto_upload(){
	      if($_FILES['scannedsignaturephoto']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('scannedsignaturephoto_upload', "No  Scanned Signature file selected");
	        return false;
	    }
	}
	
	//callback to validate idproofphoto
	function idproofphoto_upload(){
	      if($_FILES['idproofphoto']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('idproofphoto_upload', "No Id proof file selected");
	        return false;
	    }
	}	
	
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
	{
		if(isset($code))
		{
			if($code == '' || $_SESSION["nonmemlogincaptcha"] != $code )
			{
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
				//$this->session->set_userdata("regcaptcha", rand(1,100000));
				return false;
			}
			if($_SESSION["nonmemlogincaptcha"] == $code)
			{
				return true;
			}
		}
		else
		{
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
				//$this->session->set_userdata("regcaptcha", rand(1,100000));
				return false;
		}
	}
	
	//function to add membr in registration table and member_exam table
	public function addmember()
	{
		$flag=1;
		$Mtype = base64_decode($this->input->get('Mtype'));
		$ExId = base64_decode($this->input->get('ExId'));
		$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = '';
		$data['validation_errors'] = '';
		
		//check email,mobile duplication on the same time from different browser!!
		$endTime = date("H:i:s");
		$start_time= date("H:i:s",strtotime("-20 minutes",strtotime($endTime)));
		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');
		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);
		$this->db->or_where('mobile',$this->session->userdata['enduserinfo']['mobile']);
		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));
		if(count($check_duplication) > 0){
			redirect(base_url().'Examtraining/accessdenied/');
		}
	 	$password=$this->generate_random_password();
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$encPass = $aes->encrypt($password);
		//echo $this->session->userdata('uniqueString');exit;
		//vdebug($_POST);exit;
		
		if(isset($_POST['btnSubmit']))  	
		{
			$scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];
			$scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];
			$idproofphoto_file = $this->session->userdata['enduserinfo']['idname'];
			$sel_namesub = strtoupper($this->session->userdata['enduserinfo']['sel_namesub']);
			$firstname = strtoupper($this->session->userdata['enduserinfo']['firstname']);
			$middlename = strtoupper($this->session->userdata['enduserinfo']['middlename']);
			$lastname = strtoupper($this->session->userdata['enduserinfo']['lastname']);
			$addressline1= strtoupper($this->session->userdata['enduserinfo']['addressline1']);
			$addressline2 = strtoupper($this->session->userdata['enduserinfo']['addressline2']);
			$addressline3 = strtoupper($this->session->userdata['enduserinfo']['addressline3']);
			$addressline4 = strtoupper($this->session->userdata['enduserinfo']['addressline4']);
			$district= strtoupper($this->session->userdata['enduserinfo']['district']);
			$nationality = strtoupper($this->session->userdata['enduserinfo']['city']);
			$state = $this->session->userdata['enduserinfo']['state'];
			$pincode= $this->session->userdata['enduserinfo']['pincode'];
			$dob= $this->session->userdata['enduserinfo']['dob'];
			$gender = $this->session->userdata['enduserinfo']['gender'];
			$optedu= $this->session->userdata['enduserinfo']['optedu'];
			if($optedu=='U'){
				$specify_qualification=$this->session->userdata['enduserinfo']['eduqual1'];
			}
			elseif($optedu=='G')
			{
				$specify_qualification=$this->session->userdata['enduserinfo']['eduqual2'];
			}
			else if($optedu=='P')
			{
				$specify_qualification=$this->session->userdata['enduserinfo']['eduqual3'];
			}
			$institutionworking = $this->session->userdata['enduserinfo']['institution'];
			$designation = $this->session->userdata['enduserinfo']['designation'];
			$email = $this->session->userdata['enduserinfo']['email'];
			$stdcode =$this->session->userdata['enduserinfo']['stdcode'];
			$phone = $this->session->userdata['enduserinfo']['phone'];
			$mobile = $this->session->userdata['enduserinfo']['mobile'];
			$idproof = $this->session->userdata['enduserinfo']['idproof'];
			$idNo = $this->session->userdata['enduserinfo']['idNo'];
			$aadhar_card = $this->session->userdata['enduserinfo']['aadhar_card'];
			$optnletter = $this->session->userdata['enduserinfo']['optnletter'];
			$centerid=$this->session->userdata['enduserinfo']['selCenterName'];
			$centercode=$this->session->userdata['enduserinfo']['txtCenterCode'];
			$exmode=$this->session->userdata['enduserinfo']['optmode'];
			//$declaration1 = $_POST['declaration1'];
			
			/* Blended session */
			$program_code = $this->session->userdata['enduserinfo']['program_code'];
			$program_name = $this->session->userdata['enduserinfo']['program_name'];
			$training_type = $this->session->userdata['enduserinfo']['training_type'];
			$blended_center_code = $this->session->userdata['enduserinfo']['blended_center_code'];
			$blended_center_name = $this->session->userdata['enduserinfo']['blended_center_name'];
			$batch_code = $this->session->userdata['enduserinfo']['batch_code'];
			$venue_code = $this->session->userdata['enduserinfo']['venue_code'];
			$venue_name = $this->session->userdata['enduserinfo']['venue_name'];
			$start_date = $this->session->userdata['enduserinfo']['start_date'];
			$end_date = $this->session->userdata['enduserinfo']['end_date'];
			$blended_fee_amount = $this->session->userdata['enduserinfo']['blended_fee_amount'];
			$examTrg_flag  = 'EXTRG'; 
			
			$insert_info = array(
								'usrpassword'=>$encPass,
								'namesub' => $sel_namesub,
								'firstname'=>$firstname,
								'middlename'=>$middlename,
								'lastname'=>$lastname,
								'address1'=>$addressline1,
								'address2'=>$addressline2,
								'address3'=>$addressline3,
								'address4'=>$addressline4,
								'district'=>$district,
								'city'=>$nationality,
								'state'=>$state,
								'pincode'=>$pincode,
								'dateofbirth'=>$dob,
								'gender'=>$gender,
								'qualification'=>$optedu,
								'specify_qualification'=>$specify_qualification,
								'associatedinstitute'=>$institutionworking,
								'designation'=>$designation,
								'email'=>$email,
								'registrationtype'=>'NM',
								'stdcode'=>$stdcode,
								'office_phone'=>$phone,
								'mobile'=>$mobile,
								'scannedphoto'=>$scannedphoto_file,
								'scannedsignaturephoto'=>$scannedsignaturephoto_file,	
								'idproof'=>$idproof,
								'idNo'=>$idNo,
								'optnletter'=>'N',
								'declaration'=>'1',
								'idproofphoto'=>$idproofphoto_file,
								'excode'=>$this->session->userdata['enduserinfo']['excd'],
								'fee'=>$this->session->userdata['enduserinfo']['fee'],
								'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
								'centerid'=>$centerid,
								'centercode'=>$centercode,
								'exmode'=>$exmode,
								'aadhar_card'=>$aadhar_card,
								'createdon'=>date('Y-m-d H:i:s')
							);	
							
					
									
			
			if($last_id =$this->master_model->insertRecord('member_registration',$insert_info,true))
			{
				$log_title ="Non member Traning Insert Array :".$last_id;
				$log_message = serialize($insert_info);
				$rId = $last_id;
				$regNo = $last_id;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				/* Blended Insert Array */
				$blended_insert_info = array(
					'namesub' => $sel_namesub,
					'firstname' => $firstname,
					'middlename' => $middlename,
					'lastname' => $lastname,
					'address1' => $addressline1,
					'address2' => $addressline2,
					'address3' => $addressline3,
					'address4' => $addressline4,
					'district' => $district,
					'city' => $nationality,
					'state' => $state,
					'pincode' => $pincode,
					'dateofbirth' => date('Y-m-d', strtotime($dob)),
					'qualification' => $optedu,
					'specify_qualification' => $specify_qualification,
					'associatedinstitute' => $institutionworking,
					'designation' => $designation,
					'email' => $email,
					'stdcode' => $stdcode,
					'office_phone' => $phone,
					'mobile' => $mobile,
					'zone_code' => 'CO',
					'fee' => $blended_fee_amount,
					'createdon' => date('Y-m-d H:i:s'),
					'program_code' => $program_code,
					'program_name' => $program_name,
					'batch_code' => $batch_code,
					'training_type' => $training_type,
					'center_code' => $blended_center_code,
					'center_name' => $blended_center_name,
					'venue_name' => $venue_name,
					'venue_code' => $venue_code,
					'start_date' => $start_date,
					'end_date' => $end_date,
					'attempt' => 0,
					'application_flag' => $examTrg_flag
				 );
				//echo "<pre>"; print_r($blended_insert_info); echo "</pre>";exit;
				/* Insert Non-Member Details in - blended_registration table */		
				
        		$blended_last_id = $this->master_model->insertRecord('blended_registration', $blended_insert_info,true); 
				//echo $this->db->last_query(); die;
				$log_title ="Non member Traning Blended Insert Array :".$blended_last_id;
				$log_message = serialize($blended_insert_info);
				$rId = $blended_last_id;
				$regNo = $blended_last_id;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				logactivity($log_title ="Non-Member Traning user registration ", $log_message = serialize($insert_info));
				$amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM');
				
				$inser_exam_array=array('regnumber'=>$last_id,
										'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
										'exam_mode'=>$this->session->userdata['enduserinfo']['optmode'],
										'exam_medium'=>$this->session->userdata['enduserinfo']['medium'],
										'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
										'exam_center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],
										'exam_fee'=>$amount,
										'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
										'created_on'=>date('y-m-d H:i:s')
										);
										
				if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_exam_array,true))
				{
				
					// Renaming the previously uploaded file with Reg Num inserted in database
					logactivity($log_title ="Exam Traning applied During Non-Member user registration ", $log_message = serialize($inser_exam_array));
				
					$exam_name_desc=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_delete'=>'0'),'description');
				
					$userarr=array('regno'=>$last_id,
									'password'=>$password,
									'email'=>$email,
									'exam_fee'=>$this->session->userdata['enduserinfo']['fee'],
									'exam_desc'=>$exam_name_desc[0]['description'],
									'excode'=>$this->session->userdata['enduserinfo']['excd'],
									'member_exam_id'=>$exam_last_id);
					$this->session->set_userdata('non_memberdata', $userarr); 
					
					/* Gen. Non-Member Number*/
					$applicationNo = generate_NM_memreg($last_id);
	
					/* Update Non-Member Number - member_registration */
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$last_id));
							
					/* Update Non-Member Number - member_exam */		
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$last_id));	
					
					/* Update Non-Member Number - blended_registration */
					$blended_update_data=array('pay_status'=>'1','member_no'=>$applicationNo,'modify_date'=>date('y-m-d H:i:s'));
					$this->master_model->updateRecord('blended_registration',$blended_update_data,array('blended_id'=>$blended_last_id));	
					
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$last_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
					
					########get Old image Name############
					$log_title ="Non member OLD Image :".$last_id;
					$log_message = serialize($result);
					$rId = $last_id;
					$regNo = $last_id;
					storedUserActivity($log_title, $log_message, $rId, $regNo);
						
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					
					if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0){
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$last_id));
						$log_title ="Non member PICS Update :".$last_id;
						$log_message = serialize($upd_files);
						$rId = $last_id;
						$regNo = $last_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					else{
						$upd_files['scannedphoto'] = $photo_file;
						$upd_files['scannedsignaturephoto'] = $sign_file;	
						$upd_files['idproofphoto'] = $proof_file;
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$last_id));
						$log_title ="Non member PICS MANUAL PICS Update :".$last_id;
						$log_message = serialize($upd_files);
						$rId = $last_id;
						$regNo = $last_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					
					/* Email Sending */
					/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_mem_trg_email'));
					if(count($emailerstr) > 0)
					{  
						
						$final_str = $emailerstr[0]['emailer_text'];
						$info_arr = array('to'=>$email,
						'from'=>$emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str);
					}
					$this->Emailsending->mailsend($info_arr);*/

					redirect(base_url().'Examtraining/acknowledge/');
					/*if($this->config->item('exam_apply_gateway')=='sbi'){
						redirect(base_url().'Examtraining/sbi_make_payment/');
					}
					else{
							 redirect(base_url()."Examtraining/make_payment");
					}*/
				}
				else{
					$userarr=array('application_number'=>'','password'=>'','email'=>'');
					$this->session->set_userdata('memberdata', $userarr); 
					return false;
				}
			}
			else{
				$userarr=array('regno'=>'','password'=>'','email'=>'');
				$this->session->set_userdata('non_memberdata', $userarr); 
				$this->session->set_flashdata('error','Error while during registration.please try again!');
				redirect(base_url());
			}
		 }
	}
	
	
	
	##------------------Exam appky with SBI Payment Gate-way(PRAFULL)---------------##
	public function sbi_make_payment()
	{
		////check temp file uploaded or not////
		$images_flag=0;
		if(!file_exists("uploads/photograph/".$this->session->userdata['enduserinfo']['photoname']))
		{
			$images_flag=1;
		}
		if(!file_exists("uploads/scansignature/".$this->session->userdata['enduserinfo']['signname']))
		{
			$images_flag=1;
		}
		if(!file_exists("uploads/idproof/".$this->session->userdata['enduserinfo']['idname']))
		{
			$images_flag=1;
		}
		if($images_flag)
		{
			$this->session->set_flashdata('error','Please upload valid image(s)');
			redirect(base_url().'Examtraining/member/?Mtype='.base64_encode($this->session->userdata['enduserinfo']['mtype']).'=&ExId='.base64_encode($this->session->userdata['enduserinfo']['excd']).'');
		}
		
		//check email,mobile duplication on the same time from different browser!!
		$update_data = array('createdon' => date('Y-m-d H:i:s'));
		$this->master_model->updateRecord('member_registration',$update_data,array('regid'=>$this->session->userdata['non_memberdata']['regno']));
		$endTime = date("H:i:s");
		$start_time= date("H:i:s",strtotime("-20 minutes",strtotime($endTime)));
		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');
		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);
		$this->db->or_where('mobile',$this->session->userdata['enduserinfo']['mobile']);
		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));
		
		
		if(count($check_duplication) > 1)
		{
			redirect(base_url().'Examtraining/accessdenied/');
		}
		
		
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
			redirect('http://iibf.org.in/');
		}
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$regno = $this->session->userdata['non_memberdata']['regno'];
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$exam_desc= $this->session->userdata['non_memberdata']['exam_desc'];
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Examtraining/sbitranssuccess";
			$pg_fail_url    = base_url()."Examtraining/sbitransfail";
			
			if($this->config->item('sb_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				$amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM');
				//$amount=$this->session->userdata['enduserinfo']['fee'];
			}
			
			if($amount==0 || $amount=='')
			{
				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
				redirect(base_url().'Examtraining/preview/');
			}
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");

			//With Registration Non-member
			//Non memeber / DBF Apply exam
			//Ref1 = orderid
			//Ref2 = iibfexam
			//Ref3 = orderid
			//Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid']),'exam_month');
			
			if($this->session->userdata['enduserinfo']['excd']==340 || $this->session->userdata['enduserinfo']['excd']==3400)
			{
				$exam_code=34;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==580 || $this->session->userdata['enduserinfo']['excd']==5800)
			{
				$exam_code=58;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==1600 || $this->session->userdata['enduserinfo']['excd']==16000)
			{
				$exam_code=160;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==200)
			{
				$exam_code=20;
			}else if($this->session->userdata['enduserinfo']['excd']==1770 || $this->session->userdata['enduserinfo']['excd']==17700)
			{
				$exam_code=177;
			}
			else if($this->session->userdata['enduserinfo']['excd']==1750)
			{
				$exam_code=175;
			}
			else
			{
				$exam_code=$this->session->userdata['enduserinfo']['excd'];
			}
			$ref4=$exam_code.$yearmonth[0]['exam_month'];
			
			 
			// Create transaction
			$member_exam_id=$this->session->userdata['non_memberdata']['member_exam_id'];
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $member_exam_id,
				'description'      => $exam_desc,
				'status'           => '2',
				'exam_code'        => $this->session->userdata['enduserinfo']['excd'],
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>'IIBF_EXAM_REG',
				//'pg_other_details'=>$custom_field
			);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$MerchantOrderNo."^".$ref4;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			//set invoice details(Prafull)
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd'],'center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'center_delete'=>'0'));
			if(count($getcenter) > 0)
			{
				//get state code,state name,state number.
				$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				
				//call to helper (fee_helper)
				$getfees=getExamFeedetails($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],$this->session->userdata['enduserinfo']['memtype']);
			}
		
			if($getcenter[0]['state_code']=='MAH')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$this->config->item('cgst_rate');
				$sgst_rate=$this->config->item('sgst_rate');
				//set an amount as per rate
				$cgst_amt=$getfees[0]['cgst_amt'];
				$sgst_amt=$getfees[0]['sgst_amt'];
				 //set an total amount
				$cs_total=$getfees[0]['cs_tot'];
				
				$tax_type='Intra';
			
			}
			/*else if($getcenter[0]['state_code']=='JAM')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$sgst_rate=$igst_rate='';	
				$cgst_amt=$sgst_amt=$igst_amt='';	
				$igst_total=$getfees[0]['fee_amount']; 
				$tax_type='Inter';
			}*/
			else
			{
				$igst_rate=$this->config->item('igst_rate');
				$igst_amt=$getfees[0]['igst_amt'];
				$igst_total=$getfees[0]['igst_tot']; 
				$tax_type='Inter';
			}
			if($getstate[0]['exempt']=='E')
			{
				 $cgst_rate=$sgst_rate=$igst_rate='';	
				 $cgst_amt=$sgst_amt=$igst_amt='';	
			}
				
			$invoice_insert_array=array('pay_txn_id'=>$pt_id,
														'receipt_no'=>$MerchantOrderNo,
														'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
														'center_code'=>$getcenter[0]['center_code'],
														'center_name'=>$getcenter[0]['center_name'],
														'state_of_center'=>$getcenter[0]['state_code'],
														'member_no'=>$regno,
														'app_type'=>'O',
														'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
														'service_code'=>$this->config->item('exam_service_code'),
														'qty'=>'1',
														'state_code'=>$getstate[0]['state_no'],
														'state_name'=>$getstate[0]['state_name'],
														'tax_type'=>$tax_type,
														'fee_amt'=>$getfees[0]['fee_amount'],
														'cgst_rate'=>$cgst_rate,
														'cgst_amt'=>$cgst_amt,
														'sgst_rate'=>$sgst_rate,
														'sgst_amt'=>$sgst_amt,
														'igst_rate'=>$igst_rate,
														'igst_amt'=>$igst_amt,
														'cs_total'=>$cs_total,
														'igst_total'=>$igst_total,
														'exempt'=>$getstate[0]['exempt'],
														'created_on'=>date('Y-m-d H:i:s'));
								
			$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);
			
			$log_title ="Non member exam invoice insertion :".$inser_id;
			$log_message = serialize($invoice_insert_array);
			$rId = $inser_id;
			$regNo = $inser_id;
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			
			//if exam invocie entry skip
			if($inser_id==''){
				$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);
				
				$log_title ="Non member exam invoice insertion again :".$inser_id;
				$log_message = serialize($invoice_insert_array);
				$rId = $inser_id;
				$regNo = $inser_id;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
			}
			
			
			$log_title = "Exam invoice data from Nonreg cntrlr inser_id = '".$inser_id."'";
			$log_message = serialize($invoice_insert_array);
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			//insert into admit card table
			//################get userdata###########
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$regno));
			//get associate institute details
			$institute_id='';
			$institution_name='';
			if($user_info[0]['associatedinstitute']!='')
			{
				$institution_master=$this->master_model->getRecords('institution_master',array('institude_id'=>$user_info[0]['associatedinstitute']));
				if(count($institution_master) >0)
				{
					$institute_id=$institution_master[0]['institude_id'];
					$institution_name=$institution_master[0]['name'];
				}
			}
		 //############check Gender########
			if($user_info[0]['gender']=='male')
			{$gender='M';}
			else
			{$gender='F';}
			//########prepare user name########
			$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			
			//###########get State##########
			$states=$this->master_model->getRecords('state_master',array('state_code'=>$user_info[0]['state'],'state_delete'=>'0'));
			$state_name='';
			if(count($states) >0)
			{
				$state_name=$states[0]['state_name'];
			}		
			//##############Examination Mode###########
			if($this->session->userdata['enduserinfo']['optmode']=='ON')
			{
				$mode='Online';
			}
			else
			{
				$mode='Offline';
			}	
			
			if(!empty($this->session->userdata['enduserinfo']['subject_arr']))
			{
					foreach($this->session->userdata['enduserinfo']['subject_arr'] as $k=>$v)
					{
							$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'subject_delete'=>'0','group_code'=>'C','exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'subject_code'=>$k),'subject_description');
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['enduserinfo']['selCenterName']));
						
						$admitcard_insert_array=array('mem_exam_id'=>$member_exam_id,
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'mem_type'=>$this->session->userdata['enduserinfo']['memtype'],
													'mem_mem_no'=>$regno,
													'g_1'=>$gender,
													'mam_nam_1'=>$userfinalstrname,
													'mem_adr_1'=>$user_info[0]['address1'],
													'mem_adr_2'=>$user_info[0]['address2'],
													'mem_adr_3'=>$user_info[0]['address3'],
													'mem_adr_4'=>$user_info[0]['address4'],
													'mem_adr_5'=>$user_info[0]['district'],
													'mem_adr_6'=>$user_info[0]['city'],
													'mem_pin_cd'=>$user_info[0]['pincode'],
													'state'=>$state_name,
													'exm_cd'=>$this->session->userdata['enduserinfo']['excd'],
													'exm_prd'=>$this->session->userdata['enduserinfo']['eprid'],
													'sub_cd '=>$k,
													'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
													'm_1'=>$this->session->userdata['enduserinfo']['medium'],
													'inscd'=>$institute_id,
													'insname'=>$institution_name,
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
													'mode'=>$mode,
													'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
													'vendor_code'=>$get_subject_details[0]['vendor_code'],
													'remark'=>2,
													'created_on'=>date('Y-m-d H:i:s'));
						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
						
						$log_title ="Non member admit card detail insertion :".$inser_id;
						$log_message = serialize($admitcard_insert_array);
						$rId = $inser_id;
						$regNo = $inser_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
				}
			else
			{
				if($this->session->userdata['enduserinfo']['excd']!=101)
				{
					$this->session->set_flashdata('Error','Something went wrong!!');
					redirect(base_url().'Examtraining/preview/');
				}
			}
			//set cookie for Apply Exam
			applyexam_set_cookie($regno);
			$MerchantCustomerID = $regno;

			
			$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
			$data["merchIdVal"]  = $merchIdVal;
			
		

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
	
	public function sbitranssuccess()
	{
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$encData = $aes->decrypt($_REQUEST['encData']);
		$responsedata = explode("|",$encData);
		$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
		$transaction_no  = $responsedata[1];
		$attachpath=$invoiceNumber=$admitcard_pdf='';
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
		
		//SBI CALLBACK B2B
		// Handle transaction success case 
		$q_details = sbiqueryapi($MerchantOrderNo);
		if ($q_details)
		{
			if ($q_details[2] == "SUCCESS")
			{
			 $get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
			if($get_user_regnum[0]['status']==2)
			{
				######### payment Transaction ############
				$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
				$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
				if($this->db->affected_rows())
				{
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id=$get_user_regnum[0]['member_regnumber'];
					############check capacity is full or not ##########
				
					########## Generate Admit card and allocate Seat #############
					if($exam_code!=101)
					{
						$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
						//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
						if(count($exam_admicard_details) > 0)
						{		
						$msg='';
						$sub_flag=1;
						$sub_capacity=1;
						foreach($exam_admicard_details as $row)
						{
							$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
							if($capacity==0)
							{
								#########get message if capacity is full##########
								/*Add code trans_start & trans_complete : pooja  */
								$this->db->trans_start(); 
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$this->db->trans_complete();
								
								$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								redirect(base_url().'Examtraining/refund/'.base64_encode($MerchantOrderNo));
							}
						}
					}
					}
					
					//$applicationNo = generate_nm_reg_num();
					$applicationNo = generate_NM_memreg($reg_id);
					 
					
					######### payment Transaction ############
					$this->db->trans_start(); 
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					$this->db->trans_complete();
	
					########## Update Member Registration#############
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
					
					##########Update Member Exam#############
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
					
					
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
					########## Generate Admit card and allocate Seat #############
					if($exam_code!='101')
					{
						if(count($exam_admicard_details) > 0)
						{
							$password=random_password();
							foreach($exam_admicard_details as $row)
							{
								$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'=>$row['center_code']));
								
								$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
								
								//echo $this->db->last_query().'<br>';
								$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
					
								if($seat_number!='')
								{
									$final_seat_number = $seat_number;
									$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
									$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
								}
								else
								{
									$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
									if(count($admit_card_details) > 0)
									{
										$log_title ="Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
										$log_message = serialize($exam_admicard_details);
										$rId = $admit_card_details[0]['admitcard_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);
									}
									else
									{
										$log_title ="Fail user seat allocation id:".$applicationNo;
										$log_message = serialize($this->session->userdata['enduserinfo']['subject_arr']);
										$rId = $applicationNo;
										$regNo = $applicationNo;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
									}
								}
							}
							##############Get Admit card#############
							$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
						}	
						else
						{
							redirect(base_url().'Examtraining/refund/'.base64_encode($MerchantOrderNo));
						}
					}
					//	echo $this->db->last_query();exit;
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					//Query to get Medium	
					$this->db->where('exam_code',$exam_code);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
				
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
					
					########get Old image Name############
					$log_title ="Non member OLD Image :".$reg_id;
					$log_message = serialize($result);
					$rId = $reg_id;
					$regNo = $reg_id;
					storedUserActivity($log_title, $log_message, $rId, $regNo);
						
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					
					if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0)
					{
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
						$log_title ="Non member PICS Update :".$reg_id;
						$log_message = serialize($upd_files);
						$rId = $reg_id;
						$regNo = $reg_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					else
					{
						$upd_files['scannedphoto'] = $photo_file;
						$upd_files['scannedsignaturephoto'] = $sign_file;	
						$upd_files['idproofphoto'] = $proof_file;
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
						$log_title ="Non member PICS MANUAL PICS Update :".$reg_id;
						$log_message = serialize($upd_files);
						$rId = $reg_id;
						$regNo = $reg_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$this->sms_template_id = 'P6tIFIwGR';
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
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
					$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
					$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
					if(count($elern_msg_string) > 0)
					{
						foreach($elern_msg_string as $row)
						{
							$arr_elern_msg_string[]=$row['exam_code'];
						}
						if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
						{
							$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
						}
						else
						{
							$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
						}
					}
					else
					{
						$newstring21 = str_replace("#E-MSG#", '',$newstring20);
					}
					
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
				
					$info_arr=array('to'=>'chaitali.jadhav@esds.co.in',//$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
										
				//get invoice	
				$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
				//echo $this->db->last_query();exit;
				if(count($getinvoice_number) > 0)
				{
					/*if($getinvoice_number[0]['state_of_center']=='JAM')
					{
						$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
						}
					}
					else
					{*/
						$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
						}
					//}
					$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				
					/*Add code trans_start & trans_complete : pooja  */
					$this->db->trans_start();
					$this->db->where('pay_txn_id',$payment_info[0]['id']);
					$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
					$this->db->trans_complete();
					
					$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
				}
				
						
				if($attachpath!='')
				{		
					//send sms	
					$files=array($attachpath,$admitcard_pdf);				
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);			
					$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);			
					//$this->Emailsending->mailsend($info_arr);
					$this->Emailsending->mailsend_attch($info_arr,$files);
				}//Manage Log
				}
				else
				{
					$log_title ="B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
					$log_message = serialize($update_data);
					$rId = $MerchantOrderNo;
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
				}
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
		}
	}
		//END OF SBI CALLBACK B2B
		redirect(base_url().'Examtraining/acknowledge/'.base64_encode($MerchantOrderNo));
		}
	
	public function sbitransfail()
	{
		//Delete cookie
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}//cookie deleted
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
			//SBI CALLBACK SUCCESS
			// Handle transaction fail case 
			$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,member_regnumber');
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
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' =>0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'B2B');
				$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');
				$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
				
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
				//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
				$this->sms_template_id = 'Jw6bOIQGg';
				$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
				$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
				$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
				
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				
				// send SMS
				$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
				$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
				$this->Emailsending->mailsend($info_arr);
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//END OF SBI CALLBACK SUCCESS
			///Old Code
			redirect(base_url());
		}
		else
		{
			die("Please try again...");
		}
	}
	
	
	public function getTrainingType()
	{ 
		$training_typeData = '';
		
		$program_code = $this->input->post('program_code'); 
		
		if($program_code != "")
		{ 
			$this->db->where('program_code', $program_code);
			$this->db->where('isdelete', 0);
			$DateArr = $this->master_model->getRecords('blended_dates', '', 'DISTINCT(training_type)');
			if(!empty($DateArr))
			{
				$training_typeData .= "<select class='form-control' id='training_type' name='training_type' onchange='getDates(this.value);' required>";
				$training_typeData .= "<option value=''>- Select Training Type -</option>";
				foreach ($DateArr as $dkey => $dValue) 
				{
					$training_type   = $dValue['training_type'];
					if($training_type == 'PC')
					 { $training_type = 'Physical Classroom';}
					else
					{ $training_type = 'Virtual Classes';}
					
					$training_typeData .= "<option value=".$dValue['training_type'].">".$training_type."</option>";
				}
				$training_typeData .= "</select>";
				echo $training_typeData;
			}
		}
	}
	public function getDates()
	{
		$program_code = $this->input->post('program_code'); 
        $training_type  = $this->input->post('training_type'); 
		$trainingDateData = '';

		/* Check Program Activations */
		$current_date  = date("Y-m-d H:i:s");
		$programs = $batch_code = array();
		$this->db->where('program_code', $program_code);
		$this->db->where('program_reg_from_date <=', $current_date);
		$this->db->where('program_reg_to_date >=', $current_date);
		$this->db->where('program_activation_delete', 0);
		$programs = $this->master_model->getRecords('blended_program_activation_master','','batch_code');
	
		foreach ($programs as $Bkey => $BValue) {
		
			$batch_code[]=$BValue['batch_code'];
			//echo $BValue['batch_code']; 
		}
		//print_r($programs);
		/*$ExId = base64_decode($this->input->get('ExId'));
		
		if($ExId == 191)
		{
			$batch_code = 'PITC015';
		}
		elseif($ExId == 1910)
		{
			$batch_code = 'PITC011';
		}
		elseif($ExId == 19100)
		{
			$batch_code = 'PITC012';
		}*/

		/* Check Program Dates Activations */
		$this->db->where('program_code', $program_code);
		$this->db->where('training_type', $training_type);
		$this->db->where_in('batch_code', $batch_code);
		$this->db->where('isdelete', 0);
		$DateArr = $this->master_model->getRecords('blended_dates', '', 'start_date,end_date,batch_code,center_name');
		
	//echo $this->db->last_query();
	
		//print_r($DateArr);
		
		if(!empty($DateArr))
		{ 
			$trainingDateData .= "<select class='form-control' id='training_date' name='training_date' onchange='getFees();getCenters();' required>";
			$trainingDateData .= "<option value=''>- Select Training Date -</option>";
			foreach ($DateArr as $dkey => $dValue) 
			{
				$start_date = date("d-M-Y", strtotime($dValue['start_date']));
				$end_date   = date("d-M-Y", strtotime($dValue['end_date']));
				$batch_code   = $dValue['batch_code'];		
				$center_name   = $dValue['center_name'];				
				$trainingDateData .= "<option value=".$dValue['start_date']."~".$dValue['end_date']." data-id=".$batch_code.">".$start_date." To ".$end_date." ( ".$center_name." )</option>";
			}
			$trainingDateData .= "</select>";
			echo $trainingDateData;
		}
		else
		{
			echo $trainingDateData = "";
		}
	}
	public function getFees()
	{
		$fee_amount = 0;
		$program_code = $this->input->post('program_code'); 
        $training_type  = $this->input->post('training_type');
	
		$current_date  = date("Y-m-d H:i:s");
		$programs = $batch_code = array();
		$this->db->where('program_code', $program_code);
		$this->db->where('program_reg_from_date <=', $current_date);
		$this->db->where('program_reg_to_date >=', $current_date);
		$this->db->where('program_activation_delete', 0);
		$programs = $this->master_model->getRecords('blended_program_activation_master','','batch_code');
	
		foreach ($programs as $Bkey => $BValue) {
			$batch_code[]=$BValue['batch_code'];
		}
		/* Get Fees */
		$this->db->where('fee_delete', 0);
		$this->db->where('program_code', $program_code);
		$this->db->where_in('batch_code', $batch_code);
		$this->db->where('training_type', $training_type);
		$FeesArr = $this->master_model->getRecords('blended_fee_master', '', 'fee_amount');
		foreach ($FeesArr as $Fkey => $FValue) 
		{	
			if($FValue["fee_amount"] != '0'){
				$fee_amount = '<strong>'.$FValue["fee_amount"].' + GST as applicable</strong>';
			}
			else{
				$fee_amount = '<strong>'.$FValue['fee_amount'].'</strong>';
			}
			echo $fee_amount;
		}
	}
	
	
	/* Get Centers By Program Code */
    function getCenters()
    {
        $centerData = '';
		$total_reg = $centerCodeArr = $centerCodeAr = array();
        $program_code = $this->input->post('program_code');
		
		/* Check Program Activations */
		$current_date  = date("Y-m-d H:i:s");
		$programs = $batch_code = array();
		$this->db->where('program_code', $program_code);
		$this->db->where('program_reg_from_date <=', $current_date);
		$this->db->where('program_reg_to_date >=', $current_date);
		$this->db->where('program_activation_delete', 0);
		$programs = $this->master_model->getRecords('blended_program_activation_master','','batch_code');
		
		foreach ($programs as $Bkey => $BValue) {
			$batch_code[]=$BValue['batch_code'];
		}
		/*$ExId = base64_decode($this->input->get('ExId'));
		if($ExId == 191)
		{
			$batch_code = 'PITC015';
		}
		elseif($ExId == 1910)
		{
			$batch_code = 'PITC011';
		}
		elseif($ExId == 19100)
		{
			$batch_code = 'PITC012';
		}*/
		
		//echo $batch_code; die;
		//check capacity
		$this->db->where('pay_status', 1);
		$this->db->where('program_code', $program_code);
		$this->db->where_in('batch_code', $batch_code);
		$total_reg = $this->master_model->getRecords('blended_registration');
		//print_r($total_reg); die;
        if ($program_code) 
		{	
            $this->db->where('isdeleted', 0);
            $this->db->where('program_code', $program_code);
			$this->db->where_in('batch_code', $batch_code);
            $centerCodeArr = $this->master_model->getRecords('blended_venue_master', '', 'center_code,capacity');
			
			if(count($total_reg)>=$centerCodeArr[0]['capacity']){
				$centerData= 'Capacity Full';	
			}
			else{
            	foreach ($centerCodeArr as $codekey => $codeValue) {
                	$center_code[] = $codeValue['center_code'];
            	}
            
					if (!empty($center_code)) {
						$this->db->where('center_delete', 0);
						$this->db->group_by('center_code');
						$this->db->where_in('center_code', $center_code);
						$centerArr = $this->master_model->getRecords('offline_center_master', '', 'center_code,center_name');
						$centerData .= "<select class='form-control' id='center' name='center' onchange='javascript:getVenue(this.value);'>";
				$centerData .= "<option value=''>- Select Center -</option>";
						foreach ($centerArr as $ckey => $cValue) {
							$centerData .= "<option value=" . $cValue['center_code'] . ">" . $cValue['center_name'] . "</option>";
						}
						$centerData .= "</select>";
					}
			}
            echo $centerData;
        }
    }
	
	/* Get Venue Details By Center Code */
    function getVenue()
    {
		
        $venueData    = '';
		$program_code = $this->input->post('program_code');
        $center_code  = $this->input->post('center_code');
		$training_type  = $this->input->post('training_type');
		$batch_code  = $this->input->post('batch_code');
		
		//echo $batch_code; die;
        if ($program_code != '' && $center_code != '' && $batch_code != '') 
		{
            $this->db->where('isdeleted', 0);
            $this->db->where('program_code', $program_code);
            $this->db->where('center_code', $center_code);
			$this->db->where('batch_code', $batch_code);
			$this->db->where('training_type', $training_type);
			$centerArr = $this->master_model->getRecords('blended_venue_master', '', 'venue_code,venue_name,start_date,end_date,batch_code');
			foreach ($centerArr as $ckey => $cValue) 
			{
                $start_date = date("d-M-Y", strtotime($cValue['start_date']));
                $end_date   = date("d-M-Y", strtotime($cValue['end_date']));
                $venuecode  = $cValue['venue_code'];
				$batch_code  = $cValue['batch_code'];
                $venueData .= "<p><strong>" . $cValue['venue_name'] . "</strong>";
                $venueData .= "</p> ";
                $venueData .= "<input type='hidden' name='venue_code' id='venue_code' value='" . $venuecode . "'/>";
            }
			
            echo $venueData;
        }
    }
	
	
	//function for payment
	public function make_payment()
	{
			$regno = $this->session->userdata['non_memberdata']['regno'];
			$exam_desc= $this->session->userdata['non_memberdata']['exam_desc'];
			$MerchantID = $this->config->item('bd_MerchantID');
			$SecurityID = $this->config->item('bd_SecurityID');
			$checksum_key = $this->config->item('bd_ChecksumKey');
			$pg_return_url = base_url()."Nonreg/pg_response";
			$member_exam_id=$this->session->userdata['non_memberdata']['member_exam_id'];
			//$amount= $this->session->userdata['non_memberdata']['exam_fee'];
			$amount ='1';
			
			//$MerchantOrderNo = generate_order_id("bd_exam_order_id");
			
			// Create transaction
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "billdesk",
				'date'             => date('Y-m-d h:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $regno,
				'description'      => $exam_desc,
				'status'           => '2',
				'exam_code'        => $this->session->userdata['non_memberdata']['excode'],
				//'receipt_no'       => $MerchantOrderNo
			);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = bd_exam_order_id($pt_id);
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));

			$MerchantCustomerID = $regno;
			
			$custom_field = "iibfexam";

			$data["pg_form_url"] = $this->config->item('bd_pg_form_url'); // SBI ePay form URL

			/*
			Format:			requestparameter=MerchantID|CustomerID|NA|TxnAmount|NA|NA|NA|CurrencyType|NA|TypeField1|SecurityID|NA|NA|TypeField2|AdditionalInfo1|AdditionalInfo2|AdditionalInfo3|AdditionalInfo4|AdditionalInfo5|NA|NA|RU|Checksum

			Ex.	
requestparameter=IIBF|2138759|NA|500.00|NA|NA|NA|INR|NA|R|iibf|NA|NA|F|iibfexam|500081141|148201701|NA|NA|NA|NA|http://abc.somedomain.com|2387462372
			*/
			$member_exam_id=$this->session->userdata['non_memberdata']['member_exam_id'];
			$requestparameter = $MerchantID."|".$MerchantOrderNo."|NA|".$amount."|NA|NA|NA|INR|NA|R|".$SecurityID."|NA|NA|F|".$custom_field."|".$MerchantCustomerID."|".$member_exam_id."|NA|NA|NA|NA|".$pg_return_url;
			
			// Generate checksum for request parameter
			$req_param = $requestparameter."|".$checksum_key;
			$checksum = crc32($req_param);

			$requestparameter = $requestparameter . "|".$checksum;

			$data["msg"] = $requestparameter;
		
			$this->load->view('pg_bd_form',$data);
		
		
	}
	
	public function pg_response()
	{
		//$_REQUEST['msg'] = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";
		
		//	$_REQUEST['msg'] = "IIBF|2138195|HHMP4897894246|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 12:55:48|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|1435616898";
			
		if (isset($_REQUEST['msg']))
		{
			//echo "<pre>";
			//print_r($_REQUEST);
			//echo "<BR> Response : ".$_REQUEST['msg'];
			
			// validate checksum
			preg_match_all("/(.*)\|([0-9]*)$/", $_REQUEST['msg'],$result);
			//print_r($result);
			$res_checksum = $result[2][0];
			$msg_without_Checksum = $result[1][0];
		
			//$common_string = "sRKUUgdDrMGL";
			$checksum_key = $this->config->item('bd_ChecksumKey');
			$string_new=$msg_without_Checksum."|".$checksum_key;
			$checksum = crc32($string_new);
			
			$pg_res = explode("|",$msg_without_Checksum);   //print_r($pg_res); exit;
			
			// add payment responce in log
			$pg_response = "msg=".$_REQUEST['msg'];
			$this->log_model->logtransaction("billdesk", $pg_response, $pg_res[14]);
			
			if ($res_checksum == $checksum)
			{
				if($pg_res[16] == "iibfexam")
				{
					$MerchantOrderNo = filter_var($pg_res[1], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
					$transaction_no  = $pg_res[2];
					$payment_status = 2;
					switch ($pg_res[14])
					{
						case "0300":
							$payment_status = 1;
							break;
						case "0399":
							$payment_status = 0;
							break;
						/*case "PENDING":
							$payment_status = 2;
							break;*/
					}
					
					if($payment_status==1)
					{
						// Handle transaction success case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code');
						$exam_code=$get_user_regnum[0]['exam_code'];
						$reg_id=$get_user_regnum[0]['ref_id'];
						//To:Do change uniq application once iibf let us..
						//$last_count = $get_user_regnum[0]['ref_id']; 
						//$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
						//$randomNumber=mt_rand(0,9999);
						//$applicationNo = generate_nm_reg_num(); //date('Y').$randomNumber.$last_count;	
						$applicationNo = generate_NM_memreg($reg_id);
						$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
						$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
						
						
						$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7]);
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
						$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
						$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
						
		
						//Query to get exam details	
					   $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
						$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
						
					
						if($exam_info[0]['exam_mode']=='ON')
						{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
						{$mode='Offline';}
						else{$mode='';}
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
						//Query to get Medium	
						$this->db->where('exam_code',$exam_code);
						$this->db->where('exam_period',$exam_info[0]['exam_period']);
						$this->db->where('medium_code',$exam_info[0]['exam_medium']);
						$this->db->where('medium_delete','0');
						$medium=$this->master_model->getRecords('medium_master','','medium_description');
					
						//Query to get Payment details	
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount');
					
						
						//Query to get user details
						$this->db->join('state_master','state_master.state_code=member_registration.state');
						//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
						$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
						
					
						$upd_files = array();
						$photo_file = 'p_'.$applicationNo.'.jpg';
						$sign_file = 's_'.$applicationNo.'.jpg';
						$proof_file = 'pr_'.$applicationNo.'.jpg';
						
						if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
						{	$upd_files['scannedphoto'] = $photo_file;	}
						
						if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
						{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
						
						if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
						{	$upd_files['idproofphoto'] = $proof_file;	}
						
						if(count($upd_files)>0)
						{
							$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
						}
			
				
						$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
						$this->sms_template_id = 'P6tIFIwGR';
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
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
					//$result[0]['email'],
						$info_arr=array('to'=>'chaitali.jadhav@esds.co.in',
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
						
						//send sms					
						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);						
											
						//To Do---Transaction email to user	currently we using failure emailer 					
						if($this->Emailsending->mailsend($info_arr))
						{
							redirect(base_url().'Examtraining/acknowledge/'.base64_encode($applicationNo).'/'.base64_encode($MerchantOrderNo));
							//redirect(base_url().'Home/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);
						}
							else
						{
							echo 'Error while sending email';
							//$this->session->set_flashdata('error','Error while sending email !!');
							//redirect(base_url('register/preview/'));
						}
			}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7]);
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
							//Query to get Payment details	
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
						
						$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['ref_id']),'firstname,middlename,lastname,email,mobile');
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
						$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
						
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$this->sms_template_id = 'Jw6bOIQGg';
							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							
							$info_arr=array(	'to'=>'chaitali.jadhav@esds.co.in',//$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str
													);
							
							// send SMS
							$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

							$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
							
							//To Do---Transaction email to user	currently we using failure emailer 					
							if($this->Emailsending->mailsend($info_arr))
							{
								redirect(base_url());
							}
					
							
							
							//echo 'transaction fail';exit;
						}
				}
				///echo "<BR>Checksum validated successfully<br>";
				//echo "SUCCESS:".$pg_res[2];
			}
			else
			{
				//echo "<BR>Checksum validation unsuccessful<br>";
				//echo "INVALID:".$pg_res[2];
			}
			
			
			
			
			
			
			

			// Redirect to success/failure
		}
		else
		{
			die("Please try again...");	
		}
	}
	
	//validate captcha
	public function ajax_check_captcha()
	{
		$code=$_POST['code'];
		// check if captcha is set -
		if ($code == '' || $_SESSION["nonmemlogincaptcha"] != $code)
		{
			$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
			//$this->session->set_userdata("regcaptcha", rand(1, 100000));
			echo  'false';
		}
		else if ($_SESSION["nonmemlogincaptcha"] == $code)
		{
			//$this->session->unset_userdata("nonmemlogincaptcha");
			// $this->session->set_userdata("mycaptcha", rand(1,100000));
			echo 'true';
		}
	}
	
	
		public function ajax_check_captcha1()
	{
		
		$code=$_POST['code'];
		// check if captcha is set -
		if ($code == '' || $_SESSION["nonmemlogincaptcha"] != $code)
		{
			$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
			//$this->session->set_userdata("regcaptcha", rand(1, 100000));
			echo  'false';
		}
		else if ($_SESSION["nonmemlogincaptcha"] == $code)
		{
			//$this->session->unset_userdata("nonmemlogincaptcha");
			// $this->session->set_userdata("mycaptcha", rand(1,100000));
			echo 'true';
		}
	}
	
	
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemlogincaptcha");
		$this->session->set_userdata("nonmemlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["nonmemlogincaptcha"] = $cap['word'];
		echo $data;
	}
	
	//Thank you message to end user
	public function acknowledge()
	{
		$password=$decpass='';
		$data=array();
		//Query to get Payment details	
		//$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'member_regnumber,transaction_no,date,amount,exam_code,status');
		
		$member_exam_id = $this->session->userdata['non_memberdata']['member_exam_id'];
		
		$member_exam=$this->master_model->getRecords('member_exam',array('id'=>$member_exam_id),'regnumber,created_on,exam_fee,exam_code,pay_status');
		
		if(count($member_exam) <= 0)
		{redirect(base_url());}
		
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$member_exam[0]['regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
		
		
		//Query to get Medium	
		$this->db->where('exam_code',$member_exam[0]['exam_code']);
		$this->db->where('exam_period',$exam_info[0]['exam_period']);
		$this->db->where('medium_code',$exam_info[0]['exam_medium']);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		//Query to get user details
		$this->db->join('state_master','state_master.state_code=member_registration.state');
		//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
		$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$member_exam[0]['regnumber']),'firstname,middlename,lastname,regnumber,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,regid,isactive,regnumber,registrationtype');
		//$pass = md5($result[0]['usrpassword'] . "somerandomchars"); 
		
		$blended_result=$this->master_model->getRecords('blended_registration',array('member_no'=>$member_exam[0]['regnumber']),'program_code,batch_code,start_date,end_date,fee');
		
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		if(count($result) >0)
		{
		if($result[0]['isactive']==1)
		{
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
			
			$mysqltime=date("H:i:s");
			$user_data=array('nmregid'=>$result[0]['regid'],
							'nmregnumber'=>$result[0]['regnumber'],
							'nmfirstname'=>$result[0]['firstname'],
							'nmmiddlename'=>$result[0]['middlename'],
							'nmlastname'=>$result[0]['lastname'],
							'nmtimer'=>base64_encode($mysqltime),
							'memtype'=>$result[0]['registrationtype'],
							'nmpassword'=>base64_encode($decpass));
			$this->session->set_userdata($user_data);
			$sess = $this->session->userdata();
	 }
		}
		$data=array('application_number'=>$member_exam[0]['regnumber'],
		'password'=>$decpass,'exam_info'=>$exam_info,'medium'=>$medium,'result'=>$result,'member_exam'=>$member_exam);
		
		$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
		$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_examtraining_transaction_success'));
		$this->sms_template_id = 'NA';
		//echo $this->db->last_query(); die;
		$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#REG_NUM#", "".$member_exam[0]['regnumber']."",$newstring1);
		$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
		$newstring4 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring3);
		$newstring5 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring4);
		$newstring6 = str_replace("#PROGRAM_CODE#", "".$blended_result[0]['program_code']."",$newstring5);
		$newstring7 = str_replace("#BATCH_CODE#", "".$blended_result[0]['batch_code']."",$newstring6);
		$newstring8 = str_replace("#START_DATE#", "".$blended_result[0]['start_date']."",$newstring7);
		$newstring9 = str_replace("#END_DATE#", "".$blended_result[0]['end_date']."",$newstring8);
		$newstring10 = str_replace("#FEE#", "".$blended_result[0]['fee']."",$newstring9);
		$newstring11 = str_replace("#PASS#", "".$decpass."",$newstring10);
		$final_str = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring11);
						
		/*$info_arr=array('to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
							);
		
		$info_arr1=array('to'=>'ashrimali@iibf.org.in',
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
							);
						
		if($this->Emailsending->mailsend($info_arr))
		{
			$this->Emailsending->mailsend($info_arr1);
			
			$this->load->view('examtraining/profile_thankyou',$data);
			
		}
			else
		{
			echo 'Error while sending email';
			
		}*/
						
		if($exam_info[0]['exam_period'] == 14)
		{
			$info_arr=array('to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
			if($this->Emailsending->mailsend($info_arr))
			{
				$this->load->view('examtraining/profile_thankyou',$data);
			}
			else
			{
				echo 'Error while sending email';
			}
		}
		else
		{
				
			$info_arr1=array('to'=>'ravita@iibf.org.in',
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
							);
			
			if($this->Emailsending->mailsend($info_arr1))
			{
				
				$info_arr2=array('to'=>'ashrimali@iibf.org.in',
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str
								);
				$this->Emailsending->mailsend($info_arr2);
			
				$this->load->view('examtraining/profile_thankyou',$data);
			}
			else
			{
				echo 'Error while sending email';
			}
		}		
	}
	
	//Generate PDF
	public function pdf()
	{
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata['memberdata']['regno']),'regnumber,usrpassword');
	include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
	$key = $this->config->item('pass_key');
	$aes = new CryptAES();
	$aes->set_key(base64_decode($key));
	$aes->require_pkcs5();
	$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
	$html='<div class="content-wrapper">
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
                    Your application saved successfully.<br><br><strong>Your Membership No is</strong> '.$user_info[0]['regnumber'].' <strong>and Your password is </strong>'.$decpass.'<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
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
	
	

	
	
	//Set register user data in session
	public function setsession()
    {
		$outputphoto1=$outputsign1=$outputsign1='';
		$scannedphoto_file = '';
		$scannedsignaturephoto_file = '';
		$idproof_file = '';
		$enduserinfo = $this->session->userdata('enduserinfo');
		if(count($enduserinfo))
		{
			$this->session->unset_userdata('enduserinfo');
		}
				
		$eduqual1=$eduqual2=$eduqual3='';
		if($_POST['optedu']=='U')
		{
			$eduqual1=$_POST["eduqual1"];
		}
		else if($_POST['optedu']=='G')
		{
			$eduqual2=$_POST["eduqual2"];
		}
		else if($_POST['optedu']=='P')
		{
			$eduqual3=$_POST["eduqual3"];
		}
		
		$date=date('Y-m-d h:i:s');
		//Generate dynamic photo
		$input = $_POST["hiddenphoto"];
		/*$tmp_nm = strtotime($date).rand(0,100);
		$outputphoto = getcwd()."/uploads/photograph/non_mem_photo_".$tmp_nm.".jpg";
		$outputphoto1 = base_url()."uploads/photograph/non_mem_photo_".$tmp_nm.".jpg";
		file_put_contents($outputphoto, file_get_contents($input));*/
		
		
		
		if(isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name']!='')
		{
			$tmp_nm = strtotime($date).rand(0,100);
			$new_filename = 'non_mem_photo_'.$tmp_nm;
			$photopath = "./uploads/photograph";
			$uploadData = upload_file('scannedphoto', $photopath, $new_filename,'','',TRUE);
			if($uploadData)
			{
				$scannedphoto_file = $uploadData['file_name'];
				$outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
			}
		}
		
		
		// generate dynamic scan signature
		$inputsignature = $_POST["hiddenscansignature"];
		/*$tmp_signnm = strtotime($date).rand(0,100);
		$outputsign = getcwd()."/uploads/scansignature/non_mem_sign_".$tmp_signnm.".jpg";
		$outputsign1 = base_url()."uploads/scansignature/non_mem_sign_".$tmp_signnm.".jpg";
		file_put_contents($outputsign, file_get_contents($inputsignature));*/
		
		if(isset($_FILES['scannedsignaturephoto']['name']) && $_FILES['scannedsignaturephoto']['name']!='')
		{
			$tmp_signnm = strtotime($date).rand(0,100);
			$signaturepath = "./uploads/scansignature";
			$new_filename = 'non_mem_sign_'.$tmp_signnm;
			$uploadData = upload_file('scannedsignaturephoto', $signaturepath, $new_filename,'','',TRUE);
			if($uploadData)
			{
				$scannedsignaturephoto_file = $uploadData['file_name'];
				$outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
			}
		}
		
		
		
		// generate dynamic id proof
		$inputidproofphoto = $_POST["hiddenidproofphoto"];
		
		/*$tmp_inputidproof = strtotime($date).rand(0,100);
		$outputidproof = getcwd()."/uploads/idproof/non_mem_idproof_".$tmp_inputidproof.".jpg";
		$outputidproof1 = base_url()."uploads/idproof/non_mem_idproof_".$tmp_inputidproof.".jpg";
		file_put_contents($outputidproof, file_get_contents($inputidproofphoto));*/
		
		if(isset($_FILES['idproofphoto']['name']) && $_FILES['idproofphoto']['name']!='')
		{
			$tmp_inputidproof = strtotime($date).rand(0,100);
			$idproofpath = "./uploads/idproof";
			$new_filename = 'non_mem_idproof_'.$tmp_inputidproof;
			$uploadData = upload_file('idproofphoto', $idproofpath, $new_filename,'','',TRUE);
			if($uploadData)
			{
				$idproof_file = $uploadData['file_name'];
				$outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
			}
		}
		
		
		$dob1= $_POST["dob1"];
		$dob = str_replace('/','-',$dob1);
		$dateOfBirth = date('Y-m-d',strtotime($dob));
		
		if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
		{
			$user_data=array(	'firstname'			=>$_POST["firstname"],
									'sel_namesub'		=>$_POST["sel_namesub"],
									'addressline1'		=>$_POST["addressline1"],
									'addressline2'		=>$_POST["addressline2"],
									'addressline3'		=>$_POST["addressline3"],
									'addressline4'		=>$_POST["addressline4"],
									'city'				=>$_POST["city"],	
									'code'				=>trim($_POST["code"]),
									'district'			=>$_POST["district"],	
									'dob'				=>$dateOfBirth,
									'eduqual'			=>$_POST["eduqual"],	
									'eduqual1'			=>$eduqual1,	
									'eduqual2'			=>$eduqual2,	
									'eduqual3'			=>$eduqual3,	
									'email'				=>$_POST["email"],	
									'gender'			=>$_POST["gender"],	
									'idNo'				=>$_POST["idNo"],	
									'idproof'			=>$_POST["idproof"],	
									'lastname'			=>$_POST["lastname"],	

									'middlename'		=>$_POST["middlename"],	
									'mobile'			=>$_POST["mobile"],	
									'optedu'			=>$_POST["optedu"],	
									'optnletter'		=>$_POST["optnletter"],	
									'phone'				=>$_POST["phone"],	
									'pincode'			=>$_POST["pincode"],	
									'state'				=>$_POST["state"],	
									'stdcode'			=>$_POST["stdcode"],
									'scannedphoto'		=>$outputphoto1,
									'scannedsignaturephoto'=>$outputsign1,
									'idproofphoto'		=>$outputidproof1,
									'photoname'			=>$scannedphoto_file,
									'signname'			=>$scannedsignaturephoto_file,
									'idname'			=>$idproof_file,
									'selCenterName'		=>$_POST["selCenterName"],
									'txtCenterCode'=>	$_POST["txtCenterCode"],
									'optmode'			=>$_POST["optmode"],
									'exid'				=>$_POST["exid"],
									'mtype'				=>$_POST["mtype"],
									'memtype'			=>$_POST["memtype"],
									'eprid'				=>$_POST["eprid"],
									'rrsub'				=>$_POST["rrsub"],
									'excd'				=>$_POST["excd"],
									'exname'			=>$_POST["exname"],
									'fee'				=>	$_POST["fee"],
									'medium'			=>$_POST['medium']);
			$this->session->set_userdata('enduserinfo',$user_data);
			//echo 'true';
			redirect(base_url().'Examtraining/preview');
		//$data=array('middle_content'=>'preview_register');
		//$this->load->view('nm_common_view',$data);
		}
		else
		{
			echo false;
		}
	 } 
	 
	 //Preview of register form 
	 public function preview()
    {
		/*if(!$this->session->userdata('enduserinfo'))
		{
			redirect(base_url());
		}*/
		
		////check temp file uploaded or not////
		$images_flag=0;
		if(!file_exists("uploads/photograph/".$this->session->userdata['enduserinfo']['photoname']))
		{
			$images_flag=1;
		}
		if(!file_exists("uploads/scansignature/".$this->session->userdata['enduserinfo']['signname']))
		{
			$images_flag=1;
		}
		if(!file_exists("uploads/idproof/".$this->session->userdata['enduserinfo']['idname']))
		{
			$images_flag=1;
		}
		if($images_flag)
		{
			$this->session->set_flashdata('error','Please upload valid image(s)');
			redirect(base_url().'examtraining/member/?Mtype='.base64_encode($this->session->userdata['enduserinfo']['mtype']).'=&ExId='.base64_encode($this->session->userdata['enduserinfo']['excd']).'');
		}
		
		
		
		
		//check email,mobile duplication on the same time from different browser!!
		$endTime = date("H:i:s");
		$start_time= date("H:i:s",strtotime("-20 minutes",strtotime($endTime)));
		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');
		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);
		$this->db->or_where('mobile',$this->session->userdata['enduserinfo']['mobile']);
		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));
		if(count($check_duplication) > 0)
		{
			redirect(base_url().'examtraining/accessdenied/');
		}
		//check exam activation
		$check_exam_activation=check_exam_activate($this->session->userdata['enduserinfo']['excd']);
		if($check_exam_activation==0)
		{
			redirect(base_url().'examtraining/accessdenied/');
		}
		
  		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master',array('medium_master.exam_code'=>$this->session->userdata['enduserinfo']['excd']));
		
		
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$center=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd']));
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
		$this->db->like('misc_master.exam_code',$this->session->userdata['enduserinfo']['excd']);
		$exam_period=$this->master_model->getRecords('misc_master','','misc_master.exam_period'); 
		
		//$program_code = $this->session->userdata['enduserinfo']['program_code'];
		$program_name = $this->session->userdata['enduserinfo']['program_name'];
		$blended_center_name = $this->session->userdata['enduserinfo']['blended_center_name'];
		//$venue_name = $this->session->userdata['enduserinfo']['venue_name'];
		$start_date = $this->session->userdata['enduserinfo']['start_date'];
		$end_date = $this->session->userdata['enduserinfo']['end_date'];
		
		
			
		$data=array('middle_content'=>'examtraining/non_mem_preview_register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'medium'=>$medium,'center'=>$center,'exam_period'=>$exam_period,'idtype_master'=>$idtype_master);
		$this->load->view('examtraining/common_view_fullwidth',$data);
		
	 }
	 
	 
	 //Genereate random password function
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

//call back for check exam code 19 and specified qualification to be Company secretary(CS)
	public function check_exam_eligibility($specify_qualification,$examcode)
	{
		if($specify_qualification!="" && $examcode!='')
		{
			if($examcode==19)
			{
					if($specify_qualification==91 && $examcode==19)
					{	
						$this->form_validation->set_message('error', "");
						return true;
					}
					else
					{
						$str='You are not eligible to apply for this exam, you should have CS qualification.';
						$this->form_validation->set_message('check_exam_eligibility', $str); 
						return false;
					}
			}
			else
			{
				return true;
			}
		}
		else
		{
			$str='exam / qualification field is required.';
			$this->form_validation->set_message('check_exam_eligibility', $str); 
			return false;
		}
	}
	
	 
	 //check mail alredy exist or not
	 public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$where="(registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);}
			else
			{
				//$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				//$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				//$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				//$str='The entered email ID / mobile no already exist';
				$str='You are already registered and the email ID is in use.  If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
				
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	##---------check mobile number alredy exist or not for non member(prafull)-----------##
	 public function mobileduplication()
	{
		$mobile=$_POST['mobile'];
		if($mobile!="")
		{
			$where="( registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);
				}
			else
			{
				//$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				//$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				//$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				//$str='The entered email ID / mobile no already exist';
				$str='You are already registered and the Mobile no is in use.  If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	
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
    
	##---------End user logout (Vrushali)-----------##//
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		//redirect(base_url().'nonreg/memlogin/?Extype=MQ==&Mtype=Tk0='); 
		redirect(base_url().'examtraining/'); 
	}
	
	
	######### if seat allocation full show message#######
	public function refund($order_no=NULL)
	{
		//payment detail
		//$this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
		//$this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no)));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		$this->db->where('remark','2');
		$admit_card_refund=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$payment_info[0]['ref_id']));
		if(count($admit_card_refund) >0)
		{
			$update_data = array('remark' => 3);
			$this->master_model->updateRecord('admit_card_details',$update_data,array('mem_exam_id'=>$payment_info[0]['ref_id']));
		}
		$exam_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$payment_info[0]['exam_code']));
		$data=array('payment_info'=>$payment_info,'exam_name'=>$exam_name);
		$this->load->view('examtraining/non_mem_reg_refund',$data);
	}
	
}
