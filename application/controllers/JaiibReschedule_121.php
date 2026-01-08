<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class JaiibReschedule_121 extends CI_Controller 
	{	
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
			
			$current_date = date('Y-m-d');//'2021-12-06'; //
			if($current_date >= '2021-12-30' && $current_date <= '2021-12-31')
			{
			}
			else
			{
				//echo 'This link will be activate from <strong>04 Dec, 2021</strong> to <strong>06 Dec, 2021</strong>'; exit;
				echo 'Access Denied'; exit;
			}
		}
		
		public function index()
		{
			redirect(site_url('JaiibReschedule_121/login'));
		}		
		
		public function login()//START : LOGIN
		{
			//START : check session already started for user
			$login_type = $this->session->userdata('LOGIN_TYPE');
			$login_regnumber = $this->session->userdata('LOGIN_REGNUMBER');
			
			if(isset($login_type) && $login_type == 'JAIIBRESCHEDULE_121' && isset($login_regnumber) && $login_regnumber != '')
			{
				redirect(site_url('JaiibReschedule_121/dashboard'));
			}
			//END : check session already started for user
			
			$data['error']='';
			$this->chk_session->checklogin_external();
			
			$data=array();
			$data['error']='';
			
			if(isset($_POST) && count($_POST) > 0)
			{  
				$this->form_validation->set_rules('Username', 'Username', 'trim|required|callback_validate_member_no[1]|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('code', 'Code', 'trim|required|callback_validate_captcha_code[1]|xss_clean',array('required' => 'Please enter the %s'));
				
				if($this->form_validation->run() == TRUE)
				{
					$member_no = $this->security->xss_clean(trim($this->input->post('Username')));
					$user_info = $this->get_member_details($member_no);
					
					$mysqltime=date("H:i:s");
					$user_data=array('LOGIN_REGNUMBER'=>$user_info[0]['regnumber'], 'LOGIN_TYPE'=>'JAIIBRESCHEDULE_121');
					$this->session->set_userdata($user_data);
					
					redirect(site_url('JaiibReschedule_121/dashboard'));
				} 
			}
			
			$this->load->model('Captcha_model');                 
			$captcha_img = $this->Captcha_model->generate_captcha_img('JAIIBRESCHEDULE_121_CAPTCHA');
			$data['image'] = $captcha_img;
			$this->load->view('jaiib_reschedule_121/login',$data);
		}//END : LOGIN
		
		public function validate_member_no($str="", $type="0")//START : MEMBER NUMBER VALIDATION FOR SERVER SIDE AND CLIENT SIDE // type = 0 => Ajax, 1=>Server
		{
			$error_msg = 'Invalid request';
			$flag = 'error';
			$return_val = FALSE;
			
			if(isset($_POST) && count($_POST) > 0)
			{
				$member_no = $this->security->xss_clean(trim($this->input->post('Username')));
				$user_info = $this->get_member_details($member_no);			
				
				if(!empty($user_info) && count($user_info) > 0)
				{
					$chk_member_eligibilty = $this->chk_member_eligibilty($member_no);
					
					if(count($chk_member_eligibilty) > 0)
					{
						$flag = 'success';
						$error_msg = "";
						$return_val = TRUE;
					}
					else
					{
						$error_msg = "Membership No. is not eligible";
					}
				}
				else
				{						
					$error_msg = "Invalid Membership No.";
				}							
			} 
			
			if($type == '0') 
			{ 
				$result['flag'] = $flag;
				$result['response'] = $error_msg;
				echo json_encode($result);
			} 
			else if($type == '1') 
			{ 
				$this->form_validation->set_message('validate_member_no', $error_msg); 
				return $return_val;  
			}
		}//END : MEMBER NUMBER VALIDATION FOR SERVER SIDE AND CLIENT SIDE		
		
		function get_member_details($member_no=0)//START : GET MEMBER DETAILS 
		{
			$this->db->where("(registrationtype='O' OR registrationtype='A' OR registrationtype='F')");
			$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $member_no,
			'isactive'=>'1', 'isdeleted'=>'0'), 'registrationtype, regid, regnumber, namesub, firstname, middlename, lastname, email, mobile, createdon, registrationtype, isactive, usrpassword');
			return $user_info;
		}//END : GET MEMBER DETAILS
		
		function get_member_success_exam_details($member_no=0)//START : GET MEMBER SUCCESS EXAM DETAILS 
		{
			$this->db->join('payment_transaction pt', 'pt.ref_id = me.id', 'INNER');
			$exam_info = $this->master_model->getRecords('member_exam me',array('me.regnumber'=> $member_no,
			'me.exam_code'=>'21', 'me.exam_period'=>'121', 'me.pay_status'=>'1', 'pt.exam_code'=>'21', 'pt.member_regnumber'=>$member_no, 'pt.status'=>'1'), 'me.id, me.regnumber, me.exam_fee, me.elearning_flag, me.free_paid_flg, me.sub_el_count, pt.id as PtId, pt.amount, pt.date, pt.transaction_no, pt.receipt_no');
			return $exam_info;
		}//END : GET MEMBER SUCCESS EXAM DETAILS 
		
		function chk_member_eligibilty($member_no=0)//START : CHECK MEMBER IN ELIGIBLE
		{
			$this->db->where('member_no','510323886');
			$this->db->where_in('exam_code', array(60));
			return $this->master_model->getRecords('eligible_master_JaiibReschedule_121',array('member_no'=> $member_no, 'eligible_period'=>'121'), '*');
		}//END : CHECK MEMBER IN ELIGIBLE
		
		public function generatecaptchaajax()//START : GENERATE CAPTCHA CODE AJAX
		{
			$this->load->model('Captcha_model');                 
			echo $captcha_img = $this->Captcha_model->generate_captcha_img('JAIIBRESCHEDULE_121_CAPTCHA');
		}//END : GENERATE CAPTCHA CODE AJAX	
		
		public function validate_captcha_code($code_str="", $type="0")//START : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE // 0 => Ajax, 1=>Server
		{
			$session_name = 'JAIIBRESCHEDULE_121_CAPTCHA';
			$session_captcha = $_SESSION[$session_name];		
			
			if(isset($_POST) && count($_POST) > 0)
			{
				$captcha_code = $this->security->xss_clean(trim($this->input->post('code')));
				
				if($captcha_code == $session_captcha) 
				{ 
					if($type == '0') { echo "true"; } else if($type == '1') { return TRUE; } 
				} 
				else 
				{ 
					if($type == '0') { echo "false"; } 
					else if($type == '1') 
					{ 
						$this->form_validation->set_message('validate_captcha_code', 'Please enter correct code'); return FALSE;  
					} 
				}				
			} 
			else 
			{ 
				if($type == '0') { echo "false"; } 
				else if($type == '1') 
				{ 
					$this->form_validation->set_message('validate_captcha_code', 'Please enter correct code'); return FALSE;  
				} 
			}
		}//END : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE	
		
		public function dashboard()//START : DASHBOARD
		{	
			$login_regnumber = $this->session->userdata('LOGIN_REGNUMBER');
			
			$member_data = $this->check_user_validity_after_login();
			$member_success_exam_data = $this->get_member_success_exam_details($login_regnumber);
			$exam_data = $this->get_member_exam_chargeback_details($login_regnumber);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			$mem_exam_id=0;
			if(count($exam_data) > 0) { $mem_exam_id = $exam_data[0]['id']; }
			$elearning_data = $this->get_member_elearning_details($login_regnumber, $mem_exam_id);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			$member_eligibilty_data = $this->chk_member_eligibilty($login_regnumber);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			$member_exam_invoice_data = $this->get_member_exam_invoice_details($exam_data[0]['receipt_no']);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			if($exam_data[0]['elearning_flag'] == 'N')
			{
				$el_subject_cnt = 0;
			}
			else
			{
				$el_subject_cnt = $exam_data[0]['sub_el_count'];
			}
			//echo '<br>el_subject_cnt : '.$el_subject_cnt;
			
			$amount = $this->getExamFee($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']);
			
			//echo '<pre>'; print_r($amount); echo '</pre>';
			
			if(isset($exam_data[0]['elearning_flag']) && $exam_data[0]['sub_el_count'] > 0 &&($exam_data[0]['exam_code'] == 21 || $exam_data[0]['exam_code'] == 42 || $exam_data[0]['exam_code'] ==992 || $exam_data[0]['exam_code'] == $this->config->item('examCodeCaiib') || $exam_data[0]['exam_code'] == 65))
			{
				$el_amount = $this->get_el_ExamFee($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']);
				//echo '<pre>'; print_r($el_amount); echo '</pre>';
				
				$total_elearning_amt = $el_amount * $el_subject_cnt;
				$amount = $amount + $total_elearning_amt;
			}
			//echo '<pre>'; print_r($amount); echo '</pre>';
			
			$data['member_data'] = $member_data;
			$data['exam_data'] = $exam_data;
			$data['elearning_data'] = $elearning_data;
			$data['disp_amount'] = $amount;
			$data['member_success_exam_data'] = $member_success_exam_data;
			$data['middle_content'] = 'jaiib_reschedule_121/dashboard';
			$this->load->view('jaiib_reschedule_121/common_view',$data);
		}//END : DASHBOARD
		
		public function check_user_validity_after_login()//START : CHECK MEMBER VALIDATION AFTER LOGIN
		{
			$login_type = $this->session->userdata('LOGIN_TYPE');
			$login_regnumber = $this->session->userdata('LOGIN_REGNUMBER');
			
			if(isset($login_type) && $login_type == 'JAIIBRESCHEDULE_121' && isset($login_regnumber) && $login_regnumber != '')
			{
				$user_info = $this->get_member_details($login_regnumber);
				if(!empty($user_info) && count($user_info) > 0)
				{
					$chk_member_eligibilty = $this->chk_member_eligibilty($login_regnumber);
					
					if(count($chk_member_eligibilty) > 0)
					{ 
						return $user_info;
					}
					else
					{
						redirect(site_url('JaiibReschedule_121/logout'));
					}
				}
				else
				{
					redirect(site_url('JaiibReschedule_121/logout'));
				}
			}
			else
			{
				redirect(site_url('JaiibReschedule_121/logout'));
			}
		}//END : CHECK MEMBER VALIDATION AFTER LOGIN
		
		function get_member_exam_chargeback_details($member_no=0)//START : GET MEMBER EXAM DETAILS 
		{
			$this->db->where_in('me.exam_code', array(60));
			$this->db->group_by('me.id');
			$this->db->join('exam_master em', 'em.exam_code = me.exam_code', 'LEFT');
			$this->db->join('payment_transaction pt', 'pt.ref_id = me.id', 'INNER');
			$exam_data = $this->master_model->getRecords('member_exam me',array('me.regnumber'=> $member_no,
			'me.pay_status'=>'0', 'pt.status'=>'6', 'me.exam_period'=>'121', 'me.free_paid_flg'=>'P'), 'me.id, me.regnumber, me.member_type, me.app_category, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_center_code, me.exam_fee, me.elected_sub_code, me.place_of_work, me.state_place_of_work, me.pin_code_place_of_work, me.scribe_flag, me.scribe_flag_PwBD, me.disability, me.sub_disability, me.pay_status, me.elearning_flag, me.free_paid_flg, me.sub_el_count, em.description, pt.id AS PtId, pt.amount, pt.receipt_no, pt.pg_other_details');
			//echo $this->db->last_query();
			return $exam_data;
		}//END : GET MEMBER EXAM DETAILS 
		
		function get_member_elearning_details($member_no=0, $mem_exam_id=0)//START : GET MEMBER ELEARNING DETAILS 
		{
			$elearning_data = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=> $member_no, 'mem_exam_id'=> $mem_exam_id, 'sub_el_flg'=>'Y'), 'admitcard_id, center_name, exm_cd, exm_prd, sub_cd, sub_dsc, sub_el_flg');
			return $elearning_data;
		}//END : GET MEMBER ELEARNING DETAILS 
		
		function get_member_exam_invoice_details($receipt_no=0)//START : GET MEMBER EXAM INVOICE DETAILS 
		{
			$exam_invoice_data = $this->master_model->getRecords('exam_invoice',array('receipt_no'=> $receipt_no), '*');
			return $exam_invoice_data;
		}//END : GET MEMBER EXAM INVOICE DETAILS  
		
		public function logout()//START : LOGOUT
		{
			$user_data=array('LOGIN_REGNUMBER'=>'', 'LOGIN_TYPE'=>'');
			$this->session->set_userdata($user_data);
			redirect(site_url('JaiibReschedule_121/login'));
		}//END : LOGOUT
		
		function add_record()
		{
			$member_success_exam_data = $this->get_member_success_exam_details($login_regnumber);
			if(count($member_success_exam_data) > 0)
			{
				redirect(site_url('JaiibReschedule_121/dashboard')); 
			}
			
			$login_regnumber = $this->session->userdata('LOGIN_REGNUMBER');		
			$member_data = $this->check_user_validity_after_login();
			$exam_data = $this->get_member_exam_chargeback_details($login_regnumber);
			if(count($exam_data) <= 0) { redirect(site_url('JaiibReschedule_121/dashboard')); }
			
			$member_eligibilty_data = $this->chk_member_eligibilty($login_regnumber);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			$member_exam_invoice_data = $this->get_member_exam_invoice_details($exam_data[0]['receipt_no']);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			/* echo '<pre>Posted Date : '; print_r($_POST); echo '</pre>';
				echo '<pre>member_data : '; print_r($member_data); echo '</pre>';
			echo '<pre>exam_data : '; print_r($exam_data); echo '</pre>'; */
			
			if (isset($_POST['JaiibReschedule_121']))
			{
				if($exam_data[0]['elearning_flag'] == 'N')
				{
					$el_subject_cnt = 0;
				}
				else
				{
					$el_subject_cnt = $exam_data[0]['sub_el_count'];
				}
				//echo '<br>el_subject_cnt : '.$el_subject_cnt;
				
				/* $amount = $this->getExamFee($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']); */
				
				//echo '<pre>'; print_r($amount); echo '</pre>';
				
				/* if(isset($exam_data[0]['elearning_flag']) && $exam_data[0]['sub_el_count'] > 0 &&($exam_data[0]['exam_code'] == 21 || $exam_data[0]['exam_code'] == 42 || $exam_data[0]['exam_code'] ==992 || $exam_data[0]['exam_code'] == 60 || $exam_data[0]['exam_code'] == 65))
				{
					$el_amount = $this->get_el_ExamFee($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']);
					//echo '<pre>'; print_r($el_amount); echo '</pre>';
					
					$total_elearning_amt = $el_amount * $el_subject_cnt;
					$amount = $amount + $total_elearning_amt;
				} */
				//echo '<pre>'; print_r($amount); echo '</pre>';
				
				$amount = $exam_data[0]['amount'];
				
				$add_data['regnumber'] = $exam_data[0]['regnumber'];
				$add_data['exam_code'] = $exam_data[0]['exam_code'];
				$add_data['exam_mode'] = $exam_data[0]['exam_mode'];
				$add_data['exam_medium'] = $exam_data[0]['exam_medium'];
				$add_data['exam_period'] = $exam_data[0]['exam_period'];
				$add_data['exam_center_code'] = $exam_data[0]['exam_center_code'];
				$add_data['exam_fee'] = $amount;
				$add_data['elected_sub_code'] = $exam_data[0]['elected_sub_code'];
				$add_data['place_of_work'] = $exam_data[0]['place_of_work'];
				$add_data['state_place_of_work'] = $exam_data[0]['state_place_of_work'];
				$add_data['pin_code_place_of_work'] = $exam_data[0]['pin_code_place_of_work'];
				$add_data['scribe_flag'] = $exam_data[0]['scribe_flag'];
				$add_data['scribe_flag_PwBD'] = $exam_data[0]['scribe_flag_PwBD'];
				$add_data['disability'] = $exam_data[0]['disability'];
				$add_data['sub_disability'] = $exam_data[0]['sub_disability'];
				$add_data['created_on'] = date('y-m-d H:i:s');
				$add_data['elearning_flag'] = $exam_data[0]['elearning_flag'];
				$add_data['sub_el_count'] = $exam_data[0]['sub_el_count'];
				
				if($inser_id = $this->master_model->insertRecord('member_exam', $add_data, true))
				{
					//$this->session->userdata['examinfo']['insdet_id'] = $inser_id;
					$this->session->userdata['MEMBER_EXAM_ID'] = $inser_id; 
					
					/* User Log Activities : Bhushan */
					$log_title ="Member exam apply details - Insert JaiibReschedule_121.php";
					$log_message = serialize($inser_array);
					$rId = $member_data[0]['regid'];
					$regNo = $member_data[0]['regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
					
					if($this->config->item('exam_apply_gateway') == 'sbi')
					{
						redirect(site_url('JaiibReschedule_121/sbi_make_payment'));
					}
					else
					{
						redirect(site_url('JaiibReschedule_121/dashboard'));
					}
				}
			}
			else
			{
				redirect(site_url('JaiibReschedule_121/dashboard'));
			}
		}
		
		public function sbi_make_payment()
		{
			$member_success_exam_data = $this->get_member_success_exam_details($login_regnumber);
			if(count($member_success_exam_data) > 0)
			{
				redirect(site_url('JaiibReschedule_121/dashboard')); 
			}
			
			$login_regnumber = $this->session->userdata('LOGIN_REGNUMBER');		
			$member_data = $this->check_user_validity_after_login();
			$exam_data = $this->get_member_exam_chargeback_details($login_regnumber);
			if(count($exam_data) <= 0) { redirect(site_url('JaiibReschedule_121/dashboard')); }
			
			$member_eligibilty_data = $this->chk_member_eligibilty($login_regnumber);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			$member_exam_invoice_data = $this->get_member_exam_invoice_details($exam_data[0]['receipt_no']);
			//echo '<br>'.$this->db->last_query(); //exit;
			
			$cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
			$cgst_amt = $sgst_amt = $igst_amt = '';
			$cs_total = $igst_total = '';
			$total_el_amount = 0;
			$el_subject_cnt = 0;
			$total_elearning_amt = 0;
			## New elarning columns code
			$total_el_base_amount = 0;
			$total_el_gst_amount = 0;
			$total_el_cgst_amount = 0;
			$total_el_sgst_amount = 0;
			$total_el_igst_amount = 0;
			$getstate = $getcenter = $getfees = array();
			
			/* echo '<pre>member_data : '; print_r($member_data); echo '</pre>';
			echo '<pre>exam_data : '; print_r($exam_data); echo '</pre>'; */
			
			/* $valcookie = applyexam_get_cookie();
				if ($valcookie)
				{
				redirect(base_url() , 'JaiibReschedule_121/dashboard/');
			} */
			
			if (isset($_POST['processPayment']) && $_POST['processPayment'])
			{
				//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$login_regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
				if(count($checkpayment) > 0)
				{
					$endTime = date("Y-m-d H:i:s",strtotime("+120 minutes",strtotime($checkpayment[0]['date'])));
					$current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$this->session->set_flashdata('error','Your transactions is in process, please try after 2 hrs after your initial transaction.');
						redirect(site_url('JaiibReschedule_121/dashboard'));
					}
				}			
				
				$regno = $login_regnumber;
				include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$merchIdVal = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				$pg_success_url = site_url("JaiibReschedule_121/sbitranssuccess");
				$pg_fail_url = site_url("JaiibReschedule_121/sbitransfail");
				
				//echo 'in'; exit;
				
				if($exam_data[0]['elearning_flag'] == 'N')
				{
					$el_subject_cnt = 0;
				}
				else
				{
					$el_subject_cnt = $exam_data[0]['sub_el_count'];
				}
				
				if ($this->config->item('sb_test_mode'))
				{
					$amount = $this->config->item('exam_apply_fee');
				}
				else
				{
					// $amount = $this->getExamFee($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']);
					
					$amount = $exam_data[0]['amount'];
					
					if(isset($exam_data[0]['elearning_flag']) && $exam_data[0]['sub_el_count'] > 0 &&($exam_data[0]['exam_code'] == 21 || $exam_data[0]['exam_code'] == 42 || $exam_data[0]['exam_code'] ==992 || $exam_data[0]['exam_code'] == $this->config->item('examCodeCaiib') || $exam_data[0]['exam_code'] == 65))
					{ 	
						/* $el_amount = $this->get_el_ExamFee($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']); */
						
						$el_amount = '88.5';
						
						$total_elearning_amt = $el_amount * $el_subject_cnt;
						//$amount = $amount + $total_elearning_amt;
						## New elarning columns code
						$total_el_base_amount = $el_subject_cnt;
						$total_el_cgst_amount = $el_subject_cnt;
						$total_el_sgst_amount = $el_subject_cnt;
						$total_el_igst_amount = $el_subject_cnt;			 	 
					}
				}
				
				if ($amount == 0 || $amount == '')
				{
					$this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
					redirect(site_url('JaiibReschedule_121/dashboard'));
				}
				
				// $MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
				// Ordinary member Apply exam
				//	Ref1 = orderid
				//	Ref2 = iibfexam
				//	Ref3 = member reg num
				//	Ref4 = exam_code + exam year + exam month ex (101201602)
				$exam_code = $exam_data[0]['exam_code'];
				
				$pg_other_details_explod = explode("^",$exam_data[0]['pg_other_details']);
				//$ref4 = ($exam_code) . $yearmonth[0]['exam_month'];
				$ref4 = ($exam_code) . $pg_other_details_explod[(count($pg_other_details_explod)-1)];
				
				// Create transaction
				$add_data['member_regnumber'] = $login_regnumber;
				$add_data['amount'] = $amount;
				$add_data['gateway'] = "sbiepay";
				$add_data['date'] = date('Y-m-d H:i:s');
				$add_data['pay_type'] = '2';
				$add_data['ref_id'] = $this->session->userdata['MEMBER_EXAM_ID'];
				$add_data['description'] = $exam_data[0]['description'];
				$add_data['status'] = '2';
				$add_data['exam_code'] = $exam_data[0]['exam_code'];
				$add_data['pg_flag'] = "IIBF_EXAM_O";
				$pt_id = $this->master_model->insertRecord('payment_transaction', $add_data, true);
				
				$MerchantOrderNo = sbi_exam_order_id($pt_id);
				// payment gateway custom fields -
				$custom_field = $MerchantOrderNo . "^iibfexam^" . $login_regnumber . "^" . $ref4;
				
				// update receipt no. in payment transaction -
				$update_data['receipt_no']= $MerchantOrderNo;
				$update_data['pg_other_details'] = $custom_field;
				$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));	
				
				// get state code,state name,state number.
				$getstate = $this->master_model->getRecords('state_master', array(
				'state_code' => $member_exam_invoice_data[0]['state_of_center'], 
				'state_delete' => '0'
				));
				
				// call to helper (fee_helper)
				
				/* $getfees = $this->getExamFeedetails($exam_data[0]['exam_center_code'], $exam_data[0]['exam_period'], base64_encode($exam_data[0]['exam_code']), $member_eligibilty_data[0]['app_category'], $member_eligibilty_data[0]['member_type'], $exam_data[0]['elearning_flag'], $el_subject_cnt, $member_exam_invoice_data[0]['state_of_center']);
				//echo '<pre>'; print_r($getfees); echo '</pre>'; exit;
			
				if ($member_exam_invoice_data[0]['state_of_center'] == 'MAH')
				{
					// set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					if($exam_data[0]['elearning_flag'] == 'Y')
					{ 
						if(isset($el_subject_cnt) && $el_subject_cnt > 0 &&($exam_data[0]['exam_code'] == 21 || $exam_data[0]['exam_code'] == 42 || $exam_data[0]['exam_code'] ==992 || $exam_data[0]['exam_code'] == 60 || $exam_data[0]['exam_code'] == 65))
						{
							$cs_total=$amount;
							$total_el_amount = $total_elearning_amt;
							$amount_base = $getfees[0]['fee_amount'];
							$cgst_amt=$getfees[0]['cgst_amt'];
							$sgst_amt=$getfees[0]['sgst_amt'];
							## New elarning columns code				 
							$total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
							$total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
							$total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
							$total_el_gst_amount = $total_el_cgst_amount + $total_el_sgst_amount;
						}
						else
						{
							$cs_total=$getfees[0]['elearning_cs_amt_total'];
							$total_el_amount = 0;
							$amount_base = $getfees[0]['elearning_fee_amt'];
							
							$cgst_amt=$getfees[0]['elearning_cgst_amt'];
							$sgst_amt=$getfees[0]['elearning_sgst_amt'];
							$total_el_base_amount = 0;
							$total_el_gst_amount = 0;
						}
					}
					else
					{
						//set an amount as per rate
						$cgst_amt=$getfees[0]['cgst_amt'];
						$sgst_amt=$getfees[0]['sgst_amt'];
						//set an total amount
						$cs_total=$getfees[0]['cs_tot'];
						$amount_base = $getfees[0]['fee_amount'];
						$total_el_base_amount = 0;
						$total_el_gst_amount = 0;
					}
					$tax_type = 'Intra';
				}
				else
				{
					if($exam_data[0]['elearning_flag'] == 'Y')
					{						
						$igst_rate=$this->config->item('igst_rate');						
						
						if(isset($el_subject_cnt) && $el_subject_cnt > 0 &&($exam_data[0]['exam_code'] == 21 || $exam_data[0]['exam_code'] == 42 || $exam_data[0]['exam_code'] ==992 || $exam_data[0]['exam_code'] == 60 || $exam_data[0]['exam_code'] == 65))
						{
							$igst_total=$amount;
							$total_el_amount = $total_elearning_amt;
							$amount_base = $getfees[0]['fee_amount'];
							$igst_amt=$getfees[0]['igst_amt'];
							## New elarning columns code
							$total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
							$total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
							$total_el_gst_amount = $total_el_igst_amount;							 
						}
						else
						{
							$igst_total=$getfees[0]['elearning_igst_amt_total'];
							$total_el_amount = 0; 
							$amount_base = $getfees[0]['elearning_fee_amt'];
							$igst_amt=$getfees[0]['elearning_igst_amt'];
							$total_el_base_amount = 0;
							$total_el_gst_amount = 0;
						}
					}
					else
					{ 
						$igst_rate=$this->config->item('igst_rate');
						$igst_amt=$getfees[0]['igst_amt'];
						$igst_total=$getfees[0]['igst_tot'];
						$amount_base = $getfees[0]['fee_amount'];
						## Code added on 22 Oct 2021 - chaitali
						$cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $cs_total = '';
						$total_el_base_amount = 0;
						$total_el_gst_amount = 0;
					}
					$tax_type = 'Inter';
				} */
				
				if ($getstate[0]['exempt'] == 'E')
				{
					$cgst_rate = $sgst_rate = $igst_rate = '';
					$cgst_amt = $sgst_amt = $igst_amt = '';
				}
				
				$gst_no='0';
				/*if($this->session->userdata['examinfo']['gstin_no']!='')
					{
					$gst_no=$this->session->userdata['examinfo']['gstin_no'];
				}*/
				## Code added on 22 Oct 2021	- chaitali
				
				$exam_invoice_data = $this->master_model->getRecords('exam_invoice',array('receipt_no'=> $exam_data[0]['receipt_no']), '*');			
				
				$fee_details = array('state'=>$member_exam_invoice_data[0]['state_of_center'],'fee_amt'=>$amount_base,
				'total_el_amount'=>$exam_invoice_data[0]['total_el_amount'], /* $total_el_amount, */
				'cgst_rate'=>$exam_invoice_data[0]['cgst_rate'], /* $cgst_rate, */
				'cgst_amt'=>$exam_invoice_data[0]['cgst_amt'], /* $cgst_amt, */
				'sgst_rate'=>$exam_invoice_data[0]['sgst_rate'], /* $sgst_rate, */
				'sgst_amt'=>$exam_invoice_data[0]['sgst_amt'], /* $sgst_amt, */
				'igst_rate'=>$exam_invoice_data[0]['igst_rate'], /* $igst_rate, */
				'igst_amt'=>$exam_invoice_data[0]['igst_amt'], /* $igst_amt, */
				'cs_total'=>$exam_invoice_data[0]['cs_total'], /* $cs_total, */
				'igst_total'=>$exam_invoice_data[0]['igst_total'] /* $igst_total */);
				$log_title = "Exam invoice data from applyexam cntrlr before insert array";
				$log_message = serialize($fee_details);
				$rId = $this->session->userdata('regnumber');
				$regNo = $this->session->userdata('regnumber');
				storedUserActivity($log_title, $log_message, $rId, $regNo);				
				
				$add_invoice['pay_txn_id'] = $pt_id;
				$add_invoice['receipt_no'] = $MerchantOrderNo;
				$add_invoice['exam_code'] = $exam_invoice_data[0]['exam_code'];
				$add_invoice['center_code'] = $exam_invoice_data[0]['center_code'];
				$add_invoice['center_name'] = $exam_invoice_data[0]['center_name'];
				$add_invoice['state_of_center'] = $exam_invoice_data[0]['state_of_center'];
				$add_invoice['member_no'] = $exam_invoice_data[0]['member_no'];
				$add_invoice['app_type'] = 'O';
				$add_invoice['exam_period'] = $exam_invoice_data[0]['exam_period'];
				$add_invoice['service_code'] = $exam_invoice_data[0]['service_code'];
				$add_invoice['qty'] = '1';
				$add_invoice['state_code'] = $exam_invoice_data[0]['state_code'];
				$add_invoice['state_name'] = $exam_invoice_data[0]['state_name'];
				$add_invoice['tax_type'] = $exam_invoice_data[0]['tax_type']; //$tax_type;
				$add_invoice['fee_amt'] = $exam_invoice_data[0]['fee_amt']; //$amount_base;
				$add_invoice['total_el_amount'] = $exam_invoice_data[0]['total_el_amount']; //$total_el_amount;
				$add_invoice['total_el_base_amount'] = $exam_invoice_data[0]['total_el_base_amount']; //$total_el_base_amount;
				$add_invoice['total_el_gst_amount'] = $exam_invoice_data[0]['total_el_gst_amount']; //$total_el_gst_amount;
				$add_invoice['cgst_rate'] = $exam_invoice_data[0]['cgst_rate']; //$cgst_rate;
				$add_invoice['cgst_amt'] = $exam_invoice_data[0]['cgst_amt']; //$cgst_amt;
				$add_invoice['sgst_rate'] = $exam_invoice_data[0]['sgst_rate']; //$sgst_rate;
				$add_invoice['sgst_amt'] = $exam_invoice_data[0]['sgst_amt']; //$sgst_amt;
				$add_invoice['igst_rate'] = $exam_invoice_data[0]['igst_rate']; //$igst_rate;
				$add_invoice['igst_amt'] = $exam_invoice_data[0]['igst_amt']; //$igst_amt;
				$add_invoice['cs_total'] = $exam_invoice_data[0]['cs_total']; //$cs_total;
				$add_invoice['igst_total'] = $exam_invoice_data[0]['igst_total']; //$igst_total;
				$add_invoice['exempt'] = $exam_invoice_data[0]['exempt'];
				$add_invoice['created_on'] = date('Y-m-d H:i:s');
				
				$invoice_id = $this->master_model->insertRecord('exam_invoice', $add_invoice,true);
				$log_title = "Exam invoice data from JaiibReschedule_121.php last id invoice_id = '".$invoice_id."'";
				$log_message =  serialize($add_invoice);
				$rId = $login_regnumber;
				$regNo = $login_regnumber;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				$admit_card_data = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=> $login_regnumber, 'mem_exam_id'=> $exam_data[0]['id']), '*');
				
				if(count($admit_card_data) > 0)
				{			
					foreach($admit_card_data as $admit_card_res)
					{
						$add_admitcard['mem_exam_id'] = $this->session->userdata['MEMBER_EXAM_ID'];
						$add_admitcard['center_code'] = $admit_card_res['center_code'];
						$add_admitcard['center_name'] = $admit_card_res['center_name'];
						$add_admitcard['mem_type'] = $admit_card_res['mem_type'];
						$add_admitcard['mem_mem_no'] = $admit_card_res['mem_mem_no'];
						$add_admitcard['g_1'] = $admit_card_res['g_1'];
						$add_admitcard['mam_nam_1'] = $admit_card_res['mam_nam_1'];
						$add_admitcard['mem_adr_1'] = $admit_card_res['mem_adr_1'];
						$add_admitcard['mem_adr_2'] = $admit_card_res['mem_adr_2'];
						$add_admitcard['mem_adr_3'] = $admit_card_res['mem_adr_3'];
						$add_admitcard['mem_adr_4'] = $admit_card_res['mem_adr_4'];
						$add_admitcard['mem_adr_5'] = $admit_card_res['mem_adr_5'];
						$add_admitcard['mem_adr_6'] = $admit_card_res['mem_adr_6'];
						$add_admitcard['mem_pin_cd'] = $admit_card_res['mem_pin_cd'];
						$add_admitcard['state'] = $admit_card_res['state'];
						$add_admitcard['exm_cd'] = $admit_card_res['exm_cd'];
						$add_admitcard['exm_prd'] = $admit_card_res['exm_prd'];
						$add_admitcard['sub_cd'] = $admit_card_res['sub_cd'];
						$add_admitcard['sub_dsc'] = $admit_card_res['sub_dsc'];
						$add_admitcard['sub_el_flg'] = $admit_card_res['sub_el_flg'];
						$add_admitcard['m_1'] = $admit_card_res['m_1'];
						$add_admitcard['inscd'] = $admit_card_res['inscd'];
						$add_admitcard['insname'] = $admit_card_res['insname'];
						$add_admitcard['venueid'] = $admit_card_res['venueid'];
						$add_admitcard['venue_name'] = $admit_card_res['venue_name'];
						$add_admitcard['venueadd1'] = $admit_card_res['venueadd1'];
						$add_admitcard['venueadd2'] = $admit_card_res['venueadd2'];
						$add_admitcard['venueadd3'] = $admit_card_res['venueadd3'];
						$add_admitcard['venueadd4'] = $admit_card_res['venueadd4'];
						$add_admitcard['venueadd5'] = $admit_card_res['venueadd5'];
						$add_admitcard['venpin'] = $admit_card_res['venpin'];
						$add_admitcard['pwd'] = $admit_card_res['pwd'];
						$add_admitcard['exam_date'] = $admit_card_res['exam_date'];
						$add_admitcard['time'] = $admit_card_res['time'];
						$add_admitcard['mode'] = $admit_card_res['mode'];
						$add_admitcard['seat_identification'] = $admit_card_res['seat_identification'];
						$add_admitcard['scribe_flag'] = $admit_card_res['scribe_flag'];
						$add_admitcard['scribe_flag_PwBD'] = $admit_card_res['scribe_flag_PwBD'];
						$add_admitcard['disability'] = $admit_card_res['disability'];
						$add_admitcard['sub_disability'] = $admit_card_res['sub_disability'];
						$add_admitcard['vendor_code'] = $admit_card_res['vendor_code'];
						$add_admitcard['remark'] = 2;
						$add_admitcard['created_on'] = date('Y-m-d H:i:s');
						
						$admit_card_id = $this->master_model->insertRecord('admit_card_details', $add_admitcard, true);
						$log_title = "Admit card data from JaiibReschedule_121.php cntrlr";
						$log_message = serialize($add_admitcard);
						$rId = $login_regnumber;
						$regNo = $login_regnumber;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
					
					##code added to verify if master tables has the raw entries - 2021-10-22 - by chaitali
					$marchant_id = $MerchantOrderNo;
					$exam_code = $exam_data[0]['exam_code'];
					$member_no = $login_regnumber;
					$ref_id = $this->session->userdata['MEMBER_EXAM_ID'];
					
					$payment_raw = $this->master_model->getRecordCount('payment_transaction',array('receipt_no'=>$marchant_id,'exam_code'=>$exam_code,'member_regnumber'=>$member_no));
					
					$exam_invoice_raw = $this->master_model->getRecordCount('exam_invoice',array('receipt_no'=>$marchant_id,'exam_code'=>$exam_code,'member_no'=>$member_no));
					
					$admit_card_raw = $this->master_model->getRecordCount('admit_card_details',array('mem_exam_id'=>$ref_id,'exm_cd'=>$exam_code,'mem_mem_no'=>$member_no));
					
					if($payment_raw == 0 || $exam_invoice_raw == 0 || $admit_card_raw == 0)
					{
						$this->session->set_flashdata('error','Something went wrong!!');
						redirect(site_url('JaiibReschedule_121/dashboard'));
					}
					
					############check for missing subject############
					/* $this->db->where('app_category !=','R');
					$this->db->where('app_category !=','');
					$this->db->where('exam_status !=','V');
					$this->db->where('exam_status !=','P');
					$this->db->where('exam_status !=','D');
					$check_eligibility_for_applied_exam= $this->master_model->getRecords('eligible_master_JaiibReschedule_121',array('eligible_master_JaiibReschedule_121.exam_code'=>$exam_data[0]['exam_code'],'member_no'=>$login_regnumber,'eligible_period'=>121));
					
					if(count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category']=='R')
					{
						if(!empty($this->session->userdata['examinfo']['subject_arr']))
						{
							$count=0;
							foreach($this->session->userdata['examinfo']['subject_arr'] as $k=>$v)
							{
								$check_admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$this->session->userdata('regnumber'),'exm_cd'=>base64_decode($this->session->userdata['examinfo']['excd']),'sub_cd'=>$k,'venueid'=>$v['venue'],'exam_date'=>$v['date'],'time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
								if(count($check_admit_card_details) >0)
								{
									$count++;
								}
							}
						}
						if(count($this->session->userdata['examinfo']['subject_arr'])!=$count)
						{
							$log_title = "Fresh Member subject missing Home cntrlr";
							$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
							$rId = $this->session->userdata('regnumber');
							$regNo = $this->session->userdata('regnumber');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							delete_cookie('examid');
							$this->session->set_flashdata('error','Something went wrong!!');
							redirect(base_url() . 'Home/comApplication');
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
									$check_admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$this->session->userdata('regnumber'),'exm_cd'=>base64_decode($this->session->userdata['examinfo']['excd']),'sub_cd'=>$k,'venueid'=>$v['venue'],'exam_date'=>$v['date'],'time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
									if(count($check_admit_card_details) >0)
									{
										$count++;
									}
								}
							}
						}
						if(count($check_eligibility_for_applied_exam)!=$count)
						{
							$log_title = "Existing Member subject missing  Home cntrlr";
							$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
							$rId = $this->session->userdata('regnumber');
							$regNo = $this->session->userdata('regnumber');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							delete_cookie('examid');
							$this->session->set_flashdata('error','Something went wrong!!');
							redirect(base_url() . 'Home/comApplication');
						}
					}
					 */
					 ############END check for missing subject############
				}
				
				// set cookie for Apply Exam
				applyexam_set_cookie($this->session->userdata['MEMBER_EXAM_ID']);
				$MerchantCustomerID = $regno;
				$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
				$data["merchIdVal"] = $merchIdVal;
				
				$EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$EncryptTrans = $aes->encrypt($EncryptTrans);
				$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				$this->load->view('pg_sbi_form', $data);
			}
			else
			{
				$this->load->view('pg_sbi/make_payment_page');
			}
		}
		
		public function sbitranssuccessXX()
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|", $encData);
			$MerchantOrderNo = $responsedata[0];
			$transaction_no = $responsedata[1];
			$attachpath = $invoiceNumber = $admitcard_pdf = '';
			if(isset($_REQUEST['merchIdVal'])) { $merchIdVal = $_REQUEST['merchIdVal']; }
			
			if(isset($_REQUEST['Bank_Code'])) { $Bank_Code = $_REQUEST['Bank_Code']; }
			
			if(isset($_REQUEST['pushRespData'])) { $encData = $_REQUEST['pushRespData']; }
			$elective_subject_name = '';
			// Sbi B2B callback
			// check sbi payment status with MerchantOrderNo
			$q_details = sbiqueryapi($MerchantOrderNo);
			
			if($q_details)
			{
				if ($q_details[2] == "SUCCESS")
				{
					$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status,date');
					
					if($get_user_regnum[0]['status'] == 2)
					{
						// Query to get user details
						$this->db->join('state_master', 'state_master.state_code=member_registration.state');
						$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
						$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname, middlename, lastname, address1, address2, address3, address4, district, city, email, mobile, office, pincode, state_master.state_name, institution_master.name');
						
						// Query to get exam details
						$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'LEFT');
						$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code', 'LEFT');
						$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period', 'LEFT');
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period', 'LEFT');
						$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code, member_exam.exam_mode, member_exam.exam_medium, member_exam.exam_period, center_master.center_name, member_exam.exam_center_code, exam_master.description, misc_master.exam_month, member_exam.state_place_of_work, member_exam.place_of_work, member_exam.pin_code_place_of_work, member_exam.examination_date, member_exam.elected_sub_code');
						
						if ($exam_info[0]['exam_code'] != 101)
						{
							// ######### Generate Admit card and allocate Seat #############
							$exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $get_user_regnum[0]['ref_id']));
							
							if (count($exam_admicard_details) > 0)
							{
								// ######## payment Transaction ############
								$update_data = array(
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
								'auth_code' => '0300',
								'bankcode' => $responsedata[8],
								'paymode' => $responsedata[5],
								'callback' => 'B2B'
								);
								$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
								
								$query_update_payment_tra=$this->db->last_query();
								$log_title = "JaiibReschedule_121.php ctrl query_update_payment_tra :" . $query_update_payment_tra;
								$log_message = serialize($update_data);
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
								
								$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' =>$MerchantOrderNo), 'member_regnumber,ref_id,status,date');
								
								if($get_payment_status[0]['status']==1)
								{
									if (count($get_user_regnum) > 0)
									{
										$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber, usrpassword, email');
									}									
								}
								else
								{
									redirect(base_url().'JaiibReschedule_121/refund/' . base64_encode($MerchantOrderNo));
								}
							}
					    
							// #####update member_exam######
							$update_data = array('pay_status' => '1');
							
							if($get_payment_status[0]['status']==1)
							{								
								$this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));
								$query_update_member_exam=$this->db->last_query();
								$log_title = "JaiibReschedule_121.php ctrl query_update_member_exam :" . $query_update_member_exam;
								$log_message = serialize($update_data);
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}
							else
							{								
								$log_title = "JaiibReschedule_121.php ctrl query_update_member_exam fail :";
								$log_message = $get_user_regnum[0]['ref_id'];
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);									
							}
							
							if ($exam_info[0]['exam_mode'] == 'ON')
							{
								$mode = 'Online';
							}
							elseif ($exam_info[0]['exam_mode'] == 'OF')
							{
								$mode = 'Offline';
							}
							else
							{
								$mode = '';
							}
							
							/* // $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
								$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
								// Query to get Medium
								$this->db->where('exam_code', $exam_info[0]['exam_code']);
								$this->db->where('exam_period', $exam_info[0]['exam_period']);
								$this->db->where('medium_code', $exam_info[0]['exam_medium']);
								$this->db->where('medium_delete', '0');
								$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
								$this->db->where('state_delete', '0');
								$states = $this->master_model->getRecords('state_master', array(
								'state_code' => $exam_info[0]['state_place_of_work']
								) , 'state_name');
								// Query to get Payment details
								$payment_info = $this->master_model->getRecords('payment_transaction', array(
								'receipt_no' => $MerchantOrderNo,
								'member_regnumber' => $get_user_regnum[0]['member_regnumber']
								) , 'transaction_no,date,amount,id');
								$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username); */
							
							// get invoice
							$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
							'receipt_no' => $MerchantOrderNo,
							'pay_txn_id' => $payment_info[0]['id']
							));
							
							// echo $this->db->last_query();exit;
							if (count($getinvoice_number) > 0)
							{
								$invoiceNumber = '';
								if($get_payment_status[0]['status']==1)
								{
									$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
									$query_invoiceNumber=$this->db->last_query();
									$log_title = "JaiibReschedule_121.php ctrl exam_invoice number generate :" . $getinvoice_number[0]['invoice_id'];
									$log_message = $query_invoiceNumber;
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								else
								{
									$log_title = "JaiibReschedule_121.php exam_invoice number generate fail :" ;
									$log_message = $getinvoice_number[0]['invoice_id'];
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								
								
								if ($invoiceNumber)
								{
									$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
								}
								
								$update_data_invoice = array(
								'invoice_no' => $invoiceNumber,
								'transaction_no' => $transaction_no,
								'date_of_invoice' => date('Y-m-d H:i:s') ,
								'modified_on' => date('Y-m-d H:i:s')
								);
								
								if($get_payment_status[0]['status']==1)
								{									
									$this->db->where('pay_txn_id', $payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data_invoice, array(
									'receipt_no' => $MerchantOrderNo
									));
									
									$attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									
									$log_title = "JaiibReschedule_121.php ctrl exam invoice update :";
									$log_message = '';
									$rId = $MerchantOrderNo;
									$regNo = $MerchantOrderNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								else
								{									
									$log_title = "JaiibReschedule_121.php ctrl exam invoice update fail :";
									$log_message = $getinvoice_number[0]['invoice_id'];
									$rId = $MerchantOrderNo;
									$regNo = $MerchantOrderNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								
								$update_data_me = array('pay_status' => '1');
								$this->master_model->updateRecord('member_exam', $update_data_me, array('id' => $get_user_regnum[0]['ref_id']));								
								
								$query_exam_invoice_generate=$this->db->last_query();
								$log_title = "JaiibReschedule_121.php ctrl exam_invoice :" . $query_exam_invoice_generate;
								$log_message = serialize($attachpath );
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								if ($exam_info[0]['exam_code'] != 101)
								{
									// #############Get Admit card#############
									$admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
									$log_title = "JaiibReschedule_121.php ctrl admitcard_pdf:" . $get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($admitcard_pdf);
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					}
				}
			}
			
			redirect(base_url() . 'JaiibReschedule_121/dashboard');
		}
		
		public function sbitranssuccess()
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|", $encData);
			$MerchantOrderNo = $responsedata[0];
			$transaction_no = $responsedata[1];
			$attachpath = $invoiceNumber = $admitcard_pdf = '';
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
			$elective_subject_name = '';
			// Sbi B2B callback
			// check sbi payment status with MerchantOrderNo
			$q_details = sbiqueryapi($MerchantOrderNo);
			if ($q_details)
			{
				if ($q_details[2] == "SUCCESS")
				{
					$get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
					'receipt_no' => $MerchantOrderNo
					) , 'member_regnumber,ref_id,status,date');
					
					
					if ($get_user_regnum[0]['status'] == 2)
					{						
						// Query to get user details
						$this->db->join('state_master', 'state_master.state_code=member_registration.state');
						$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
						$result = $this->master_model->getRecords('member_registration', array(
						'regnumber' => $get_user_regnum[0]['member_regnumber']
						) , 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,institution_master.name');
						// Query to get exam details
						$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'LEFT');
						$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code', 'LEFT');
						$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period', 'LEFT');
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period', 'LEFT');
						$exam_info = $this->master_model->getRecords('member_exam', array(
						'regnumber' => $get_user_regnum[0]['member_regnumber'],
						'member_exam.id' => $get_user_regnum[0]['ref_id']
						) , 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
						if ($exam_info[0]['exam_code'] != 101)
						{
							// ######### Generate Admit card and allocate Seat #############
							$exam_admicard_details = $this->master_model->getRecords('admit_card_details', array(
							'mem_exam_id' => $get_user_regnum[0]['ref_id']
							));
							// ###########check capacity is full or not ##########
							// $subject_arr=$this->session->userdata['examinfo']['subject_arr'];
							if (count($exam_admicard_details) > 0)
							{
								$msg = '';
								$sub_flag = 1;
								$sub_capacity = 1;
								/* foreach($exam_admicard_details as $row)
								{
									$capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
									if ($capacity == 0)
									{
										// ########get message if capacity is full##########
										$log_title = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
										$log_message = serialize($exam_admicard_details);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
									}
								} */
							}
							
							if (count($exam_admicard_details) > 0)// && $capacity > 0
							{								
								// ######## payment Transaction ############
								$update_data = array(
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
								'auth_code' => '0300',
								'bankcode' => $responsedata[8],
								'paymode' => $responsedata[5],
								'callback' => 'B2B'
								);
								$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array(
								'receipt_no' => $MerchantOrderNo,
								'status' => 2
								));
								
								$query_update_payment_tra=$this->db->last_query();
								$log_title = "JaiibReschedule_121.php ctrl query_update_payment_tra :" . $query_update_payment_tra;
								$log_message = serialize($update_data);
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
								/*  if ($this->db->affected_rows())
								{ */
								$get_payment_status = $this->master_model->getRecords('payment_transaction', array(
								'receipt_no' => $MerchantOrderNo
								) , 'member_regnumber,ref_id,status,date');
								
								if($get_payment_status[0]['status']==1)
								{
									if (count($get_user_regnum) > 0)
									{
										$user_info = $this->master_model->getRecords('member_registration', array(
										'regnumber' => $get_user_regnum[0]['member_regnumber']
										) , 'regnumber,usrpassword,email');
									}
									
									// admit card gen
									$password = random_password();
									/* foreach($exam_admicard_details as $row)
									{
										$get_subject_details = $this->master_model->getRecords('venue_master', array(
										'venue_code' => $row['venueid'],
										'exam_date' => $row['exam_date'],
										'session_time' => $row['time'],
										'center_code'=>$row['center_code']
										));
										$admit_card_details = $this->master_model->getRecords('admit_card_details', array(
										'venueid' => $row['venueid'],
										'exam_date' => $row['exam_date'],
										'time' => $row['time'],
										'mem_exam_id' => $get_user_regnum[0]['ref_id'],
										'sub_cd' => $row['sub_cd']
										));
										// echo $this->db->last_query().'<br />';
										$seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
										if ($seat_number != '')
										{
											$final_seat_number = $seat_number;
											$update_data = array(
											'pwd' => $password,
											'seat_identification' => $final_seat_number,
											'remark' => 1,
											'modified_on' => date('Y-m-d H:i:s')
											);
											$this->master_model->updateRecord('admit_card_details', $update_data, array(
											'admitcard_id' => $admit_card_details[0]['admitcard_id']
											));
										}
										else
										{
											$admit_card_details = $this->master_model->getRecords('admit_card_details', array(
											'admitcard_id' => $admit_card_details[0]['admitcard_id'],
											'remark' => 1
											));
											if (count($admit_card_details) > 0)
											{
												$log_title = "Home Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($exam_admicard_details);
												$rId = $admit_card_details[0]['admitcard_id'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
											}
											else
											{
												$log_title = "Home Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($exam_admicard_details);
												$rId = $get_user_regnum[0]['member_regnumber'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
											}
										}
									} */
								}
								else
								{
									redirect(base_url() . 'JaiibReschedule_121/refund/' . base64_encode($MerchantOrderNo));
								}
							}
							// #####update member_exam######
							$update_data = array(
							'pay_status' => '1'
							);
							if($get_payment_status[0]['status']==1)
							{								
								$this->master_model->updateRecord('member_exam', $update_data, array(
								'id' => $get_user_regnum[0]['ref_id']
								));
								$query_update_member_exam=$this->db->last_query();
								$log_title = "JaiibReschedule_121.php ctrl query_update_member_exam :" . $query_update_member_exam;
								$log_message = serialize($update_data);
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
								
							}
							else
							{								
								$log_title = "JaiibReschedule_121.php ctrl query_update_member_exam fail :";
								$log_message = $get_user_regnum[0]['ref_id'];
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}
							
							if ($exam_info[0]['exam_mode'] == 'ON')
							{
								$mode = 'Online';
							}
							elseif ($exam_info[0]['exam_mode'] == 'OF')
							{
								$mode = 'Offline';
							}
							else
							{
								$mode = '';
							}
							// $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
							$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
							// Query to get Medium
							$this->db->where('exam_code', $exam_info[0]['exam_code']);
							$this->db->where('exam_period', $exam_info[0]['exam_period']);
							$this->db->where('medium_code', $exam_info[0]['exam_medium']);
							$this->db->where('medium_delete', '0');
							$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
							$this->db->where('state_delete', '0');
							$states = $this->master_model->getRecords('state_master', array(
							'state_code' => $exam_info[0]['state_place_of_work']
							) , 'state_name');
							// Query to get Payment details
							$payment_info = $this->master_model->getRecords('payment_transaction', array(
							'receipt_no' => $MerchantOrderNo,
							'member_regnumber' => $get_user_regnum[0]['member_regnumber']
							) , 'transaction_no,date,amount,id');
							$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							// if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
							
							/* if ($exam_info[0]['place_of_work'] != '' && $exam_info[0]['state_place_of_work'] != '' && $exam_info[0]['pin_code_place_of_work'] != '')
							{
								// get Elective Subeject name for CAIIB Exam
								if ($exam_info[0]['elected_sub_code'] != 0 && $exam_info[0]['elected_sub_code'] != '')
								{
									$this->db->group_by('subject_code');
									$elective_sub_name_arr = $this->master_model->getRecords('subject_master', array(
									'subject_code' => $exam_info[0]['elected_sub_code'],
									'subject_delete' => 0
									) , 'subject_description');
									if (count($elective_sub_name_arr) > 0)
									{
										$elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
									}
								}
								$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'member_exam_enrollment_nofee_elective'
								));
								$newstring1 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
								$newstring4 = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
								$newstring5 = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring4);
								$newstring6 = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring5);
								$newstring7 = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring6);
								$newstring8 = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring7);
								$newstring9 = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring8);
								$newstring10 = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring9);
								$newstring11 = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring10);
								$newstring12 = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring11);
								$newstring13 = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring12);
								$newstring14 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring13);
								$newstring15 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring14);
								$newstring16 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring15);
								$newstring17 = str_replace("#ELECTIVE_SUB#", "" . $elective_subject_name . "", $newstring16);
								$newstring18 = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring17);
								$newstring19 = str_replace("#PLACE_OF_WORK#", "" . strtoupper($exam_info[0]['place_of_work']) . "", $newstring18);
								$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "" . $states[0]['state_name'] . "", $newstring19);
								$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "" . $exam_info[0]['pin_code_place_of_work'] . "", $newstring20);
								// $elern_msg_string=array(21,60,62,63,64,65,66,67,68,69,70,71,72,42,58,580,81,5800,34,340,3400,151);
								$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
								if (count($elern_msg_string) > 0)
								{
									foreach($elern_msg_string as $row)
									{
										$arr_elern_msg_string[] = $row['exam_code'];
									}
									if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string))
									{
										$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg') , $newstring21);
									}
									else
									{
										$newstring22 = str_replace("#E-MSG#", '', $newstring21);
									}
								}
								else
								{
									$newstring22 = str_replace("#E-MSG#", '', $newstring21);
								}
								$final_str = str_replace("#MODE#", "" . $mode . "", $newstring22);
							}
							else
							{
								$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'apply_exam_transaction_success'
								));
								$newstring1 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
								$newstring4 = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
								$newstring5 = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
								$newstring6 = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
								$newstring7 = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
								$newstring8 = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
								$newstring9 = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
								$newstring10 = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
								$newstring11 = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
								$newstring12 = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
								$newstring13 = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
								$newstring14 = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
								$newstring15 = str_replace("#INSTITUDE#", "" . $result[0]['name'] . "", $newstring14);
								$newstring16 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring15);
								$newstring17 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring16);
								$newstring18 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring17);
								$newstring19 = str_replace("#MODE#", "" . $mode . "", $newstring18);
								$newstring20 = str_replace("#PLACE_OF_WORK#", "" . $result[0]['office'] . "", $newstring19);
								$newstring21 = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring20);
								// $elern_msg_string=array(21,60,62,63,64,65,66,67,68,69,70,71,72,42,58,580,81,5800,34,340,3400,151);
								$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
								if (count($elern_msg_string) > 0)
								{
									foreach($elern_msg_string as $row)
									{
										$arr_elern_msg_string[] = $row['exam_code'];
									}
									if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string))
									{
										$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg') , $newstring21);
									}
									else
									{
										$newstring22 = str_replace("#E-MSG#", '', $newstring21);
									}
								}
								else
								{
									$newstring22 = str_replace("#E-MSG#", '', $newstring21);
								}
								$final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring22);
							}
							 */
							
							$elearning_data = $this->get_member_elearning_details($get_user_regnum[0]['member_regnumber'], $get_user_regnum[0]['ref_id']);
							
							$elearning_str = '';
							if(count($elearning_data) > 0) 
							{
								$i=1;
								foreach($elearning_data as $elearning_res)
								{
									$elearning_str.= $i.'. '.$elearning_res['sub_dsc'];
									if($i < count($elearning_data)) { $elearning_str.= '<br>'; }
									$i++;
								}
							}
							else
							{
								$elearning_str = '--';
							}
							
							$final_str = '<table style="max-width:600px; width:100%; margin:20px auto; background:#FFFFCC;" cellspacing="5" cellpadding="5" border="1"> 
														<tbody style="">
															<tr style="">
																<td colspan="2" style="" width="100%">
																	<p>Dear '.$userfinalstrname.',<br><br>Your transaction has been success.</p>
																</td>
															</tr>
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Member Number : </strong></p></td>
																<td style="" width="64%">'.$get_user_regnum[0]['member_regnumber'].'</td>
															</tr>
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Member Name : </strong></p></td>
																<td style="" width="64%">'.$userfinalstrname.'</td>
															</tr>
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Exam Name : </strong></p></td>
																<td style="" width="64%">'.$exam_info[0]['description'].'</td>
															</tr>								
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Amount : </strong></p></td>
																<td style="" width="64%">'.$payment_info[0]['amount'].'</td>
															</tr>
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Email Id : </strong></p></td>
																<td style="" width="64%"><span class="Object" role="link"><a href="mailto:'.$result[0]['email'].'" target="_blank" style="">'.$result[0]['email'].'</a></span></td>
															</tr>
															
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Elective Subject Name : </strong></p></td>
																<td style="" width="64%">'.$elearning_str.'</td>
															</tr>
															
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Mode : </strong></p></td>
																<td style="" width="64%">'.$mode.'</td>
															</tr>
															
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Place of Work : </strong></p></td>
																<td style="" width="64%">'.$result[0]['office'].'</td>
															</tr>
															
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">State :<br style="">
																(Place of Work)</strong></p></td>
																<td style="" width="64%">'.$result[0]['state_name'].'</td>
															</tr>
															<tr style="">
																<td style="" width="35%"><p style=""><strong style="">Pin Code :<br style="">
																(Place of Work)</strong></p></td>
																<td style="" width="64%">'.$result[0]['pincode'].'</td>
															</tr>
														</tbody>
													</table>	';
							
							$info_arr = array(
							'to' => $result[0]['email'],
							'bcc'=>'sagar.matale@esds.co.in',
							'from' => $emailerstr[0]['from'],
							'subject' => 'Exam Enrolment Acknowledgement : '.$get_user_regnum[0]['member_regnumber'],
							'message' => $final_str
							);
							// get invoice
							$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
							'receipt_no' => $MerchantOrderNo,
							'pay_txn_id' => $payment_info[0]['id']
							));
							// echo $this->db->last_query();exit;
							if (count($getinvoice_number) > 0)
							{
								$invoiceNumber = '';
								if($get_payment_status[0]['status']==1)// && $capacity > 0
								{
									$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
									$query_invoiceNumber=$this->db->last_query();
									$log_title = "JaiibReschedule_121.php ctrl exam_invoice number generate :" . $getinvoice_number[0]['invoice_id'];
									$log_message = $query_invoiceNumber;
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								else
								{
									$log_title = "JaiibReschedule_121.php ctrl exam_invoice number generate fail :" ;
									$log_message = $getinvoice_number[0]['invoice_id'];
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}								
								
								if ($invoiceNumber)
								{
									$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
								}
								
								$update_data_invoice = array(
								'invoice_no' => $invoiceNumber,
								'transaction_no' => $transaction_no,
								'date_of_invoice' => date('Y-m-d H:i:s') ,
								'modified_on' => date('Y-m-d H:i:s')
								);
								
								if($get_payment_status[0]['status']==1)
								{									
									$this->db->where('pay_txn_id', $payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data_invoice, array(
									'receipt_no' => $MerchantOrderNo
									));
									
									$attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									
									$log_title = "JaiibReschedule_121.php ctrl exam invoice update :";
									$log_message = '';
									$rId = $MerchantOrderNo;
									$regNo = $MerchantOrderNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								else
								{									
									$log_title = "JaiibReschedule_121.php ctrl exam invoice update fail :";
									$log_message = $getinvoice_number[0]['invoice_id'];
									$rId = $MerchantOrderNo;
									$regNo = $MerchantOrderNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								
								$update_data_me = array(
								'pay_status' => '1'
								);
								$this->master_model->updateRecord('member_exam', $update_data_me, array(
								'id' => $get_user_regnum[0]['ref_id']
								));								
								
								$query_exam_invoice_generate=$this->db->last_query();
								$log_title = "JaiibReschedule_121.php exam_invoice :" . $query_exam_invoice_generate;
								$log_message = serialize($attachpath );
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								if ($exam_info[0]['exam_code'] != 101)
								{
									// #############Get Admit card#############
									$admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
									$log_title = "JaiibReschedule_121.php admitcard_pdf:" . $get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($admitcard_pdf);
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
							
							if ($attachpath != '')
							{
								$files = array(
								$attachpath,
								$admitcard_pdf
								);
								$sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
								$sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
								$sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
								$sms_final_str = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
								//$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
								$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'C-48OSQMg',$exam_info[0]['exam_code']);
								$this->Emailsending->mailsend_attch($info_arr, $files);
							}
						}
						// Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					}
					
					
				}
			} //End of check sbi payment status with MerchantOrderNo
			// /End of SBICALL Back
			// Old Code
			
			$get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
			'receipt_no' => $MerchantOrderNo
			) , 'member_regnumber,ref_id');
			// Query to get exam details
			$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info = $this->master_model->getRecords('member_exam', array(
			'regnumber' => $get_user_regnum[0]['member_regnumber'],
			'member_exam.id' => $get_user_regnum[0]['ref_id']
			) , 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			//redirect(base_url() . 'Home/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));
			
			$this->session->set_flashdata('success','Your transactions is successful.');
			redirect(base_url() . 'JaiibReschedule_121/dashboard');
		}
		
		public function sbitransfailxx()
		{
			if (isset($_REQUEST['encData']))
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encData = $aes->decrypt($_REQUEST['encData']);
				$responsedata = explode("|", $encData);
				$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
				$transaction_no = $responsedata[1];
				// SBICALL Back B2B
				$get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
				'receipt_no' => $MerchantOrderNo
				) , 'member_regnumber,ref_id,status');
				if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2)
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
					$update_data = array(
					'transaction_no' => $transaction_no,
					'status' => 0,
					'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
					'auth_code' => 0399,
					'bankcode' => $responsedata[8],
					'paymode' => $responsedata[5],
					'callback' => 'B2B'
					);
					$this->master_model->updateRecord('payment_transaction', $update_data, array(
					'receipt_no' => $MerchantOrderNo
					));
					
					// Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				// End Of SBICALL Back
				// Old Code
				redirect(base_url() . 'JaiibReschedule_121/fail/' . base64_encode($MerchantOrderNo));
			}
			else
			{
				die("Please try again...");
			}
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
				$responsedata = explode("|", $encData);
				$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
				$transaction_no = $responsedata[1];
				// SBICALL Back B2B
				$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo) , 'member_regnumber,ref_id,status');
				
				if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2)
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
					$update_data = array(
					'transaction_no' => $transaction_no,
					'status' => 0,
					'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
					'auth_code' => 0399,
					'bankcode' => $responsedata[8],
					'paymode' => $responsedata[5],
					'callback' => 'B2B'
					);					
					$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
					
					// Query to get Payment details
					$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo,
					'member_regnumber' => $get_user_regnum[0]['member_regnumber']) , 'transaction_no,date,amount');
					
					// Query to get user details
					$this->db->join('state_master', 'state_master.state_code=member_registration.state');
					$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
					$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname, middlename, lastname, address1, address2, address3, address4, district, city, email, mobile, office,	pincode, state_master.state_name, institution_master.name');
					
					// Query to get exam details
					$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'LEFT');
					$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code', 'LEFT');
					$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period', 'LEFT');
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period', 'LEFT');
					$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
					
					//echo $this->db->last_query();
					// $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
					$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
					$username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
					$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
					$newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
					$newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
					$final_str = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
					$info_arr = array(
					'to' => $result[0]['email'],
					'bcc' => 'sagar.matale@esds.co.in',
					'from' => $emailerstr[0]['from'],
					'subject' => $emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
					'message' => $final_str
					);
					// send sms to Ordinary Member
					$sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
					$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg',$exam_info[0]['exam_code']);
					$this->Emailsending->mailsend($info_arr);
					// Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				// End Of SBICALL Back
				// Old Code
				redirect(base_url() . 'JaiibReschedule_121/fail/' . base64_encode($MerchantOrderNo));
			}
			else
			{
				die("Please try again...");
			}
		}
		
		public function fail($order_no = NULL)
		{
			$login_regnumber = $this->session->userdata('LOGIN_REGNUMBER');
			
			// $this->chk_session->checkphoto();
			// payment detail
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no) ,
			'member_regnumber' => $login_regnumber));
			if (count($payment_info) <= 0)
			{
				redirect(base_url('JaiibReschedule_121/dashboard'));
			}
			
			$data = array(
			'middle_content' => 'jaiib_reschedule_121/exam_applied_fail',
			'payment_info' => $payment_info
			);
			$this->load->view('jaiib_reschedule_121/common_view', $data);
		}
		
		public function refund($order_no = NULL)
		{
			// payment detail
			// $this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
			// $this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
			$payment_info = $this->master_model->getRecords('payment_transaction', array(
			'receipt_no' => base64_decode($order_no)
			));
			
			if (count($payment_info) <= 0)
			{
				redirect(base_url('JaiibReschedule_121/dashboard'));
			}
			
			$this->db->where('remark', '2');
			$admit_card_refund = $this->master_model->getRecords('admit_card_details', array(
			'mem_exam_id' => $payment_info[0]['ref_id']
			));
			if (count($admit_card_refund) > 0)
			{
				$update_data = array(
				'remark' => 3
				);
				$this->master_model->updateRecord('admit_card_details', $update_data, array(
				'mem_exam_id' => $payment_info[0]['ref_id']
				));
			}
			$exam_name = $this->master_model->getRecords('exam_master', array(
			'exam_code' => $payment_info[0]['exam_code']
			));
			
			##adding below code for processing the refund process - added by chaitali on 2021-09-17						
			$insert_data = array('receipt_no'=>base64_decode($order_no),'transaction_no'=>$payment_info[0]['transaction_no'],'refund'=>'0','created_on'=>date('Y-m-d'),'email_flag'=>'0','sms_flag'=>'0');				
			$this->master_model->insertRecord('exam_payment_refund',$insert_data);			
			//echo $this->db->last_query(); die;		
			## ended insert code
			
			$data = array(
			'middle_content' => 'jaiib_reschedule_121/member_refund',
			'payment_info' => $payment_info,
			'exam_name' => $exam_name
			);
			$this->load->view('jaiib_reschedule_121/common_view', $data);
		}
		
		function getExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL,$el_subject_cnt=NULL,$state_code=NULL)
		{
			$fee=0;
			$CI = & get_instance();
			//$centerCode= $_POST['centerCode'];
			//$eprid=$_POST['eprid'];
			//	$excd=$_POST['excd'];
			//$grp_code=$_POST['grp_code'];
			
			/* echo '<br> centerCode : '.$centerCode;
				echo '<br> eprid : '.$eprid;
				echo '<br> excd : '.$excd;
				echo '<br> grp_code : '.$grp_code;
			echo '<br> memcategory : '.$memcategory; */
			
			if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$state_code,'state_delete'=>'0'));
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
				
				if($grp_code=='R')
				{
					$grp_code='B1_1';
				}
				
				//$today_date=date('Y-m-d');
				$today_date='2021-02-02';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master_JaiibReschedule_121',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master_JaiibReschedule_121',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				
				if(count($getfees) > 0)
				{
					if($state_code=='MAH')
					{
						if($elearning_flag == 'Y')
						{
							if($el_subject_cnt > 0 &&(base64_decode($excd) == 21 || base64_decode($excd) == 42 || base64_decode($excd) ==992 || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65))
							{
								$fee=$getfees[0]['cs_tot'];
							}
							else
							{
								$fee=$getfees[0]['elearning_cs_amt_total'];
							}
						}
						else
						{
							$fee=$getfees[0]['cs_tot'];
						}
					}
					else
					{
						if($elearning_flag == 'Y')
						{
							if($el_subject_cnt > 0 &&(base64_decode($excd) == 21 || base64_decode($excd) == 42 || base64_decode($excd) ==992 || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65) )
							{
								$fee=$getfees[0]['igst_tot'];
							}
							else
							{
								$fee=$getfees[0]['elearning_igst_amt_total'];
							}
						}
						else
						{
							$fee=$getfees[0]['igst_tot'];
						}
					}
				}
				
			}
			return $fee;
		}
		
		function get_el_ExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL,$el_subject_cnt=NULL,$state_code=NULL)
		{
			$fee=0;
			$CI = & get_instance();
			//$centerCode= $_POST['centerCode'];
			//$eprid=$_POST['eprid'];
			//	$excd=$_POST['excd'];
			//$grp_code=$_POST['grp_code'];
			
			/* echo '<br> centerCode : '.$centerCode;
				echo '<br> eprid : '.$eprid;
				echo '<br> excd : '.$excd;
				echo '<br> grp_code : '.$grp_code;
				echo '<br> memcategory : '.$memcategory;
				echo '<br> elearning_flag : '.$elearning_flag;
				echo '<br> memcategory : '.$memcategory;
				echo '<br> el_subject_cnt : '.$el_subject_cnt;
			echo '<br> state_code : '.$state_code; */
			
			if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$state_code,'state_delete'=>'0'));
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
				
				if($grp_code=='R')
				{
					$grp_code='B1_1';
				}
				
				//$today_date=date('Y-m-d');
				$today_date='2021-02-02';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master_JaiibReschedule_121',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master_JaiibReschedule_121',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				
				if(count($getfees) > 0)
				{
					if($state_code=='MAH')
					{
						if($elearning_flag == 'Y'){
							$fee=$getfees[0]['elearning_cs_amt_total'];
							}else{
							$fee=$getfees[0]['elearning_cs_amt_total'];
						}
					}
					else
					{
						if($elearning_flag == 'Y'){
							$fee=$getfees[0]['elearning_igst_amt_total'];
							}else{
							$fee=$getfees[0]['elearning_igst_amt_total'];
						}
					}
				}
			}
			return $fee;
		}
		
		function getExamFeedetails($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag = NULL,$el_subject_cnt=NULL,$state_code=NULL)
		{ 
			$getfees=array();
			$fee=0;
			$CI = & get_instance();
			//$centerCode= $_POST['centerCode'];
			//$eprid=$_POST['eprid'];
			//	$excd=$_POST['excd'];
			//$grp_code=$_POST['grp_code'];
			if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
			{
				$getstatedetails=$CI->master_model->getRecords('state_master',array('state_code'=>$state_code,'state_delete'=>'0'));
				//echo "<br/>".$CI->db->last_query();
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
				
				if($grp_code=='R')
				{
					$grp_code='B1_1';
				}
				
				 //$today_date=date('Y-m-d');
					$today_date='2021-02-02';
				 //$today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				//$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master_JaiibReschedule_121',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				//echo $CI->db->last_query();exit;
				//echo "<br/>".$CI->db->last_query();
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master_JaiibReschedule_121',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
					//echo "<br/>".$CI->db->last_query();
				}
			}
			/* print_r($getfees);
			exit; */
			return $getfees;
		}
	
		/* function test_mail()
		{
			$info_arr = array(
							//'to' => $result[0]['email'],
							'to'=>'sagar.matale@esds.co.in',
							'from' => 'logs@esds.co.in',
							'subject' => 'Test Mail',
							'message' => 'Test Mail'
							);
			$files = array();
			$mail = $this->Emailsending->mailsend_attch($info_arr, $files);
			print_r($mail);
		} */
	}
