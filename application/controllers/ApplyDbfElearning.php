<?php	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class ApplyDbfElearning extends CI_Controller 
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
			//$this->load->model('chk_session');
			exit;	
			
		}	
		
		public function index() { redirect(site_url('ApplyDbfElearning/login')); }
		
		public function login()
		{		
			//$this->check_browser();	
			//exit;		
			$chk_time = date('Y-m-d H:i:s');
			$data['error'] = '';
			
			if($this->session->userdata('DbfElearningLoginFlag')==1) { redirect(site_url('ApplyDbfElearning/comApplication')); }
			
			if(isset($_POST['submit']))
			{  
				$this->form_validation->set_rules('Username', 'Registration/Membership No.', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('val1', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('val2', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('val3', 'Value', 'trim|callback_check_login_captcha|xss_clean',array('required' => 'Please enter the %s'));
				
				if($this->form_validation->run() == TRUE)
				{
					$ChkExist = $this->master_model->getRecords('member_exam',array('regnumber'=>$this->input->post('Username')));
					if(count($ChkExist) == 0) { $this->session->set_flashdata('error','Invalid credential'); redirect(site_url('ApplyDbfElearning/logout')); }
					
					$checkAlreadyApply = $this->master_model->getRecords('member_exam',array('regnumber'=>$this->input->post('Username'), 'elearning_flag'=>'Y'));
										
					if(count($checkAlreadyApply) > 0)
					{
						$data['error'] = 'You already updated your application';
					}
					else
					{					
						$this->db->where('mem_mem_no',$this->input->post('Username'));
						$chk_old_app = $this->master_model->getRecords('admit_card_details_jaiib_20nov2020','','admitcard_id,exm_cd');
						
						/*if($chk_old_app[0]['exm_cd'] != '21'){
							$data['error']='Invalid credential..';			
						}
						else*/
						{
							
							$this->db->where('regnumber',$this->input->post('Username'));
							$this->db->where('isactive','1');
							$user_info = $this->master_model->getRecords('member_registration','','regid,regnumber,firstname,middlename,lastname,registrationtype');
							
							$this->db->where('mem_mem_no',$this->input->post('Username'));
							$this->db->where('remark',1);
							$this->db->where('exm_prd',220);
							$chk_app = $this->master_model->getRecords('admit_card_details','','admitcard_id,exm_prd,exm_cd,app_update');
													
							if(count($user_info) > 0 && count($chk_app) > 0 && count($chk_old_app) > 0)
							{
								$user_data['mregid_applyexam'] = $user_info[0]['regid'];
								$user_data['mregnumber_applyexam'] = $user_info[0]['regnumber'];
								$user_data['mfirstname_applyexam'] = $user_info[0]['firstname'];
								$user_data['mmiddlename_applyexam'] = $user_info[0]['middlename'];
								$user_data['mlastname_applyexam'] = $user_info[0]['lastname'];
								$user_data['memtype'] = $user_info[0]['registrationtype'];
								$user_data['memexcode'] = $chk_app[0]['exm_cd'];
								$user_data['memexprd'] = $chk_app[0]['exm_prd'];
								$user_data['mem_admitid'] = $chk_app[0]['admitcard_id'];
								$user_data['DbfElearningLoginFlag'] = 1;
	
								$this->session->set_userdata($user_data);							
								redirect(site_url('ApplyDbfElearning/comApplication'));
							}
							else
							{
								$data['error']='Invalid credential..';						
							}							
						}			
					}					
				} 
				else
				{
					$data['validation_errors'] = validation_errors();
				}
			}
			
			$this->load->view('memapplyexam_dbf/apply_dbf_elearning_login',$data);
		}
		
		public function check_browser() //ALLOW ONLY MOZILLA FIREFOX BROWSER TO ACCESS THE LINK
		{
			$this->load->library('user_agent');
			if($this->agent->is_browser())
			{
				$agent = $this->agent->browser();
				if(strtolower($agent) != 'firefox') 
				{ 
					echo '<div style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
					<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
					<div style="padding:20px;">
					<p style="margin:0 0 20px 0;">Dear Member,</p>
					<p style="margin:0 0 10px 0;line:height:22px;">Please Kindly use Mozilla Firefox browser for this applicaiton.<br><br><a href="https://www.mozilla.org/en-US/firefox/new/">Download Mozilla Firefox</a></p>
					</div>                            
					</div>';
					exit;
				}
			}
			else
			{
				echo '<div style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
				<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
				<div style="padding:20px;">
				<p style="margin:0 0 20px 0;">Dear Member,</p>
				<p style="margin:0 0 10px 0;line:height:22px;">Please Kindly use Mozilla Firefox browser for this applicaiton.<br><br><a href="https://www.mozilla.org/en-US/firefox/new/">Download Mozilla Firefox</a></p>
				</div>                            
				</div>';
				exit;
			}			
		}
		
		public function check_login_captcha() 
		{
			$val1 = $this->input->post('val1');		  
			$val2 = $this->input->post('val2');		  
			$val3 = $this->input->post('val3');
			$add_val = ($val1+$val2);
			
			if($val1 == "" || $val2 == "" || $val3 == "" || $add_val != $val3)
			{
				$this->form_validation->set_message('check_login_captcha', 'Please enter correct answer');	
				return FALSE;
			}
			else
			{
				return TRUE;								
			}
		}	
				
		public function comApplication()
		{			
			$data = array();
			if($this->session->userdata('memexcode')=='' || $this->session->userdata('DbfElearningLoginFlag') != '1') 
			{ 
				$this->session->set_flashdata('error','Error occurred');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}
			
			$ChkExist = $this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($ChkExist) == 0) { $this->session->set_flashdata('error','Invalid credential'); redirect(site_url('ApplyDbfElearning/logout')); }
			
			$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$this->db->where('exam_period',$this->session->userdata('memexprd'));
			$this->db->where('pay_status',1);
			$this->db->where('elearning_flag','Y');
			$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');
			if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 ) 
			{ 
				$this->session->set_flashdata('error','You already updated your application');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}	
			
			$this->db->where('mem_mem_no',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exm_cd',$this->session->userdata('memexcode'));
			$this->db->where('exm_prd',$this->session->userdata('memexprd'));
			$this->db->where('remark',1);
			$data['compulsory_subjects'] = $compulsory_subjects = $this->master_model->getRecords('admit_card_details','','sub_cd,sub_dsc,center_code,venueid,exam_date,time',array('sub_cd'=>'ASC'));
			
			$data['center'] = $center = $this->master_model->getRecords('center_master',array('center_code'=>$compulsory_subjects[0]['center_code']), '', '', '', '1');
			
			if(isset($_POST['btnPreviewSubmit']))
			{
				if($this->session->userdata('examinfo')) { $this->session->unset_userdata('examinfo'); }
				
				//$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
				$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
				
				if($this->form_validation->run()==TRUE)
				{
					//print_r($_POST);exit;
					$el_subject = array();
					if(isset($_POST['el_subject']))
					{
						$el_subject = $_POST['el_subject'];
					}
					
					$user_data=array(	
					'selCenterName'=>$center[0]['center_code'],
					'excd'=>$_POST['excd'],
					'eprid'=>$_POST['eprid'],
					'txtCenterCode'=>$center[0]['center_code'],
					'subject_arr'=>array(),
					'elearning_flag'=>"Y",
					'el_subject'=>$el_subject,
					'feeEL'=>$this->input->post('feeEL')					
					);
					$this->session->set_userdata('examinfo',$user_data);
					
					
					$log_title ="DBF free Member exam apply details";
					$log_message = serialize($user_data);
					$rId = $this->session->userdata('mregid_applyexam');
					$regNo = $this->session->userdata('mregnumber_applyexam');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
					redirect(site_url('ApplyDbfElearning/preview'));					
				}
			}		
			
			$data['user_info'] = $user_info = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			$data['examinfo'] = $examinfo = $this->master_model->getRecords('exam_master', array('exam_code'=>$this->session->userdata('memexcode')));
			
			$this->load->view('memapplyexam_dbf/dbf_elearning_comApplication',$data);
		}
		
		public function preview()
		{		/* echo "<pre>"; print_r($_SESSION);	 echo "</pre>"; */
			$data = array();
			if($this->session->userdata('memexcode')=='' || $this->session->userdata('DbfElearningLoginFlag') != '1') 
			{
				$this->session->set_flashdata('error','Error occurred');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}
			
			$ChkExist = $this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($ChkExist) == 0) { $this->session->set_flashdata('error','Invalid credential'); redirect(site_url('ApplyDbfElearning/logout')); }
			
			$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$this->db->where('exam_period',$this->session->userdata('memexprd'));
			$this->db->where('pay_status',1);
			$this->db->where('elearning_flag','Y');
			$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');			
			if(isset($chk_already_apppy_member_exam) && count($chk_already_apppy_member_exam) > 0 )
			{ 
				$this->session->set_flashdata('error','You already updated your application');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}
			
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$data['examinfo'] = $examinfo=$this->master_model->getRecords('exam_master');
			
			
			$data['user_info'] = $user_info = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			$data['center'] = $center = $this->master_model->getRecords('center_master',array('center_code'=>$this->session->userdata['examinfo']['txtCenterCode']), '', '', '', '1');
			//$data['elearning_flag'] = $this->session->userdata['examinfo']['elearning_flag'];
			$data['el_subject'] = $this->session->userdata['examinfo']['el_subject'];
			$this->load->view('memapplyexam_dbf/dbf_elearning_preview',$data);
		}
		
		public function set_jaiib_elsub_cnt()
		{
			$subject_cnt_arr = array('subject_cnt'=>$_POST['subject_cnt']);
			$this->session->set_userdata($subject_cnt_arr);
		}
		
		public function add_record()
		{
			$data = array();
			if($this->session->userdata('memexcode')=='' || $this->session->userdata('DbfElearningLoginFlag') != '1')
			{ 
				$this->session->set_flashdata('error','Error occurred');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}
			
			$ChkExist = $this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($ChkExist) == 0) { $this->session->set_flashdata('error','Invalid credential'); redirect(site_url('ApplyDbfElearning/logout')); }
			
			$this->db->where('regnumber',$this->session->userdata('mregnumber_applyexam'));
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$this->db->where('exam_period',$this->session->userdata('memexprd'));
			$this->db->where('pay_status',1);
			$this->db->where('elearning_flag','Y');
			$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');			
			if(count($chk_already_apppy_member_exam) > 0 )
			{ 
				$this->session->set_flashdata('error','You already updated your application');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}
			
			/* Get EL Subject Counts */
			$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']); 
			
			if($el_subject_cnt > 0)
			{
				if($this->config->item('exam_apply_gateway')=='sbi')
				{					
					/*Payment Check Code - Bhushan */
					$check_payment_val = check_payment_status($this->session->userdata('mregnumber_applyexam'));
					if($check_payment_val == 1)
					{
						$this->session->set_flashdata('error','Your transaction is in process. Please wait for some time!');
						redirect(site_url('ApplyDbfElearning/comApplication'));
					}
					else
					{
						redirect(site_url('ApplyDbfElearning/sbi_make_payment/'));
					}
				}	
			}
		}		
		
		public function sbi_make_payment()
		{
			/* Payment Code : Bhushan */
			$this->chk_session->Mem_checklogin_external_user();
			$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
			$cgst_amt=$sgst_amt=$igst_amt='';
			$cs_total=$igst_total='';
			$getstate=$getcenter=$getfees=array();
			$valcookie= applyexam_get_cookie();
			$total_el_amount = 0;
			$el_subject_cnt = 0;
			$total_elearning_amt = 0;
			if($valcookie)
			{
				////redirect(site_url('ApplyDbfElearning/login'));
			}
			
			$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
			if(count($el_subject_cnt) <= 0)
			{
				$this->session->set_flashdata('error','Error occurred');
				redirect(site_url('ApplyDbfElearning/logout'));
			}		
			
			$ChkExist = $this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			if(count($ChkExist) == 0) { $this->session->set_flashdata('error','Invalid credential'); redirect(site_url('ApplyDbfElearning/logout')); }
			
			if(isset($_POST['processPayment']) && $_POST['processPayment'])
			{
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('mregnumber_applyexam'),'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
				if(count($checkpayment) > 0)
				{
					$endTime = date("Y-m-d H:i:s",strtotime("+15 minutes",strtotime($checkpayment[0]['date'])));
					$current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$this->session->set_flashdata('error','Wait your transaction is under process!.');
						redirect(site_url('ApplyDbfElearning/comApplication'));
					}
				}
				
				$regno = $this->session->userdata('mregnumber_applyexam');
				include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$merchIdVal = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				$pg_success_url = site_url("ApplyDbfElearning/sbitranssuccess");
				$pg_fail_url    = site_url("ApplyDbfElearning/sbitransfail");
				
				$amount = $total_elearning_amt = 0;
				$txtCenterCode = $this->session->userdata['examinfo']['txtCenterCode'];
				$eprid = $this->session->userdata('memexprd');
				$excd = $this->session->userdata('memexcode');
				$memtype = $this->session->userdata('memtype');
				$elearning_flag = "Y";
				$el_subject = $this->session->userdata['examinfo']['el_subject'];
				$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
				$regno = $this->session->userdata('mregnumber_applyexam');
				//echo "<br>grp_code => ".$grp_code = $this->session->userdata['examinfo']['grp_code'];
				
				// Fee Logic
				if($this->config->item('sb_test_mode'))
				{
					$amount = $this->config->item('exam_apply_fee');
				}
				else
				{
					if(isset($el_subject) && $el_subject_cnt > 0 && ($excd == $this->config->item('examCodeJaiib') || $excd == $this->config->item('examCodeDBF') || $excd == $this->config->item('examCodeSOB')))
					{ 
						$el_amount = get_el_ExamFeeFree($this->session->userdata['examinfo']['selCenterName'],$eprid,$excd,$memtype,$elearning_flag);
						$total_elearning_amt = $el_amount * $el_subject_cnt;
						$amount = $amount + $total_elearning_amt;
					}
				}
				
				if($amount==0 || $amount=='')
				{
					$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
					redirect(base_url().'ApplyDbfElearning/comApplication/');	
				}
				
				//Ordinary member Apply exam
				//	Ref1 = orderid
				//	Ref2 = iibfexam
				//	Ref3 = member reg num
				//	Ref4 = exam_code + exam year + exam month ex (101201602)
				$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>$excd,'exam_period'=>$eprid),'exam_month');
				$ref4 = ($excd).$yearmonth[0]['exam_month'];
				
				$exname = '';
				$exname_arr = $this->master_model->getRecords('exam_master',array('exam_code'=>$excd),'description');
				$exname = $exname_arr[0]['description'];
				
				if($memtype == 'O'){ $pg_flag = 'IIBF_EXAM_O'; }
				elseif($memtype == 'NM'){ $pg_flag = 'IIBF_EXAM_NM'; }
				elseif($memtype == 'DB'){ $pg_flag = 'IIBF_EXAM_DB'; }
				else{ $pg_flag = 'IIBF_EXAM_O'; }
				//regnumber, exam_code, exam_period, pay_status = 1
				//member_exam => id
				//payment_transaction => ref_id
				
				$ger_ref_id = $this->master_model->getRecords('member_exam',array('regnumber'=>$regno, 'exam_code'=>$excd, 'exam_period'=>$eprid, 'pay_status'=>"1"),'id');
				
				$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				//'ref_id'         => $mem_exam_id, // Primary Key of Member Exam
				'ref_id'           => $ger_ref_id[0]['id'], // Primary Key of Member Exam
				'description'      => $exname,
				'status'           => '2',
				'exam_code'    	   => $excd,
				'pg_flag'          => $pg_flag
				);
				
				$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);		
				
				/* Get Order Id */
				$MerchantOrderNo = sbi_exam_order_id($pt_id);
				
				// payment gateway custom fields -
				$custom_field = $MerchantOrderNo."^iibfexam^".$regno."^".$ref4;
				
				// update receipt no. in payment transaction -
				$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
				$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
				
				/* Code For Invoice */ 
				$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$this->session->userdata['examinfo']['selCenterName'],'exam_period'=>$eprid,'center_delete'=>'0'));
				
				if(count($getcenter) > 0)
				{
					//get state code,state name,state number.
					$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
					
					//call to helper (fee_helper)
					$getfees = getExamFeedetailsEL($this->session->userdata['examinfo']['selCenterName'],$eprid,$excd,$memtype,$elearning_flag);
				}
				
				
				if($getcenter[0]['state_code']=='MAH')
				{
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate=$this->config->item('cgst_rate');
					$sgst_rate=$this->config->item('sgst_rate');
					if($elearning_flag == 'Y')
					{
						//set an total amount
						if(isset($el_subject) && $el_subject_cnt > 0 &&($excd == $this->config->item('examCodeJaiib') || $excd == $this->config->item('examCodeDBF') || $excd == $this->config->item('examCodeSOB')))
						{
							$amount_base = $getfees[0]['elearning_fee_amt'] * $el_subject_cnt;
							$cs_total = $amount; 
							$cgst_amt = $getfees[0]['elearning_cgst_amt'] * $el_subject_cnt; 
							$sgst_amt = $getfees[0]['elearning_sgst_amt'] * $el_subject_cnt; 
						}
					}
					$tax_type='Intra';
				}
				else
				{
					$igst_rate=$this->config->item('igst_rate');
					if($elearning_flag == 'Y')
					{
						if(isset($el_subject) && $el_subject_cnt > 0 &&($excd == $this->config->item('examCodeJaiib') || $excd == $this->config->item('examCodeDBF') || $excd == $this->config->item('examCodeSOB')))
						{
							$amount_base = $getfees[0]['elearning_fee_amt'] * $el_subject_cnt; 
							$igst_total = $amount; 
							$igst_amt = $getfees[0]['elearning_igst_amt'] * $el_subject_cnt;
						}
					}
					$tax_type='Inter';
				}
				if($getstate[0]['exempt']=='E')
				{
					$cgst_rate=$sgst_rate=$igst_rate='';	
					$cgst_amt=$sgst_amt=$igst_amt='';	
				}
				
				/*echo "
					<pre>
					";
					print_r($getstate);
					print_r($getcenter);
					print_r($getfees);
					echo 'state_code =>'.$getcenter[0]['state_code'];
					echo 'elearning_flag =>'.$elearning_flag;
					
				exit;*/
				$gst_no='0';
				$invoice_insert_array = array(
				'pay_txn_id' => $pt_id,
				'receipt_no' => $MerchantOrderNo,
				'exam_code' => $excd,
				'center_code' => $getcenter[0]['center_code'],
				'center_name' => $getcenter[0]['center_name'],
				'state_of_center' => $getcenter[0]['state_code'],
				'member_no' => $regno,
				'app_type' => 'O',
				'exam_period' => $eprid,
				'service_code' => $this->config->item('exam_service_code') ,
				'qty' => '1',
				'state_code' => $getstate[0]['state_no'],
				'state_name' => $getstate[0]['state_name'],
				'tax_type' => $tax_type,
				'fee_amt' => $amount_base,
				'cgst_rate' => $cgst_rate,
				'cgst_amt' => $cgst_amt,
				'sgst_rate' => $sgst_rate,
				'sgst_amt' => $sgst_amt,
				'igst_rate' => $igst_rate,
				'igst_amt' => $igst_amt,
				'cs_total' => $cs_total,
				'igst_total' => $igst_total,
				'exempt' => $getstate[0]['exempt'],
				'created_on' => date('Y-m-d H:i:s')
				);
				
				$inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array,true);
				$log_title = "Exam invoice data from ApplyDbfElearning cntrlr last id inser_id = '".$inser_id."'";
				$log_message =  serialize($invoice_insert_array);
				$rId = $regno;
				$regNo = $regno;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				/* Payment Process Code */
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
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$attachpath=$invoiceNumber=$admitcard_pdf='';
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid receipt id
			$transaction_no  = $responsedata[1];
			if (isset($_REQUEST['merchIdVal'])) { $merchIdVal = $_REQUEST['merchIdVal']; }
			if (isset($_REQUEST['Bank_Code'])) { $Bank_Code = $_REQUEST['Bank_Code']; }
			if (isset($_REQUEST['pushRespData'])) { $encData = $_REQUEST['pushRespData']; }
			
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
						
						$last_qry_payment_transaction = $this->db->last_query();
						$log_title = "E-learning payment_transaction update : receipt_no = '".$MerchantOrderNo."'";
						$log_message =  $last_qry_payment_transaction;
						$rId = $get_user_regnum[0]['member_regnumber'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						
						if($this->db->affected_rows())	
						{
							########## Generate Admit card and allocate Seat #############
							$excode = $this->session->userdata('memexcode');
							if($excode != 101)
							{
								//$el_subject = $this->session->userdata['examinfo']['el_subject'];
								$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
								
								/* Update EL Subject Count in the Member Exam Table */
								$elearning_flag = $this->session->userdata['examinfo']['elearning_flag'];
								$update_data2=array('elearning_flag'=>$elearning_flag,'sub_el_count'=>$el_subject_cnt,'created_on'=>date('Y-m-d H:i:s'));
								$this->master_model->updateRecord('member_exam',$update_data2,array('id'=>$get_user_regnum[0]['ref_id']));	
								
								$last_qry_member_exam = $this->db->last_query();
								$log_title = "E-learning member_exam update : id = '".$get_user_regnum[0]['ref_id']."'";
								$log_message =  $last_qry_member_exam;
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								/* Update 'Y' sub_el_flg : EL Subject in the admit_card_details Table */
								$el_subject_arr = array();
								$el_subject_arr = $this->session->userdata['examinfo']['el_subject'];
								if(!empty($el_subject_arr))
								{
									foreach($el_subject_arr as $el_key => $el_subject)
									{
										if($el_subject == 'Y')	
										{
											$update_data_admit = array('sub_el_flg' => 'Y','created_on'=>date('Y-m-d H:i:s'));
											$this->master_model->updateRecord('admit_card_details',$update_data_admit,
											array('mem_mem_no'=>$get_user_regnum[0]['member_regnumber'],
											'mem_exam_id'=>$get_user_regnum[0]['ref_id'],
											'sub_cd'=>$el_key,
											'exm_cd'=>$this->session->userdata('memexcode'),
											'exm_prd'=>$this->session->userdata('memexprd'),
											'remark'=>'1'));
											
											$last_qry_admit_card_details = $this->db->last_query();
											$log_title = "E-learning admit_card_details update : mem_mem_no = '".$get_user_regnum[0]['member_regnumber']."'";
											$log_message = $last_qry_admit_card_details;
											$rId = $get_user_regnum[0]['member_regnumber'];
											$regNo = $get_user_regnum[0]['member_regnumber'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);
										} 
									}
								}
								
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
								
								//get invoice	
								$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
								
								if(count($getinvoice_number) > 0)
								{
									$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
									if($invoiceNumber)
									{
										$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
									}
									
									$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
									$this->db->where('pay_txn_id',$payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
									$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									
									$last_qry_exam_invoice = $this->db->last_query();
									$log_title = "E-learning exam_invoice update : receipt_no = '".$MerchantOrderNo."'";
									$log_message = $last_qry_exam_invoice;
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}	
								
								$mail_content = "<div style='border: 1px solid #ccc;background: #f5f5f5;padding: 15px 20px;max-width: 700px;'>
																		<p style='margin:0'>Dear Candidate,</p>
																		<p style='margin:20px 0 0 0'>Greetings from IIBF !</p>
																		<p style='margin:10px 0 15px 0'>This mail is to inform you that we have received your payment and you have successfully registered for E-learning option against DB&F Examination. <br><br>
																		Please check below transaction details,<br>
																		<strong>Transaction No. : ".$payment_info[0]['transaction_no']."</strong><br>
																		<strong>Transaction Date : ".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."</strong></p>
																		<p style='margin:0'>Regards,<br>IIBF Team</p>
																	</div>";
								
								$sender_email = $this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>"1"),"email", "", "", "1");
								
								$info_arr=array('to'=>$sender_email[0]['email'],
								'from'=>"logs@iibf.esdsconnect.com",
								'subject'=>"Exam Enrolment Acknowledgement",
								'message'=>$mail_content
								);
								
								if($attachpath!='')
								{		
									$files=array($attachpath);
									/* $exam_period_date = 'DEC - 2020';
									$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
									$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
									$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
									$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str); */	
									$this->Emailsending->mailsend_attch($info_arr,$files);
									//$this->Emailsending->mailsend($info_arr);
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
				
				redirect(base_url().'ApplyDbfElearning/mail_success');
			}
		}
				
		public function mail_success()
		{		
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$examinfo=$this->master_model->getRecords('exam_master');			
			
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$this->db->where('exam_period',$this->session->userdata('memexprd'));
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			
			$data['user_info'] = $user_info;
			$data['examinfo'] = $examinfo;
			$data['center'] = $center;
			$this->load->view('memapplyexam_dbf/dbf_elearning_success',$data);
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
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description');
					
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
					$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
					
					$info_arr=array('to'=>$result[0]['email'],
						'from'=>$emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str
					);
					/* $info_arr=array('to'=>'Akshay.Shirke@esds.co.in',
					'from'=>$emailerstr[0]['from'],
					'subject'=>$emailerstr[0]['subject'],
					'message'=>$final_str
					);	 */					
					
					//send sms to Ordinary Member
					//$sms_final_str = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					//$this->master_model->send_sms('8793012005',$sms_final_str);
					//$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		
				}
				//End Of SBICALL Back	
				//redirect(base_url().'ApplyDbfElearning/fail/'.base64_encode($MerchantOrderNo));
				
				redirect(base_url().'ApplyDbfElearning/mail_fail');
				
			}
			else
			{
				die("Please try again...");
			}
		}	
		
		public function mail_fail()
		{			
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			
			$this->db->where('exam_code',$this->session->userdata('memexcode'));
			$examinfo=$this->master_model->getRecords('exam_master');
			
			
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$this->db->where('exam_period',$this->session->userdata('memexprd'));
			$this->db->where("center_delete",'0');
			$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
			
			$data['user_info'] = $user_info; 
			$data['examinfo'] = $examinfo;
			$data['center'] = $center;
			$this->load->view('memapplyexam_dbf/dbf_elearning_fail',$data);
		}		
	
		public function logout()
		{	
			$session_data['mregid_applyexam'] = $session_data['mregnumber_applyexam'] = $session_data['mfirstname_applyexam'] = $session_data['mmiddlename_applyexam'] = $session_data['mlastname_applyexam'] = $session_data['memtype'] = $session_data['memexcode'] = $session_data['memexprd'] = $session_data['mem_admitid'] = $session_data['DbfElearningLoginFlag'] = "";

			$this->session->set_userdata($session_data);
			
			if($this->session->flashdata('error')) { $this->session->set_flashdata('error',$this->session->flashdata('error')); }			
			redirect(site_url('ApplyDbfElearning/login'));
		}		
	} 	