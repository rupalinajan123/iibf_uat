<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dbfuser_new extends CI_Controller {
	
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
		//$this->load->model('chk_session');
	  //	$this->chk_session->chk_member_session();
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
	 
	 ##---------default userlogin (prafull)-----------##
	public function login()
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
				'registrationtype'=>'DB',
			);
			if ($this->form_validation->run() == TRUE)
			{
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info))
				{ 
					 if($user_info[0]['isactive']==1)
					 {
						$chklink=$this->master_model->showcarddownloadlink($user_info[0]['regnumber']);
						if($chklink['is_show'] == "yes"){
							$exam_name=$chklink['exam_name'];
							$showlink = "yes";	
						}else{
							$showlink = "no";	
							$exam_name=$chklink['exam_name'];
						}
				
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
				
						$mysqltime=date("H:i:s");
						$user_data=array('dbregid'=>$user_info[0]['regid'],
													'dbregnumber'=>$user_info[0]['regnumber'],
													'dbfirstname'=>$user_info[0]['firstname'],
													'dbmiddlename'=>$user_info[0]['middlename'],
													'dblastname'=>$user_info[0]['lastname'],
													'dbtimer'=>base64_encode($mysqltime),
													'showlink'=>$showlink,
													'exam_name'=>$exam_name,
													'memtype'=>$user_info[0]['registrationtype'],
													'dbpassword'=>base64_encode($decpass));
													
						$this->session->set_userdata($user_data);
						$sess = $this->session->userdata();
						redirect(base_url().'Dbf/showexam/?ExId='.$this->input->get('ExId'));
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
		$this->session->set_userdata('dbflogincaptcha', $cap['word']);
		$this->load->view('dbf/dbf_login',$data);

	}
	
	##---------check captcha userlogin (vrushali)-----------##
	public function check_captcha_userlogin($code) 
	{
		if(!isset($this->session->dbflogincaptcha) && empty($this->session->dbflogincaptcha))
		{
			return false;
		}
		
		if($code == '' || $this->session->dbflogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
			$this->session->set_userdata("dbflogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->dbflogincaptcha == $code)
		{
			$this->session->set_userdata('dbflogincaptcha','');
			$this->session->unset_userdata("dbflogincaptcha");
			return true;
		}
	}
	
	//##---- reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("dbflogincaptcha");
		$this->session->set_userdata("dbflogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["dbflogincaptcha"] = $cap['word'];
		echo $data;
	}
	
	
	public function examlist()
	{
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
		 
			 $ignore_exam_code = array(33,47,51,52);
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
			 $this->db->where_not_in('exam_activation_master.exam_code', $ignore_exam_code);
			 $this->db->group_by('medium_master.exam_code');
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
		$this->load->view('dbf/examlist',$data);
	}
	 
	 
	public function member()
	{
		$flag=1;
		$Mtype = base64_decode($this->input->get('Mtype'));
	 	$ExId = base64_decode($this->input->get('ExId'));
		$scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password='';
		$data['validation_errors'] = '';
		
		
		 if(isset($_POST['btnSubmit']))  	
		 {
			
				$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';
				$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
				$this->form_validation->set_rules('firstname','First Name','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('nameoncard','Name as to appear on Card','trim|max_length[35]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('addressline1','Addressline1','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('district','District','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('city','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('state','State','trim|required|xss_clean');
				
				if($this->input->post('state')!='')
				{
					$state=$this->input->post('state');
				}
				
				$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
				$this->form_validation->set_rules('dob1','Date of Birth','trim|required|xss_clean');
				
				$this->form_validation->set_rules('gender','Gender','trim|required|xss_clean');
				$this->form_validation->set_rules('optedu','Qualification','trim|required|xss_clean');
				
				if(isset($_POST['middlename']) && $_POST['middlename']!='')
				{
					$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
				}
				if(isset($_POST['lastname']) && $_POST['lastname']!='')
				{
					$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['optedu']) && $_POST['optedu']=='U')
				{
					$this->form_validation->set_rules('eduqual1','Please specify','trim|required|xss_clean');
				}
				else if(isset($_POST['optedu']) && $_POST['optedu']=='G')
				{
					$this->form_validation->set_rules('eduqual2','Please specify','trim|required|xss_clean');
				}
				else if(isset($_POST['optedu']) && $_POST['optedu']=='P')
				{
					$this->form_validation->set_rules('eduqual3','Please specify','trim|required|xss_clean');
				}
				
				if(isset($_POST['addressline2']) && $_POST['addressline2']!='')
				{
					$this->form_validation->set_rules('addressline2','Addressline2','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['addressline3']) && $_POST['addressline3']!='')
				{
					$this->form_validation->set_rules('addressline3','Addressline3','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['addressline4']) && $_POST['addressline4']!='')
				{
					$this->form_validation->set_rules('addressline4','Addressline4','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['stdcode']) && $_POST['stdcode']!='')
				{
					$this->form_validation->set_rules('stdcode','STD Code','trim|max_length[4]|required|numeric|xss_clean');
				}
				
				if(isset($_POST['phone']) && $_POST['phone']!='')
				{
					$this->form_validation->set_rules('phone',' Phone No','trim|required|numeric|xss_clean');
				}
				
				//$this->form_validation->set_rules('institutionworking','Bank/Institution working','trim|required|alpha_numeric_spaces|xss_clean');
				//$this->form_validation->set_rules('office','Branch/Office','trim|required|xss_clean');
				//$this->form_validation->set_rules('designation','Designation','trim|required|xss_clean');
				//$this->form_validation->set_rules('doj1','Date of joining Bank/Institution','trim|required|xss_clean');
				$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
				$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
				$this->form_validation->set_rules('scannedphoto','scanned Photograph','callback_scannedphoto_upload');
				$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','callback_scannedsignaturephoto_upload');
				$this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');
				$this->form_validation->set_rules('idNo','ID No','trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('idproofphoto','Id proof','callback_idproofphoto_upload');
				$this->form_validation->set_rules('medium','Medium','required|xss_clean');
				$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
				$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
				$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
				$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
				
				
				if($this->form_validation->run()==TRUE)
				{
					$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';
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
					if(isset($_FILES['scannedphoto']['name']) &&($_FILES['scannedphoto']['name']!=''))
					{
						$img = "scannedphoto";
						$tmp_nm = strtotime($date).rand(0,100);
						$new_filename = 'non_mem_photo_'.$tmp_nm;
						$config=array('upload_path'=>'./uploads/photograph',
								  'allowed_types'=>'jpg|jpeg|',
								  'file_name'=>$new_filename,);
								  
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
						if($size)
						{
						if($this->upload->do_upload($img))
						{
							  $dt=$this->upload->data();
							  $file=$dt['file_name'];
							 $scannedphoto_file = $dt['file_name'];
							 $outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
						}
						else
						{
								$var_errors.=$this->upload->display_errors();
								//$data['error']=$this->upload->display_errors();
						}
						}
						else
						{
								$var_errors.='The filetype you are attempting to upload is not allowed';
						}
						
					}
					
					
					// generate dynamic scan signature
					$inputsignature = $_POST["hiddenscansignature"];
					if(isset($_FILES['scannedsignaturephoto']['name']) &&($_FILES['scannedsignaturephoto']['name']!=''))
					{
						$img = "scannedsignaturephoto";
						$tmp_signnm = strtotime($date).rand(0,100);
						$new_filename = 'non_mem_sign_'.$tmp_signnm;
						$config=array('upload_path'=>'./uploads/scansignature',
								  'allowed_types'=>'jpg|jpeg|',
								  'file_name'=>$new_filename,);
								  
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
						if($size)
						{
						if($this->upload->do_upload($img))
						{
							  $dt=$this->upload->data();
							 $scannedsignaturephoto_file=$dt['file_name'];
							 $outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
						}
						else
						{
								$var_errors.=$this->upload->display_errors();
								//$data['error']=$this->upload->display_errors();
						}
						}
						else
						{
								$var_errors.='The filetype you are attempting to upload is not allowed';
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
									  'allowed_types'=>'jpg|jpeg|',
									  'file_name'=>$new_filename,);
									  
							$this->upload->initialize($config);
							$size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
							if($size)
							{
							if($this->upload->do_upload($img))
							{
								  $dt=$this->upload->data();
								  $idproof_file=$dt['file_name'];
								  $outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
							}
							else
							{
									$var_errors.=$this->upload->display_errors();
									//$data['error']=$this->upload->display_errors();
							}
							}
							else
							{
									$var_errors.='The filetype you are attempting to upload is not allowed';
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
									'city'					=>$_POST["city"],	
									'code'					=>trim($_POST["code"]),
									'district'				=>$_POST["district"],	
									'dob'						=>$dateOfBirth,
									'eduqual'				=>$_POST["eduqual"],	
									'eduqual1'				=>$eduqual1,	
									'eduqual2'				=>$eduqual2,	
									'eduqual3'				=>$eduqual3,	
									'email'					=>$_POST["email"],	
									'gender'				=>$_POST["gender"],	
									'idNo'					=>$_POST["idNo"],	
									'idproof'				=>$_POST["idproof"],	
									'lastname'				=>$_POST["lastname"],	
									'middlename'			=>$_POST["middlename"],	
									'mobile'					=>$_POST["mobile"],	
									'nameoncard'			=>$_POST["nameoncard"],	
									'optedu'				=>$_POST["optedu"],	
									'optnletter'			=>$_POST["optnletter"],	
									'phone'					=>$_POST["phone"],	
									'pincode'				=>$_POST["pincode"],	
									'state'					=>$_POST["state"],	
									'stdcode'				=>$_POST["stdcode"],
									'scannedphoto'		=>$outputphoto1,
									'scannedsignaturephoto'=>$outputsign1,
									'idproofphoto'		=>$outputidproof1,
									'photoname'			=>"non_mem_photo_".$tmp_nm.".jpg",
									'signname'				=>"non_mem_sign_".$tmp_signnm.".jpg",
									'idname'				=>"non_mem_idproof_".$tmp_inputidproof.".jpg",
									'selCenterName'	=>$_POST["selCenterName"],
									'txtCenterCode'		=>	$_POST["txtCenterCode"],
									'optmode'				=>$_POST["optmode"],
									'exid'					=>$_POST["exid"],
									'mtype'					=>$_POST["mtype"],
									'memtype'				=>$_POST["memtype"],
									'eprid'					=>$_POST["eprid"],
									'rrsub'					=>$_POST["rrsub"],
									'excd'					=>$_POST["excd"],
									'exname'				=>$_POST["exname"],
									'fee'						=>	$_POST["fee"],
									'medium'				=>$_POST['medium']);
						$this->session->set_userdata('enduserinfo',$user_data);
						redirect(base_url().'Dbfuser/preview');
					}
				}
				/*else
				{
					echo validation_errors();exit;
				}*/
				
		 }
		 
		
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$states=$this->master_model->getRecords('state_master');
		$this->db->not_like('name','college');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		//Considering B1 as group code on query (By Prafull)
		/*$where="CASE WHEN eligible_master.app_category ='' THEN  fee_master.group_code='B1' ELSE fee_master.group_code=eligible_master.app_category END";
		$this->db->join('fee_master','fee_master.exam_code=exam_master.exam_code');
		$this->db->join("eligible_master","eligible_master.exam_code=fee_master.exam_code AND eligible_master.eligible_period=fee_master.exam_period");
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=eligible_master.eligible_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where('fee_master.member_category',$Mtype);
		$this->db->where($where,'',false);
		$this->db->where('exam_master.exam_code',$ExId);
		$examinfo=$this->master_model->getRecords('exam_master');
		if(count($examinfo) <=0)
		{*/
			$this->db->select('fee_master.*,exam_master.*,misc_master.*,fee_master.fee_amount as fees');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code");
 			$this->db->where('fee_master.group_code','B1');
			$this->db->where('fee_master.member_category',$Mtype);
			$this->db->where('exam_master.exam_code',$ExId);
 			$examinfo = $this->master_model->getRecords('exam_master');
			//echo $this->db->last_query();			exit;
			/*$this->db->where('exam_name',$ExId);
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));*/
			
	//	}
			//echo $this->db->last_query();exit;
			$this->db->where('exam_code',$ExId);
			$medium=$this->master_model->getRecords('medium_master');
			
			$this->db->where('exam_name',$ExId);
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			if(!count($examinfo) > 0 || !count($medium) > 0 ||  !count($center) > 0)
			{
				$flag=0;
			}
			$this->load->helper('captcha');
			$this->session->unset_userdata("dbfmemregcaptcha");
			$this->session->set_userdata("dbfmemregcaptcha", rand(1, 100000));
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => base_url().'uploads/applications/',
						);
			$cap = create_captcha($vals);
			$_SESSION["dbfmemregcaptcha"] = $cap['word']; 
			if($flag==1)
			{
			/*$data=array('middle_content'=>'nonmember/non_mem_reg','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master);
			$this->load->view('nonmember/nm_common_view',$data);*/
			
			
			$data=array('middle_content'=>'dbf/dbf_mem_reg','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master);
			$this->load->view('dbf/common_view_fullwidth',$data);
		}
		else
		{
			$this->load->view('access_denied',$data);
		}
	}
	
	//call back for e-mail duplication
	 public function check_emailduplication($email)
	{
		if($email!="")
		{
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
			if($code == '' || $_SESSION["dbfmemregcaptcha"] != $code )
			{
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
				//$this->session->set_userdata("regcaptcha", rand(1,100000));
				return false;
			}
			if($_SESSION["dbfmemregcaptcha"] == $code)
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
		$scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password='';
		$data['validation_errors'] = '';
		
		/*$last_id=$this->master_model->getRecords('member_registration','','regid',array('regid'=>'DESC'),'',1);
		if(count($last_id) > 0)
		{
			$last_count = $last_id[0]['regid']; 
			$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
			$randomNumber=mt_rand(0,9999);
			$applicationNo = date('Y').$randomNumber.$last_count;	
		}	
		else
		{
			$last_count = '0'; 
			$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
			$randomNumber=mt_rand(0,9999);
			$applicationNo = date('Y').$randomNumber.$last_count;	
		}	*/
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
			$nameoncard = strtoupper($this->session->userdata['enduserinfo']['nameoncard']);
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
			if($optedu=='U')
			{
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
			$email = $this->session->userdata['enduserinfo']['email'];
			$stdcode =$this->session->userdata['enduserinfo']['stdcode'];
			$phone = $this->session->userdata['enduserinfo']['phone'];
			$mobile = $this->session->userdata['enduserinfo']['mobile'];
			$idproof = $this->session->userdata['enduserinfo']['idproof'];
			$idNo = $this->session->userdata['enduserinfo']['idNo'];
			$optnletter = $this->session->userdata['enduserinfo']['optnletter'];
			$centerid=$this->session->userdata['enduserinfo']['selCenterName'];
			$centercode=$this->session->userdata['enduserinfo']['txtCenterCode'];
			$exmode=$this->session->userdata['enduserinfo']['optmode'];
			//$declaration1 = $_POST['declaration1'];
			
			$insert_info = array(
											'usrpassword'=>$encPass,
											'namesub' => $sel_namesub,
											'firstname'=>$firstname,
											'middlename'=>$middlename,
											'lastname'=>$lastname,
											'displayname'=>$nameoncard,
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
											'email'=>$email,
											'registrationtype'=>'DB',
											'stdcode'=>$stdcode,
											'office_phone'=>$phone,
											'mobile'=>$mobile,
											'scannedphoto'=>$scannedphoto_file,
											'scannedsignaturephoto'=>$scannedsignaturephoto_file,	
											'idproof'=>$idproof,
											'idNo'=>$idNo,
											'optnletter'=>$optnletter,
											'declaration'=>'1',
											'idproofphoto'=>$idproofphoto_file,
											'excode'=>$this->session->userdata['enduserinfo']['excd'],
											'fee'=>$this->session->userdata['enduserinfo']['fee'],
											'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
											'centerid'=>$centerid,
											'centercode'=>$centercode,
											'exmode'=>$exmode,
											'createdon'=>date('Y-m-d H:i:s')
								);			
								
			//$personalInfo = filter($personal_info);
			
			if($last_id =$this->master_model->insertRecord('member_registration',$insert_info,true))
			{
					$upd_files = array();
					$photo_file = 'p_'.$last_id.'.jpg';
					$sign_file = 's_'.$last_id.'.jpg';
					$proof_file = 'pr_'.$last_id.'.jpg';
					
					if(@rename("./uploads/photograph/".$scannedphoto_file,"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@rename("./uploads/scansignature/".$scannedsignaturephoto_file,"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@rename("./uploads/idproof/".$idproofphoto_file,"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0)
					{
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$last_id));
					}
					
					logactivity($log_title ="Non-Member user registration ", $log_message = serialize($insert_info));
					$inser_exam_array=array(	'regnumber'=>$last_id,
															'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
															'exam_mode'=>$this->session->userdata['enduserinfo']['optmode'],
															'exam_medium'=>$this->session->userdata['enduserinfo']['medium'],
															'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
															'exam_center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],
															'exam_fee'=>$this->session->userdata['enduserinfo']['fee'],
															'created_on'=>date('y-m-d h:i:sa'));
									
										
						if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_exam_array,true))
						{
							// Renaming the previously uploaded file with Reg Num inserted in database
							logactivity($log_title ="Exam applied During Non-Member user registration ", $log_message = serialize($inser_exam_array));
							$exam_name_desc=$this->master_model->getRecords('exam_master',array('	exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_delete'=>'0'),'description');
							$userarr=array('regno'=>$last_id,
													'password'=>$password,
													'email'=>$email,
													'exam_fee'=>$this->session->userdata['enduserinfo']['fee'],
													'exam_desc'=>$exam_name_desc[0]['description'],
													'excode'=>$this->session->userdata['enduserinfo']['excd'],
													'member_exam_id'=>$exam_last_id);
							 $this->session->set_userdata('dbf_memberdata', $userarr); 
							if($this->config->item('exam_apply_gateway')=='sbi')
							{
								redirect(base_url().'Dbfuser/sbi_make_payment/');
							}
							else
							{
							 	redirect(base_url()."Dbfuser/make_payment");
							}
						}
					else
					{
						$userarr=array('application_number'=>'','password'=>'','email'=>'');
						$this->session->set_userdata('memberdata', $userarr); 
						return false;
					}
					}
			else
				{
					$userarr=array('regno'=>'','password'=>'','email'=>'');
					$this->session->set_userdata('dbf_memberdata', $userarr); 
					$this->session->set_flashdata('error','Error while during registration.please try again!');
					redirect(base_url());
				}
			 }
		}
	
	
	##------------------Exam appky with SBI Payment Gate-way(PRAFULL)---------------##
	public function sbi_make_payment()
	{
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$regno = $this->session->userdata['dbf_memberdata']['regno'];
			$exam_desc= $this->session->userdata['dbf_memberdata']['exam_desc'];
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Dbfuser/sbitranssuccess";
			$pg_fail_url    = base_url()."Dbfuser/sbitransfail";
			
			if($this->config->item('sb_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				$amount=$this->session->userdata['examinfo']['fee'];
			}
			
			$amount=1;
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");

			//With Registration DBF
			//Non memeber / DBF Apply exam
			//Ref1 = orderid
			//Ref2 = iibfexam
			//Ref3 = orderid
			//Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid']),'exam_month');
			
			if($this->session->userdata['enduserinfo']['excd']==340)
			{
				$exam_code=34;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==580)
			{
				$exam_code=58;		
			}
			else
			{
				$exam_code=$this->session->userdata['enduserinfo']['excd'];
			}
			
			$ref4=$exam_code.$yearmonth[0]['exam_month'];
			
			// Create transaction
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $regno,
				'description'      => $exam_desc,
				'status'           => '2',
				'exam_code'        => $this->session->userdata['dbf_memberdata']['excode'],
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>'IIBF_EXAM_DB',
				//'pg_other_details'=>$custom_field
			);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$MerchantOrderNo."^".$ref4;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			$MerchantCustomerID = $regno;

			//$custom_field = "^iibfexam^^";

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
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
				if($get_user_regnum[0]['status']==2)
				{
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id=$get_user_regnum[0]['ref_id'];
					//$applicationNo = generate_dbf_reg_num();
					$applicationNo = generate_DBF_memreg($reg_id); 
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
				
					//Query to get exam details	
				   $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
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
				
					$info_arr=array('to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
					
					//send sms					
					$sms_newstring = str_replace("#exam_name#", "".trim($exam_info[0]['description'])."",$emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",$sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",$sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",$sms_newstring2);
					$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
					$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
			}
		}
		redirect(base_url().'Dbfuser/acknowledge/'.base64_encode($MerchantOrderNo));
		
	}
	
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
			
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
			if($get_user_regnum[0]['status']!=0 && $get_user_regnum[0]['status']==2)
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
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
					
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['ref_id']),'firstname,middlename,lastname,email,mobile');
					
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
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
					$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
				//Main Code
				redirect(base_url());
			//}
					
		//echo 'transaction fail';exit;
		}
		else
		{
			die("Please try again...");
		}
	}
	
	//function for payment
	public function make_payment()
	{
		if(!$this->session->userdata('dbf_memberdata'))
		{
			redirect(base_url());
		}
		
			$regno = $this->session->userdata['dbf_memberdata']['regno'];
			$exam_desc= $this->session->userdata['dbf_memberdata']['exam_desc'];
			$MerchantID = $this->config->item('bd_MerchantID');
			$SecurityID = $this->config->item('bd_SecurityID');
			$checksum_key = $this->config->item('bd_ChecksumKey');
			$pg_return_url = base_url()."Dbfuser/pg_response";
			$member_exam_id=$this->session->userdata['dbf_memberdata']['member_exam_id'];
			//$amount= $this->session->userdata['dbf_memberdata']['exam_fee'];
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
				'exam_code'        => $this->session->userdata['dbf_memberdata']['excode'],
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
			$member_exam_id=$this->session->userdata['dbf_memberdata']['member_exam_id'];
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
						//$applicationNo = generate_dbf_reg_num(); //date('Y').$randomNumber.$last_count;	
						$applicationNo = generate_DBF_memreg($reg_id);
						
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
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
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
					
						$info_arr=array('to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
						
						//send sms					
						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
											
						//To Do---Transaction email to user	currently we using failure emailer 					
						if($this->Emailsending->mailsend($info_arr))
						{
							redirect(base_url().'Dbfuser/acknowledge/'.base64_encode($applicationNo).'/'.base64_encode($MerchantOrderNo));
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
						
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
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
							$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							
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
		if ($code == '' || $_SESSION["dbfmemregcaptcha"] != $code)
		{
			$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
			//$this->session->set_userdata("regcaptcha", rand(1, 100000));
			echo  'false';
		}
		else if ($_SESSION["dbfmemregcaptcha"] == $code)
		{
			//$this->session->unset_userdata("nonmemlogincaptcha");
			// $this->session->set_userdata("mycaptcha", rand(1,100000));
			echo 'true';
		}
	}
	
	

	//Thank you message to end user
	public function acknowledge($MerchantOrderNo=NULL)
	{
		$data=array();
		//Query to get Payment details	
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'transaction_no,date,amount,exam_code,status,member_regnumber');
		
		if(count($payment_info) <= 0)
		{redirect(base_url());}
		
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
		
		//Query to get Medium	
		$this->db->where('exam_code',$payment_info[0]['exam_code']);
		$this->db->where('exam_period',$exam_info[0]['exam_period']);
		$this->db->where('medium_code',$exam_info[0]['exam_medium']);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		//Query to get user details
		$this->db->join('state_master','state_master.state_code=member_registration.state');
		//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
		$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,regnumber,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,regid,isactive,regnumber,registrationtype');	
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		if($result[0]['isactive']==1)
		{
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
			
			$mysqltime=date("H:i:s");
			$user_data=array('dbregid'=>$result[0]['regid'],
										'dbregnumber'=>$result[0]['regnumber'],
										'dbfirstname'=>$result[0]['firstname'],
										'dbmiddlename'=>$result[0]['middlename'],
										'dblastname'=>$result[0]['lastname'],
										'dbtimer'=>base64_encode($mysqltime),
										'memtype'=>$result[0]['registrationtype'],
										'dbpassword'=>base64_encode($decpass));
			$this->session->set_userdata($user_data);
			$sess = $this->session->userdata();
	 }
						 
		$data=array('application_number'=>$payment_info[0]['member_regnumber'],
		'password'=>$decpass,'payment_info'=>$payment_info,'exam_info'=>$exam_info,'medium'=>$medium,'result'=>$result);
		
		$this->load->view('dbf/profile_thankyou',$data);
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
		$tmp_nm = strtotime($date).rand(0,100);
		$outputphoto = getcwd()."/uploads/photograph/non_mem_photo_".$tmp_nm.".jpg";
		$outputphoto1 = base_url()."uploads/photograph/non_mem_photo_".$tmp_nm.".jpg";
		file_put_contents($outputphoto, file_get_contents($input));
		
		// generate dynamic scan signature
		$inputsignature = $_POST["hiddenscansignature"];
		$tmp_signnm = strtotime($date).rand(0,100);
		$outputsign = getcwd()."/uploads/scansignature/non_mem_sign_".$tmp_signnm.".jpg";
		$outputsign1 = base_url()."uploads/scansignature/non_mem_sign_".$tmp_signnm.".jpg";
		file_put_contents($outputsign, file_get_contents($inputsignature));
		
		// generate dynamic id proof
		$inputidproofphoto = $_POST["hiddenidproofphoto"];
		$tmp_inputidproof = strtotime($date).rand(0,100);
		$outputidproof = getcwd()."/uploads/idproof/non_mem_idproof_".$tmp_inputidproof.".jpg";
		$outputidproof1 = base_url()."uploads/idproof/non_mem_idproof_".$tmp_inputidproof.".jpg";
		file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
		
		$dob1= $_POST["dob1"];
		$dob = str_replace('/','-',$dob1);
		$dateOfBirth = date('Y-m-d',strtotime($dob));
		
		$user_data=array(	'firstname'			=>$_POST["firstname"],
									'sel_namesub'		=>$_POST["sel_namesub"],
									'addressline1'		=>$_POST["addressline1"],
									'addressline2'		=>$_POST["addressline2"],
									'addressline3'		=>$_POST["addressline3"],
									'addressline4'		=>$_POST["addressline4"],
									'city'					=>$_POST["city"],	
									'code'					=>trim($_POST["code"]),
									'district'				=>$_POST["district"],	
									'dob'						=>$dateOfBirth,
									'eduqual'				=>$_POST["eduqual"],	
									'eduqual1'				=>$eduqual1,	
									'eduqual2'				=>$eduqual2,	
									'eduqual3'				=>$eduqual3,	
									'email'					=>$_POST["email"],	
									'gender'				=>$_POST["gender"],	
									'idNo'					=>$_POST["idNo"],	
									'idproof'				=>$_POST["idproof"],	
									'lastname'				=>$_POST["lastname"],	
									'middlename'			=>$_POST["middlename"],	
									'mobile'					=>$_POST["mobile"],	
									'nameoncard'			=>$_POST["nameoncard"],	
									'optedu'				=>$_POST["optedu"],	
									'optnletter'			=>$_POST["optnletter"],	
									'phone'					=>$_POST["phone"],	
									'pincode'				=>$_POST["pincode"],	
									'state'					=>$_POST["state"],	
									'stdcode'				=>$_POST["stdcode"],
									'scannedphoto'		=>$outputphoto1,
									'scannedsignaturephoto'=>$outputsign1,
									'idproofphoto'		=>$outputidproof1,
									'photoname'			=>"non_mem_photo_".$tmp_nm.".jpg",
									'signname'				=>"non_mem_sign_".$tmp_signnm.".jpg",
									'idname'				=>"non_mem_idproof_".$tmp_inputidproof.".jpg",
									'selCenterName'	=>$_POST["selCenterName"],
									'txtCenterCode'		=>	$_POST["txtCenterCode"],
									'optmode'				=>$_POST["optmode"],
									'exid'					=>$_POST["exid"],
									'mtype'					=>$_POST["mtype"],
									'memtype'				=>$_POST["memtype"],
									'eprid'					=>$_POST["eprid"],
									'rrsub'					=>$_POST["rrsub"],
									'excd'					=>$_POST["excd"],
									'exname'				=>$_POST["exname"],
									'fee'						=>	$_POST["fee"],
									'medium'				=>$_POST['medium']);
		$this->session->set_userdata('enduserinfo',$user_data);
		//echo 'true';
		redirect(base_url().'Dbfuser/preview');
		//$data=array('middle_content'=>'preview_register');
		//$this->load->view('nm_common_view',$data);
		
	 } 
	 
	 //Preview of register form 
	 public function preview()
    {
		if(!$this->session->userdata('enduserinfo'))
		{
			redirect(base_url());
		}
  		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		$medium=$this->master_model->getRecords('medium_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd']));
		$center=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd']));
		$this->db->like('exam_code',$this->session->userdata['enduserinfo']['excd']);
		$exam_period=$this->master_model->getRecords('misc_master','','exam_period'); 
		//echo $this->db->last_query();exit;
		
		$data=array('middle_content'=>'dbf/dbf_mem_preview_register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'medium'=>$medium,'center'=>$center,'exam_period'=>$exam_period,'idtype_master'=>$idtype_master);
		$this->load->view('dbf/common_view_fullwidth',$data);
		
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
	 
	 //check mail alredy exist or not
	 public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$where="( registrationtype='DB')";
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
				$str='The entered email ID / mobile no already exist';
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
			$where="( registrationtype='DB')";
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
				$str='The entered email ID / mobile no already exist';
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
		//redirect(base_url().'Dbfuser/memlogin/?Extype=MQ==&Mtype=Tk0='); 
		redirect(base_url()); 
	}
	
	
	##---------forget password (prafull)-----------##
	public function forgotpassword()
	{
		$data['page_title']='Forget Password';
		$data['pass_error']=$data['error']='';
		if(isset($_POST['btn_forget_pass']))
		{
			$this->form_validation->set_rules('non_memno','Registration No.','trim|required|xss_clean');
			if($this->form_validation->run())
			{
				$non_memno=$this->input->post('non_memno');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$non_memno));
				if(count($result)>0)
				{
					//generate random password
					$password=$this->generate_random_password();
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					 $key = $this->config->item('pass_key');
					 $aes = new CryptAES();
					 $aes->set_key(base64_decode($key));
					 $aes->require_pkcs5();
					 $encPass = $aes->encrypt($password);
			
					// update a password in db
					$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass),array('regid'=>$result[0]['regid']));
					$log_arr=array('regnumber'=>$non_memno,'usrpassword'=>$encPass);
					logactivity($log_title ="Forgrt pass DB&F", $log_message = serialize($log_arr));
					if($query)
					{
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_forgetpass'));
							$newstring1 = str_replace("#application_num#", "".$result[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
							$newstring2= str_replace("#password#", "".$password."",  $newstring1);
							$newstring3= str_replace("#username#", "".$userfinalstrname."",  $newstring2);
							$final_str= str_replace("#url#", "".base_url()."",  $newstring3);
							
							$info_arr=array(
														'to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str
													);
													
							if($this->Emailsending->mailsend($info_arr))
							{
								//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								 redirect(base_url().'Dbfuser/forgetack/');
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								 redirect(base_url().'Dbfuser/');
							}					
						}
					}
				else
				{
					 $this->session->set_flashdata('error_message','Invalid Membership/Registration No!');
					 redirect(base_url().'Dbfuser/forgotpassword/');
				}
			}
		}
		$this->load->view('dbf/dbf_forgetpass',$data);
	}
	
	
	//### forget pass acknowledgment
	 public function forgetack()
	 {
		$this->load->view('nonmember/foergetpass_ack');	
	}
	
	

}
