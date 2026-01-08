<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class BulkApplyDB extends CI_Controller {
	public function __construct()
	{
		// if($this->get_client_ip1()!='115.124.115.75'){
		// 	echo '<H1><CENTER>SITE IS UNDER MAINTAINANCE, WILL BE BACK SOON</CENTER></H1>';DIE;
		// }

		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('chk_session');
		$this->load->model('Emailsending');
		$this->load->helper('cookie');
		$this->load->model('log_model');
		$this->chk_session->chk_bank_login_session();
	
		if($this->router->fetch_method()!='comApplication' && $this->router->fetch_method()!='preview' && $this->router->fetch_method()!='Msuccess' && $this->router->fetch_method()!='editmobile' && $this->router->fetch_method()!='editemailduplication' && $this->router->fetch_method()!='setExamSession' && $this->router->fetch_method()!='saveexam' && $this->router->fetch_method()!='savedetails' && $this->router->fetch_method()!='exampdf' && $this->router->fetch_method()!='printexamdetails' && $this->router->fetch_method()!='details' && $this->router->fetch_method()!='sbi_make_payment' && $this->router->fetch_method()!='sbitranssuccess' && $this->router->fetch_method()!='sbitransfail' && $this->router->fetch_method()!='accessdenied' && $this->router->fetch_method()!='getFee' && $this->router->fetch_method()!='check_emailduplication' && $this->router->fetch_method()!='check_mobileduplication' && $this->router->fetch_method()!='check_checkpin' && $this->router->fetch_method()!='refund' && $this->router->fetch_method()!='checkpin' && $this->router->fetch_method()!='exam_applicantlst' && $this->router->fetch_method()!='add_member' && $this->router->fetch_method() != 'set_dbf_elsub_cnt' &&  $this->router->fetch_method() != 'handle_billdesk_response' && $this->router->fetch_method() != 'getsetAsFresherOrOld' )
		{
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			/* if($this->session->userdata('examcode'))
			{
				$this->session->unset_userdata('examcode');
			}*/
		}
		//exit;
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
	
//##---------accessdenied page----------##
	public function GST()
	{
		$message='<div style="color:#F00">Please pay GST amount of Exam/Mem registration in order to apply for the exam.<a href="' . base_url() . 'GstRecovery/" target="new">click here</a></div>';
	 	$data=array('middle_content'=>'bulk/bulk-not_eligible','check_eligibility'=>$message);
		$this->load->view('bulk/bulk_common_view',$data);
	}
	function get_client_ip1() {
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
	public function getsetAsFresherOrOld() // priyanka d- 27-feb-23 >> put candidate selection in session and keep it one till he ends session
    {
        if(isset($_GET['method']) && $_GET['method']=='get')
        {
            echo $this->session->userdata('selectedoptVal');
            return $this->session->userdata('selectedoptVal');;
        }
        if($this->session->userdata('selectedoptVal')!=0) {
            echo $this->session->userdata('selectedoptVal');
            return $this->session->userdata('selectedoptVal');;
        }
        $selectedoptVal = array('selectedoptVal' => $_GET['optVal']);
        $this->session->set_userdata($selectedoptVal);
		$selectedoptValreg = array('selectedoptValreg'=>$_GET['regnumber']);
        $this->session->set_userdata($selectedoptValreg);
		
        $this->session->set_userdata('selectedoptVal_examcode',$this->session->userdata('examcode'));
        echo $_GET['optVal'];

    }
	public function set_dbf_elsub_cnt()
    {
        $subject_cnt_arr = array('subject_cnt' => $_POST['subject_cnt']);
        $this->session->set_userdata($subject_cnt_arr);
    }
	//##-------Non Member Registration in Bank Dashboard---## //Tejasvi
	public function add_member()
	{
		if($this->get_client_ip1()!='115.124.115.75'  && $this->get_client_ip1()!='182.73.101.70'  && $this->get_client_ip1()!='192.168.11.100'  && $this->get_client_ip1()!='206.84.236.49' ){
			//echo'Under progress';exit;
		}

		$this->session->unset_userdata('selectedoptVal');
		$this->session->unset_userdata('selectedoptValreg');
	 	$ex_prd='';
		if(isset($this->session->userdata['exmCrdPrd']['exam_prd']))
		{
			$ex_prd=$this->session->userdata['exmCrdPrd']['exam_prd'];
		}
		$exam_code = $this->session->userdata('examcode');
		if(empty($exam_code))
		{
			redirect(base_url().'bulk/BulkApply/examlist');
		}
		$is_exam_valid_nm = $this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code,'elg_mem_db'=>'Y'));
		
		if(empty($is_exam_valid_nm))
		{
			$this->session->set_flashdata('error','You are not eligible to apply for this exam!');
			redirect(base_url().'bulk/BulkApply/exam_applicantlst/');
		}
		$flag=$profile_flag=1;
		$showOptForJaiib = 0;
		$getDataOfMember=0;
		
		if(isset($_POST['getdata']))  	
		{
		//echo '<pre>',print_r($_POST),'</pre>';exit;
		
			$this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');
			if($this->form_validation->run() == TRUE)
			{
				$mem_info=array();

				if(isset($_POST['regnumber']))
				{
					
					// code to validate user
					$this->db->where('member_no',$_POST['regnumber']);
					$this->db->where('exam_code',$this->session->userdata('examcode'));
					// $this->db->where('institute_id !=', 0);//Added by pooja mane for fetching bulk eligible only
					$this->db->where('eligible_period',$this->session->userdata['exmCrdPrd']['exam_prd']);
					$chk_eligible_member = $this->master_model->getRecords('eligible_master','','discount_flag,institute_id,fee_paid_flag');
					if(count($chk_eligible_member)>0){
						if($chk_eligible_member[0]['fee_paid_flag'] == 'F'){
							$this->session->set_flashdata('error','Entered Membership number is wrong!!');
							redirect(base_url('bulk/BulkApplyDB/add_member/'));
						}
						// if($this->session->userdata['institute_id'] != $chk_eligible_member[0]['institute_id']){
						// 	$this->session->set_flashdata('error','Entered Membership number not belong to this institute!!');
						// 	redirect(base_url('bulk/BulkApplyDB/add_member/'));
						// }
						if ($chk_eligible_member[0]['exam_status'] == 'D') {

                            $this->session->set_flashdata('error', 'You Are Debarred For this Exam!!');

                            redirect(base_url('bulk/BulkApplyDB/add_member/'));

                        }
					}else{
						$this->session->set_flashdata('error','Wrong member number!!');
						redirect(base_url('bulk/BulkApplyDB/add_member/'));
					}
					
					// code to validate user end
					// accedd denied due to GST
				   $GST_val=check_GST($_POST['regnumber']);
					if($GST_val==2)
					{
						redirect(base_url() . 'bulk/BulkApplyDB/GST');
					}
				
					$mem_no=$_POST['regnumber'];
					$mem_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$mem_no,'isactive'=>'1'));
					
					if(empty($mem_info))
					{
						$this->session->set_flashdata('error','Entered Membership number not present...Please do the registration!!');
						redirect(base_url('bulk/BulkApplyDB/add_member/'));
					}
					elseif($mem_info[0]['registrationtype'] != 'DB')
					{
						$this->session->set_flashdata('error','Registration number entred is invalid, Kindly enter correct Registration number.!!');
						redirect(base_url('bulk/BulkApplyDB/add_member/'));
					}
					
					//validating for already registered Non_member
					if(!empty($mem_info))
					{
						$user_data=array('mregid_applyexam'=>$mem_info[0]['regid'],
										'mregnumber_applyexam'=>$mem_info[0]['regnumber'],
										'memtype'=>$mem_info[0]['registrationtype']);
						$this->session->set_userdata($user_data);
						$profile_flag=1;
						$message='';
						$exam_status=1;
						$applied_exam_info=array();
						$flag=1;$checkqualifyflag=0;
						$examcode=$this->session->userdata('examcode');
						
						if(!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')) || validate_nonmemdata($this->session->userdata('mregnumber_applyexam')))
						{
						
							$profile_flag=0;
						}
						//echo $profile_flag;
						//echo $this->db->last_query();exit;
					
						if($profile_flag==1)
						{
							$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
							
							if(count($check_qualify_exam) > 0)
							{
								if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0')
								{
									$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$this->session->userdata('examcode'),$check_qualify_exam[0]['qualifying_part1']);
									$flag=$qaulifyarry['flag'];
									$message=$qaulifyarry['message'];
									if($flag==0)
									{
										$checkqualifyflag=1;
									}
								}
								//if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
								if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0')
								{	
									$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$this->session->userdata('examcode'),$check_qualify_exam[0]['qualifying_part2']);
									$flag=$qaulifyarry['flag'];
									$message=$qaulifyarry['message'];
									if($flag==0)
									{
										$checkqualifyflag=1;
									}
								}
								//if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
								if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0')
								{	
									$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$this->session->userdata('examcode'),$check_qualify_exam[0]['qualifying_part3']);
									$flag=$qaulifyarry['flag'];
									$message=$qaulifyarry['message'];
									if($flag==0)
									{
										$checkqualifyflag=1;
									}
								}
								else if($flag==1 && $checkqualifyflag==0)
						        {
									//echo 'in';exit;
									//check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
									$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');
									 $this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

									$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));
										//echo'<pre>';print_r($check_eligibility_for_applied_exam);exit;

									if(count($check_eligibility_for_applied_exam) > 0)
									{
										foreach($check_eligibility_for_applied_exam as $check_exam_status)
										{
											if($check_exam_status['optForCandidate']=='Y')
												$showOptForJaiib = 1;
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
										  // print_r($this->session->userdata('examcode'));exit;
											$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
											//echo $this->db->last_query();
											//print_r($check);exit;
											//$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
											if(!$check)
											{
												$check_date=$this->examdate($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
												if(!$check_date)
												{
												//CAIIB apply directly
												$flag=1;
												}
												else
												{
													
													$message=$this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
													//$message='Exam fall in same date';
													$flag=0;
												}
											}
											else
											{
												$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
												$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
												$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata('examcode'),'misc_master.misc_delete'=>'0'),'exam_month');
												
												//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
												$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
												$flag=0;
											}
										}
									}
									else
									{
										$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));	
										
										if(!$check)
										{
								            $check_date=$this->examdate($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
											
											if(!$check_date)
											{
												//CAIIB apply directly
												$flag=1;
											}
											else
											{
											
												$message=$this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
												//$message='Exam fall in same date';
												$flag=0;
											}
										}
										else
										{
										
										
											$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
											 $this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
											$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata('examcode'),'misc_master.misc_delete'=>'0'),'exam_month');
											
												//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
												$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
											//$message='You have already applied for the examination';
											$flag=0;
										}
									}
								}
							}
							else
							{
								$flag=1;
							}	
							
							$today_date=date('Y-m-d');
							$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
							$this->db->where('exam_master.elg_mem_db','Y');
							//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
							$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
							//$this->db->where('member_exam.pay_status','1');				
							$this->db->where('member_exam.bulk_isdelete','0');				
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
						}
					}
					
					if($profile_flag==0)
					{
						$message = '<div style="color:#F00" class="col-md-4">Please update your profile!! <a target="_blank" href="' . base_url() . '/dbfuser/login"> Click here </a> to login</div>';
						$data  = array('middle_content' => 'bulk/bulk-not_eligible','check_eligibility'=>$message);
						
						$this->load->view('bulk/bulk_common_view', $data);
						
					}
					else if($flag==0)
					{
						if($profile_flag==0)
						{
							$message = '<div style="color:#F00" class="col-md-4">Please update your profile!! <a target="_blank" href="' . base_url() . '/dbfuser/login"> Click here </a> to login</div>';
						}
						//echo 'message',print_r($message);
						 $data=array('middle_content'=>'bulk/bulk-not_eligible','check_eligibility'=>$message);
						 
						 $this->load->view('bulk/bulk_common_view',$data);
					}
					else if(count($applied_exam_info) > 0)
					{
					
						if($check_qualify_exam[0]['exam_category']==1)
						{
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('nmregnumber')),'examination_date');
							
							$special_exam_dates=$this->master_model->getRecords('special_exam_dates',array('examination_date'=>$applied_exam_info[0]['examination_date']),'period');
							
							$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0','exam_period'=>$special_exam_dates[0]['period']),'exam_month');
						
							//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
							$message='Application for this examination is already registered by you and is valid for<strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
							$flag=0;
							$data=array('middle_content'=>'bulk/bulk-already-apply','check_eligibility'=>$message);
							
							$this->load->view('bulk/bulk_common_view',$data);	
						}
						else
						{
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
							//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
							$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
							$flag=0;
							$data=array('middle_content'=>'bulk/bulk-already-apply','check_eligibility'=>$message);
							
							$this->load->view('bulk/bulk_common_view',$data);
						}
						
					}
					else
					{
					
						$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=exam_master.exam_code');
						$this->db->join("eligible_master",'eligible_master.exam_code=bulk_exam_activation_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period','left');
						$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
						$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
						$this->db->where("misc_master.misc_delete",'0');
						$this->db->where("eligible_master.member_no",$this->session->userdata('mregnumber_applyexam'));
						$this->db->where("eligible_master.app_category !=",'R');
						$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
						$examinfo=$this->master_model->getRecords('exam_master');
						//echo '<pre>',print_r($examinfo),'</pre>';exit;
						####### get subject mention in eligible master ##########
						if(count($examinfo) > 0)
						{
							//19_03_2018 for only accept fresh member or 'R' cat member 
							//if($examinfo[0]['app_category']!='R')
							//{
							//	$this->session->set_flashdata('error','You are not eligible...!');
								//redirect(base_url('bulk/BulkApplyDB/add_member'));
							//}
							
							foreach($examinfo as $rowdata)
							{
									if($rowdata['exam_status']!='P')
									{
										$this->db->group_by('subject_code');
										$compulsory_subjects[]=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$rowdata['exam_period'],'subject_code'=>$rowdata['subject_code']));	
									}
								}	
								
							//$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));
							$compulsory_subjects = array_map('current', $compulsory_subjects);
							sort($compulsory_subjects );
						}	
						//center for eligible member
						$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
						$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
						$this->db->where('center_master.exam_name',$this->session->userdata('examcode'));
						$this->db->where("center_delete",'0');
						$center=$this->master_model->getRecords('center_master');
						//echo '<pre>',print_r($examinfo),'</pre>';exit;
					}
				}
			}
		}
		
		
		//Below code, if member is new member
		if(empty($examinfo))
		{
		 
			$this->db->select('exam_master.*,misc_master.*');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period');//added on 5/6/2017
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
			$examinfo = $this->master_model->getRecords('exam_master');
			
			//get center
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
				
			if($ex_prd!='')
			{
				$this->db->where('center_master.exam_period',$ex_prd);
			}
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where("center_delete",'0');
			$this->db->where('exam_name',$this->session->userdata('examcode'));
			$this->db->group_by('center_master.center_name');
			$center=$this->master_model->getRecords('center_master');
			
			####### get compulsory subject list##########
			$this->db->group_by('subject_code');
			$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$examinfo[0]['exam_period']),'',array('subject_code'=>'ASC'));
		}
		$institute_id = $this->session->userdata('institute_id');
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		
		$states=$this->master_model->getRecords('state_master');
		
		
		$this->db->where("bulk_branch_master.institute_id",$institute_id);
		$bulk_branch_master=$this->master_model->getRecords('bulk_branch_master');
		
		$this->db->where("bulk_designation_master.institute_id",$institute_id);
		$bulk_designation_master=$this->master_model->getRecords('bulk_designation_master');
		
		$this->db->where("bulk_zone_master.institute_id",$institute_id);
		$bulk_zone_master=$this->master_model->getRecords('bulk_zone_master');
		
		$this->db->where("bulk_payment_scale_master.institute_id",$institute_id);
		$bulk_payment_scale_master=$this->master_model->getRecords('bulk_payment_scale_master');
		
		
		$this->db->not_like('name','Declaration Form');
		$this->db->not_like('name','college');
		$this->db->not_like('name','Aadhaar id');
		$this->db->not_like('name','Election Voters card');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		/*$this->db->select('exam_master.*,misc_master.*');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period');//added on 5/6/2017
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where('exam_master.exam_code',$exam_code);
		$examinfo = $this->master_model->getRecords('exam_master');*/
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
		if($ex_prd!='')
		{
			$this->db->where('medium_master.exam_period',$ex_prd);
		}
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$this->db->where('medium_master.exam_code',$exam_code);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master');
		
		//subject information
		$caiib_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E','exam_period'=>$examinfo[0]['exam_period']));
		
		$special_exam_dates = array();
		$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
		if($exam_category[0]['exam_category']==1)
		{
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$special_exam_apply_date=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'bulk_isdelete'=>'0','examination_date !='=>'0000-00-00'),'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'
			$specialdateapply=array();
			if(count($special_exam_apply_date) > 0)
			{
				foreach($special_exam_apply_date as $row)
				{
					$specialdateapply[]=$row['examination_date'];
				}
			}
			$today_date=date('Y-m-d');
			$this->db->where("'$today_date' BETWEEN from_date AND to_date");
			$special_exam_dates=$this->master_model->getRecords('special_exam_dates');
		}
	
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemregcaptcha_bulk");
		$this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$_SESSION["nonmemregcaptcha_bulk"] = $cap['word']; //nonmemlogincaptcha
		$n_flag = 1;
		
		if(!count($examinfo) > 0 || !count($medium) > 0 ||  !count($center) > 0)
		{
			$n_flag=0;
		}
		
		if($n_flag==1) 
		{
		
			
			if($flag==1 && $profile_flag==1)
			{
				if(empty($mem_info))
				{
					
					
					$data=array('middle_content'=>'bulk/bulk_add_memberDB','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'special_exam_dates'=>$special_exam_dates,'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master);
					
					$this->load->view('bulk/bulk_common_view',$data);
				}
				else
				{
					//echo'here';exit;
					$data=array('middle_content'=>'bulk/bulk_add_regmemberDB','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'mem_info'=>$mem_info,'special_exam_dates'=>$special_exam_dates,'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master,'showOptForJaiib'=>$showOptForJaiib); // added showOptForJaiib >> priyanka d >> 07-march-24
					
					$this->load->view('bulk/bulk_common_view',$data);
				}
			}
		}
		else
		{
			
			//$this->load->view('access_denied',$data);
			redirect(base_url().'bulk/BulkApply/accessdenied/');
		}
		
	}
	//##-------Non Member Registration in Bank Dashboard---## //Tejasvi
	public function exam_form()
	{
		

	 	$ex_prd='';
		if(isset($this->session->userdata['exmCrdPrd']['exam_prd']))
		{
			$ex_prd=$this->session->userdata['exmCrdPrd']['exam_prd'];
		}
		$exam_code = $this->session->userdata('examcode');
		if(empty($exam_code))
		{
			redirect(base_url().'bulk/BulkApply/examlist');
		}
		$is_exam_valid_nm = $this->master_model->getRecords('exam_master',array('exam_code'=>$exam_code,'elg_mem_db'=>'Y'));
		
		if(empty($is_exam_valid_nm))
		{
			$this->session->set_flashdata('error','You are not eligible to apply for this exam!');
			redirect(base_url().'bulk/BulkApply/exam_applicantlst/');
		}
		$flag=$profile_flag=1;
		$showOptForJaiib = 0;
		$getDataOfMember=0;
		
		if($this->session->userdata('selectedoptVal') && $this->session->userdata('selectedoptValreg'))  	
		{
		
				$mem_info=array();

				$_POST['regnumber'] = $this->session->userdata('selectedoptValreg');
				//if(isset($_POST['regnumber']))
				{
					
					// code to validate user
					$this->db->where('member_no',$_POST['regnumber']);
					$this->db->where('exam_code',$this->session->userdata('examcode'));
					// $this->db->where('institute_id !=', 0);//Added by pooja mane for fetching bulk eligible only
					$this->db->where('eligible_period',$this->session->userdata['exmCrdPrd']['exam_prd']);
					$chk_eligible_member = $this->master_model->getRecords('eligible_master','','discount_flag,institute_id,fee_paid_flag');
					if(count($chk_eligible_member)>0){
						if($chk_eligible_member[0]['fee_paid_flag'] == 'F'){
							$this->session->set_flashdata('error','Entered Membership number is wrong!!');
							redirect(base_url('bulk/BulkApplyDB/add_member/'));
						}
						// if($this->session->userdata['institute_id'] != $chk_eligible_member[0]['institute_id']){
						// 	$this->session->set_flashdata('error','Entered Membership number not belong to this institute!!');
						// 	redirect(base_url('bulk/BulkApplyDB/add_member/'));
						// }
						if ($chk_eligible_member[0]['exam_status'] == 'D') {

                            $this->session->set_flashdata('error', 'You Are Debarred For this Exam!!');

                            redirect(base_url('bulk/BulkApplyDB/add_member/'));

                        }
					}else{
						$this->session->set_flashdata('error','Wrong member number!!');
						redirect(base_url('bulk/BulkApplyDB/add_member/'));
					}
					
					// code to validate user end
					// accedd denied due to GST
				   $GST_val=check_GST($_POST['regnumber']);
					if($GST_val==2)
					{
						redirect(base_url() . 'bulk/BulkApplyDB/GST');
					}
				
					$mem_no=$_POST['regnumber'];
					$mem_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$mem_no,'isactive'=>'1'));
					
					if(empty($mem_info))
					{
						$this->session->set_flashdata('error','Entered Membership number not present...Please do the registration!!');
						redirect(base_url('bulk/BulkApplyDB/add_member/'));
					}
					elseif($mem_info[0]['registrationtype'] != 'DB')
					{
						$this->session->set_flashdata('error','Registration number entred is invalid, Kindly enter correct Registration number.!!');
						redirect(base_url('bulk/BulkApplyDB/add_member/'));
					}
					
					//validating for already registered Non_member
					if(!empty($mem_info))
					{
						$user_data=array('mregid_applyexam'=>$mem_info[0]['regid'],
										'mregnumber_applyexam'=>$mem_info[0]['regnumber'],
										'memtype'=>$mem_info[0]['registrationtype']);
						$this->session->set_userdata($user_data);
						$profile_flag=1;
						$message='';
						$exam_status=1;
						$applied_exam_info=array();
						$flag=1;$checkqualifyflag=0;
						$examcode=$this->session->userdata('examcode');
						
						if(!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')) || validate_nonmemdata($this->session->userdata('mregnumber_applyexam')))
						{
						
							$profile_flag=0;
						}
						//echo $profile_flag;
						//echo $this->db->last_query();exit;
					
						if($profile_flag==1)
						{
							$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
							
							if(count($check_qualify_exam) > 0)
							{
								if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0')
								{
									$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$this->session->userdata('examcode'),$check_qualify_exam[0]['qualifying_part1']);
									$flag=$qaulifyarry['flag'];
									$message=$qaulifyarry['message'];
									if($flag==0)
									{
										$checkqualifyflag=1;
									}
								}
								//if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
								if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0')
								{	
									$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$this->session->userdata('examcode'),$check_qualify_exam[0]['qualifying_part2']);
									$flag=$qaulifyarry['flag'];
									$message=$qaulifyarry['message'];
									if($flag==0)
									{
										$checkqualifyflag=1;
									}
								}
								//if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
								if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0')
								{	
									$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$this->session->userdata('examcode'),$check_qualify_exam[0]['qualifying_part3']);
									$flag=$qaulifyarry['flag'];
									$message=$qaulifyarry['message'];
									if($flag==0)
									{
										$checkqualifyflag=1;
									}
								}
								else if($flag==1 && $checkqualifyflag==0)
						        {
									//echo 'in';exit;
									//check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
									$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');
									 $this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));

									$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));
										//echo'<pre>';print_r($check_eligibility_for_applied_exam);exit;

									if(count($check_eligibility_for_applied_exam) > 0)
									{
										foreach($check_eligibility_for_applied_exam as $check_exam_status)
										{
											if($check_exam_status['optForCandidate']=='Y')
												$showOptForJaiib = 1;
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
										  // print_r($this->session->userdata('examcode'));exit;
											$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
											//echo $this->db->last_query();
											//print_r($check);exit;
											//$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
											if(!$check)
											{
												$check_date=$this->examdate($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
												if(!$check_date)
												{
												//CAIIB apply directly
												$flag=1;
												}
												else
												{
													
													$message=$this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
													//$message='Exam fall in same date';
													$flag=0;
												}
											}
											else
											{
												$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
												$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
												$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata('examcode'),'misc_master.misc_delete'=>'0'),'exam_month');
												
												//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
												$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
												$flag=0;
											}
										}
									}
									else
									{
										$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));	
										
										if(!$check)
										{
								            $check_date=$this->examdate($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
											
											if(!$check_date)
											{
												//CAIIB apply directly
												$flag=1;
											}
											else
											{
											
												$message=$this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'));
												//$message='Exam fall in same date';
												$flag=0;
											}
										}
										else
										{
										
										
											$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
											 $this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
											$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata('examcode'),'misc_master.misc_delete'=>'0'),'exam_month');
											
												//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
												$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
											//$message='You have already applied for the examination';
											$flag=0;
										}
									}
								}
							}
							else
							{
								$flag=1;
							}	
							
							$today_date=date('Y-m-d');
							$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
							$this->db->where('exam_master.elg_mem_db','Y');
							//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
							$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
							//$this->db->where('member_exam.pay_status','1');				
							$this->db->where('member_exam.bulk_isdelete','0');				
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
						}
					}
					
					if($profile_flag==0)
					{
						$message = '<div style="color:#F00" class="col-md-4">Please update your profile!! <a target="_blank" href="' . base_url() . '/dbfuser/login"> Click here </a> to login</div>';
						$data  = array('middle_content' => 'bulk/bulk-not_eligible','check_eligibility'=>$message);
						
						$this->load->view('bulk/bulk_common_view', $data);
						
					}
					else if($flag==0)
					{
						if($profile_flag==0)
						{
							$message = '<div style="color:#F00" class="col-md-4">Please update your profile!! <a target="_blank" href="' . base_url() . '/dbfuser/login"> Click here </a> to login</div>';
						}
						//echo 'message',print_r($message);
						 $data=array('middle_content'=>'bulk/bulk-not_eligible','check_eligibility'=>$message);
						 
						 $this->load->view('bulk/bulk_common_view',$data);
					}
					else if(count($applied_exam_info) > 0)
					{
					
						if($check_qualify_exam[0]['exam_category']==1)
						{
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('nmregnumber')),'examination_date');
							
							$special_exam_dates=$this->master_model->getRecords('special_exam_dates',array('examination_date'=>$applied_exam_info[0]['examination_date']),'period');
							
							$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0','exam_period'=>$special_exam_dates[0]['period']),'exam_month');
						
							//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
							$message='Application for this examination is already registered by you and is valid for<strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
							$flag=0;
							$data=array('middle_content'=>'bulk/bulk-already-apply','check_eligibility'=>$message);
							
							$this->load->view('bulk/bulk_common_view',$data);	
						}
						else
						{
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
							$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
							$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
							//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
							$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
							$flag=0;
							$data=array('middle_content'=>'bulk/bulk-already-apply','check_eligibility'=>$message);
							
							$this->load->view('bulk/bulk_common_view',$data);
						}
						
					}
					else
					{
					
						if(!$this->session->userdata('selectedoptVal_examcode') || $this->session->userdata('selectedoptVal_examcode')!=$this->session->userdata('examcode') ) {
							//echo $this->session->userdata('selectedoptVal');exit;
											//$selectedoptVal = array('selectedoptVal' => 0);
											//$this->session->set_userdata($selectedoptVal);
											redirect(base_url() . 'bulk/BulkApplyDB/add_member');
										}
				
						 $continueAsOld=1;
						 if($this->session->userdata('selectedoptVal')==1)
						 
						 {
							 $continueAsOld=0;
						 }
						$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=exam_master.exam_code');
						$this->db->join("eligible_master",'eligible_master.exam_code=bulk_exam_activation_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period','left');
						$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
						$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
						$this->db->where("misc_master.misc_delete",'0');
						$this->db->where("eligible_master.member_no",$this->session->userdata('mregnumber_applyexam'));
						$this->db->where("eligible_master.app_category !=",'R');
						$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
						$examinfo=$this->master_model->getRecords('exam_master');
						//echo '<pre>',print_r($examinfo),'</pre>';exit;
						####### get subject mention in eligible master ##########
						if(count($examinfo) > 0  && $continueAsOld==1)
						{
							
							foreach($examinfo as $rowdata)
							{
									if($rowdata['exam_status']!='P')
									{
										$this->db->group_by('subject_code');
										$compulsory_subjects[]=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$rowdata['exam_period'],'subject_code'=>$rowdata['subject_code']));	
									}
								}	
								
							//$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));
							$compulsory_subjects = array_map('current', $compulsory_subjects);
							//sort($compulsory_subjects );
						}	
						//center for eligible member
						$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
						$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
						$this->db->where('center_master.exam_name',$this->session->userdata('examcode'));
						$this->db->where("center_delete",'0');
						$this->db->group_by('center_master.center_name');
						$center=$this->master_model->getRecords('center_master'); // Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
						//echo $this->db->last_query();exit;
						//echo '<pre>',print_r($examinfo),'</pre>';exit;
					}
				}
			
		}
		
		
		//Below code, if member is new member
		if(empty($examinfo)   || $continueAsOld==0)
		{
		 
			$this->db->select('exam_master.*,misc_master.*');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period');//added on 5/6/2017
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
			$examinfo = $this->master_model->getRecords('exam_master');
			
			//get center
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
				
			if($ex_prd!='')
			{
				$this->db->where('center_master.exam_period',$ex_prd);
			}
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where("center_delete",'0');
			$this->db->where('exam_name',$this->session->userdata('examcode'));
			$this->db->group_by('center_master.center_name');
			$center=$this->master_model->getRecords('center_master');
			//echo $this->db->last_query();exit;
			####### get compulsory subject list##########
			$this->db->group_by('subject_code');
			$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','exam_period'=>$examinfo[0]['exam_period']),'',array('subject_code'=>'ASC'));
		}
		$institute_id = $this->session->userdata('institute_id');
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		
		$states=$this->master_model->getRecords('state_master');
		
		
		$this->db->where("bulk_branch_master.institute_id",$institute_id);
		$bulk_branch_master=$this->master_model->getRecords('bulk_branch_master');
		
		$this->db->where("bulk_designation_master.institute_id",$institute_id);
		$bulk_designation_master=$this->master_model->getRecords('bulk_designation_master');
		
		$this->db->where("bulk_zone_master.institute_id",$institute_id);
		$bulk_zone_master=$this->master_model->getRecords('bulk_zone_master');
		
		$this->db->where("bulk_payment_scale_master.institute_id",$institute_id);
		$bulk_payment_scale_master=$this->master_model->getRecords('bulk_payment_scale_master');
		
		
		$this->db->not_like('name','Declaration Form');
		$this->db->not_like('name','college');
		$this->db->not_like('name','Aadhaar id');
		$this->db->not_like('name','Election Voters card');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		/*$this->db->select('exam_master.*,misc_master.*');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period');//added on 5/6/2017
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where('exam_master.exam_code',$exam_code);
		$examinfo = $this->master_model->getRecords('exam_master');*/
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
		if($ex_prd!='')
		{
			$this->db->where('medium_master.exam_period',$ex_prd);
		}
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$this->db->where('medium_master.exam_code',$exam_code);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master');
		
		//subject information
		$caiib_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E','exam_period'=>$examinfo[0]['exam_period']));
		
		$special_exam_dates = array();
		$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
		if($exam_category[0]['exam_category']==1)
		{
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$special_exam_apply_date=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'bulk_isdelete'=>'0','examination_date !='=>'0000-00-00'),'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'
			$specialdateapply=array();
			if(count($special_exam_apply_date) > 0)
			{
				foreach($special_exam_apply_date as $row)
				{
					$specialdateapply[]=$row['examination_date'];
				}
			}
			$today_date=date('Y-m-d');
			$this->db->where("'$today_date' BETWEEN from_date AND to_date");
			$special_exam_dates=$this->master_model->getRecords('special_exam_dates');
		}
	
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemregcaptcha_bulk");
		$this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$_SESSION["nonmemregcaptcha_bulk"] = $cap['word']; //nonmemlogincaptcha
		$n_flag = 1;
		
		if(!count($examinfo) > 0 || !count($medium) > 0 ||  !count($center) > 0)
		{
			$n_flag=0;
		}
		
		if($n_flag==1) 
		{
		
			
			if($flag==1 && $profile_flag==1)
			{
				if(empty($mem_info))
				{
					
					
					$data=array('middle_content'=>'bulk/bulk_add_memberDB','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'special_exam_dates'=>$special_exam_dates,'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master);
					
					$this->load->view('bulk/bulk_common_view',$data);
				}
				else
				{
					//echo'here';exit;
					$data=array('middle_content'=>'bulk/bulk_add_regmemberDB','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'mem_info'=>$mem_info,'special_exam_dates'=>$special_exam_dates,'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master,'showOptForJaiib'=>$showOptForJaiib,'selectedoptVal'=>$this->session->userdata('selectedoptVal')); // added showOptForJaiib >> priyanka d >> 07-march-24
					
					$this->load->view('bulk/bulk_common_view',$data);
				}
			}
		}
		else
		{
			
			//$this->load->view('access_denied',$data);
			redirect(base_url().'bulk/BulkApply/accessdenied/');
		}
		
	}
	##------------------ CMS Page for logged in user()---------------##
	public function comApplication()
	{
		if(isset($_POST['btnPreviewSubmit']))  	
		{
	        // echo '<pre>',print_r($_POST),'</pre>';exit;
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
			//$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
			//$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('medium','Medium','required|xss_clean');
			$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
			$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
			
			if(isset($_POST['bank_branch']) && $_POST['bank_branch']!='')
				{
					$this->form_validation->set_rules('bank_branch','Bank Branch','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_designation']) && $_POST['bank_designation']!='')
				{
					$this->form_validation->set_rules('bank_designation','Bank Designation','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_scale']) && $_POST['bank_scale']!='')
				{
					$this->form_validation->set_rules('bank_scale','Pay Scale','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_zone']) && $_POST['bank_zone']!='')
				{
					$this->form_validation->set_rules('bank_zone','Bank Zone','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_emp_id']) && $_POST['bank_emp_id']!='')
				{
					$this->form_validation->set_rules('bank_emp_id','Bank Employee Id','trim|required|xss_clean');
				}
			
			if($this->session->userdata('examcode')!=101
			 && $this->session->userdata('examcode')!=1010
			 && $this->session->userdata('examcode')!=10100
			 && $this->session->userdata('examcode')!=101000
			 && $this->session->userdata('examcode')!=1010000
			 && $this->session->userdata('examcode')!=10100000
			 && $this->session->userdata('examcode')!=996)
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

			if ($this->input->post('elearning_flag') == 'Y') {
                $this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
            } // priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july >> elearning subject array 
			
			
				if($this->form_validation->run()==TRUE)
				{
					
					$subject_arr=array();
					$venue=$this->input->post('venue');
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					
					$special_exam_date = '';
					$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
					if($exam_category[0]['exam_category']==1)
					{
						$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
						$special_exam_apply_date=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'bulk_isdelete'=>'0','examination_date !='=>'0000-00-00'),'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'
						$specialdateapply=array();
						if(count($special_exam_apply_date) > 0)
						{
							foreach($special_exam_apply_date as $row)
							{
								$specialdateapply[]=$row['examination_date'];
							}
						}
						$today_date=date('Y-m-d');
						$this->db->where("'$today_date' BETWEEN from_date AND to_date");
						$special_exam_dates=$this->master_model->getRecords('special_exam_dates');
						$special_exam_date = $special_exam_dates[0]['examination_date'];
					}
					
					$splexamdate='';
					if(count($venue) >0 && count($date) >0 && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$splexamdate=$date[$k];
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
											//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											{
												$sub_flag=0;
											}
										}
									}
								 $capacity=check_capacity_bulk($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									$msg=getVenueDetails_bulk($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								}
								if($msg!='')
								{
									$this->session->set_flashdata('error',$msg);
									redirect(base_url().'bulk/BulkApplyDB/add_member');
								}
							}
						}
						if($sub_flag==0)
						{
							if(base64_decode($_POST['excd'])!=101 
							&& base64_decode($_POST['excd'])!=1010
							&& base64_decode($_POST['excd'])!=10100
							&& base64_decode($_POST['excd'])!=101000
							&& base64_decode($_POST['excd'])!=1010000
							&& base64_decode($_POST['excd'])!=10100000
							&& base64_decode($_POST['excd'])!=996)
							{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'bulk/BulkApplyDB/add_member');
							}
						}
					}
					
					$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
					if($exam_category[0]['exam_category']==1)
					{
						###############check wheather exam alredy applied on same date or not#########
						$this->check_examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'), $splexamdate);
					}
					
					if(isset($_POST['scribe_flag']))
					{
						$scribe_flag='Y';
					}
					
					
					$optFlg='N';//priyanka d
					$selectedoptval=$this->session->userdata('selectedoptVal');
                	if($selectedoptval==1)
                    $optFlg='F';
                	else if($selectedoptval==2)
                    $optFlg='R';

					$elearning_flag_new = 'N';
					if (isset($_POST['el_subject'])) {
						$el_subject         = $_POST['el_subject'];
						$elearning_flag_new = 'Y';
					} else {
						$el_subject = 'N';
					}
	
					

					if (in_array(base64_decode($_POST['excd']), array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB')))) {
						$elearning_flag_new = $elearning_flag_new;
					} else if (in_array(base64_decode($_POST['excd']), array($this->config->item('examCodeCaiib'), 65))) {
						$elearning_flag_new = $elearning_flag_new;
					} else {
						$elearning_flag_new = $_POST['elearning_flag'];
					}//priyanka d

					$user_data=array('photo'=>'',
									'signname'=>'',
									'medium'=>$_POST['medium'],
									'selCenterName'=>$_POST['selCenterName'],
									'optmode'=>$_POST['optmode'],
									'extype'=>$_POST['extype'],
									'exname'=>$_POST['exname'],
									'excd'=>base64_decode($_POST["excd"]),
									'eprid'=>$_POST['eprid'],
									'fee'=>$_POST['fee'],
									'txtCenterCode'=>$_POST['txtCenterCode'],
									'insdet_id'=>'',
									//'selected_elect_subcode'=>@$_POST['selSubcode'],
									//'selected_elect_subname'=>@$_POST['selSubName1'],
									'placeofwork'=>$_POST['placeofwork'],
									'state_place_of_work'=>$_POST['state_place_of_work'],
									'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
									'elected_exam_mode'=>$_POST['elected_exam_mode'],
									'special_exam_date'=>$special_exam_date,
									'grp_code'=>$_POST['grp_code'],
									'subject_arr'=>$subject_arr,
									'scribe_flag'=>$scribe_flag,
					                'bank_branch'=>$_POST['bank_branch'],
									'bank_designation'=>$_POST['bank_designation'],
									'bank_scale'=>$_POST['bank_scale'],
									'bank_zone'=>$_POST['bank_zone'],
									'bank_emp_id'=>$_POST['bank_emp_id'],
									'elearning_flag'=>$elearning_flag_new,// priyanka d - 24-o1-23
									'discount_flag'=>$_POST['discount_flag'],
									'free_paid_flag'=>$_POST['free_paid_flag'],
									'reapeter_flag'=>'',
									'optval'                    => $selectedoptval, // priyanka d - 24-o1-23
									'optFlg'                    => $optFlg,// priyanka d - 24-o1-23
									'el_subject'               => $el_subject,// priyanka d - 24-o1-23
									);
					//echo '<pre>',print_r($user_data),'</pre>';exit;
					$this->session->set_userdata('examinfo',$user_data);
					//logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
					
					//logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
					/* User Log Activities : Bhushan */
					$log_title ="Non-Member bulk exam apply details";
					$log_message = serialize($user_data);
					$rId = $this->session->userdata('regid');
					$regNo = $this->session->userdata('mregnumber_applyexam');
					$inst_id = $this->session->userdata['institute_id'];
					//echo '<pre>',print_r($user_data),'</pre>';exit;
					bulk_storedUserActivity($log_title, $log_message,$inst_id ,$rId, $regNo);
					/* Close User Log Actitives */
					//echo '<pre>',print_r($user_data),'</pre>';exit;
					redirect(base_url().'bulk/BulkApplyDB/preview');
				}
				else
				{
					$var_errors = str_replace("<p>", "<span>", $var_errors);
					$var_errors = str_replace("</p>", "</span><br>", $var_errors);
				}
		}
		
	}
	//##---------------exam application with new non-member registration----------(Tejasvi)-----##
	public function comApplication_reg()
	{
		if(!$this->session->userdata('examcode'))
		{	
			redirect(base_url().'bulk/BulkApply/dashboard');
		}
		if(isset($_POST['btnSubmit']))  	
		{
			//echo '<pre>',print_r($_POST),'</pre>';exit;
				
			    $scribe_flag='N';
			    $scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';
			    $this->form_validation->set_rules('sel_namesub', 'First Name','trim|required|xss_clean');
				$this->form_validation->set_rules('firstname','First Name','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('addressline1','Address line1','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
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
					$this->form_validation->set_rules('eduqual1','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$this->session->userdata('examcode').']');
				}
				else if(isset($_POST['optedu']) && $_POST['optedu']=='G')
				{
					$this->form_validation->set_rules('eduqual2','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$this->session->userdata('examcode').']');
				}
				else if(isset($_POST['optedu']) && $_POST['optedu']=='P')
				{
					$this->form_validation->set_rules('eduqual3','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$this->session->userdata('examcode').']');
				}
				
				if(isset($_POST['addressline2']) && $_POST['addressline2']!='')
				{
					$this->form_validation->set_rules('addressline2','Address line2','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['addressline3']) && $_POST['addressline3']!='')
				{
					$this->form_validation->set_rules('addressline3','Address line3','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['addressline4']) && $_POST['addressline4']!='')
				{
					$this->form_validation->set_rules('addressline4','Address line4','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['stdcode']) && $_POST['stdcode']!='')
				{
					$this->form_validation->set_rules('stdcode','STD Code','trim|max_length[4]|required|numeric|xss_clean');
				}
				
				if(isset($_POST['phone']) && $_POST['phone']!='')
				{
					$this->form_validation->set_rules('phone','Phone No','trim|required|numeric|xss_clean');
				}
				
				//$this->form_validation->set_rules('institutionworking','Bank/Institution working','trim|required|alpha_numeric_spaces|xss_clean');
				//$this->form_validation->set_rules('office','Branch/Office','trim|required|xss_clean');
				//$this->form_validation->set_rules('designation','Designation','trim|required|xss_clean');
				//$this->form_validation->set_rules('doj1','Date of joining Bank/Institution','trim|required|xss_clean');
				$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
				$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
				if($this->session->userdata('examcode')!=101
				 && $this->session->userdata('examcode')!=1010
				 && $this->session->userdata('examcode')!=10100
				 && $this->session->userdata('examcode')!=101000
				 && $this->session->userdata('examcode')!=1010000
				 && $this->session->userdata('examcode')!=10100000
				 && $this->session->userdata('examcode')!=996)
				{
					$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
					$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
					$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
				}
				$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
				
				$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
				$this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');
				$this->form_validation->set_rules('idNo','ID No','trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');
				
				if($this->session->userdata('examcode')!=101 
				|| $this->input->post('aadhar_card') != '' 
				|| $this->session->userdata('examcode')!=1010
				|| $this->session->userdata('examcode')!=10100
				|| $this->session->userdata('examcode')!=101000
				|| $this->session->userdata('examcode')!=1010000
				|| $this->session->userdata('examcode')!=10100000
				|| $this->session->userdata('examcode')!=996)
				{
					if($this->input->post('aadhar_card')!='')
					{
					//$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required|max_length[12]|numeric|xss_clean|callback_check_aadhar');
					//$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|numeric|xss_clean|callback_check_aadhar');
					$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');
					
					}
				}
				if(isset($_POST['bank_branch']) && $_POST['bank_branch']!='')
				{
					$this->form_validation->set_rules('bank_branch','Bank Branch','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_designation']) && $_POST['bank_designation']!='')
				{
					$this->form_validation->set_rules('bank_designation','Bank Designation','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_scale']) && $_POST['bank_scale']!='')
				{
					$this->form_validation->set_rules('bank_scale','Pay Scale','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_zone']) && $_POST['bank_zone']!='')
				{
					$this->form_validation->set_rules('bank_zone','Bank Zone','trim|required|numeric|xss_clean');
				}
				if(isset($_POST['bank_emp_id']) && $_POST['bank_emp_id']!='')
				{
					$this->form_validation->set_rules('bank_emp_id','Bank Employee Id','trim|required|xss_clean');
				}
				$this->form_validation->set_rules('idproofphoto','Id proof','file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
				$this->form_validation->set_rules('medium','Medium','required|xss_clean');
				$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
				$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
				//$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
				$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');

				if ($this->input->post('elearning_flag') == 'Y') {
					$this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
				} //Priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july
				
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
					
					$special_exam_date = '';
					$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
					if($exam_category[0]['exam_category']==1)
					{
						$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
						$special_exam_apply_date=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'bulk_isdelete'=>'0','examination_date !='=>'0000-00-00'),'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'
						$specialdateapply=array();
						if(count($special_exam_apply_date) > 0)
						{
							foreach($special_exam_apply_date as $row)
							{
								$specialdateapply[]=$row['examination_date'];
							}
						}
						$today_date=date('Y-m-d');
						$this->db->where("'$today_date' BETWEEN from_date AND to_date");
						$special_exam_dates=$this->master_model->getRecords('special_exam_dates');
						$special_exam_date = $special_exam_dates[0]['examination_date'];
					}
					
					$splexamdate='';
					
					########### get POST data of subject ##############
					if(count($venue) >0 && count($date) && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$splexamdate=$date[$k];
							$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=subject_master.exam_code AND bulk_exam_activation_master.exam_period=subject_master.exam_period');
							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'C','subject_code'=>$k),'subject_description');
						
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
								 $capacity=check_capacity_bulk($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									$msg=getVenueDetails_bulk($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								}
								if($msg!='')
								{
									$this->session->set_flashdata('error',$msg);
									redirect(base_url().'bulk/BulkApplyDB/add_member');
								}
							}
						}
						if($sub_flag==0)
						{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'bulk/BulkApplyDB/add_member');
							//redirect(base_url().'nonreg/member/?Mtype='.$this->input->get('Mtype').'=&ExId='.$this->input->get('ExId').'');
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
					
					
					$dob1= $_POST["dob1"];
					$dob = str_replace('/','-',$dob1);
					$dateOfBirth = date('Y-m-d',strtotime($dob));
					/*added scribe_flag : pooja*/
					if(isset($_POST['scribe_flag']))
					{
						$scribe_flag='Y';
					}
					//Priyanka D >> DBFMOUPAYMENTCHANGE >> start 10-july
					$elearning_flag_new = 'N';
					if (isset($_POST['el_subject'])) {
						$el_subject         = $_POST['el_subject'];
						$elearning_flag_new = 'Y';
					} else {
						$el_subject = 'N';
					}
				   //Priyanka D >> DBFMOUPAYMENTCHANGE >> end 10-july
					if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
					{
						$user_data=array(	'firstname'			=>$_POST["firstname"],
													'sel_namesub'		=>$_POST["sel_namesub"],
													'addressline1'		=>$_POST["addressline1"],
													'addressline2'		=>$_POST["addressline2"],
													'addressline3'		=>$_POST["addressline3"],
													'addressline4'		=>$_POST["addressline4"],
													'city'					=>$_POST["city"],	
													//'code'					=>trim($_POST["code"]),
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
													'excd'					=>base64_decode($_POST["excd"]),
													'exname'				=>$_POST["exname"],
													'fee'						=>	$_POST["fee"],
													'medium'				=>$_POST['medium'],
													'aadhar_card'		=>$_POST['aadhar_card'],
													'grp_code'			=>$_POST['grp_code'],
													'bank_branch'			=>$_POST['bank_branch'],
													'bank_designation'			=>$_POST['bank_designation'],
													'bank_scale'			=>$_POST['bank_scale'],
													'bank_zone'			=>$_POST['bank_zone'],
													'bank_emp_id'			=>$_POST['bank_emp_id'],
													'special_exam_date' =>$special_exam_date,
													'subject_arr'		=>$subject_arr,
													'scribe_flag'=>$scribe_flag,
													'elearning_flag'=>$_POST['elearning_flag'],
													'discount_flag'=>$_POST['discount_flag'],
													'el_subject'               => $el_subject, // priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july
												);
						$this->session->set_userdata('enduserinfo',$user_data);
						//echo '<pre>_POST',print_r($_POST),'</pre>';
						//echo '<pre>user_data',print_r($user_data),'</pre>';exit; 
						
						$log_title ="Non-Member bulk exam apply details";
						$log_message = serialize($user_data);
						$rId = $this->session->userdata('regid');
						$regNo = $this->session->userdata('mregnumber_applyexam');
						$inst_id = $this->session->userdata['institute_id'];
						//echo '<pre>',print_r($user_data),'</pre>';exit;
						bulk_storedUserActivity($log_title, $log_message,$inst_id ,$rId, $regNo);
						
						redirect(base_url().'bulk/BulkApplyDB/exam_preview');
					}
				}
			}
			
		$institute_id = $this->session->userdata('institute_id');	
		$exam_code=$this->session->userdata('examcode');
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		
		$states=$this->master_model->getRecords('state_master');
		$this->db->where("bulk_branch_master.institute_id",$institute_id);
		$bulk_branch_master=$this->master_model->getRecords('bulk_branch_master');
		
		$this->db->where("bulk_designation_master.institute_id",$institute_id);
		$bulk_designation_master=$this->master_model->getRecords('bulk_designation_master');
		
		$this->db->where("bulk_zone_master.institute_id",$institute_id);
		$bulk_zone_master=$this->master_model->getRecords('bulk_zone_master');
		
		$this->db->where("bulk_payment_scale_master.institute_id",$institute_id);
		$bulk_payment_scale_master=$this->master_model->getRecords('bulk_payment_scale_master');
		
		$this->db->not_like('name','Declaration Form');
		$this->db->not_like('name','college');
		$this->db->not_like('name','Aadhaar id');
		$this->db->not_like('name','Election Voters card');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		$this->db->select('exam_master.*,misc_master.*');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period');//added on 5/6/2017
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where('exam_master.exam_code',$exam_code);
		$examinfo = $this->master_model->getRecords('exam_master');
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$this->db->where('medium_master.exam_code',$exam_code);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master');
		
		//subject information
		$caiib_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata('examcode'),'subject_delete'=>'0','group_code'=>'E','exam_period'=>$examinfo[0]['exam_period']));
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$this->db->where("center_delete",'0');
		$this->db->where('exam_name',$exam_code);
		$this->db->group_by('center_master.center_name');
		$center=$this->master_model->getRecords('center_master');
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemregcaptcha_bulk");
		$this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$_SESSION["nonmemregcaptcha_bulk"] = $cap['word']; //nonmemlogincaptcha
		$flag = 1;
		if(!count($examinfo) > 0 || !count($medium) > 0 ||  !count($center) > 0)
		{
			$flag=0;
		}
		
		if($flag==1)
		{
		############# get Compulsory Subject List ##############
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=subject_master.exam_code AND bulk_exam_activation_master.exam_period=subject_master.exam_period');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$compulsory_subjects=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'C'));
		
		$data=array('middle_content'=>'bulk/bulk_add_memberDB','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'image' => $cap['image'],'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'idtype_master'=>$idtype_master,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master);
				$this->load->view('bulk/bulk_common_view',$data);
		}
		else
		{
			echo 'Access denied';
		}
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
						$str='You are not eligible to apply for exam';
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
	
	##------------------ Preview for applied exam,for logged in user()---------------##
	public function preview()
	{
		//$this->chk_session->checklogin();
		if(empty($this->session->userdata['institute_id']))
		{
			redirect(base_url().'bulk/Banklogin/');
		}
		
		$sub_flag=1;
		$sub_capacity=1;
		//echo $this->session->userdata['examinfo']['selCenterName'];exit;
		$compulsory_subjects=array();
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		//check exam acivation
		$check_exam_activation=bulk_check_exam_activate($this->session->userdata['examinfo']['excd']);
		if($check_exam_activation['flag']==0)
		{
			redirect(base_url().'bulk/BulkApply/accessdenied/');
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
				 $capacity=check_capacity_bulk($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				 
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails_bulk($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					//redirect(base_url().'bulk/BulkApply/comApplication');
					redirect(base_url().'bulk/BulkApplyDB/add_member');
				}
			} 
		}
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			//redirect(base_url().'bulk/BulkApply/comApplication');
			redirect(base_url().'bulk/BulkApplyDB/add_member');
		}
			
		/*$cookieflag=1;
		//$this->chk_session->checkphoto();
		//ask user to wait for 5 min, until the payment transaction process complete by ()
		$valcookie=$this->session->userdata('mregnumber_applyexam');
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
		} */
		//End Of ask user to wait for 5 min, until the payment transaction process complete by ()
			
		if(!$this->session->userdata('examinfo'))
		{
			$this->session->set_flashdata('error','Session expire!!');
			redirect(base_url().'bulk/BulkApply/add_member/');
		}	
		//check for valid fee
		if($this->session->userdata['examinfo']['fee']==0 || $this->session->userdata['examinfo']['fee']=='')
		{
		//echo 'in';exit;
			$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
			//redirect(base_url().'bulk/BulkApply/comApplication');
			redirect(base_url().'bulk/BulkApplyDB/add_member');
		}
			
		/*$examination_date = $this->session->userdata['examinfo']['special_exam_date'];	
		$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
		if($exam_category[0]['exam_category']==1)
		{
			###############check wheather exam alredy applied on same date or not#########
			$this->check_examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'), $examination_date);
		}	*/
			
		$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata['examinfo']['excd']);
		
		
		if(!$check)
		{		
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where('medium_master.exam_code',$this->session->userdata['examinfo']['excd']);
			$this->db->where('medium_delete','0');
			$medium=$this->master_model->getRecords('medium_master');
			
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where('exam_name',$this->session->userdata['examinfo']['excd']);
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$center=$this->master_model->getRecords('center_master','','center_name');
			//echo $this->db->last_query();exit;
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($user_info) <=0)
			{
				redirect(base_url().'bulk');
			}	
			$this->db->where('state_delete','0');
			$states=$this->master_model->getRecords('state_master');
			
			$institute_id = $this->session->userdata('institute_id');
			
			$this->db->where("bulk_branch_master.institute_id",$institute_id);
			$bulk_branch_master=$this->master_model->getRecords('bulk_branch_master');
			
			$this->db->where("bulk_designation_master.institute_id",$institute_id);
			$bulk_designation_master=$this->master_model->getRecords('bulk_designation_master');
			
			$this->db->where("bulk_zone_master.institute_id",$institute_id);
			$bulk_zone_master=$this->master_model->getRecords('bulk_zone_master');
			
			$this->db->where("bulk_payment_scale_master.institute_id",$institute_id);
			$bulk_payment_scale_master=$this->master_model->getRecords('bulk_payment_scale_master');
		
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$misc=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata['examinfo']['excd'],'misc_delete'=>'0'));
			
			/*if($cookieflag==0)
			{
				$data=array('middle_content'=>'exam_apply_cms_msg');
			}
			else
			{}*/
			
			$data=array('middle_content'=>'bulk/exam_preview','user_info'=>$user_info,'medium'=>$medium,'center'=>$center,'misc'=>$misc,'states'=>$states,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr'],'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master);
			
			$this->load->view('bulk/bulk_common_view',$data);
		}
		else
		{
		
		$exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));
		if($exam_category[0]['exam_category']==1)
		{
			 $this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$this->session->userdata['examinfo']['excd'],'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'examination_date');
			
			$special_exam_dates=$this->master_model->getRecords('special_exam_dates',array('examination_date'=>$applied_exam_info[0]['examination_date']),'period');
			
			$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata['examinfo']['excd'],'misc_master.misc_delete'=>'0','exam_period'=>$special_exam_dates[0]['period']),'exam_month');
						
				 //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
				  $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
				 $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
				 $message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
				 $flag=0;
				 $data=array('middle_content'=>'bulk/already_apply','check_eligibility'=>$message);
				 $this->load->view('bulk/bulk_common_view',$data);
		}
		else
		{
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata['examinfo']['excd'],'misc_master.misc_delete'=>'0'),'exam_month');
			//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
			$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
			$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
			$flag=0;
			 $data=array('middle_content'=>'bulk/already_apply','check_eligibility'=>$message);
			 $this->load->view('bulk/bulk_common_view',$data);	
		 }
			
		}
	}
	
	public function exam_preview()
	{
		$ex_prd='';
		if(isset($this->session->userdata['exmCrdPrd']['exam_prd']))
		{
			$ex_prd=$this->session->userdata['exmCrdPrd']['exam_prd'];
		}
		//$this->chk_session->checklogin();
		if(empty($this->session->userdata['institute_id']))
		{
			redirect(base_url().'bulk/Banklogin/');
		}
		
		$sub_flag=1;
		if(!$this->session->userdata('enduserinfo'))
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		
		//check exam acivation
		$check_exam_activation=bulk_check_exam_activate($this->session->userdata['enduserinfo']['excd']);
	
		if($check_exam_activation['flag']==0)
		{
			redirect(base_url().'bulk/BulkApply/accessdenied/');
		}
		############check capacity is full or not ##########
		$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
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
				 $capacity=check_capacity_bulk($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);
				 
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails_bulk($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					//redirect(base_url().'bulk/BulkApply/comApplication');
					redirect(base_url().'bulk/BulkApplyDB/add_member');
				}
			} 
		}
		
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			//redirect(base_url().'bulk/BulkApply/comApplication');
			redirect(base_url().'bulk/BulkApplyDB/add_member');
		}
		
		//check email,mobile duplication on the same time from different browser!!
		$endTime = date("H:i:s");
		$start_time= date("H:i:s",strtotime("-20 minutes",strtotime($endTime)));
		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');
		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);
		$this->db->or_where('email',$this->session->userdata['enduserinfo']['mobile']);
		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));
		if(count($check_duplication) > 0)
		{
			redirect(base_url().'bulk/BulkApply/accessdenied/');
		}
		
		//check for valid fee
		if($this->session->userdata['enduserinfo']['fee']==0 || $this->session->userdata['enduserinfo']['fee']=='')
		{
		
			$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
			redirect(base_url().'bulk/BulkApplyDB/add_member');
		}
		
		$institute_id = $this->session->userdata('institute_id');
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		
		
		$this->db->where("bulk_branch_master.institute_id",$institute_id);
		$bulk_branch_master=$this->master_model->getRecords('bulk_branch_master');
		
		$this->db->where("bulk_designation_master.institute_id",$institute_id);
		$bulk_designation_master=$this->master_model->getRecords('bulk_designation_master');
		
		$this->db->where("bulk_zone_master.institute_id",$institute_id);
		$bulk_zone_master=$this->master_model->getRecords('bulk_zone_master');
		
		$this->db->where("bulk_payment_scale_master.institute_id",$institute_id);
		$bulk_payment_scale_master=$this->master_model->getRecords('bulk_payment_scale_master');
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$this->db->where('medium_delete','0');
		if($ex_prd!='')
		{
			$this->db->where('medium_master.exam_period',$ex_prd);
		}
		$medium=$this->master_model->getRecords('medium_master',array('medium_master.exam_code'=>$this->session->userdata['enduserinfo']['excd']));
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		if($ex_prd!='')
		{
			$this->db->where('center_master.exam_period',$ex_prd);
		}
		$center=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd']));
		
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		$this->db->like('misc_master.exam_code',$this->session->userdata['enduserinfo']['excd']);
		$exam_period=$this->master_model->getRecords('misc_master','','misc_master.exam_period'); 
		//echo $this->db->last_query();exit;
		
		$data=array('middle_content'=>'bulk/exam_preview_register_DB','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'medium'=>$medium,'center'=>$center,'exam_period'=>$exam_period,'idtype_master'=>$idtype_master,'compulsory_subjects'=>$this->session->userdata['enduserinfo']['subject_arr'],'bulk_branch_master'=>$bulk_branch_master,'bulk_designation_master'=>$bulk_designation_master,'bulk_zone_master'=>$bulk_zone_master,'bulk_payment_scale_master'=>$bulk_payment_scale_master);
		$this->load->view('bulk/bulk_common_view',$data);
			
	}
	##------------------Insert data in member_exam table for applied exam,for logged in user With Payment using Billdesk Gate-way()---------------##
	public function Msuccess()
	{
		//echo '<pre>',print_r($this->session->userdata['examinfo']); exit;
		//$this->chk_session->checklogin();
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		$elected_sub_code = '';
		if(!empty($this->session->userdata['examinfo']['selected_elect_subcode']))
		{
			$elected_sub_code = $this->session->userdata['examinfo']['selected_elect_subcode'];
		}
		if(isset($_POST['btnPreview']))
		{
		
			

			$update_array1=array('bank_branch'=>$this->session->userdata['examinfo']['bank_branch'],
							'bank_designation'=>$this->session->userdata['examinfo']['bank_designation'],
							'bank_scale'=>$this->session->userdata['examinfo']['bank_scale'],
							'bank_zone'=>$this->session->userdata['examinfo']['bank_zone'],
							'bank_emp_id'=>$this->session->userdata['examinfo']['bank_emp_id']);
							
			$this->master_model->updateRecord('member_registration',$update_array1,array('regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			
			$amount=bulk_getExamFee($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),$this->session->userdata['examinfo']['elearning_flag'],$this->session->userdata['examinfo']['discount_flag']);
			
			//##------------get app_category and base_fee
			$fee_result=bulk_getFee_Appcat($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'),$this->session->userdata['examinfo']['elearning_flag'],$this->session->userdata['examinfo']['discount_flag']);
			
			//priyanka d =22-feb-23
			$optFlg	=	'N';
			if(isset($this->session->userdata['examinfo']['optFlg']))
				$optFlg	=	$this->session->userdata['examinfo']['optFlg'];
			
			$el_subject_cnt = 0;
			if (isset($this->session->userdata['examinfo']['el_subject']) ){
				if ($this->session->userdata['examinfo']['el_subject'] == 'N') {
					$el_subject_cnt = 0;
				} else {
					$el_subject_cnt = $this->session->userdata('subject_cnt'); // Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
				}
			}
			$inser_array=array(	'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
											'member_type'=>$this->session->userdata('memtype'),
											'app_category'=>$fee_result['grp_code'],
											'base_fee'=>$fee_result['base_fee'],
											'original_base_fee'=>$fee_result['original_base_fee'],
											'bulk_discount_flg'=>$fee_result['bulk_discount_flg'],
											'discount_percent'=>$fee_result['discount_percent'],
											'discount_amount'=>$fee_result['discount_amount'],
											'calculate_discount'=>$fee_result['calculate_discount'],
											'taken_discount'=>$fee_result['taken_discount'],
			 								'exam_code'=>$this->session->userdata['examinfo']['excd'],
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$amount,
											'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
											'created_on'=>date('y-m-d H:i:s'),
											'pay_status'=>'2',
											'institute_id'=>$this->session->userdata['institute_id'],
											'bulk_isdelete'=>'0',
											'examination_date'=>$this->session->userdata['examinfo']['special_exam_date'],
											'elearning_flag'=>$this->session->userdata['examinfo']['elearning_flag'],
											'sub_el_count'                   => $el_subject_cnt,//priyanka d
                							'optFlg'                         => $optFlg,//priyanka d
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
				//get email and mobile
				$user_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'),array('email','mobile'));
				
				//check if email is unique
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$user_data[0]['email'],'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$user_data[0]['email']));
					$email_mbl_flg = 1;	
				}
				// check if mobile is unique
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$user_data[0]['mobile'],'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$user_data[0]['mobile']));
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
					/* $user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));
					*/
					if(count($user_info))
					{
						$prevData = $user_info[0];
					}
					
					$desc['updated_data'] = $update_array;
					$desc['old_data'] = $prevData;
					$inst_id = $this->session->userdata['institute_id'];
					
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
					
					//bulk_log_profile_user($log_title = "Profile updated successfully", $edited,'data',$this->session->userdata('mregid_applyexam'),$this->session->userdata('mregnumber_applyexam'));
					
					bulk_logactivity($log_title ="Non Member update profile during exam apply", $log_message = serialize($desc),$inst_id);
					
				}
				
				/*if($this->config->item('exam_apply_gateway')=='sbi')
				{
					redirect(base_url().'Applyexam/sbi_make_payment/');
				}
				else
				{
					redirect(base_url().'Applyexam/make_payment/');
				}*/
				
				
				//Insert record in bulk admit_card_details##-------Start------## (Tejasvi)
			
				//################get userdata###########
				$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));
				
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
				
				//#######get Institute info########
				if(!empty($this->session->userdata['institute_id']) && ($this->session->userdata['institute_name']) )
				{
					$institute_id = $this->session->userdata['institute_id'];
					$institution_name = $this->session->userdata['institute_name'];
				}
				/* else
				{
					$this->session->set_flashdata('Error','Session Expire!!');
					redirect(base_url().'bulk/Banklogin/');
				} */
				
				//##############Examination Mode###########
				if($this->session->userdata['examinfo']['optmode']=='ON')
				{
					$mode='Online';
				}
				else
				{
					$mode='Offline';
				}	
			
				//set invoice details
				$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['examinfo']['excd'],'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
		
			    $password = random_password();
				//print_r(($this->session->userdata['examinfo']['subject_arr']));exit;
				if(!empty($this->session->userdata['examinfo']['subject_arr']))
				{

					

					foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
					{
							$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata['examinfo']['excd'],'subject_delete'=>'0','group_code'=>'C','exam_period'=>$this->session->userdata['examinfo']['eprid'],'subject_code'=>$k),'subject_description');
							
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));
					
							if ($this->session->userdata['examinfo']['el_subject'] != 'N') {  //Priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july
								if (array_key_exists($k, $this->session->userdata['examinfo']['el_subject'])) {
									$sub_el_flg = 'Y';
								} else {
									$sub_el_flg = 'N';
								}
							}

						$admitcard_insert_array=array(
													'mem_exam_id'=>$this->session->userdata['examinfo']['insdet_id'],
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'mem_type'=>$this->session->userdata('memtype'),
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
													'exm_cd'=>$this->session->userdata['examinfo']['excd'],
													'exm_prd'=>$this->session->userdata['examinfo']['eprid'],
													'sub_cd '=>$k,
													'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
													'm_1'=>$this->session->userdata['examinfo']['medium'],
													//'inscd'=>$institute_id,
													//'insname'=>$institution_name,
													'venueid'=>$get_subject_details[0]['venue_code'],
													'venue_name'=>$get_subject_details[0]['venue_name'],
													'venueadd1'=>$get_subject_details[0]['venue_addr1'],
													'venueadd2'=>$get_subject_details[0]['venue_addr2'],
													'venueadd3'=>$get_subject_details[0]['venue_addr3'],
										 			'venueadd4'=>$get_subject_details[0]['venue_addr4'],
													'venueadd5'=>$get_subject_details[0]['venue_addr5'],
													'venpin'=>$get_subject_details[0]['venue_pincode'],
													'pwd'=>$password,
													'exam_date'=>$get_subject_details[0]['exam_date'],
													'time'=>$get_subject_details[0]['session_time'],
													'mode'=>$mode,
													'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
													'vendor_code'=>$get_subject_details[0]['vendor_code'],
													'remark'=>2,
													'sub_el_flg'=>$sub_el_flg,// Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
													'record_source'=>'Bulk',
													'created_on'=>date('Y-m-d H:i:s'));
						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
					}
				}
				else
				{
				if($this->session->userdata['examinfo']['excd']!=101 
				&& $this->session->userdata['examinfo']['excd']!=1010
				&& $this->session->userdata['examinfo']['excd']!=10100
				&& $this->session->userdata['examinfo']['excd']!=101000
				&& $this->session->userdata['examinfo']['excd']!=1010000
				&& $this->session->userdata['examinfo']['excd']!=10100000
				&& $this->session->userdata['examinfo']['excd']!=996)
				{
					$this->session->set_flashdata('Error','Something went wrong!!');
					//redirect(base_url().'bulk/BulkApply/comApplication');
					redirect(base_url().'bulk/BulkApply/add_member');
				}
					
				}
				//##--------End-----------
				
				
				//unset member info session info
				$this->session->unset_userdata('mregid_applyexam');
				$this->session->unset_userdata('mregnumber_applyexam');
				$this->session->unset_userdata('memtype');
				
				$this->session->set_flashdata('success','Application for examination has been done successfully..');
				redirect(base_url().'bulk/BulkApply/exam_applicantlst');
			}
		}
		else
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
	}
	public function Msuccess_reg()
	{
		//echo '<pre>Msuccess_reg=',print_r($this->session->userdata['enduserinfo']),'</pre>';exit;
		
		$scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password='';
		
		//check email,mobile duplication on the same time from different browser!!
		$endTime = date("H:i:s");
		$start_time= date("H:i:s",strtotime("-20 minutes",strtotime($endTime)));
		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');
		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);
		$this->db->or_where('email',$this->session->userdata['enduserinfo']['mobile']);
		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));
		
		if(count($check_duplication) > 0)
		{
			redirect(base_url().'bulk/BulkApply/accessdenied/');
		}
		
		if(($this->session->userdata('enduserinfo')==''))
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		//echo '<pre>',print_r($this->session->userdata['enduserinfo']),'</pre>';
		//echo '<pre>',print_r($_POST),'</pre>';exit;
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
			
			$bank_branch=$this->session->userdata['enduserinfo']['bank_branch'];
			$bank_designation=$this->session->userdata['enduserinfo']['bank_designation'];
			$bank_scale=$this->session->userdata['enduserinfo']['bank_scale'];
			$bank_zone=$this->session->userdata['enduserinfo']['bank_zone'];
			$bank_emp_id=$this->session->userdata['enduserinfo']['bank_emp_id'];
			
			$insert_info = array(
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
							'registrationtype'=>'DB',
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
							'bank_branch'=>$bank_branch,
							'bank_designation'=>$bank_designation,
							'bank_scale'=>$bank_scale,
							'bank_zone'=>$bank_zone,
							'bank_emp_id'=>$bank_emp_id,
							'createdon'=>date('Y-m-d H:i:s')
				);	
				
				if($last_id =$this->master_model->insertRecord('member_registration',$insert_info,true))
				{
					//insert member regid 
					$this->master_model->updateRecord('member_registration',array('regnumber'=>$last_id),array('regid'=>$last_id));
					
					bulk_logactivity($log_title ="Non-Member user registration ", $log_message = serialize($insert_info),$this->session->userdata['institute_id']);
					
					$amount=bulk_getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],$this->session->userdata['enduserinfo']['excd'],$this->session->userdata['enduserinfo']['grp_code'],'DB',$this->session->userdata['enduserinfo']['elearning_flag'],$this->session->userdata['enduserinfo']['discount_flag']);
					//echo $amount;exit;
					//##------------get app_category and base_fee
					$fee_result=bulk_getFee_Appcat($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],$this->session->userdata['enduserinfo']['excd'],$this->session->userdata['enduserinfo']['grp_code'],'DB',$this->session->userdata['enduserinfo']['elearning_flag'],$this->session->userdata['enduserinfo']['discount_flag']);

					$sub_el_count	 =0; // Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
					if($this->session->userdata['enduserinfo']['elearning_flag']=='Y') {
						$sub_el_count = $this->session->userdata('subject_cnt');
					} // Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
					
					$inser_exam_array=array('regnumber'=>$last_id,
											'member_type'=>'DB', 
											'app_category'=>$fee_result['grp_code'],
											'base_fee'=>$fee_result['base_fee'],
											'original_base_fee'=>$fee_result['original_base_fee'],
											'bulk_discount_flg'=>$fee_result['bulk_discount_flg'],
											'discount_percent'=>$fee_result['discount_percent'],
											'discount_amount'=>$fee_result['discount_amount'],
											'calculate_discount'=>$fee_result['calculate_discount'],
											'taken_discount'=>$fee_result['taken_discount'],
											'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
											'exam_mode'=>$this->session->userdata['enduserinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['enduserinfo']['medium'],
											'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],
											'exam_fee'=>$amount,
											'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
											'created_on'=>date('y-m-d H:i:s'),
											'pay_status'=>'2',
											'institute_id'=>$this->session->userdata['institute_id'],
											'bulk_isdelete'=>'0',
											'examination_date'=>$this->session->userdata['enduserinfo']['special_exam_date'],
											'elearning_flag'=>$this->session->userdata['enduserinfo']['elearning_flag'],
											'sub_el_count'=>$sub_el_count// Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
											); 
													
					if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_exam_array,true))
					{
					//##----------prepare user name
					$username=$firstname.' '.$middlename.' '.$lastname;
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					
					//##----------set invoice details
					$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd'],'center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'center_delete'=>'0'));
				
					 //##---------check Gender
					if($gender=='male')
					{$gender='M';}
					else
					{$gender='F';}
					
					//##---------Institute Information
					if(!empty($this->session->userdata['institute_id']) && ($this->session->userdata['institute_name']) )
					{
						$institute_id = $this->session->userdata['institute_id'];
						$institution_name = $this->session->userdata['institute_name'];
					}
					
					//##----------get state name
					$states=$this->master_model->getRecords('state_master',array('state_code'=>$state,'state_delete'=>'0'));
					$state_name='';
					if(count($states) >0)
					{
						$state_name=$states[0]['state_name'];
					}		
				
					
					
					//##---------get mode 
					if($this->session->userdata['enduserinfo']['optmode']=='ON')
					{ $mode='Online'; }
					else
					{ $mode='Offline'; }	
					
					$password = random_password();
					
					//print_r(($this->session->userdata['enduserinfo']['subject_arr']));exit;
					
						if(!empty($this->session->userdata['enduserinfo']['subject_arr']))
						{
							

								foreach($this->session->userdata['enduserinfo']['subject_arr'] as $k=>$v)
								{
										$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'subject_delete'=>'0','group_code'=>'C','exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'subject_code'=>$k),'subject_description');
										$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));

									if ($this->session->userdata['enduserinfo']['el_subject'] != 'N') {  //Priyanka D >> DBFMOUPAYMENTCHANGE >> 10-july
										if (array_key_exists($k, $this->session->userdata['enduserinfo']['el_subject'])) {
											$sub_el_flg = 'Y';
										} else {
											$sub_el_flg = 'N';
										}
									}
									
									$admitcard_insert_array=array('mem_exam_id'=>$exam_last_id,
																'center_code'=>$getcenter[0]['center_code'],
																'center_name'=>$getcenter[0]['center_name'],
																'mem_type'=>'DB',
																'mem_mem_no'=>$last_id,
																'g_1'=>$gender,
																'mam_nam_1'=>$userfinalstrname,
																'mem_adr_1'=>$addressline1,
																'mem_adr_2'=>$addressline2,
																'mem_adr_3'=>$addressline3,
																'mem_adr_4'=>$addressline4,
																'mem_adr_5'=>$district,
																'mem_adr_6'=>$nationality,
																'mem_pin_cd'=>$pincode,
																'state'=>$state_name,
																'exm_cd'=>$this->session->userdata['enduserinfo']['excd'],
																'exm_prd'=>$this->session->userdata['enduserinfo']['eprid'],
																'sub_cd '=>$k,
																'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
																'm_1'=>$this->session->userdata['enduserinfo']['medium'],
																//'inscd'=>$institute_id,
																//'insname'=>$institution_name,
																'venueid'=>$get_subject_details[0]['venue_code'],
																'venue_name'=>$get_subject_details[0]['venue_name'],
																'venueadd1'=>$get_subject_details[0]['venue_addr1'],
																'venueadd2'=>$get_subject_details[0]['venue_addr2'],
																'venueadd3'=>$get_subject_details[0]['venue_addr3'],
																'venueadd4'=>$get_subject_details[0]['venue_addr4'],
																'venueadd5'=>$get_subject_details[0]['venue_addr5'],
																'venpin'=>$get_subject_details[0]['venue_pincode'],
																'pwd'=>$password,
																'exam_date'=>$get_subject_details[0]['exam_date'],
																'time'=>$get_subject_details[0]['session_time'],
																'mode'=>$mode,
																'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
																'vendor_code'=>$get_subject_details[0]['vendor_code'],
																'remark'=>2,
																'sub_el_flg'=>$sub_el_flg,// Priyanka D >>   DBFMOUPAYMENTCHANGE >> 10-july-25
																'record_source'=>'Bulk',
																'created_on'=>date('Y-m-d H:i:s'));
									$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
									//echo $this->db->last_query();exit;
								}
						}
						else
						{
							if($this->session->userdata['enduserinfo']['excd']!=101 
							&& $this->session->userdata['enduserinfo']['excd']!=1010
							&& $this->session->userdata['enduserinfo']['excd']!=10100
							&& $this->session->userdata['enduserinfo']['excd']!=101000
							&& $this->session->userdata['enduserinfo']['excd']!=1010000
							&& $this->session->userdata['enduserinfo']['excd']!=10100000
							&& $this->session->userdata['enduserinfo']['excd']!=996)
							{
							$this->session->set_flashdata('Error','Something went wrong!!');
							//redirect(base_url().'bulk/BulkApply/comApplication');
							redirect(base_url().'bulk/BulkApply/add_member');
							}
						}
							
					}					
				}
				
				$this->session->set_flashdata('success','Application for examination has been done successfully..');
				redirect(base_url().'bulk/BulkApply/exam_applicantlst');
		}
		else
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		
		
	}
	// reload captcha functionality  -(Tejasvi)
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemregcaptcha_bulk");
		$this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["nonmemregcaptcha_bulk"] = $cap['word'];
		echo $data;
	}
	
	
	//validate captcha -(Tejasvi)
	public function ajax_check_captcha()
	{
		$code=$_POST['code'];
		// check if captcha is set -
		if ($code == '' || $_SESSION["nonmemregcaptcha_bulk"] != $code)
		{
			$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
			//$this->session->set_userdata("regcaptcha", rand(1, 100000));
			echo  'false';
		}
		else if ($_SESSION["nonmemregcaptcha_bulk"] == $code)
		{
			//$this->session->unset_userdata("nonmemlogincaptcha");
			// $this->session->set_userdata("mycaptcha", rand(1,100000));
			echo 'true';
		}
	}
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
	{
		if(isset($code))
		{
			if($code == '' || $_SESSION["nonmemregcaptcha_bulk"] != $code )
			{
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
				//$this->session->set_userdata("regcaptcha", rand(1,100000));
				return false;
			}
			if($_SESSION["nonmemregcaptcha_bulk"] == $code)
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
	
	//check mail alredy exist or not
	public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$where="(registrationtype='DB')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isdeleted'=>'0','isactive'=>'1'));//,'isactive'=>'1'
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);}
			else
			{
				$str='You are already registered and the email ID is in use. If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
				
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	##---------check mobile number alredy exist or not for non member-----------##
	 public function mobileduplication()
	{
		$mobile=$_POST['mobile'];
		if($mobile!="")
		{
			$where="(registrationtype='DB')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isdeleted'=>'0','isactive'=>'1'));//,'isactive'=>'1'
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);
				}
			else
			{
			
				$str='You are already registered and the Mobile no is in use. If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
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
	
	// ##---------Thank you page for user -----------##
	public function acknowledge()
	{
	   $kycflag=0;
	   //$this->chk_session->checkphoto();
		$data=array('middle_content'=>'profile_thankyou','application_number'=>$this->session->userdata('regnumber'),'password'=>base64_decode($this->session->userdata('password')));
		$this->load->view('common_view',$data);
	
	}
	//##---------accessdenied page----------##
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
	 	$data=array('middle_content'=>'bulk/bulk-not_eligible','check_eligibility'=>$message);
		$this->load->view('bulk/bulk_common_view',$data);
	}
		
	##-------------- check qualify exam pass/fail
	public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)
	{
		//echo $examcode;exit;
		if($examcode==NULL || $examcode=='')
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
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
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');
		$this->db->where('bulk_exam_activation_master.institute_code',$this->session->userdata('institute_id'));
		$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')),'exam_status,remark');
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
					$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');
					$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));
					if(count($check_eligibility_for_applied_exam) > 0)
					{
						foreach($check_eligibility_for_applied_exam as $check_exam_status)
							{
								if($check_exam_status['exam_status']=='F')
								{
									$exam_status=0;
								}
							}
						/*if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')*/
						if($exam_status==1)
						{
							$flag=0;
							if(base64_decode($this->input->get('Extype'))=='3')
							{
								
									$message='You have already cleared this subject as separate  Examination. Hence you cannot apply for the same.';
								//$message='You have already cleared this subject under <strong>'.$check_qualify_exam_name[0]['description'].'</strong> Elective Examination. Hence you cannot apply for the same';
							}
							else
							{
								$message=$check_eligibility_for_applied_exam[0]['remark'];
							}
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						/*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}*/
								
						//else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
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
					$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam')),'specify_qualification');
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
							//$message='you have not cleared qualifying examination - <strong>'.$get_exam[0]['description'].'</strong>.';
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
						//$message='You have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';
						}
					}
				}
			}
			
			$check_qualify=array('flag'=>$flag,'message'=>$message);
			return $check_qualify;
		}
	}
	//check user already exam apply or not
	public function examapplied($regnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$today_date=date('Y-m-d');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
		$this->db->where('exam_master.elg_mem_db','Y');
		$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
		//$this->db->where('pay_status','0');
		$this->db->where('bulk_isdelete','0');
		$this->db->where('institute_id!=','');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$exam_code,'regnumber'=>$regnumber));
		//check for normal exam applied or not
		if(count($applied_exam_info)<=0)
		{
			$today_date=date('Y-m-d');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
			$this->db->where('exam_master.elg_mem_db','Y');
			$this->db->where('pay_status','1');
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$exam_code,'regnumber'=>$regnumber));
		}
		
		
		//echo $this->db->last_query();exit;
		return count($applied_exam_info);
	}
	//check user already exam apply or not - Special exam check
	public function check_examapplied($regnumber=NULL,$exam_code=NULL,$selected_date=NULL)
	{
		//check where exam alredy apply or not
		if($regnumber!=NULL&& $exam_code!=NULL && $selected_date!=NULL)
		{
			$check_applied_flag=0;
			$today_date=date('Y-m-d');
			$this->db->select('member_exam.examination_date,member_exam.exam_code');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
			$this->db->where('exam_master.elg_mem_db','Y');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			//$this->db->where('pay_status','1');
			$this->db->where('bulk_isdelete','0');
			$this->db->where('institute_id!=','');
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'examination_date !='=>''));
			//check for normal apply
			if(count($applied_exam_info)<=0)
			{
				$today_date=date('Y-m-d');
				$this->db->select('member_exam.examination_date,member_exam.exam_code');
				$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
				$this->db->where('exam_master.elg_mem_db','Y');
				$this->db->where('pay_status','1');
				$applied_exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'examination_date !='=>''));
			}
			
			if(count($applied_exam_info) > 0)
			{
				foreach($applied_exam_info as $row)
				{
					if($row['examination_date']==$selected_date)	
					{
						$check_applied_flag=1;
						$message=$this->get_alredy_applied_examname_special($regnumber,$exam_code,$selected_date);
						$this->session->set_flashdata('error',$message);
						redirect(base_url().'SpecialExamNm/comApplication');
					}
				}
			}
		}
	}
	//check whether applied exam date fall in same date of other exam date
	public function examdate($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$today_date=date('Y-m-d');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->where('institute_id!=','');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'bulk_isdelete'=>'0'),'member_exam.exam_code');//'pay_status'=>'1'
			
			#---- checking normal applied applied ------#
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code');
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
	##--------- get applied exam name which is fall on same date
	public function get_alredy_applied_examname($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$msg='';
		$today_date=date('Y-m-d');
		
		$this->db->select('subject_master.*,exam_master.description');
		$this->db->join('exam_master','exam_master.exam_code=subject_master.exam_code');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$exam_code,'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'bulk_isdelete'=>'0'),'member_exam.exam_code,exam_master.description'); //'pay_status'=>'1'
			
			#--------checking normal applied-------#
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
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
	//get applied exam name which is fall on same date(Prafull)
	public function get_alredy_applied_examname_special($regnumber=NULL,$exam_code=NULL,$selected_date=NULL)
	{
		$flag=0;
		$msg='';
		$today_date=date('Y-m-d');
		
		$this->db->select('subject_master.*,exam_master.description');
		$this->db->join('exam_master','exam_master.exam_code=subject_master.exam_code');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$exam_code,'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->where("bulk_exam_activation_master.institute_code",$this->session->userdata('institute_id'));
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'bulk_isdelete'=>'0','examination_date'=>$selected_date),'member_exam.exam_code,exam_master.description');
			
			#--------checking normal applied-------#
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
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
	
	
	// Remove an item from string
	public function removeFromString($str, $item) {
		$parts = explode(',', $str);
		while(($i = array_search($item, $parts)) !== false) {
			unset($parts[$i]);
		}
		return implode(',', $parts);
	}
	
	// ##---------Edit Images-----------##
	public function editimages()
	{
			$kyc_update_data=array();
			$kyc_edit_flag=0;
			$flag=1;
			$member_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'scannedphoto,scannedsignaturephoto,idproofphoto');
			$applicationNo = $this->session->userdata('regnumber');
			if(isset($_POST['btnSubmit']))  	
			 {
				if($_FILES['scannedphoto']['name']=='' && $_FILES['scannedsignaturephoto']['name']=='' && $_FILES['idproofphoto']['name']=='')
				{
					$this->form_validation->set_rules('scannedphoto','Please Change atleast One Value','file_required');
				}
				if($_FILES['scannedphoto']['name']!='')
				{
					$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
				}
				if($_FILES['scannedsignaturephoto']['name']!='')
				{
					$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
				}
				if($_FILES['idproofphoto']['name']!='')
				{
					$this->form_validation->set_rules('idproofphoto','id proof','file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
				}
				if($this->form_validation->run()==TRUE)
				{
					$prev_edited_on = '';
					$prev_photo_flg = "N";
					$prev_signature_flg = "N";
					$prev_id_flg = "N";
					$prev_edited_on_qry = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
					if(count($prev_edited_on_qry))
					{
						$prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
						$prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
						$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
						$prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
						if($prev_edited_on != date('Y-m-d'))
						{
							$this->master_model->updateRecord('member_registration', array('photo_flg'=>'N', 'signature_flg'=>'N', 'id_flg'=>'N'), array('regid'=>$this->session->userdata('regid')));
						}
					}
					
					$date=date('Y-m-d h:i:s');
					
					$scannedphoto_file = '';
					if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
					{	$photo_flg = 'N';	}
					else {	$photo_flg = $prev_photo_flg;	}
					
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
							$kyc_edit_flag=1;
							$kyc_update_data['edited_mem_photo']=1;
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
					
					$scannedsignaturephoto_file = '';
					if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
					{	$signature_flg = 'N';	}
					else {	$signature_flg = $prev_signature_flg;	}
					
					if($_FILES['scannedsignaturephoto']['name']!='')
					{
						@unlink('uploads/photograph/'.$member_info[0]['scannedsignaturephoto']);
						$path = "./uploads/scansignature";
						//$new_filename = 'sign_'.strtotime($date).rand(1,99999);
						$new_filename = 's_'.$applicationNo;
						$uploadData = upload_file('scannedsignaturephoto', $path, $new_filename,'','',TRUE);
						if($uploadData)
						{
							$kyc_edit_flag=1;
							$kyc_update_data['edited_mem_sign']=1;
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
					$idproofphoto_file = '';
					if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
					{	$id_flg = 'N';	}
					else {	$id_flg = $prev_id_flg;	}
					if($_FILES['idproofphoto']['name']!='')
					{
						@unlink('uploads/photograph/'.$member_info[0]['idproofphoto']);
						$path = "./uploads/idproof";
						//$new_filename = 'idproof_'.strtotime($date).rand(1,99999);
						$new_filename = 'pr_'.$applicationNo;
						$uploadData = upload_file('idproofphoto', $path, $new_filename,'','',TRUE); 
						if($uploadData)
						{
							$kyc_edit_flag=1;
							$kyc_update_data['edited_mem_proof']=1;
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
						/*$update_info = array(
												'scannedphoto'=>$scannedphoto_file,
												'scannedsignaturephoto'=>$scannedsignaturephoto_file,
												'idproofphoto'=>$idproofphoto_file,
												'editedon'=>date('Y-m-d H:i:s'),
												'photo_flg'=>$photo_flg,
												'signature_flg'=>$signature_flg,
												'id_flg'=>$id_flg,
												'editedon'=>date('Y-m-d H:i:s'),
												'editedby'=>'Candidate',
											);*/
											
						$update_info = array(
												'scannedphoto'=>$scannedphoto_file,
												'scannedsignaturephoto'=>$scannedsignaturephoto_file,
												'idproofphoto'=>$idproofphoto_file,
												'images_editedon'=>date('Y-m-d H:i:s'),
												'images_editedby'=>'Candidate',
												'photo_flg'=>$photo_flg,
												'signature_flg'=>$signature_flg,
												'id_flg'=>$id_flg,
												'kyc_edit'=>$kyc_edit_flag,
												'kyc_status'=>'0'
											);
											
						//$personalInfo = filter($personal_info);
						if($this->master_model->updateRecord('member_registration',$update_info,array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'))))
						{
							$desc['updated_data'] = $update_info;
							$desc['old_data'] = $member_info[0];
							//logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));
							
						/* User Log Activities : Bhushan */
						$log_title ="Member Edit Images";
						$log_message = serialize($desc);
						$rId = $this->session->userdata('regid');
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */
				
							$finalStr = '';
							if($edited!='')
							{
								$edit_data = trim($edited);
								$finalStr = rtrim($edit_data,"||");
							}
							log_profile_user($log_title = "Profile updated successfully", $finalStr,'image',$this->session->userdata('regid'),$this->session->userdata('regnumber'));
							
							
							if($kyc_edit_flag==1)
							{
								$kycmemdetails=$this->master_model->getRecords('member_kyc',array('regnumber'=>$this->session->userdata('regnumber')),'',array('kyc_id'=>'DESC'),'0','1');
								if(count($kycmemdetails) > 0)
								{
									$kyc_update_data['user_edited_date']=date('Y-m-d H:i:s');
									$kyc_update_data['kyc_state']='2';
									$kyc_update_data['kyc_status']='0';
									
									$this->db->like('allotted_member_id',$this->session->userdata('regnumber'));
									$this->db->or_like('original_allotted_member_id',$this->session->userdata('regnumber'));
									$check_duplicate_entry=$this->master_model->getRecords('admin_kyc_users',array('list_type'=>'New'));
									if(count($check_duplicate_entry) > 0)
									{
										foreach($check_duplicate_entry as $row)
										{
											$allotted_member_id=$this->removeFromString($row['allotted_member_id'],$this->session->userdata('regnumber'));
											$original_allotted_member_id=$this->removeFromString($row['original_allotted_member_id'],$this->session->userdata('regnumber'));
											$admin_update_data=array('allotted_member_id'=>$allotted_member_id,'original_allotted_member_id'=>$original_allotted_member_id);
											$this->master_model->updateRecord('admin_kyc_users',$admin_update_data,array('kyc_user_id'=>$row['kyc_user_id']));
										}
									}
									
									//$kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
									if($kycmemdetails[0]['kyc_status']=='0')
									{
										$this->master_model->updateRecord('member_kyc',$kyc_update_data,array('kyc_id'=>$kycmemdetails[0]['kyc_id']));
										$this->KYC_Log_model->create_log('kyc member edited images', '','',$this->session->userdata('regnumber'), serialize($desc));
									}
									
									//check membership count
									$check_membership_cnt=$this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')));
									if(count($check_membership_cnt) > 0)
									{
										//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
										/* update dowanload count 8-8-2017 */	
										$this->master_model->updateRecord('member_idcard_cnt',array('card_cnt'=>'0'),array('member_number'=>$this->session->userdata('regnumber')));
										/* Close update dowanload count */	
										/* User Log Activities : Pooja */
										$uerlog = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('regnumber')),'regid,regnumber');
										$user_info = $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')));
										$log_title ="Membership Id card Count Reset to 0 : ".$uerlog[0]['regid'];
										$log_message = serialize($user_info);
										$rId =$uerlog[0]['regid'];
										$regNo = $this->session->userdata('regnumber');
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										/* Close User Log Actitives */	
									}
						
								}	
							
							//echo $this->db->last_query();exit;
						//change by pooja godse for  memebersgip id card  dowanload count reset
					//check membership count
					$check_membership_cnt=$this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')));
					if(count($check_membership_cnt) > 0)
					{
						//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
						/* update dowanload count 8-8-2017 */	
						$this->master_model->updateRecord('member_idcard_cnt',array('card_cnt'=>'0'),array('member_number'=>$this->session->userdata('regnumber')));
						/* User Log Activities : Pooja */
						$uerlog = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('regnumber')),'regid,regnumber');
						$user_info = $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')));
						$log_title ="Membership Id card Count Reset to 0 : ".$uerlog[0]['regid'];
						$log_message = serialize($user_info);
						$rId =$uerlog[0]['regid'];
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */	
						/* Close update dowanload count */	
					}
			
			//logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
				
					/* User Log Activities : Bhushan */
						$log_title ="kyc member edited images id : ".$this->session->userdata('regid');
						$log_message = serialize($desc);
						$rId = $this->session->userdata('regid');
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */
			
	}
						
			
			
							//if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_userdata($this->session->userdata('regnumber')))
							if(!is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_ordinary_userdata($this->session->userdata('regnumber')))
							{
								$this->session->set_flashdata('error','Please update your profile!!');
								redirect(base_url('bulk/BulkApply/profile/'));
							}
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
							if(count($emailerstr) > 0)
							{
								$member_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'email');
								$newstring = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
								$final_str= str_replace("#password#", "".base64_decode($this->session->userdata('password'))."",  $newstring);
								$info_arr=array(
															'to'=>$member_info[0]['email'],
															'from'=>$emailerstr[0]['from'],
															'subject'=>$emailerstr[0]['subject'],
															'message'=>$final_str
														);
														
								if($this->Emailsending->mailsend($info_arr))
								{
									//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
									redirect(base_url('bulk/BulkApply/acknowledge/'));
								
								}
								else
								{
									$this->session->set_flashdata('error','Error while sending email !!');
									redirect(base_url('bulk/BulkApply/editimages/'));
								}
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								redirect(base_url('bulk/BulkApply/editimages/'));
							}
						}
						else
						{
							$desc['updated_data'] = $update_info;
							$desc['old_data'] = $member_info[0];
							//logactivity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
							
						/* User Log Activities : Bhushan */
						$log_title ="Error While Member Images Edit";
						$log_message = serialize($desc);
						$rId = $this->session->userdata('regid');
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */
							
							$this->session->set_flashdata('error','Error While Adding Your Information !!');
							$last = $this->uri->total_segments();
							$post = $this->uri->segment($last);
							redirect(base_url().$post);	
						}
			
					}
					else
					{
							$this->session->set_flashdata('error','Please follow the instruction while uploading image(s)!!');
							redirect(base_url('bulk/BulkApply/editimages/'));
					}
				}
				else
				{
					$data['validation_errors'] = validation_errors();
			 	}
			 }
		$data=array('middle_content'=>'member_edit_images','member_info'=>$member_info);
		$this->load->view('common_view',$data);
	}
	//##---------check mail alredy exist or not on edit page-----------## 
	public function editemailduplication()
	{
		$email = $_POST['email'];
		$regid = $_POST['regid'];
		if($email!="" && $regid!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'regid !='=>$regid,'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
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
	
	//##---------check mobile number alredy exist or not on edit page-----------## 
	public function editmobile()
	{
		$mobile = $_POST['mobile'];
		$regid = $_POST['regid'];
		if($mobile!="" && $regid!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'regid !='=>$regid,'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
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
	
	##------------------ chenge password ()---------------##
	public function changepass()
	{
		$this->chk_session->checkphoto();
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
				
				$row=$this->master_model->getRecordCount('member_registration',array('usrpassword'=>$curr_encrypass,'regid'=>$this->session->userdata('regid')));
				if($row==0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'bulk/BulkApply/changepass/');
				}
				else
				{
					if($current_pass!=$new_pass)
					{
						$input_array=array('usrpassword'=>$encPass);
						$this->master_model->updateRecord('member_registration',$input_array,array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')));
						//logactivity($log_title ="Changed password Member ", $log_message = serialize($input_array));
						
						/* User Log Activities : Bhushan */
						$log_title ="Changed password Member";
						$log_message = serialize($input_array);
						$rId = $this->session->userdata('regid');
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */
						
						$this->session->unset_userdata('password');
						$this->session->set_userdata("password",base64_encode($new_pass));
						$this->session->set_flashdata('success','Password Changed successfully.'); 
						redirect(base_url().'bulk/BulkApply/changepass/');
					}
					else
					{
						$this->session->set_flashdata('error','Current password and new password cannot be same..'); 
						redirect(base_url().'bulk/BulkApply/changepass/');
					}
				}
		  }
	  }
		$data=array('middle_content'=>'change_pass',$data);
		$this->load->view('common_view',$data);
	}
	##---------check pincode/zipcode alredy exist or not -----------##
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
	
	#Function is use to display all member exam list#
	public function all_exam_applicantlst()
    {
	  #---Display the member lsit ---#
	  //set seesion data 
	  $inst_id=$_SESSION['institute_id'];
	 //$exam_code=$this->session->userdata('examcode');
	  
	  $member_list=array();
	  $member_list=$this->master_model->getRecords('bulk_member_exam',array('inst_id'=>$inst_id,'virtual_del'=>0));
	
	  if(!empty($member_list))
	    { 
		   $member_list=$member_list;
	    }
	  #----end---#
		//$check_exam_activation=check_exam_activate($this->session->userdata('examcode'));
		
		$result = $this->master_model->getRecords('bulk_member_exam');
		$data  = array('middle_content'=>'bulk/all_exam_applicantlst','result'=>$result,'member_list'=>$member_list);
        $this->load->view('bulk/bulk_common_view', $data);
	//	$this->load->view('bulk/exam_applicantlst');
    }
	
	##-------------- check qualify exam pass/fail
	/*public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)
	{
		$flag=0;
		$check_qualify=array();
		$message='Pre qualifying exam not found';
		$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');
		$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('regnumber')),'exam_status,remark');
		if(count($check_qualify_exam_eligibility) > 0)
		{
			if($check_qualify_exam_eligibility[0]['exam_status']=='P')
			{
					//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
					$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('regnumber')));
					if(count($check_eligibility_for_applied_exam) > 0)
					{
						if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v')
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
					$message='You have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';
				}
			}
			
			$check_qualify=array('flag'=>$flag,'message'=>$message);
			return $check_qualify;
		}
					
	}*/
	
	
	
	
	public function add()
	{
		$data=array();
		$data  = array('middle_content' => 'bulk/bulk-add-exam');
       	    $this->load->view('bulk/bulk_common_view', $data);
		//$this->load->view('bulk/bulk-add-exam',$data);	
	}
	##------------------ Set applied exam value in session for logged in user()---------------##
	//public function setExamSession()
	//{
		/*$outputphoto1=$outputsign1=$photo_name=$sign_name='';
		if($this->session->userdata('examinfo'))
		{
			$this->session->unset_userdata('examinfo');
		}
		
		//Generate dynamic photo
		if(isset($_POST["hiddenphoto"]) && $_POST["hiddenphoto"]!='')
		{
			$input = $_POST["hiddenphoto"];
			//$tmp_nm = rand(0,100);
			$tmp_nm = 'p_'.$this->session->userdata('regnumber').'.jpg';
			$outputphoto = getcwd()."/uploads/photograph/".$tmp_nm;
			$outputphoto1 = base_url()."uploads/photograph/".$tmp_nm;
			file_put_contents($outputphoto, file_get_contents($input));
			$photo_name = $tmp_nm;
		}*/
		
		// generate dynamic id proof
		/*if(isset($_POST["hiddenscansignature"]) && $_POST["hiddenscansignature"]!='')
		{
			$inputsignature = $_POST["hiddenscansignature"];
			//$tmp_signnm = rand(0,100);
			$tmp_signnm = 's_'.$this->session->userdata('regnumber').'.jpg';
			$outputsign = getcwd()."/uploads/scansignature/".$tmp_signnm;
			$outputsign1 = base_url()."uploads/scansignature/".$tmp_signnm;
			file_put_contents($outputsign, file_get_contents($inputsignature));
			$sign_name = $tmp_signnm;
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
							//'selSubName'=>$_POST['selSubName'],
							'placeofwork'=>$_POST['placeofwork'],
							'state_place_of_work'=>$_POST['state_place_of_work'],
							'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
							'elected_exam_mode'=>$_POST['elected_exam_mode']
							);
			$this->session->set_userdata('examinfo',$user_data);
			logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
			//redirect(base_url().'BulkApply/preview');
			return 'true';*/
	//}
	##------------------Exam appky with SBI Payment Gate-way()---------------##
	public function sbi_make_payment()
	{
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			redirect(base_url(),'bulk/BulkApply/dashboard/');
		}
		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
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
					redirect(base_url().'bulk/BulkApply/comApplication');
				}
			}
		}
			if($sub_flag==0)
			{
				$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
				redirect(base_url().'bulk/BulkApply/comApplication');
			}
		
			$regno = $this->session->userdata('regnumber');
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."BulkApply/sbitranssuccess";
			$pg_fail_url    = base_url()."BulkApply/sbitransfail";
			
			if($this->config->item('sb_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				$amount=bulk_getExamFee($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'));
				//$amount=$this->session->userdata['examinfo']['fee'];
			}
			
		
			if($amount==0 || $amount=='')
			{
				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
				redirect(base_url().'bulk/BulkApply/comApplication');
			}
			
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
			
			//Ordinary member Apply exam
			//	Ref1 = orderid
			//	Ref2 = iibfexam
			//	Ref3 = member reg num
			//	Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth = $this->master_model->getRecords('misc_master',array('exam_code'=>$this->session->userdata['examinfo']['excd'],'exam_period'=>$this->session->userdata['examinfo']['eprid']),'exam_month');
			
			/*if($this->session->userdata['examinfo']['excd'] == 340 || $this->session->userdata['examinfo']['excd']==3400)
			{
				$exam_code=34;		
			}
			elseif($this->session->userdata['examinfo']['excd']==580 || $this->session->userdata['examinfo']['excd']==5800)
			{
				$exam_code=58;	
			}
			elseif($this->session->userdata['examinfo']['excd']==1600) 
			{
				$exam_code=160;	
			}
			elseif($this->session->userdata['examinfo']['excd']==200)
			{
				$exam_code=20;	
			}elseif($this->session->userdata['examinfo']['excd']==1770)
			{
				$exam_code=177;	
			}
			elseif($this->session->userdata['examinfo']['excd']==590)
			{
				$exam_code=59;	
			}
			elseif($this->session->userdata['examinfo']['excd']==810)
			{
				$exam_code=81;	
			}
			else
			{
				$exam_code=$this->session->userdata['examinfo']['excd'];	
			}*/
			
			 ####Code added by pooja ######
		   $get_exam_code=$this->master_model->getRecords('multiple_exam_period', array(
                'exam_code' => $this->session->userdata['examinfo']['excd'],
                'exam_period' => $this->session->userdata['examinfo']['eprid']
            ) , 'actul_exam_code');
			
			if(count($get_exam_code) > 0)
			{
				$exam_code = $get_exam_code[0]['actul_exam_code'];
			}
		  else
			{
				$exam_code = $this->session->userdata['examinfo']['excd'];
			}
			$ref4=($exam_code).$yearmonth[0]['exam_month'];
		   // Create transaction
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'            => $amount,
				'gateway'          => "sbiepay",
				'date'           		=> date('Y-m-d H:i:s'),
				'pay_type'        => '2',
				'ref_id'            => $this->session->userdata['examinfo']['insdet_id'],
				'description'     => $this->session->userdata['examinfo']['exname'],
				'status'             => '2',
				'exam_code'      =>$this->session->userdata['examinfo']['excd'],
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>"IIBF_EXAM_O",
				//'pg_other_details'=>$custom_field
			);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$this->session->userdata('regnumber')."^".$ref4;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			
			//set invoice details
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['examinfo']['excd'],'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
			
			if(count($getcenter) > 0)
			{
				//get state code,state name,state number.
				$getstate = $this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				
				//call to helper (fee_helper)
				$getfees=bulk_getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'));
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
													'exam_code'=>$this->session->userdata['examinfo']['excd'],
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'state_of_center'=>$getcenter[0]['state_code'],
													'member_no'=>$this->session->userdata('regnumber'),
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
													'created_on'=>date('Y-m-d H:i:s'));
			$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
															
			//insert into admit card table
			//################get userdata###########
			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'));
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
					/*	$seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');*/
						
							$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata['examinfo']['excd'],'subject_delete'=>'0','exam_period'=>$this->session->userdata['examinfo']['eprid'],'subject_code'=>$k),'subject_description');
							
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));
						
						$admitcard_insert_array=array(
													'mem_exam_id'=>$this->session->userdata['examinfo']['insdet_id'],
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'mem_type'=>$this->session->userdata('memtype'),
													'mem_mem_no'=>$this->session->userdata('regnumber'),
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
													'exm_cd'=>$this->session->userdata['examinfo']['excd'],
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
													'vendor_code'=>$get_subject_details[0]['vendor_code'],
													'remark'=>2,
													'created_on'=>date('Y-m-d H:i:s'));
											
												$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
						
					}
				}
			else
			{
				if($this->session->userdata['examinfo']['excd']!=101 
				&& $this->session->userdata['examinfo']['excd']!=1010
				&& $this->session->userdata['examinfo']['excd']!=10100
				&& $this->session->userdata['examinfo']['excd']!=101000
				&& $this->session->userdata['examinfo']['excd']!=1010000
				&& $this->session->userdata['examinfo']['excd']!=10100000
				&& $this->session->userdata['examinfo']['excd']!=996)
				{
					$this->session->set_flashdata('Error','Something went wrong!!');
					redirect(base_url().'bulk/BulkApply/comApplication');
				}
			}
			//set cookie for Apply Exam
			applyexam_set_cookie($this->session->userdata['examinfo']['insdet_id']);
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
		$responsedata = explode("|",$encData);
		$MerchantOrderNo = $responsedata[0]; 
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
		$elective_subject_name='';
	//Sbi B2B callback
	//check sbi payment status with MerchantOrderNo 
	$q_details = sbiqueryapi($MerchantOrderNo);
	if ($q_details)
	{
		if ($q_details[2] == "SUCCESS")
		{
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			//check user payment status is updated by s2s or not
			if($get_user_regnum[0]['status']==2)
			{
			######### payment Transaction ############
			$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
			$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
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
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
			
				if($exam_info[0]['exam_code']!=101 
				&& $exam_info[0]['exam_code']!=1010
				&& $exam_info[0]['exam_code']!=10100
				&& $exam_info[0]['exam_code']!=101000
				&& $exam_info[0]['exam_code']!=1010000
				&& $exam_info[0]['exam_code']!=10100000
				&& $exam_info[0]['exam_code']!=996)
				{
					########## Generate Admit card and allocate Seat #############
					$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
					############check capacity is full or not ##########
					//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
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
								$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'bulk/BulkApply/refund/'.base64_encode($MerchantOrderNo));
							}
						}
					}
					if(count($exam_admicard_details) > 0)
					{	
						$password=random_password();
						foreach($exam_admicard_details as $row)
						{
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
							
							$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
							
							//echo $this->db->last_query().'<br>';
							$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
				
							if($seat_number!='')
							{
								$final_seat_number =$seat_number;
								$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
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
									$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($exam_admicard_details);
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									//redirect(base_url().'BulkApply/refund/'.base64_encode($MerchantOrderNo));
								}
							}
						}
						
						##############Get Admit card#############
						$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
					}
					else
					{
						redirect(base_url().'bulk/BulkApply/refund/'.base64_encode($MerchantOrderNo));
					}
				}
				######update member_exam######
				$update_data = array('pay_status' => '1');
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
				
				$this->db->where('state_delete','0');
				$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
			
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
					$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
					$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
					$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
					$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
					$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
					$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
					$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
					//$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
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
				$info_arr=array('to'=>$result[0]['email'],
										//'to'=>'kumartupe@gmail.com',
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str
									);
				//echo '<pre>';
			//	print_r($info_arr);
			//	exit;
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
						$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
						}
					//}
					$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				
				$this->db->where('pay_txn_id',$payment_info[0]['id']);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
			}	
			
			if($attachpath!='')
			{		
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
				$log_title ="B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
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
			//Old Code
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
			//Query to get exam details	
			$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			redirect(base_url().'bulk/BulkApply/details/'.base64_encode($MerchantOrderNo).'/'.base64_encode($exam_info[0]['exam_code']));
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
				
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'B2B');
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			//Query to get Payment details	
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
			//Query to get user details
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
			//Query to get exam details	
			$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
			//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
			$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
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
			
			//Old Code
			redirect(base_url().'bulk/BulkApply/fail/'.base64_encode($MerchantOrderNo));
		}
		else
		{
			die("Please try again...");
		}
	}
	
	##------------------Insert data in member_exam table for applied exam,for logged in user Without Payment()---------------##
	/*public function saveexam()
	{
		$final_str='';
		$this->chk_session->checkphoto();
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'BulkApply/dashboard/');
		}
		if(isset($_POST['btnPreview']))
		{
			$inser_array=array(	'regnumber'=>$this->session->userdata('regnumber'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$this->session->userdata['examinfo']['fee'],
											'pay_status'=>'1',
											'elected_sub_code'=>$this->session->userdata['examinfo']['selected_elect_subcode'],
											'place_of_work'=>$this->session->userdata['examinfo']['placeofwork'],
											'state_place_of_work'=>$this->session->userdata['examinfo']['state_place_of_work'],
											'pin_code_place_of_work'=>$this->session->userdata['examinfo']['pincode_place_of_work'],
											'created_on'=>date('y-m-d H:i:s')
											);
											
			if($inser_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				//echo $this->session->userdata['examinfo']['fee'];
				$this->session->userdata['examinfo']['insdet_id']=$inser_id;
				$update_array=array();
				//update an array for images
				if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'scannedsignaturephoto');
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
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')));
					//@unlink('uploads/photograph/'.$photoname);
					//@unlink('uploads/scansignature/'.$singname);
					
				logactivity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
					
				}
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');
				
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
				$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$inser_id,'member_exam.regnumber'=>$this->session->userdata('regnumber')),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				
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
				
				$this->db->where('state_delete','0');
				$states=$this->master_model->getRecords('state_master',array('state_code'=>$this->session->userdata['examinfo']['state_place_of_work']),'state_name');
		
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
			 	 {
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);
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
					$newstring18 = str_replace("#PLACE_OF_WORK#", "".strtoupper($this->session->userdata['examinfo']['placeofwork'])."",$newstring17);
					$newstring19 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring18);
					$newstring20 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$this->session->userdata['examinfo']['pincode_place_of_work']."",$newstring19);
					$final_str = str_replace("#MODE#", "".$mode."",$newstring20);
			 	 }
				  else
				  {
			  		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);
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
				  }
				
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				//To Do---Transaction email to user	currently we using failure emailer 					
				$this->Emailsending->mailsend($info_arr);
				redirect(base_url().'BulkApply/savedetails/');
				
			}
		}
		else
		{
			redirect(base_url().'BulkApply/dashboard/');
		}
	}*/
	//Show acknowlodgement to to user after transaction succeess
	public function details($order_no=NULL,$excd=NULL)
	{
		$this->chk_session->checkphoto();
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('regnumber')));
		
		
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');
		$this->db->where('elg_mem_o','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($excd),'regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'));
		
		if(count($applied_exam_info)<=0)
		{
			redirect(base_url().'bulk/BulkApply/dashboard');
		}
			
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',base64_decode($excd));
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
	
		$this->db->where('exam_name',base64_decode($excd));
		$this->db->where("center_delete",'0');
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		//echo $this->db->last_query();exit;
		
		//get state details
		$this->db->where('state_delete','0');
		$states=$this->master_model->getRecords('state_master');
		
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		
		/*
		$user_data=array('email'=>'',
						'mobile'=>'',	
						'photo'=>'',
						'signname'=>'',
						'medium'=>'',
						'selCenterName'=>'',
						'optmode'=>'',
						'extype'=>'',
						'exname'=>'',
						'excd'=>'',
						'eprid'=>'',
						'fee'=>'',
						'txtCenterCode'=>'',
						'insdet_id'=>'',
						'selected_elect_subcode'=>'',
						'selected_elect_subname'=>'',
						'placeofwork'=>'',
						'state_place_of_work'=>'',
						'pincode_place_of_work'=>''
                		);
		$this->session->unset_userdata('examinfo',$user_data);
		*/
		$data=array('middle_content'=>'exam_applied_success','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info,'payment_info'=>$payment_info,'states'=>$states);
		$this->load->view('common_view',$data);
	}
	//Show acknowlodgement to to user after transaction succeess
	public function savedetails()
	{
		$this->chk_session->checkphoto();
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		$exam_code= $this->session->userdata['examinfo']['excd'];
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_o','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$exam_code,'regnumber'=>$this->session->userdata('regnumber')));
		
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',$exam_code);
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		$this->db->where('exam_name',$exam_code);
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$this->db->where("center_delete",'0');
		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		//get state details
		$this->db->where('state_delete','0');
		$states=$this->master_model->getRecords('state_master');
			
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		$data=array('middle_content'=>'exam_applied_success_withoutpay','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info,'states'=>$states);
		$this->load->view('common_view',$data);
	}
	//Show acknowlodgement to to user after transaction Failure
	public function fail($order_no=NULL)
	{
		$this->chk_session->checkphoto();
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('regnumber')));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		$data=array('middle_content'=>'exam_applied_fail','payment_info'=>$payment_info);
		$this->load->view('common_view',$data);
	
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
		$data=array('middle_content'=>'member_refund','payment_info'=>$payment_info,'exam_name'=>$exam_name);
		$this->load->view('common_view',$data);
	
	}
	
	/*//get applied exam name which is fall on same date
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
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
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
	}*/
	##---------Forcefully Update profile mesage to user-----------##
	public function notification()
	{
		 $msg='';
		 $flag=1;
		 $user_images=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'),'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');
		  
		 if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']=='') && (!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p'))))
		 {
			 $flag=0;
			$msg.='<li>Your Photo/signature or ID proof are not available kindly go to Edit Profile and <a href="'.base_url().'BulkApply/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>';
		 }
		 if($user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
		 {
			 $flag=0;
			$msg.='<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'BulkApply/profile/">click here</a> to update the, email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
		}
		
		if(validate_userdata($this->session->userdata('regnumber')))
		 {
			 $flag=0;
			$msg.='<li>
Please check all mandatory fields in profile <a href="'.base_url().'BulkApply/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';
		}
		
	/*	if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) || ($user_images[0]['scannedphoto']=='' || $user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']!='')) || ($user_images[0]['mobile']=='' ||$user_images[0]['email']==''))
		{
		
			$flag=0;
			$msg='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'BulkApply/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>
			<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'BulkApply/profile/">click here</a> to update the, then email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
		}*/
		 
		if($flag)
		{
			redirect(base_url().'bulk/BulkApply/dashboard');
		}
		$data=array('middle_content'=>'member_notification','msg'=>$msg);
		$this->load->view('common_view',$data);
	}
	##---print user edit profile 
	public function printUser()
	{
			$this->chk_session->checkphoto();
			$qualification=array();
			$this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'));
			if(count($user_info) <=0)
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
	
	##--print user edit profile 
	public function printexamdetails()
	{
			$state_place_of_work=$elective_subject_name='';
			if(($this->session->userdata('examinfo')==''))
			{
				redirect(base_url().'bulk/BulkApply/dashboard/');
			}
			//$this->chk_session->checkphoto();
			$qualification=array();
			$this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'));
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
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
			
			if(($this->session->userdata('examinfo')==''))
			{
				redirect(base_url().'bulk/BulkApply/dashboard/');
			}
			$exam_code= $this->session->userdata['examinfo']['excd'];
			$today_date=date('Y-m-d');
			$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');
			$this->db->where('elg_mem_o','Y');	
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$exam_code,'regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'));
			
			$this->db->where('medium_delete','0');
			$this->db->where('exam_code',$exam_code);
			$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			
			$this->db->where('exam_name',$exam_code);
			$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		
			//get state details
			$this->db->where('state_delete','0');
			$states=$this->master_model->getRecords('state_master');
				
			if(count($applied_exam_info) <=0)
			{
				redirect(base_url().'bulk/BulkApply/dashboard/');
			}
		
		//get Elective Subeject name for CAIIB Exam
		 $elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$applied_exam_info['0']['elected_sub_code'],'subject_delete'=>0),'subject_description');
		if(count($elective_sub_name_arr) > 0)
		{
			$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
		}	
		
		$data=array('middle_content'=>'print_member_applied_exam_details','user_info'=>$user_info,'qualification'=>$qualification,'idtype_master'=>$idtype_master,'applied_exam_info'=>$applied_exam_info,'medium'=>$medium,'center'=>$center,'qualification'=>$qualification,'states'=>$states,'elective_subject_name'=>$elective_subject_name);
		$this->load->view('common_view',$data);
	}
	
	//download pdf 
	public function downloadeditprofile()
	{
			$gender=$idtype='';
			$this->chk_session->checkphoto();
			$qualification=array();
			$this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'));
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
			<img src="'.base_url().get_img_name($this->session->userdata('regnumber'),'p').'" height="100" width="100" >
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
				'.$useradd.'			</td>
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
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['dname'].' </td>
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
			<td class="tablecontent2">Aadhar Card Number :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['aadhar_card'].'</td>
		</tr>
		
		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$idtype.'</td>
		</tr>
		
				<tr>
			<td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> '.$optnletter.' </td>
		</tr>
		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="'.base_url().get_img_name($this->session->userdata('regnumber'),'pr').'"  height="180" width="100"></td>
		</tr>
		<tr>
			<td class="tablecontent2">Signature :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="'.base_url().get_img_name($this->session->userdata('regnumber'),'s').'" height="100" width="100"></td>
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
	// ##---------Download pdf-----------##
	public function pdf()
	{
		$this->chk_session->checkphoto();
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
						Your application saved successfully.<br><br><strong>Your Membership No is</strong> '.$this->session->userdata('regnumber').' <strong>and Your password is </strong>'.base64_decode($this->session->userdata('password')).'<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
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
	##---------------------- Public function exam pdf ##########
	public function exampdf()
	{	
			$state_place_of_work=$elective_subject_name=$ID_Proof='';
			$this->chk_session->checkphoto();
			if(($this->session->userdata('examinfo')==''))
			{
			redirect(base_url().'bulk/BulkApply/dashboard/');
			}
			$qualification=array();
			$this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
			$this->db->where('institution_master.institution_delete','0');
			$this->db->where('state_master.state_delete','0');
			$this->db->where('designation_master.designation_delete','0');
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'));
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
		   $useradd=preg_replace('#[\s]+#', ' ', $finalstr1.$finalstr2);
							   
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		//$this->session->userdata['examinfo']['excd']='NTgw';
		$exam_code= $this->session->userdata['examinfo']['excd'];
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');
		$this->db->where('elg_mem_o','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$exam_code,'regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'));
		
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',$exam_code);
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		$this->db->where('exam_name',$exam_code);
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$this->db->where("center_delete",'0');
		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		
		//get state details
		$this->db->where('state_delete','0');
		$states=$this->master_model->getRecords('state_master');
			
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url().'bulk/BulkApply/dashboard/');
		}
		
		 //$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
		 $month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4);
         $exam_period= date('F',strtotime($month))."-".substr($applied_exam_info['0']['exam_month'],0,-2);
		if($applied_exam_info[0]['exam_mode']=='ON')
		{
			$mode= 'Online';
		}
		else if($applied_exam_info[0]['exam_mode']=='OF')
		{
			$mode= 'Offline';
		}
		
		//get sate name for CAIIB/JAIIB examination		
		if(count($states) > 0 && $applied_exam_info[0]['state_place_of_work']!='')
		{
			foreach($states as $srow)
			{
				if($applied_exam_info[0]['state_place_of_work']==$srow['state_code'])
				{
					$state_place_of_work= $srow['state_name'];
				}	
			 }
		}
			
		//get Elective Subeject name for CAIIB Exam	
	   $elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$applied_exam_info['0']['elected_sub_code'],'subject_delete'=>0),'subject_description');
		if(count($elective_sub_name_arr) > 0)
		{
			$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
		}	
			
		if(isset($idtype_master[0]['name']))
		{
			$ID_Proof=$idtype_master[0]['name'];
		}				
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
			<img src="'.base_url().get_img_name($this->session->userdata('regnumber'),'p').'" height="100" width="100" >
			</td>
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
				'.wordwrap($useradd,50,"<br>\n").'			</td>
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
			<td class="tablecontent2">Aadhar Card Number :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$user_info[0]['aadhar_card'].'</td>
		</tr>
		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$ID_Proof.'</td>
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
		</tr>';
	         if($applied_exam_info[0]['elected_sub_code']!=0)
			  {
				 	$html.='<tr>
					<td class="tablecontent2">Elective Subject Name :</td>
					<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$elective_subject_name.'</td>
					</tr>';
				}
				
			if($applied_exam_info[0]['place_of_work']!='' && $applied_exam_info[0]['state_place_of_work']!='' && $applied_exam_info[0]['pin_code_place_of_work']!='' )
			{
					$html.='
					<tr>
					<td class="tablecontent2">Place of Work :</td>
					<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$applied_exam_info[0]['place_of_work'].'</td>
					</tr>
						<tr>
						<td class="tablecontent2">State (Place of Work) :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$state_place_of_work.'</td>
						</tr>
						<tr>
						<td class="tablecontent2">Pin Code (Place of Work) :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">'.$applied_exam_info[0]['pin_code_place_of_work'].'</td>
					</tr>';
				 
			}
        
$html.=	'<tr>
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
				 $valid_member_list=$this->master_model->getRecords('eligible_master',array('eligible_period'=>'417','member_type'=>'O'),'member_no');
				if(count($valid_member_list) > 0)
				{
					foreach($valid_member_list as $row)
					{
						$memberlist_arr[]=$row['member_no'];
					}
					 if(in_array($this->session->userdata('regnumber'),$memberlist_arr))
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
	//check aadhar card
	public function check_aadhar($aadhar_card)
	{
		
		if($aadhar_card!="")
		{
			$where="registrationtype='DB'";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('aadhar_card'=>$aadhar_card));//,'isactive'=>'1'
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
	
	public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'regid !='=>$this->session->userdata('regid'),'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			
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
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'regid !='=>$this->session->userdata('regid'),'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			
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
  // ##---------End user profile -----------##
   public function profile()
   {
	    $kycflag=0;
		$prevData = array();
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'));
		if(count($user_info))
		{
			$prevData = $user_info[0];
		}
		else
		{
			base_url();
		}
	  $scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file ='';
	  if(isset($_POST['btnSubmit']))  	
	  {
		$this->form_validation->set_rules('firstname','First Name','trim|max_length[30]|required');
		$this->form_validation->set_rules('nameoncard','Name as to appear on Card','trim|max_length[35]|required');
		$this->form_validation->set_rules('addressline1','Addressline1','trim|max_length[30]|required');
		$this->form_validation->set_rules('district','District','trim|max_length[30]|required');
		$this->form_validation->set_rules('city','City','trim|max_length[30]|required');
		$this->form_validation->set_rules('state','State','trim|required');
		$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required');
		//$this->form_validation->set_rules('dob','Date of Birth','trim|required');
		$this->form_validation->set_rules('gender','Gender','trim|required');
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
		
		if(isset($_POST['middlename']))
		{
			$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
		}
		
		if(isset($_POST['lastname']))
		{
			$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
		}
		
			$this->form_validation->set_rules('institutionworking','Bank/Institution working','trim|numeric|required');
		
		
		$this->form_validation->set_rules('office','Branch/Office','trim|required');
		$this->form_validation->set_rules('designation','Designation','trim|required');
		$this->form_validation->set_rules('doj1','Date of joining Bank/Institution','trim|required');
		//$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required');
		//$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|numeric|is_unique[member_registration.aadhar_card.regid.'.$this->session->userdata('regid').']');
		if($this->input->post('state')=='ASS' || $this->input->post('state')=='JAM' || $this->input->post('state')=='MEG')
		{
			if( $this->input->post("aadhar_card")!='')
			{
				//$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
			$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
			
			}
		}
	else
	{		if( $this->input->post("aadhar_card")!='')
			{
				//$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
			$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
			
			}
	}
		
		$this->form_validation->set_rules('idproof','Id Proof','trim|required|max_length[25]|xss_clean');
		$this->form_validation->set_rules('sel_namesub','Sub Name','trim|required');
		
		$this->form_validation->set_rules('scannedphoto1_hidd','Uploaded Photo','trim|required');
		$this->form_validation->set_rules('scannedsignaturephoto1_hidd','uploaded Signature','trim|required');
		$this->form_validation->set_rules('idproofphoto1_hidd','Uploaded ID Proof','trim|required');
		
		//$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_validunique[member_registration.email.regid.'.$this->session->userdata('regid').'.isactive.1]|xss_clean');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_validuniqueO[member_registration.email.regid.'.$this->session->userdata('regid').'.isactive.1.registrationtype.'.$this->session->userdata('memtype').']|xss_clean');
		
		$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric');
		
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
			//$dob= $this->input->post('dob');
			$gender =$this->input->post('gender');
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
			
			$institutionworking = $this->input->post('institutionworking');
			
			$office = strtoupper($this->input->post('office'));
			$designation = $this->input->post('designation');
			$doj = $this->input->post('doj1');
			$email = $this->input->post('email');
			$stdcode = $this->input->post('stdcode');
			$phone = $this->input->post('phone');
			$mobile = $this->input->post('mobile');
			$idproof = $this->input->post('idproof');
			$optnletter = $this->input->post('optnletter');
			$declaration1 = $this->input->post("declaration1");
			$aadhar_card = $this->input->post("aadhar_card");
			$idproof = $this->input->post("idproof");
			$sel_namesub= $this->input->post("sel_namesub");
			$firstname= $this->input->post("firstname");
			$nameoncard= $this->input->post("nameoncard");
			$dob= $this->input->post("dob1");		
			$middlename= $this->input->post("middlename");		
			$lastname= $this->input->post("lastname");		
			
				
	
			// Check if value is edited
			$update_data = $kyc_update_data=array();
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
				
				if(date('Y-m-d',strtotime($prevData['dateofbirth'])) != date('Y-m-d',strtotime($dob)) && $dob!=='')
				{	$update_data['dateofbirth'] = date('Y-m-d',strtotime($dob));	
					$update_data['kyc_edit']='1';
					$update_data['kyc_status']='0';
					$kycflag=1;
					$kyc_update_data['edited_mem_dob']=1;
					}
				
				if($prevData['gender'] != $gender)
				{	$update_data['gender'] = $gender;	}
				
				if($prevData['qualification'] != $optedu)
				{	$update_data['qualification'] = $optedu;	}
				
				if($prevData['specify_qualification'] != $specify_qualification)
				{	$update_data['specify_qualification'] = $specify_qualification;	}
				
				if($prevData['associatedinstitute'] != $institutionworking)
				{	$update_data['associatedinstitute'] = $institutionworking;	
					$update_data['kyc_edit']='1';
					$update_data['kyc_status']='0';
					$kycflag=1;
					$kyc_update_data['edited_mem_associate_inst']=1;
				}
				
				if($prevData['office'] != $office)
				{	$update_data['office'] = $office;	}
				
				if($prevData['designation'] != $designation)
				{	$update_data['designation'] = $designation;	}
				
				if(date('Y-m-d',strtotime($prevData['dateofjoin'])) != date('Y-m-d',strtotime($doj)))
				{	$update_data['dateofjoin'] = date('Y-m-d',strtotime($doj));	
					}
				
				if($prevData['email'] != $email)
				{	$update_data['email'] = $email;	}
				
				if($prevData['stdcode'] != $stdcode)
				{	$update_data['stdcode'] = $stdcode;	}
				
				if($prevData['office_phone'] != $phone)
				{	$update_data['office_phone'] = $phone;	}
				
				if($prevData['mobile'] != $mobile)
				{	$update_data['mobile'] = $mobile;	}
				
				if($prevData['idproof'] != $idproof)
				{	$update_data['idproof'] = $idproof;	}
				
				
				if($prevData['aadhar_card'] != $aadhar_card)
				{	$update_data['aadhar_card'] = $aadhar_card;	}
				
				
				if($prevData['optnletter'] != $optnletter)
				{	$update_data['optnletter'] = $optnletter;	}
				
				if($prevData['namesub'] != $sel_namesub)
				{	$update_data['namesub'] = $sel_namesub;	
					$update_data['kyc_edit']='1';
					$update_data['kyc_status']='0';
					$kycflag=1;
					$kyc_update_data['edited_mem_name']=1;
				}
				
				if($prevData['firstname'] != $firstname)
				{	$update_data['firstname'] = $firstname;	
					$update_data['kyc_edit']='1';
					$update_data['kyc_status']='0';
					$kycflag=1;
					$kyc_update_data['edited_mem_name']=1;
				}
				
				if($prevData['middlename'] != $middlename)
				{	$update_data['middlename'] = $middlename;	
					$update_data['kyc_edit']='1';
					$update_data['kyc_status']='0';
					$kycflag=1;
					$kyc_update_data['edited_mem_name']=1;
				}
				
				if($prevData['lastname'] != $lastname)
				{	$update_data['lastname'] = $lastname;	
					$update_data['kyc_edit']='1';
					$update_data['kyc_status']='0';
					$kycflag=1;
					$kyc_update_data['edited_mem_name']=1;
				}
				
				
				if($prevData['displayname'] != $nameoncard)
				{	$update_data['displayname'] = $nameoncard;	}
			
				
			}	
		
			
		$edited = array();
		$edited = '';
		if(count($update_data))
		{
			foreach($update_data as $key => $val)
			{
				$edited .= strtoupper($key)." = ".strtoupper($val)." && ";
			}
			
			// Set Image update flags to "N"
			/*$prev_edited_on_qry = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'DATE(editedon) editedon');
			if(count($prev_edited_on_qry))
			{
				$prev_edited_on = $prev_edited_on_qry[0]['editedon'];
				if($prev_edited_on != date('Y-m-d'))
				{
					$this->master_model->updateRecord('member_registration', array('photo_flg'=>'N', 'signature_flg'=>'N', 'id_flg'=>'N'), array('regid'=>$this->session->userdata('regid')));
				}
			}*/
			
			$update_data['editedon'] = date('Y-m-d H:i:s');
			$update_data['editedby'] = 'Candidate';
			//$update_data['editedbyadmin'] = $this->UserID;
		
			//update member_kyc 
			
			
			
			//$personalInfo = filter($personal_info);
			if($this->master_model->updateRecord('member_registration',$update_data,array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'))))
			{
				$desc['updated_data'] = $update_data;
				$desc['old_data'] = $user_info[0];
				//profile update logs
				log_profile_user($log_title = "Profile updated successfully", $edited,'data',$this->session->userdata('regid'),$this->session->userdata('regnumber'));
				
				//logactivity($log_title = "Profile updated successfully id:".$this->session->userdata('regid'), $description = serialize($desc));
				
					/* User Log Activities : Bhushan */
					$log_title ="Profile updated successfully id:".$this->session->userdata('regid');
					$log_message = serialize($desc);
					$rId = $this->session->userdata('regid');
					$regNo = $this->session->userdata('regnumber');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
				
				if($kycflag==1)
				{
					$kyc_update_data['user_edited_date']=date('Y-m-d H:i:s');
					$kyc_update_data['kyc_state']='2';
					$kyc_update_data['kyc_status']='0';
					/*echo '<pre>';
					print_r($kyc_update_data);
					exit;*/
					
					$this->db->like('allotted_member_id',$this->session->userdata('regnumber'));
					$this->db->or_like('original_allotted_member_id',$this->session->userdata('regnumber'));
					$check_duplicate_entry=$this->master_model->getRecords('admin_kyc_users',array('list_type'=>'New'));
					if(count($check_duplicate_entry) > 0)
					{
						foreach($check_duplicate_entry as $row)
						{
							$allotted_member_id=$this->removeFromString($row['allotted_member_id'],$this->session->userdata('regnumber'));
							$original_allotted_member_id=$this->removeFromString($row['original_allotted_member_id'],$this->session->userdata('regnumber'));
							$admin_update_data=array('allotted_member_id'=>$allotted_member_id,'original_allotted_member_id'=>$original_allotted_member_id);
							$this->master_model->updateRecord('admin_kyc_users',$admin_update_data,array('kyc_user_id'=>$row['kyc_user_id']));
						}         
					}
					
					$kycmemdetails=$this->master_model->getRecords('member_kyc',array('regnumber'=>$this->session->userdata('regnumber')),'',array('kyc_id'=>'DESC'),'0','1');
					if(count($kycmemdetails) > 0)
					{
						if($kycmemdetails[0]['kyc_status']=='0')
						{
							$this->master_model->updateRecord('member_kyc',$kyc_update_data,array('kyc_id'=>$kycmemdetails[0]['kyc_id']));
							$this->KYC_Log_model->create_log('kyc member profile edit', '','',$this->session->userdata('regnumber'), serialize($desc));
						}
						
					}	
					//echo $this->db->last_query();exit;
					//change by pooja godse for  memebersgip id card  dowanload count reset
					//check membership count
					$check_membership_cnt=$this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')));
					if(count($check_membership_cnt) > 0)
					{
						//$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
						/* update dowanload count 8-8-2017 */	
						$this->master_model->updateRecord('member_idcard_cnt',array('card_cnt'=>'0'),array('member_number'=>$this->session->userdata('regnumber')));
						/* Close update dowanload count */	
						/* User Log Activities : Pooja */
						$uerlog = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('regnumber')),'regid,regnumber');
						$user_info = $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')));
						$log_title ="Membership Id card Count Reset to 0 : ".$uerlog[0]['regid'];
						$log_message = serialize($user_info);
						$rId =$uerlog[0]['regid'];
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */	
					}
						//	logactivity($log_title = "KYC Profile updated successfully id:".$this->session->userdata('regid'), $description = serialize($desc));
						/* User Log Activities : Bhushan */
						$log_title ="KYC Profile updated successfully id:".$this->session->userdata('regid');
						$log_message = serialize($desc);
						$rId = $this->session->userdata('regid');
						$regNo = $this->session->userdata('regnumber');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						/* Close User Log Actitives */	
					
				}
			
			
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
				if(count($emailerstr) > 0)
				{
					$newstring = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
					$final_str= str_replace("#password#", "".base64_decode($this->session->userdata('password'))."",  $newstring);
					$info_arr=array(
												'to'=>$email,
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
											
					if($this->Emailsending->mailsend($info_arr))
					{
						//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
						redirect(base_url('bulk/BulkApply/acknowledge/'));
					}
					else
					{
						
						$this->session->set_flashdata('error','Error while sending email !!');
						redirect(base_url('bulk/BulkApply/profile/'));
					}
				}
				else
				{
					$this->session->set_flashdata('error','Error while sending email !!');
					redirect(base_url('bulk/BulkApply/profile/'));
				}
			}
			else
			{
				$desc['updated_data'] = $update_data;
				$desc['old_data'] = $user_info[0];
				//logactivity($log_title = "Profile update error id:".$this->session->userdata('regid'), $description = serialize($desc));
					
				/* User Log Activities : Bhushan */
				$log_title ="Profile update error id:".$this->session->userdata('regid');
				$log_message = serialize($desc);
				$rId = $this->session->userdata('regid');
				$regNo = $this->session->userdata('regnumber');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				/* Close User Log Actitives */
				
				
				$this->session->set_flashdata('error','Error While Adding Your Information !!');
				$last = $this->uri->total_segments();
				$post = $this->uri->segment($last);
				redirect(base_url().$post);	
			}
		}
		else
		{
			$this->session->set_flashdata('error','Change atleast one field');
			redirect(base_url('bulk/BulkApply/profile/'));	
		}
	}
	else
	{
		//echo validation_errors();exit;
	$data['validation_errors'] = validation_errors();
	//echo "222222";vdebug($_POST);exit; 
	}
	}
	 
	$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
	
	$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
	
	$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
	
	
	$this->db->where('institution_master.institution_delete','0');
	$institution_master=$this->master_model->getRecords('institution_master');
	
	
	$this->db->where('state_master.state_delete','0');
	$states=$this->master_model->getRecords('state_master');
	
	$this->db->where('designation_master.designation_delete','0');
	$designation=$this->master_model->getRecords('designation_master');
	
	/*$this->db->like('name','Employer\'s card');
	$this->db->or_like('name','Declaration Form');*/
	$this->db->where('id',4);
	$this->db->or_where('id',8);
	$idtype_master=$this->master_model->getRecords('idtype_master');
	$data=array('middle_content'=>'userprofile','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master);
	$this->load->view('common_view',$data);
   }
}
