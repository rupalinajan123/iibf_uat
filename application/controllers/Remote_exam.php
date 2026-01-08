<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Remote_exam extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('master_model');		
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
		$this->load->helper('cookie');
		$this->load->model('log_model');
		$this->load->model('KYC_Log_model'); 
		 $this->chk_session->Check_mult_session();
		//$this->load->model('chk_session');
	 	// 	
		
		
	}
	
	public function examdetails(){ 
		
		$exam_code = base64_decode($this->input->get('ExId'));
		$exam_period = base64_decode($this->input->get('Exprd'));
		$member_number = $this->session->userdata('mregnumber_applyexam');
		
		$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
		$this->db->where('exam_period',base64_decode($this->input->get('Exprd')));
		$this->db->where('exam_code',base64_decode($this->input->get('ExId')));
		$this->db->where('pay_status',1);
		$this->db->order_by("id", "desc");
		$chk_eligible_2_reg2= $this->master_model->getRecords('member_exam','','id');
		if(count($chk_eligible_2_reg2) >=1)

		{redirect(base_url().'Examination/?type=Tw==');}
		
		$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
		$this->db->where('exm_prd',base64_decode($this->input->get('Exprd')));
		$this->db->where('exm_cd',base64_decode($this->input->get('ExId')));
		$this->db->where('remark',1);
		$this->db->order_by("admitcard_id", "desc");
		$chk_eligible_2_reg1= $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mam_nam_1,mem_type,exm_cd,exm_prd,mem_exam_id,admitcard_id');
		if(count($chk_eligible_2_reg1) >=1)

		{redirect(base_url().'Examination/?type=Tw==');}
		
		$cookieflag=1;
		$valcookie=$this->session->userdata('mregnumber_applyexam');
		
		
		// check weather cadidate is free member or not	
		$apply_flag = 1;
		
		$this->db->where('exam_code',$exam_code);
		$get_period = $this->master_model->getRecords('exam_activation_master','','exam_period');
		$this->db->where('fee_paid_flag','F');
		//$this->db->or_where('fee_paid_flag','f');
		$this->db->where('eligible_period',$exam_period);
		$this->db->where('member_no',$member_number);
		$this->db->where('exam_code',$exam_code);
		$this->db->order_by("id", "desc");
		
		$eligible_info = $this->master_model->getRecords('eligible_master','','exam_status,eligible_period,fee_paid_flag');
		
		if(count($eligible_info) > 0){
			foreach($eligible_info as $eligible_info_rec){
				if($eligible_info_rec['fee_paid_flag'] == '' || $eligible_info_rec['fee_paid_flag'] == 'P' || $eligible_info_rec['fee_paid_flag'] == 'p'){
					$apply_flag = 0;
				}
			}
		}else{
			$apply_flag = 0;
		}
		if($apply_flag == 0){
			redirect(base_url().'Examination/?type=Tw==');
		}
		
		if(isset($_POST['btnPreviewSubmit']))  	
		{
			$scribe_flag='N';
			$caiib_subjects=array();
			$compulsory_subjects=array();
			$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $state= $password = $var_errors = '';
			$venue=$this->input->post('venue');
			$date=$this->input->post('date');
			$time=$this->input->post('time');
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			$this->form_validation->set_rules('scribe_flag','Scribe Services','required');
			$this->form_validation->set_rules('medium','Medium','required|xss_clean');
			$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
			$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
			if($this->session->userdata('examcode')!=101)
			{
				$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
				$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
				$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
			}
			if($this->session->userdata('examcode')==$this->config->item('examCodeCaiib'))
			{
				$this->form_validation->set_rules('selSubcode','Elective Subject Name','required|xss_clean');
			}
			if($this->session->userdata('examcode')==$this->config->item('examCodeJaiib'))
			{
				$this->form_validation->set_rules('placeofwork','Place of Work','trim|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('state_place_of_work','State','trim|required|xss_clean');
				if($this->input->post('state_place_of_work')!='')
				{
					$state = $this->input->post('state_place_of_work');
				}
				$this->form_validation->set_rules('pincode_place_of_work','Pin Code','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
			}
			
				if($this->form_validation->run()==TRUE)
				{
					$subject_arr=array();
					$venue=$this->input->post('venue');
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					if(count($venue) >0 && count($date) >0 && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$this->db->group_by('subject_code');
							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($_POST['excd']),'subject_delete'=>'0','exam_period'=>$_POST['eprid'],'subject_code'=>$k),'subject_description');
							$subject_arr[$k]=array('venue'=>$v,'date'=>$date[$k],'session_time'=>$time[$k],'subject_name'=>$compulsory_subjects_name[0]['subject_description']);
						}
						#### add elective subject in venue,time,date array#########
						if(isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib']))
						{
								$subject_arr[$this->input->post('selSubcode')]=array('venue'=>$this->input->post('venue_caiib'),'date'=>$this->input->post('date_caiib'),'session_time'=>$this->input->post('time_caiib'),'subject_name'=>$this->input->post('selSubName1'));
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
									redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->input->get('ExId').'&Extype='.$this->input->get('Extype').'&Exprd='.base64_encode($eligible_info[0]['eligible_period']));
								}
							}
						}
						if($sub_flag==0)
						{
							if(base64_decode($_POST['excd'])!=101)
							{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->input->get('ExId').'&Extype='.$this->input->get('Extype').'&Exprd='.base64_encode($eligible_info[0]['eligible_period']));
							}
						}
					}
							#----Scrib Flag - changes by POOJA GODSE---#
 					$scribe_flag =$scribe_flag_d='N';
 					$Sub_menue_disability=$disability_value ='';
                if (isset($_POST['scribe_flag']))
                    {
                    $scribe_flag = $_POST['scribe_flag'];
                    }
  					if (isset($_POST['scribe_flag_d']))
                    {
                    $scribe_flag_d = $_POST['scribe_flag_d'];
                    }
					if (isset($_POST['Sub_menue']))
                    {
                    $Sub_menue_disability = $_POST['Sub_menue'];
                    }
					if (isset($_POST['disability_value']))
                    {
                    $disability_value = $_POST['disability_value'];
                    }
					#----Scrib Flag end - changes by POOJA GODSE---#
					
					
					$user_data=array('email'=>$_POST["email"],	
									'mobile'=>$_POST["mobile"],	
									'photo'=>'',
									'signname'=>'',
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
									'scribe_flag_d'=>$scribe_flag_d,
									'disability_value'=>$disability_value,
									'Sub_menue_disability'=>$Sub_menue_disability,
									'elearning_flag'=>$_POST['elearning_flag']
									);
					$this->session->set_userdata('examinfo',$user_data);
					/* User Log Activities : Bhushan */
					$log_title ="phase-3 Remote exam apply details";
					$log_message = serialize($user_data);
					$rId = $this->session->userdata('regid');
					$regNo = $this->session->userdata('mregnumber_applyexam');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					redirect(base_url().'Remote_exam/preview');
				}
				else
				{
					$var_errors = str_replace("<p>", "<span>", $var_errors);
					$var_errors = str_replace("</p>", "</span><br>", $var_errors);
				}
		}
		
		
		if($member_number == '' ){ 
			redirect(base_url().'Home/dashboard/');
		}else{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
			$this->db->join("eligible_master",'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period','left');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->where("eligible_master.member_no",$this->session->userdata('mregnumber_applyexam'));
			$this->db->where("eligible_master.app_category !=",'R');
			$this->db->where('exam_master.exam_code',$exam_code);
			$examinfo=$this->master_model->getRecords('exam_master');
			
			####### get subject mention in eligible master ##########
			if(count($examinfo) > 0)
			{
				foreach($examinfo as $rowdata)
				{
						if($rowdata['exam_status']!='P')
						{
							$this->db->group_by('subject_code');	
							$compulsory_subjects[]=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'subject_delete'=>'0','exam_period'=>$rowdata['exam_period'],'subject_code'=>$rowdata['subject_code']));	
						}
					}	
				//$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));
				$compulsory_subjects = array_map('current', $compulsory_subjects);
				sort($compulsory_subjects );
			}	
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where('center_master.exam_name',$exam_code);
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			//Below code, if member is new member
			if(count($examinfo) <=0)
			{
				$this->db->select('exam_master.*,misc_master.*');
				$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period');//added on 5/6/2017
				$this->db->where("misc_master.misc_delete",'0');
				$this->db->where('exam_master.exam_code',$exam_code);
				$examinfo = $this->master_model->getRecords('exam_master');
				//get center
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
				$this->db->where("center_delete",'0');
				$this->db->where('exam_name',$exam_code);
				$this->db->group_by('center_master.center_name');
				$center=$this->master_model->getRecords('center_master');
				####### get compulsory subject list##########
				$this->db->group_by('subject_code');
				$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$examinfo[0]['exam_period']),'',array('subject_code'=>'ASC'));
			}
		
			if(count($examinfo)<=0)
			{
				redirect(base_url());
			}
			
			
			if($valcookie)
			{
				$regnumber= $valcookie;
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'),'0','1');
				if(count($checkpayment) > 0)
				{
					$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
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
			
			$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
			$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
			$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
			
			$this->db->where('institution_delete','0');
			$institution_master=$this->master_model->getRecords('institution_master');
			
			$this->db->where('designation_delete','0');
			$designation=$this->master_model->getRecords('designation_master');
			
			$idtype_master=$this->master_model->getRecords('idtype_master');
			//To-do use exam-code wirh medium master
			
			$this->db->where('state_delete','0');
			$states=$this->master_model->getRecords('state_master');
				
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
			$this->db->where('medium_master.exam_code',$exam_code);
			$this->db->where('medium_delete','0');
			$medium=$this->master_model->getRecords('medium_master');
			//get center as per exam
			
			//user information
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($user_info) <=0)
			{
				redirect(base_url());
			}
			$scribe_disability = $this->master_model->getRecords('scribe_disability', array('is_delete' => '0'));
			//subject information
			$caiib_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'E','exam_period'=>$examinfo[0]['exam_period']));
			
			
			/* Benchmark Disability */
			$benchmark_disability_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam')),'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
			/* Benchmark Disability Close */
			
			
				if($cookieflag==0 )
				{
					$data=array('middle_content'=>'remote_exam/exam_apply_cms_msg');
					$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
				}
				else
				{
				
					/*Payment Check Code - Bhushan */
					$check_payment_val = check_payment_status($this->session->userdata('mregnumber_applyexam'));
					if($check_payment_val == 1){
						$msg= '<h4> Your transaction is in process. Please wait for some time.</h4>';
						$data = array('middle_content' => 'remote_exam/exam_apply_cms_msg','msg' => $msg);
						$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
						 
					}
					else
					{ 
				
						$data=array('scribe_disability' => $scribe_disability,'middle_content'=>'remote_exam/comApplication_preview','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'benchmark_disability_info' => $benchmark_disability_info);
					$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
					}
					/*Payment Check Code Close - Bhushan */
					
				}
		}
	}
	
	
	
	public function preview()
	{
		
		// check weather cadidate is free member or not	
		$apply_flag = 1;
		$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
		$get_period = $this->master_model->getRecords('exam_activation_master','','exam_period');
		$this->db->where('fee_paid_flag','F');
		//$this->db->or_where('fee_paid_flag','f');
		$this->db->where('eligible_period',$this->session->userdata['examinfo']['eprid']);
		$this->db->where('member_no',$this->session->userdata('mregnumber_applyexam'));
		$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->order_by("id", "desc");
		
		$eligible_info = $this->master_model->getRecords('eligible_master','','exam_status,eligible_period,fee_paid_flag');
		
		if(count($eligible_info) > 0){
			foreach($eligible_info as $eligible_info_rec){
				if($eligible_info_rec['fee_paid_flag'] == '' || $eligible_info_rec['fee_paid_flag'] == 'P' || $eligible_info_rec['fee_paid_flag'] == 'p'){
					$apply_flag = 0;
				}
			}
		}else{
			$apply_flag = 0;
		}
		if($apply_flag == 0){
			redirect(base_url().'Examination/?type=Tw==');
		}
		
	//Allowed member for different data
	  $subject_arr1 = $this->session->userdata['examinfo']['subject_arr'];
	 if(count($subject_arr1) > 0)
	 {
	 foreach($subject_arr1 as $k => $v)
      {
		$flag=allowed_examdate($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'),$v['date']);
		 if ($flag == 1)
			  {
					redirect(base_url().'Remote_exam/info');
			 }
	  }
	 }
		
		$this->chk_session->Mem_checklogin_external_user();
		$sub_flag=1;
		$sub_capacity=1;
		//echo $this->session->userdata['examinfo']['selCenterName'];exit;
		$compulsory_subjects=array();
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url().'Home/dashboard/');
		}
		//check exam acivation
		$check_exam_activation=check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
		if($check_exam_activation==0)
		{
			redirect(base_url().'Remote_exam/accessdenied/');
		}
			
		############check capacity is full or not ##########
		$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
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
				 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
				}
			}
		}
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
		}
			
		$cookieflag=1;
		//$this->chk_session->checkphoto();
		//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
		$valcookie=$this->session->userdata('mregnumber_applyexam');
		if($valcookie)
		{
			$regnumber= $valcookie;
			$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
			if(count($checkpayment) > 0)
			{
				$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
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
		//End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
			
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url());
		}	
		//check for valid fee
		if($this->session->userdata['examinfo']['fee']=='')
		{
			$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
			redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
		}
		
		$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata['examinfo']['excd']);
		if(!$check)
		{		
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
			$this->db->where('medium_master.exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('medium_delete','0');
			$medium=$this->master_model->getRecords('medium_master');
			
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$center=$this->master_model->getRecords('center_master','','center_name');
			//echo $this->db->last_query();exit;
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($user_info) <=0)
			{
				redirect(base_url());
			}	
			$this->db->where('state_delete','0');
			$states=$this->master_model->getRecords('state_master');
		
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$misc=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_delete'=>'0'));
			
			if($cookieflag==0)
			{
				$data=array('middle_content'=>'exam_apply_cms_msg');
			}
			else
			{
					$disability_value = $this->master_model->getRecords('scribe_disability', array('is_delete' =>0));
					$scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('is_delete' =>0));
					
					/* Benchmark Disability */
					$benchmark_disability_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam')),'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
					/* Benchmark Disability Close */
				
					$data=array('disability_value' => $disability_value,
			        'scribe_sub_disability' => $scribe_sub_disability,'middle_content'=>'remote_exam/exam_preview','user_info'=>$user_info,'medium'=>$medium,'center'=>$center,'misc'=>$misc,'states'=>$states,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr'],'benchmark_disability_info' => $benchmark_disability_info);
			}
			$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
		}
		else
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_master.misc_delete'=>'0'),'exam_month');
			//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
			$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
			$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
			 $data=array('middle_content'=>'memapplyexam/already_apply','check_eligibility'=>$message);
			 $this->load->view('memapplyexam/mem_apply_exam_common_view',$data);	
		}
	}
	
	public function add_record(){ 
		
		if($this->session->userdata['examinfo']['elearning_flag'] == 'N'){
			
			$exm_prd_arr = array(777);
			
			$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exam_period',$this->session->userdata['examinfo']['eprid']);
			$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('pay_status',1);
			$this->db->order_by("id", "desc");
			$chk_eligible_2_reg2= $this->master_model->getRecords('member_exam','','id');
			if(count($chk_eligible_2_reg2) >=1)

			{redirect(base_url().'Examination/?type=Tw==');}
			
			$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exm_prd',$this->session->userdata['examinfo']['eprid']);
			$this->db->where('exm_cd',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('remark',1);
			$this->db->order_by("admitcard_id", "desc");
			$chk_eligible_2_reg1= $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mam_nam_1,mem_type,exm_cd,exm_prd,mem_exam_id,admitcard_id');
			if(count($chk_eligible_2_reg1) >=1)

			{redirect(base_url().'Examination/?type=Tw==');}
			
			$amount=0;
			
			$inser_array=array(	'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
				'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
				'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
				'exam_medium'=>$this->session->userdata['examinfo']['medium'],
				'exam_period'=>$this->session->userdata['examinfo']['eprid'],
				'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
				'exam_fee'=>$amount,
				'elected_sub_code'=>$this->session->userdata['examinfo']['selected_elect_subcode'],
				'place_of_work'=>$this->session->userdata['examinfo']['placeofwork'],
				'state_place_of_work'=>$this->session->userdata['examinfo']['state_place_of_work'],
				'pin_code_place_of_work'=>$this->session->userdata['examinfo']['pincode_place_of_work'],
				'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
				'scribe_flag_PwBD' => 'N',
				'disability' => $this->session->userdata['examinfo']['disability_value'],
				'sub_disability' => $this->session->userdata['examinfo']['Sub_menue_disability'],
				'created_on'=>date('y-m-d H:i:s'),
				'elearning_flag' => $this->session->userdata['examinfo']['elearning_flag'],
				'free_paid_flg' => 'F'
			);
			$member_inser_id = $this->master_model->insertRecord('member_exam',$inser_array,true);
			
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($this->session->userdata['examinfo']['excd']),'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
			
			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));
			
			if($user_info[0]['gender']=='male')
			{$gender='M';}
			else
			{$gender='F';}
			
			$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			
			$states=$this->master_model->getRecords('state_master',array('state_code'=>$user_info[0]['state'],'state_delete'=>'0'));
			$state_name='';
			if(count($states) >0)
			{
				$state_name=$states[0]['state_name'];
			}
			
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
			
			if($this->session->userdata['examinfo']['optmode']=='ON')
			{
				$mode='Online';
			}
			else
			{
				$mode='Offline';
			}
			
			if(!empty($this->session->userdata['examinfo']['subject_arr']))
			{
				foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
				{
					
					$this->db->group_by('subject_code');	
					$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'subject_delete'=>'0','exam_period'=>$this->session->userdata['examinfo']['eprid'],'subject_code'=>$k),'subject_description');
					$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
					
					$admitcard_insert_array=array(
									'mem_exam_id'=>$member_inser_id,
									'center_code'=>$getcenter[0]['center_code'],
									'center_name'=>$getcenter[0]['center_name'],
									'mem_type'=>$user_info[0]['registrationtype'],
									'mem_mem_no'=>$this->session->userdata('mregnumber_applyexam'),
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
									'exam_date'=>$get_subject_details[0]['exam_date'],
									'time'=>$get_subject_details[0]['session_time'],
									'mode'=>$mode,
									'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
									'scribe_flag_PwBD' => $this->session->userdata['examinfo']['scribe_flag_d'],
									'disability' => $this->session->userdata['examinfo']['disability_value'],
								'sub_disability' => $this->session->userdata['examinfo']['Sub_menue_disability'],
									'vendor_code'=>$get_subject_details[0]['vendor_code'],
									'remark'=>2,
									'free_paid_flg'=>'F',
									'created_on'=>date('Y-m-d H:i:s'));
					$admit_inser_id = $this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
					
					
					// code to generate seat number
					$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$member_inser_id));
					
					if(count($exam_admicard_details) > 0){
						foreach($exam_admicard_details as $row){
							$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
							if($capacity==0){
								$log_title =" phase-3 Remote_exam Capacity full id:".$this->session->userdata('mregnumber_elapplyexam');
								$log_message = serialize($exam_admicard_details);
								$rId = $this->session->userdata('mregnumber_elapplyexam');
								$regNo = $this->session->userdata('mregnumber_elapplyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'Home/dashboard/');
							}
						}
					}
					
					$this->db->where('id',$member_inser_id);
					$exam_info=$this->master_model->getRecords('member_exam');
					
					if(count($exam_admicard_details) > 0){
						$password=random_password();
						foreach($exam_admicard_details as $row){
							
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],
							'exam_date'=>$row['exam_date'],
							'session_time'=>$row['time'],
							'center_code'=>$row['center_code']));
							
							$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$row['mem_exam_id'],'sub_cd'=>$row['sub_cd']));
							
							$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
							
							if($seat_number!=''){
								$final_seat_number = $seat_number;
								$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
								$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
							}else{
								$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
								if(count($admit_card_details) > 0){
									
									$log_title ="phase-3 Remote_exam Seat number already allocated id:".$this->session->userdata('mregnumber_elapplyexam');
									$log_message = serialize($exam_admicard_details);
									$rId = $admit_card_details[0]['admitcard_id'];
									$regNo = $this->session->userdata('mregnumber_elapplyexam');
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}else{
									$log_title ="phase-3 Remote_exam Fail user seat allocation id:".$this->session->userdata('mregnumber_elapplyexam');
									$log_message = serialize($exam_admicard_details);
									$rId = $this->session->userdata('mregnumber_elapplyexam');
									$regNo = $this->session->userdata('mregnumber_elapplyexam');
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									redirect(base_url().'Home/dashboard/');
								}
							}
						}
					}
				
				
				 
					
					// generate admitcard pdf
					$admitcard_pdf=remote_genarate_admitcard($this->session->userdata('mregnumber_applyexam'),base64_decode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['eprid']);
					
					
					
					
				
					if($admitcard_pdf!=''){
						
						$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
						$exam_name = $this->master_model->getRecords('exam_master','','description');
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
						
						$this->db->where('regnumber',$this->session->userdata('mregnumber_elapplyexam'));
						$this->db->where('isactive','1');
						$member_info = $this->master_model->getRecords('member_registration','','email,mobile,registrationtype');
						
						$final_str = 'Hello Sir/Madam <br/><br/>';
						$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
						$final_str.= '<br/><br/>';
						$final_str.= 'Regards,';
						$final_str.= '<br/>';
						$final_str.= 'IIBF TEAM';
						
						$info_arr=array('to'=>$member_info[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
						
						$files=array($admitcard_pdf);
						$this->Emailsending->mailsend_attch($info_arr,$files);
						
						// update admit card table
						$admitcard_image = base64_decode($this->session->userdata['examinfo']['excd']).'_'.$this->session->userdata['examinfo']['eprid'].'_'.$this->session->userdata('mregnumber_applyexam').'.pdf'; 
						$this->master_model->updateRecord('admit_card_details',array('admitcard_image'=>$admitcard_image), array('mem_exam_id'=>$member_inser_id));
						
						
						
						// update member exam table
						$member_update_arr = array('pay_status'=>1,'modified_on'=>date('Y-m-d H:i:s'));
						$this->master_model->updateRecord('member_exam',$member_update_arr, array('id'=>$member_inser_id));
						
						$this->db->where('mem_exam_id',$member_inser_id);
						$admitcard_info = $this->master_model->getRecords('admit_card_details');
						
						$this->db->where('exam_code',$admitcard_info[0]['exm_cd']);
						$exam_name = $this->master_model->getRecords('exam_master','','description');
						
						$this->db->where('exam_code',$admitcard_info[0]['exm_cd']);
						$subject_name = $this->master_model->getRecords('subject_master','','subject_description');
						
						$this->db->where('member_no',$this->session->userdata('mregnumber_elapplyexam'));
						$this->db->where('exam_code',$this->session->userdata('exmcd_elapplyexam'));
						$this->db->where('exam_period',$this->session->userdata('exmprd_elapplyexam'));
						$this->db->where('transaction_no !=','');
						$fee_amt = $this->master_model->getRecords('exam_invoice','','fee_amt');
						
						
						
						
						$this->db->where('exam_period',$this->session->userdata['examinfo']['eprid']);
						$misc_master = $this->master_model->getRecords('misc_master','','exam_month');
						
						$misc_monthmonth = date('Y')."-".substr($misc_master['0']['exam_month'],4);
                    	$exam_period =  date('F',strtotime($misc_monthmonth))."-".substr($misc_master['0']['exam_month'],0,-2);
						
						$data = array('admitcard_info'=>$admitcard_info,'exam_name'=>$exam_name,'subject_name'=>$subject_name,'fee_amt'=>$fee_amt,'exam_period'=>$exam_period,'middle_content'=>'remote_exam/REL_exam_applied_success');
						$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
						
					}
				} 
			}
			
		}else{
			echo 'Invalid request';	
		}
	}
	
	public function Msuccess()
	{
		$this->chk_session->Mem_checklogin_external_user();
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url());
		}
		if(isset($_POST['btnPreview']))
		{
			$amount=295;
			
			$inser_array=array(	'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
								'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
								'exam_medium'=>$this->session->userdata['examinfo']['medium'],
								'exam_period'=>$this->session->userdata['examinfo']['eprid'],
								'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
								'exam_fee'=>$amount,
								'elected_sub_code'=>$this->session->userdata['examinfo']['selected_elect_subcode'],
								'place_of_work'=>$this->session->userdata['examinfo']['placeofwork'],
								'state_place_of_work'=>$this->session->userdata['examinfo']['state_place_of_work'],
								'pin_code_place_of_work'=>$this->session->userdata['examinfo']['pincode_place_of_work'],
								'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
								'scribe_flag_PwBD' => $this->session->userdata['examinfo']['scribe_flag_d'],
								'disability' => $this->session->userdata['examinfo']['disability_value'],
								'sub_disability' => $this->session->userdata['examinfo']['Sub_menue_disability'],
								'created_on'=>date('y-m-d H:i:s'),
								'elearning_flag' => $this->session->userdata['examinfo']['elearning_flag']
								);
			if($inser_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				//echo $this->session->userdata['examinfo']['fee'];
				$this->session->userdata['examinfo']['insdet_id']=$inser_id;
				//$data['insdet_id'] =$inser_id;  
  				//$this->session->set_userdata('examinfo', $data);  
				$update_array=array();
				
				// Re-set previous image update flags
				$prev_edited_on = '';
				$prev_photo_flg = "N";
				$prev_signature_flg = "N";
				$prev_id_flg = "N";
				$prev_edited_on_qry = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam')),'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
				if(count($prev_edited_on_qry))
				{
					$prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
					$prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
					$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
					$prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
					if($prev_edited_on != date('Y-m-d'))
					{
						$this->master_model->updateRecord('member_registration', array('photo_flg'=>'N', 'signature_flg'=>'N', 'id_flg'=>'N'), array('regid'=>$this->session->userdata('mregid_applyexam')));
					}
				}
				
				//update an array for images
				$photo_flg = '';
				if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
				{	$photo_flg = 'N';	}
				else {	$photo_flg = $prev_photo_flg;	}
				
				$signature_flg = '';
				if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
				{	$signature_flg = 'N';	}
				else {	$signature_flg = $prev_signature_flg;	}
				
				if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
					$photo_flg = 'Y';
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'scannedsignaturephoto');
					$singname=$sing_name[0]['scannedsignaturephoto'];
					$signature_flg = 'Y';
				}
				
				if($prev_edited_on != date('Y-m-d') && ($photo_flg == 'Y' || $signature_flg == 'Y'))
				{
					$update_array['photo_flg'] = $photo_flg;
					$update_array['signature_flg'] = $signature_flg;
					$update_array['images_editedon'] = date('Y-m-d H:i:s');
					$update_array['images_editedby'] = 'Candidate';
				}
				
				$email_mbl_flg = 0;
				//check if email is unique
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email'],'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));
					$email_mbl_flg = 1;	
				}
				// check if mobile is unique
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile'],'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));
					$email_mbl_flg = 1;	
				}
				if(count($update_array) > 0)
				{
					$edited = '';
					foreach($update_array as $key => $val)
					{
						$edited .= strtoupper($key)." = ".strtoupper($val)." && ";
					}
					
					if($email_mbl_flg == 1)
					{
						$update_array['editedon'] = date('Y-m-d H:i:s');
						$update_array['editedby'] = "Candidate";
					}
					
					$prevData = array();
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));
					if(count($user_info))
					{
						$prevData = $user_info[0];
					}
					
					$desc['updated_data'] = $update_array;
					$desc['old_data'] = $prevData;
					
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
					
					log_profile_user($log_title = "phase-3 Profile updated successfully", $edited,'data',$this->session->userdata('mregid_applyexam'),$this->session->userdata('mregnumber_applyexam'));
					
					logactivity($log_title ="phase-3 Member update profile during exam apply", $log_message = serialize($desc));
					
				}
				
				if($this->config->item('exam_apply_gateway')=='sbi')
				{
					redirect(base_url().'Remote_exam/sbi_make_payment/');
				}
				else
				{
					redirect(base_url().'Remote_exam/make_payment/');
				}
			}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	public function sbi_make_payment()
	{
		$this->chk_session->Mem_checklogin_external_user();
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			redirect('http://iibf.org.in/');
		}
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('mregnumber_applyexam'),'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
				if(count($checkpayment) > 0)
				{
					$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
					 $current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$this->session->set_flashdata('error','Wait your transaction is under process!.');
						redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
					}
				}
				
			$sub_flag=1;
			############check capacity is full or not
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
							//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
							if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
							{
								$sub_flag=0;
							}
						}
					}
				 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
				}
			}
		}
			if($sub_flag==0)
			{
				$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
				redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
			}
			
			
			$regno = $this->session->userdata('mregnumber_applyexam');//$this->session->userdata('regnumber');
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Remote_exam/sbitranssuccess";
			$pg_fail_url    = base_url()."Remote_exam/sbitransfail";
			
			$amount = 295;
			
			if($amount==0 || $amount=='')
			{
				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
				redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
			}
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
			
			//Ordinary member Apply exam
			//	Ref1 = orderid
			//	Ref2 = iibfexam
			//	Ref3 = member reg num
			//	Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'exam_period'=> $this->session->userdata['examinfo']['eprid']),'exam_month');
			
			if(base64_decode($this->session->userdata['examinfo']['excd'])==340 || base64_decode($this->session->userdata['examinfo']['excd'])==3400)
			{
				$exam_code=34;		
			}
			else if(base64_decode($this->session->userdata['examinfo']['excd'])==580 || base64_decode($this->session->userdata['examinfo']['excd'])==5800)
			{
				$exam_code=58;	
			}
			else if(base64_decode($this->session->userdata['examinfo']['excd'])==1600 || base64_decode($this->session->userdata['examinfo']['excd'])==16000) 
			{
				$exam_code=160;	
			}
			else if(base64_decode($this->session->userdata['examinfo']['excd'])==200)
			{
				$exam_code=20;	
			}
			else if(base64_decode($this->session->userdata['examinfo']['excd'])==1770 || base64_decode($this->session->userdata['examinfo']['excd'])==17700)
			{
				$exam_code=177;	
			}
			else if(base64_decode($this->session->userdata['examinfo']['excd'])==1750)
			{
				$exam_code=175;	
			}
			else
			{
				$exam_code=base64_decode($this->session->userdata['examinfo']['excd']);	
			}
			$ref4=($exam_code).$yearmonth[0]['exam_month'];
		    
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
				'description'      => $this->session->userdata['examinfo']['exname'],//"Duplicate ID card request. Reason:".$this->session->userdata('desc'),
				'status'           => '2',
				'exam_code'    	   => base64_decode($this->session->userdata['examinfo']['excd']),
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>'IIBF_EXAM_O',
				//'pg_other_details'=>$custom_field
			);
				
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$this->session->userdata('mregnumber_applyexam')."^".$ref4;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			
			//set invoice details(Prafull)
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($this->session->userdata['examinfo']['excd']),'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
			if(count($getcenter) > 0)
			{
				//get state code,state name,state number.
				$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				
				//call to helper (fee_helper)
				$getfees=getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),$this->session->userdata['examinfo']['elearning_flag']);
			}
			if($getcenter[0]['state_code']=='MAH')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$this->config->item('cgst_rate');
				$sgst_rate=$this->config->item('sgst_rate');
				
				if($this->session->userdata['examinfo']['elearning_flag'] == 'Y'){
					//set an amount as per rate
					$cgst_amt=22.5;
					$sgst_amt=22.5;
				 	//set an total amount
					$cs_total=295;
					$base_amount = 250;
					
				}else{
					//set an amount as per rate
					$cgst_amt=0.00;
					$sgst_amt=0.00;
				 	//set an total amount
					$cs_total=0.00;
					$base_amount = 0.00;
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
				if($this->session->userdata['examinfo']['elearning_flag'] == 'Y'){
					
					$igst_rate=$this->config->item('igst_rate');
					$igst_amt=45;
					$igst_total=295; 
					$base_amount = 250;
					
				}else{
					$igst_rate=$this->config->item('igst_rate');
					$igst_amt=0.00;
					$igst_total=0.00;
				 	$base_amount = 0.00;
					
				}
				
				$tax_type='Inter';
			}
			if($getstate[0]['exempt']=='E')
			{
				 $cgst_rate=$sgst_rate=$igst_rate='';	
				 $cgst_amt=$sgst_amt=$igst_amt='';	
			}	
				
				$gst_no='0';
				/*if($this->session->userdata['examinfo']['gstin_no']!='')
				{
					$gst_no=$this->session->userdata['examinfo']['gstin_no'];
				}*/
				
			$invoice_insert_array=array('pay_txn_id'=>$pt_id,
													'receipt_no'=>$MerchantOrderNo,
													'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'state_of_center'=>$getcenter[0]['state_code'],
													'member_no'=>$this->session->userdata('mregnumber_applyexam'),
													'app_type'=>'O',
													'exam_period'=>$this->session->userdata['examinfo']['eprid'],
													'service_code'=>$this->config->item('exam_service_code'),
													'qty'=>'1',
													'state_code'=>$getstate[0]['state_no'],
													'state_name'=>$getstate[0]['state_name'],
													'tax_type'=>$tax_type,
													'fee_amt'=>$base_amount,
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
			$log_title = "phase-3 Remote Exam invoice data from applyexam cntrlr inser_id = '".$inser_id."'";
			$log_message = serialize($invoice_insert_array);
			$rId = $this->session->userdata('mregnumber_applyexam');
			$regNo = $this->session->userdata('mregnumber_applyexam');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			
			
			//insert into admit card table
			//################get userdata###########
			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));
			
			
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
			if($this->session->userdata['examinfo']['optmode']=='ON')
			{
				$mode='Online';
			}
			else
			{
				$mode='Offline';
			}	
			
			if(!empty($this->session->userdata['examinfo']['subject_arr']))
			{
					foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
					{
																$this->db->group_by('subject_code');	
							$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'subject_delete'=>'0','exam_period'=>$this->session->userdata['examinfo']['eprid'],'subject_code'=>$k),'subject_description');
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName'])); 
						
						$admitcard_insert_array=array(
													'mem_exam_id'=>$this->session->userdata['examinfo']['insdet_id'],
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'mem_type'=>$user_info[0]['registrationtype'],
													'mem_mem_no'=>$this->session->userdata('mregnumber_applyexam'),
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
													'exam_date'=>$get_subject_details[0]['exam_date'],
													'time'=>$get_subject_details[0]['session_time'],
													'mode'=>$mode,
													'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
													'scribe_flag_PwBD' => 'N',
													'disability' => $this->session->userdata['examinfo']['disability_value'],
													'sub_disability' => $this->session->userdata['examinfo']['Sub_menue_disability'],
													'vendor_code'=>$get_subject_details[0]['vendor_code'],
													'remark'=>2,
													'created_on'=>date('Y-m-d H:i:s'));
							//echo '<pre>';
						//print_r($admitcard_insert_array);
						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
						$log_title ="phase-3 Remote exam Admit card data from Applyexam cntrlr";
						$log_message = serialize($admitcard_insert_array);
						$rId = $this->session->userdata('mregnumber_applyexam');
						$regNo = $this->session->userdata('mregnumber_applyexam');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
					
					############check for missing subject############
					$this->db->where('app_category !=','R');
					$this->db->where('app_category !=','');
					$this->db->where('exam_status !=','V');
					$this->db->where('exam_status !=','P');
					$this->db->where('exam_status !=','D');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'member_no'=>$this->session->userdata('mregnumber_applyexam'),'eligible_period'=>$this->session->userdata['examinfo']['eprid']));
					
					if(count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category']=='R')
					{
						if(!empty($this->session->userdata['examinfo']['subject_arr']))
						{
							$count=0;
							foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
							{
								$check_admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$this->session->userdata('mregnumber_applyexam'),'exm_cd'=>base64_decode($this->session->userdata['examinfo']['excd']),'sub_cd'=>$k,'venueid'=>$v['venue'],'exam_date'=>$v['date'],'time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
								if(count($check_admit_card_details) >0)
								{
									$count++;
								}
							}
						}
						if(count($this->session->userdata['examinfo']['subject_arr'])!=$count)
						{
								$log_title = "phase-3 Remote exam Fresh Member subject missing applyexam cntrlr";
								$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
								$rId = $this->session->userdata('mregnumber_applyexam');
								$regNo = $this->session->userdata('mregnumber_applyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								delete_cookie('examid');
								$this->session->set_flashdata('error','Something went wrong!!');
								redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
						}
					}
					else
					{
							$count=0;
							if(count($check_eligibility_for_applied_exam)==count($this->session->userdata['examinfo']['subject_arr']))
							{
									if(!empty($this->session->userdata['examinfo']['subject_arr']))
									{
										foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
										{
											$check_admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$this->session->userdata('mregnumber_applyexam'),'exm_cd'=>base64_decode($this->session->userdata['examinfo']['excd']),'sub_cd'=>$k,'venueid'=>$v['venue'],'exam_date'=>$v['date'],'time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
											if(count($check_admit_card_details) >0)
											{
												$count++;
											}
										}
									}
							}
							if(count($check_eligibility_for_applied_exam)!=$count)
							{
								$log_title = "phase-3 Remote exam Existing Member subject missing  applyexam cntrlr";
								$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
								$rId = $this->session->userdata('mregnumber_applyexam');
								$regNo = $this->session->userdata('mregnumber_applyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								delete_cookie('examid');
								$this->session->set_flashdata('error','Something went wrong!!');
								redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
							}
					}
					############END check for missing subject############
				}
			else
			{
					if(base64_decode($this->session->userdata['examinfo']['excd'])!=101)
					{
						$this->session->set_flashdata('Error','Something went wrong!!');
						redirect(base_url().'Remote_exam/examdetails/?ExId='.$this->session->userdata['examinfo']['excd'].'&Extype='.$this->session->userdata['examinfo']['extype'].'&Exprd='.$this->session->userdata['examinfo']['eprid']); 
					}
			}
			//set cookie for Apply Exam
			applyexam_set_cookie($this->session->userdata['examinfo']['insdet_id']);
			//$update_data = array('receipt_no' => $pt_id);
			//	$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
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
		$attachpath=$invoiceNumber=$admitcard_pdf='';
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
						
		$elective_subject_name='';
		//Sbi B2B callback
	//check sbi payment status with MerchantOrderNo 
	$q_details = sbiqueryapi($MerchantOrderNo);
	if ($q_details)
	{
		if ($q_details[2] == "SUCCESS")
		{
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			//check user payment status is updated by b2b or not
			if($get_user_regnum[0]['status']==2)
			{
				######### payment Transaction ############
				$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
				$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
				
				$log_title = "phase-3 Remote_exam update payment table after success  = '".$MerchantOrderNo."'";
				$log_message = serialize($update_data);
				$rId = $this->session->userdata('mregnumber_elapplyexam');
				$regNo = $this->session->userdata('mregnumber_elapplyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				
				if($this->db->affected_rows())	
				{
						if(count($get_user_regnum) > 0)
				{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
				}
		
			
				//Query to get user details
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,institution_master.name');
				
				//Query to get exam details	
				$this->db->where('id',$this->session->userdata['examinfo']['insdet_id']);
				$exam_info=$this->master_model->getRecords('member_exam');
				
				//Generate Admit card
				$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
				
				if(count($exam_admicard_details) > 0){
					foreach($exam_admicard_details as $row){
						$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
						if($capacity==0){
							$log_title ="phase-3 Remote_exam Capacity full id:".$this->session->userdata('mregnumber_elapplyexam');
							$log_message = serialize($exam_admicard_details);
							$rId = $this->session->userdata('mregnumber_elapplyexam');
							$regNo = $this->session->userdata('mregnumber_elapplyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url().'Home/dashboard/');
						}
					}
				}
				
				if(count($exam_admicard_details) > 0){
					$password=random_password();
					foreach($exam_admicard_details as $row){
						
						$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],
						'exam_date'=>$row['exam_date'],
						'session_time'=>$row['time'],
						'center_code'=>$row['center_code']));
						
						$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$row['mem_exam_id'],'sub_cd'=>$row['sub_cd']));
						
						$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
						
						if($seat_number!=''){
							$final_seat_number = $seat_number;
							$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
							
							$log_title ="phase-3 Remote_exam Seat number allocated id:".$this->session->userdata('mregnumber_elapplyexam');
							$log_message = serialize($exam_admicard_details);
							$rId = $admit_card_details[0]['admitcard_id'];
							$regNo = $this->session->userdata('mregnumber_elapplyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							
						}else{
							$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
							if(count($admit_card_details) > 0){
								
								$log_title ="phase-3 Remote_exam Seat number already allocated id:".$this->session->userdata('mregnumber_elapplyexam');
								$log_message = serialize($exam_admicard_details);
								$rId = $admit_card_details[0]['admitcard_id'];
								$regNo = $this->session->userdata('mregnumber_elapplyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}else{
								$log_title ="phase-3  Remote_exam Fail user seat allocation id:".$this->session->userdata('mregnumber_elapplyexam');
								$log_message = serialize($exam_admicard_details);
								$rId = $this->session->userdata('mregnumber_elapplyexam');
								$regNo = $this->session->userdata('mregnumber_elapplyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'Home/dashboard/');
							}
						}
					}
				}
				
				//generate admitcard pdf	
				$admitcard_pdf=remote_genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
				
					
			  	
				//update member Exam/
				$update_data = array('pay_status' => '1');
				$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
				
				$log_title = "phase-3 Remote_exam update member exam pay status  = '".$get_user_regnum[0]['ref_id']."'";
				$log_message = 'exam Update member exam table '.$get_user_regnum[0]['member_regnumber'];
				$rId = $this->session->userdata('mregnumber_elapplyexam');
				$regNo = $this->session->userdata('mregnumber_elapplyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
					
					
				if($exam_info[0]['exam_mode']=='ON'){
					$mode='Online';
				}elseif($exam_info[0]['exam_mode']=='OF'){
					$mode='Offline';
				}else{
					$mode='';
				}
				if($exam_info[0]['examination_date']!='0000-00-00')
				{
					$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
				}
				else
				{
					
					$exam_period = 'June-2020';
				}
			
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
		
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
				if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!='')
				{
					//get Elective Subeject name for CAIIB Exam	
				   if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')
				   {
					   $elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');
					
						if(count($elective_sub_name_arr) > 0)
						{
							$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
						}	
				   }
					   
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period."",$newstring3);
					$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
					$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
					$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
					$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
					$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
					$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
					$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
					$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
					$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
					$newstring14 = str_replace("#MEDIUM#", "English",$newstring13);
					$newstring15 = str_replace("#CENTER#", "Remote Proctored Exam",$newstring14);
					$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
					$newstring17 = str_replace("#ELECTIVE_SUB#", "".$elective_subject_name."",$newstring16);
					$newstring18 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring17);
					$newstring19 = str_replace("#PLACE_OF_WORK#", "".strtoupper($exam_info[0]['place_of_work'])."",$newstring18);
					$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring19);
					$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$exam_info[0]['pin_code_place_of_work']."",$newstring20);
					$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
					if(count($elern_msg_string) > 0)
					{
						foreach($elern_msg_string as $row)
						{
							$arr_elern_msg_string[]=$row['exam_code'];
						}
						if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
						{
							$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		
						}
						else
						{
							$newstring22 = str_replace("#E-MSG#", '',$newstring21);		
						}
					}
					else
					{
						$newstring22 = str_replace("#E-MSG#", '',$newstring21);
					}
					$final_str = str_replace("#MODE#", "".$mode."",$newstring22);
				 }
				else
				{
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period."",$newstring3);
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
					$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
					$newstring16 = str_replace("#MEDIUM#", "English",$newstring15);
					$newstring17 = str_replace("#CENTER#", "Remote Proctored Exam",$newstring16);
					$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
					$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
					$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
					$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
					$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
					if(count($elern_msg_string) > 0)
					{
						foreach($elern_msg_string as $row)
						{
							$arr_elern_msg_string[]=$row['exam_code'];
						}
						if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
						{
							$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		
						}
						else
						{
							$newstring22 = str_replace("#E-MSG#", '',$newstring21);		
						}
					}
					else
					{
						$newstring22 = str_replace("#E-MSG#", '',$newstring21);
					}
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring22);
				 }
				 
				$this->db->where('regnumber',$this->session->userdata('mregnumber_elapplyexam'));
				$this->db->where('isactive','1');
				$member_info = $this->master_model->getRecords('member_registration','','email,mobile,registrationtype');
				
				
				$info_arr=array(
										'to'=>$member_info[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str
									);
									
				//get invoice	
				$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
				//echo $this->db->last_query();exit;
				if(count($getinvoice_number) > 0)
				{
					
					$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
						
						$log_title = "phase-3 Remote_exam invoice number generation done  = '".$invoiceNumber."'";
						$log_message = 'Invoice number generation done '.$get_user_regnum[0]['member_regnumber'];
						$rId = $this->session->userdata('mregnumber_elapplyexam');
						$regNo = $this->session->userdata('mregnumber_elapplyexam');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
					
					$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
					$this->db->where('pay_txn_id',$payment_info[0]['id']);
					$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
					$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
				}	
		
				if($attachpath!='')
				{	
				
					$log_title = "phase-3 Remote_exam invoice image generation done  = '".$invoiceNumber."'";
					$log_message = 'Invoice image generation done '.$get_user_regnum[0]['member_regnumber'];
					$rId = $this->session->userdata('mregnumber_elapplyexam');
					$regNo = $this->session->userdata('mregnumber_elapplyexam');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					
					$files=array($attachpath,$admitcard_pdf);
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'C-48OSQMg');
					$this->Emailsending->mailsend_attch($info_arr,$files);
					
				}
				
				}
				else
				{
					$log_title ="phase-3 Remote exam B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
					$log_message = serialize($update_data);
					$rId = $MerchantOrderNo;
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
				}
				
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
		}
	}//End of check sbi payment status with MerchantOrderNo 
	///End of SBICALL Back	
	
	
		$this->db->where('mem_exam_id',$this->session->userdata['examinfo']['insdet_id']);
		$admitcard_info = $this->master_model->getRecords('admit_card_details');
		
		$this->db->where('exam_code',$admitcard_info[0]['exm_cd']);
		$exam_name = $this->master_model->getRecords('exam_master','','description');
		
		$this->db->where('exam_code',$admitcard_info[0]['exm_cd']);
		$subject_name = $this->master_model->getRecords('subject_master','','subject_description');
		
		$this->db->where('member_no',$this->session->userdata('mregnumber_elapplyexam'));
		$this->db->where('exam_code',$this->session->userdata('exmcd_elapplyexam'));
		$this->db->where('exam_period',$this->session->userdata('exmprd_elapplyexam'));
		$this->db->where('transaction_no !=','');
		$fee_amt = $this->master_model->getRecords('exam_invoice','','fee_amt');
		
		
		
		$this->db->where('exam_period',$this->session->userdata['examinfo']['eprid']);
		$misc_master = $this->master_model->getRecords('misc_master','','exam_month');
		
		$misc_monthmonth = date('Y')."-".substr($misc_master['0']['exam_month'],4);
		$exam_period =  date('F',strtotime($misc_monthmonth))."-".substr($misc_master['0']['exam_month'],0,-2);
												
		$data_arr = array('admitcard_info'=>$admitcard_info,'exam_name'=>$exam_name,'subject_name'=>$subject_name,'fee_amt'=>$fee_amt,'exam_period'=>$exam_period,'middle_content'=>'remote_exam/REL_exam_applied_success');
		$this->load->view('memapplyexam/mem_apply_exam_common_view',$data_arr);
		
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
			//SBICALL Back B2B
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
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
				
			$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'B2B');
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			
			//Query to get Payment details	
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
			
			//Query to get user details
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
			
			//Query to get exam details	
			$this->db->where('id',$this->session->userdata['examinfo']['member_exam_id']);
			$exam_info=$this->master_model->getRecords('member_exam');
		
			if($this->session->userdata('exmprd_elapplyexam') == 912){
				$exam_period_date = 'June-2020';
			}else{
				$exam_period_date = 'June-2020';
			}
			
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
			//send sms to Ordinary Member
			$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
			$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
			//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
			$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg');
			$this->Emailsending->mailsend($info_arr);
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//End Of SBICALL Back	
			redirect(base_url().'Remote_exam/fail/'.base64_encode($MerchantOrderNo));
		}
		else
		{
			die("Please try again...");
		}
	}
	
	public function fail($order_no=NULL)
	{ 
	
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('mregnumber_elapplyexam')));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		$data=array('middle_content'=>'memapplyexam/exam_applied_fail','payment_info'=>$payment_info);
		$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
	
	}
	
	
	
	public function add_admitcard ($table, $primary_key_field, $primary_key_val){
	   /* generate the select query */
	   $this->db->where($primary_key_field, $primary_key_val); 
	   $query = $this->db->get($table);
	  
		foreach ($query->result() as $row){   
		   foreach($row as $key=>$val){        
			  if($key != $primary_key_field){ 
			  /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
			  $this->db->set($key, $val);               
			  }//endif              
		   }//endforeach
		}//endforeach
	
		/* insert the new record into table*/
		$this->db->insert($table); 
   		return $insert_id = $this->db->insert_id();
	}
	
	public function add_memberexam ($table, $primary_key_field, $primary_key_val){
	   /* generate the select query */
	   $this->db->where($primary_key_field, $primary_key_val); 
	   $query = $this->db->get($table);
	  
		foreach ($query->result() as $row){   
		   foreach($row as $key=>$val){        
			  if($key != $primary_key_field){ 
			  /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
			  $this->db->set($key, $val);               
			  }//endif              
		   }//endforeach
		}//endforeach
	
		/* insert the new record into table*/
		$this->db->insert($table); 
   		return $insert_id = $this->db->insert_id();
	}
	
	public function add_examinvoice ($table, $primary_key_field, $primary_key_val){
	   /* generate the select query */
	   $this->db->where($primary_key_field, $primary_key_val); 
	   $query = $this->db->get($table);
	  
		foreach ($query->result() as $row){   
		   foreach($row as $key=>$val){        
			  if($key != $primary_key_field){ 
			  /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
			  $this->db->set($key, $val);               
			  }//endif              
		   }//endforeach
		}//endforeach
	
		/* insert the new record into table*/
		$this->db->insert($table); 
   		return $insert_id = $this->db->insert_id();
	}
	
	public function add_payment ($table, $primary_key_field, $primary_key_val){
	   /* generate the select query */
	   $this->db->where($primary_key_field, $primary_key_val); 
	   $query = $this->db->get($table);
	  
		foreach ($query->result() as $row){   
		   foreach($row as $key=>$val){        
			  if($key != $primary_key_field){ 
			  /* $this->db->set can be used instead of passing a data array directly to the insert or update functions */
			  $this->db->set($key, $val);               
			  }//endif              
		   }//endforeach
		}//endforeach
	
		/* insert the new record into table*/
		$this->db->insert($table); 
   		return $insert_id = $this->db->insert_id();
	}
	
		##---------check captcha userlogin (vrushali)-----------##
	public function check_captcha_examapply($code) 
	{
		if(!isset($this->session->mem_applyexam_captcha) && empty($this->session->mem_applyexam_captcha))
		{
			return false;
		}
		
		if($code == '' || $this->session->mem_applyexam_captcha != $code )
		{
			$this->form_validation->set_message('check_captcha_examapply', 'Invalid %s.'); 
			$this->session->set_userdata("mem_applyexam_captcha", rand(1,100000));
			return false;
		}
		if($this->session->mem_applyexam_captcha == $code)
		{
			$this->session->set_userdata('mem_applyexam_captcha','');
			$this->session->unset_userdata("mem_applyexam_captcha");
			return true;
		}
	}
	
	//##---- reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("mem_applyexam_captcha");
		$this->session->set_userdata("mem_applyexam_captcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["mem_applyexam_captcha"] = $cap['word'];
		echo $data;
	}
	
	##GST Message
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
		$data=array('middle_content'=>'memapplyexam/not_eligible','check_eligibility'=>$message);
  	    $this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
	}
	
	##GST Message
	public function GST()
	{
		$message='<div style="color:#F00">Please pay GST amount of Exam/Mem registration in order to apply for the exam.
<a href="' . base_url() . 'GstRecovery/" target="new">click here</a> </div>';
		$data=array('middle_content'=>'memapplyexam/not_eligible','check_eligibility'=>$message);
  	    $this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
	}
	
	public function info()
	{
		$message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->session->userdata['examinfo']['excd']);
		
		$data=array('middle_content'=>'memapplyexam/not_eligible','check_eligibility'=>$message);
		$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
		
	}
	
	public function examapplied($regnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$today_date=date('Y-m-d');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$this->db->where('exam_master.elg_mem_o','Y');
		$this->db->where('pay_status','1');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber));
		//echo $this->db->last_query();exit;
		####check if number applied through the bulk registration (Prafull)###
		if(count($applied_exam_info)<=0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$this->db->where('exam_master.elg_mem_o','Y');
			$this->db->where('bulk_isdelete','0');
			$this->db->where('institute_id!=','');
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber));
		}	
		###### End of check  number applied through the bulk registration###
		
		return count($applied_exam_info);
	}
	
	//get applied exam name which is fall on same date(Prafull)
	public function get_alredy_applied_examname($regnumber=NULL,$exam_code=NULL)
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
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
			### checking bulk applied ######
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->where('bulk_isdelete','0');
				$this->db->where('institute_id!=','');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'2'),'member_exam.exam_code,exam_master.description');
			}
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=array();
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
						if($flag==1)
						{
							break;
						}
					}
				}
			}
		return $msg;
	}
	
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		redirect(base_url().'Applyexam/exapplylogin');
	}
	
	
}
