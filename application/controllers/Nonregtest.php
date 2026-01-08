<?php

defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

class Nonregtest extends CI_Controller {

	

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

		//$this->load->model('chk_session');

	    //$this->chk_session->chk_member_session();

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

	public function memlogin()

	{ //exit;

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

						redirect(base_url().'NonMember/showexam/?ExId='.$this->input->get('ExId').'&Extype='.$Extype);

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

		

		/* $this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('nonmemlogincaptcha', $cap['word']); */
		
		$this->load->model('Captcha_model');
		$data['image'] = $this->Captcha_model->generate_captcha_img('nonmemlogincaptcha');

		$this->load->view('nonmember/nonmember_login',$data);



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

		$examcodes=array('528','529','991');

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

		 //echo $this->db->last_query();exit;

	



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

		$this->load->view('nonmember/examlist',$data);

	}

	 

	 

	 public function accessdenied()

	{

			$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';

			$data=array('middle_content'=>'nonmember/access-denied-registration','check_eligibility'=>$message);

			$this->load->view('nonmember/common_view_fullwidth',$data);

	}

	

	public function member()

	{

		//accedd denied due to GST

		//$this->master_model->warning();

		/* $valcookie= applyexam_get_cookie();

		if($valcookie)

		{

			delete_cookie('examid');

		} */
		## code added by chaitali on 2021-10-18
		if($this->session->userdata('enduserinfo'))
		{
			$this->session->unset_userdata('enduserinfo');
		}
		$flag=1;
		$valcookie= applyexam_get_cookie();
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
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regid,'status'=>'2'));
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
		$scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password=$var_errors='';



		$scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';



		$data['validation_errors'] = '';

		$ExId = base64_decode($this->input->get('ExId'));

		$Mtype = base64_decode($this->input->get('Mtype'));

		$ignore_exam_code = array(33,47,51,52);

		if(in_array($ExId,$ignore_exam_code))

		{
			echo '1'; exit;
			redirect(base_url().'Nonregtest/accessdenied?Mtype='.$this->input->get('Mtype').'&ExId='.$this->input->get('ExId'));

		}

		

		//check exam activation

		$check_exam_activation=check_exam_activate($ExId);

		if($check_exam_activation==0)

		{
			echo '2'; exit;
			redirect(base_url().'Nonregtest/accessdenied?Mtype='.$this->input->get('Mtype').'&ExId='.$this->input->get('ExId'));

		}

		$flag=1;

		 if(isset($_POST['btnSubmit']))  	

		 {

		 //echo '<pre>',print_r($_POST),'</pre>';exit;

			    $scribe_flag='N';

				$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';



				// Benchmark Disability	

				$output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';

			



				$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');

				$this->form_validation->set_rules('firstname','First Name','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');

				$this->form_validation->set_rules('addressline1','Addressline1','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');

				$this->form_validation->set_rules('district','District','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');

				$this->form_validation->set_rules('city','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');

				$this->form_validation->set_rules('state','State','trim|required|xss_clean');

				

				if($this->input->post('state')!='')

				{

					$state=$this->input->post('state');

				}

				$examcode=base64_decode($this->input->get('ExId'));

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

					$this->form_validation->set_rules('eduqual1','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');

				}

				else if(isset($_POST['optedu']) && $_POST['optedu']=='G')

				{

					$this->form_validation->set_rules('eduqual2','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');

				}

				else if(isset($_POST['optedu']) && $_POST['optedu']=='P')

				{

					$this->form_validation->set_rules('eduqual3','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');

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

				if(base64_decode($this->input->get('ExId'))!=101)

				{

					$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');

					$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');

					$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');

				}

				$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');

				

				$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

				$this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');

				$this->form_validation->set_rules('idNo','ID No','trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');

				

				if(base64_decode($this->input->get('ExId'))!=101 || $this->input->post('aadhar_card') != '' )

				{

					if($this->input->post('aadhar_card')!='')

					{

					//$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required|max_length[12]|numeric|xss_clean|callback_check_aadhar');

					//$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|numeric|xss_clean|callback_check_aadhar');

					$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');

					

					}

				}

				$this->form_validation->set_rules('idproofphoto','Id proof','file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');

				$this->form_validation->set_rules('medium','Medium','required|xss_clean');

				$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');

				$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');

				$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');

				$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');

				/*if($this->input->post('gstin_no'))

				{

					$this->form_validation->set_rules('gstin_no', 'Bank GSTIN Number', 'trim|alpha_numeric|min_length[15]|xss_clean');

				}*/

				

			 	/* Benchmark Disability Validations */

				if(isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y'){

					$this->form_validation->set_rules('scanned_vis_imp_cert','Visually impaired Attach scan copy of PWD certificate','required');

				}

				if(isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y'){

					$this->form_validation->set_rules('scanned_orth_han_cert','Orthopedically handicapped Attach scan copy of PWD certificate','required');

				}

				if(isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y'){

					$this->form_validation->set_rules('scanned_cer_palsy_cert','Cerebral palsy Attach scan copy of PWD certificate','required');

				}



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

					$subject_arr=array();

					$venue=$this->input->post('venue');

					$date=$this->input->post('date');

					$time=$this->input->post('time');

					

					/* Benchmark Disability */

					$output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';



					//print_r($this->input->post('venue'));

					########### get POST data of subject ##############

					if(count($venue) >0 && count($date) && count($time) >0)	

					{

						foreach($venue as $k=>$v)

						{

							$this->db->join('exam_activation_master','exam_activation_master.exam_code=subject_master.exam_code AND exam_activation_master.exam_period=subject_master.exam_period');

							$this->db->group_by('subject_code');

							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$ExId,'subject_delete'=>'0','group_code'=>'C','subject_code'=>$k),'subject_description');

						

							$subject_arr[$k]=array('venue'=>$v,'date'=>$date[$k],'session_time'=>$time[$k],'subject_name'=>$compulsory_subjects_name[0]['subject_description']);

						}

					

						#########check duplication of venue,date,time##########		

						if(count($subject_arr) > 0)

						{	

							$msg='';

							$sub_flag=1;

							$sub_capacity=1;

							foreach($subject_arr as $k=>$v)

							{

									foreach($subject_arr as $j=>$val)

									{

										if($k!=$j)

										{

											//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])

											if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])

											{

												$sub_flag=0;

											}

										}

									}

								 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);

								if($capacity==0)

								{

									#########get message if capacity is full##########

									$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);

								}

								if($msg!='')

								{

									$this->session->set_flashdata('error',$msg);

									redirect(base_url().'Nonregtest/member/?Mtype='.$this->input->get('Mtype').'=&ExId='.$this->input->get('ExId').'');

								}

							}

						}

						if($sub_flag==0)

						{

							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');

							redirect(base_url().'Nonregtest/member/?Mtype='.$this->input->get('Mtype').'=&ExId='.$this->input->get('ExId').'');

						}

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

																	  'allowed_types'=>'jpg|jpeg',

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

								$this->session->set_flashdata('error','Scanned Photograph :'.$this->upload->display_errors());

								//$var_errors.=$this->upload->display_errors();

								//$data['error']=$this->upload->display_errors();

						}

						}

						else

						{

								$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');

								//$var_errors.='The filetype you are attempting to upload is not allowed';

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

								//echo $this->upload->display_errors();;exit;

								$this->session->set_flashdata('error','Scanned Signature :'.$this->upload->display_errors());

								//	$var_errors.=$this->upload->display_errors();

								//$data['error']=$this->upload->display_errors();

						}

						}

						else

						{

								$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');

								//$var_errors.='The filetype you are attempting to upload is not allowed';

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

							if($this->upload->do_upload($img))

							{

								  $dt=$this->upload->data();

								  $idproof_file=$dt['file_name'];

								  $outputidproof1 = base_url()."uploads/idproof/".$idproof_file;

							}

							else

							{

									$this->session->set_flashdata('error','Id proof :'.$this->upload->display_errors());

									//$var_errors.=$this->upload->display_errors();

									//$data['error']=$this->upload->display_errors();

							}

							}

							else

							{

									$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');

									//$var_errors.='The filetype you are attempting to upload is not allowed';

							}

					}

					

				/* Benchmark Disability */

				/* Visually impaired certificate */

				$input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];

				if(isset($_FILES['scanned_vis_imp_cert']['name']) &&($_FILES['scanned_vis_imp_cert']['name']!=''))

				{

					$img = "scanned_vis_imp_cert";

					$tmp_nm = strtotime($date).rand(0,100);

					$new_filename = 'vis_imp_cert_'.$tmp_nm;

					$config=array('upload_path'=>'./uploads/disability',

							  'allowed_types'=>'jpg|jpeg|',

							  'file_name'=>$new_filename,);	  

					$this->upload->initialize($config);

					$size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);

					if($size){

						if($this->upload->do_upload($img)){

							  $dt=$this->upload->data();

							  $file=$dt['file_name'];

							 $scanned_vis_imp_cert_file = $dt['file_name'];

							 $output_vis_imp_cert1 = base_url()."uploads/disability/".$scanned_vis_imp_cert_file;

						}else{

								$var_errors.=$this->upload->display_errors();

						}

					}else{

							$var_errors.='The filetype you are attempting to upload is not allowed';

					}

				}

				/* Orthopedically handicapped certificate */

				$input_orth_han_cert = $_POST["hidden_orth_han_cert"];

				if(isset($_FILES['scanned_orth_han_cert']['name']) &&($_FILES['scanned_orth_han_cert']['name']!=''))

				{

					$img = "scanned_orth_han_cert";

					$tmp_nm = strtotime($date).rand(0,100);

					$new_filename = 'orth_han_cert_'.$tmp_nm;

					$config=array('upload_path'=>'./uploads/disability',

							  'allowed_types'=>'jpg|jpeg|',

							  'file_name'=>$new_filename,);	  

					$this->upload->initialize($config);

					$size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);

					if($size){

						if($this->upload->do_upload($img)){

							  $dt=$this->upload->data();

							  $file=$dt['file_name'];

							 $scanned_orth_han_cert_file = $dt['file_name'];

							 $output_orth_han_cert1 = base_url()."uploads/disability/".$scanned_orth_han_cert_file;

						}else{

								$var_errors.=$this->upload->display_errors();

						}

					}else{

							$var_errors.='The filetype you are attempting to upload is not allowed';

					}

				}

				/* Cerebral palsy certificate */

				$input_cer_palsy_cert = $_POST["hidden_cer_palsy_cert"];

				if(isset($_FILES['scanned_cer_palsy_cert']['name']) &&($_FILES['scanned_cer_palsy_cert']['name']!=''))

				{

					$img = "scanned_cer_palsy_cert";

					$tmp_nm = strtotime($date).rand(0,100);

					$new_filename = 'cer_palsy_cert_'.$tmp_nm;

					$config=array('upload_path'=>'./uploads/disability',

							  'allowed_types'=>'jpg|jpeg|',

							  'file_name'=>$new_filename,);	  

					$this->upload->initialize($config);

					$size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);

					if($size){

						if($this->upload->do_upload($img)){

							  $dt=$this->upload->data();

							  $file=$dt['file_name'];

							 $scanned_cer_palsy_cert_file = $dt['file_name'];

							 $output_cer_palsy_cert1 = base_url()."uploads/disability/".$scanned_cer_palsy_cert_file;

						}else{

								$var_errors.=$this->upload->display_errors();

						}

					}else{

							$var_errors.='The filetype you are attempting to upload is not allowed';

					}

				}

				$benchmark_disability = '';

				$benchmark_disability = $_POST['benchmark_disability'];

					

					$dob1= $_POST["dob1"];

					$dob = str_replace('/','-',$dob1);

					$dateOfBirth = date('Y-m-d',strtotime($dob));

					/*added scribe_flag : pooja*/

					if(isset($_POST['scribe_flag']))

						{

							$scribe_flag='Y';

						}

					

					

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

													'optedu'				=>$_POST["optedu"],	

													'optnletter'			=>$_POST["optnletter"],	

													'phone'					=>$_POST["phone"],	

													'pincode'				=>$_POST["pincode"],	

													'state'					=>$_POST["state"],	

													'stdcode'				=>$_POST["stdcode"],

													'scannedphoto'		=>$outputphoto1,

													'scannedsignaturephoto'=>$outputsign1,

													'idproofphoto'		=>$outputidproof1,

													'photoname'			=>$scannedphoto_file,

													'signname'				=>$scannedsignaturephoto_file,

													'idname'				=>$idproof_file,

													'selCenterName'	=>$_POST["selCenterName"],

													'txtCenterCode'		=>	$_POST["txtCenterCode"],

													'optmode'				=>$_POST["optmode"],

													'exid'					=>$_POST["exid"],

													'mtype'					=>$_POST["mtype"],

													'memtype'				=>$_POST["memtype"],

													'eprid'					=>$_POST["eprid"],

													'exam_month'   		=>$_POST["exmonth"],

													'rrsub'					=>$_POST["rrsub"],

													'excd'					=>$_POST["excd"],

													'exname'				=>$_POST["exname"],

													'fee'						=>	$_POST["fee"],

													'medium'				=>$_POST['medium'],

													'aadhar_card'		=>$_POST['aadhar_card'],

													'grp_code'			=>$_POST['grp_code'],

													'subject_arr'		=>$subject_arr,

													'scribe_flag'=>$scribe_flag,

													'benchmark_disability'=>$benchmark_disability,

													'scanned_vis_imp_cert'=>$output_vis_imp_cert1,

													'vis_imp_cert_name'=>$scanned_vis_imp_cert_file,

													'scanned_orth_han_cert'=>$output_orth_han_cert1,

													'orth_han_cert_name'=>$scanned_orth_han_cert_file,

													'scanned_cer_palsy_cert'=>$output_cer_palsy_cert1,

													'cer_palsy_cert_name'=>$scanned_cer_palsy_cert_file,

													'visually_impaired'=>$_POST["visually_impaired"],

													'orthopedically_handicapped'=>$_POST["orthopedically_handicapped"],

													'cerebral_palsy'=>$_POST["cerebral_palsy"],

													'elearning_flag'=>$_POST['elearning_flag']

													);

						$this->session->set_userdata('enduserinfo',$user_data);

						redirect(base_url().'Nonregtest/preview');

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

	

		if(!count($examinfo) > 0 || !count($medium) > 0 ||  !count($center) > 0)

		{

			$flag=0;

		}

		/* $this->load->helper('captcha');

		$this->session->unset_userdata("nonmemlogincaptcha");

		$this->session->set_userdata("nonmemlogincaptcha", rand(1, 100000));

		$vals = array(

						'img_path' => './uploads/applications/',

						'img_url' => base_url().'uploads/applications/',

					);

		$cap = create_captcha($vals);

		$_SESSION["nonmemlogincaptcha"] = $cap['word'];  */
		$this->load->model('Captcha_model');
		 $captcha_image = $this->Captcha_model->generate_captcha_img('nonmemlogincaptcha');
		 
		if($flag==1)

		{

		/*$data=array('middle_content'=>'nonmember/non_mem_reg','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master);

		$this->load->view('nonmember/nm_common_view',$data);*/

		

		############# get Compulsory Subject List ##############

		$this->db->join('exam_activation_master','exam_activation_master.exam_code=subject_master.exam_code AND exam_activation_master.exam_period=subject_master.exam_period');

			$this->db->group_by('subject_code');

		$compulsory_subjects=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$ExId,'subject_delete'=>'0','group_code'=>'C'),'',array('subject_code'=>'ASC'));

		

		$data=array('middle_content'=>'nonmember/non_mem_reg','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $captcha_image,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'compulsory_subjects'=>$compulsory_subjects);

			$this->load->view('nonmember/common_view_fullwidth',$data);

		}

		else

		{
			echo 'fail';exit;
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

	{//exit;

		$flag=1;

		$Mtype = base64_decode($this->input->get('Mtype'));

	 	$ExId = base64_decode($this->input->get('ExId'));

		$scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password='';

		

		$scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_cer_palsy_cert_file = '';

		

		$data['validation_errors'] = '';

		//check email,mobile duplication on the same time from different browser!!

		$endTime = date("H:i:s");

		$start_time= date("H:i:s",strtotime("-20 minutes",strtotime($endTime)));

		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');

		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);

		$this->db->or_where('mobile',$this->session->userdata['enduserinfo']['mobile']);

		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));

		if(count($check_duplication) > 0)

		{
			echo '3'; exit;
			redirect(base_url().'Nonregtest/accessdenied/');

		}

		

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

			$aadhar_card = $this->session->userdata['enduserinfo']['aadhar_card'];

			$optnletter = $this->session->userdata['enduserinfo']['optnletter'];

			$centerid=$this->session->userdata['enduserinfo']['selCenterName'];

			$centercode=$this->session->userdata['enduserinfo']['txtCenterCode'];

			$exmode=$this->session->userdata['enduserinfo']['optmode'];

			//$declaration1 = $_POST['declaration1'];

			

			/* benchmark_disability */

			$benchmark_disability = $this->session->userdata['enduserinfo']['benchmark_disability'];

			$scanned_vis_imp_cert_file = $this->session->userdata['enduserinfo']['vis_imp_cert_name'];

			$scanned_orth_han_cert_file = $this->session->userdata['enduserinfo']['orth_han_cert_name'];

			$scanned_cer_palsy_cert_file = $this->session->userdata['enduserinfo']['cer_palsy_cert_name'];

			$visually_impaired = $this->session->userdata['enduserinfo']['visually_impaired'];

			$orthopedically_handicapped = $this->session->userdata['enduserinfo']['orthopedically_handicapped'];

			$cerebral_palsy = $this->session->userdata['enduserinfo']['cerebral_palsy'];

			

			if($benchmark_disability == 'N'){

				$scanned_vis_imp_cert_file = '';

				$scanned_orth_han_cert_file = '';

				$scanned_cer_palsy_cert_file = '';

				$visually_impaired = '';

				$orthopedically_handicapped = '';

				$cerebral_palsy = '';

			}

			if($visually_impaired == 'N'){

				$scanned_vis_imp_cert_file = '';

			}

			if($orthopedically_handicapped == 'N'){

				$scanned_orth_han_cert_file = '';

			}

			if($cerebral_palsy == 'N'){

				$scanned_cer_palsy_cert_file = '';

			}

			

			if($benchmark_disability == 'Y'){

				if($visually_impaired == 'N' && $orthopedically_handicapped == 'N' && $cerebral_palsy == 'N')

				{

					$benchmark_disability = 'N';

				}

			}



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

											'benchmark_disability'=>$benchmark_disability,

											'vis_imp_cert_img'=>$scanned_vis_imp_cert_file,

											'orth_han_cert_img'=>$scanned_orth_han_cert_file,

											'cer_palsy_cert_img'=>$scanned_cer_palsy_cert_file,	

											'visually_impaired'=>$visually_impaired,

											'orthopedically_handicapped'=>$orthopedically_handicapped,

											'cerebral_palsy'=>$cerebral_palsy,

											'createdon'=>date('Y-m-d H:i:s')

								);			

								

			//$personalInfo = filter($personal_info);

			

			if($last_id =$this->master_model->insertRecord('member_registration',$insert_info,true))

			{

				

					$log_title ="Non member  INSERT Array :".$last_id;

					$log_message = serialize($insert_info);

					$rId = $last_id;

					$regNo = $last_id;

					storedUserActivity($log_title, $log_message, $rId, $regNo);

				

					logactivity($log_title ="Non-Member user registration ", $log_message = serialize($insert_info));

					$amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM',$this->session->userdata['enduserinfo']['elearning_flag']);

					

					$inser_exam_array=array(	'regnumber'=>$last_id,

															'exam_code'=>$this->session->userdata['enduserinfo']['excd'],

															'exam_mode'=>$this->session->userdata['enduserinfo']['optmode'],

															'exam_medium'=>$this->session->userdata['enduserinfo']['medium'],

															'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],

															'exam_center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],

															'exam_fee'=>$amount,

															'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],

															'created_on'=>date('y-m-d H:i:s'),

															'elearning_flag' => $this->session->userdata['enduserinfo']['elearning_flag']

															);

									

										

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

							 $this->session->set_userdata('non_memberdata', $userarr); 

							 if($this->config->item('exam_apply_gateway')=='sbi')

							{

									redirect(base_url().'Nonregtest/sbi_make_payment/');

							}

							else

							{

										 redirect(base_url()."Nonregtest/make_payment");

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

			redirect(base_url().'Nonregtest/member/?Mtype='.base64_encode($this->session->userdata['enduserinfo']['mtype']).'=&ExId='.base64_encode($this->session->userdata['enduserinfo']['excd']).'');

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
			echo '4'; exit;
			redirect(base_url().'Nonregtest/accessdenied/');

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

			

			$pg_success_url = base_url()."Nonregtest/sbitranssuccess";

			$pg_fail_url    = base_url()."Nonregtest/sbitransfail";

			

			if($this->config->item('sb_test_mode'))

			{

				$amount = $this->config->item('exam_apply_fee');

			}

			else

			{

				$amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM',$this->session->userdata['enduserinfo']['elearning_flag']);

				//$amount=$this->session->userdata['enduserinfo']['fee'];

			}

			

			if($amount==0 || $amount=='')

			{

				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');

				redirect(base_url().'Nonregtest/preview/');

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

				$getfees=getExamFeedetails($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],$this->session->userdata['enduserinfo']['memtype'],$this->session->userdata['enduserinfo']['elearning_flag']);

			}

		

			if($getcenter[0]['state_code']=='MAH')

			{

				//set a rate (e.g 9%,9% or 18%)

				$cgst_rate=$this->config->item('cgst_rate');

				$sgst_rate=$this->config->item('sgst_rate');

				if($this->session->userdata['enduserinfo']['elearning_flag'] == 'Y'){

					//set an amount as per rate

					$cgst_amt=$getfees[0]['elearning_cgst_amt'];

					$sgst_amt=$getfees[0]['elearning_sgst_amt'];

				 	//set an total amount

					$cs_total=$getfees[0]['elearning_cs_amt_total'];

					$amount_base = $getfees[0]['elearning_fee_amt'];

					

				}else{

					//set an amount as per rate

					$cgst_amt=$getfees[0]['cgst_amt'];

					$sgst_amt=$getfees[0]['sgst_amt'];

				 	//set an total amount

					$cs_total=$getfees[0]['cs_tot'];

					$amount_base = $getfees[0]['fee_amount'];

				}

				

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

				if($this->session->userdata['enduserinfo']['elearning_flag'] == 'Y'){

					

					$igst_rate=$this->config->item('igst_rate');

					$igst_amt=$getfees[0]['elearning_igst_amt'];

					$igst_total=$getfees[0]['elearning_igst_amt_total']; 

					$amount_base = $getfees[0]['elearning_fee_amt'];

					

				}else{

					$igst_rate=$this->config->item('igst_rate');

					$igst_amt=$getfees[0]['igst_amt'];

					$igst_total=$getfees[0]['igst_tot'];

				 	$amount_base = $getfees[0]['fee_amount'];

					

				}   

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

														'fee_amt'=>$amount_base,

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

			

			

			$log_title = "Exam invoice data from Nonregtest cntrlr inser_id = '".$inser_id."'";

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

					{											$this->db->group_by('subject_code');	

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

					redirect(base_url().'Nonregtest/preview/');

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
 					$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
					if($get_payment_status[0]['status']==1)
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

								

								redirect(base_url().'Nonregtest/refund/'.base64_encode($MerchantOrderNo));

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

										//redirect(base_url().'Nonregtest/refund/'.base64_encode($MerchantOrderNo));

									}

								}

							}

						}	

						else

						{

							redirect(base_url().'Nonregtest/refund/'.base64_encode($MerchantOrderNo));

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

					$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img');	

					

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

					

					$visually_file = 'v_'.$applicationNo.'.jpg';

					$orthopedically_file = 'o_'.$applicationNo.'.jpg';

					$cerebral_file = 'c_'.$applicationNo.'.jpg';



					if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))

					{	$upd_files['scannedphoto'] = $photo_file;	}

					

					if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))

					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}

					

					if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))

					{	$upd_files['idproofphoto'] = $proof_file;	}

					

					if(@ rename("./uploads/disability/".$result[0]['vis_imp_cert_img'],"./uploads/disability/".$visually_file))

					{	$upd_files['vis_imp_cert_img'] = $visually_file;	}

					

					if(@ rename("./uploads/disability/".$result[0]['orth_han_cert_img'],"./uploads/disability/".$orthopedically_file))

					{	$upd_files['orth_han_cert_img'] = $orthopedically_file;	}

					

					if(@ rename("./uploads/disability/".$result[0]['cer_palsy_cert_img'],"./uploads/disability/".$cerebral_file))

					{	$upd_files['cer_palsy_cert_img'] = $cerebral_file;	}



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



						$upd_files['vis_imp_cert_img'] = $visually_file;

						$upd_files['orth_han_cert_img'] = $orthopedically_file;	

						$upd_files['cer_palsy_cert_img'] = $cerebral_file;



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

				

					$info_arr=array('to'=>$result[0]['email'],

											'from'=>$emailerstr[0]['from'],

											'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,

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
					##############Get Admit card#############
					$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);

				}

				

						

				if($attachpath!='')

				{		

					//send sms	

					$files=array($attachpath,$admitcard_pdf);				

					$sms_newstring = str_replace("#exam_name#", "".substr($exam_info[0]['description'],0,28)."",  $emailerstr[0]['sms_text']);

					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);

					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);

					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						

					$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'P6tIFIwGR');	
					//$this->Emailsending->mailsend($info_arr);

					$this->Emailsending->mailsend_attch($info_arr,$files);

				}//Manage Log
				}
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

		redirect(base_url().'Nonregtest/acknowledge/'.base64_encode($MerchantOrderNo));

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

				$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg');	

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

	

	//function for payment

	public function make_payment()

	{

			$regno = $this->session->userdata['non_memberdata']['regno'];

			$exam_desc= $this->session->userdata['non_memberdata']['exam_desc'];

			$MerchantID = $this->config->item('bd_MerchantID');

			$SecurityID = $this->config->item('bd_SecurityID');

			$checksum_key = $this->config->item('bd_ChecksumKey');

			$pg_return_url = base_url()."Nonregtest/pg_response";

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

												'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,

												'message'=>$final_str

											);

						

						//send sms					

						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);

						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);

						//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);			

						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'P6tIFIwGR');			

											

						//To Do---Transaction email to user	currently we using failure emailer 					

						if($this->Emailsending->mailsend($info_arr))

						{

							redirect(base_url().'Nonregtest/acknowledge/'.base64_encode($applicationNo).'/'.base64_encode($MerchantOrderNo));

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

							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);

							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);

							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);

							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);

							

							$info_arr=array(	'to'=>$result[0]['email'],

														'from'=>$emailerstr[0]['from'],

														'subject'=>$emailerstr[0]['subject'].' '.$this->session->userdata('regnumber'),

														'message'=>$final_str

													);

							

							// send SMS

							$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

							//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

							$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg');	
							

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

		/* $this->load->helper('captcha');
		$this->session->unset_userdata("nonmemlogincaptcha");
		$this->session->set_userdata("nonmemlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["nonmemlogincaptcha"] = $cap['word'];
		echo $data; */
		
		$this->load->model('Captcha_model');
		echo $this->Captcha_model->generate_captcha_img('nonmemlogincaptcha');
	}

	

	//Thank you message to end user

	public function acknowledge($MerchantOrderNo=NULL)

	{

		$password=$decpass='';

		$data=array();

		//Query to get Payment details	

		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'member_regnumber,transaction_no,date,amount,exam_code,status');

		

		if(count($payment_info) <= 0)

		{redirect(base_url());}

		

		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');

		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,exam_master.ebook_flag,member_exam.exam_code');

		

		

		

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

		$data=array('application_number'=>$payment_info[0]['member_regnumber'],

		'password'=>$decpass,'payment_info'=>$payment_info,'exam_info'=>$exam_info,'medium'=>$medium,'result'=>$result);



		$this->load->view('nonmember/profile_thankyou',$data);

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

			redirect(base_url().'Nonregtest/preview');

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

		if(!$this->session->userdata('enduserinfo'))

		{

			redirect(base_url());

		}

		

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

			redirect(base_url().'Nonregtest/member/?Mtype='.base64_encode($this->session->userdata['enduserinfo']['mtype']).'=&ExId='.base64_encode($this->session->userdata['enduserinfo']['excd']).'');

		}

		

		$sub_flag=1;

		############check capacity is full or not ##########

		$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];

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

							//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])

							if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])

							{

								$sub_flag=0;

							}

						}

					}

				 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);

				if($capacity==0)

				{

					#########get message if capacity is full##########

					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);

				}

				if($msg!='')

				{

					$this->session->set_flashdata('error',$msg);

					redirect(base_url().'Nonregtest/member/?Mtype='.$this->session->userdata['enduserinfo']['mtype'].'=&ExId='.$this->session->userdata['enduserinfo']['excd'].'');

				}

			}

		}

		if($sub_flag==0)

		{

			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');

			redirect(base_url().'Nonregtest/member/?Mtype='.$this->session->userdata['enduserinfo']['mtype'].'=&ExId='.$this->session->userdata['enduserinfo']['excd'].'');

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
			echo '5'; exit;
			redirect(base_url().'Nonregtest/accessdenied/');

		}

		//check exam activation

		$check_exam_activation=check_exam_activate($this->session->userdata['enduserinfo']['excd']);

		if($check_exam_activation==0)

		{
			echo '6'; exit;
			redirect(base_url().'Nonregtest/accessdenied/');

		}

		//check for valid fee

		if($this->session->userdata['enduserinfo']['fee']==0 || $this->session->userdata['enduserinfo']['fee']=='')

		{

			//$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');

			redirect('http://iibf.org.in/');

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

		//echo $this->db->last_query();exit;

		

		$data=array('middle_content'=>'nonmember/non_mem_preview_register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'medium'=>$medium,'center'=>$center,'exam_period'=>$exam_period,'idtype_master'=>$idtype_master,'compulsory_subjects'=>$this->session->userdata['enduserinfo']['subject_arr']);

		$this->load->view('nonmember/common_view_fullwidth',$data);

		

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

		//redirect(base_url().'Nonregtest/memlogin/?Extype=MQ==&Mtype=Tk0='); 

		redirect(base_url().'nonmem/'); 

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

		$this->load->view('nonmember/non_mem_reg_refund',$data);

	}

	

}

