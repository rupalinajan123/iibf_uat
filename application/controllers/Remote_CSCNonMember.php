<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Remote_CSCNonMember extends CI_Controller {
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
		$this->load->model('KYC_Log_model'); 
		$this->chk_session->Check_mult_session();
		
		// set free flag to verify user is free member OR paid
		$this->session->set_userdata('csc_venue_flag','F');
		
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
	 
	Public function comApplication(){
		echo 'Registration period is over';
	}
	 
	public function comApplication_oldfun(){ 
	exit;
		$exam_code = base64_decode($this->input->get('excode'));
		$exam_period = base64_decode($this->input->get('Exprd'));
		$this->session->set_userdata('examcode',$exam_code);
		$this->session->set_userdata('csc_remote_period',$exam_period);
		
		$this->db->where('regnumber',$this->session->userdata('cscnmregnumber'));
		$this->db->where('exam_code',$exam_code);
		$this->db->where('exam_period',$exam_period);
		$this->db->where('pay_status',1);
		$this->db->order_by("id", "desc");
		$chk_member_exam_entry = $this->master_model->getRecords('member_exam','','id');
		if(count($chk_member_exam_entry) > 0){
			redirect(base_url().'CSCNonMember/memlogin/?Extype=Mg==&Mtype=Tk0=&ExId=OTkx');
		}
		
		$this->db->where('mem_mem_no',$this->session->userdata('cscnmregnumber'));
		$this->db->where('exm_cd',$exam_code);
		$this->db->where('exm_prd',$exam_period);
		$this->db->where('remark',1);
		$this->db->order_by("admitcard_id", "desc");
		$chk_admit_card_entry = $this->master_model->getRecords('admit_card_details','','admitcard_id');
		if(count($chk_admit_card_entry) > 0){
			redirect(base_url().'CSCNonMember/memlogin/?Extype=Mg==&Mtype=Tk0=&ExId=OTkx');
		}
		
		$caiib_subjects=array();
		if(isset($_POST['btnPreviewSubmit']))  	
		{ 
		/*echo '<pre>';
		print_r($_POST);
		exit;*/
			
			$scribe_flag='N';
			$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $state= $password = $var_errors = '';
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
			$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
			$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
			$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
			$this->form_validation->set_rules('medium','Medium','required|xss_clean');
			$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
			$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
			
			if($_POST["hiddenphoto"]!='')
			{
			$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
			}
			if($_POST["hiddenscansignature"]!='')
			{
			$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
			}
			if($this->input->post('gstin_no'))
			{
				$this->form_validation->set_rules('gstin_no', 'Bank GSTIN Number', 'trim|alpha_numeric|min_length[15]|xss_clean');
			}
			/*echo 'here';
			echo '<pre>';
			print_r($_POST);
			exit;*/
				if($this->form_validation->run()==TRUE)
				{ 
					$subject_arr=array();
					$venue=$this->input->post('venue');
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					if(count($venue) >0 && count($date) && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($_POST['excd']),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$_POST['eprid'],'subject_code'=>$k),'subject_description');
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
								 $capacity=csc_check_capacity($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								}
								if($msg!='')
								{
									
									$this->session->set_flashdata('error',$msg);
									redirect(base_url().'Remote_CSCNonMember/comApplication/?excode='.base64_encode($this->session->userdata('examcode')).'&Exprd='.base64_encode($this->session->userdata('csc_remote_period')));
								}
							}
						}
						if($sub_flag==0)
						{
							
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'Remote_CSCNonMember/comApplication/?excode='.base64_encode($this->session->userdata('examcode')).'&Exprd='.base64_encode($this->session->userdata('csc_remote_period')));
						}
					}
					/*add code  scribe_flag :pooja*/
					if(isset($_POST['scribe_flag']))
					{
						$scribe_flag='Y';
					}
					
					//Generate dynamic photo
					$photo_name = '';
					$sign_name = '';
					if($_POST["hiddenphoto"]!='')
					{
						$photo_name= $_POST["hiddenphoto"];
					}
					// generate dynamic id proof
					if($_POST["hiddenscansignature"]!='')
					{
						$sign_name =$_POST["hiddenscansignature"];
					}	
					$user_data=array('email'=>$_POST["email"],	
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
									'insdet_id'=>'',
									'selected_elect_subcode'=>$_POST['selSubcode'],
									'selected_elect_subname'=>$_POST['selSubName1'],
									'placeofwork'=>$_POST['placeofwork'],
									'state_place_of_work'=>$_POST['state_place_of_work'],
									'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
									'elected_exam_mode'=>$_POST['elected_exam_mode'],
									'grp_code'=>$_POST['grp_code'],
									'subject_arr'=>$subject_arr,
									'scribe_flag'=>$scribe_flag,
									'gstin_no'=>$_POST['gstin_no'],
									'csc_venue'=>$_POST['csc_venue']
									);
					
					$this->session->set_userdata('examinfo',$user_data);
					
					
					
					/* User Log Activities : Bhushan */
					$log_title ="CSC free member Non Member exam apply details";
					$log_message = serialize($user_data);
					$rId = $this->session->userdata('cscnmregid');
					$regNo = $this->session->userdata('cscnmregnumber');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
					redirect(base_url().'Remote_CSCNonMember/preview');
				}
				else
				{
					$var_errors = str_replace("<p>", "<span>", $var_errors);
					$var_errors = str_replace("</p>", "</span><br>", $var_errors);
				}
				
		}
		
		//Considering B1 as group code in query (By Prafull)
		if($this->session->userdata('examcode')=='')
		{
			redirect(base_url().'CSCNonMember/examlist/');	
		}
		//check exam acivation
		$check_exam_activation=check_exam_activate($this->session->userdata('examcode'));
		if($check_exam_activation==0)
		{
			redirect(base_url().'Remote_CSCNonMember/accessdenied/');
		}
		//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
		$cookieflag=1;
		$valcookie=$this->session->userdata('cscnmregnumber');
		
		//END Of ask user to wait for 5 min, until the payment transaction process complete
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
		$this->db->join("eligible_master",'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period','left');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where("eligible_master.member_no",$this->session->userdata('cscnmregnumber'));
		$this->db->where("eligible_master.app_category !=",'R');
		$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
		$examinfo=$this->master_model->getRecords('exam_master');
		
		####### get subject mention in eligible master ##########
		if(count($examinfo) > 0)
		{
			foreach($examinfo as $rowdata)
			{
					if($rowdata['exam_status']!='P')
					{
						$compulsory_subjects[]=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$rowdata['exam_period'],'subject_code'=>$rowdata['subject_code']));	
					}
				}	
				$compulsory_subjects = array_map('current', $compulsory_subjects);
				sort($compulsory_subjects );
		}	
		
		//echo $this->db->last_query();exit;
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',$this->session->userdata('examcode'));
		$this->db->where("center_delete",'0');
		$this->db->where("center_master.center_code !=",751);
		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',$this->session->userdata('examcode'));
		$this->db->where("center_delete",'0');
		$this->db->where("center_master.center_code ",751);
		$home_center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		
		
		
		//Below code, if member is new member
		if(count($examinfo) <=0)
		{
			$this->db->select('exam_master.*,misc_master.*');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period');//added on 5/6/2017
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
 			$examinfo = $this->master_model->getRecords('exam_master');
			//get center
			//get center as per exam
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where("center_delete",'0');
			$this->db->where('exam_name',$this->session->userdata('examcode'));
			$this->db->group_by('center_master.center_name');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			####### get compulsory subject list##########
			$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$examinfo[0]['exam_period']),'',array('subject_code'=>'ASC'));
		}
		if(count($examinfo)<=0)
		{
			redirect(base_url().'CSCNonMember/examlist');
		}
		
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		//To-do use exam-code wirh medium master
		//get medium
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_delete','0');
		$this->db->where('medium_master.exam_code',$this->session->userdata('examcode'));
		$medium=$this->master_model->getRecords('medium_master');
		//user information
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
		if(count($user_info) <=0)
		{
			redirect(base_url().'CSCNonMember/dashboard');
		}
		//subject information
		$caiib_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E'));
		if($cookieflag==0)
		{	
			$data=array('middle_content'=>'cscnonmember/exam_apply_cms_msg');
		}
		else
		{
			$data=array('middle_content'=>'cscnonmember/remote_comApplication','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'home_center'=>$home_center);
		}
		$this->load->view('cscnonmember/nm_common_view',$data);
		
	}	
	
	
	public function preview()
	{
		$this->chk_session->chk_cscnon_member_session();
		$compulsory_subjects=array();
		
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url());
		}
		//check exam acivation
		$check_exam_activation=check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
		if($check_exam_activation==0)
		{
			redirect(base_url().'Remote_CSCNonMember/accessdenied/');
		}
		
		
		############check capacity is full or not ##########
		$sub_flag=1;
		$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
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
				 $capacity=csc_check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					redirect(base_url().'Remote_CSCNonMember/comApplication/?excode='.base64_encode($this->session->userdata('examcode')).'&Exprd='.base64_encode($this->session->userdata('csc_remote_period')));
				}
			}
		}
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			redirect(base_url().'Remote_CSCNonMember/comApplication/?excode='.base64_encode($this->session->userdata('examcode')).'&Exprd='.base64_encode($this->session->userdata('csc_remote_period')));
		}
		//##########ask user to wait for 5min###########
		$cookieflag=1;
		$valcookie=$this->session->userdata('cscnmregnumber');
		if($valcookie)
		{
			$regnumber= $valcookie;
			$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
			if(count($checkpayment) > 0)
			{
				$endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));
				 $current_time= date("Y-m-d H:i:s");
				if(strtotime($current_time)<=strtotime($endTime))
				{
					$cookieflag=0;
				}
				else
				{
					delete_cookie('examid');
				}
			}
			else
			{
				delete_cookie('examid');
			}
		}
		else
		{
			delete_cookie('examid');
		}
		$check=$this->examapplied($this->session->userdata('cscnmregnumber'),$this->session->userdata['examinfo']['excd']);
		if(!$check)
		{		
			//get medium
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
			$this->db->where('medium_master.exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$medium=$this->master_model->getRecords('medium_master');
			//get center
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$center=$this->master_model->getRecords('center_master','','center_name',array('center_name'=>'ASC'));
			
			//echo $this->db->last_query();exit;
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
		
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$misc=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_delete'=>'0'));
			
			if($cookieflag==0)
			{
				$data=array('middle_content'=>'cscnonmember/exam_apply_cms_msg');
			}
			else
			{
				$data=array('middle_content'=>'cscnonmember/remote_exam_preview','user_info'=>$user_info,'medium'=>$medium,'center'=>$center,'misc'=>$misc,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr']);
			}
			$this->load->view('cscnonmember/nm_common_view',$data);
		}
		else
		{
			 $get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_master.misc_delete'=>'0'),'exam_month');
			 //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
			 $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
			 $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
			 //$message='4Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
			 $message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
			 $data=array('middle_content'=>'cscnonmember/already_apply','check_eligibility'=>$message);
			 $this->load->view('cscnonmember/nm_common_view',$data);	
		}
	}
	
	public function add_member(){
		
		
		
		$this->db->where('regnumber',$this->session->userdata('cscnmregnumber'));
		$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->where('exam_period',$this->session->userdata['examinfo']['eprid']);
		$this->db->where('pay_status',1);
		$this->db->order_by("id", "desc");
		$chk_member_exam_entry = $this->master_model->getRecords('member_exam','','id');
		if(count($chk_member_exam_entry) > 0){
			redirect(base_url().'CSCNonMember/memlogin/?Extype=Mg==&Mtype=Tk0=&ExId=OTkx');
		}
		
		$this->db->where('mem_mem_no',$this->session->userdata('cscnmregnumber'));
		$this->db->where('exm_cd',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->where('exm_prd',$this->session->userdata['examinfo']['eprid']);
		$this->db->where('remark',1);
		$this->db->order_by("admitcard_id", "desc");
		$chk_admit_card_entry = $this->master_model->getRecords('admit_card_details','','admitcard_id');
		if(count($chk_admit_card_entry) > 0){
			redirect(base_url().'CSCNonMember/memlogin/?Extype=Mg==&Mtype=Tk0=&ExId=OTkx');
		}
		
		
		$inser_array_memberexam=array(	'regnumber'=>$this->session->userdata('cscnmregnumber'),
							'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
							'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
							'exam_medium'=>$this->session->userdata['examinfo']['medium'],
							'exam_period'=>$this->session->userdata['examinfo']['eprid'],
							'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
							'exam_fee'=>0,
							'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
							'created_on'=>date('y-m-d H:i:s'),
							'free_paid_flg'=>'F'
						);
						
		$insert_id_member=$this->master_model->insertRecord('member_exam',$inser_array_memberexam,true);
		$this->session->set_userdata('insert_id_member',$insert_id_member);
		
		$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($this->session->userdata['examinfo']['excd']),'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
		
		
		if(count($getcenter) > 0){
			//get state code,state name,state number.
			$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
			//call to helper (fee_helper)
			$getfees=getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],base64_encode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),'N');
			
		}
		
		
		$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('cscnmregnumber'),'isactive'=>'1'));
		//get associate institute details
		$institute_id='';
		$institution_name='';
		if($user_info[0]['associatedinstitute']!=''){
			$institution_master=$this->master_model->getRecords('institution_master',array('institude_id'=>$user_info[0]['associatedinstitute']));
			if(count($institution_master) >0){
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
		if(count($states) >0){
			$state_name=$states[0]['state_name'];
		}		
		//##############Examination Mode###########
		if($this->session->userdata['examinfo']['optmode']=='ON'){
			$mode='Online';
		}else{
			$mode='Offline';
		}	
		
		if(!empty($this->session->userdata['examinfo']['subject_arr']))
		{
			foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
			{
				$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$this->session->userdata['examinfo']['eprid'],'subject_code'=>$k),'subject_description');
				
				$query='(exam_date = "0000-00-00" OR exam_date = "")';
				$this->db->where($query);
				$this->db->where('session_time=','');
				$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
					
				$admitcard_insert_array=array('mem_exam_id'=>$insert_id_member,
											'center_code'=>$getcenter[0]['center_code'],
											'center_name'=>$getcenter[0]['center_name'],
											'mem_type'=>$this->session->userdata('memtype'),
											'mem_mem_no'=>$this->session->userdata('cscnmregnumber'),
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
											'exm_cd'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exm_prd'=>$this->session->userdata['examinfo']['eprid'],
											'sub_cd '=>$k,
											'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
											'm_1'=>$this->session->userdata['examinfo']['medium'],
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
											'exam_date'=>$v['date'],
											'time'=>$v['session_time'],
											'mode'=>$mode,
											'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
											'vendor_code'=>$get_subject_details[0]['vendor_code'],
											'remark'=>2,
											'created_on'=>date('Y-m-d H:i:s'),
											'free_paid_flg'=>'F'
											);
				$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
			}
		}else{
			$this->session->set_flashdata('Error','Something went wrong!!');
			redirect(base_url().'Remote_CSCNonMember/comApplication/?excode='.base64_encode($this->session->userdata('examcode')).'&Exprd='.base64_encode($this->session->userdata('csc_remote_period')));
		}
		
		$update_array=array();
		$where="( registrationtype='NM')";
		$this->db->where($where);
		$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email'],'isactive'=>'1'));
		if($check_email==0){
			$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));	
		}
		// check if mobile is unique
		$where="( registrationtype='NM')";
		$this->db->where($where);
		$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile'],'isactive'=>'1'));
		if($check_mobile==0){
			$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));	
		}
		
		if(count($update_array) > 0){
			$update_array['editedon'] = date('Y-m-d H:i:s');
			$update_array['editedby'] = "Candidate";
			$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
		
		/* User Log Activities : Bhushan */
		$log_title ="CSC free Non Member update profile during exam apply";
		$log_message = serialize($update_array);
		$rId = $this->session->userdata('cscnmregid');
		$regNo = $this->session->userdata('cscnmregnumber');
		storedUserActivity($log_title, $log_message, $rId, $regNo);
		/* Close User Log Actitives */
		}
		
		
		// Seat allocation code start
		
		//Query to get exam details	
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('cscnmregnumber'),'member_exam.id'=>$insert_id_member),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
		
		$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$insert_id_member));
		if(count($exam_admicard_details) > 0)
		{
		############check capacity is full or not ##########
		//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
		if(count($exam_admicard_details) > 0)
		{		
			$msg='';
			$sub_flag=1;
			$sub_capacity=1;
			foreach($exam_admicard_details as $row)
			{
				 $capacity=csc_check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$log_title ="CSC free member Capacity full id:".$this->session->userdata('cscnmregnumber');
					$log_message = serialize($exam_admicard_details);
					$rId = $get_user_regnum[0]['ref_id'];
					$regNo = $this->session->userdata('cscnmregnumber');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					redirect(base_url().'Remote_CSCNonMember/refund/'.base64_encode($MerchantOrderNo));
				}
			}
		}
		$password=random_password();
		foreach($exam_admicard_details as $row)
		{
			
			$query='(exam_date = "0000-00-00" OR exam_date = "")';
			$this->db->where($query);
			$this->db->where('session_time=','');
			$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
			
			$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$insert_id_member,'sub_cd'=>$row['sub_cd']));
			
			//echo $this->db->last_query().'<br>';
			$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
			

			if($seat_number!='')
			{
				$final_seat_number = $seat_number;
				$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
				$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
			}
			else
			{
				$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
				if(count($admit_card_details) > 0)
				{
					$log_title ="CSC free member Seat number already allocated id:".$this->session->userdata('cscnmregnumber');
					$log_message = serialize($exam_admicard_details);
					$rId = $admit_card_details[0]['admitcard_id'];
					$regNo = $this->session->userdata('cscnmregnumber');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				}
				else
				{
					$log_title ="CSC free member Fail user seat allocation id:".$this->session->userdata('cscnmregnumber');
					$log_message = serialize($exam_admicard_details);
					$rId = $this->session->userdata('cscnmregnumber');
					$regNo = $this->session->userdata('cscnmregnumber');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
				}
			}
		}
			##############Get Admit card#############
			$admitcard_pdf=genarate_admitcard($this->session->userdata('cscnmregnumber'),$exam_info[0]['exam_code'],$exam_info[0]['exam_period']); 
		}		
		else
		{
			//redirect(base_url().'CSCNonMember/refund/'.base64_encode($MerchantOrderNo));
		}
	
		######update member_exam######
		$update_data = array('pay_status' => '1');
		$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$insert_id_member));
		
		
		if($admitcard_pdf!=''){
			
			
			
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
			
			$this->db->where('regnumber',$this->session->userdata('cscnmregnumber'));
			$this->db->where('isactive','1');
			$member_info = $this->master_model->getRecords('member_registration','','email,mobile,registrationtype');
			
			$final_str = 'Hello Sir/Madam <br/><br/>';
			$final_str.= 'Please check your new attached revised admit card letter for CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENT examination';   
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM';
			
			$info_arr=array('to'=>$member_info[0]['email'],
								//'to'=>'pawansing.pardeshi@esds.co.in',
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
							);
			
			$files=array($admitcard_pdf);
			$this->Emailsending->mailsend_attch($info_arr,$files);
			
			redirect(base_url().'Remote_CSCNonMember/success/');
			
		}
	}
	
	
	public function success(){
		
		$this->db->where('mem_exam_id',$this->session->userdata('insert_id_member'));
		$admitcard_info = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image');
		
		$data = array('admitcard_info'=>$admitcard_info,'middle_content'=>'cscnonmember/remote_csc_applied_success');
		$this->load->view('cscnonmember/nm_common_view',$data);
	}
	
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
		$data=array('middle_content'=>'cscnonmember/not_eligible','check_eligibility'=>$message);
		$this->load->view('cscnonmember/nm_common_view',$data);
	}
	
	public function check_emailduplication($email)
	{
		if($email!="")
		{
			$where="( registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'regid !='=>$this->session->userdata('cscnmregid'),'isactive'=>'1'));
			
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
	
	public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
			$where="( registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'regid !='=>$this->session->userdata('cscnmregid'),'isactive'=>'1'));
			
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
	
	public function examapplied($cscnmregnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$cnt=0;
		$today_date=date('Y-m-d');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$this->db->where('exam_master.elg_mem_nm','Y');
		$this->db->where('pay_status','1');
		$this->db->order_by('member_exam.id','desc');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$cscnmregnumber),'member_exam.created_on,member_exam.pay_status');
		####check if number applied through the bulk registration (Prafull)###
		if(count($applied_exam_info)<=0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$this->db->where('exam_master.elg_mem_nm','Y');
			$this->db->where('bulk_isdelete','0');
			$this->db->where('institute_id!=','');
			$this->db->order_by('member_exam.id','desc');
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$cscnmregnumber),'member_exam.created_on,member_exam.pay_status');
		}
		######get eligible created on data##########
		
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
		$this->db->limit('1');
		$get_eligible_date=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>base64_decode($exam_code),'member_no'=>$cscnmregnumber),'eligible_master.created_on');
		
		if(count($applied_exam_info) > 0 && count($get_eligible_date) > 0)
		{
			if(strtotime($applied_exam_info[0]['created_on'] ) > strtotime($get_eligible_date[0]['created_on']))
			{
				$cnt=$cnt+1;
			}
		}
		else if(count($applied_exam_info)> 0  && $applied_exam_info[0]['pay_status']==1)
		{
				$cnt=$cnt+1;
		}
		return $cnt;
	}
	
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
		$data=array('middle_content'=>'SpecialexamMApply/SpecialexamMApply_refund','payment_info'=>$payment_info,'exam_name'=>$exam_name);
		$this->load->view('SpecialexamMApply/mem_apply_exam_common_view',$data);
		
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
	
}

