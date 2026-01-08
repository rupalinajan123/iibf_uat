<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Applyjaiibtest extends CI_Controller {
	
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
	
public function login(){
	 
	$chk_time = date('Y-m-d H:i:s');
	$data=array();
	$data['error']='';
	if(isset($_POST['submit'])){  
			$config = array(
							array(
									'field' => 'Username',
									'label' => 'Registration/Membership No.',
									'rules' => 'trim|required'
								),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_examapply',
								),
							);
			$this->form_validation->set_rules($config);
			$dataarr=array(
				'regnumber'=> $this->input->post('Username'),
				
			);
			if ($this->form_validation->run() == TRUE){
				$this->db->where('mem_mem_no',$this->input->post('Username'));
				$chk_old_app = $this->master_model->getRecords('admit_card_details_jaiib','','admitcard_id');
				
				$this->db->where('regnumber',$this->input->post('Username'));
				$this->db->where('isactive','1');
				$user_info = $this->master_model->getRecords('member_registration','','regid,regnumber,firstname,middlename,lastname,registrationtype');
				
				$this->db->where('mem_mem_no',$this->input->post('Username'));
				$this->db->where('remark',1);
				$this->db->where('exm_prd',220);
				$chk_app = $this->master_model->getRecords('admit_card_details','','admitcard_id,exm_prd,exm_cd,app_update');
				
				 if(count($user_info) > 0 && count($chk_app) > 0 && count($chk_old_app) > 0){
					 $user_data=array('mregid_applyexam'=>$user_info[0]['regid'],
													'mregnumber_applyexam'=>$user_info[0]['regnumber'],
													'mfirstname_applyexam'=>$user_info[0]['firstname'],
													'mmiddlename_applyexam'=>$user_info[0]['middlename'],
													'mlastname_applyexam'=>$user_info[0]['lastname'],
													'memtype'=>$user_info[0]['registrationtype'],
													'memexcode'=>$chk_app[0]['exm_cd'],
													'memexprd'=>$chk_app[0]['exm_prd'],
													'mem_admitid'=>$chk_app[0]['admitcard_id']
													);
					$this->session->set_userdata($user_data);
					
					if($chk_app[0]['app_update'] != 0){
						 $data['error']='<span style="">You have already updated your application</span>';
					}else{
						 redirect(base_url().'Applyjaiibtest/comApplication');
					}
				 }else{
					 $data['error']='<span style="">Invalid credential..</span>';
				}
				
			} else{
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
	$this->session->set_userdata('mem_applyexam_captcha', $cap['word']);
	$this->load->view('examlogin',$data);
}

public function comApplication(){
	
	$data = array();
	if($this->session->userdata('memexcode')==''){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	$this->db->where('remark',1);
	$this->db->where('app_update','1');
	$chk_already_apppy = $this->master_model->getRecords('admit_card_details','','admitcard_id,app_update');
						
	if(isset($chk_already_apppy) && count($chk_already_apppy) > 0 ){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	
	$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where('pay_status',1);
	$this->db->where('app_update','1');
	$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
						
	if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	
	
	if(isset($_POST['btnPreviewSubmit'])){
		$venue=$this->input->post('venue');
		$date=$this->input->post('date');
		$time=$this->input->post('time');
		if($this->session->userdata('examinfo'))
		{
			$this->session->unset_userdata('examinfo');
		}
		
		$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
		$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
		
		$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
		$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
		$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
		
		if($this->form_validation->run()==TRUE){
			$subject_arr=array();
			$venue=$this->input->post('venue');
			$date=$this->input->post('date');
			$time=$this->input->post('time');
			
			
			if(count($venue) >0 && count($date) >0 && count($time) >0){
				foreach($venue as $k=>$v){
						
						
						$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
						$this->db->where('exm_cd',$this->session->userdata('memexcode'));
						$this->db->where('exm_prd',$this->session->userdata('memexprd'));
						$this->db->where('sub_cd',$k);
						$this->db->where('remark',1);
						$compulsory_subjects_name = $this->master_model->getRecords('admit_card_details','','sub_cd,sub_dsc,center_code,venueid,exam_date,time',array('sub_cd'=>'ASC'));
						
						
						$subject_arr[$k]=array('venue'=>$v,'date'=>$date[$k],'session_time'=>$time[$k],'subject_name'=>$compulsory_subjects_name[0]['sub_dsc']);
						
						
				}
				
				#########check duplication of venue,date,time##########		
				if(count($subject_arr) > 0){	
					$msg='';
					$sub_flag=1;
					$sub_capacity=1;
					foreach($subject_arr as $k=>$v){
						foreach($subject_arr as $j=>$val){
							if($k!=$j){
								if($v['date']==$val['date'] && $v['session_time']==$val['session_time']){
									$sub_flag=0;
								}
							}
						}
					}
				}
				if($sub_flag==0){
					if(base64_decode($_POST['excd'])!=101){
						$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
						redirect(base_url().'Applyjaiibtest/comApplication');
					}
				}
			}
			
			$user_data=array(
							'selCenterName'=>$_POST['selCenterName'],
							'excd'=>$_POST['excd'],
							'eprid'=>$_POST['eprid'],
							'txtCenterCode'=>$_POST['txtCenterCode'],
							'subject_arr'=>$subject_arr
							);
			$this->session->set_userdata('examinfo',$user_data);
			
			
			$log_title ="JAIIB free Member exam apply details";
			$log_message = serialize($user_data);
			$rId = $this->session->userdata('mregid_applyexam');
			$regNo = $this->session->userdata('mregnumber_applyexam');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			/* Close User Log Actitives */
			redirect(base_url().'Applyjaiibtest/preview');
			
		}
	}
	
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	$this->db->where('remark',1);
	$compulsory_subjects = $this->master_model->getRecords('admit_card_details','','sub_cd,sub_dsc,center_code,venueid,exam_date,time',array('sub_cd'=>'ASC'));
	echo $this->db->last_query();
	exit;
	
	$old_center = $compulsory_subjects[0]['center_code'];
	
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
	
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$examinfo=$this->master_model->getRecords('exam_master');
	
	$this->db->where('exam_name',$this->session->userdata('memexcode'));
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where("center_delete",'0');
	$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
	
	$data=array('middle_content'=>'jaiibfree_comApplicationtest','user_info'=>$user_info,'examinfo'=>$examinfo,'compulsory_subjects'=>$compulsory_subjects,'center'=>$center,'old_center'=>$old_center);
	$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
	
}

public function preview(){
	
	$data = array();
	if($this->session->userdata('memexcode')==''){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	$this->db->where('remark',1);
	$this->db->where('app_update','1');
	$chk_already_apppy = $this->master_model->getRecords('admit_card_details','','admitcard_id,app_update');
						
	if(isset($chk_already_apppy) && count($chk_already_apppy) > 0 ){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where('pay_status',1);
	$this->db->where('app_update','1');
	$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
						
	if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	$sub_flag=1;
	
	$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
	if(count($subject_arr) > 0){		
		$msg='';
		$sub_flag=1;
		foreach($subject_arr as $k=>$v){
			foreach($subject_arr as $j=>$val){
				if($k!=$j){
					if($v['date']==$val['date'] && $v['session_time']==$val['session_time']){
						$sub_flag=0;
					}
				}
			}
		}
	}
	if($sub_flag==0)
	{
		$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
		redirect(base_url().'Applyjaiibtest/comApplication');
	}
	
	
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
	
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$examinfo=$this->master_model->getRecords('exam_master');
	
	
	$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where("center_delete",'0');
	$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
	
	$data=array('middle_content'=>'jaiibfree_preview','user_info'=>$user_info,'examinfo'=>$examinfo,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr'],'center'=>$center);
	$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
}


public function add_record(){
	
	$data = array();
	if($this->session->userdata('memexcode')==''){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	$this->db->where('remark',1);
	$this->db->where('app_update','1');
	$chk_already_apppy = $this->master_model->getRecords('admit_card_details','','admitcard_id,app_update');
						
	if(isset($chk_already_apppy) && count($chk_already_apppy) > 0 ){
		redirect(base_url().'Applyjaiibtest/login');
	}
	
	$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where('pay_status',1);
	$this->db->where('app_update','1');
	$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
						
	if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ){
		redirect(base_url().'Applyjaiibtest/login');
	}
	$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
	$this->db->where('exam_name',$this->session->userdata('memexcode'));
	$get_center_name = $this->master_model->getRecords('center_master','','center_name');
	
	$admitcard_image_name = $this->session->userdata('memexcode')."_".$this->session->userdata('memexprd')."_".$this->session->userdata('mregnumber_applyexam').".pdf";
	if(!empty($this->session->userdata['examinfo']['subject_arr'])){
		$update_arr = array();
		foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v){
			
			$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
			
			echo $this->db->last_query();
			exit;
			
			$update_arr_admitcard = array(
								'center_code'=>$this->session->userdata['examinfo']['selCenterName'],
								'center_name'=>$get_center_name[0]['center_name'],
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
								'admitcard_image'=>$admitcard_image_name,
								'created_on'=>date("Y-m-d H:i:s"),
								'app_update'=>1
							);
							
			if(count($get_subject_details) > 0){
				$this->master_model->updateRecord('admit_card_details',$update_arr_admitcard,array('mem_mem_no'=>$this->session->userdata('mregnumber_applyexam'),'remark'=>'1','exm_cd'=>$this->session->userdata('memexcode'),'exm_prd'=>$this->session->userdata('memexprd'),'sub_cd'=>$k));
			}
			
			
			$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('remark','1');
			$this->db->where('exm_cd',$this->session->userdata('memexcode'));
			$this->db->where('exm_prd',$this->session->userdata('memexprd'));
			$this->db->where('sub_cd',$k);
			$get_admitcard = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id');
			
			
			$update_arr_seatalloaction = array(
								'venue_code'=>$get_subject_details[0]['venue_code'],
								'session'=>$get_subject_details[0]['session_time'],
								'center_code'=>$this->session->userdata['examinfo']['selCenterName'],
								'date'=>$get_subject_details[0]['exam_date'],
								'createddate'=>date("Y-m-d H:i:s"),
							);
			
			if(count($get_subject_details) > 0){				
				$this->master_model->updateRecord('seat_allocation',$update_arr_seatalloaction,array('admit_card_id'=>$get_admitcard[0]['admitcard_id'],'subject_code'=>$k,'exam_code'=>$this->session->userdata('memexcode'),'exam_period'=>$this->session->userdata('memexprd')));
			}
			
			$update_arr_memberexam = array(
								'exam_center_code'=>$this->session->userdata['examinfo']['selCenterName'],
								'created_on'=>date("Y-m-d H:i:s"),
								'app_update'=>1
								);
			
			if(count($get_subject_details) > 0){
				$this->master_model->updateRecord('member_exam',$update_arr_memberexam,array('id'=>$get_admitcard[0]['mem_exam_id'],'regnumber'=>$this->session->userdata('mregnumber_applyexam'),'exam_code'=>$this->session->userdata('memexcode'),'pay_status'=>1,'exam_period'=>$this->session->userdata('memexprd')));
			}
			
			$log_title ="JAIIB free Member exam apply details";
			$log_message = 'All three table updated for subject code'.$k;
			$rId = $this->session->userdata('mregid_applyexam');
			$regNo = $this->session->userdata('mregnumber_applyexam');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			
		}
		
		$admitcard_pdf=genarate_admitcard($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('memexcode'),$this->session->userdata('memexprd'));
		if($admitcard_pdf != ''){
			
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			
			$final_str = 'Hello Sir/Madam <br/><br/>';
			$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
			  
			  
			$admitcard_image_name = $this->session->userdata('memexcode')."_".$this->session->userdata('memexprd')."_".$this->session->userdata('mregnumber_applyexam').".pdf";
			$attachpath = "uploads/admitcardpdf/".$admitcard_image_name;  
			
			
			
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'),'email,mobile');   
			$info_arr=array(//'to'=>$email[0]['email'],
							'to'=>'pawansing.pardeshi@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Revised Admit Letter',
							'message'=>$final_str
						); 
			$files=array($attachpath);
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				
				$log_title ="JAIIB free Member exam apply details";
				$log_message = 'Mail send successfully';
				$rId = $this->session->userdata('mregid_applyexam');
				$regNo = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				redirect(base_url().'Applyjaiibtest/mail_success');
			}else{
				
				$log_title ="JAIIB free Member exam apply details";
				$log_message = 'Mail send fail';
				$rId = $this->session->userdata('mregid_applyexam');
				$regNo = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				redirect(base_url().'Applyjaiibtest/mail_fail');
			}
		}
	}
}

public function mail_success(){
	
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
	
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$examinfo=$this->master_model->getRecords('exam_master');
	
	
	$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where("center_delete",'0');
	$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
	
	$data=array('middle_content'=>'jaiibfree_success','user_info'=>$user_info,'examinfo'=>$examinfo,'center'=>$center);
	$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
}

public function mail_fail(){
	
	
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
	
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$examinfo=$this->master_model->getRecords('exam_master');
	
	
	$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where("center_delete",'0');
	$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
	
	$data=array('middle_content'=>'jaiibfree_fail','user_info'=>$user_info,'examinfo'=>$examinfo,'center'=>$center);
	$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
}
	
	
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

	function displayExamFee()
	{
		// echo "8888"; exit;
		
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>'510423752'),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code, member_exam.exam_code');
		print_r($exam_info);
	}
	
	function displayExamFeetest()
	{
		// echo "8888"; exit;

		$centerCode = '177';
		$today_date='2022-04-25';
		$elearning_flag='Y';
		$subject_cnt=3;
		$grp_code='B1_2';

		$eprid ='122' ;
		$excd ='MjE=' ;
		$memcategory='O';
		
		$fee=0;
		$CI = & get_instance();
		// echo $CI->session->userdata('subject_cnt');
		if($subject_cnt > 0 && (base64_decode($excd) == $this->config->item('examCodeJaiib') || base64_decode($excd) == $this->config->item('examCodeDBF') || base64_decode($excd) == $this->config->item('examCodeSOB') || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65)){
			$elearning_flag = 'Y';
		}
		/*if(isset($CI->session->userdata('subject_cnt'))){
			$elearning_flag = 'Y';
		}*/
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			// echo $CI->db->last_query();exit;
			if(count($getstate) <= 0)
			{
				$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			}
			
			if(count($getstate) > 0)
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$getstate[0]['state_code'],'state_delete'=>'0'));
			
				if($grp_code!='')
				{
					if(substr($grp_code, 0, 1)=='S')
					{
						$grp_code='S1';
					}
				}
				else
				{
			
					$grp_code='B1_1';
				}
				
				//  $today_date=date('Y-m-d');
				 //XXX Added By Sagar On 30-11-2021 For allowing member to register for JAIIB after registration closed if($CI->session->userdata('regnumber') == '510505814') { $today_date = '2021-11-28'; }
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				echo "<pre>".$CI->db->last_query(); print_r($getfees);//exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getfees[0]['exempt']=='E')
					{
						$fee=$getfees[0]['fee_amount'];
					}
					else
					{
						if($elearning_flag == 'Y'){
							if($subject_cnt > 0 && (base64_decode($excd) == $this->config->item('examCodeJaiib') || base64_decode($excd) == $this->config->item('examCodeDBF') || base64_decode($excd) == $this->config->item('examCodeSOB') || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65) ){
								$el_amt = $getfees[0]['elearning_cs_amt_total'] * $subject_cnt;
								$fee = $getfees[0]['igst_tot'] + $el_amt;
							} else{
								$fee=$getfees[0]['elearning_fee_amt'].' + GST as applicable';
							}
						}else{
							$fee=$getfees[0]['fee_amount'].' + GST as applicable';
						}
						print_r($fee);
					}
				}
			}
		}
		
		echo "====Final====>".$fee;
	}
	
	
}
