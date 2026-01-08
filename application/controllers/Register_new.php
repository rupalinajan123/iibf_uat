<?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class Register_new extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('Master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		//$this->load->model('chk_session');
	  	//$this->chk_session->checklogin();
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
	 
	
	 
	 
	public function member()
	{
		//echo $port = $_SERVER['SERVER_PORT'];
		//exit;
		//redirect ("uc.html");
		//exit;
		$flag=1;
		$valcookie= register_get_cookie();
		if($valcookie)
		{
			$regid= $valcookie;
			//$regid= '57';
			$checkuser=$this->master_model->getRecords('member_registration',array('regid'=>$regid,'regnumber !='=>'','isactive !='=>'0'));
			if(count($checkuser)>0)
			{
				delete_cookie('regid');
			}
			else
			{
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('ref_id'=>$regid,'status'=>'2'));
				if(count($checkpayment) > 0)
				{
					///$datearr=explode(' ',$checkpayment[0]['date']);
					$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
					$current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$flag=0;
					}
					else
					{
						delete_cookie('regid');
					}
				}
				else
				{
					$flag=1;
					delete_cookie('regid');
				}
			}	
		}
		
		if($this->session->userdata('enduserinfo'))
		{
			$this->session->unset_userdata('enduserinfo');
		}
		$scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password=$var_errors='';
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
				
				if(isset($_POST['middlename']))
				{
					$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
				}
				if(isset($_POST['lastname']))
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
				
				$this->form_validation->set_rules('institutionworking','Bank/Institution working','trim|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('office','Branch/Office','trim|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('designation','Designation','trim|required|xss_clean');
				$this->form_validation->set_rules('doj1','Date of joining Bank/Institution','trim|required|xss_clean');
				$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
				$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
				$this->form_validation->set_rules('scannedphoto','scanned Photograph','callback_scannedphoto_upload');
				$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','callback_scannedsignaturephoto_upload');
				$this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');
				$this->form_validation->set_rules('idNo','ID No','trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('idproofphoto','Id proof','callback_idproofphoto_upload');
				$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
				
				$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
				
				
				if($this->form_validation->run()==TRUE)
				{
						
					$outputphoto1=$outputsign1=$outputsign1='';
					$scannedphoto_file = '';
					$scannedsignaturephoto_file = '';
					$idproof_file = '';
					
					// ajax response -
					$resp = array('success' => 0, 'error' => 0, 'msg' => '');
					
					/*$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
					$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
					$this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|required|xss_clean');
					$this->form_validation->set_rules('addressline1', 'Address line1', 'trim|required|xss_clean');
					$this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean');
					$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
					$this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
					$this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|xss_clean');
					$this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
					$this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
					$this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
					//$this->form_validation->set_rules('eduqual', 'Please specify', 'trim|required|xss_clean');
					$this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|required|xss_clean');
					$this->form_validation->set_rules('office', 'Branch/Office', 'trim|required|xss_clean');
					$this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
					$this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required|xss_clean');
					$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
					$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean');
					$this->form_validation->set_rules('idproof', 'Select Id Proof', 'trim|required|xss_clean');
					$this->form_validation->set_rules('idNo', 'ID No.', 'trim|required|xss_clean');
					$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
					
					// check if form validation fail -
					if($this->form_validation->run() == FALSE)
					{
						$var_errors = validation_errors();
						$var_errors = str_replace("<p>", "<span>", $var_errors);
						$var_errors = str_replace("</p>", "</span><br>", $var_errors);
						
						$resp = array('success' => 0, 'error' => 1, 'msg' => $var_errors);
						print json_encode($resp);
						die;
					}*/
					
					$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputsign1='';
					$this->session->unset_userdata('enduserinfo');
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
					/*if(isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name']!='')
					{
						$tmp_nm = strtotime($date).rand(0,100);
						$new_filename = 'photo_'.$tmp_nm;
						$photopath = "./uploads/photograph";
						$uploadData = upload_file('scannedphoto', $photopath, $new_filename,'','',TRUE);
						if($uploadData)
						{
							$scannedphoto_file = $uploadData['file_name'];
							$outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
						}
					}*/
					
					if(isset($_FILES['scannedphoto']['name']) &&($_FILES['scannedphoto']['name']!=''))
					{
							$img = "scannedphoto";
							$tmp_nm = strtotime($date).rand(0,100);
							$new_filename = 'photo_'.$tmp_nm;
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
					
					/*if(isset($_FILES['scannedsignaturephoto']['name']) && $_FILES['scannedsignaturephoto']['name']!='')
					{
						$tmp_signnm = strtotime($date).rand(0,100);
						$signaturepath = "./uploads/scansignature";
						$new_filename = 'sign_'.$tmp_signnm;
						$uploadData = upload_file('scannedsignaturephoto', $signaturepath, $new_filename,'','',TRUE);
						if($uploadData)
						{
							$scannedsignaturephoto_file = $uploadData['file_name'];
							$outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
						}
					}*/
					
					if(isset($_FILES['scannedsignaturephoto']['name']) &&($_FILES['scannedsignaturephoto']['name']!=''))
					{
							$img = "scannedsignaturephoto";
							$tmp_signnm = strtotime($date).rand(0,100);
							$new_filename = 'sign_'.$tmp_signnm;
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
					/*if(isset($_FILES['idproofphoto']['name']) && $_FILES['idproofphoto']['name']!='')
					{
						$tmp_inputidproof = strtotime($date).rand(0,100);
						$idproofpath = "./uploads/idproof";
						$new_filename = 'idproof_'.$tmp_inputidproof;
						$uploadData = upload_file('idproofphoto', $idproofpath, $new_filename,'','',TRUE);
						if($uploadData)
						{
							$idproof_file = $uploadData['file_name'];
							$outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
						}
					}*/
					
					
					if(isset($_FILES['idproofphoto']['name']) &&($_FILES['idproofphoto']['name']!=''))
					{
							$img = "idproofphoto";
							$tmp_inputidproof = strtotime($date).rand(0,100);
							$new_filename = 'idproof_'.$tmp_inputidproof;
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
					
					$doj1= $_POST["doj1"];
					$doj = str_replace('/','-',$doj1);
					$dateOfJoin = date('Y-m-d',strtotime($doj));	
					
					
					if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
					{
							$user_data=array('firstname'=>$_POST["firstname"],
							'sel_namesub'=>$_POST["sel_namesub"],
							'addressline1'=>$_POST["addressline1"],
							'addressline2'=>$_POST["addressline2"],
							'addressline3'=>$_POST["addressline3"],
							'addressline4'=>$_POST["addressline4"],
							'city'=>$_POST["city"],	
							'code'=>trim($_POST["code"]),
							'designation'=>$_POST["designation"],	
							'district'=>substr($_POST["district"],0,30),	
							'dob'	=>$dateOfBirth,
							'doj'=>$dateOfJoin,	
							'eduqual'=>$_POST["eduqual"],	
							'eduqual1'=>$eduqual1,	
							'eduqual2'=>$eduqual2,	
							'eduqual3'=>$eduqual3,	
							'email'=>$_POST["email"],	
							'gender'=>$_POST["gender"],	
							'idNo'=>$_POST["idNo"],	
							'idproof'=>$_POST["idproof"],	
							'institution'=>trim($_POST["institutionworking"]),
							'lastname'=>$_POST["lastname"],	
							'middlename'=>$_POST["middlename"],	
							'mobile'=>$_POST["mobile"],	
							'nameoncard'=>$_POST["nameoncard"],	
							'office'=>$_POST["office"],	
							'optedu'=>$_POST["optedu"],	
							'optnletter'=>$_POST["optnletter"],	
							'phone'=>$_POST["phone"],	
							'pincode'=>$_POST["pincode"],	
							'state'=>$_POST["state"],	
							'stdcode'=>$_POST["stdcode"],
							'scannedphoto'=>$outputphoto1,
							'scannedsignaturephoto'=>$outputsign1,
							'idproofphoto'=>$outputidproof1,
							'photoname'=>$scannedphoto_file ,
							'signname'=>$scannedsignaturephoto_file ,
							'idname'=>$idproof_file );
							$this->session->set_userdata('enduserinfo',$user_data);
							
							$this->form_validation->set_message('error', "");
							redirect(base_url().'register/preview');
					}
					else
					{
						$var_errors = str_replace("<p>", "<span>", $var_errors);
						$var_errors = str_replace("</p>", "</span><br>", $var_errors);
					}
				}
		}
		
		 
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		
		$this->db->where('institution_master.institution_delete','0');
		$institution_master=$this->master_model->getRecords('institution_master','','',array('name'=>'asc'));
		
		$this->db->where('state_master.state_delete','0');
		$states=$this->master_model->getRecords('state_master');
		
		$this->db->where('designation_master.designation_delete','0');
		$designation=$this->master_model->getRecords('designation_master');
		
		$this->db->not_like('name','college');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		$this->load->helper('captcha');
		//$this->session->unset_userdata("regcaptcha");
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$_SESSION["regcaptcha"] = $cap['word'];
		
		//$calendar = get_calendar_input(); 
		if($flag==0)
		{
			$data=array('middle_content'=>'cookie_msg');
			$this->load->view('common_view_fullwidth',$data);
		}
		else
		{
			$data=array('middle_content'=>'register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'image' => $cap['image'],'idtype_master'=>$idtype_master,'var_errors'=>$var_errors);
			$this->load->view('common_view_fullwidth',$data);
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
	
	//call back for e-mail duplication
	 public function check_emailduplication($email)
	{
		if($email!="")
		{
			$where="( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				return true;	
			}
			else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered email ID already exist for membership / registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				
				$this->form_validation->set_message('check_emailduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	//call back for mobile duplication
	 public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
			$where="( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				return true;
				}
			else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered  mobile no already exist for membership / registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				$this->form_validation->set_message('check_mobileduplication', $str); 
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
	
	
	
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
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
	
		
	public function date_view()
	{
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		
		$this->db->where('institution_master.institution_delete','0');
		$institution_master=$this->master_model->getRecords('institution_master');
		
		$this->db->where('state_master.state_delete','0');
		$states=$this->master_model->getRecords('state_master');
		
		$this->db->where('designation_master.designation_delete','0');
		$designation=$this->master_model->getRecords('designation_master');
		
		$this->db->not_like('name','college');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		
		
		$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('regcaptcha', $cap['word']);
		
	
		$calendar = get_calendar_input();
		//print_r($calendar);
		//exit; 
			
		$data=array('middle_content'=>'register1','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'image' => $cap['image'],'idtype_master'=>$idtype_master,'calendar'=>$calendar);
		$this->load->view('common_view_fullwidth',$data);
	}
	
	public function addmember()
	{
		if(!$this->session->userdata['enduserinfo'])
		{
			redirect(base_url());
		}
		//$last_id=$this->master_model->getRecords('member_registration','','regid',array('regid'=>'DESC'),'',1);
		/*if(count($last_id) > 0)
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
		// $encPass = $aes->encrypt($pass);
		// $decData = $aes->decrypt($encPass);
		 //if(isset($_POST['btnSubmit']))  	
		 //{
		 
			$scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];
			$scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];
			$idproofphoto_file = $this->session->userdata['enduserinfo']['idname'];
			$sel_namesub = $this->session->userdata['enduserinfo']['sel_namesub'];
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
			$state= $this->session->userdata['enduserinfo']['state'];
			$pincode= $this->session->userdata['enduserinfo']['pincode'];
			$dob= $this->session->userdata['enduserinfo']['dob'];
			$gender =$this->session->userdata['enduserinfo']['gender'];
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
			$institutionworking = $this->session->userdata['enduserinfo']['institution'];
			$office = strtoupper($this->session->userdata['enduserinfo']['office']);
			$designation = $this->session->userdata['enduserinfo']['designation'];
			$doj = $this->session->userdata['enduserinfo']['doj'];
			$email = $this->session->userdata['enduserinfo']['email'];
			$stdcode = $this->session->userdata['enduserinfo']['stdcode'];
			$phone = $this->session->userdata['enduserinfo']['phone'];
			$mobile = $this->session->userdata['enduserinfo']['mobile'];
			$idproof = $this->session->userdata['enduserinfo']['idproof'];
			$idNo = $this->session->userdata['enduserinfo']['idNo'];
			$optnletter = $this->session->userdata['enduserinfo']['optnletter'];
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
											'dateofbirth'=>date('Y-m-d',strtotime($dob)),
											'gender'=>$gender,
											'qualification'=>$optedu,
											'specify_qualification'=>$specify_qualification,
											'associatedinstitute'=>$institutionworking,
											'office'=>$office,
											'designation'=>$designation,
											'dateofjoin'=>date('Y-m-d',strtotime($doj)),
											'email'=>$email,
											'registrationtype'=>'O',
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
											'createdon'=>date('Y-m-d H:i:s')
											);			
										
			if($last_id = $this->master_model->insertRecord('member_registration',$insert_info,true))
			{
				// Renaming the previously uploaded file with Reg Num inserted in database
				$upd_files = array();
				/*$photo_file = 'p_'.$last_id.'.jpg';
				$sign_file = 's_'.$last_id.'.jpg';
				$proof_file = 'pr_'.$last_id.'.jpg';
				
				if(@ rename("./uploads/photograph/".$scannedphoto_file,"./uploads/photograph/".$photo_file))
				{	$upd_files['scannedphoto'] = $photo_file;	}
				
				if(@ rename("./uploads/scansignature/".$scannedsignaturephoto_file,"./uploads/scansignature/".$sign_file))
				{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
				
				if(@ rename("./uploads/idproof/".$idproofphoto_file,"./uploads/idproof/".$proof_file))
				{	$upd_files['idproofphoto'] = $proof_file;	}
				
				if(count($upd_files)>0)
				{
					$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$last_id));
				}*/
				logactivity($log_title ="Member user registration ", $log_message = serialize($insert_info));
				$userarr=array('regno'=>$last_id,
										'password'=>$password,
										'email'=>$email);
				$this->session->set_userdata('memberdata', $userarr); 
				
				redirect(base_url()."Register/make_payment");
				
			}
			else
			{
				$userarr=array('regno'=>'',
										'password'=>'',
										'email'=>'');
				$this->session->set_userdata('memberdata', $userarr); 
				//$this->make_payment();
				$this->session->set_flashdata('error','Error while during registration.please try again!');
				redirect(base_url());
			}
		
	 
	 //}
		
		
	}
	//validate captcha
	##---------check captcha userlogin (prafull)-----------##
	public function ajax_check_captcha()
	{
		$code=$_POST['code'];
		// check if captcha is set -
		if ($code == '' || $_SESSION["regcaptcha"] != $code)
		{
			$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
			//$this->session->set_userdata("regcaptcha", rand(1, 100000));
			echo  'false';
		}
		else if ($_SESSION["regcaptcha"] == $code)
		{
			//$this->session->unset_userdata("regcaptcha");
			// $this->session->set_userdata("mycaptcha", rand(1,100000));
			echo 'true';
		}
	}
	
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("regcaptcha");
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["regcaptcha"] = $cap['word'];
		echo $data;
	}
	
	
	//Thank you message to end user
	public function acknowledge()
	{
		
		$data=array();
		if($this->session->userdata('memberdata')=='')
		{
			redirect(base_url());
		}
		if($this->session->userdata('enduserinfo'))
		{
			$this->session->unset_userdata('enduserinfo');
		}
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata['memberdata']['regno']),'regnumber');
		$data=array('middle_content'=>'thankyou','application_number','application_number'=>$user_info[0]['regnumber'],
		'password'=>$this->session->userdata['memberdata']['password']);
		$this->load->view('common_view',$data);
	}
	
	
	//print user Register profile (Prafull)
	public function printUser()
	{
		
		
			$qualification=array();
			$this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata['memberdata']['regno'],'isactive'=>'1'));
			if(count($user_info) <= 0)
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
			$data=array('middle_content'=>'print_member_profile','user_info'=>$user_info,'qualification'=>$qualification,'idtype_master'=>$idtype_master);
			$this->load->view('common_view',$data);
	}
	
	//Download pdf(Prafull)
	public function pdf()
	{
			if(!$this->session->userdata('memberdata'))
			{
				redirect(base_url());
			}
			$qualification=array();
			$this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata['memberdata']['regno'],'isactive'=>'1'));
			
			if(count($user_info) <= 0)
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
				$user_info[0]['address2']=$user_info[0]['address2'].'<br>';
			}
			if($user_info[0]['address3']!='')
			{
				$user_info[0]['address3']=$user_info[0]['address3'].'<br>';
			}
			if($user_info[0]['address4']!='')
			{
				$user_info[0]['address4']=$user_info[0]['address4'].'<br>';
			}
							   $useradd=$user_info[0]['address1'].$user_info[0]['address2'].$user_info[0]['address3'].$user_info[0]['address4'].','.$user_info[0]['district'].','.$user_info[0]['city'].','.$user_info[0]['state_name'].$user_info[0]['pincode'];
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
			<td colspan="2" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['displayname'].'</td>
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
			<td class="tablecontent2">Bank/Institution working :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['name'].' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Branch/Office :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['office'].' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Designation :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'. $user_info[0]['dname'].' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Date of Joining :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.date('d-m-Y',strtotime($user_info[0]['dateofjoin'])).' </td>
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
			<td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> '.$optnletter.' </td>
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
	
	
/*	public function appNo()
	{
		$last_id=$this->master_model->getRecords('personal_info','','id',array('id'=>'DESC'),'',1);
		if(count($last_id) > 0)
		{
			$last_count = $last_id[0]['id']; 
			$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
			$timeStr = date('Ymds');
			echo $applicationId = $timeStr.'-'.$last_count;
		}	
	}*/
	
	
	
	/*public function setsession()
    {
		$outputphoto1=$outputsign1=$outputsign1='';
		
		$scannedphoto_file = '';
		$scannedsignaturephoto_file = '';
		$idproof_file = '';
		
		// ajax response -
		$resp = array('success' => 0, 'error' => 0, 'msg' => '');
		
		//$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('addressline1', 'Address line1', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
//		//$this->form_validation->set_rules('eduqual', 'Please specify', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('office', 'Branch/Office', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('idproof', 'Select Id Proof', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('idNo', 'ID No.', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
		
		// check if form validation fail -
		if($this->form_validation->run() == FALSE)
		{
			$var_errors = validation_errors();
			$var_errors = str_replace("<p>", "<span>", $var_errors);
			$var_errors = str_replace("</p>", "</span><br>", $var_errors);
			
			$resp = array('success' => 0, 'error' => 1, 'msg' => $var_errors);
			print json_encode($resp);
			die;
		}
		
		$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputsign1='';
		$this->session->unset_userdata('enduserinfo');
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
		
		
		//$text =  ($_POST["photo"]	);				
//		//$text = str_replace("data:image/jpeg;base64,","",$text);					
//		$tmp_nm = rand(0,100);
//		$name = "/home/tgdemo/public_html/tgpublic/iibf/uploads/photo_".$tmp_nm.".jpg";
//		$name1 = "http://demo.teamgrowth.net/tgpublic/iibf/uploads/photo_".$tmp_nm.".jpg";
//		file_put_contents($name, $text);
		
		
		$date=date('Y-m-d h:i:s');
		
		//Generate dynamic photo
		$input = $_POST["hiddenphoto"];
		
		if(isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name']!='')
		{
			$tmp_nm = strtotime($date).rand(0,100);
			$new_filename = 'photo_'.$tmp_nm;
			$photopath = "./uploads/photograph";
			$uploadData = upload_file('scannedphoto', $photopath, $new_filename,'','',TRUE);
			if($uploadData)
			{
				$scannedphoto_file = $uploadData['file_name'];
				$outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
			}
		}
		
		//$tmp_nm = strtotime($date).rand(0,100);
//		$outputphoto = getcwd()."/uploads/photograph/photo_".$tmp_nm.".jpg";
//		$outputphoto1 = base_url()."uploads/photograph/photo_".$tmp_nm.".jpg";
//		file_put_contents($outputphoto, file_get_contents($input));
		
			
		
		
		//file_get_contents() function required in staggin/tgpublic server do not remove from 
		//file_put_contents($outputphoto, ($input));
		
		// generate dynamic scan signature
		$inputsignature = $_POST["hiddenscansignature"];
		
		if(isset($_FILES['scannedsignaturephoto']['name']) && $_FILES['scannedsignaturephoto']['name']!='')
		{
			$tmp_signnm = strtotime($date).rand(0,100);
			$signaturepath = "./uploads/scansignature";
			$new_filename = 'sign_'.$tmp_signnm;
			$uploadData = upload_file('scannedsignaturephoto', $signaturepath, $new_filename,'','',TRUE);
			if($uploadData)
			{
				$scannedsignaturephoto_file = $uploadData['file_name'];
				$outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
			}
		}
		
		//$tmp_signnm = strtotime($date).rand(0,100);
//		$outputsign = getcwd()."/uploads/scansignature/sign_".$tmp_signnm.".jpg";
//		$outputsign1 = base_url()."uploads/scansignature/sign_".$tmp_signnm.".jpg";
//		file_put_contents($outputsign, file_get_contents($inputsignature));
		
		// generate dynamic id proof
		$inputidproofphoto = $_POST["hiddenidproofphoto"];
		if(isset($_FILES['idproofphoto']['name']) && $_FILES['idproofphoto']['name']!='')
		{
			$tmp_inputidproof = strtotime($date).rand(0,100);
			$idproofpath = "./uploads/idproof";
			$new_filename = 'idproof_'.$tmp_inputidproof;
			$uploadData = upload_file('idproofphoto', $idproofpath, $new_filename,'','',TRUE);
			if($uploadData)
			{
				$idproof_file = $uploadData['file_name'];
				$outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
			}
		}
		
		//$tmp_inputidproof = strtotime($date).rand(0,100);
//		$outputidproof = getcwd()."/uploads/idproof/idproof_".$tmp_inputidproof.".jpg";
//		$outputidproof1 = base_url()."uploads/idproof/idproof_".$tmp_inputidproof.".jpg";
//		file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
	
		
		$dob1= $_POST["dob1"];
		$dob = str_replace('/','-',$dob1);
		$dateOfBirth = date('Y-m-d',strtotime($dob));		
		
		$doj1= $_POST["doj1"];
		$doj = str_replace('/','-',$doj1);
		$dateOfJoin = date('Y-m-d',strtotime($doj));	
		
		
		if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
		{
		$user_data=array('firstname'=>substr($_POST["firstname"],0,30),
									'sel_namesub'=>$_POST["sel_namesub"],
									'addressline1'=>substr($_POST["addressline1"],0,30),
									'addressline2'=>substr($_POST["addressline2"],0,30),
									'addressline3'=>substr($_POST["addressline3"],0,30),
									'addressline4'=>substr($_POST["addressline4"],0,30),
									'city'=>substr($_POST["city"],0,30),	
									'code'=>trim($_POST["code"]),
									'designation'=>$_POST["designation"],	
									'district'=>substr($_POST["district"],0,30),	
									'dob'	=>$dateOfBirth,
									'doj'=>$dateOfJoin,	
									'eduqual'=>$_POST["eduqual"],	
									'eduqual1'=>$eduqual1,	
									'eduqual2'=>$eduqual2,	
									'eduqual3'=>$eduqual3,	
									'email'=>$_POST["email"],	
									'gender'=>$_POST["gender"],	
									'idNo'=>$_POST["idNo"],	
									'idproof'=>$_POST["idproof"],	
									'institution'=>trim($_POST["institutionworking"]),
									'lastname'=>$_POST["lastname"],	
									'middlename'=>$_POST["middlename"],	
									'mobile'=>$_POST["mobile"],	
									'nameoncard'=>substr($_POST["nameoncard"],0,35),	
									'office'=>$_POST["office"],	
									'optedu'=>$_POST["optedu"],	
									'optnletter'=>$_POST["optnletter"],	
									'phone'=>$_POST["phone"],	
									'pincode'=>$_POST["pincode"],	
									'state'=>$_POST["state"],	
									'stdcode'=>$_POST["stdcode"],
									'scannedphoto'=>$outputphoto1,
									'scannedsignaturephoto'=>$outputsign1,
									'idproofphoto'=>$outputidproof1,
									'photoname'=>$scannedphoto_file ,
									'signname'=>$scannedsignaturephoto_file ,
									'idname'=>$idproof_file );
		$this->session->set_userdata('enduserinfo',$user_data);
		redirect(base_url().'register/preview');
		echo true;
		}
		else
		{
			echo false;
			
			//$resp = array('success' => 0, 'error' => 1, 'msg' => '<span>File(s) Upload Error.</span>');
			//print json_encode($resp);
			//die;
		}
		
		//return 'true';
		//$data=array('middle_content'=>'preview_register');
		//$this->load->view('common_view',$data);
		
	 } */
	 
	 
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
		$data=array('middle_content'=>'preview_register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'idtype_master'=>$idtype_master);
		$this->load->view('common_view_fullwidth',$data);
		
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
	 
	##---------check mail alredy exist or not (prafull)-----------##
	 public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$where="( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);}
			else
			{
				$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered email ID and mobile no already exist for membership / registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
				
			}
		}
		else
		{
			echo 'error';
		}
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
	
	##---------check mobile nnnumber alredy exist or not (prafull)-----------##
	 public function mobileduplication()
	{
		$mobile=$_POST['mobile'];
		if($mobile!="")
		{
			$where="( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
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
				$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered email ID and mobile no already exist for membership / registration number '.$user_info[0]['regnumber'].' , '.$userfinalstrname.'';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	
    
	public function make_payment() {
		
		// TO do:
		// Validate reg no in DB
		//$_REQUEST['regno'] = "ODExODU5OTE1";
		//$regno = base64_decode($_REQUEST['regno']);
		$regno = $this->session->userdata['memberdata']['regno'];
		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			//setting cookie for tracking multiple payment scenario
			register_set_cookie($regno);
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Register/sbitranssuccess";
			$pg_fail_url    = base_url()."Register/sbitransfail";
			
			//$amount = $this->config->item('member_reg_fee');
			$amount = '1';
			
			$MerchantOrderNo = generate_order_id("reg_sbi_order_id");
			
			 //Member registration
			 //Ref1 = primary key of member registation table
			 //Ref2 = iibfregn
			 //Ref3 = primary key of member registation table
			 //Ref4 = orderid  For below string
			$custom_field = $regno."^iibfregn^".$regno."^".$MerchantOrderNo;
			// Create transaction
			$insert_data = array(
				'gateway'     => "sbiepay",
				'amount'      => $amount,
				'date'        => date('Y-m-d H:i:s'),
				'ref_id'	  =>  $regno,	
				'description' => "Membership Registration",
				'pay_type'    => 1,
				'status'      => 2,
				'receipt_no'  => $MerchantOrderNo,
				
				'pg_flag'=>'iibfregn',
				
				'pg_other_details'=>$custom_field
			);
		
			$pt_id = $this->master_model->insertRecord('payment_transaction',$insert_data,true);
			$MerchantCustomerID = $regno;
			
		
			
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
			//$data["regno"] = $_REQUEST['regno'];
			$this->load->view('pg_sbi/make_payment_page');
		}
	}
	
	public function sbitranssuccess()
	{
		delete_cookie('regid');
		if (isset($_REQUEST['encData']))
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
					$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
					//check user payment status is updated by s2s or not
					if($get_user_regnum_info[0]['status']==2)
					{
						$reg_id=$get_user_regnum_info[0]['ref_id'];
						//$applicationNo = generate_mem_reg_num();
						$applicationNo =generate_O_memreg($reg_id);
						
					
					$update_data = array('member_regnumber' => $applicationNo,'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					if(count($get_user_regnum_info) > 0)
					{
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
					
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
					
					
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					
					if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0)
					{
					$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
					}
				}
					
					//email to user
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
					if(count($emailerstr) > 0)
					{
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
					$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
					//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
					$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
					$final_str= str_replace("#password#", "".$decpass."",  $newstring);
					$info_arr=array('to'=>$user_info[0]['email'],
					'from'=>$emailerstr[0]['from'],
					'subject'=>$emailerstr[0]['subject'],
					'message'=>$final_str
					);
					
					$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
					$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
					$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
					
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				
					if($this->Emailsending->mailsend($info_arr))
					{
						redirect(base_url().'Register/acknowledge/');
					}
					else
					{
						redirect(base_url().'Register/acknowledge/');
					}
					}
					else
					{
						redirect(base_url().'Register/acknowledge/');
					}
					}
				}
			}
			///End of SBI B2B callback 
			redirect(base_url().'Register/acknowledge/');
			
			}
			else
			{
				die("Please try again...");
			}
	}
	
	public function sbitransfail()
	{
		delete_cookie('regid');
		if (isset($_REQUEST['encData']))
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
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//Sbi fail code without callback
			echo "Transaction failed";
				/*	$this->load->model('log_model');
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$merchIdVal = $_REQUEST['merchIdVal'];
			$Bank_Code = $_REQUEST['Bank_Code'];
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no  = $responsedata[1];*/
			
			//SBI Callback Code
			/*$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5]);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
		//END of SBI Callback code			
		
			//print_r($responsedata);  // Payment gateway response
			// TO DO : Redirect to user acknowledge page
			
		}
		else
		{
			die("Please try again...");
		}
	}
	/* Display list of exams for members */
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
		$this->load->view('examlist',$data);
	}
}
