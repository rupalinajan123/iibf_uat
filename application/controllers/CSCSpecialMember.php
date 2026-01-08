<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CSCSpecialMember extends CI_Controller {
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
		//$this->chk_session->chk_cscnon_member_session();
		$this->chk_session->Check_mult_session();
		$this->session->set_userdata('csc_venue_flag','P');

		if($this->get_client_ip() !='115.124.115.75') {
	    	//echo'As per the updated guidelines of BCBF, this link is deactivated from 1st April 2024.';exit;
		} 
	    //echo "<h4>Sorry for the inconvenience, we performing some maintenance for 15-20 min</h4>";
	    //exit;
		
    	//echo "<h4>Sorry for the inconvenience, we performing some maintenance for 2 hours</h4>"; exit;

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
		/*echo "Online Registrations is close for some upgradation work.";
		exit;*/ 

		$exam_code_array=array('991','1052','1054');
		//check exam active or not
		$check_exam_activation=check_exam_activate(base64_decode($this->input->get('ExId')));
		/*if($check_exam_activation==0 || !in_array(base64_decode($this->input->get('ExId')),$exam_code_array)){
			redirect(base_url().'CSCSpecialMember/accessdenied/');
		}
		$check_exam_code = base64_decode($this->input->get('ExId'));
		if($check_exam_code != 991 && $check_exam_code != 1052 && $check_exam_code != 1054){
			redirect(base_url().'CSCSpecialMember/accessdenied/');
		}*/
		$data=array();
		$data['error']='';
		$Extype = $this->input->get('Extype');
		$Mtype = $this->input->get('Mtype');
		if(isset($_POST['btnLogin'])){
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
			if($this->form_validation->run() == TRUE)
			{
  
				$resceduled_members_info=$this->master_model->getRecords('csc_resceduled_members',array('csc_resceduled_members.member_number'=> $this->input->post('Username')),'csc_resceduled_members.exam_code,member_number,already_applied,applied_date');

				if(isset($resceduled_members_info) && count($resceduled_members_info) > 0) 
                {  
					$this->db->where("member_registration.isactive",'1');
					$user_info=$this->master_model->getRecords('member_registration',array('member_registration.regnumber'=> $this->input->post('Username'),'member_registration.isactive'=> '1'),'member_registration.*');

					if(isset($user_info) && count($user_info) <= 0) 
					{
						$this->db->where("iibfbcbf_batch_candidates.hold_release_status",'3');
						$user_info=$this->master_model->getRecords('iibfbcbf_batch_candidates',array('iibfbcbf_batch_candidates.regnumber'=> $this->input->post('Username'), 'iibfbcbf_batch_candidates.is_deleted'=> '0'),'iibfbcbf_batch_candidates.candidate_id AS regid, iibfbcbf_batch_candidates.salutation AS namesub, iibfbcbf_batch_candidates.first_name AS firstname, iibfbcbf_batch_candidates.middle_name AS middlename, iibfbcbf_batch_candidates.last_name AS lastname, iibfbcbf_batch_candidates.email_id AS email, iibfbcbf_batch_candidates.mobile_no AS mobile,iibfbcbf_batch_candidates.regnumber,iibfbcbf_batch_candidates.registration_type AS registrationtype,address1,address2,address3,address4,district,city,state,pincode'); 
					}

					//print_r($user_info);die;

						if(count($user_info))
						{  
							$mysqltime=date("H:i:s");
							$user_data=array('cscnmregid'=>$user_info[0]['regid'],
											 'cscnmregnumber'=>$user_info[0]['regnumber'],
											 'cscnmnamesub'=>$user_info[0]['namesub'],
											 'cscnmfirstname'=>$user_info[0]['firstname'],
											 'cscnmmiddlename'=>$user_info[0]['middlename'],
											 'cscnmlastname'=>$user_info[0]['lastname'],
											 'cscnmemail'=>$user_info[0]['email'],
											 'csc_exam_code'=>$resceduled_members_info[0]['exam_code'],
											 'examcode'=>$resceduled_members_info[0]['exam_code'],
											 'csc_member_number'=>$resceduled_members_info[0]['member_number'],
											 'csc_already_applied'=>$resceduled_members_info[0]['already_applied'],
											 'csc_applied_date'=>$resceduled_members_info[0]['applied_date'],
											 'cscnmtimer'=>base64_encode($mysqltime),
											 'memtype'=>$user_info[0]['registrationtype'],
											 'csctype'=>'exm'
											 );
							$this->session->set_userdata($user_data);
							$sess = $this->session->userdata();
							//redirect(base_url().'CSCSpecialMember/showexam/?ExId='.$this->input->get('ExId').'&Extype='.$Extype);
							
							if(in_array($resceduled_members_info[0]['exam_code'], array(1039,1040))){
								redirect(base_url().'CSCSpecialMember/csccomApplication');
							}else{
								redirect(base_url().'CSCSpecialMember/comApplication');
							}
							
						}
						else{
							$data['error']='<span style="">Invalid Credentials</span>'; 
						}
					}
					else
					{
						$data['error']='<span style="">Invalid Credentials</span>';
					}
                }
                else
                {
					$data['error']='<span style="">Invalid Credentials</span>';
				} 
				
			}else{
				$data['validation_errors'] = validation_errors();
			}
		  
		$this->load->model('Captcha_model');                 
		$captcha_img = $this->Captcha_model->generate_captcha_img('cscnonmemlogincaptcha');
		$data['image'] = $captcha_img;
		$this->load->view('cscspecialmember/nonmember_login',$data);
	}

	public function comApplication()
	{
		$this->load->helper('iibfbcbf/iibf_bcbf_helper');
	    $scannedphoto_path = 'uploads/photograph';
	    $scannedsignaturephoto_path = 'uploads/scansignature';  
	    $bank_bc_id_card_path = 'uploads/empidproof';

	    $user_data_verified = array(
          'email_verified' => 'No'
        );
        $this->session->set_userdata('enduserinfo', $user_data_verified);

	    $corporate_bc_option = $corporate_bc_associated = '';

		$outputphoto1=$outputsign1=$photo_name=$sign_name='';
		if($this->session->userdata('csc_id'))
		{
			$this->session->unset_userdata('csc_id');
		}
		
		//$this->chk_session->chk_cscnon_member_session();

		if($this->session->userdata('cscnmregid') != '')
		{

		}else{
			redirect(base_url().'CSCSpecialMember');
		}

		$csc_exam_code = $this->session->userdata('csc_exam_code');
		$csc_exam_period = '';
		$cscnmregnumber = $this->session->userdata('cscnmregnumber');

		if(in_array($csc_exam_code, array(1039,1040)))
		{
			//$user_info=$this->master_model->getRecords('iibfbcbf_batch_candidates',array('candidate_id'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
			$user_info=$this->master_model->getRecords('iibfbcbf_batch_candidates',array('candidate_id'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')),'iibfbcbf_batch_candidates.candidate_id AS regid, iibfbcbf_batch_candidates.salutation AS namesub, iibfbcbf_batch_candidates.first_name AS firstname, iibfbcbf_batch_candidates.middle_name AS middlename, iibfbcbf_batch_candidates.last_name AS lastname, iibfbcbf_batch_candidates.email_id AS email, iibfbcbf_batch_candidates.mobile_no AS mobile');
			$csc_exam_period = '1';
		}
		else if(in_array($csc_exam_code, array(1052,1053,1054)))
		{
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
			$csc_exam_period = '998';
		}
		
		if(count($user_info) <=0)
		{
			redirect(base_url().'CSCSpecialMember');
		}

		/*echo "<pre>";print_r($user_info);
		echo "<br><pre>";print_r($_SESSION);
		echo "<br><pre>";print_r($csc_exam_code);
		echo "<br><pre>";print_r($csc_exam_period);
		die;*/

		$resceduled_members_info=$this->master_model->getRecords('csc_resceduled_members',array('csc_resceduled_members.member_number'=> $cscnmregnumber,'csc_resceduled_members.exam_code'=> $csc_exam_code),'csc_resceduled_members.exam_code,member_number,already_applied,applied_date');
		if($resceduled_members_info && count($resceduled_members_info) > 0)
		{
			if($resceduled_members_info[0]["already_applied"] == "1")
			{
				$this->session->set_flashdata('error_message','You have already applied for the exam. Kindly check your email for the exam details.');
				redirect(base_url().'CSCSpecialMember');
			}
		}else{
			redirect(base_url().'CSCSpecialMember');
		}
		

		/*$set_client_ip_address = array('182.73.101.70','115.124.115.72','115.124.115.75');
        $exam_codes_chk = array(991,1052);
        $get_ip_address = get_ip_address();
        if(in_array($this->session->userdata('examcode'),$exam_codes_chk) && in_array($get_ip_address,$set_client_ip_address) )
        {
            //echo "<br>".$get_ip_address;
        }else{
            echo "Site Under Maintenance. Please try again later.".$get_ip_address;exit;
        }*/  

		//accedd denied due to GST
		//$this->master_model->warning();
		$caiib_subjects=array();
		if(isset($_POST['btnPreviewSubmit']))  	
		{ 

			$state= $password = $var_errors = '';
			$update_member_exam_details_arr = $update_admit_card_details_arr = $update_admit_card_seat_details = $update_csc_resceduled_members_arr = array();

			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			$this->form_validation->set_rules('email','Email','trim|valid_email|xss_clean|callback_check_email_mobile_otp_verification[email]');
			//$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
			$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
			$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
			$this->form_validation->set_rules('medium','Medium','required|xss_clean');
			$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
			$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
			
			$this->form_validation->set_rules('csc_scannedphoto','Photograph','trim|required|callback_scannedphoto_upload_exist'); 

			if($this->form_validation->run()==TRUE)
			{

					$user_data_verified = array(
		            	'email_verified' => 'yes',
		            	'verified_email_val' => $user_info[0]['email']
		          	);
		          	$this->session->set_userdata('enduserinfo', $user_data_verified);

					$subject_arr=array();
					$venue=$this->input->post('venue');
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					 
					if(count($venue) >0 && count($date) && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('exam_code'=>$csc_exam_code,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$csc_exam_period,'subject_code'=>$k),'subject_description');
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
								 $capacity=csc_check_capacity($v['venue'],$v['date'],$v['session_time'],$_POST['txtCenterCode']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$_POST['txtCenterCode']);
								}
								if($msg!='')
								{
									$this->session->set_flashdata('error',$msg);
									redirect(base_url().'CSCSpecialMember/comApplication');
								}
							}
						}
						if($sub_flag==0)
						{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'CSCSpecialMember/comApplication');
						}
					}  

					/*$user_data=array( 
									'medium'=>$_POST['medium'],
									'selCenterName'=>$_POST['selCenterName'],  
									'txtCenterCode'=>$_POST['txtCenterCode'],  
									'subject_arr'=>$subject_arr 
									);
					$this->session->set_userdata('examinfo',$user_data);*/


						/*START: Update member_exam, admit_card_details & csc_resceduled_members table*/

					if(!empty($subject_arr))
					{
						
						$member_exam_details=$this->master_model->getRecords('member_exam',array('regnumber'=>$cscnmregnumber, 'exam_code'=>$csc_exam_code, 'exam_period'=>$csc_exam_period, 'pay_status' => '1'));
						if(isset($member_exam_details) && is_array($member_exam_details) && count($member_exam_details) > 0)
						{

							/*Update Member Exam Details in member_exam table*/

							$log_prev_member_exam_details["exam_medium"] = $member_exam_details[0]["exam_medium"];
							$log_prev_member_exam_details["exam_period"] = $member_exam_details[0]["exam_period"];
							$log_prev_member_exam_details["exam_center_code"] = $member_exam_details[0]["exam_center_code"];
							$log_prev_member_exam_details["modified_on"] = $member_exam_details[0]["modified_on"];

							$update_prev_member_exam_details_arr['log_date'] = date('Y-m-d H:i:s');
							$update_prev_member_exam_details_arr['log_previous_mem_exam_data'] = serialize($log_prev_member_exam_details);

							//update csc_resceduled_members
							$this->master_model->updateRecord('csc_resceduled_members',$update_prev_member_exam_details_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code));

							if(isset($_POST['medium']) && isset($_POST['txtCenterCode']) && $_POST['medium'] != "" && $_POST['txtCenterCode'] != "")
							{
								$update_member_exam_details_arr["exam_medium"] = $_POST['medium'];
								//$update_member_exam_details_arr["exam_period"] = '998';
								$update_member_exam_details_arr["exam_center_code"] = $_POST['txtCenterCode']; 
								$update_member_exam_details_arr['modified_on'] = date('Y-m-d H:i:s'); 

								//update member_exam
								$this->master_model->updateRecord('member_exam',$update_member_exam_details_arr,array('regnumber'=>$cscnmregnumber, 'exam_code'=>$csc_exam_code, 'exam_period'=>$csc_exam_period, 'pay_status' => '1')); 
							

								/*Update Admit Card Details in admit_card_details table*/

								if(isset($member_exam_details[0]["id"]) && $member_exam_details[0]["id"] > 0)
								{
									$admit_card_details_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$member_exam_details[0]["id"], 'mem_mem_no'=>$cscnmregnumber, 'exm_cd'=>$csc_exam_code, 'exm_prd'=>$csc_exam_period, 'remark' => '1'));
									if(isset($admit_card_details_details) && is_array($admit_card_details_details) && count($admit_card_details_details) > 0){

										$log_prev_admit_card_details["mem_exam_id"] = $admit_card_details_details[0]["mem_exam_id"];
										$log_prev_admit_card_details["center_code"] = $admit_card_details_details[0]["center_code"];
										$log_prev_admit_card_details["center_name"] = $admit_card_details_details[0]["center_name"];
										$log_prev_admit_card_details["m_1"] = $admit_card_details_details[0]["m_1"];
										$log_prev_admit_card_details["venueid"] = $admit_card_details_details[0]["venueid"];
										$log_prev_admit_card_details["venue_name"] = $admit_card_details_details[0]["venue_name"];
										$log_prev_admit_card_details["venueadd1"] = $admit_card_details_details[0]["venueadd1"];
										$log_prev_admit_card_details["venueadd2"] = $admit_card_details_details[0]["venueadd2"];
										$log_prev_admit_card_details["venueadd3"] = $admit_card_details_details[0]["venueadd3"];
										$log_prev_admit_card_details["venueadd4"] = $admit_card_details_details[0]["venueadd4"];
										$log_prev_admit_card_details["venueadd5"] = $admit_card_details_details[0]["venueadd5"];
										$log_prev_admit_card_details["venpin"] = $admit_card_details_details[0]["venpin"];
										$log_prev_admit_card_details["exam_date"] = $admit_card_details_details[0]["exam_date"];
										$log_prev_admit_card_details["time"] = $admit_card_details_details[0]["time"];
										$log_prev_admit_card_details["vendor_code"] = $admit_card_details_details[0]["vendor_code"];
										$log_prev_admit_card_details["pwd"] = $admit_card_details_details[0]["pwd"];
										$log_prev_admit_card_details["seat_identification"] = $admit_card_details_details[0]["seat_identification"];
										$log_prev_admit_card_details["modified_on"] = $admit_card_details_details[0]["modified_on"];

										$update_prev_admit_card_details_arr['log_date'] = date('Y-m-d H:i:s');
										$update_prev_admit_card_details_arr['log_previous_admit_card_data'] = serialize($log_prev_admit_card_details);

										//update csc_resceduled_members
										$this->master_model->updateRecord('csc_resceduled_members',$update_prev_admit_card_details_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code));


										if(!empty($subject_arr))
										{
											foreach($subject_arr as $k=>$v)
											{
													  
													$query='(exam_date = "0000-00-00" OR exam_date = "")';
													$this->db->where($query);
													$this->db->where('session_time=','');
													if($this->session->userdata['csc_venue_flag'] == 'F'){
														$this->db->where('venue_flag','F');
													}elseif($this->session->userdata['csc_venue_flag'] == 'P'){
														$this->db->where('venue_flag','P');
													}
													$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'center_code'=>$_POST['txtCenterCode']));

													$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$csc_exam_code,'center_code'=>$_POST['txtCenterCode'],'exam_period'=>$csc_exam_period,'center_delete'=>'0'));

												$update_admit_card_details_arr["center_code"] = $getcenter[0]['center_code'];
												$update_admit_card_details_arr["center_name"] = $getcenter[0]['center_name'];
												$update_admit_card_details_arr["m_1"] = $_POST['medium'];
												$update_admit_card_details_arr["venueid"] = $get_subject_details[0]['venue_code'];
												$update_admit_card_details_arr["venue_name"] = $get_subject_details[0]['venue_name'];
												$update_admit_card_details_arr["venueadd1"] = $get_subject_details[0]['venue_addr1'];
												$update_admit_card_details_arr["venueadd2"] = $get_subject_details[0]['venue_addr2'];
												$update_admit_card_details_arr["venueadd3"] = $get_subject_details[0]['venue_addr3'];
												$update_admit_card_details_arr["venueadd4"] = $get_subject_details[0]['venue_addr4'];
												$update_admit_card_details_arr["venueadd5"] = $get_subject_details[0]['venue_addr5'];
												$update_admit_card_details_arr["venpin"] = $get_subject_details[0]['venue_pincode'];
												$update_admit_card_details_arr["exam_date"] = $v['date'];
												$update_admit_card_details_arr["time"] = $v['session_time'];
												$update_admit_card_details_arr["vendor_code"] = $get_subject_details[0]['vendor_code'];

												$update_admit_card_details_arr["modified_on"] = date('Y-m-d H:i:s');

												/*$update_admit_card_details_arr["pwd"] = $admit_card_details_details[0]["pwd"];
												$update_admit_card_details_arr["seat_identification"] = $admit_card_details_details[0]["seat_identification"];*/ 
												
												//update admit_card_details
												$this->master_model->updateRecord('admit_card_details',$update_admit_card_details_arr,array('mem_exam_id'=>$member_exam_details[0]["id"], 'mem_mem_no'=>$cscnmregnumber, 'exm_cd'=>$csc_exam_code, 'exm_prd'=>$csc_exam_period, 'remark' => '1'));  

												$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$member_exam_details[0]["id"], 'mem_mem_no'=>$cscnmregnumber, 'exm_cd'=>$csc_exam_code, 'exm_prd'=>$csc_exam_period, 'remark' => '1'));
												if(count($exam_admicard_details) > 0)
												{  
													$password=random_password();
													foreach($exam_admicard_details as $row)
													{
														$query='(exam_date = "0000-00-00" OR exam_date = "")';
														$this->db->where($query);
														$this->db->where('session_time=','');
														if($this->session->userdata['csc_venue_flag'] == 'F'){
															$this->db->where('venue_flag','F');
														}elseif($this->session->userdata['csc_venue_flag'] == 'P'){
															$this->db->where('venue_flag','P');
														}
														$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
														$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$member_exam_details[0]["id"],'sub_cd'=>$row['sub_cd']));
														//echo $this->db->last_query().'<br>';
														$seat_number=getseat($row['exm_cd'],$row['center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$row['exm_prd'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
														if($seat_number!='')
														{
															$final_seat_number = $seat_number; 
															$update_admit_card_seat_details["pwd"] = $password;
															$update_admit_card_seat_details["seat_identification"] = $final_seat_number;
															$update_admit_card_seat_details["modified_on"] = date('Y-m-d H:i:s');

															$this->master_model->updateRecord('admit_card_details',$update_admit_card_seat_details,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
														}
														else
														{
															$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
															if(count($admit_card_details) > 0)
															{
																$log_title ="CSC Seat number already allocated id:".$cscnmregnumber;
																$log_message = serialize($exam_admicard_details);
																$rId = $admit_card_details[0]['admitcard_id'];
																$regNo = $cscnmregnumber;
																storedUserActivity($log_title, $log_message, $rId, $regNo);
															}
															else
															{
																$log_title ="CSC Fail user seat allocation id:".$cscnmregnumber;
																$log_message = serialize($exam_admicard_details);
																$rId = $cscnmregnumber;
																$regNo = $cscnmregnumber;
																storedUserActivity($log_title, $log_message, $rId, $regNo);
																//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
															}
														}
													}
												
													/*START: Generate & Send Admit Card to candidate */

													$admitcard_pdf='';

													$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
													$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
													$exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$csc_exam_code));
													//Query to get Medium	
													$this->db->where('exam_code',$csc_exam_code);
													$this->db->where('exam_period',$csc_exam_period);
													$this->db->where('medium_code',$_POST['medium']);
													$this->db->where('medium_delete','0');
													$medium=$this->master_model->getRecords('medium_master','','medium_description');
													//Query to get Center
													$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$csc_exam_code,'center_code'=>$_POST['txtCenterCode'],'exam_period'=>$csc_exam_period,'center_delete'=>'0'));
													//Exam Mode
													$mode='';
													if(isset($member_exam_details[0]['exam_mode']) && $member_exam_details[0]['exam_mode']=='ON')
													{
														$mode='Online';
													}
													else if(isset($member_exam_details[0]['exam_mode']) && $member_exam_details[0]['exam_mode']=='OF')
													{
														$mode='Offline';
													} 

													$decpass = '';
													include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
													$key = $this->config->item('pass_key');
													$aes = new CryptAES();
													$aes->set_key(base64_decode($key));
													$aes->require_pkcs5();
													$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));

													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));  

													$str_email_html = '<table width="40%" cellspacing="5" cellpadding="0" bgcolor="#FFFFCC" align="center" style="border:1px solid #ddd; font-family:Arial, Helvetica, sans-serif; font-size:14px;" border="1">
														  <thead>
														  <tr bgcolor="">
														    <td colspan="2"  align="center"><h2 style="line-height:24px; margin:10px 0;">Exam Application Details</h2></td>
														  </tr>
														  </thead>
														  <tbody>
														  <tr>
														    <td colspan="2" style="padding:10px;" width="100%"><p>Dear '.$userfinalstrname.'</p>
														      <p>You have been Registered for the Examination as per the details given under.
														Please  print/save/note down your Exam Application Details for future reference.</p></td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														      <p><strong>Registration No./Login ID : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$cscnmregnumber.'</td>
														  </tr> 
														  <tr >
														    <td style="padding:5px 10px;;" width="35%">
														      <p><strong>Name of the Applicant : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$userfinalstrname.'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														      <p><strong>Exam Name : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$exam_info[0]['description'].'</td>
														  </tr> 
														  <tr >
														    <td style="padding:5px 10px;" width="35%" valign="top">
														      <p><strong>Address : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$user_info[0]['address1'].'<br>'.$user_info[0]['address2'].'<br>'.$user_info[0]['address3'].'<br>'.$user_info[0]['address4'].'<br>'.$user_info[0]['district'].'<br>'.$user_info[0]['city'].'<br>'.$user_info[0]['state_name'].'<br'.$user_info[0]['pincode'].'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														    <p><strong>Email Id : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%"><span class="Object" role="link" id="OBJ_PREFIX_DWT254_ZmEmailObjectHandler"><a href="mailto:'.$user_info[0]['email'].'" target="_blank">'.$user_info[0]['email'].'</a></span></td>
														  </tr> 
														  
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														      <p><strong>Medium : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$medium[0]['medium_description'].'
														</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%"><p><strong>Centre Name : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%">'.$getcenter[0]['center_name'].'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%"><p><strong>Centre Code : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%">'.$getcenter[0]['center_code'].'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%"><p><strong>Mode : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%">'.$mode.'</td>
														  </tr> 

														  <tr><td colspan="2">
														  <div style="padding:5px 10px;" width="35%">
														PLEASE NOTE: <br>
														<br>Please find the attached admit card file. <strong>Kindly download and save it for your reference.</strong></div></td></tr>
														 
														</tbody>
														</table>'; 
		 
													$info_arr=array('to'=>$user_info[0]['email'],
																	/*'cc'=>'iibfdevp@esds.co.in',*/
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'],
																	'message'=>$str_email_html
																	);

													//get admit card										 	
													$admitcard_pdf=genarate_admitcard($cscnmregnumber,$csc_exam_code,$csc_exam_period);				
													if($admitcard_pdf)
													{			
														$update_csc_resceduled_members_arr['already_applied'] = '1';
														$update_csc_resceduled_members_arr['applied_date'] = date('Y-m-d H:i:s'); 

														//update csc_resceduled_members
														$this->master_model->updateRecord('csc_resceduled_members',$update_csc_resceduled_members_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code));

														$files = $admitcard_pdf;  
														$this->Emailsending->mailsend_attch($info_arr,$files);
													}
												 
													/*END: Generate & Send Admit Card to candidate */

												}		
												else
												{
													$this->session->set_flashdata('Error','Something went wrong!!');
													redirect(base_url().'CSCSpecialMember/comApplication');
												}

											}
										}else{
											$this->session->set_flashdata('Error','Something went wrong!!');
											redirect(base_url().'CSCSpecialMember/comApplication');
										}

										  
									}
								}
							}



						
						}

						$logs_updated_data_arr = array();
						if (count($update_member_exam_details_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_member_exam_details_arr);
						}
						if (count($update_admit_card_details_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_admit_card_details_arr);
						}
						if (count($update_admit_card_seat_details) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_admit_card_seat_details);
						}
						if (count($update_csc_resceduled_members_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_csc_resceduled_members_arr);
						}


						//update csc_resceduled_members
						$log_updated_data_arr['log_updated_data'] = serialize($logs_updated_data_arr);
						$this->master_model->updateRecord('csc_resceduled_members',$log_updated_data_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code)); 

						/*END: Update member_exam, admit_card_details & csc_resceduled_members table*/ 
	 

						/* User Log Activities : Bhushan */
						$log_title ="CSC Rescheduled Non Member exam apply details";
						$log_message = serialize($logs_updated_data_arr);
						$rId = $this->session->userdata('cscnmregid');
						$regNo = $this->session->userdata('cscnmregnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */
						//redirect(base_url().'CSCSpecialMember/preview');
						redirect(base_url().'CSCSpecialMember/acknowledge/');

					}
					else{
						$this->session->set_flashdata('Error','Something went wrong!!');
						redirect(base_url().'CSCSpecialMember/comApplication');
					}
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
			redirect(base_url().'CSCSpecialMember/');	
		}
		//check exam acivation
		$check_exam_activation=check_exam_activate($this->session->userdata('examcode'));
		if($check_exam_activation==0)
		{
			//redirect(base_url().'CSCSpecialMember/accessdenied/');
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

		if($this->session->userdata('examcode') == "1053"){
			//$this->db->where("center_master.vendor","csc");
		}

		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
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

			if($this->session->userdata('examcode') == "1053"){
				//$this->db->where("center_master.vendor","csc");
			}

			$this->db->group_by('center_master.center_name');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
 

			####### get compulsory subject list##########
			$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$examinfo[0]['exam_period']),'',array('subject_code'=>'ASC'));
		}
		if(count($examinfo)<=0)
		{
			//redirect(base_url().'CSCSpecialMember/examlist');
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

		
		


		/*OLD BCBF Inst Mater Dropdown*/
        $old_bcbf_institute_data = array();
        $ExamCode = $this->session->userdata('examcode');
        if($ExamCode == 1052 || $ExamCode == 1054) 
        { 
            $this->db->where('is_deleted', '0');
            $old_bcbf_institute_data = $this->master_model->getRecords('bcbf_old_exam_institute_master', '', '', array('institute_name' => 'asc')); 
        }
        /*OLD BCBF Inst Mater Dropdown*/

		//subject information
		$caiib_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E'));
		if($cookieflag==0)
		{	
			$data=array('middle_content'=>'cscspecialmember/exam_apply_cms_msg');
		}
		else
		{
			$data=array('middle_content'=>'cscspecialmember/comApplication','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects, 'old_bcbf_institute_data'=>$old_bcbf_institute_data);
		}
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}


	/******** START : CSC CANDIDATES EXAM APPLICATION FUNCTION ********/
    public function csccomApplication($enc_exam_code='0', $enc_candidate_id='0')
    {      
    	$this->load->model('iibfbcbf/Iibf_bcbf_model'); 
    	$this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
    	
    	$user_data_verified = array(
          'email_verified' => 'No'
        );
        $this->session->set_userdata('enduserinfo', $user_data_verified);

 	    if($this->session->userdata('csc_id'))
		{
			$this->session->unset_userdata('csc_id');
		}
		
		//$this->chk_session->chk_cscnon_member_session();

		if($this->session->userdata('cscnmregid') != '')
		{

		}else{
			redirect(base_url().'CSCSpecialMember');
		}

		$data["csc_exam_code"] = $csc_exam_code = $this->session->userdata('csc_exam_code');
		
		$csc_exam_period = '1';
		$cscnmregnumber = $this->session->userdata('cscnmregnumber');

		if(in_array($csc_exam_code, array(1039,1040)))
		{
			//$user_info=$this->master_model->getRecords('iibfbcbf_batch_candidates',array('candidate_id'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
			$data["user_info"] = $user_info = $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates',array('candidate_id'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')),'iibfbcbf_batch_candidates.candidate_id, iibfbcbf_batch_candidates.salutation AS namesub, iibfbcbf_batch_candidates.first_name AS firstname, iibfbcbf_batch_candidates.middle_name AS middlename, iibfbcbf_batch_candidates.last_name AS lastname, iibfbcbf_batch_candidates.email_id AS email, iibfbcbf_batch_candidates.mobile_no AS mobile,address1,address2,address3,address4,district,city,state,pincode,regnumber,candidate_photo');
			$data["csc_exam_period"] = $csc_exam_period = '1';
		} 
		
		if(count($user_info) <=0)
		{
			redirect(base_url().'CSCSpecialMember');
		}

		/*echo "<pre>";print_r($user_info);
		echo "<br><pre>";print_r($_SESSION);
		echo "<br><pre>";print_r($csc_exam_code);
		echo "<br><pre>";print_r($csc_exam_period);
		die;*/

		$resceduled_members_info=$this->master_model->getRecords('csc_resceduled_members',array('csc_resceduled_members.member_number'=> $cscnmregnumber,'csc_resceduled_members.exam_code'=> $csc_exam_code),'csc_resceduled_members.exam_code,member_number,already_applied,applied_date');
		if($resceduled_members_info && count($resceduled_members_info) > 0)
		{
			if($resceduled_members_info[0]["already_applied"] == "1")
			{
				$this->session->set_flashdata('error_message','You have already applied for the exam. Kindly check your email for the exam details.');
				redirect(base_url().'CSCSpecialMember');
			}
		}else{
			redirect(base_url().'CSCSpecialMember');
		}

		$data['applied_exam_data'] = $applied_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('candidate_id'=>$user_info[0]['candidate_id'], 'exam_code'=>$csc_exam_code, 'exam_period'=>$csc_exam_period, 'pay_status'=>'2'),'',array('member_exam_id'=>'DESC'),'',1); 

		if(isset($_POST['btnPreviewSubmit']))  	
		{
			$state= $password = $var_errors = '';
			$update_member_exam_details_arr = $update_admit_card_details_arr = $update_admit_card_seat_details = $update_csc_resceduled_members_arr = array();

			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			
			$this->form_validation->set_rules('email','Email','trim|valid_email|xss_clean|callback_check_email_mobile_otp_verification[email]');
			//$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
			$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
			$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
			$this->form_validation->set_rules('medium','Medium','required|xss_clean');
			$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
			$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
			 
			$this->form_validation->set_rules('csc_scannedphoto','Photograph','trim|required|callback_scannedphoto_upload_exist'); 

			if($this->form_validation->run()==TRUE)
			{

					$user_data_verified = array(
		            	'email_verified' => 'yes',
		            	'verified_email_val' => $user_info[0]['email']
		          	);
		          	$this->session->set_userdata('enduserinfo', $user_data_verified);

					$subject_arr=array();
					$venue=$this->input->post('venue');
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					 
					if(count($venue) >0 && count($date) && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$compulsory_subjects_name = $this->master_model->getRecords('iibfbcbf_exam_subject_master',array('exam_code'=>$csc_exam_code,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$csc_exam_period,'subject_code'=>$k),'subject_description'); 
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

								  $venue_code = $v['venue'];
						          $exam_date = $v['date'];
						          $disabledDates = getcentrenonavailability($venue_code);        
						        
						          $disabled_flag = 0;
						          if(isset($disabledDates['disabledDates']) && count($disabledDates['disabledDates']) > 0 &&  in_array($exam_date, $disabledDates['disabledDates'])) { $disabled_flag = 1; }

						          if(isset($disabledDates['weeklyOff']) && $disabledDates['weeklyOff'] != '' &&  strtolower(date('l', strtotime($exam_date))) == strtolower($disabledDates['weeklyOff'])) { $disabled_flag = 1; }

						          if($disabled_flag == '0')
						          {

						          }
						          else
						          {
						            $msg = 'The venue is closed on selected exam date '.$exam_date.'. Kindy select the different exam date.';
						            $this->session->set_flashdata('error',$msg);
					                redirect(base_url().'CSCSpecialMember/csccomApplication');
						          }

						          	$chk_member_exam_id = ''; if(count($applied_exam_data) > 0) { $chk_member_exam_id = $applied_exam_data[0]['member_exam_id']; } 
					                $chk_capacity = $this->Iibf_bcbf_model->get_capacity_csc($csc_exam_code, $csc_exam_period, $_POST['txtCenterCode'], $venue_code, $exam_date, $v['session_time'], $chk_member_exam_id);
					  
					                if($chk_capacity <= 0)
					                {
					                  $this->session->set_flashdata('error','The capacity is full');
					                  redirect(base_url().'CSCSpecialMember/csccomApplication');
					                } 
							}
						}
						if($sub_flag==0)
						{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'CSCSpecialMember/csccomApplication');
						}
					}  

					/*$user_data=array( 
									'medium'=>$_POST['medium'],
									'selCenterName'=>$_POST['selCenterName'],  
									'txtCenterCode'=>$_POST['txtCenterCode'],  
									'subject_arr'=>$subject_arr 
									);
					$this->session->set_userdata('examinfo',$user_data);*/


						/*START: Update member_exam, admit_card_details & csc_resceduled_members table*/

					if(!empty($subject_arr))
					{
						 
						$member_exam_details=$this->master_model->getRecords('iibfbcbf_member_exam',array('regnumber'=>$cscnmregnumber, 'exam_code'=>$csc_exam_code, 'exam_period'=>$csc_exam_period, 'pay_status' => '1'),'',array('member_exam_id'=>'DESC'),'',1);
						if(isset($member_exam_details) && is_array($member_exam_details) && count($member_exam_details) > 0)
						{

							/*Update Member Exam Details in member_exam table*/

							$log_prev_member_exam_details["exam_medium"] = $member_exam_details[0]["exam_medium"];
							$log_prev_member_exam_details["exam_period"] = $member_exam_details[0]["exam_period"];
							$log_prev_member_exam_details["exam_centre_code"] = $member_exam_details[0]["exam_centre_code"];
							$log_prev_member_exam_details["exam_venue_code"] = $member_exam_details[0]["exam_venue_code"];
							$log_prev_member_exam_details["updated_on"] = $member_exam_details[0]["modified_on"];

							$update_prev_member_exam_details_arr['log_date'] = date('Y-m-d H:i:s');
							$update_prev_member_exam_details_arr['log_previous_mem_exam_data'] = serialize($log_prev_member_exam_details);

							//update csc_resceduled_members
							$this->master_model->updateRecord('csc_resceduled_members',$update_prev_member_exam_details_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code));

							if(isset($_POST['medium']) && isset($_POST['txtCenterCode']) && $_POST['medium'] != "" && $_POST['txtCenterCode'] != "")
							{
								$update_member_exam_details_arr["exam_medium"] = $_POST['medium'];
								//$update_member_exam_details_arr["exam_period"] = '998';
								$update_member_exam_details_arr["exam_centre_code"] = $_POST['txtCenterCode']; 
								//$update_member_exam_details_arr["exam_venue_code"] = $_POST['venue']; 
								$update_member_exam_details_arr['updated_on'] = date('Y-m-d H:i:s'); 

								//update member_exam
								$this->master_model->updateRecord('iibfbcbf_member_exam',$update_member_exam_details_arr,array('member_exam_id' => $member_exam_details[0]["member_exam_id"], 'regnumber'=>$cscnmregnumber, 'exam_code'=>$csc_exam_code, 'exam_period'=>$csc_exam_period, 'pay_status' => '1')); 
							

								/*Update Admit Card Details in admit_card_details table*/

								if(isset($member_exam_details[0]["member_exam_id"]) && $member_exam_details[0]["member_exam_id"] > 0)
								{
									$admit_card_details_details=$this->master_model->getRecords('iibfbcbf_admit_card_details',array('mem_exam_id'=>$member_exam_details[0]["member_exam_id"], 'mem_mem_no'=>$cscnmregnumber, 'exm_cd'=>$csc_exam_code, 'exm_prd'=>$csc_exam_period, 'remark' => '1'));
									if(isset($admit_card_details_details) && is_array($admit_card_details_details) && count($admit_card_details_details) > 0){

										$log_prev_admit_card_details["mem_exam_id"] = $admit_card_details_details[0]["mem_exam_id"];
										$log_prev_admit_card_details["exam_centre_code"] = $admit_card_details_details[0]["exam_centre_code"];
										$log_prev_admit_card_details["exam_centre_name"] = $admit_card_details_details[0]["exam_centre_name"];
										$log_prev_admit_card_details["m_1"] = $admit_card_details_details[0]["m_1"];
										$log_prev_admit_card_details["venueid"] = $admit_card_details_details[0]["venueid"];
										$log_prev_admit_card_details["venue_name"] = $admit_card_details_details[0]["venue_name"];
										$log_prev_admit_card_details["venueadd1"] = $admit_card_details_details[0]["venueadd1"];
										$log_prev_admit_card_details["venueadd2"] = $admit_card_details_details[0]["venueadd2"];
										$log_prev_admit_card_details["venueadd3"] = $admit_card_details_details[0]["venueadd3"];
										$log_prev_admit_card_details["venueadd4"] = $admit_card_details_details[0]["venueadd4"];
										$log_prev_admit_card_details["venueadd5"] = $admit_card_details_details[0]["venueadd5"];
										$log_prev_admit_card_details["venpin"] = $admit_card_details_details[0]["venpin"];
										$log_prev_admit_card_details["exam_date"] = $admit_card_details_details[0]["exam_date"];
										$log_prev_admit_card_details["time"] = $admit_card_details_details[0]["time"];
										$log_prev_admit_card_details["vendor_code"] = $admit_card_details_details[0]["vendor_code"];
										$log_prev_admit_card_details["pwd"] = $admit_card_details_details[0]["pwd"];
										$log_prev_admit_card_details["seat_identification"] = $admit_card_details_details[0]["seat_identification"];
										$log_prev_admit_card_details["modified_on"] = $admit_card_details_details[0]["modified_on"];

										$update_prev_admit_card_details_arr['log_date'] = date('Y-m-d H:i:s');
										$update_prev_admit_card_details_arr['log_previous_admit_card_data'] = serialize($log_prev_admit_card_details);

										//update csc_resceduled_members
										$this->master_model->updateRecord('csc_resceduled_members',$update_prev_admit_card_details_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code));


										if(!empty($subject_arr))
										{
											foreach($subject_arr as $k=>$v)
											{
													  
													$query='(exam_date = "0000-00-00" OR exam_date = "")';
													$this->db->where($query);
													$this->db->where('session_time=','');
													if($this->session->userdata['csc_venue_flag'] == 'F'){
														$this->db->where('venue_flag','F');
													}elseif($this->session->userdata['csc_venue_flag'] == 'P'){
														$this->db->where('venue_flag','P');
													}
													$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'center_code'=>$_POST['txtCenterCode']));

													$getcenter=$this->master_model->getRecords('iibfbcbf_exam_centre_master',array('exam_name'=>$csc_exam_code,'centre_code'=>$_POST['txtCenterCode'],'exam_period'=>$csc_exam_period,'centre_delete'=>'0')); 

													$getmedium=$this->master_model->getRecords('iibfbcbf_exam_medium_master',array('exam_code'=>$csc_exam_code,'exam_period'=>$csc_exam_period,'medium_code'=>$_POST['medium']));

												$update_admit_card_details_arr["exam_centre_code"] = $getcenter[0]['centre_code'];
												$update_admit_card_details_arr["exam_centre_name"] = $getcenter[0]['centre_name'];
												$update_admit_card_details_arr["m_1"] = $getmedium[0]['medium_description'];
												$update_admit_card_details_arr["venueid"] = $get_subject_details[0]['venue_code'];
												$update_admit_card_details_arr["venue_name"] = $get_subject_details[0]['venue_name'];
												$update_admit_card_details_arr["venueadd1"] = $get_subject_details[0]['venue_addr1'];
												$update_admit_card_details_arr["venueadd2"] = $get_subject_details[0]['venue_addr2'];
												$update_admit_card_details_arr["venueadd3"] = $get_subject_details[0]['venue_addr3'];
												$update_admit_card_details_arr["venueadd4"] = $get_subject_details[0]['venue_addr4'];
												$update_admit_card_details_arr["venueadd5"] = $get_subject_details[0]['venue_addr5'];
												$update_admit_card_details_arr["venpin"] = $get_subject_details[0]['venue_pincode'];
												$update_admit_card_details_arr["exam_date"] = $v['date'];
												$update_admit_card_details_arr["time"] = $v['session_time'];
												$update_admit_card_details_arr["vendor_code"] = $get_subject_details[0]['vendor_code'];

												$update_admit_card_details_arr["modified_on"] = date('Y-m-d H:i:s');

												/*$update_admit_card_details_arr["pwd"] = $admit_card_details_details[0]["pwd"];
												$update_admit_card_details_arr["seat_identification"] = $admit_card_details_details[0]["seat_identification"];*/ 

												//update iibfbcbf_member_exam
												$update_mem_exam_details_arr["exam_venue_code"] = $v['venue']; 
												$update_mem_exam_details_arr['updated_on'] = date('Y-m-d H:i:s'); 

												//update member_exam
												$this->master_model->updateRecord('iibfbcbf_member_exam',$update_mem_exam_details_arr,array('member_exam_id' => $member_exam_details[0]["member_exam_id"], 'regnumber'=>$cscnmregnumber, 'exam_code'=>$csc_exam_code, 'exam_period'=>$csc_exam_period, 'pay_status' => '1'));
												
												//update admit_card_details
												$this->master_model->updateRecord('iibfbcbf_admit_card_details',$update_admit_card_details_arr,array('mem_exam_id'=>$member_exam_details[0]["member_exam_id"], 'mem_mem_no'=>$cscnmregnumber, 'exm_cd'=>$csc_exam_code, 'exm_prd'=>$csc_exam_period, 'remark' => '1'));  

												$exam_admicard_details=$this->master_model->getRecords('iibfbcbf_admit_card_details',array('mem_exam_id'=>$member_exam_details[0]["member_exam_id"], 'mem_mem_no'=>$cscnmregnumber, 'exm_cd'=>$csc_exam_code, 'exm_prd'=>$csc_exam_period, 'remark' => '1'));

												
												if(count($exam_admicard_details) > 0)
												{  
													$password=random_password();
													
												  if(count($exam_admicard_details) > 0)
									              {
									                $this->db->where('admitcard_id <',$exam_admicard_details[0]['admitcard_id']);
									              }
									              
									              $get_admitcard_data = $this->master_model->getRecords('iibfbcbf_admit_card_details', array(
									                'seat_identification !='=>'',
									                'exm_cd' => $csc_exam_code, 
									                'exm_prd' => $csc_exam_period, 
									                'exam_date' => $v['date'], 
									                //'time' => $res['exam_time'],
									                'exam_centre_code' => $_POST['txtCenterCode'],  
									                'venueid' => $get_subject_details[0]['venue_code']
									              ), 'seat_identification', array('admitcard_id'=>'DESC'),'',1);
									              //_pq(1);
									              //echo '<br><br>'.$this->db->last_query();
									              
									              if(count($get_admitcard_data) == 0)
									              {
									                $seat_number = str_pad(1, 3, '0', STR_PAD_LEFT);
									              }
									              else
									              {
									                $seat_number = str_pad(($get_admitcard_data[0]['seat_identification']+1), 3, '0', STR_PAD_LEFT);
									              }

									              	if($seat_number!='')
													{
														$final_seat_number = $seat_number; 
														$update_admit_card_seat_details["pwd"] = $password;
														$update_admit_card_seat_details["seat_identification"] = $final_seat_number;
														$update_admit_card_seat_details["modified_on"] = date('Y-m-d H:i:s');

														$this->master_model->updateRecord('iibfbcbf_admit_card_details',$update_admit_card_seat_details,array('admitcard_id'=>$exam_admicard_details[0]['admitcard_id']));
													}

													 
												
													/*START: Generate & Send Admit Card to candidate */
 
													$admitcard_pdf='';

													$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
													$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

													$exam_info=$this->master_model->getRecords('iibfbcbf_exam_master',array('exam_code'=>$csc_exam_code)); 
													   
													//Exam Mode
													$mode='';
													if(isset($member_exam_details[0]['exam_mode']) && $member_exam_details[0]['exam_mode']=='ON')
													{
														$mode='Online';
													}
													else if(isset($member_exam_details[0]['exam_mode']) && $member_exam_details[0]['exam_mode']=='OF')
													{
														$mode='Offline';
													} 

													 
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));  

													$str_email_html = '<table width="40%" cellspacing="5" cellpadding="0" bgcolor="#FFFFCC" align="center" style="border:1px solid #ddd; font-family:Arial, Helvetica, sans-serif; font-size:14px;" border="1">
														  <thead>
														  <tr bgcolor="">
														    <td colspan="2"  align="center"><h2 style="line-height:24px; margin:10px 0;">Exam Application Details</h2></td>
														  </tr>
														  </thead>
														  <tbody>
														  <tr>
														    <td colspan="2" style="padding:10px;" width="100%"><p>Dear '.$userfinalstrname.'</p>
														      <p>You have been Registered for the Examination as per the details given under.
														Please  print/save/note down your Exam Application Details for future reference.</p></td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														      <p><strong>Registration No./Login ID : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$cscnmregnumber.'</td>
														  </tr> 
														  <tr >
														    <td style="padding:5px 10px;;" width="35%">
														      <p><strong>Name of the Applicant : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$userfinalstrname.'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														      <p><strong>Exam Name : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$exam_info[0]['description'].'</td>
														  </tr> 
														  <tr >
														    <td style="padding:5px 10px;" width="35%" valign="top">
														      <p><strong>Address : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$user_info[0]['address1'].'<br>'.$user_info[0]['address2'].'<br>'.$user_info[0]['address3'].'<br>'.$user_info[0]['address4'].'<br>'.$user_info[0]['district'].'<br>'.$user_info[0]['city'].'<br>'.$user_info[0]['state'].'<br'.$user_info[0]['pincode'].'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														    <p><strong>Email Id : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%"><span class="Object" role="link" id="OBJ_PREFIX_DWT254_ZmEmailObjectHandler"><a href="mailto:'.$user_info[0]['email'].'" target="_blank">'.$user_info[0]['email'].'</a></span></td>
														  </tr> 
														  
														  <tr >
														    <td style="padding:5px 10px;" width="35%">
														      <p><strong>Medium : </strong></p>
														    </td>
														    <td style="padding:5px 10px;" width="64%">'.$getmedium[0]['medium_description'].'
														</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%"><p><strong>Centre Name : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%">'.$getcenter[0]['centre_name'].'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%"><p><strong>Centre Code : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%">'.$getcenter[0]['centre_code'].'</td>
														  </tr>
														  <tr >
														    <td style="padding:5px 10px;" width="35%"><p><strong>Mode : </strong></p></td>
														    <td style="padding:5px 10px;" width="64%">'.$mode.'</td>
														  </tr> 

														  <tr><td colspan="2">
														  <div style="padding:5px 10px;" width="35%">
														PLEASE NOTE: <br>
														<br>Please find the attached admit card file. <strong>Kindly download and save it for your reference.</strong></div></td></tr>
														 
														</tbody>
														</table>'; 
		 
													$info_arr=array('to'=>$user_info[0]['email'],
																	//'cc'=>'iibfdevp@esds.co.in',
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'],
																	'message'=>$str_email_html
																	);

													//get admit card 
													$enc_admitcard_id = url_encode($exam_admicard_details[0]['admitcard_id']);

													$admitcard_pdf = $this->Iibf_bcbf_model->download_admit_card_pdf_single($enc_admitcard_id, 'save');				
													if($admitcard_pdf)
													{			
														$update_csc_resceduled_members_arr['already_applied'] = '1';
														$update_csc_resceduled_members_arr['applied_date'] = date('Y-m-d H:i:s'); 

														//update csc_resceduled_members
														$this->master_model->updateRecord('csc_resceduled_members',$update_csc_resceduled_members_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code));

														$files = $admitcard_pdf;  
														$this->Emailsending->mailsend_attch($info_arr,$files);
													}
												 
													/*END: Generate & Send Admit Card to candidate */

												}		
												else
												{
													$this->session->set_flashdata('Error','Something went wrong!!');
													redirect(base_url().'CSCSpecialMember/csccomApplication');
												}

											}
										}else{
											$this->session->set_flashdata('Error','Something went wrong!!');
											redirect(base_url().'CSCSpecialMember/csccomApplication');
										}

										  
									}
								}
							}



						
						}

						$logs_updated_data_arr = array();
						if (count($update_member_exam_details_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_member_exam_details_arr);
						}
						if (count($update_mem_exam_details_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_mem_exam_details_arr);
						}
						if (count($update_admit_card_details_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_admit_card_details_arr);
						}
						if (count($update_admit_card_seat_details) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_admit_card_seat_details);
						}
						if (count($update_csc_resceduled_members_arr) > 0)
						{
							$logs_updated_data_arr = array_merge($logs_updated_data_arr, $update_csc_resceduled_members_arr);
						}


						//update csc_resceduled_members
						$log_updated_data_arr['log_updated_data'] = serialize($logs_updated_data_arr);
						$this->master_model->updateRecord('csc_resceduled_members',$log_updated_data_arr,array('member_number'=>$cscnmregnumber,'exam_code'=>$csc_exam_code)); 

						/*END: Update member_exam, admit_card_details & csc_resceduled_members table*/ 
	 

						/* User Log Activities : Bhushan */
						$log_title ="CSC Rescheduled Non Member exam apply details";
						$log_message = serialize($logs_updated_data_arr);
						$rId = $this->session->userdata('cscnmregid');
						$regNo = $this->session->userdata('cscnmregnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */
						//redirect(base_url().'CSCSpecialMember/preview');
						redirect(base_url().'CSCSpecialMember/acknowledge/');

					}
					else{
						$this->session->set_flashdata('Error','Something went wrong!!');
						redirect(base_url().'CSCSpecialMember/csccomApplication');
					}
			}
			else
			{ 
				$var_errors = str_replace("<p>", "<span>", $var_errors);
				$var_errors = str_replace("</p>", "</span><br>", $var_errors);
			}
		}

      //START : GET CANDIDATES DETAILS
        
      $this->db->group_by('exam_code');
      $data['compulsory_subjects'] = $compulsory_subjects = $this->master_model->getRecords('iibfbcbf_exam_subject_master',array('exam_code'=>$csc_exam_code,'subject_delete'=>'0','group_code'=>'C'),'',array('subject_code'=>'ASC'));

      $this->db->join('iibfbcbf_exam_activation_master eam','eam.exam_code = ecm.exam_name AND eam.exam_period = ecm.exam_period');
      $data['center'] = $center = $this->master_model->getRecords('iibfbcbf_exam_centre_master ecm',array('ecm.exam_name'=>$csc_exam_code, 'eam.exam_activation_delete'=>'0', 'ecm.centre_delete'=>'0'),'',array('ecm.centre_name'=>'ASC'));
            
      $this->db->join('iibfbcbf_exam_activation_master eam','eam.exam_code = emm.exam_code AND eam.exam_period = emm.exam_period');
      $data['medium'] = $medium = $this->master_model->getRecords('iibfbcbf_exam_medium_master emm',array('emm.exam_code'=>$csc_exam_code, 'eam.exam_activation_delete'=>'0', 'emm.medium_delete'=>'0'));

      //print_r($compulsory_subjects);

      $this->db->where('iibfbcbf_exam_master.exam_code',$csc_exam_code);
	  $data['examinfo'] = $examinfo=$this->master_model->getRecords('iibfbcbf_exam_master');

      $data=array('middle_content'=>'cscspecialmember/csccomApplication','user_info'=>$user_info,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'csc_exam_code'=>$csc_exam_code,'csc_exam_period'=>$csc_exam_period,'compulsory_subjects'=>$compulsory_subjects);

      $this->load->view('cscspecialmember/nm_common_view',$data);
 
       //END : CALCULATE GROUP CODE, FEE AMOUNT, FRESH CANDIDATE COUNT & REPEATER CANDIDATE COUNT  
      
      //if($load_main_view_flag == '1') { $this->load->view('cscspecialmember/apply_exam_candidate_csc', $data); }
    }/******** END : CSC CANDIDATES EXAM APPLICATION FUNCTION ********/

  public function send_otp()
  {
    $email = strtolower($_POST['email']);
    $type  = $_POST['type'];
    if ($type == 'send_otp' || $type == 'resend_otp')
    {
       
        $sendOTPStatus = $this->send_otp_sms_email($email, 'email');
        if ($sendOTPStatus)
        {
          $status = true;
          $msg    = 'OTP successfully sent to email address. The OTP is valid for 10 minutes.';
        }
        else
        {
          $status = false;
          $msg    = 'Error occured, While sending an OTP on email id.';
        }
       
    }
    elseif ($type == 'verify_otp')
    {
      $input_otp = $_POST['otp'];

      $otp_data = $this->master_model->getRecords('member_login_otp', array('email' => $email, 'is_validate' => '0', 'otp_type' => '3'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);

      if (count($otp_data) > 0)
      {
        if ($otp_data[0]['otp'] != $input_otp)
        {
          $status = false;
          $msg = 'Please enter the correct OTP.';
        }
        else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
        {
          $status = false;
          $msg = 'The OTP has already expired.';
        }
        else
        {
          $up_data['is_validate'] = 1;
          $up_data['updated_on']  = date("Y-m-d H:i:s");
          $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

          $status = true;
          $msg = 'OTP verified successfully.';

          $user_data_verified = array(
            'email_verified' => 'yes',
            'verified_email_val' => $email
          );
          $this->session->set_userdata('enduserinfo', $user_data_verified);
        }
      }
      else
      {
        $status = false;
        $msg    = 'No record found.';
      }
    }

    $arr_email_status['status'] = $status;
    $arr_email_status['msg']    = $msg;
    echo json_encode($arr_email_status);
  }

   private function send_otp_sms_email($data, $field_type)
  {
    $data           = $data;
    // $email_id    = $email;
    $otp            = rand(100000, 999999);;
    $otp_sent_on    = date('Y-m-d H:i:s');
    $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'email_mobile_verification'));
    if ($field_type == 'email')
    {
      $email_text = $emailerstr[0]['emailer_text'];
      // $email_text = str_replace('#CANDIDATENAME#', "Test", $email_text);
      $email_text = str_replace('#OTP#', $otp, $email_text);

      $otp_mail_arr['to']      = $data;
      $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
      $otp_mail_arr['message'] = $email_text;
      $email_sms_response = $this->Emailsending->mailsend($otp_mail_arr);
    } 

    if ($email_sms_response)
    {
      if ($field_type == 'email')
      {
        $add_data['email']    = $data;
        $add_data['otp_type'] = '3';
      } 

      $add_data['otp']            = $otp;
      $add_data['is_validate']    = '0';
      $add_data['otp_expired_on'] = $otp_expired_on;
      $add_data['created_on']     = $otp_sent_on;

      $this->db->insert('member_login_otp ', $add_data);
      return true;
    }
    else
    {
      return false;
    }
  }

  //START : ADDED BY SAGAR & ANIL ON 2024-08-27. SERVER SIDE VALIDATION TO CHECK THE EMAIL & MOBILE IS VERIFIED CORRECTLY OR NOT
  function check_email_mobile_otp_verification($str = '', $type = '')
  {
    $flag = '';
    $message = 'Please verify the email';

    if ($type != '' && ($type == 'email' || $type == 'mobile'))
    {
      $this->db->where_in('otp_type', array(3, 4));
      $this->db->limit(1);
      $otp_data = $this->master_model->getRecords('member_login_otp', array($type => $str), 'email, otp, is_validate, created_on, DATE(otp_expired_on) AS OtpExpiryDate', array('otp_id' => 'DESC'));
      if (count($otp_data) > 0)
      {
        if ($otp_data[0]['is_validate'] == '1' && $otp_data[0]['OtpExpiryDate'] >= date('Y-m-d'))
        {
          $flag = 'success';
        }
        else
        {
          $message = 'The OTP is not verified for ' . $type . ' ' . $str;
        }
      }
    }

    if ($flag == 'success')
    {
      return true;
    }
    else
    {
    	$user_data_verified = array(
        	'email_verified' => 'No'
      	);
      	$this->session->set_userdata('enduserinfo', $user_data_verified);

      $this->form_validation->set_message('check_email_mobile_otp_verification', $message);
      return false;
    }
  }

	public function check_captcha_userlogin($code){
		if(!isset($this->session->cscnonmemlogincaptcha) && empty($this->session->cscnonmemlogincaptcha)){
			return false;
		}
		if($code == '' || $this->session->cscnonmemlogincaptcha != $code ){
			$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
			$this->session->set_userdata("cscnonmemlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->userlogincaptcha == $code){
			$this->session->set_userdata('cscnonmemlogincaptcha','');
			$this->session->unset_userdata("cscnonmemlogincaptcha");
			return true;
		}
	}
	public function showexam(){
		$this->chk_session->chk_cscnon_member_session();
		$examcode=$this->input->get('ExId');
		$Extype=$this->input->get('Extype');
		$flag=1;
		$checkqualifyflag=0;
		if($examcode!=''){
		   $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>base64_decode($examcode)));
		   if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0'){
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],base64_decode($examcode),$check_qualify_exam[0]['qualifying_part1']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0){
					$checkqualifyflag=1;
				}
			}
			if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0')
			{	
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],base64_decode($examcode),$check_qualify_exam[0]['qualifying_part2']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
			}
			if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0')
			{	
				$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],base64_decode($examcode),$check_qualify_exam[0]['qualifying_part3']);
				$flag=$qaulifyarry['flag'];
				$message=$qaulifyarry['message'];
				if($flag==0)
				{
					$checkqualifyflag=1;
				}
				}
			if($checkqualifyflag==0){
				$check=$this->examapplied($this->session->userdata('cscnmregnumber'),$examcode);
				if($check){
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
					$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($examcode),'misc_master.misc_delete'=>'0'),'exam_month');
					$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
					//$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
					$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
					$flag=0;
				}
			}
		}
		if($flag==1 && $checkqualifyflag==0){
			redirect(base_url().'CSCSpecialMember/examdetails/?excode2='.$examcode.'&Extype='.$Extype); 
		}else{
			$data=array('middle_content'=>'nonmember/not_eligible','check_eligibility'=>$message);
			$this->load->view('cscspecialmember/nm_common_view',$data);
		}
	}
   
   public function acknowledge()
   {
   		//print_r($this->session->userdata());
		$data=array('middle_content'=>'cscspecialmember/edif_profile_thankyou','application_number'=>$this->session->userdata('cscnmregnumber'),'password'=>base64_decode($this->session->userdata('cscnmpassword')));
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}
	 
	//##---------check mobile number alredy exist or not on edit page(Prafull)-----------## 
	 public function editmobile()
	{
		$mobile = $_POST['mobile'];
		$regid = $_POST['regid'];
		if($mobile!="" && $regid!="")
		{
			$where="( registrationtype='NM')";
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
			$where="( registrationtype='NM')";
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
	 
	 
	##GST Message
	public function GST()
	{
		 $msg= '<li>Please pay GST amount of Exam/Mem registration in order to apply for the exam.<a href="' . base_url() . 'GstRecovery/" target="new">click here</a> </li>';
		$data=array('middle_content'=>'cscspecialmember/nmmember_notification','msg'=>$msg);
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}
	##------------------ Exam list for logged in user(Vrushali)---------------##
	 
	##----------- Show error msg for non-eligible
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
		$data=array('middle_content'=>'cscspecialmember/not_eligible','check_eligibility'=>$message);
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}
	##------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
	public function examdetails()
	{		$this->chk_session->chk_cscnon_member_session();
			####check GST paid or not.	
			$GST_val=check_GST($this->session->userdata('cscnmregnumber'));
			if($GST_val==2)
			{
				redirect(base_url() . 'CSCSpecialMember/GST');
			}
			$flag=$this->checkusers(base64_decode($this->input->get('excode2')));
			if($flag==0)
			{
				redirect(base_url().'CSCSpecialMember/accessdenied/');
			}
			//check exam acivation
			$check_exam_activation=check_exam_activate(base64_decode($this->input->get('excode2')));
			if($check_exam_activation==0)
			{
				redirect(base_url().'CSCSpecialMember/accessdenied/');
			}
			if(!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p')) || validate_nonmemdata($this->session->userdata('cscnmregnumber')))
			{
				redirect(base_url().'CSCSpecialMember/notification');
			}
			$cookieflag=$exam_status=1;
			$message='';
			$applied_exam_info=array();
			$flag=1;$checkqualifyflag=0;
			$examcode=base64_decode($this->input->get('excode2'));
			//Query to check selected exam details
			$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
			if($check_qualify_exam[0]['exam_category']==1)
			{
				redirect(base_url().'CSCSpecialMember/examdetails/?excode2='.$this->input->get('excode2').'&'.'Extype='.$this->input->get('Extype'));
			}
			//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
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
			//End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
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
							$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('cscnmregnumber')));
							if(count($check_eligibility_for_applied_exam) > 0)
							{
								foreach($check_eligibility_for_applied_exam as $check_exam_status)
								{
									if($check_exam_status['exam_status']=='F')
									{
										$exam_status=0;
									}
								}
								if($exam_status==1)
								{
									$flag=0;
									$message=$check_eligibility_for_applied_exam[0]['remark'];
								}
								else if($exam_status==0)
								{
									$check=$this->examapplied($this->session->userdata('cscnmregnumber'),$this->input->get('excode2'));
									if(!$check)
									{
										$flag=1;
									}
									else
									{
										$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('excode2')),'misc_master.misc_delete'=>'0'),'exam_month');
										$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
										//$message='1Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
										$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
										$flag=0;
									}
								}
							}
							else
							{
								$check=$this->examapplied($this->session->userdata('cscnmregnumber'),$this->input->get('excode2'));
								if($check)
								{
									$check_date=$this->examdate($this->session->userdata('cscnmregnumber'),$this->input->get('excode2'));
									if(!$check_date)
									{
									$flag=1;
									}
									else
									{
										$message=$this->get_alredy_applied_examname($this->session->userdata('cscnmregnumber'),$this->input->get('excode2'));
										$flag=0;
									}
								}
								else
								{
										$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('excode2')),'misc_master.misc_delete'=>'0'),'exam_month');
										$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
										//$message='2Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
										$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
									$flag=1;
								}
							}
					}
				}
				else
				{
					$flag=1;
				}
				//Query to check where exam applied successfully or not with transaction
				$is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$this->session->userdata('cscnmregnumber'),'status'=>'1'));
			 if($is_transaction_doone >0)
			 {
				$today_date=date('Y-m-d');
				$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
				$this->db->where('exam_master.elg_mem_nm','Y');
				//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('member_exam.pay_status','1');
				$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('cscnmregnumber')));
			}
				########get Eligible createon date######
				$this->db->limit('1');
				$get_eligible_date=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_no'=>$this->session->userdata('cscregnumber_applyexam')),'eligible_master.created_on');
				$eligiblecnt=0;
				if(count($applied_exam_info) > 0 &&  count($get_eligible_date) > 0 )
				{
					if(strtotime($applied_exam_info[0]['created_on'] ) > strtotime($get_eligible_date[0]['created_on']))
					{
						$eligiblecnt=$eligiblecnt+1;
					}
				}
				if($cookieflag==0)
				{
					$data=array('middle_content'=>'cscspecialmember/exam_apply_cms_msg');
					$this->load->view('cscspecialmember/nm_common_view',$data);
				}
				if($flag==0 && $cookieflag==1)
				{
					$data=array('middle_content'=>'cscspecialmember/not_eligible','check_eligibility'=>$message);
					$this->load->view('cscspecialmember/nm_common_view',$data);
				}
				else if($eligiblecnt)
				{
						$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
						$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
						$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
						//$message='3Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
						$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
					$data=array('middle_content'=>'cscspecialmember/already_apply','check_eligibility'=>$message);
					$this->load->view('cscspecialmember/nm_common_view',$data);
				}
				else if($cookieflag==1)
				{	
					$this->db->where('fee_paid_flag','F'); 
					$this->db->where('member_no',$this->session->userdata('cscnmregnumber'));
					$this->db->where('exam_code',$examcode);
					$this->db->order_by("id", "desc");
					$eligible_info = $this->master_model->getRecords('eligible_master','','fee_paid_flag,eligible_period');
					if(count($eligible_info) > 0){
						redirect(base_url().'Remote_CSCSpecialMember/comApplication/?excode='.base64_encode($examcode).'&Exprd='.base64_encode($eligible_info[0]['eligible_period']));
					}
					$exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
					$data=array('middle_content'=>'cscspecialmember/cms_page','exam_info'=>$exam_info);
					$this->load->view('cscspecialmember/nm_common_view',$data);
				}
		}
	##-------------- check qualify exam pass/fail
	public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)
	{
		//echo $examcode;exit;
		if($examcode==NULL || $examcode=='')
		{
			redirect(base_url().'CSCSpecialMember/dashboard');
		}
		$flag=0;$exam_status=1;
		$check_qualify=array();
		$check_qualify_exam_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');
		if(count($check_qualify_exam_name) > 0)
		{
			$message='you have not cleared qualifying examination - <strong>'.$check_qualify_exam_name[0]['description'].'</strong>.';
		}
		else
		{
			$message='you have not cleared qualifying examination.';
		}
		$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
		$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('cscnmregnumber')),'exam_status,remark');
		if(count($check_qualify_exam_eligibility) > 0)
		{
			foreach($check_qualify_exam_eligibility as $check_exam_status)
			{
				if($check_exam_status['exam_status']=='F' || $check_exam_status['exam_status']=='V' || $check_exam_status['exam_status']=='D')
				{
					$exam_status=0;
				}
			}
			//if($check_qualify_exam_eligibility[0]['exam_status']=='P')
			if($exam_status==1)
			{
					//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('cscnmregnumber')));
					if(count($check_eligibility_for_applied_exam) > 0)
					{
						foreach($check_eligibility_for_applied_exam as $check_exam_status)
							{
								if($check_exam_status['exam_status']=='F')
								{
									$exam_status=0;
								}
							}
						if($exam_status==1)
						{
							$flag=0;
							if(base64_decode($this->input->get('Extype'))=='3')
							{
									$message='You have already cleared this subject as separate  Examination. Hence you cannot apply for the same.';
							}
							else
							{
								$message=$check_eligibility_for_applied_exam[0]['remark'];
							}
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						else if($exam_status==0)
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
					$qualification=0;
					$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('cscnmregnumber')),'specify_qualification');
					if(count($user_info) > 0)
					{
						$qualification=$user_info[0]['specify_qualification'];
					}
					if(base64_decode($this->input->get('Extype'))=='3')
					{
						if($qualification==91 && $examcode==19)
						{
							$flag=1;
						}
						else
						{
							$message='You are not eligible to apply for this exam, you should have CS qualification.';
						}
					}
					else
					{
						if($qualification==91 && $examcode==19)
						{
							$flag=1;
						}
						else
						{
							$message='You are not eligible to apply for this exam, you should have CS qualification.';
						}
					}
				}
			}
			$check_qualify=array('flag'=>$flag,'message'=>$message);
			return $check_qualify;
		}
	}
	##------------------ CMS Page for logged in user(PRAFULL)---------------##
	
	##------------------ Preview for applied exam,for logged in user(PRAFULL)---------------##
	public function preview()
	{
		$this->chk_session->chk_cscnon_member_session();
		$compulsory_subjects=array();
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url().'CSCSpecialMember/');
		}
		//check exam acivation
		$check_exam_activation=check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
		if($check_exam_activation==0)
		{
			redirect(base_url().'CSCSpecialMember/accessdenied/');
		}
		if($this->session->userdata['examinfo']['fee']==0 || $this->session->userdata['examinfo']['fee']=='')
		{
			$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
			redirect(base_url().'CSCSpecialMember/comApplication/');
		}

		/*$decode_exam_code = $this->session->userdata('examcode');

        if ($decode_exam_code == 1052) 
        {
            $images_flag = 0;
            if (!file_exists("uploads/empidproof/" . $this->session->userdata['examinfo']['bank_bc_id_card_file_path'])) {
                $images_flag = 1;
                //$this->session->set_flashdata('error', 'Please upload valid image(s)');
                //redirect(base_url().'CSCSpecialMember/comApplication/');
            }
        }*/

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
					redirect(base_url().'CSCSpecialMember/comApplication');
				}
			}
		}
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			redirect(base_url().'CSCSpecialMember/comApplication');
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

		//START : ADDED BY SAGAR M ON 2024-09-12        
        $exam_date_arr = $subject_arr_for_examdate = array();
        $subject_arr_for_examdate = $this->session->userdata['examinfo']['subject_arr'];
        if(count($subject_arr_for_examdate) > 0){
            foreach ($subject_arr_for_examdate as $k => $v) 
            {
              $exam_date_arr[] = $v['date']; 
            }
        }
        //END : ADDED BY SAGAR M 2024-09-12
        //print_r($exam_date_arr);die;
		$check=$this->examapplied($this->session->userdata('cscnmregnumber'),$this->session->userdata['examinfo']['excd'],$exam_date_arr);
		//echo $check;die;
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
				$data=array('middle_content'=>'cscspecialmember/exam_apply_cms_msg');
			}
			else
			{
				$data=array('middle_content'=>'cscspecialmember/exam_preview','user_info'=>$user_info,'medium'=>$medium,'center'=>$center,'misc'=>$misc,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr']);
			}
			$this->load->view('cscspecialmember/nm_common_view',$data);
		}
		else
		{
			 $get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_master.misc_delete'=>'0'),'exam_month');
			 //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
			 $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
			 $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
			 //$message='4Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
			 $message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
			 $data=array('middle_content'=>'cscspecialmember/already_apply','check_eligibility'=>$message);
			 $this->load->view('cscspecialmember/nm_common_view',$data);	
		}
	}
	public function details($order_no=NULL,$excd=NULL)
	{
	//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('cscnmregnumber')));
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_nm','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($excd),'regnumber'=>$this->session->userdata('cscnmregnumber')));
		if(count($applied_exam_info)<=0)
		{
			redirect(base_url().'CSCSpecialMember/dashboard');
		}
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',base64_decode($excd));
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		$this->db->where('exam_name',base64_decode($excd));
		$this->db->where("center_delete",'0');
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'CSCSpecialMember/dashboard/');
		}
		$data=array('middle_content'=>'cscspecialmember/exam_applied_success','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info,'payment_info'=>$payment_info);
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}
	//Detail page for non member without pay
	public function applydetails()
	{
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'CSCSpecialMember/dashboard/');
		}
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_nm','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'regnumber'=>$this->session->userdata('cscnmregnumber')));
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$this->db->where("center_delete",'0');
		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'CSCSpecialMember/dashboard/');
		}
		$data=array('middle_content'=>'cscspecialmember/exam_applied_success_withoutpay','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info);
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}
	public function examapplied($cscnmregnumber=NULL,$exam_code=NULL, $exam_date_arr=array())
	{
		//check where exam alredy apply or not
		//echo "Inn";
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

		//START : ADDED BY ANIL ON 2024-09-16 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE
		//echo "==".count($applied_exam_info);die;
        if (count($applied_exam_info) <= 0 && count($exam_date_arr) > 0)
        {
          $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
          $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
          $this->db->join('admit_card_details', 'admit_card_details.mem_exam_id = member_exam.id', 'inner');
          //$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
          //$this->db->where('exam_master.elg_mem_o', 'Y');
          $this->db->where('bulk_isdelete', '0');
          //$this->db->where('institute_id!=', '');

          $this->db->where_in('admit_card_details.exam_date', $exam_date_arr);

          //if instutute id is null/empty then check remark is 1 else no need to check remark
          //$this->db->where(" (((institute_id IS NULL OR institute_id = '') AND remark = '1') OR (institute_id IS NOT NULL AND institute_id != '')) ");       
          $this->db->where(" (((institute_id IS NULL OR institute_id = '' OR institute_id = '0') AND remark = '1') OR (institute_id IS NOT NULL AND institute_id != '' AND institute_id != '0')) ");    

          $applied_exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $cscnmregnumber));
          //echo '<br><br>4 ' . $this->db->last_query(); die;
        } //END : ADDED BY ANIL ON 2024-09-16 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE

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
	//check whether applied exam date fall in same date of other exam date(Prafull)
	public function examdate($cscnmregnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$today_date=date('Y-m-d');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$cscnmregnumber,'pay_status'=>'1'),'member_exam.exam_code');
			### checking bulk applied ######
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where('bulk_isdelete','0');
				$this->db->where('institute_id!=','');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$cscnmregnumber,'pay_status'=>'2'),'member_exam.exam_code');
			}
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
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description,exam_activation_master.exam_period');
			$month = date('Y')."-".substr($getapplied_exam_code[0]['exam_period'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($getapplied_exam_code[0]['exam_period'],0,-2);
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
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										//$msg='5Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
										$msg='You have already applied for the exam, please wait till the result is declared. period. Hence you need not apply for the same.';
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
										//$msg='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
										$msg='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
									break;
								}
							}
						}
					}
				}
			}
		return $msg;
	}
	##------------------NON MEMBER Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
	public function Msuccess()
	{	
		$this->chk_session->chk_cscnon_member_session();
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'CSCSpecialMember/dashboard/');
		}
		if(isset($_POST['btnPreview']))
		{

			// START: OLD BCBF EXTRA CHECK ADDED BY ANIL
	        $exCode = base64_decode($this->session->userdata['examinfo']['excd']);
	        $examCd_Arr = array(1052,1054); 
	        if(in_array($exCode, $examCd_Arr)){
	            $this->db->select('member_exam.*');
	            $this->db->where('member_exam.pay_status', '1');
	            $this->db->where('member_exam.exam_code', $exCode);
	            $duplicate_applied_exam_chk = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $this->session->userdata['examinfo']['eprid'], 'member_exam.regnumber' => $this->session->userdata('cscnmregnumber')));

	            $session_data['user_all_data'] = isset($this->session->userdata) ? $this->session->userdata : array();
	            $last_query = $this->db->last_query(); 
	            $current_url_with_query = current_url(true); //echo $current_url_with_query;
	            $insert_dup_oldbcbf_application_data['page_title'] = "Controller: ".$this->router->fetch_class()." & Function: ".$this->router->fetch_method();
	            $insert_dup_oldbcbf_application_data['url'] = $current_url_with_query;
	            $insert_dup_oldbcbf_application_data['description'] = $last_query;
	            $insert_dup_oldbcbf_application_data['member_no'] = $this->session->userdata('cscnmregnumber');
	            $insert_dup_oldbcbf_application_data['exam_code'] = base64_decode($this->session->userdata['examinfo']['excd']);
	            $insert_dup_oldbcbf_application_data['exam_period'] = $this->session->userdata['examinfo']['eprid'];
	            $insert_dup_oldbcbf_application_data['session_data'] = serialize($session_data);
	            $insert_dup_oldbcbf_application_data['admit_card_details_data'] = serialize($duplicate_applied_exam_chk);
	           
	            $inser_id_oldbcbf = $this->master_model->insertRecord('check_dup_application_data', $insert_dup_oldbcbf_application_data, true);

	            $chk_admit_card_tbl = array();
	            
	            if(isset($duplicate_applied_exam_chk) && count($duplicate_applied_exam_chk) > 0)
	            {  

	            	$this->db->select('member_exam.*');
	            	$this->db->where("created_on BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()", null, false);
		            $this->db->where('member_exam.pay_status', '1');
		            $this->db->where('member_exam.exam_code', $exCode);
		            $duplicate_applied_exam_secd_chk = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $this->session->userdata['examinfo']['eprid'], 'member_exam.regnumber' => $this->session->userdata('cscnmregnumber')));
		            $last_query_secd = $this->db->last_query();
		            if(isset($duplicate_applied_exam_secd_chk) && count($duplicate_applied_exam_secd_chk) > 0)
		            {
		            	if(isset($inser_id_oldbcbf) && $inser_id_oldbcbf > 0){
		            		$description_secd = $last_query." == secd_chk == ".$last_query_secd;
		                    $this->master_model->updateRecord('check_dup_application_data', array('duplicate_application' => 1,'description' => $description_secd), array('id' => $inser_id_oldbcbf));
		                } 
		                redirect(base_url() . 'CSCSpecialMember/accessdenied_already_apply');
		            }else{
		            	if(isset($this->session->userdata['examinfo']['subject_arr']) && count($this->session->userdata['examinfo']['subject_arr']) > 0)
		            	{
		            		foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
			            	{ 
			            		if($v['date'] != ""){

			            			$this->db->select('admit_card_details.*'); 				            	
					            	$this->db->where('admit_card_details.exam_date', $v['date']);
					            	$this->db->where('admit_card_details.exm_cd', $exCode);
					            	$this->db->where('admit_card_details.remark', '1');
					            	$chk_admit_card_tbl = $this->master_model->getRecords('admit_card_details', array('admit_card_details.exm_prd' => $this->session->userdata['examinfo']['eprid'], 'admit_card_details.mem_mem_no' => $this->session->userdata('cscnmregnumber')));

					            	$last_query_admit_card = $this->db->last_query();

					            	if($chk_admit_card_tbl && count($chk_admit_card_tbl) > 0){

					            		if(isset($inser_id_oldbcbf) && $inser_id_oldbcbf > 0){
					            			$description_admit_card = $last_query." == admit_card_chk == ".$last_query_admit_card;
						                    $this->master_model->updateRecord('check_dup_application_data', array('duplicate_application' => 1,'description' => $description_admit_card), array('id' => $inser_id_oldbcbf));
						                } 
						                redirect(base_url() . 'CSCSpecialMember/accessdenied_already_apply');
					            	} 
			            		}
			            	}
		            	}
		            } 
	                
	            }
	            //echo $this->db->last_query(); die;
	        }    
	        // END: OLD BCBF EXTRA CHECK ADDED BY ANIL

			$amount=getExamFee($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),'N');
			$inser_array=array(	'regnumber'=>$this->session->userdata('cscnmregnumber'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$amount,
											'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
											'created_on'=>date('y-m-d H:i:s')
											);
			if($insert_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				//echo $this->session->userdata['examinfo']['fee'];
				$this->session->userdata['examinfo']['insert_id']=$insert_id;
				$update_array=array();
				//update an array for images
				/*if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')),'scannedsignaturephoto');
					$singname=$sing_name[0]['scannedsignaturephoto'];
				}*/
				//check if email is unique
				$where="( registrationtype='NM')";
				$this->db->where($where);
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email'],'isactive'=>'1'));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));	
				}
				// check if mobile is unique
				$where="( registrationtype='NM')";
				$this->db->where($where);
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile'],'isactive'=>'1'));
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));	
				}

				$sess_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
                if($sess_exam_code == 1052 || $sess_exam_code == 1054){
                    $update_array = array_merge($update_array, array("ippb_emp_id" => $this->session->userdata['examinfo']['ippb_emp_id']));
                    $update_array = array_merge($update_array, array("name_of_bank_bc" => $this->session->userdata['examinfo']['name_of_bank_bc']));
                    $update_array = array_merge($update_array, array("date_of_commenc_bc" => $this->session->userdata['examinfo']['date_of_commenc_bc'])); 
                    if ($this->session->userdata['examinfo']['bank_bc_id_card_filename'] != '') {
                        $update_array = array_merge($update_array, array("bank_bc_id_card" => $this->session->userdata['examinfo']['bank_bc_id_card_filename']));
                        $photo_name   = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('nmregid'), 'regnumber' => $this->session->userdata('cscnmregnumber')), 'bank_bc_id_card');
                        $bank_bc_id_card_filename = $photo_name[0]['bank_bc_id_card'];
                    }

                    $update_array = array_merge($update_array, array("are_you_corporate_bc" => $this->session->userdata['examinfo']['are_you_corporate_bc']));

                    $corporate_bc_option = $this->session->userdata['examinfo']['corporate_bc_option'] != "" ? $this->session->userdata['examinfo']['corporate_bc_option'] : '';
                    $update_array = array_merge($update_array, array("corporate_bc_option" => $corporate_bc_option));

        			$corporate_bc_associated = $this->session->userdata['examinfo']['corporate_bc_associated'] != "" ? $this->session->userdata['examinfo']['corporate_bc_associated'] : ''; 
        			$update_array = array_merge($update_array, array("corporate_bc_associated" => $corporate_bc_associated));
        			   
                }

				if(count($update_array) > 0)
				{
					$update_array['editedon'] = date('Y-m-d H:i:s');
					$update_array['editedby'] = "Candidate";
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber')));
				/* User Log Activities : Bhushan */
				$log_title ="Non Member update profile during exam apply";
				$log_message = serialize($update_array);
				$rId = $this->session->userdata('cscnmregid');
				$regNo = $this->session->userdata('cscnmregnumber');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				/* Close User Log Actitives */
				}
				 //redirect(base_url().'CSCSpecialMember/connect');
				redirect(base_url().'CSC_connect/User.php');
			}
		}
		else
		{
			redirect(base_url().'CSCSpecialMember/dashboard/');
		}
	}
		public function 	connect()
		{
			$this->load->view('cscspecialmember/csc_wallet');
			// redirect(base_url().'CSC_connect/connect');
		}
	##------------------Exam apply with SBI Payment Gate-way(PRAFULL)---------------##
	public function wallet_make_payment()
	{
		$csc_id=$this->uri->segment('3');
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		if(!$this->session->userdata('examinfo'))
		{
			redirect('http://iibf.org.in/');
		}
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
			redirect('http://iibf.org.in/');
		}
		if(isset($csc_id) && $csc_id!='')
		{
			if(!$this->session->userdata('csc_id'))
			{
				$this->session->set_userdata('csc_id',$csc_id);
			}
			else
			{
				redirect('http://iibf.org.in/');
			}
			//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
			$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('cscnmregnumber'),'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
			if(count($checkpayment) > 0)
			{
				$endTime = date("Y-m-d H:i:s",strtotime("+60 minutes",strtotime($checkpayment[0]['date'])));
				 $current_time= date("Y-m-d H:i:s");
				if(strtotime($current_time)<=strtotime($endTime))
				{
					$this->session->set_flashdata('error','Wait your transaction is under process!.');
					redirect(base_url().'CSCSpecialMember/comApplication');
				}
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
								//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
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
						redirect(base_url().'CSCSpecialMember/comApplication');
					}
				}
			}
			if($sub_flag==0){
				$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
				redirect(base_url().'CSCSpecialMember/comApplication');
			}
			$regno = $this->session->userdata('cscnmregnumber');//$this->session->userdata('regnumber');
      
      if (strpos(base_url(), '/staging') !== false) 
      {        
        require_once $_SERVER['DOCUMENT_ROOT'] . '/staging/BridgePG/PHP_BridgePG/BridgePGUtil.php';//STAGING URL        
      } 
      else 
      {        
        require_once $_SERVER['DOCUMENT_ROOT'] . '/BridgePG/PHP_BridgePG/BridgePGUtil.php';//PRODUCTION URL        
      }

			$rand_no = date('Ymdhims');
			$csc_success_url = base_url() . "CSCSpecialMember/csc_transsuccess";
			$csc_fail_url = base_url() . "CSCSpecialMember/csc_transfail";
			$csc_product_id = $this->config->item('csc_product_id');
			$MerchantOrderNo = $this->master_model->generate_csc_receipt_number(); //rand(10000000, 99999999);
			if($this->config->item('wallet_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				$amount=getExamFee($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),'N');
			}
			if($amount==0 || $amount=='')
			{
				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
				redirect(base_url().'CSCSpecialMember/comApplication/');
			}
			$p = array(
                    'csc_id' => $csc_id,
					'merchant_receipt_no' => $rand_no,
                    'txn_amount' => $amount,
                	'return_url' => $csc_success_url ,
                    'cancel_url' => $csc_fail_url,
                    'product_id' => $csc_product_id,
                    'merchant_txn' =>$MerchantOrderNo
                );
			// With Login Non-member
			// Non memeber / DBF Apply exam
			// Ref1 = orderid
			// Ref2 = iibfexam
			// Ref3 = member_regno
			// Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'exam_period'=>$this->session->userdata['examinfo']['eprid']),'exam_month');
			$exam_code=base64_decode($this->session->userdata['examinfo']['excd']);
			$ref4=($exam_code).$yearmonth[0]['exam_month'];
			// Create transaction
			$insert_data = array(
				'member_regnumber' 	=> $regno,
				'amount'           	=> $amount,
				'gateway'          	=> "csc",
				'date'           	=> date('Y-m-d H:i:s'),
				'pay_type'        	=> '2',
				'ref_id'           	=> $this->session->userdata['examinfo']['insert_id'],
				'description'     	=> $this->session->userdata['examinfo']['exname'],
				'status'          	=> '2',
				'exam_code'      	=> base64_decode($this->session->userdata['examinfo']['excd']),
				'pg_flag'			=>'CSC_EXM_NM',
			);
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			//$MerchantOrderNo = sbi_exam_order_id($pt_id);
			wallet_exam_order_id($pt_id,$MerchantOrderNo);
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$this->session->userdata('cscnmregnumber')."^".$ref4;
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			//set invoice details(Prafull)
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($this->session->userdata['examinfo']['excd']),'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
			$querygetcenter=$this->db->last_query();
			if(count($getcenter) > 0){
				//get state code,state name,state number.
				$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				//call to helper (fee_helper)
				$getfees=getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),'N');
				$querygetfees=$this->db->last_query();
				#########get message if capacity is full##########
				$log_title ="CSC exam invoice CSCSpecialMember  session value:".$this->session->userdata['examinfo']['excd'];
				$log_message = serialize($this->session->userdata['examinfo']['excd']);
				$rId = $regno;
				$regNo = $regno;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
			}
			#########get message if capacity is full##########
			$log_title ="CSC exam invoice CSCSpecialMember  get fee:".$querygetfees.'='.$querygetcenter;
			$log_message = serialize($getfees);
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			if($getcenter[0]['state_code']=='MAH'){
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$this->config->item('cgst_rate');
				$sgst_rate=$this->config->item('sgst_rate');
				//set an amount as per rate
				$cgst_amt=$getfees[0]['cgst_amt'];
				$sgst_amt=$getfees[0]['sgst_amt'];
				 //set an total amount
				$cs_total=$getfees[0]['cs_tot'];
				$tax_type='Intra';
			}else{
				$igst_rate=$this->config->item('igst_rate');
				$igst_amt=$getfees[0]['igst_amt'];
				$igst_total=$getfees[0]['igst_tot']; 
				$tax_type='Inter';
			}
			if($getstate[0]['exempt']=='E'){
				 $cgst_rate=$sgst_rate=$igst_rate='';	
				 $cgst_amt=$sgst_amt=$igst_amt='';	
			}	
		$invoice_insert_array=array('pay_txn_id'=>$pt_id,
							'receipt_no'=>$MerchantOrderNo,
							'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
							'center_code'=>$getcenter[0]['center_code'],
							'center_name'=>$getcenter[0]['center_name'],
							'state_of_center'=>$getcenter[0]['state_code'],
							'member_no'=>$this->session->userdata('cscnmregnumber'),
							'app_type'=>'O',
							'exam_period'=>$this->session->userdata['examinfo']['eprid'],
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
							'gstin_no'=>0,
							'created_on'=>date('Y-m-d H:i:s'));
			$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
			$query=$this->db->last_query();
			#########get message if capacity is full##########
			$log_title ="CSC exam invoice CSCSpecialMember:".$query;
			$log_message = serialize($invoice_insert_array);
			$rId = $this->session->userdata('cscnmregnumber');
			$regNo = $this->session->userdata('cscnmregnumber');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			//insert into admit card table
				//################get userdata###########
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
							//$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
							$query='(exam_date = "0000-00-00" OR exam_date = "")';
							$this->db->where($query);
							$this->db->where('session_time=','');
							if($this->session->userdata['csc_venue_flag'] == 'F'){
								$this->db->where('venue_flag','F');
							}elseif($this->session->userdata['csc_venue_flag'] == 'P'){
								$this->db->where('venue_flag','P');
							}
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
						$admitcard_insert_array=array('mem_exam_id'=>$this->session->userdata['examinfo']['insert_id'],
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
													'created_on'=>date('Y-m-d H:i:s'));
						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
					}
			}else{
				$this->session->set_flashdata('Error','Something went wrong!!');
				redirect(base_url().'CSCSpecialMember/comApplication');
			}
			/* Close Invoice functionality */
			//set cookie for Apply Exam
			applyexam_set_cookie($this->session->userdata['examinfo']['insert_id']);
			$bconn = new BridgePGUtil ();
			$bconn->set_params($p);
			$enc_text = $bconn->get_parameter_string();
			$frac = $bconn->get_fraction();
			$data = array(
			'enc_text' => $enc_text,
			'frac' => $frac,
			);
			$this->load->view('csc', $data);
		}
		else
		{
			$this->session->set_flashdata('error','something went wrong123!!');
			redirect(base_url().'CSCSpecialMember/comApplication/');
		}
	}
	public function csc_transsuccess()
	{
		$photo_name=$sign_name='';
		$valcookie= applyexam_get_cookie();
		if($valcookie){
			delete_cookie('examid');
		}

    if (strpos(base_url(), '/staging') !== false) 
    {        
      require_once $_SERVER['DOCUMENT_ROOT'] . '/staging/BridgePG/PHP_BridgePG/BridgePGUtil.php';//STAGING URL        
    } 
    else 
    {        
      require_once $_SERVER['DOCUMENT_ROOT'] . '/BridgePG/PHP_BridgePG/BridgePGUtil.php';//PRODUCTION URL        
    }

        $bconn = new BridgePGUtil ();
        $bridge_message = $bconn->get_bridge_message();
        $params = explode('|', $bridge_message);
    	//breack with pipe operators
        $fine_params = array();
        foreach ($params as $param) {
           $param = explode('=', $param);
            if (isset($param[0])) {
			   	if(isset($param[1]))
				{
			    $fine_params[$param[0]] = $param[1];
				}
			}
        }
    	$params = $fine_params;
        $transaction_id = $params['csc_txn'];
		$order_no = $params['merchant_txn'];
		$amount = $params['txn_amount'];
		$attachpath=$invoiceNumber=$admitcard_pdf='';
		$MerchantOrderNo = $params['merchant_txn']; // To DO: temp testing changes please remove it and use valid recipt id
		$transaction_no  = $params['csc_txn'];
		$merchIdVal =$params['merchant_receipt_no'];
		$encData='';
		$Bank_Code=$params['txn_mode'];
		$csc_id=$params['csc_id'];
		$product_id=$params['product_id'];
		$merchant_receipt_no=$params['merchant_receipt_no'];
		$date=$params['merchant_txn_date_time']; 
		$customer_id=$params['merchant_id'];
		if ($params['txn_status'] == '100')
		{
			$this->db->order_by('ref_id','DESC');
			$this->db->limit(1);
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			if($get_user_regnum[0]['status']==2)
			{
				######### payment Transaction ############
				$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $params['txn_status_message']." - ".$params['merchant_txn'],'auth_code' => '0300', 'bankcode' => 'csc', 'paymode' => 'wallet','callback'=>'B2B','date'=>$params['merchant_txn_date_time'],'csc_id'=>$csc_id,'product_id'=>$product_id,'merchant_receipt_no'=>$merchant_receipt_no,'customer_id'=>$customer_id);
				$this->db->order_by('id','DESC');
				$this->db->limit(1);
				$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
				if($this->db->affected_rows()){
				######### Exam Invoice Transaction ############
				$update_data = array('transaction_no'=>$transaction_no);
				$this->db->where('receipt_no',$MerchantOrderNo);
				$this->db->order_by('invoice_id','DESC');
				$this->db->limit(1);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				// Handle transaction success case 
				if(count($get_user_regnum) > 0){
				if($this->session->userdata['examinfo']['photo']!='')
					{
						$input = $this->session->userdata['examinfo']['photo'];
						//$tmp_nm = rand(0,100);
						$tmp_nm = 'p_'.$get_user_regnum[0]['member_regnumber'].'.jpg';
						$outputphoto = getcwd()."/uploads/photograph/".$tmp_nm;
						$outputphoto1 = base_url()."uploads/photograph/".$tmp_nm;
						file_put_contents($outputphoto, file_get_contents($input));
						$photo_name = $tmp_nm;
						$update_array['scannedphoto'] = $photo_name ;
						$update_array['photo_flg'] = 'Y';
					}
					if($this->session->userdata['examinfo']['signname']!='')
					{
						$inputsignature = $this->session->userdata['examinfo']['signname'];
						//$tmp_signnm = rand(0,100);
						$tmp_signnm = 's_'.$get_user_regnum[0]['member_regnumber'].'.jpg';
						$outputsign = getcwd()."/uploads/scansignature/".$tmp_signnm;
						$outputsign1 = base_url()."uploads/scansignature/".$tmp_signnm;
						file_put_contents($outputsign, file_get_contents($inputsignature));
						$sign_name = $tmp_signnm;
						$update_array['scannedsignaturephoto'] = $sign_name;
						$update_array['signature_flg'] = 'Y';
					}	
					if($photo_name!=''|| $sign_name !='')
					{
					$update_array['kyc_edit']=1;
					$update_array['kyc_status']='0';
					$update_array['images_editedon']=date('Y-m-d H:i:s');
					$update_array['images_editedby']='Candidate';
					$update_array['editedon'] = date('Y-m-d H:i:s');
					$update_array['editedby'] = "Candidate";
					$this->db->order_by('regid','DESC');
					$this->db->limit(1);
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$get_user_regnum[0]['member_regnumber']));
					}
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
				}
				//Query to get user details
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
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
								$log_title ="CSC Capacity full id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'CSCSpecialMember/refund/'.base64_encode($MerchantOrderNo));
							}
						}
					}
					$password=random_password();
					foreach($exam_admicard_details as $row)
					{
						$query='(exam_date = "0000-00-00" OR exam_date = "")';
						$this->db->where($query);
						$this->db->where('session_time=','');
						if($this->session->userdata['csc_venue_flag'] == 'F'){
							$this->db->where('venue_flag','F');
						}elseif($this->session->userdata['csc_venue_flag'] == 'P'){
							$this->db->where('venue_flag','P');
						}
						$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
						$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
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
								$log_title ="CSC Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $admit_card_details[0]['admitcard_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}
							else
							{
								$log_title ="CSC Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
							}
						}
					}
				}		
				else
				{
					redirect(base_url().'CSCSpecialMember/refund/'.base64_encode($MerchantOrderNo));
				}
				######update member_exam######
				$update_data = array('pay_status' => '1');
				$this->db->order_by('id','DESC');
				$this->db->limit(1);
				$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
				if($exam_info[0]['exam_mode']=='ON')
				{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
				{$mode='Offline';}
				else{$mode='';}
				//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				//Query to get Medium	
				$this->db->where('exam_code',$exam_info[0]['exam_code']);
				$this->db->where('exam_period',$exam_info[0]['exam_period']);
				$this->db->where('medium_code',$exam_info[0]['exam_medium']);
				$this->db->where('medium_delete','0');
				$medium=$this->master_model->getRecords('medium_master','','medium_description');
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
				$sms_template_id = 'P6tIFIwGR';
				$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
				$newstring4 = str_replace("#EXAM_DATE#", "-",$newstring3);
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
								/*'cc'=>'iibfdevp@esds.co.in',*/
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
			//get invoice	
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
					$invoice_get_query= $this->db->last_query();
								$log_title ="CSC NONMEMBER invoice get query :".$invoice_get_query;
								$log_message = serialize($getinvoice_number);
								$rId = $MerchantOrderNo;
								$regNo = $payment_info[0]['id'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
			if(count($getinvoice_number) > 0)
			{
				$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
				if($invoiceNumber)
				{
					$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
				}
				$update_data = array('invoice_no' => $invoiceNumber,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				$this->db->where('pay_txn_id',$payment_info[0]['id']);
				$this->db->order_by('invoice_id','DESC');
				$this->db->limit(1);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
				##############Get Admit card#############
				$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
			}					
			if($attachpath!='')
			{			
				$files=array($attachpath,$admitcard_pdf);	
					
				$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
				$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
				$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
				$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
				// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
				$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 15 Sep 2023

				//$this->Emailsending->mailsend($info_arr);
				$this->Emailsending->mailsend_attch($info_arr,$files);
			}
			######CSC Recon check transaction status########
					 $get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'customer_id,product_id,csc_id,receipt_no,date,amount,transaction_no,merchant_receipt_no');
					 $datajson = array();
                    // Prepare JSON Post Data
                    if(count($get_user_regnum) > 0)
					{
						$datajson['merchant_id'] = $get_user_regnum[0]['customer_id'];
						$datajson['product_id'] = $get_user_regnum[0]['product_id'];
						$datajson['csc_id'] = $get_user_regnum[0]['csc_id'];
						$datajson['merchant_txn'] = $get_user_regnum[0]['receipt_no'];
						$datajson['merchant_txn_datetime'] = $get_user_regnum[0]['date'];
						$datajson['txn_amount'] = $get_user_regnum[0]['amount'];
						$datajson['csc_txn'] = $get_user_regnum[0]['transaction_no'];
						$datajson['merchant_receipt_no'] = $get_user_regnum[0]['merchant_receipt_no'];
						$datajson['merchant_txn_status'] = 'S';
					}
                    $message_text = '';
                    foreach ($datajson as $p => $v) {
                        $message_text .= $p . '=' . $v . '|';
                    }
                    //echo $message_text;die;
                    //echo $message_text."\n\n\n\n";
                    $message_cipher = $bconn->encrypt($message_text);
                    $json_data_array = array(
                        'merchant_id' => $datajson['merchant_id'],
                        'request_data' => $message_cipher
                    );
				    $post = json_encode($json_data_array);
                    // cURL Request starts here
                    $ch = curl_init();
                    $headers = array('Content-Type: application/json');
                    curl_setopt_array($ch, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => "https://bridge.csccloud.in/v2/recon/log",
                        CURLOPT_VERBOSE => true,
                        CURLOPT_HEADER => false,
                        CURLOPT_HTTPHEADER => $headers,
                        CURLINFO_HEADER_OUT => false,
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
                        //CURLOPT_CUSTOMREQUEST => 'PUT',
                        CURLOPT_POST => 1,
                        //CURLOPT_FOLLOWLOCATION => 1,
                        CURLOPT_POSTFIELDS => $post
                    ));
                    $server_output = curl_exec($ch);
			        //echo '<pre>';
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if ($http_code == '200') {
					$xml_response = simplexml_load_string($server_output);
					$p = $bconn->decrypt($xml_response->response_data);
					$p = explode('|', $p);
					$fine_params = array();
					foreach ($p as $param) {
						$param = explode('=', $param);
						if (isset($param[0])) {
								if(isset($param[1])){
									$fine_params[$param[0]] = $param[1];
								}
						}
					}
					$p = $fine_params;
					//Insert into recon table 
					$recron_response= array();
					$recron_response=array(	'order_details_id' =>$payment_info[0]['id'],
															'response_code' => $xml_response->response_code,
															'response_status' => $xml_response->response_status,
															'merchant_id' => $p['merchant_id'],
															'merchant_txn' => $p['csc_txn'],
															'csc_txn' => $p['merchant_id'],
															'recon_reference' => $p['recon_reference'],
															'response_message' => $xml_response->response_message,
															'response_server' => $xml_response->response_server,
															'response_date' => $xml_response->response_date
														);
				$inser_id=$this->master_model->insertRecord('recron_response',$recron_response);
				}
					 curl_close($ch);
                    //echo "\n\n\n";
				#####End of recon#########
			  }
			 else
			{
				$log_title ="CSC B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
				$log_message = serialize($update_data);
				$rId = $MerchantOrderNo;
				$regNo = $get_user_regnum[0]['member_regnumber'];
				storedUserActivity($log_title, $log_message, $rId, $regNo);	
			}
			//Manage Log
			$pg_response = "encData=".serialize($params)."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logtransaction("wallet", $pg_response, $params['txn_status_message']);
			//END OF Wallet CALLBACK 
			redirect(base_url().'CSCSpecialMember/acknowledge/'.base64_encode($MerchantOrderNo));
			}
		}else{
			// Handle transaction fail case 
				$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,member_regnumber');
				if($get_user_regnum_info[0]['status']!=0 && $get_user_regnum_info[0]['status']==2){
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $params['txn_status_message']." - ".$params['merchant_txn'],'auth_code' =>'0399','bankcode' => 'csc','paymode' => 'wallet','callback'=>'B2B','customer_id'=>$params['merchant_id']);
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
					$sms_template_id = 'Jw6bOIQGg';
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
					$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
					$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);	
					$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 15 Sep 2023

					$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=".serialize($params)."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("wallet", $pg_response, $params['txn_status_message']);		
					//END OF WALLET SUCCESS
					die("Please try again...");
				}
		}
		//END OF SBI CALLBACK SUCCESS B2B
		//Main Code
		$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		redirect(base_url().'CSCSpecialMember/details/'.base64_encode($MerchantOrderNo).'/'.base64_encode($exam_info[0]['exam_code']));
	}
	public function csc_transfail()
	{	
		//Delete cookie
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}//cookie deleted
		
    if (strpos(base_url(), '/staging') !== false) 
    {        
      require_once $_SERVER['DOCUMENT_ROOT'] . '/staging/BridgePG/PHP_BridgePG/BridgePGUtil.php';//STAGING URL        
    } 
    else 
    {        
      require_once $_SERVER['DOCUMENT_ROOT'] . '/BridgePG/PHP_BridgePG/BridgePGUtil.php';//PRODUCTION URL        
    }

        $bconn = new BridgePGUtil ();
        $bridge_message = $bconn->get_bridge_message();
        $params = explode('|', $bridge_message);
    	//breack with pipe operators
        $fine_params = array();
        foreach ($params as $param) {
           $param = explode('=', $param);
            if (isset($param[0])) {
			   	if(isset($param[1]))
				{
			    $fine_params[$param[0]] = $param[1];
				}
			}
        }
    	$params = $fine_params;
		$order_no = $params['merchant_txn'];
		$attachpath=$invoiceNumber=$admitcard_pdf='';
		$MerchantOrderNo = $params['merchant_txn']; // To DO: temp testing changes please remove it and use valid recipt id
		$transaction_no  = $params['merchant_txn'];
		$merchIdVal =$params['merchant_txn'];
		$encData='';
		$Bank_Code='wallet';
		$customer_id=$params['merchant_id']; 
			///SBI CALLBACK START
			// Handle transaction 
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			if($get_user_regnum[0]['status']!=0 && $get_user_regnum[0]['status']==2){	
				$update_data = array('status' => 0,'transaction_details' => $params['txn_status_message']." - ".$params['merchant_txn'],'auth_code' =>'0399','bankcode' => 'csc','paymode' => 'wallet','callback'=>'B2B','customer_id'=>$customer_id);
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
				$sms_template_id = 'Jw6bOIQGg';
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
				$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
				$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
				$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
				//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
				$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 15 Sep 2023

				$this->Emailsending->mailsend($info_arr);
				//Manage Log
				$pg_response = "encData=".serialize($params)."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("wallet", $pg_response, $params['txn_status_message']);		
				//END OF WALLET SUCCESS
				die("Please try again...");
			}
			//END OF SBI CALLBACK
			//Main Code
			redirect(base_url().'CSCSpecialMember/fail/'.base64_encode($MerchantOrderNo));
	}
	//Show acknowlodgement to to user after transaction Failure
	public function fail($order_no=NULL)
	{
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('cscnmregnumber')));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		$data=array('middle_content'=>'cscspecialmember/exam_applied_fail','payment_info'=>$payment_info);
		$this->load->view('cscspecialmember/nm_common_view',$data);
	}
	##---------Forcefully Update profile mesage to user(prafull)-----------##
	public function notification()
	{
		$msg='';
		$flag=1;
		$user_images=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber'),'isactive'=>'1'),'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');
		  /*if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']=='') && (!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p'))))*/
		if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']=='') && (!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p'))))
		 {
			 $flag=0;
			$msg.='<li>Your Photo/signature or ID proof are not available kindly go to Edit Profile and <a href="'.base_url().'NonMember/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>';
		 }
		 if($user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
		 {
			 $flag=0;
			$msg.='<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'NonMember/profile/">click here</a> to update the, email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
		}
		if(validate_nonmemdata($this->session->userdata('cscnmregnumber')))
		 {
			 $flag=0;
			$msg.='<li>
Please check all mandatory fields in profile <a href="'.base_url().'NonMember/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';
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
			redirect(base_url().'NonMember/profile/');
		}
		$data=array('middle_content'=>'nonmember/nmmember_notification','msg'=>$msg);
		$this->load->view('nonmember/nm_common_view',$data);
	}
	//print user edit profile (Prafull)
	public function printUser()
	{
			$gender='';
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			//$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			//$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			//$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber'),'isactive'=>'1'));
			if(count($user_info) <=0)
			{
				redirect(base_url().'NonMember/dashboard');
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
			$data=array('middle_content'=>'nonmember/print_non_member_profile','user_info'=>$user_info,'qualification'=>$qualification,'idtype_master'=>$idtype_master);
			$this->load->view('nonmember/nm_common_view',$data);
	}
	// ##-------download pdf (Prafull)
	public function downloadeditprofile()
	{
			$gender=$idtype='';
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			//$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			//$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			//$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber'),'isactive'=>'1'));
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
			if(isset($idtype_master[0]['name']))
			{
				$idtype=$idtype_master[0]['name'];
			}
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
			$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
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
								<img src="'.base_url().get_img_name($this->session->userdata('cscnmregnumber'),'p').'" height="100" width="100" >
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
								<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$idtype.'</td>
							</tr>
							<tr>
								<td class="tablecontent2">ID No :</td>
								<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['idNo'].'</td>
							</tr>
								<tr>
								<td class="tablecontent2">Aadhar Card Number :</td>
								<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['aadhar_card'].'</td>
							</tr>
							<tr>
								<td class="tablecontent2">ID Proof :</td>
								<td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="'.base_url().get_img_name($this->session->userdata('cscnmregnumber'),'pr').'"  height="180" width="100"></td>
							</tr>
							<tr>
								<td class="tablecontent2">Signature :</td>
								<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="'.base_url().get_img_name($this->session->userdata('cscnmregnumber'),'s').'" height="100" width="100"></td>
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
						Your application saved successfully.<br><br><strong>Your Membership No is</strong> '.$this->session->userdata('cscnmregnumber').' <strong>and Your password is </strong>'.base64_decode($this->session->userdata('cscnmpassword')).'<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
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
			redirect(base_url().'NonMember/dashboard/');
		}
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->where('state_master.state_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber'),'isactive'=>'1'));
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
			$this->db->where('elg_mem_nm','Y');	
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'regnumber'=>$this->session->userdata('cscnmregnumber'),'pay_status'=>'1'));
			$this->db->where('medium_delete','0');
			$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			//$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
			$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4);
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
			<img src="'.base_url().get_img_name($this->session->userdata('cscnmregnumber'),'p').'" height="100" width="100" >
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
			<td class="tablecontent2">Aadhar Card Number :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['aadhar_card'].'</td>
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
			<td class="tablecontent2">Exam Period :</td>
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
			redirect(base_url().'NonMember/dashboard/');
		}
			$qualification=array();
			$this->db->select('member_registration.*,state_master.state_name');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->where('state_master.state_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('cscnmregid'),'regnumber'=>$this->session->userdata('cscnmregnumber'),'isactive'=>'1'));
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
			$this->db->where('elg_mem_nm','Y');	
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'regnumber'=>$this->session->userdata('cscnmregnumber'),'pay_status'=>'1'));
			$this->db->where('medium_delete','0');
			$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			$data=array('middle_content'=>'nonmember/print_non_member_applied_exam_details','user_info'=>$user_info,'qualification'=>$qualification,'idtype_master'=>$idtype_master,'applied_exam_info'=>$applied_exam_info,'center'=>$center,'medium'=>$medium);
	$this->load->view('nonmember/nm_common_view',$data);
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
				 $valid_member_list=$this->master_model->getRecords('eligible_master',array('eligible_period'=>'417','member_type'=>'NM'),'member_no');
				if(count($valid_member_list) > 0)
				{
					foreach($valid_member_list as $row)
					{
						$memberlist_arr[]=$row['member_no'];
					}
					 if(in_array($this->session->userdata('cscnmregnumber'),$memberlist_arr))
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
	// Remove an item from string
public function removeFromString($str, $item) {
    $parts = explode(',', $str);
    while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
    }
    return implode(',', $parts);
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
	//callback to validate photo exist
	function scannedphoto_upload_exist($csc_scannedphoto){
	    if(file_exists($csc_scannedphoto)){
          return true; 
        }
        else{
	       $this->form_validation->set_message('scannedphoto_upload_exist', "Your photograph is not available. A photograph is mandatory to display on the admit card.".$csc_scannedphoto);
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
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->model('Captcha_model');                 
		$captcha_img = $this->Captcha_model->generate_captcha_img('cscnonmemlogincaptcha');
		$data = $captcha_img;
		echo $data;
	}

	//START: callback to validate function
    function empidproofphoto_upload()
    {
        if ($_FILES['empidproofphoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('empidproofphoto_upload', "No Employee Id proof file selected");
            return false;
        }
    }	
	public function check_bank_bc_id_no_duplication($ippb_emp_id, $name_of_bank_bc)
    {
        if ($ippb_emp_id != "" && $ippb_emp_id != "0" && $name_of_bank_bc != '') {
            $this->db->where("name_of_bank_bc",$name_of_bank_bc);
            $this->db->where("ippb_emp_id",$ippb_emp_id);
            $this->db->where("regid != ",$this->session->userdata('cscnmregid')); 
            $prev_count = $this->master_model->getRecordCount('member_registration', array('isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count > 0) {
                $str = 'Bank BC ID No Already Exists for selected Name of Bank.';
                $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
                return false;} else {
                $this->form_validation->set_message('error', "");
            }

            {return true;}
        } else if ($ippb_emp_id == "0") {
            $str = 'Bank BC ID No field is required.';
            $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
            return false;
        } else if ($name_of_bank_bc == "") {
            $str = 'Name of Bank field is required.';
            $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
            return false;
        } else {
            $str = 'Bank BC ID No & Name of Bank field is required.';
            $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
            return false;
        }
    }
    public function check_date_of_joining_bc_validation($date_of_commenc_bc)
    {
        if ($date_of_commenc_bc != "") {
              
              $jdate = date("Y-m-d",strtotime($date_of_commenc_bc));  
              $ninemonthDate = date("Y-m-d",strtotime("+9 month", strtotime($jdate)));  
              $chk_date = "2024-03-31";  
              //$check_start_date = "2023-07-01";
              $check_start_date = "1964-01-01";
              $check_end_date = "2024-03-31";  
              if ($jdate < $check_start_date)
              {
                $str = 'Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'; 
                $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                return false;
              }
              else if ($jdate > $check_end_date)
              {
                $str = 'Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'; 
                $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                return false;
              } 
              else {
                $this->form_validation->set_message('error', ""); 
              } 
              
              {return true;}
        } else {
            $str = 'Date of joining field is required.';
            $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
            return false;
        }
    }
    //END: callback to validate function

    public function get_client_ip() {
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

	public function accessdenied_already_apply()
    {
        /*$get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'misc_master.misc_delete' => '0'), 'exam_month');
        //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
        $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
        $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
        $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.period. Hence you need not apply for the same.';*/

        $message = "You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.";
        
        $data=array('middle_content'=>'cscspecialmember/already_apply','check_eligibility'=>$message);
		$this->load->view('cscspecialmember/nm_common_view',$data); 
    }

    public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		} 
		redirect(base_url().'CSCSpecialMember/'); 
	}

}