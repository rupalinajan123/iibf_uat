<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Applyjaiib_zerofee extends CI_Controller {
	public function __construct()
	{
		  if($this->get_client_ip_sm() != '115.124.115.71')
		{
			exit;
		}   
		
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->helper('seatallocation_helper');
		$this->load->helper('admitcard_helper');
		$this->load->model('master_model');		
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
		$this->load->helper('cookie');
		$this->load->model('log_model');
		$this->load->model('KYC_Log_model'); 
		$this->chk_session->Check_mult_session();
	//	exit; 
		//$db = \Config\Database::connect('group_name', false);
		//$this->load->model('chk_session');
	 	// 	
	}
	function get_client_ip_sm()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
public function login(){ 
	/*if(!isset($_COOKIE["jaiib"]) || $_COOKIE["jaiib"]==0)
	{
		redirect('https://iibf.esdsconnect.com/instructoinLoginexam.php');
	}*/
	$tdate = date('Y-m-d');
	if($tdate == '2020-11-16'){
		exit;
	}
	//exit;
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
								)
							); 
			$this->form_validation->set_rules($config);
			$dataarr=array(
				'regnumber'=> $this->input->post('Username'),
			);
			if ($this->form_validation->run() == TRUE){
				$this->db->where('mem_mem_no',$this->input->post('Username'));
				$this->db->where('reg_flag','0');
				$chk_old_app = $this->master_model->getRecords('admit_card_details_jiib','','admitcard_id');
				$this->db->where('regnumber',$this->input->post('Username'));
				$this->db->where('isactive','1');
				$user_info = $this->master_model->getRecords('member_registration','','regid,regnumber,firstname,middlename,lastname,registrationtype');
				$this->db->where('mem_mem_no',$this->input->post('Username'));
				//$this->db->where('remark',1);
				$this->db->where('exm_prd',120);
				$chk_app = $this->master_model->getRecords('admit_card_details','','admitcard_id,exm_prd,exm_cd,app_update');
				//echo $this->db->last_query();
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
					if($chk_old_app[0]['reg_flag'] != 0){
						 $data['error']='<span style="">You have already updated your application</span>';
					}else{
						 redirect(base_url().'Applyjaiib_zerofee/comApplication');
					}
				 }else{
					 $data['error']='<span style="">You have already updated your application</span>';
				}
			} else{
				$data['validation_errors'] = validation_errors();
			}
		}
	$this->load->model('Captcha_model');
	$data['captcha_img'] = $this->Captcha_model->generate_captcha_img('mem_applyexam_captcha');
	$this->load->view('examlogin_zerofee',$data);
}
public function comApplication(){
	$data = array();
	if($this->session->userdata('memexcode')==''){
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	//$this->db->where('remark',1);
	$this->db->where('app_update','1');
	$chk_already_apppy = $this->master_model->getRecords('admit_card_details','','admitcard_id,app_update');
	//echo $this->db->last_query();
	if(isset($chk_already_apppy) && count($chk_already_apppy) > 0 ){ 
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	//$this->db->where('pay_status',1);
	$this->db->where('app_update','1');
	$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
	if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ){ 
		redirect(base_url().'Applyjaiib_zerofee/login');
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
						redirect(base_url().'Applyjaiib_zerofee/comApplication');
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
			redirect(base_url().'Applyjaiib_zerofee/preview');
		}
	}
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	$this->db->where('remark',1);
	$compulsory_subjects = $this->master_model->getRecords('admit_card_details_caiib','','sub_cd,sub_dsc,center_code,venueid,exam_date,time',array('sub_cd'=>'ASC'));
	//echo $this->db->last_query();
	$old_center = $compulsory_subjects[0]['center_code'];
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$examinfo=$this->master_model->getRecords('exam_master');
	
	$this->db->where('exam_name',$this->session->userdata('memexcode'));
	$this->db->where('exam_period','221'); //$this->session->userdata('memexprd')
	$this->db->where("center_delete",'0');
	$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
	//echo $this->db->last_query();
	$data=array('middle_content'=>'jaiibfree_comApplication','user_info'=>$user_info,'examinfo'=>$examinfo,'compulsory_subjects'=>$compulsory_subjects,'center'=>$center,'old_center'=>$old_center);
	$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
}
public function preview(){
	$data = array();
	if($this->session->userdata('memexcode')==''){
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd',$this->session->userdata('memexprd'));
	$this->db->where('remark',1);
	$this->db->where('app_update','1');
	$chk_already_apppy = $this->master_model->getRecords('admit_card_details','','admitcard_id,app_update');
	if(isset($chk_already_apppy) && count($chk_already_apppy) > 0 ){
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where('pay_status',1);
	$this->db->where('app_update','1');
	$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
	if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ){
		redirect(base_url().'Applyjaiib_zerofee/login');
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
		redirect(base_url().'Applyjaiib_zerofee/comApplication');
	}
	$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	$examinfo=$this->master_model->getRecords('exam_master');
	$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
	$this->db->where('exam_period','221'); 
	$this->db->where("center_delete",'0');
	$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
	//print_r($center);//echo $this->db->last_query(); 
	$data=array('middle_content'=>'jaiibfree_preview','user_info'=>$user_info,'examinfo'=>$examinfo,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr'],'center'=>$center);
	$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
}
public function add_record(){ 
	$data = array();
	if($this->session->userdata('memexcode')==''){
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exm_cd',$this->session->userdata('memexcode'));
	$this->db->where('exm_prd','221');//$this->session->userdata('memexprd')
	$this->db->where('remark',1);
	$this->db->where('app_update','1');
	$chk_already_apppy = $this->master_model->getRecords('admit_card_details','','admitcard_id,app_update');
	if(isset($chk_already_apppy) && count($chk_already_apppy) > 0 ){
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
	$this->db->where('exam_code',$this->session->userdata('memexcode'));
	//$this->db->where('exam_period',$this->session->userdata('memexprd'));
	$this->db->where('exam_period','221');
	$this->db->where('pay_status',1);
	$this->db->where('app_update','1');
	$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
	if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ){
		redirect(base_url().'Applyjaiib_zerofee/login');
	}
	$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
	$this->db->where('exam_name',$this->session->userdata('memexcode'));
	$get_center_name = $this->master_model->getRecords('center_master','','center_name');
	$admitcard_image_name = $this->session->userdata('memexcode')."_".$this->session->userdata('memexprd')."_".$this->session->userdata('mregnumber_applyexam').".pdf";
	
	$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
							$this->db->where('exm_cd',$this->session->userdata('memexcode'));
							$this->db->where('exm_prd','121');
							//$this->db->where('remark',1);
							//$this->db->where('app_update','1');
							$admit_card_data_jaiib = $this->master_model->getRecords('admit_card_details_caiib','');
			
			
	
	// new entry in member exam 
			$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$this->db->where('exam_period','121');
			$this->db->where('pay_status',1);
			//$this->db->where('app_update','1');
			$member_exam_details = $this->master_model->getRecords('member_exam','');
			 $medium_mast = $member_exam_details[0]['exam_medium'];
			
			$inser_array=array(	'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
			 								'exam_code'=>$this->session->userdata('memexcode'),
											'exam_mode'=>'ON',//$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$admit_card_data_jaiib[0]['m_1'],//$this->session->userdata['examinfo']['medium'],
											'exam_period'=>'221',//$this->session->userdata('memexprd'),
											'exam_center_code'=>$this->session->userdata['examinfo']['selCenterName'],
											'exam_fee'=>$member_exam_details[0]['exam_fee'],
											'elected_sub_code'=>'0',//$this->session->userdata['examinfo']['selected_elect_subcode'],
											'place_of_work'=>$member_exam_details[0]['place_of_work'],
											'state_place_of_work'=>$member_exam_details[0]['state_place_of_work'],
											'pin_code_place_of_work'=>$member_exam_details[0]['pin_code_place_of_work'],
											'scribe_flag'=>$member_exam_details[0]['scribe_flag'],
											'scribe_flag_PwBD' => $member_exam_details[0]['scribe_flag_PwBD'],
											'disability' => $member_exam_details[0]['disability'],
											'sub_disability' => $member_exam_details[0]['sub_disability'],
											'created_on'=>date('y-m-d H:i:s'),
											'elearning_flag' => $member_exam_details[0]['elearning_flag'],
											'free_paid_flg' => 'F',
											'sub_el_count' => '0',
											'app_update' => '1'
											
											);
							$last_id=$this->master_model->insertRecord('member_exam',$inser_array, true);
	
	if(!empty($this->session->userdata['examinfo']['subject_arr'])){ 
		$update_arr = array();
		foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v){
			
			$this->db->group_by('subject_code');	
							$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('memexcode'),'subject_delete'=>'0','exam_period'=>'221','subject_code'=>$k),'subject_description');
			//echo $this->db->last_query(); exit;
			$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
			
			
			
			
							//print_r($last_id);
							//admit card new entry
							$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
							$this->db->where('exm_cd',$this->session->userdata('memexcode'));
							$this->db->where('exm_prd','121');
							$this->db->where('remark',1);
							//$this->db->where('app_update','1');
							$admit_card_data = $this->master_model->getRecords('admit_card_details','');
							
							
							
								$mem_ty = $admit_card_data[0]['mem_type'];					
							$admitcard_insert_array=array(
													'mem_exam_id'=>$last_id,//$this->session->userdata['examinfo']['insdet_id'],
													'center_code'=>$this->session->userdata['examinfo']['selCenterName'],
													'center_name'=>$get_center_name[0]['center_name'],
													'mem_type'=>$mem_ty,
													'mem_mem_no'=>$this->session->userdata('mregnumber_applyexam'),
													'g_1'=>$admit_card_data[0]['g_1'],
													'mam_nam_1'=>$admit_card_data[0]['mam_nam_1'],
													'mem_adr_1'=>$admit_card_data[0]['mem_adr_1'],
													'mem_adr_2'=>$admit_card_data[0]['mem_adr_2'],
													'mem_adr_3'=>$admit_card_data[0]['mem_adr_3'],
													'mem_adr_4'=>$admit_card_data[0]['mem_adr_4'],
													'mem_adr_5'=>$admit_card_data[0]['mem_adr_5'],
													'mem_adr_6'=>$admit_card_data[0]['mem_adr_6'],
													'mem_pin_cd'=>$admit_card_data[0]['mem_pin_cd'],
													'state'=>$admit_card_data[0]['state'],
													'exm_cd'=>$this->session->userdata('memexcode'),
													'exm_prd'=>'221',//$this->session->userdata('memexprd'),
													'sub_cd '=>$k,
													'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
													'sub_el_flg'=>'0',
													'm_1'=>$admit_card_data_jaiib[0]['m_1'],
													'inscd'=>$admit_card_data[0]['inscd'],
													'insname'=>$admit_card_data[0]['insname'],
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
													'mode'=>'Online',
													'scribe_flag'=>$admit_card_data[0]['scribe_flag'],
													'scribe_flag_PwBD' => $admit_card_data[0]['scribe_flag_PwBD'],
													'disability' =>'', //$admit_card_data[0]['scribe_flag_PwBD'],
													'sub_disability' => '',//$admit_card_data[0]['scribe_flag_PwBD'],
													'vendor_code'=>$get_subject_details[0]['vendor_code'],
													'remark'=>2, 
													'free_paid_flg'=>'F', 
													'app_update'=>'1', 
													'created_on'=>date('Y-m-d H:i:s'));
							
						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array, true);
			
			
			
		}
		
		
		$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('mem_exam_id',$last_id);
			$this->db->where('exm_cd',$this->session->userdata('memexcode'));
			$this->db->where('exm_prd','221');
			//$this->db->where('sub_cd',$k);
			$exam_admicard_details = $this->master_model->getRecords('admit_card_details','');
			//echo $this->db->last_query(); //die;
			//print_r($exam_admicard_details); die;
			if (count($exam_admicard_details) > 0 )
             {
                //print_r(count($exam_admicard_details)); die;                
			// admit card gen
			$password=random_password();
			foreach($exam_admicard_details as $row)
					{
					$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],
					'exam_date'=>$row['exam_date'],
					'session_time'=>$row['time'],
					'center_code'=>$row['center_code']));
					//echo 'venue qry=>'.$this->db->last_query().'<br>';
					$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$last_id,'sub_cd'=>$row['sub_cd']));
					//echo $this->db->last_query();
					//seat allocation
					$seat_number=getseat($this->session->userdata('memexcode'),$this->session->userdata['examinfo']['selCenterName'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],'221',$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
					//print_r($seat_number); die;
					if($seat_number!='')
					{
						$final_seat_number = $seat_number;
						$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
						$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
						
						$update_mem = array('pay_status'=>1);
						$this->master_model->updateRecord('member_exam',$update_mem,array('id'=>$last_id));
					}
					else
					{ 
						$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
						//echo $this->db->last_query();
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
							$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
							$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
							$rId = $get_user_regnum[0]['member_regnumber'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url().'Applyexam/refund/'.base64_encode($MerchantOrderNo));
						}
					}
				}
			 }
			
			$log_title ="JAIIB free Member exam apply details";
			$log_message = 'All three table updated for subject code'.$k.'>>'.serialize($get_subject_details);
			$rId = $this->session->userdata('mregid_applyexam');
			$regNo = $this->session->userdata('mregnumber_applyexam');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
		
		
		$admitcard_pdf=genarate_admitcard($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('memexcode'),'221');
		if($admitcard_pdf != ''){
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			$final_str = 'Hello Sir/Madam <br/><br/>';
			$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
			$admitcard_image_name = $this->session->userdata('memexcode')."_".'221'."_".$this->session->userdata('mregnumber_applyexam').".pdf";
			$attachpath = "uploads/admitcardpdf/".$admitcard_image_name;  
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'),'email,mobile');   
			$info_arr=array('to'=>$email[0]['email'],
							'bcc'=>'iibfdevp@esds.co.in',
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
				redirect(base_url().'Applyjaiib_zerofee/mail_success');
			}else{
				$log_title ="JAIIB free Member exam apply details";
				$log_message = 'Mail send fail';
				$rId = $this->session->userdata('mregid_applyexam');
				$regNo = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				redirect(base_url().'Applyjaiib_zerofee/mail_fail');
			}
		}
	}
}



public function mail_success(){
	
	$update_mem = array('reg_flag'=>1);
	$this->master_model->updateRecord('admit_card_details_caiib',$update_mem,array('mem_mem_no'=>$this->session->userdata('mregnumber_applyexam')));
						
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
}