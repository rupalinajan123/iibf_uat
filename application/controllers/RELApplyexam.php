<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RELApplyexam extends CI_Controller {
	
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

	 ##---------default userlogin (prafull)-----------##
	public function exapplylogin1()
	{	
		exit;
	
		$data=array();
		$data['error']='';
		
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
					'rules' => 'trim|required|callback_check_captcha_examapply',
			),
		);
		
		$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE){
				
				$exm_cd_arr = array(340,3400,1600,16000,177,1770);
				$exm_prd_arr = array(912,913);
				$this->db->where('mem_mem_no',$this->input->post('Username'));
				$this->db->where_in('exm_prd',$exm_prd_arr);
				$this->db->where_in('exm_cd',$exm_cd_arr);
				$this->db->where('remark',1);
				$this->db->order_by("admitcard_id", "desc");
				$this->db->limit(1);
				$chk_eligible = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mam_nam_1,mem_type,exm_cd,exm_prd,mem_exam_id,admitcard_id');
				
				//echo $this->db->last_query();
				//exit;
				
				if(count($chk_eligible) > 0)
				{ 
					
					$exm_cd_arr = array(1002,1003,1004);
					$exm_prd_arr = array(777);
					$this->db->where('mem_mem_no',$this->input->post('Username'));
					$this->db->where_in('exm_prd',$exm_prd_arr);
					$this->db->where_in('exm_cd',$exm_cd_arr);
					$this->db->where('remark',1);
					$this->db->order_by("admitcard_id", "desc");
					$this->db->limit(1);
					$chk_eligible_2 = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mam_nam_1,mem_type,exm_cd,exm_prd,mem_exam_id,admitcard_id');
					
					if(count($chk_eligible_2) <= 0){
					
					$mysqltime=date("H:i:s");
					$user_data=array(
									'mregnumber_elapplyexam'=>$chk_eligible[0]['mem_mem_no'],
									'name_elapplyexam'=>$chk_eligible[0]['mam_nam_1'],
									'exmcd_elapplyexam'=>$chk_eligible[0]['exm_cd'],
									'exmprd_elapplyexam'=>777,
									'payment_exmprd_elapplyexam'=>$chk_eligible[0]['exm_prd'],
									'memberexamid_elapplyexam'=>$chk_eligible[0]['mem_exam_id'],
									'admitcard_id_elapplyexam'=>$chk_eligible[0]['admitcard_id'],
									'memtype_elapplyexam'=>$chk_eligible[0]['mem_type']
									);
					$this->session->set_userdata($user_data);
					$sess = $this->session->userdata();
					redirect(base_url().'RELApplyexam/examdetails/');
					
					}else{
						$data['error']='<span style="">You already apply for examination</span>';
					}
					
				}
				else
				{
					$data['error']='<span style="">You are not allow to apply for examination</span>';
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
		$this->session->set_userdata('mem_applyexam_captcha', $cap['word']);
		$this->load->view('REL_mem_exam_apply_login',$data);

	}
	

	
	public function examdetails(){
		$exm_cd_arr = array(1002,1003,1004);

					$exm_prd_arr = array(777);

					$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_elapplyexam'));

					$this->db->where_in('exm_prd',$exm_prd_arr);

					$this->db->where_in('exm_cd',$exm_cd_arr);

					$this->db->where('remark',1);

					$this->db->order_by("admitcard_id", "desc");

					$this->db->limit(1);

					$chk_eligible_2_reg= $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mam_nam_1,mem_type,exm_cd,exm_prd,mem_exam_id,admitcard_id');

					if(count($chk_eligible_2_reg) >=1)

					{redirect(base_url().'RELApplyexam/exapplylogin/');}
		
		
		if($this->session->userdata('mregnumber_elapplyexam') == '' ){ 
			redirect(base_url().'RELApplyexam/exapplylogin/');
		}else{
			
			if($this->session->userdata('exmcd_elapplyexam') == 340 || $this->session->userdata('exmcd_elapplyexam') == 3400){
			$update_exam_code = 1002;
		}elseif($this->session->userdata('exmcd_elapplyexam') == 1600 || $this->session->userdata('exmcd_elapplyexam') == 16000){
			$update_exam_code = 1003; // MSME
		}elseif($this->session->userdata('exmcd_elapplyexam') == 177 || $this->session->userdata('exmcd_elapplyexam') == 1770){
			$update_exam_code = 1004;
		}
			
		
		// get venue detail
		
		$where="(exam_code1='".$update_exam_code."' OR exam_code2='".$update_exam_code."' )";
		$this->db->where($where);
		$this->db->group_by('venue_code');
		$venue_detail = $this->master_model->getRecords('venue_master');
		
			$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_elapplyexam'));
			$this->db->where('exm_cd',$this->session->userdata('exmcd_elapplyexam'));
			$this->db->where('exm_prd',$this->session->userdata('exmprd_elapplyexam'));
			$this->db->where('mem_exam_id',$this->session->userdata('memberexamid_elapplyexam'));
			$this->db->where('remark',1);
			$this->db->order_by("admitcard_id", "desc");
			$this->db->limit(1);
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
			
			$this->db->where('regnumber',$this->session->userdata('mregnumber_elapplyexam'));
			$this->db->where('isactive','1');
			$member_info = $this->master_model->getRecords('member_registration','','email,mobile,registrationtype');
			
			$exam_period = 'June-2020';
			
			$this->db->where('exam_code',$update_exam_code);
			$this->db->group_by('exam_code');
			$compulsory_subjects = $this->master_model->getRecords('subject_master');
			
			$this->db->where('exam_code',$update_exam_code);
			$this->db->where('member_category',$member_info[0]['registrationtype']);
			$fee_detail = $this->master_model->getRecords('fee_master');
			
			
			$data = array('admitcard_info'=>$admitcard_info,'exam_name'=>$exam_name,'subject_name'=>$subject_name,'fee_amt'=>$fee_amt,'exam_period1'=>$exam_period,'member_info'=>$member_info,'middle_content'=>'REL_exam_preview','venue_detail'=>$venue_detail,'compulsory_subjects'=>$compulsory_subjects,'center_code'=>$venue_detail[0]['center_code'],'exam_period'=>$compulsory_subjects[0]['exam_period'],'exam_code'=>$update_exam_code,'fee_detail'=>$fee_detail);
			
			$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
		}
	}
	
	public function add_record(){
		
		$exm_cd_arr = array(1002,1003,1004);

					$exm_prd_arr = array(777);

					$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_elapplyexam'));

					$this->db->where_in('exm_prd',$exm_prd_arr);

					$this->db->where_in('exm_cd',$exm_cd_arr);

					$this->db->where('remark',1);

					$this->db->order_by("admitcard_id", "desc");

					$this->db->limit(1);

					$chk_eligible_2_reg= $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mam_nam_1,mem_type,exm_cd,exm_prd,mem_exam_id,admitcard_id');

					if(count($chk_eligible_2_reg) >=1)

					{redirect(base_url().'RELApplyexam/exapplylogin/');}
		
		$update_exam_code = '';
		if($this->session->userdata('mregnumber_elapplyexam') == '' ){ 
			redirect(base_url().'RELApplyexam/exapplylogin/');
			exit;
		}
		
		if($this->session->userdata('exmcd_elapplyexam') == 340 || $this->session->userdata('exmcd_elapplyexam') == 3400){
			$update_exam_code = 1002;
		}elseif($this->session->userdata('exmcd_elapplyexam') == 1600 || $this->session->userdata('exmcd_elapplyexam') == 16000){
			$update_exam_code = 1003; // MSME
		}elseif($this->session->userdata('exmcd_elapplyexam') == 177 || $this->session->userdata('exmcd_elapplyexam') == 1770){
			$update_exam_code = 1004;
		}
		
		$this->session->userdata['examinfo']['update_exam_code']=$update_exam_code;
		
		if(($update_exam_code == 1002 || $update_exam_code == 1004 || $update_exam_code == 1003) && $this->input->post('elearning_flag') == 'N'){
			
			
			//insert new record in member exam table
			$last_memberexam_insert_id = $this->add_memberexam('member_exam','id',$this->session->userdata('memberexamid_elapplyexam'));
			//update member_exam table							
			$memberexam_update_array = array(
												'exam_period'=>777,
												'exam_code'=>$update_exam_code,
												'exam_center_code'=>$this->input->post('selCenterName'),
												'modified_on'=>date('Y-m-d H:i:s'),
												'created_on'=>date('Y-m-d H:i:s')
											);
											
			$this->master_model->updateRecord('member_exam',$memberexam_update_array, array('id'=>$last_memberexam_insert_id));
			
			// insert new record in admit card table
			$last_admitcard_insert_id = $this->add_admitcard('admit_card_details','admitcard_id',$this->session->userdata('admitcard_id_elapplyexam'));
			//update admitcard table
			
			$admitcard_image = $update_exam_code.'_'.$this->session->userdata('exmprd_elapplyexam').'_'.$this->session->userdata('mregnumber_elapplyexam').'.pdf'; 
			
			//get subject code
			$this->db->where('exam_code',$update_exam_code);
			$subject = $this->master_model->getRecords('subject_master','','subject_code');
			$venue_id = $this->input->post('venue['.$subject[0]['subject_code'].']');
			$exam_date = $this->input->post('date['.$subject[0]['subject_code'].']');
			$exam_time = $this->input->post('time['.$subject[0]['subject_code'].']');
			
			$this->db->where('venue_code',$venue_id);
			$venue_name = $this->master_model->getRecords('venue_master','','venue_name,venue_pincode');
			
			
			
			$admitcard_update_array = array( 
												'mem_exam_id'=>$last_memberexam_insert_id,
												'exm_cd'=>$update_exam_code,
												'exm_prd'=>777,
												'center_code'=>$this->input->post('selCenterName'),
												'center_name'=>'Remote Proctored Exam',
												'venueid'=>$venue_id,
												'venue_name'=>$venue_name[0]['venue_name'], 
												'venueadd1'=>'',
												'venueadd2'=>'',
												'venueadd3'=>'',
												'venueadd4'=>'',
												'venpin'=>$venue_name[0]['venue_pincode'],
												'exam_date'=>$exam_date,
												'remark'=>2,
												'admitcard_image'=>'',
												'time'=>$exam_time,
												'seat_identification'=>'',
												'admitcard_image'=>$admitcard_image,
												'modified_on'=>date('Y-m-d H:i:s'),
												'created_on'=>date('Y-m-d H:i:s')
											);
											
			$this->master_model->updateRecord('admit_card_details',$admitcard_update_array, array('admitcard_id'=>$last_admitcard_insert_id));
			
			// code to generate seat number
			$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$last_memberexam_insert_id));
			
			if(count($exam_admicard_details) > 0){
				foreach($exam_admicard_details as $row){
					$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
					if($capacity==0){
						$log_title =" RELapplyexam Capacity full id:".$this->session->userdata('mregnumber_elapplyexam');
						$log_message = serialize($exam_admicard_details);
						$rId = $this->session->userdata('mregnumber_elapplyexam');
						$regNo = $this->session->userdata('mregnumber_elapplyexam');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						redirect(base_url().'RELApplyexam/exapplylogin/');
					}
				}
			}
			
			$this->db->where('id',$last_memberexam_insert_id);
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
							
							$log_title ="RELapplyexam Seat number already allocated id:".$this->session->userdata('mregnumber_elapplyexam');
							$log_message = serialize($exam_admicard_details);
							$rId = $admit_card_details[0]['admitcard_id'];
							$regNo = $this->session->userdata('mregnumber_elapplyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
						}else{
							$log_title =" RELapplyexam Fail user seat allocation id:".$this->session->userdata('mregnumber_elapplyexam');
							$log_message = serialize($exam_admicard_details);
							$rId = $this->session->userdata('mregnumber_elapplyexam');
							$regNo = $this->session->userdata('mregnumber_elapplyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url().'RELApplyexam/exapplylogin/');
						}
					}
				}
			}
			
			// generate admitcard pdf
			$admitcard_pdf=remote_genarate_admitcard($this->session->userdata('mregnumber_elapplyexam'),$update_exam_code,$this->session->userdata('exmprd_elapplyexam'));
			
			if($admitcard_pdf!=''){
				
				$this->db->where('exam_code',$update_exam_code);
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
				
				$admitcard_image = $update_exam_code.'_'.$this->session->userdata('exmprd_elapplyexam').'_'.$this->session->userdata('mregnumber_elapplyexam').'.pdf'; 
				
				$this->master_model->updateRecord('admit_card_details',array('admitcard_image'=>$admitcard_image), array('mem_exam_id'=>$this->session->userdata('memberexamid_elapplyexam')));
				
				
				
				$this->db->where('mem_exam_id',$this->session->userdata('memberexamid_elapplyexam'));
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
				
				
				$exam_period = 'June-2020';
				$data = array('admitcard_info'=>$admitcard_info,'exam_name'=>$exam_name,'subject_name'=>$subject_name,'fee_amt'=>$fee_amt,'exam_period'=>$exam_period,'middle_content'=>'REL_exam_applied_success');
				$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
				
			}
		}
		elseif($update_exam_code == 1003 && $this->input->post('elearning_flag') == 'Y')
		{
			
			
			$this->db->where('exam_code',$update_exam_code);
			$subject = $this->master_model->getRecords('subject_master','','subject_code');
			$venue_id = $this->input->post('venue['.$subject[0]['subject_code'].']');
			$exam_date = $this->input->post('date['.$subject[0]['subject_code'].']');
			$exam_time = $this->input->post('time['.$subject[0]['subject_code'].']');
			
			//insert new record in member exam table
			$last_memberexam_insert_id = $this->add_memberexam('member_exam','id',$this->session->userdata('memberexamid_elapplyexam'));
			
			$user_data = array(
								'member_exam_id'=>$last_memberexam_insert_id,
								'tot_el_fee_amt'=>$this->input->post('tot_el_fee_amt'),
								'center_code'=>$this->input->post('selCenterName'),
								'update_exam_code'=>$update_exam_code,
								'venueid'=>$venue_id,
								'exam_date'=>$exam_date,
								'exam_time'=>$exam_time
								);
								
			$this->session->set_userdata('examinfo',$user_data);
			
			//$this->session->userdata['examinfo']['member_exam_id']=$last_memberexam_insert_id;
			//$this->session->userdata['examinfo']['tot_el_fee_amt']=$this->input->post('tot_el_fee_amt');
			
			
			//update member_exam table							
			$memberexam_update_array = array(
												'exam_code'=>$update_exam_code,
												'exam_period'=>777,
												'exam_center_code'=>$this->input->post('selCenterName'),
												'exam_fee'=>$this->input->post('tot_el_fee_amt'),
												'pay_status'=>2,
												'modified_on'=>date('Y-m-d H:i:s'),
												'created_on'=>date('Y-m-d H:i:s')
											);
											
			$this->master_model->updateRecord('member_exam',$memberexam_update_array, array('id'=>$last_memberexam_insert_id));
			
			$log_title = "RELApplyexam Insert in member exam table  = '".$last_memberexam_insert_id."'";
			$log_message = serialize($memberexam_update_array);
			$rId = $this->session->userdata('mregnumber_elapplyexam');
			$regNo = $this->session->userdata('mregnumber_elapplyexam');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			
			$this->sbi_make_payment();
			
			
		} 
	}
	
	public function sbi_make_payment(){
		
		
		include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		$merchIdVal = $this->config->item('sbi_merchIdVal');
		$AggregatorId = $this->config->item('sbi_AggregatorId');
		$pg_success_url = base_url()."RELApplyexam/sbitranssuccess";
		$pg_fail_url    = base_url()."RELApplyexam/sbitransfail";
		
		$this->db->where('member_no',$this->session->userdata('mregnumber_elapplyexam'));
		$this->db->where('exam_code',$this->session->userdata('exmcd_elapplyexam'));
		$this->db->where('exam_period',$this->session->userdata('payment_exmprd_elapplyexam'));
		$this->db->where('transaction_no !=','');
		$invoice_info = $this->master_model->getRecords('exam_invoice','','invoice_id,pay_txn_id,state_of_center');
		
		//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
		$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('mregnumber_elapplyexam'),'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
		if(count($checkpayment) > 0)
		{
			$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
			 $current_time= date("Y-m-d H:i:s");
			if(strtotime($current_time)<=strtotime($endTime))
			{
				$this->session->set_flashdata('error','Wait your transaction is under process!.');
				redirect(base_url().'RELApplyexam/examdetails');
			}
		}
		
		
		//insert new record in payment transaction table
		$last_payment_insert_id = $this->add_payment('payment_transaction','id',$invoice_info[0]['pay_txn_id']);
		$MerchantOrderNo = sbi_exam_order_id($last_payment_insert_id);
		// payment gateway custom fields -
		
		$yearmonth = '202006';
		$ref4=($this->session->userdata['examinfo']['update_exam_code']).$yearmonth;
		$custom_field = $MerchantOrderNo."^iibfexam^".$this->session->userdata('mregnumber_elapplyexam')."^".$ref4;
		
		//update payment_transaction table												
		$payment_update_array = array(
										'ref_id'=>$this->session->userdata['examinfo']['member_exam_id'],
										'exam_code'=>$this->session->userdata['examinfo']['update_exam_code'],
										'status'=>2,
										'receipt_no'=>$MerchantOrderNo,
										'transaction_no'=>'',
										'transaction_details'=>'',
										'bankcode'=>'',
										'paymode'=>'',
										'amount'=>$this->session->userdata['examinfo']['tot_el_fee_amt'],
										'pg_other_details'=>$custom_field,
										'date'=>date('Y-m-d H:i:s') 
									 );
									 
		$this->master_model->updateRecord('payment_transaction',$payment_update_array, array('id'=>$last_payment_insert_id));
		
		$log_title = "RELApplyexam Insert in payment transaction  table  = '".$last_payment_insert_id."'";
		$log_message = serialize($payment_update_array);
		$rId = $this->session->userdata('mregnumber_elapplyexam');
		$regNo = $this->session->userdata('mregnumber_elapplyexam');
		storedUserActivity($log_title, $log_message, $rId, $regNo);
		
		
		$this->db->where('exam_code',$this->session->userdata['examinfo']['update_exam_code']);
		$this->db->where('member_category',$this->session->userdata('memtype_elapplyexam'));
		$fee_detail = $this->master_model->getRecords('fee_master');
		
		$cgst_amt=$sgst_amt=$cs_total=$cgst_rate=$sgst_rate='';
		$igst_amt=$igst_total=$igst_rate='';
		
		if($invoice_info[0]['state_of_center']=='MAH'){
			$cgst_rate=$this->config->item('cgst_rate');
			$sgst_rate=$this->config->item('sgst_rate');
			$cgst_amt=$fee_detail[0]['elearning_cgst_amt'];
			$sgst_amt=$fee_detail[0]['elearning_sgst_amt'];
			$cs_total=$fee_detail[0]['elearning_cs_amt_total'];
			$tax_type='Intra';
		}else{
			$igst_rate=$this->config->item('igst_rate');
			$igst_amt=$fee_detail[0]['elearning_igst_amt'];
			$igst_total=$fee_detail[0]['elearning_igst_amt_total']; 
			$tax_type='Inter';
		}
		
		
		//inset new record in exam invoice table
		$last_invoice_insert_id = $this->add_examinvoice('exam_invoice','invoice_id',$invoice_info[0]['invoice_id']);
		
		//update exam_invoice table									
		$examinvoice_update_array = array(
											'pay_txn_id'=>$last_payment_insert_id,
											'exam_code'=>$this->session->userdata['examinfo']['update_exam_code'],
											'center_code'=>'997',
											'exam_period' => 777,
											'center_name'=>'Remote Proctored Exam',
											'receipt_no'=>$MerchantOrderNo,
											'invoice_no'=>'',
											'transaction_no'=>'',
											'invoice_image'=>'',
											'fee_amt'=>$fee_detail[0]['elearning_fee_amt'],
											'cgst_rate'=>$cgst_rate,
											'cgst_amt'=>$cgst_amt,
											'sgst_rate'=>$sgst_rate,
											'sgst_amt'=>$sgst_amt,
											'cs_total'=>$cs_total,
											'igst_rate'=>$igst_rate,
											'igst_amt'=>$igst_amt,
											'igst_total'=>$igst_total,
											'modified_on'=>date('Y-m-d H:i:s'),
											'created_on'=>date('Y-m-d H:i:s')
										);
										
		$this->master_model->updateRecord('exam_invoice',$examinvoice_update_array, array('invoice_id'=>$last_invoice_insert_id)); 
		
		$log_title = "RELApplyexam Insert in exam invoice  table  = '".$last_invoice_insert_id."'";
		$log_message = serialize($examinvoice_update_array);
		$rId = $this->session->userdata('mregnumber_elapplyexam');
		$regNo = $this->session->userdata('mregnumber_elapplyexam');
		storedUserActivity($log_title, $log_message, $rId, $regNo);
		
		
		//insert new record in admitcard table
		$last_admitcard_insert_id = $this->add_admitcard('admit_card_details','admitcard_id',$this->session->userdata('admitcard_id_elapplyexam'));
		
		$this->db->where('venue_code',$this->session->userdata['examinfo']['venueid']);
		$venue_name = $this->master_model->getRecords('venue_master','','venue_name,venue_pincode');
		
		//update admitcard table
		$admitcard_update_array = array(
											'mem_exam_id'=>$this->session->userdata['examinfo']['member_exam_id'],
											'exm_cd'=>$this->session->userdata['examinfo']['update_exam_code'],
											'exm_prd'=>777,
											'center_code'=>$this->session->userdata['examinfo']['center_code'],
											'center_name'=>'Remote Proctored Exam',
											'venueid'=>$this->session->userdata['examinfo']['venueid'],
											'venue_name'=>$venue_name[0]['venue_name'], 
											'venueadd1'=>'',
											'venueadd2'=>'',
											'venueadd3'=>'',
											'venueadd4'=>'',
											'venpin'=>$venue_name[0]['venue_pincode'],
											'exam_date'=>$this->session->userdata['examinfo']['exam_date'],
											'time'=>$this->session->userdata['examinfo']['exam_time'],
											'remark'=>2,
											'seat_identification'=>'',
											'admitcard_image'=>'',
											'modified_on'=>date('Y-m-d H:i:s'),
											'created_on'=>date('Y-m-d H:i:s')
										);
										
		$this->master_model->updateRecord('admit_card_details',$admitcard_update_array, array('admitcard_id'=>$last_admitcard_insert_id));
		
		
		$log_title = "RELApplyexam Insert in admit card table  table  = '".$last_admitcard_insert_id."'";
		$log_message = serialize($admitcard_update_array);
		$rId = $this->session->userdata('mregnumber_elapplyexam');
		$regNo = $this->session->userdata('mregnumber_elapplyexam');
		storedUserActivity($log_title, $log_message, $rId, $regNo);
		
		
		// payment configuration
		 $regno = $this->session->userdata('mregnumber_elapplyexam');
		 $amount=$this->session->userdata['examinfo']['tot_el_fee_amt'];
		
		
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
				
				$log_title = "RELApplyexam update payment table after success  = '".$MerchantOrderNo."'";
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
				$this->db->where('id',$this->session->userdata['examinfo']['member_exam_id']);
				$exam_info=$this->master_model->getRecords('member_exam');
				
				//Generate Admit card
				$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
				
				if(count($exam_admicard_details) > 0){
					foreach($exam_admicard_details as $row){
						$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
						if($capacity==0){
							$log_title =" RELapplyexam Capacity full id:".$this->session->userdata('mregnumber_elapplyexam');
							$log_message = serialize($exam_admicard_details);
							$rId = $this->session->userdata('mregnumber_elapplyexam');
							$regNo = $this->session->userdata('mregnumber_elapplyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url().'RELApplyexam/exapplylogin/');
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
							
							$log_title ="RELapplyexam Seat number allocated id:".$this->session->userdata('mregnumber_elapplyexam');
							$log_message = serialize($exam_admicard_details);
							$rId = $admit_card_details[0]['admitcard_id'];
							$regNo = $this->session->userdata('mregnumber_elapplyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							
						}else{
							$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
							if(count($admit_card_details) > 0){
								
								$log_title ="RELapplyexam Seat number already allocated id:".$this->session->userdata('mregnumber_elapplyexam');
								$log_message = serialize($exam_admicard_details);
								$rId = $admit_card_details[0]['admitcard_id'];
								$regNo = $this->session->userdata('mregnumber_elapplyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}else{
								$log_title =" RELapplyexam Fail user seat allocation id:".$this->session->userdata('mregnumber_elapplyexam');
								$log_message = serialize($exam_admicard_details);
								$rId = $this->session->userdata('mregnumber_elapplyexam');
								$regNo = $this->session->userdata('mregnumber_elapplyexam');
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'RELApplyexam/exapplylogin/');
							}
						}
					}
				}
				
				//generate admitcard pdf	
				$admitcard_pdf=remote_genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
				
					
			  	
				//update member Exam/
				$update_data = array('pay_status' => '1');
				$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
				
				$log_title = "RELApplyexam update member exam pay status  = '".$get_user_regnum[0]['ref_id']."'";
				$log_message = 'Update member exam table '.$get_user_regnum[0]['member_regnumber'];
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
				
				
				$info_arr=array(		//'to'=>$result[0]['email'],
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
						
						$log_title = "RELApplyexam invoice number generation done  = '".$invoiceNumber."'";
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
				
					$log_title = "RELApplyexam invoice image generation done  = '".$invoiceNumber."'";
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
	
	
		$this->db->where('mem_exam_id',$this->session->userdata['examinfo']['member_exam_id']);
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
		
		$exam_period = 'June-2020';
												
		$data_arr = array('admitcard_info'=>$admitcard_info,'exam_name'=>$exam_name,'subject_name'=>$subject_name,'fee_amt'=>$fee_amt,'exam_period'=>$exam_period,'middle_content'=>'REL_exam_applied_success');
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
			redirect(base_url().'RELApplyexam/fail/'.base64_encode($MerchantOrderNo));
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
	
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		redirect(base_url().'RELApplyexam/exapplylogin');
	}
	
	
}
