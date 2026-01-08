<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	class Applyexam extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
            $this->load->helper('url');
			$this->load->model('master_model');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			$this->load->model('chk_session');
			$this->load->helper('cookie');
			$this->load->model('log_model');
			$this->load->model('KYC_Log_model');
			$this->chk_session->Check_mult_session();
			$this->load->model('billdesk_pg_model');
			$this->load->model('refund_after_capacity_full');
			
			
			
			//XXX : CODE ADDED BY SAGAR & PRATIBHA
			//$this->jaiib_reschedule_arr = array(500134774,510265855,510346707,510319177,500161524);//
			//$this->jaiib_reschedule_arr = array(510113643,510323886,510166917,510070879,510187398,510410098,510056851);
			// $this->jaiib_reschedule_arr = array(510488437);
			$this->jaiib_reschedule_arr = array();
			//redirect(base_url());
			//$this->load->model('chk_session');
			//

			//Allowing member to register for RPE Period 876 after registration closed
        	/*$this->rpe_reschedule_arr = array(500190517,510501116,500136619,510281475,510386222,510374914,510061119,510620004,510142541,510387752,510076540,500040245,510279737,500131505,510347683,510245580,510558225,802632784,510317919,500127266,510296416,510517749,510057361,510256562,510381093,500163026,510605327,500186128,510629204,510086805,500110839,510161709,510409435,802671269,510429320,510218501,510593966,510447788,510483929,510310409,510035240,510101093,510536518,510134169,510576484,510295231,510480713,510676095,500145531,500166548);*/

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
		
		// Add the below function check the is fedai member or not created by gaurav shewale
        private function is_fedai_member($regnumber,$ExamCode) 
        {   
            // $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            // $this->db->join("eligible_master", 'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period', 'left');
            // $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
            // $this->db->where("misc_master.misc_delete", '0');
            // $this->db->where("eligible_master.member_no",$regnumber);
            // $this->db->where("eligible_master.app_category !=", 'R');
            $this->db->where('exam_code', $ExamCode);
            // $this->db->order_by("eligible_master.subject_code", "asc");
            $examinfo = $this->master_model->getRecords('exam_activation_master');

            $exam_period = isset($examinfo[0]['exam_period']) ? $examinfo[0]['exam_period'] : '';

            $arr_fedai_eligible   = [];
            $AssociateInstituteId = '';
            
            $arr_fedai_eligible = $this->master_model->check_fedai_eligible($ExamCode,$exam_period,$regnumber);
            $arr_response = [];
            if ($arr_fedai_eligible['api_res_flag'] == 'success') 
            {
                $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true); 
                // print_r($arr_response); exit;  
                if (count($arr_response)>0) 
                {
                    $AssociateInstituteId = $arr_response[0][7];
                    $examstatus = $arr_response[0][4];
                    if($examstatus !='F' && $examstatus!='') {
                        return false;
                    }
                }
                else
                {
                    $this->db->where("isactive",'1');
                    $this->db->where("regnumber",$regnumber);
                    $memberinfo = $this->master_model->getRecords('member_registration');
                    $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
                }
            }
            else
            {
                $this->db->where("isactive",'1');
                $this->db->where("regnumber",$regnumber);
                $memberinfo = $this->master_model->getRecords('member_registration');
                $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
            }

            $sel_institute_data  = [];
            if ( $AssociateInstituteId != '' || $AssociateInstituteId != null ) 
            {
                $this->db->where('fedai_institution_master.institution_delete', '0');
                $this->db->where('fedai_institution_master.institude_id', $AssociateInstituteId);
                $sel_institute_data = $this->master_model->getRecords('fedai_institution_master', '', '', array('name' => 'asc'));
                
                if ( count($sel_institute_data) < 1 ) {
                    return false;
                }
                else
                {
                    return true;
                }
            }
            else
            {
             
                return false;
            }   
            // End
        }
		
		##---------default userlogin (prafull)-----------##
		public function exapplylogin()
		{
			//  echo 'Under maintenance. We will get back in 30 minutes';exit;
			//exit;
			$system_date = date("H:i:s");
			//if ($system_date > '12:00:00' && $system_date < '13:00:00')
			
			if ($system_date > '12:00:00' && $system_date < '13:00:00')
				{ 
					// echo'ONLINE PAYMENT services are not available due to maintenance activity from 12 PM to 01 PM.';exit;
				}
			
			$data['error'] = '';
			$this->chk_session->checklogin_external();
			//check exam active or not
			
			// IP whitelist condition
			if( $this->get_client_ip1()!='115.124.115.75' && $this->get_client_ip1()!='182.73.101.70' )
			{
				$excodes = array(220,8,11,19,78,79,151,153,156,158,163,165,166,119,157);
				
				if(in_array(base64_decode($this->input->get('ExId')),$excodes)) {
					echo'Dipcert Exam will be start soon';exit;
				}
			}
			
			
			$check_exam_activation = check_exam_activate(base64_decode($this->input->get('ExId')));
			
			if (base64_decode($this->input->get('ExId')) == '528' || base64_decode($this->input->get('ExId')) == '529' || base64_decode($this->input->get('ExId')) == '530' || base64_decode($this->input->get('ExId')) == '531' || base64_decode($this->input->get('ExId')) == '534') {
				redirect(base_url() . 'ELearning/exapplylogin/?Extype=' . $this->input->get('Extype') . '&Mtype=' . $this->input->get('Mtype') . '&ExId=' . $this->input->get('ExId'));
			}
			if ($check_exam_activation == 0) {
				redirect(base_url() . 'Applyexam/accessdenied/');
			}

			$get_ip_address = '';
            $set_client_ip_address = array('182.73.101.70','115.124.115.75','106.216.246.171','106.216.243.125','106.216.243.125');
            $exam_codes_chk = array(1002,1003,1004,1005,1009,1013,1014,1006,1007,1008,1011,1012,1017,1019,1020,2027,1058,1001);
            $get_ip_address = get_ip_address();

            if(in_array(base64_decode($this->input->get('ExId')),$exam_codes_chk) && !in_array($get_ip_address,$set_client_ip_address) )
		    {
		        //echo "<br>".$get_ip_address;
		        //echo "Site Under Maintenance. Please try again later.";exit;
		    } 

             
			
			$data          = array();
			$data['error'] = '';
			
			$Extype = $this->input->get('Extype');
			$Mtype  = $this->input->get('Mtype');
			$regnumber =  $this->input->post('Username');
			$exam_code = base64_decode($this->input->get('ExId'));
			
			// SPECIAL CONDITION DIPCERT GAURAV
		    $excodes = array(8, 11, 19, 78, 79, 119, 151, 153, 154, 156, 157, 158, 163, 165, 166, 220);
		    $ExId = base64_decode($this->input->get('ExId'));
		    if (in_array($ExId, $excodes))
		    {
		      echo'This Exam is not active'; exit;
		    }
			
			if (isset($_POST['btnLogin'])) {

				/*if (!in_array($this->input->post('Username'), $this->rpe_reschedule_arr)){ 
		            $this->session->set_flashdata('error_message', 'Exam is not active, registration is closed.');
			        redirect(base_url() . 'Applyexam/exapplylogin/?Extype='.$Extype.'&Mtype='.$Mtype.'&ExId='.$ExId);
		        }*/

				$config = array(
				array(
				'field' => 'Username',
				'label' => 'Username',
				'rules' => 'trim|required',
				),
				array(
				'field' => 'code',
				'label' => 'Code',
				'rules' => 'trim|required|callback_check_captcha_examapply',
				),
				);
				
				$this->form_validation->set_rules($config);
				$dataarr = array(
				'regnumber' => $this->input->post('Username'),
				'isactive'  => '1',
				'isdeleted' => '0',
				);
				/* $val1=$_POST['val1'];
					
					$val2=$_POST['val2'];
					
					$val3=$_POST['val3'];
					
				$add_val= ($val1+$val2);*/
				
				// if($add_val==$val3)
				//{
				if ($this->form_validation->run() == true) {
					
					// For exam code 19 and 156 fresh member not allowed skipadmit gaurav
					$ExId     = $this->input->get('ExId');
					$examcode = base64_decode($ExId);
					$Extype   = $this->input->get('Extype');
					$Mtype    = $this->input->get('Mtype');
			        if ($examcode == 19 || $examcode == 156 || $examcode == 153) 
			        {
			            $check_eligible_fresh_old_member = $this->master_model->getRecords('eligible_master', array('
			                exam_code' => $examcode,'member_no' => $this->input->post('Username')));
			            // echo $this->db->last_query(); exit;
			            if (count($check_eligible_fresh_old_member) < 1) {
			                $message = 'The exam has been discontinued, and no new registrations will be accepted.';
			                $this->session->set_flashdata('error_message', $message);
			                redirect(base_url() . 'Applyexam/exapplylogin/?Extype='.$Extype.'&Mtype='.$Mtype.'&ExId='.$ExId);
			            }   
						else if(count($check_eligible_fresh_old_member)>0) {
							if ($check_eligible_fresh_old_member[0]['app_category']=='R') {
								$message = 'The exam has been discontinued, and no new registrations will be accepted.';
								$this->session->set_flashdata('error_message', $message);
								redirect(base_url() . 'Applyexam/exapplylogin/?Extype='.$Extype.'&Mtype='.$Mtype.'&ExId='.$ExId);
							}
						}
			        }
			        //  end


					$this->db->select('registrationtype,regid,regnumber,firstname,middlename,lastname,createdon,registrationtype,isactive,usrpassword');
					$where = "(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
					$this->db->where($where);
					$user_info = $this->master_model->getRecords('member_registration', $dataarr);
					//echo '---'.$this->db->last_query();
					//print_r($user_info);
					if (count($user_info) > 0) {
						if ($user_info[0]['isactive'] == 1) {
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
							
							$mysqltime = date("H:i:s");
							$user_data = array('mregid_applyexam' => $user_info[0]['regid'],
							'mregnumber_applyexam'                => $user_info[0]['regnumber'],
							'mfirstname_applyexam'                => $user_info[0]['firstname'],
							'mmiddlename_applyexam'               => $user_info[0]['middlename'],
							'mlastname_applyexam'                 => $user_info[0]['lastname'],
							'mtimer_applyexam'                    => base64_encode($mysqltime),
							'memtype'                             => $user_info[0]['registrationtype'],
							'mpassword_applyexam'                 => base64_encode($decpass));
							
							
							//Added bby Priyanka W on 31/10/2023 for Dipcert
							$examCd = base64_decode($this->input->get('ExId'));
							//echo $examCd; die;
							if(in_array($examCd,$this->config->item('examCodeDIPCERT'))){ 
								$memNo =  $this->input->post('Username');
								//$this->db->where_in('exam_status',array('F','A'));
								//$this->db->where_in('app_category',array('R','B1_1'));
								$res = $this->master_model->getRecords('eligible_master', array('member_no' => $memNo, 'exam_code' => $examCd));
								//echo $this->db->last_query();
								
								if(count($res)>0){
									//foreach ($$res as $key => $value) {
									//if(($value['exam_status'] == 'F' || $value['exam_status'] == 'A') && $value['app_category'] == 'R' || $value['app_category'] == 'B1_1'){
									if(($res[0]['exam_status'] == 'F' || $res[0]['exam_status'] == 'A') && $res[0]['app_category'] == 'R' || $res[0]['app_category'] == 'B1_1')
									{
										$data['error'] = '<span style="">This exam has been discontinued. No fresh registrations are allowed.</span>';
									}
									else
									{
										if ($examCd == 8 || $examCd == 158) 
										{
											if(($res[0]['exam_status'] == 'F' || $res[0]['exam_status'] == 'A') && $res[0]['app_category'] == 'R' || $res[0]['app_category'] == 'S1' || $res[0]['app_category'] == 'B1_1' || $res[0]['app_category'] == 'B1_2' || $res[0]['app_category'] == '') 
											{
												$data['error'] = '<span style="">This exam has been discontinued. No fresh registrations are allowed.</span>';
											}
											else 
											{
												if ($exam_code != '' && $exam_code == 1009 && $regnumber != '') 
												{
													$fedaiStatus = $this->is_fedai_member($regnumber,$exam_code);
													if ($fedaiStatus) 
													{
														$this->session->set_userdata($user_data);
														$sess = $this->session->userdata();
														redirect(base_url() . 'Applyexam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $Extype);       
													}
													else
													{
														$this->db->where('exam_code', $exam_code);
											            // $this->db->order_by("eligible_master.subject_code", "asc");
											            $examinfo = $this->master_model->getRecords('exam_activation_master');

											            $exam_period = isset($examinfo[0]['exam_period']) ? $examinfo[0]['exam_period'] : '';

											            $arr_fedai_eligible   = [];
											            $AssociateInstituteId = '';
											            
											            $arr_fedai_eligible = $this->master_model->check_fedai_eligible($exam_code,$exam_period,$regnumber);
											            $arr_response = [];

											            if ($arr_fedai_eligible['api_res_flag'] == 'success') {
											                $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true); 
											                // print_r($arr_response); exit;  
											                if (count($arr_response)>0) {
											                    $AssociateInstituteId = $arr_response[0][7];
											                    $examstatus = $arr_response[0][4];
											                    if($examstatus !='F' && $examstatus!='') 
											                    {
											             			if ($examstatus == 'D') {
										                    			$data['error'] = '<span style="">You have debarred this exam.</span>';	
											                    	} elseif ($examstatus == 'P') {
											                    		$data['error'] = '<span style="">You have alredy passed this exam.</span>';
											                    	} elseif ($examstatus == 'V') {
											                    		$data['error'] = '<span style="">Valid application exist, Attempt remaining.</span>';
											                    	}           
											                    }
											                    else
											                    {
											             			$data['error'] = '<span style="">You are not eligible to apply for this FEDAI exam.</span>';
											                    }
											                }
											                else
										                    {
										             			$data['error'] = '<span style="">You are not eligible to apply for this exam.</span>';
										                    }
											            }
											            else
											            {
											            	$data['error'] = '<span style="">You are not eligible to apply for this exam.</span>';
											            }           
													}
												}
												else
												{
													$this->session->set_userdata($user_data);
													$sess = $this->session->userdata();
													redirect(base_url() . 'Applyexam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $Extype);
												}    
											}   
										}
										else
										{
											if ($exam_code != '' && $exam_code == 1009 && $regnumber != '') 
											{
												$fedaiStatus = $this->is_fedai_member($regnumber,$exam_code);
												if ($fedaiStatus) 
												{
													$this->session->set_userdata($user_data);
													$sess = $this->session->userdata();
													redirect(base_url() . 'Applyexam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $Extype);       
												}
												else
												{
													$this->db->where('exam_code', $exam_code);
										            // $this->db->order_by("eligible_master.subject_code", "asc");
										            $examinfo = $this->master_model->getRecords('exam_activation_master');

										            $exam_period = isset($examinfo[0]['exam_period']) ? $examinfo[0]['exam_period'] : '';

										            $arr_fedai_eligible   = [];
										            $AssociateInstituteId = '';
										            
										            $arr_fedai_eligible = $this->master_model->check_fedai_eligible($exam_code,$exam_period,$regnumber);
										            $arr_response = [];

										            if ($arr_fedai_eligible['api_res_flag'] == 'success') {
										                $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true); 
										                // print_r($arr_response); exit;  
										                if (count($arr_response)>0) {
										                    $AssociateInstituteId = $arr_response[0][7];
										                    $examstatus = $arr_response[0][4];
										                    if($examstatus !='F' && $examstatus!='') 
										                    {
										             			if ($examstatus == 'D') {
										                    			$data['error'] = '<span style="">You have debarred this exam.</span>';	
										                    	} elseif ($examstatus == 'P') {
										                    		$data['error'] = '<span style="">You have alredy passed this exam.</span>';
										                    	} elseif ($examstatus == 'V') {
										                    		$data['error'] = '<span style="">Valid application exist, Attempt remaining.</span>';
										                    	}                 
										                    }
										                    else
										                    {
										             			$data['error'] = '<span style="">You are not eligible to apply for this FEDAI exam.</span>';
										                    }
										                }
										                else
									                    {
									             			$data['error'] = '<span style="">You are not eligible to apply for this exam.</span>';
									                    }
										            }
										            else
										            {
										            	$data['error'] = '<span style="">You are not eligible to apply for this exam.</span>';
										            }           
												}
											}
											else
											{
												$this->session->set_userdata($user_data);
												$sess = $this->session->userdata();
												redirect(base_url() . 'Applyexam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $Extype);
											}
										}
									}
									//}
								}
								else{
									$data['error'] = '<span style="">This exam has been discontinued. No fresh registrations are allowed.</span>';
								}
          					}
							else
							{	
								if ($exam_code != '' && $exam_code == 1009 && $regnumber != '') 
								{
									$fedaiStatus = $this->is_fedai_member($regnumber,$exam_code);
									if ($fedaiStatus) 
									{
										$this->session->set_userdata($user_data);
										$sess = $this->session->userdata();
										redirect(base_url() . 'Applyexam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $Extype);       
									}
									else
									{
										$this->db->where('exam_code', $exam_code);
							            // $this->db->order_by("eligible_master.subject_code", "asc");
							            $examinfo = $this->master_model->getRecords('exam_activation_master');

							            $exam_period = isset($examinfo[0]['exam_period']) ? $examinfo[0]['exam_period'] : '';

							            $arr_fedai_eligible   = [];
							            $AssociateInstituteId = '';
							            
							            $arr_fedai_eligible = $this->master_model->check_fedai_eligible($exam_code,$exam_period,$regnumber);
							            $arr_response = [];

							            if ($arr_fedai_eligible['api_res_flag'] == 'success') {
							                $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true); 
							                // print_r($arr_response); exit;  
							                if (count($arr_response)>0) {
							                    $AssociateInstituteId = $arr_response[0][7];
							                    $examstatus = $arr_response[0][4];
							                    if($examstatus !='F' && $examstatus!='') 
							                    {
							                    	if ($examstatus == 'D') {
										                $data['error'] = '<span style="">You have debarred this exam.</span>';	
							                    	} elseif ($examstatus == 'P') {
							                    		$data['error'] = '<span style="">You have alredy passed this exam.</span>';
							                    	} elseif ($examstatus == 'V') {
							                    		$data['error'] = '<span style="">Valid application exist, Attempt remaining.</span>';
							                    	}            
							                    }
							                    else
							                    {
							             			$data['error'] = '<span style="">You are not eligible to apply for this FEDAI exam.</span>';
							                    }
							                }
							                else
						                    {
						             			$data['error'] = '<span style="">You are not eligible to apply for this exam.</span>';
						                    }
							            }
							            else
							            {
							            	$data['error'] = '<span style="">You are not eligible to apply for this exam.</span>';
							            }               
									}
								}
								else
								{
									$this->session->set_userdata($user_data);
									$sess = $this->session->userdata();
									redirect(base_url() . 'Applyexam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $Extype);
								}
							}
							//End
							
							
							//Closed
							} else if ($user_info[0]['isactive'] == 0) {
							$data['error'] = '<span style="">Invalid Credentials</span>';
							} else {
							$data['error'] = '<span style="">This account is suspended</span>';
						}
						} else {
						$data['error'] = '<span style="">Invalid Credentials</span>';
					}
					} else {
					$data['validation_errors'] = validation_errors();
				}
				//}
				/*else
					{
					$data['error'] = 'Please enter correct answer';
				}*/
			}
			
			/*$this->load->helper('captcha');
				$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'uploads/applications/',
				);
				$cap = create_captcha($vals);
				$data['image'] = $cap['image'];
				$data['code']=$cap['word'];
			$this->session->set_userdata('mem_applyexam_captcha', $cap['word']);*/
			//$data['image'] = '';
			//$data['code']='123';
			$this->load->model('Captcha_model');
			$captcha_img   = $this->Captcha_model->generate_captcha_img('mem_applyexam_captcha');
			$data['image'] = $captcha_img;
			$this->load->view('memapplyexam/mem_exam_apply_login', $data);
		}
		
		public function check_eligibility_dipcert($exam_code, $mem_no){
			
			if($exam_code == 18){
				$mem_list1 = array(510003542,510068818,510114370);
				if(in_array($mem_no, $mem_list1)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 78){
				$mem_list2 = array(510014403,510313631,510151273,510378667,510550167);
				if(in_array($mem_no, $mem_list2)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 79){
				$mem_list3 = array(510411275,500091058,510196561,500193326,510429097,510441498,510067901,510227854,802274676,510570073,510283552,510466601);
				if(in_array($mem_no, $mem_list3)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 149){
				$mem_list4 = array(500091058);
				if(in_array($mem_no, $mem_list4)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 151){
				$mem_list5 = array(510427696,510381495,510454687,510438247,510480358,500086049,510113995,510334828,510453880,500035269,510016261,500210589,510387474,510043019,510078514,510133797,510231682);
				if(in_array($mem_no, $mem_list5)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 153){
				$mem_list6 = array(510224084,510135423,510112744);
				if(in_array($mem_no, $mem_list6)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 158){
				$mem_list7 = array(510384077,510276559,510143222,510512274,510373407,510582295,510552487,510346901,510582383,500211985,510473681,500205653);
				if(in_array($mem_no, $mem_list7)){
					return 1;
				}
				else{
					return 0;
				}
			}
			elseif($exam_code == 163){
				$mem_list8 = array(500200148,510586519);
				if(in_array($mem_no, $mem_list8)){
					return 1;
				}   
				else{
					return 0;
				}
			}
			elseif($exam_code == 165){
				$mem_list9 = array(802274826,802274945);
				if(in_array($mem_no, $mem_list9)){
					return 1;
				}
				else{
					return 0;
				}
			}
			
		}
		
		##---------check captcha userlogin (vrushali)-----------##
		public function check_captcha_examapply($code)
		{
			/*if(!isset($this->session->mem_applyexam_captcha) && empty($this->session->mem_applyexam_captcha))
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
			}*/
			$session_name    = 'mem_applyexam_captcha';
			$session_captcha = '';
			if (isset($_SESSION[$session_name])) {$session_captcha = $_SESSION[$session_name];}
			
			$captcha_code = $this->security->xss_clean(trim($this->input->post('code')));
			
			//echo $captcha_code.'=='.$session_captcha;
			
			if ($captcha_code == $session_captcha) {return true;} else { $this->form_validation->set_message('check_captcha_examapply', 'Please enter correct code');return false;}
		}
		
		//##---- reload captcha functionality
		public function generatecaptchaajax()
		{
			/*$this->load->helper('captcha');
				$this->session->unset_userdata("mem_applyexam_captcha");
				$this->session->set_userdata("mem_applyexam_captcha", rand(1, 100000));
				$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
				);
				$cap = create_captcha($vals);
				$data = $cap['image'];
				$_SESSION["mem_applyexam_captcha"] = $cap['word'];
			echo $data;*/
			$this->load->model('Captcha_model');
			echo $captcha_img = $this->Captcha_model->generate_captcha_img('mem_applyexam_captcha');
		}
		
		##GST Message
		public function accessdenied()
		{
			$message = '<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
			$data    = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
		}
		
		//Added bby Priyanka W on 26/10/2023
		public function accessdenied_dipcert()
		{
			$message = '<div style="color:#F00">This exam has been discontinued. No fresh registrations are allowed.</div>';
			$data    = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
		}
		
		##GST Message
		public function GST()
		{
			$message = '<div style="color:#F00">Please pay GST amount of Exam/Mem registration in order to apply for the exam.
			<a href="' . base_url() . 'GstRecovery/" target="new">click here</a> </div>';
			$data = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
		}
		
		public function benchmark_disability_check()
		{
			$msg      = '';
			$flag     = 1;
			$data_arr = $this->master_model->getRecords('member_registration', array(
			'regnumber' => $this->session->userdata('mregnumber_applyexam'),
			'isactive'  => '1',
			), 'benchmark_disability,registrationtype');
			
			if ($data_arr[0]['benchmark_disability'] == '') {
				if ($data_arr[0]['registrationtype'] == 'O') {
					$message = '<div style="color:#F00">Kindly go to Edit Profile and .
					<a href="' . base_url() . 'Login/" target="new">click here</a> to update the, "Benchmark Disability" and then apply for exam. For any queries contact zonal office.</div>';
					$data = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
					$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
				}
				
				if ($data_arr[0]['registrationtype'] == 'NM') {
					$message = '<div style="color:#F00">Kindly go to Edit Profile and .
					<a href="' . base_url() . 'nonmem/" target="new">click here</a> to update the, "Benchmark Disability" and then apply for exam. For any queries contact zonal office.</div>';
					$data = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
					$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
				}
			}
		}
		##------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
		public function examdetails()
		{
			/*if (!in_array($this->session->userdata('mregnumber_applyexam'), $this->rpe_reschedule_arr)){
	            $this->session->set_flashdata('error_invalide_exam_selection', 'Exam is not active, registration is closed.');
	            redirect(base_url() . 'Applyexam/accessdenied_not_old_bcbf_mem');
	        }*/

			/* START : Access denied due to exam recovery by Pooja mane 2025-02-06*/
			$this->db->where('pay_status','2');
			$this->db->where('member_no',$this->session->userdata('mregnumber_applyexam'));
			$result = $this->master_model->getRecords("exam_recovery_master");
			if(count($result)>0){
			   redirect(base_url() . 'Applyexam/Recovery');
			}
			/* END : Access denied due to exam recovery end by Pooja mane 2025-02-06*/

			//XXX : CODE ADDED BY SAGAR & PRATIBHA
			// if(base64_decode($this->input->get('ExId')) == '60' && !in_array($this->session->userdata('mregnumber_applyexam'), $this->jaiib_reschedule_arr)) { redirect(base_url().'Applyexam/accessdenied/'); }
			
			/* Benchmark Disability Check */
			$user_benchmark_disability = $this->master_model->getRecords('member_registration', array(
			'regnumber' => $this->session->userdata('mregnumber_applyexam'),
			'isactive'  => '1',
			), 'benchmark_disability,associatedinstitute');
			
			//START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
			$decode_exam_code = base64_decode($this->input->get('ExId'));
			$check_valid_exam_flag = $this->check_valid_exam_for_member($decode_exam_code);
			if($check_valid_exam_flag == 1){
				//$this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile");
				redirect(base_url() . 'Applyexam/access_denied_invalid_exam');
			} 
			//END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023 
			
			/*For OLD BCBF Exam check in New BCBF Module*/
            $decode_exam_code = base64_decode($this->input->get('ExId'));
            if($decode_exam_code == "101" || $decode_exam_code == "1046" || $decode_exam_code == "1047"){
                $user_details_iibfbcbf = $this->master_model->getRecords('iibfbcbf_batch_candidates', array(
                    'regnumber' => $this->session->userdata('mregnumber_applyexam'),
                    'parent_table_name'  => 'member_registration',
                    'is_deleted'  => '0',
                ), 'regnumber');
                if(isset($user_details_iibfbcbf) && count($user_details_iibfbcbf) > 0){
                    $this->session->set_flashdata('error_invalide_exam_selection', "You are not eligible to register for the selected examination. <strong>For any queries contact zonal office</strong>.");
                    //echo $this->db->last_query();die;
                    redirect(base_url() . 'Applyexam/accessdenied_not_old_bcbf_mem/');
                }
            }
            /*For OLD BCBF Exam check in New BCBF Module*/ 

			if ($user_benchmark_disability[0]['benchmark_disability'] == '') {
				redirect(base_url() . 'Applyexam/benchmark_disability_check');
			}
			/* Benchmark Disability Check Close */
			####check GST paid or not.
			$GST_val = check_GST($this->session->userdata('mregnumber_applyexam'));
			if ($GST_val == 2) {
				redirect(base_url() . 'Applyexam/GST');
			}
			$this->chk_session->Mem_checklogin_external_user();
			//accedd denied due to GST
			//$this->master_model->warning();
			
			//check exam activation
			$check_exam_activation = check_exam_activate(base64_decode($this->input->get('ExId')));
			if ($check_exam_activation == 0) {
				redirect(base_url() . 'Applyexam/accessdenied/');
			}
			
			$flag = $this->checkusers(base64_decode($this->input->get('ExId')));
			
			if ($flag == 0) {
				redirect(base_url() . 'Applyexam/accessdenied/');
			}
			
			
			
			
			$profile_flag = 1;
			if (validate_userdata($this->session->userdata('mregnumber_applyexam')) ||
			!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 's')) ||
			!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 'p')) ||
			!is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 'pr'))) {
				$profile_flag = 0;
			}
			
			$message            = '';
			$cookieflag         = $exam_status         = 1;
			$applied_exam_info  = array();
			$flag               = 1;
			$checkqualifyflag   = 0;
			$examcode           = base64_decode($this->input->get('ExId'));
			$valcookie          = $this->session->userdata('mregnumber_applyexam');
			$check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));
			
			if ($check_qualify_exam[0]['exam_category'] == 1) {
				redirect(base_url() . 'ApplySplexamM/examdetails/?ExId=' . $this->input->get('ExId') . '&' . 'Extype=' . $this->input->get('Extype'));
			}
			
			if ($valcookie) {
				$regnumber    = $valcookie;
				$checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $regnumber, 'status' => '2', 'pay_type' => '2','exam_code'=>$examcode ), '', array('id' => 'DESC'), '0', '1');
				
				if (count($checkpayment) > 0) {
					$endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
					$current_time = date("Y-m-d H:i:s");
					if (strtotime($current_time) <= strtotime($endTime)) {
						$cookieflag = 0;
						} else {
						delete_cookie('examid');
					}
					} else {
					delete_cookie('examid');
				}
				} else {
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
 
			//$check=$this->examapplied($this->session->userdata('regnumber'),$this->input->get('excode2'));
			//if(!$check)
			//{
			//Query to check selected exam details
			if (count($check_qualify_exam) > 0) {
				
				//Condition to check the qualifying id exist
				//if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
				if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0') {
					$qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam1'], $examcode, $check_qualify_exam[0]['qualifying_part1']);
					$flag        = $qaulifyarry['flag'];
					$message     = $qaulifyarry['message'];
					
					if ($flag == 0) {
						$checkqualifyflag = 1;
					}
				}
				//if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0)
				if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0') {
					$qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam2'], $examcode, $check_qualify_exam[0]['qualifying_part2']);
					$flag        = $qaulifyarry['flag'];
					$message     = $qaulifyarry['message'];
					if ($flag == 0) {
						$checkqualifyflag = 1;
					}
				}
				//if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
				if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0') {
					$qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam3'], $examcode, $check_qualify_exam[0]['qualifying_part3']);
					$flag        = $qaulifyarry['flag'];
					$message     = $qaulifyarry['message'];
					if ($flag == 0) {
						$checkqualifyflag = 1;
					}
					} else if ($flag == 1 && $checkqualifyflag == 0) {
					//check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					/*$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode,'eligible_master.institute_id' => 0, 'member_no' => $this->session->userdata('mregnumber_applyexam')));*/
					
					$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_no' => $this->session->userdata('mregnumber_applyexam')));
					
					//Added by Priyanka W for RPE exam Validation
					$examCdArr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','1017','1019','1020','2027','1058','1001');
					
					/* if(in_array($examcode, $examCdArr)){
						//echo '--'; die;
						$get_exam_period = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $examcode));
						
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_period=eligible_master.eligible_period');
						$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.eligible_period' => $get_exam_period[0]['exam_period'], 'member_no' => $this->session->userdata('mregnumber_applyexam')));
						//echo $this->db->last_query(); die;
					} */
					//End
					
					/*$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));*/
					
					if (count($check_eligibility_for_applied_exam) > 0) {
						foreach ($check_eligibility_for_applied_exam as $check_exam_status) {
							if ($check_exam_status['exam_status'] == 'F') {
								$exam_status = 0;
							}
						}

						//if($exam_status==1 ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
						
						if ($exam_status == 1) {
							$flag    = 0;
							$message = $check_eligibility_for_applied_exam[0]['remark'];
						}
						/*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
							{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
						}*/
						//else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
						else if ($exam_status == 0) {
                            $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->input->get('ExId'), $exam_date_arr);
                            //print_r($check); die;
							if (!$check) {
								$check_date = $this->examdate($this->session->userdata('mregnumber_applyexam'), $this->input->get('ExId'));
								if (!$check_date) {
									//CAIIB apply directly
									$flag = 1;
									} else {
									
									$message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->input->get('ExId'));
									//$message='Exam fall in same date';
									$flag = 0;
								}
								} else {
								$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
								$get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->input->get('ExId')), 'misc_master.misc_delete' => '0'), 'exam_month');
								
								//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
								$month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
								$exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
								$message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
								$flag             = 0;
							}
						}
						} else {
                        $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->input->get('ExId'), $exam_date_arr);
						if (!$check) {
							$check_date = $this->examdate($this->session->userdata('mregnumber_applyexam'), $this->input->get('ExId'));
							
							if (!$check_date) {
								//CAIIB apply directly
								$flag = 1;
								} else {
								$message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->input->get('ExId'));
								//$message='Exam fall in same date';
								$flag = 0;
							}
							} else {
							$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
							$get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->input->get('ExId')), 'misc_master.misc_delete' => '0'), 'exam_month');
							//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
							$month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
							$exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
							$message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
							//$message='You have already applied for the examination';
							$flag = 0;
						}
					}
				}
				} else {
				
				$flag = 1;
			}
			
			//Query to check where exam applied successfully or not with transaction
			$is_transaction_doone = $this->master_model->getRecordCount('payment_transaction', array('exam_code' => $examcode, 'member_regnumber' => $this->session->userdata('mregnumber_applyexam'), 'status' => '1'));
			
			if ($is_transaction_doone > 0) {
				$today_date = date('Y-m-d');
				$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,member_exam.created_on');
				$this->db->where('exam_master.elg_mem_o', 'Y');
				//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
				$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
				//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('member_exam.pay_status', '1');
				$applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $examcode, 'regnumber' => $this->session->userdata('mregnumber_applyexam')));
			}
			
			########get Eligible createon date######
			$this->db->limit('1');
			$get_eligible_date = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode), 'eligible_master.created_on');
			$eligiblecnt       = 0;
			if (count($applied_exam_info) > 0) {
				if (strtotime($applied_exam_info[0]['created_on']) > strtotime($get_eligible_date[0]['created_on'])) {
					$eligiblecnt = $eligiblecnt + 1;
				}
			}
			
			if ($cookieflag == 0 && $profile_flag == 1) {
				
				$data = array('middle_content' => 'memapplyexam/exam_apply_cms_msg');
				$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
				} else if ($flag == 0 && $cookieflag == 1) {
				
				if ($profile_flag == 0) {
					$message = '<div style="color:#F00" class="col-md-4">Please update your profile!!<a href=' . base_url() . '> Click here </a>to login</div>';
				}
				
				$data = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
				$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			}
			/*else if(count($applied_exam_info) > 0)
				{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
				$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
				//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
				$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
				$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
				$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
				if($profile_flag==0)
				{
				$message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
				}
				$data=array('middle_content'=>'memapplyexam/already_apply','check_eligibility'=>$message);
				$this->load->view('memapplyexam/mem_apply_exam_common_view',$data);
			}*/
			else if ($eligiblecnt) {
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
				$get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $examcode, 'misc_master.misc_delete' => '0'), 'exam_month');
				//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
				$month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
				$exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
				$message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
				if ($profile_flag == 0) {
					$message = '<div style="color:#F00" class="col-md-4">Please update your profile!!<a href=' . base_url() . '> Click here </a>to login</div>';
				}
				$data = array('middle_content' => 'ELearning/already_apply', 'check_eligibility' => $message);
				$this->load->view('ELearning/mem_apply_exam_common_view', $data);
				} else if ($cookieflag == 1 && $profile_flag == 1) {
				$exam_info = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));
				if (count($exam_info) <= 0) {
					redirect(base_url());
				}
				$this->db->where('exam_code', base64_decode($this->input->get('ExId')));
				$get_period = $this->master_model->getRecords('exam_activation_master', '', 'exam_period');
				
				/*Payment Check Code - Bhushan */
				$check_payment_val = check_payment_status($this->session->userdata('mregnumber_applyexam'));
				if ($check_payment_val == 1) {
					$message = '<h4> Your transaction is in process. Please wait for some time.</h4>';
					$data    = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
					$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
					} else {
					
					$this->db->where('fee_paid_flag', 'F');
					//$this->db->or_where('fee_paid_flag','f');
					$this->db->where('eligible_period', $get_period[0]['exam_period']);
					$this->db->where('member_no', $this->session->userdata('mregnumber_applyexam'));
					$this->db->where('exam_code', base64_decode($this->input->get('ExId')));
					$this->db->order_by("id", "desc");
					
					$eligible_info = $this->master_model->getRecords('eligible_master', '', 'eligible_period');
					
					//echo $this->db->last_query();exit;
					if (count($eligible_info) > 0) {
						redirect(base_url() . 'Remote_exam/examdetails/?ExId=' . $this->input->get('ExId') . '&Extype=' . $this->input->get('Extype') . '&Exprd=' . base64_encode($eligible_info[0]['eligible_period']));
					}
					
					$data = array('middle_content' => 'memapplyexam/cms_page', 'exam_info' => $exam_info);
					$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
				}
				} else if ($profile_flag == 0) { 
				$message = '<div style="color:#F00" class="col-md-4">Please update your profile!!<a href=' . base_url() . '> Click here </a>to login</div>';
				$data    = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
				$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			}
			
			//}
			/*else
        {
        $data=array('middle_content'=>'already_apply','check_eligibility'=>'You have already applied for the examination.');
        $this->load->view('common_view',$data);
			}*/
			
		}
		
		##-------------- check qualify exam pass/fail
		public function checkqualify($qualify_id = null, $examcode = null, $part_no = null)
		{
			// echo $qualify_id.$examcode; exit;
			$message                 = '';
			$flag                    = 0;
			$exam_status             = 1;
			$check_qualify           = array();

			// skipadmit gaurav
			if ($examcode == 19 || $examcode == 156  || $examcode == 153) {
				$check_eligible_fresh_old_member = $this->master_model->getRecords('eligible_master', array('
					exam_code' => $examcode,'member_no' => $this->session->userdata('mregnumber_applyexam')));
				
				if (count($check_eligible_fresh_old_member) < 1) {
					$message = 'The exam has been discontinued, and no new registrations will be accepted.';

					$check_qualify = array('flag' => $flag, 'message' => $message);
					return $check_qualify;	
				}	
				else if(count($check_eligible_fresh_old_member)>0) {
							if ($check_eligible_fresh_old_member[0]['app_category']=='R') {
								$message = 'The exam has been discontinued, and no new registrations will be accepted.';
								$check_qualify = array('flag' => $flag, 'message' => $message);
								return $check_qualify;	
							}
						}

			}
			//  end


			$check_qualify_exam_name = $this->master_model->getRecords('exam_master', array('exam_code' => $qualify_id), 'description');
			//echo  count($check_qualify_exam_name);exit;
			if (count($check_qualify_exam_name) > 0) {
				if ($examcode == 19 || $examcode == 119) {$message = 'You are not eligible to apply for this exam, you should either be <strong>CAIIB</strong> passed or should have <strong>CS qualification</strong>.';} else { $message = 'you have not cleared qualifying examination - <strong>' . $check_qualify_exam_name[0]['description'] . '</strong>.';}
				} else {
				$message = 'you have not cleared qualifying examination.';
			}
			$check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));
			//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
			
			/*$check_qualify_exam_eligibility = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $qualify_id, 'eligible_master.institute_id' => 0, 'part_no' => $part_no, 'member_no' => $this->session->userdata('mregnumber_applyexam')), 'exam_status,remark');*/
			
			$check_qualify_exam_eligibility = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $qualify_id, 'part_no' => $part_no, 'member_no' => $this->session->userdata('mregnumber_applyexam')), 'exam_status,remark');
			
			/*$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')),'exam_status,remark');*/
			if (count($check_qualify_exam_eligibility) > 0) {
				foreach ($check_qualify_exam_eligibility as $check_exam_status) {
					if ($check_exam_status['exam_status'] == 'F' || $check_exam_status['exam_status'] == 'V' || $check_exam_status['exam_status'] == 'D') {
						$exam_status = 0;
					}
				}
				
				//if($check_qualify_exam_eligibility[0]['exam_status']=='P')
				if ($exam_status == 1) {
					//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
					if (base64_decode($this->input->get('Extype')) == '3') {
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
						$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.subject' => '1' . $examcode, 'member_no' => $this->session->userdata('mregnumber_applyexam')));
						
						/*$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.subject'=>'1'. $examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));*/
						} else {
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
						$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_no' => $this->session->userdata('mregnumber_applyexam')));
						
						/*$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));*/
					}
					if (count($check_eligibility_for_applied_exam) > 0) {
						foreach ($check_eligibility_for_applied_exam as $check_exam_status) {
							if ($check_exam_status['exam_status'] == 'F' || $check_exam_status['exam_status'] == 'V' || $check_exam_status['exam_status'] == 'D') {
								$exam_status = 0;
							}
						}
						/*if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')*/
						if ($exam_status == 1) {
							$flag = 0;
							if (base64_decode($this->input->get('Extype')) == '3') {
								
								$message = 'You have already cleared this subject under <strong>' . $check_qualify_exam_name[0]['description'] . '</strong> Elective Examination. Hence you cannot apply for the same';
								//$message='You have already cleared this subject under <strong>'.$check_qualify_exam_name[0]['description'].'</strong> Elective Examination. Hence you cannot apply for the same';
								} else {
								$message = $check_eligibility_for_applied_exam[0]['remark'];
							}
							$check_qualify = array('flag' => $flag, 'message' => $message);
							return $check_qualify;
						}
						//else if($check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' )
						else if ($exam_status == 1) {
							$flag          = 0;
							$message       = $check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify = array('flag' => $flag, 'message' => $message);
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
						else if ($exam_status == 0) {
							$flag          = 1;
							$check_qualify = array('flag' => $flag, 'message' => $message);
							return $check_qualify;
						}
						} else {
						//CAIIB apply directly
						$flag          = 1;
						$check_qualify = array('flag' => $flag, 'message' => $message);
						return $check_qualify;
					}
					} else {
					$flag          = 0;
					$qualification = 0;
					$user_info     = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam')), 'specify_qualification');
					if (count($user_info) > 0) {
						$qualification = $user_info[0]['specify_qualification'];
					}
					if ($qualification == 91 && ($examcode == 19 || $examcode == 119)) {
						$flag = 1;
					}
					if ($check_qualify_exam_eligibility[0]['remark'] != '') {
						$message = $check_qualify_exam_eligibility[0]['remark'];
					}
					$check_qualify = array('flag' => $flag, 'message' => $message);
					return $check_qualify;
				}
				} else {
				//show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
				$flag = 0;
				if ($qualify_id) {
					$get_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $qualify_id), 'description');
					if (count($get_exam) > 0) {
						$qualification = 0;
						$user_info     = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam')), 'specify_qualification');
						if (count($user_info) > 0) {
							$qualification = $user_info[0]['specify_qualification'];
						}
						if (base64_decode($this->input->get('Extype')) == '3') {
							if ($qualification == 91 && ($examcode == 19 || $examcode == 119)) {
								$flag = 1;
								} else {
								if ($examcode == 19 || $examcode == 119) {$message = 'You are not eligible to apply for this exam, you should either be CAIIB passed or should have CS qualification.';} else { $message = 'you have not cleared qualifying examination - <strong>' . $get_exam[0]['description'] . '</strong>.';}
							}
							} else {
							if ($qualification == 91 && ($examcode == 19 || $examcode == 119)) {
								$flag = 1;
								} else {
								if ($examcode == 19 || $examcode == 119) {$message = 'You are not eligible to apply for this exam, you should either be CAIIB passed or should have CS qualification.';} else {
									$message = 'You have not cleared  <strong>' . $get_exam[0]['description'] . '</strong> examination, hence you cannot apply for <strong> ' . $check_qualify_exam[0]['description'] . '</strong>.';
								}
							}
						}
					}
				}
				$check_qualify = array('flag' => $flag, 'message' => $message);
				return $check_qualify;
			}
		}
		
        public function examapplied($regnumber = null, $exam_code = null, $exam_date_arr=array())
		{
			//check where exam alredy apply or not
			$cnt        = 0;
			$today_date = date('Y-m-d');
			$this->db->select('member_exam.*');
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
			//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$this->db->where('exam_master.elg_mem_o', 'Y');
			$this->db->where('pay_status', '1');
			$applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($exam_code), 'regnumber' => $regnumber));
            //echo $this->db->last_query();exit;
			
            //Added by Priyank W for RPE exam Validation
			$exCode = base64_decode($exam_code);
			$examCdArr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','1017','1019','1020','2027','1058','1001');
			
			if(in_array($exCode, $examCdArr)){
				$get_exam_period = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exCode));
				
				$this->db->select('member_exam.*');
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
				//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('exam_master.elg_mem_o', 'Y');
				$this->db->where('pay_status', '1');
				$applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $get_exam_period[0]['exam_period'], 'regnumber' => $regnumber));
				//echo $this->db->last_query(); die;
			}
			//End
			
			####check if number applied through the bulk registration (Prafull)###
			if (count($applied_exam_info) <= 0) {
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
				//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('exam_master.elg_mem_o', 'Y');
				$this->db->where('bulk_isdelete', '0');
				$this->db->where('institute_id!=', '');
				$applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($exam_code), 'regnumber' => $regnumber));
				
			}
            //echo $this->db->last_query();
			//exit;
			###### End of check  number applied through the bulk registration###

            //START : ADDED BY SAGAR ON 2024-09-12 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE
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
              $this->db->where(" (((institute_id IS NULL OR institute_id = '' OR institute_id = '0') AND remark = '1') OR (institute_id IS NOT NULL AND institute_id != '' AND institute_id != '0')) ");      

              $applied_exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber));
              //echo '<br><br>4 ' . $this->db->last_query(); //die;
              if(count($applied_exam_info) > 0)//START : LOG ADDED BY SAGAR M ON 2024-10-02
              {
                $add_duplicate_log = array();
                if(isset($_POST) && count($_POST) > 0) { $add_duplicate_log['posted_data'] = json_encode($_POST); }
                if(isset($_SESSION)) { $add_duplicate_log['session_data'] = json_encode($_SESSION); }
                $add_duplicate_log['last_query'] = $this->db->last_query();
                $add_duplicate_log['regnumber'] = $regnumber;
                $this->master_model->insertRecord('duplicate_redirected_records', $add_duplicate_log);

                $email_send_arr = array();
                $email_send_arr['to'] = 'sagar.matale@esds.co.in, anil.s@esds.co.in';
                $email_send_arr['from'] = 'logs@iibf.esdsconnect.com';
                $email_send_arr['subject'] = 'Duplicate exam application prevented : '.$regnumber;
                $email_send_arr['message'] = 'The member '.$regnumber.' tried to apply for the duplicate exam date but redirected due to the check added by Anil & Sagar.';
                $this->Emailsending->mailsend($email_send_arr);
              }//END : LOG ADDED BY SAGAR M ON 2024-10-02
            } //END : ADDED BY SAGAR ON 2024-09-12 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE

            // START: OLD BCBF CHECK ADDED BY ANIL
            $exCode = base64_decode($exam_code);
            $examCd_Arr = array(1046,1047); 
            if(in_array($exCode, $examCd_Arr)){
                $get_exam_period = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exCode));
                $this->db->select('member_exam.*');
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
                //$this->db->where('exam_master.elg_mem_o', 'Y');
                $this->db->where('pay_status', '1');
                $this->db->where_in('member_exam.exam_code', $examCd_Arr);
                $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $get_exam_period[0]['exam_period'], 'regnumber' => $regnumber));
                //echo $this->db->last_query(); die;
            }    
            // END: OLD BCBF CHECK ADDED BY ANIL

			######get eligible created on data##########
			$this->db->limit('1');
			$get_eligible_date = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($exam_code), 'member_no' => $regnumber), 'eligible_master.created_on');
            //echo count($applied_exam_info);exit;
			
			if (count($applied_exam_info) > 0) {
				if (base64_decode($exam_code) != $this->config->item('examCodeJaiib') && base64_decode($exam_code) != $this->config->item('examCodeDBF') && base64_decode($exam_code) != $this->config->item('examCodeSOB') && base64_decode($exam_code) != $this->config->item('examCodeCaiib') && base64_decode($exam_code) != 62 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective63') && base64_decode($exam_code) != 64 && base64_decode($exam_code) != 65 && base64_decode($exam_code) != 66 && base64_decode($exam_code) != 67 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective68') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective69') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective70') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective71') && base64_decode($exam_code) != 72) {
					
					if (count($get_eligible_date) > 0) {
						
						if (strtotime($applied_exam_info[0]['created_on']) > strtotime($get_eligible_date[0]['created_on'])) {
							$cnt = $cnt + 1;
						}
						
						} else {
						$cnt = count($applied_exam_info);
					}
					} else {
					$cnt = count($applied_exam_info);
				}
				
			}
			return $cnt;
			//return count($applied_exam_info);
		}
		
		//check whether applied exam date fall in same date of other exam date(Prafull)
		public function examdate($regnumber = null, $exam_code = null)
		{
			$flag       = 0;
			$today_date = date('Y-m-d');
			
			$applied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($exam_code), 'exam_date >=' => $today_date, 'subject_delete' => '0'));
			if (count($applied_exam_date) > 0) {
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '1'), 'member_exam.exam_code');
				
				### checking bulk applied ######
				if (count($getapplied_exam_code) <= 0) {
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$this->db->where('bulk_isdelete', '0');
					$this->db->where('institute_id!=', '');
					$getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '2'), 'member_exam.exam_code');
				}
				
				if (count($getapplied_exam_code) > 0) {
					foreach ($getapplied_exam_code as $exist_ex_code) {
						$getapplied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exist_ex_code['exam_code'], 'exam_date >=' => $today_date, 'subject_delete' => '0'));
						if (count($getapplied_exam_date) > 0) {
							foreach ($getapplied_exam_date as $exist_ex_date) {
								foreach ($applied_exam_date as $sel_ex_date) {
									if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {
										$flag = 1;
										break;
									}
								}
								if ($flag == 1) {
									break;
								}
							}
						}
					}
				}
			}
			
			//Remove below flag once RPE exam get over
			if (base64_decode($exam_code) != $this->config->item('examCodeJaiib') && base64_decode($exam_code) != $this->config->item('examCodeDBF') && base64_decode($exam_code) != $this->config->item('examCodeSOB') && base64_decode($exam_code) != $this->config->item('examCodeCaiib') && base64_decode($exam_code) != 62 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective63') && base64_decode($exam_code) != 64 && base64_decode($exam_code) != 65 && base64_decode($exam_code) != 66 && base64_decode($exam_code) != 67 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective68') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective69') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective70') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective71') && base64_decode($exam_code) != 72) {
				$flag = 0;
			}
			
			return $flag;
		}
		
		//get applied exam name which is fall on same date(Prafull)
		public function get_alredy_applied_examname($regnumber = null, $exam_code = null)
		{
			
			$flag       = 0;
			$msg        = '';
			$today_date = date('Y-m-d');
			
			$this->db->select('subject_master.*,exam_master.description');
			$this->db->join('exam_master', 'exam_master.exam_code=subject_master.exam_code');
			$applied_exam_date = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => base64_decode($exam_code), 'exam_date >=' => $today_date, 'subject_delete' => '0'));
			
			if (count($applied_exam_date) > 0) {
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
				$getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '1'), 'member_exam.exam_code,exam_master.description');
				### checking bulk applied ######
				if (count($getapplied_exam_code) <= 0) {
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
					$this->db->where('bulk_isdelete', '0');
					$this->db->where('institute_id!=', '');
					$getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '2'), 'member_exam.exam_code,exam_master.description');
				}
				if (count($getapplied_exam_code) > 0) {
					foreach ($getapplied_exam_code as $exist_ex_code) {
						$getapplied_exam_date = array();
						$getapplied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exist_ex_code['exam_code'], 'exam_date >=' => $today_date, 'subject_delete' => '0'));
						
						if (count($getapplied_exam_date) > 0) {
							foreach ($getapplied_exam_date as $exist_ex_date) {
								foreach ($applied_exam_date as $sel_ex_date) {
									if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {
										$msg  = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";
										$flag = 1;
										break;
									}
								}
								if ($flag == 1) {
									$msg = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";
									break;
								}
							}
						}
						if ($flag == 1) {
							break;
						}
					}
				}
			}
			return $msg;
		}
		
		##------------------ CMS Page for logged in user(PRAFULL)---------------##
		public function comApplication()
		{
			/*if (!in_array($this->session->userdata('mregnumber_applyexam'), $this->rpe_reschedule_arr)){
	            $this->session->set_flashdata('error_invalide_exam_selection', 'Exam is not active, registration is closed.');
	            redirect(base_url() . 'Applyexam/accessdenied_not_old_bcbf_mem');
	        }*/

            $this->load->helper('iibfbcbf/iibf_bcbf_helper');
            $bank_bc_id_card_path = 'uploads/empidproof';
			/* if($this->get_client_ip1()!='115.124.115.75'  && $this->get_client_ip1()!='115.124.115.69'  && $this->get_client_ip1()!='182.73.101.70') {
				$caiibArr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective70') ,$this->config->item('examCodeCaiibElective71'));
				if(in_array($this->session->userdata('examcode'),$caiibArr))
				echo'CAIIB will be start from 01-Sept-2023';exit;
				//echo 'Under maintenance.We will get back in 30 minutes';exit;
			} */


			//  For checking the DIPCERT exam condition
	        $get_ip_address = '';
	        $dipcert_set_client_ip_address = array('152.59.3.250','115.124.115.75'); 
	        $dipcert_exam_codes_chk = array(8,11,19,151,153,156,158,78,79,163,165,166,220,119,157);
	        $get_ip_address = get_ip_address();

	        // IP whitelist condition
	        if( $this->get_client_ip1()!='115.124.115.75' && $this->get_client_ip1()!='182.73.101.70' )
			{
				$excodes = array(220,8,11,19,78,79,151,153,156,158,163,165,166,119,157);
				
				if(in_array($this->session->userdata('examcode'),$excodes)) {
					echo'Dipcert Exam will be start soon';exit;
				}
			}    

			if($this->get_client_ip1()!='115.124.115.72'    && $this->get_client_ip1()!='115.124.115.75'    && $this->get_client_ip1()!='182.73.101.70' && $this->get_client_ip1()!='106.194.206.125') 
			{
				$excodes = array(505,506,507,508,509,600);
				
				if(in_array($this->session->userdata('examcode'),$excodes)) {
					
					//echo 'Under maintenance.We will get back in 30 minutes';exit;
				}
			}  
			if($this->session->userdata('examcode')==220) {
				// echo'Site under maintenance';exit; 
			}
			$restrictNumbersexcodes = array(505,506,507,508,509,600);
			$restrictNumbers=array(500006450,500084034,500202598,510055913,510061506,510100804,510116606,510209816,510227194,510292816,510329062,510342266,510349790,510350357,510383705,510389617,510390900,510392263,510437401,510447741,510451017,510456597,510499789,510517661,510563493,510587838,510592571,510597194,7648018);
			if(in_array($this->session->userdata('mregnumber_applyexam'),$restrictNumbers) && in_array($this->session->userdata('examcode'),$restrictNumbersexcodes)) {
				echo'You are debarred for this exam';
                 exit;
				
			}
			## show/hide elective subject dropdown
			$elective = 'hide';
			//    echo 'Under maintenance.We will get back in 30 minutes';exit;
			$this->chk_session->Mem_checklogin_external_user();
			//accedd denied due to GST
			//$this->master_model->warning();
			$compulsory_subjects = array();
			
			if (isset($_POST['btnPreviewSubmit'])) {

				if($this->get_client_ip1()!='115.124.115.75') 
				{
					//echo '<pre>',print_r($_POST),'</pre>';exit;							 
				}
				//echo '<pre>',print_r($_POST),'</pre>';exit;
				$scribe_flag         = 'N';
				$caiib_subjects      = array();
				$compulsory_subjects = array();
				$scannedphoto_file   = $scannedsignaturephoto_file   = $idproofphoto_file   = $state   = $password   = $var_errors   = '';
				$venue               = $this->input->post('venue');
				$date                = $this->input->post('date');
				$time                = $this->input->post('time');
				if ($this->session->userdata('examinfo')) {
					$this->session->unset_userdata('examinfo');
				}
				if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) {
					if ($this->input->post('check_elective_validation') == 'Y') {
						if(!isset($_POST['venue_caiib']) || !isset($_POST['date_caiib']) || !isset($_POST['time_caiib'])) { 
							$this->session->set_flashdata('error','Error while during registration.please try again!');
							redirect(base_url() . 'Applyexam/comApplication');
						}
					}
					else {
						if(!isset($_POST['venue']) || !isset($_POST['date'])  || !isset($_POST['time']) || empty($_POST['venue']) || empty($_POST['date']) || empty($_POST['time'])) {
							$this->session->set_flashdata('error','Error while during registration.please try again!');
							redirect(base_url() . 'Applyexam/comApplication');
						}
					}
                    
                } 
				$this->form_validation->set_rules('scribe_flag', 'Scribe Services', 'required');
				$this->form_validation->set_rules('medium', 'Medium', 'required|xss_clean');
				$this->form_validation->set_rules('selCenterName', 'Centre Name', 'required|xss_clean');
				$this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');
				if ($this->session->userdata('examcode') != 101 && $this->session->userdata('examcode') != 1046 && $this->session->userdata('examcode') != 1047 && $this->input->post('venue') != '' && $this->input->post('date') && $this->input->post('time') != '') {
                    $this->form_validation->set_rules('venue[]', 'Venue', 'trim|required|xss_clean');
                    $this->form_validation->set_rules('date[]', 'Date', 'trim|required|xss_clean');
                    $this->form_validation->set_rules('time[]', 'Time', 'trim|required|xss_clean');
                }
				
				if ($this->session->userdata('examcode') == $this->config->item('examCodeCaiib')) {
					if ($this->input->post('check_elective_validation') == 'Y') {
						$this->form_validation->set_rules('selSubcode', 'Elective Subject Name', 'required|xss_clean');
					}
				}
				
				if ($this->session->userdata('examcode') == $this->config->item('examCodeSOB')) {
					if ($this->input->post('elearning_flag') == 'Y') {
						$this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
					}
				}
				if ($this->session->userdata('examcode') == $this->config->item('examCodeJaiib') || $this->session->userdata('examcode') == $this->config->item('examCodeCaiib') || $this->session->userdata('examcode') == 65) {
					
					if ($this->input->post('elearning_flag') == 'Y') {
						$this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
					}
					
					$this->form_validation->set_rules('placeofwork', 'Place of Work', 'trim|required|alpha_numeric_spaces|xss_clean');
					$this->form_validation->set_rules('state_place_of_work', 'State', 'trim|required|xss_clean');
					if ($this->input->post('state_place_of_work') != '') {
						$state = $this->input->post('state_place_of_work');
					}
					$this->form_validation->set_rules('pincode_place_of_work', 'Pin Code', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
				}

                $bank_bc_id_card_req_flg  = $empidproofphoto_req_flg = 'required|';

                if($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047 || $this->session->userdata('examcode') == 991 || $this->session->userdata('examcode') == 997){

                    $this->form_validation->set_rules('doj1', 'Date of commencement of operations/joining as BC', 'required|xss_clean');
                    $this->form_validation->set_rules('ippb_emp_id', 'Bank BC ID No', 'required|alpha_numeric_spaces|max_length[20]|xss_clean');
                    //$this->form_validation->set_rules('empidproofphoto', 'Upload Bank BC ID Card', 'file_required|file_allowed_type[jpg,jpeg]|file_size_min[100]|file_size_max[300]|callback_empidproofphoto_upload');

                    if ($this->input->post('name_of_bank_bc') != '') {
                        $name_of_bank_bc = $this->input->post('name_of_bank_bc');
                        $this->form_validation->set_rules('ippb_emp_id', 'Bank BC ID No', 'trim|required|alpha_numeric_spaces|xss_clean|callback_check_bank_bc_id_no_duplication[' . $name_of_bank_bc . ']');
                    } 
                    //if($this->input->post('doj1') != '' && $this->input->post('exam_date_exist') != ''){
                    //echo "Inn".$this->input->post('doj1')."==".$this->input->post('exam_date_exist');die;
                        $exam_date_exist = $this->input->post('exam_date_exist');
                        $this->form_validation->set_rules('doj1', 'Date of Joining', 'trim|required|xss_clean|callback_check_date_of_joining_bc_validation[' . $exam_date_exist . ']');
                    //}
                    if (isset($_POST['bank_bc_id_card_cropper']) && $_POST['bank_bc_id_card_cropper'] != "") { $bank_bc_id_card_req_flg = ''; }  
                    $this->form_validation->set_rules('empidproofphoto', 'Upload Bank BC ID Card', 'trim|'.$bank_bc_id_card_req_flg.'xss_clean');

                }
				
				if ($this->form_validation->run() == true) {
					$subject_arr = array();
					$venue       = $this->input->post('venue');
					$date        = $this->input->post('date');
					$time        = $this->input->post('time');
					$selinstitute       = $this->input->post('institutionworking');
					$selinstitutionname = $this->input->post('institutionname');
					
					if (count($venue) > 0 && count($date) > 0 && count($time) > 0) {
						foreach ($venue as $k => $v) {
							$this->db->group_by('subject_code');
							$compulsory_subjects_name = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($_POST['excd']), 'subject_delete' => '0', 'exam_period' => $_POST['eprid'], 'subject_code' => $k), 'subject_description');
							$subject_arr[$k]          = array('venue' => $v, 'date' => $date[$k], 'session_time' => $time[$k], 'subject_name' => $compulsory_subjects_name[0]['subject_description']);
						}
						#### add elective subject in venue,time,date array#########
						if (isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib'])) {
							$subject_arr[$this->input->post('selSubcode')] = array('venue' => $this->input->post('venue_caiib'), 'date' => $this->input->post('date_caiib'), 'session_time' => $this->input->post('time_caiib'), 'subject_name' => $this->input->post('selSubName1'));
						}
						#########check duplication of venue,date,time##########
						if (count($subject_arr) > 0) {
							$msg          = '';
							$sub_flag     = 1;
							$sub_capacity = 1;
							foreach ($subject_arr as $k => $v) {
								foreach ($subject_arr as $j => $val) {
									if ($k != $j) {
										//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
										if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
											$sub_flag = 0;
										}
									}
								}
								$capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);
								if ($capacity <= 1)
								{
									$total_admit_count=getLastseat(base64_decode($_POST['excd']),$_POST['selCenterName'],$v['venue'],$v['date'],$v['session_time']);
									
									if($total_admit_count > 0)
									{
										$msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);
										$msg =$msg .' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
									}
								}
								if ($msg != '') {
									$this->session->set_flashdata('error', $msg);
									redirect(base_url() . 'Applyexam/comApplication');
								}
							}
						}
						if ($sub_flag == 0) {
							if (base64_decode($_POST['excd']) != 101 && base64_decode($_POST['excd']) != 1046 && base64_decode($_POST['excd']) != 1047) {
                                $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
                                redirect(base_url() . 'Applyexam/comApplication');
                            }
						}
						} else {
						if (isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib'])) {
							$subject_arr[$this->input->post('selSubcode')] = array('venue' => $this->input->post('venue_caiib'), 'date' => $this->input->post('date_caiib'), 'session_time' => $this->input->post('time_caiib'), 'subject_name' => $this->input->post('selSubName1'));
						}
					}
					#----Scrib Flag - changes by POOJA GODSE---#
					$scribe_flag          = $scribe_flag_d          = 'N';
					$Sub_menue_disability = $disability_value = '';
					if (isset($_POST['scribe_flag'])) {
						$scribe_flag = $_POST['scribe_flag'];
					}
					if (isset($_POST['scribe_flag_d'])) {
						$scribe_flag_d = $_POST['scribe_flag_d'];
					}
					if (isset($_POST['Sub_menue'])) {
						$Sub_menue_disability = $_POST['Sub_menue'];
					}
					if (isset($_POST['disability_value'])) {
						$disability_value = $_POST['disability_value'];
					}
					
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
					}
					#----Scrib Flag end - changes by POOJA GODSE---#
					
					$bank_bc_id_card_file_path = $outputempidproof1 = $bank_bc_id_card_filename = $empidproof_file = $date_of_commenc_bc = '';
                    if ( ($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ) 
                    {

                        if(isset($_POST["doj1"]) && $_POST["doj1"] != ""){
                            $doj1       = $_POST["doj1"];
                            $doj        = str_replace('/', '-', $doj1);
                            $date_of_commenc_bc = date('Y-m-d', strtotime($doj));  
                        }
                        /*// generate dynamic employee id card added by gaurav
                        $inputidproofphoto = $_POST["hiddenempidproofphoto"];
                        if (isset($_FILES['empidproofphoto']['name']) && ($_FILES['empidproofphoto']['name'] != '')) 
                        {
                            $img = "empidproofphoto";
                            $tmp_inputempidproof = strtotime($date) . rand(0, 100);
                            //$new_filename = 'non_mem_empidproof_' . $tmp_inputempidproof;

                            if($this->session->userdata('mregnumber_applyexam') != ""){
	                            $new_filename = 'bank_bc_id_card_'.$this->session->userdata('mregnumber_applyexam');
	                        }else{
	                            $new_filename = 'bank_bc_id_card_'.$tmp_inputempidproof;
	                        }

                            $config = array(
                                'upload_path' => './uploads/empidproof',
                                'allowed_types' => 'jpg|jpeg',
                                'file_name' => $new_filename,
                            );
                            $this->upload->initialize($config);
                            $size = @getimagesize($_FILES['empidproofphoto']['tmp_name']);
                            if ($size) 
                            {
                                if ($this->upload->do_upload($img)) 
                                {
                                    $dt = $this->upload->data(); 
                                    $bank_bc_id_card_filename = $dt['file_name'];
                                    $bank_bc_id_card_file_path = base_url() . "uploads/empidproof/" . $bank_bc_id_card_filename;
                                     
                                } 
                                else 
                                {
                                    $this->session->set_flashdata('error', 'Employee Id proof :' . $this->upload->display_errors());
                                    redirect(base_url() . 'NonMember/comApplication');
                                }
                            } 
                            else 
                            {
                                $this->session->set_flashdata('error', 'The filetype you are attempting to upload is not allowed');
                                redirect(base_url() . 'NonMember/comApplication');
                            }
                        }*/

                        if (isset($_POST['bank_bc_id_card_cropper']) && $_POST['bank_bc_id_card_cropper'] != "")
                        {
                            $bank_bc_id_card_cropper = $this->security->xss_clean($this->input->post('bank_bc_id_card_cropper'));
                            $date_new=date('Y-m-d h:i:s');
                            $file_name_str = strtotime($date_new) . rand(0, 100); 

                            if($this->session->userdata('mregnumber_applyexam') != ""){
                                $bank_bc_id_card_new_file_name = "bank_bc_id_card_" . $this->session->userdata('mregnumber_applyexam') . '.' . strtolower(pathinfo($bank_bc_id_card_cropper, PATHINFO_EXTENSION));
                            }else{
                                $bank_bc_id_card_new_file_name = "bank_bc_id_card_" . $file_name_str . '.' . strtolower(pathinfo($bank_bc_id_card_cropper, PATHINFO_EXTENSION));
                            }

                            if (copy(str_replace(base_url(), '', $bank_bc_id_card_cropper), $bank_bc_id_card_path . '/' . $bank_bc_id_card_new_file_name))
                            {
                              $bank_bc_id_card_filename = $bank_bc_id_card_new_file_name;
                              $bank_bc_id_card_file_path = base_url() . "uploads/empidproof/" . $bank_bc_id_card_filename;
                            }
                        }else{
                            $this->session->set_flashdata('error', 'Upload Bank BC ID Card :' . $this->upload->display_errors());
                            redirect(base_url() . 'Applyexam/comApplication');
                        }

                    }

					//priyanka d
					$optFlg='N';
					/*if(isset($_POST['optval']) && $_POST['optval']==1)
						$optFlg='F';
						else if(isset($_POST['optval']) && $_POST['optval']==2)
					$optFlg='R';*/
					
					$selectedoptval=$this->session->userdata('selectedoptVal');
					if($selectedoptval==1) {
						$_POST['grp_code']='B1_1';
						$optFlg='F';
					}
					
					else if($selectedoptval==2)
					$optFlg='R';
					$user_data = array('email' => $_POST["email"],
					'mobile'                   => $_POST["mobile"],
					'photo'                    => '',
					'signname'                 => '',
					'medium'                   => $_POST['medium'],
					'selCenterName'            => $_POST['selCenterName'],
					'optmode'                  => $_POST['optmode'],
					'extype'                   => $_POST['extype'],
					'exname'                   => $_POST['exname'],
					'excd'                     => $_POST['excd'],
					'eprid'                    => $_POST['eprid'],
					'fee'                      => $_POST['fee'],
					'txtCenterCode'            => $_POST['txtCenterCode'],
					'insdet_id'                => '',
					'selected_elect_subcode'   => $_POST['selSubcode'],
					'selected_elect_subname'   => $_POST['selSubName1'],
					'placeofwork'              => $_POST['placeofwork'],
					'state_place_of_work'      => $_POST['state_place_of_work'],
					'pincode_place_of_work'    => $_POST['pincode_place_of_work'],
					'elected_exam_mode'        => $_POST['elected_exam_mode'],
					'grp_code'                 => $_POST['grp_code'],
					'subject_arr'              => $subject_arr,
					'el_subject'               => $el_subject,
					'scribe_flag'              => $scribe_flag,
					'scribe_flag_d'            => $scribe_flag_d,
					'disability_value'         => $disability_value,
					'Sub_menue_disability'     => $Sub_menue_disability,
					'elearning_flag'           => $elearning_flag_new,
					/* 'elearning_flag'=>$_POST['elearning_flag'] */
					'optval'                 => $selectedoptval, // priyanka d- 24-03-23
					'optFlg'                =>  $optFlg,
					'selinstitute'           => $selinstitute,
					'selinstitutionname'     => $selinstitutionname,
					'date_of_commenc_bc'  => $date_of_commenc_bc,
                    'ippb_emp_id' => (isset($_POST['ippb_emp_id']) ? $_POST['ippb_emp_id']:''),
                    'name_of_bank_bc' => (isset($_POST['name_of_bank_bc']) ? $_POST['name_of_bank_bc']:''),
                    'bank_bc_id_card_file_path' => $bank_bc_id_card_file_path,
                    'bank_bc_id_card_filename'       => $bank_bc_id_card_filename
					);
					$this->session->set_userdata('examinfo', $user_data);
					/*echo '<pre>';
						print_r($user_data);
					exit;*/
					//logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
					
					//logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
					/* User Log Activities : Bhushan */
					$log_title   = "Member exam apply details";
					$log_message = serialize($user_data);
					$rId         = $this->session->userdata('regid');
					$regNo       = $this->session->userdata('mregnumber_applyexam');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
					
					/* JAIIB - store user choosed group code - Priyanka D - 03-jan-2023 */
					
					$log_title   = "Member exam apply - group code info";
					
					$log_message = 'original group code='.$_POST['grp_code'].' change group code='.$_POST['origin_grp_code'];
					
					$rId         = $this->session->userdata('regid');
					
					$regNo       = $this->session->userdata('mregnumber_applyexam');
					
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					
					/* JAIIB - store user choosed group code - Priyanka D - 03-jan-2023 */
					
					
					
					redirect(base_url() . 'Applyexam/preview');
					} else {
					$var_errors = str_replace("<p>", "<span>", $var_errors);
					$var_errors = str_replace("</p>", "</span><br>", $var_errors);
				}
			}
			//Considering B1 as group code in query (By Prafull)
			if ($this->session->userdata('examcode') == '') {
				redirect(base_url());
			}
			
			//START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
			$decode_exam_code = $this->session->userdata('examcode');
			$check_valid_exam_flag = $this->check_valid_exam_for_member($decode_exam_code);
			if($check_valid_exam_flag == 1){
				//$this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile");
				redirect(base_url() . 'Applyexam/access_denied_invalid_exam');
			} 
			//END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
			
			//check exam activation
			$check_exam_activation = check_exam_activate($this->session->userdata('examcode'));
			if ($check_exam_activation == 0) {
				
				redirect(base_url() . 'Applyexam/accessdenied/');
			}
			
			$cookieflag = 1;
			$this->chk_session->checkphoto();
			//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
			$valcookie = $this->session->userdata('mregnumber_applyexam');
			if ($valcookie) {
				$regnumber    = $valcookie;
				$checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $regnumber, 'status' => '2', 'pay_type' => '2'), '', array('id' => 'DESC'));
				if (count($checkpayment) > 0) {
					$endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
					$current_time = date("Y-m-d H:i:s");
					if (strtotime($current_time) <= strtotime($endTime)) {
						$cookieflag = 0;
						} else {
						delete_cookie('examid');
					}
					} else {
					delete_cookie('examid');
				}
				} else {
				delete_cookie('examid');
			}
			//End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
			
			//Considering B1 as group code in query (By Prafull)
			if ($this->session->userdata('examcode') == '') {
				redirect(base_url() . 'Applyexam/examlist/');
			}
			
			// priyanka d - for jaiib -23-01-23
			
			if(!$this->session->userdata('selectedoptVal_examcode') || $this->session->userdata('selectedoptVal_examcode')!=$this->session->userdata('examcode') ) {
				//echo $this->session->userdata('selectedoptVal');exit;
				$selectedoptVal = array('selectedoptVal' => 0);
				$this->session->set_userdata($selectedoptVal);
				
			}
			
			$continueAsOld=1;
			
			if($this->session->userdata('selectedoptVal')==1)
			//if(isset($_GET['optval']) && $_GET['optval']==1)
			{
				$continueAsOld=0;
			}
			$keepElectiveSelected=0;
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
			$this->db->join("eligible_master", 'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period', 'left');
			$this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$this->db->where("misc_master.misc_delete", '0');
			$this->db->where("eligible_master.member_no", $this->session->userdata('mregnumber_applyexam'));
			$this->db->where("eligible_master.app_category !=", 'R');
			//$this->db->where("eligible_master.institute_id", 0); // this condition ("eligible_master.institute_id") added by gaurav refered by pooja mane
			$this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));
			$this->db->order_by("eligible_master.subject_code", "asc");
			$examinfo = $this->master_model->getRecords('exam_master');
			// echo "<pre>"; print_r($examinfo);
			// echo $continueAsOld; exit;
			####### get subject mention in eligible master ##########
			$presentElectiveSub=array();
			if (count($examinfo) > 0 && $continueAsOld==1) { //priyanka d- 17-feb-23
				if($this->session->userdata('examcode')==$this->config->item('examCodeCaiib')) { // priyanka d - 30-feb-23 >> keep provision to sect other elective sub
					$getElectiveSub=$this->master_model->getRecords('subject_master', array(
					'exam_code'      => $this->session->userdata('examcode'),
					'subject_delete' => '0',
					'group_code'    => 'E', // priyanka d- 20-3023 >> change caiib exam selection 
					));
					
					foreach($getElectiveSub as $e) {
						$presentElectiveSub[]=$e['subject_code'];
					}
					//  echo'<pre>';print_r($getElectiveSub);exit;
				}
				foreach ($examinfo as $rowdata) {
					if ($rowdata['exam_status'] != 'P') {
						## Caiib changes on 23-Mar-2021
						if ($rowdata['subject_code'] == 999  || in_array($rowdata['subject_code'],$presentElectiveSub)) {
							## Elective subjects
							$elective = 'show';
							$keepElectiveSelected=$rowdata['subject_code']; // priyanka d - 30-feb-23 >> keep provision to sect other elective sub
							} else {
							$this->db->group_by('subject_code');
							$compulsory_subjects[] = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'group_code'    => 'C', // priyanka d- 20-3023 >> change caiib exam selection 
							'subject_delete' => '0', 'exam_period' => $rowdata['exam_period'], 'subject_code' => $rowdata['subject_code']));
							//$elective = 'hide';
						}
					}
				}
				//$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));
				$compulsory_subjects=array_filter($compulsory_subjects);// priyanka d- 20-3023
				$compulsory_subjects = array_map('current', $compulsory_subjects);
				//sort($compulsory_subjects);
				//print_r($compulsory_subjects);
			}
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where('center_master.exam_name', $this->session->userdata('examcode'));
			$this->db->where("center_delete", '0');
			$center = $this->master_model->getRecords('center_master', '', '', array('center_name' => 'ASC'));
			//Below code, if member is new member
			if (count($examinfo) <= 0  || $continueAsOld==0) { 
				
				//priyanka d - 17feb23
				$this->db->select('exam_master.*,misc_master.*');
				$this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period'); //added on 5/6/2017
				$this->db->where("misc_master.misc_delete", '0');
				$this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));
				$examinfo = $this->master_model->getRecords('exam_master');
				//get center
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
				$this->db->where("center_delete", '0');
				$this->db->where('exam_name', $this->session->userdata('examcode'));
				$this->db->group_by('center_master.center_name');
				$center = $this->master_model->getRecords('center_master');
				####### get compulsory subject list##########
				$this->db->group_by('subject_code');
				$compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $examinfo[0]['exam_period']), '', array('subject_code' => 'ASC'));
				
				//echo $this->db->last_query();
				
			}
			
			if (count($examinfo) <= 0) {
				redirect(base_url());
			}
			
			$undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
			$graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
			$postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));
			
			$this->db->where('institution_delete', '0');
			$institution_master = $this->master_model->getRecords('institution_master');
			
			$this->db->where('designation_delete', '0');
			$designation = $this->master_model->getRecords('designation_master');
			
			$idtype_master = $this->master_model->getRecords('idtype_master');
			//To-do use exam-code wirh medium master
			
			$this->db->where('state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
			$this->db->where('medium_master.exam_code', $this->session->userdata('examcode'));
			$this->db->where('medium_delete', '0');
			$this->db->group_by('medium_code');
			$medium = $this->master_model->getRecords('medium_master');
			//get center as per exam
			
			//user information
			$user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')));
			if (count($user_info) <= 0) {
				redirect(base_url());
			}
			$scribe_disability = $this->master_model->getRecords('scribe_disability', array('is_delete' => '0'));
			//subject information
			$caiib_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'E', 'exam_period' => $examinfo[0]['exam_period']));
			
			/* Benchmark Disability */
			$benchmark_disability_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam')), 'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
			/* Benchmark Disability Close */
			// priyanka d - 23-01-23 for jaiib flag
			$order_by=array('id'=>'desc');
			$checkJaiibFlag = $this->master_model->getRecords('eligible_master', array('exam_code' => $this->session->userdata('examcode'),  'eligible_period' => $examinfo[0]['exam_period'],'member_no' => $this->session->userdata('mregnumber_applyexam')),'',$order_by,0,1);
			$showOptForJaiib=0;
			//	echo'<pre>';print_r($checkJaiibFlag);exit;
			if(count($checkJaiibFlag)>0) {
				foreach($checkJaiibFlag as $j) {
					if($j['optForCandidate']=='Y')
					$showOptForJaiib=1;
				}
			}
			if(isset($_GET['optval']) && $_GET['optval']!='' && $showOptForJaiib==0)  {
				redirect(base_url().'/Applyexam/comApplication/');
			}
			
			if(!isset($_GET['optval']) && $this->session->userdata('selectedoptVal')!=0) {
				redirect(base_url().'/Applyexam/comApplication/?optval='.$this->session->userdata('selectedoptVal'));
			}
			if($_GET['optval']!= $this->session->userdata('selectedoptVal')) {
				redirect(base_url().'/Applyexam/comApplication/?optval='.$this->session->userdata('selectedoptVal'));
			}
			
			// Start and add the below field institute name by gaurav
            $ExamCode    = $this->session->userdata('examcode');
            $regnumber   = $this->session->userdata('mregnumber_applyexam');
            $exam_period = isset($examinfo[0]['exam_period']) ? $examinfo[0]['exam_period'] : '';

            $ButtonDisable        = true;
            $arr_fedai_eligible   = [];
            $AssociateInstituteId = '';

            if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '') 
            {
	            $arr_fedai_eligible = $this->master_model->check_fedai_eligible($ExamCode,$exam_period,$regnumber);
	            $arr_response = [];
	            if ($arr_fedai_eligible['api_res_flag'] == 'success') 
	            {
	                $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true);
	                if (count($arr_response)>0) {
	                    $AssociateInstituteId = $arr_response[0][7];

	                    $examstatus = $arr_response[0][4];
	                    if( $examstatus !='F' && $examstatus!='' ) 
	                    {
	                        $msg = 'You are not eligible for Fedai Exam ';
	                        if($examstatus=='P') {
	                            $msg = 'You have already passed selected exam';
	                        } 
	                        $ButtonDisable = false;
	                        $this->session->set_flashdata('error', $msg);        
	                       
	                    }
	                }
	                else
	                {
	                    $this->db->where("isactive",'1');
	                    $this->db->where("regnumber",$regnumber);
	                    $memberinfo = $this->master_model->getRecords('member_registration');
	                    $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
	                }
	            }
	            else
	            {
	                $this->db->where("isactive",'1');
	                $this->db->where("regnumber",$regnumber);
	                $memberinfo = $this->master_model->getRecords('member_registration');
	                $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
	            }

	            $sel_institute_data  = [];
	            if ( $AssociateInstituteId != '' || $AssociateInstituteId != null ) 
	            {
	                 $this->master_model->updateRecord('member_registration', array('associatedinstitute' => $AssociateInstituteId), array('regnumber' => $regnumber));

	                $this->db->where('fedai_institution_master.institution_delete', '0');
	                $this->db->where('fedai_institution_master.institude_id', $AssociateInstituteId);
	                $sel_institute_data = $this->master_model->getRecords('fedai_institution_master', '', '', array('name' => 'asc'));
	                
	                if ( count($sel_institute_data) < 1) {

	                    $this->db->where('institution_master.institution_delete', '0');
	                    $this->db->where('institution_master.institude_id', $AssociateInstituteId);
	                    $sel_institute_data = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));
	                    
	                    $ButtonDisable = false;
	                    $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
	                }
	            }
	            else
	            {
	                $ButtonDisable = false;
	                $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
	            }   
        	}


            /* START: Check Member for Fedai Institute API */
            /*if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '')
            {
                $ButtonDisable = $this->check_fedai_member($ExamCode,$exam_period,$regnumber); 
            }*/
            /* END: Check Member for Fedai Institute API */
			
						//exemption 1007 code start
            $showExemptionOption = 0;
            $exemptionres = $this->master_model->exemption_api_call_func($this->session->userdata('examcode'),$examinfo[0]['exam_period'],$this->session->userdata('mregnumber_applyexam'));
            if($exemptionres=='true' && $this->session->userdata('examcode') == '1007') {
                    $showExemptionOption =1;
            }
						if($this->get_client_ip1()!='115.124.115.75'    && $this->get_client_ip1()!='182.73.101.70') 
						{
							//$showExemptionOption = 0;
						}
            $chk_exemption_application_exist = $this->master_model->chk_exemption_application($this->session->userdata('examcode'),$this->session->userdata('mregnumber_applyexam'));
            if($chk_exemption_application_exist['applicationexist']==1 || $chk_exemption_application_exist['inprogress']==1) {
                $msg  = $chk_exemption_application_exist['msg'];
                $data = array('middle_content' => 'member_notification', 'msg' => $msg);
                return $this->load->view('nonmember/nm_common_view', $data);
            }

            //exemption 1007 code end
			/*OLD BCBF Inst Mater Dropdown*/
            $old_bcbf_institute_data = array();
            $ExamCode = $this->session->userdata('examcode');
            if( $ExamCode == 101 || $ExamCode == 991 || $ExamCode == 997 || $ExamCode == 1046 || $ExamCode == 1047 ) 
            { 
                $this->db->where('is_deleted', '0');
                $old_bcbf_institute_data = $this->master_model->getRecords('bcbf_old_exam_institute_master', '', '', array('institute_name' => 'asc')); 
            }
            /*OLD BCBF Inst Mater Dropdown*/
			
			$data = array('scribe_disability' => $scribe_disability, 'middle_content' => 'memapplyexam/comApplication', 'states' => $states, 'undergraduate' => $undergraduate, 'ButtonDisable' => $ButtonDisable, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'sel_institute_data' => $sel_institute_data, 'institution_master' => $institution_master, 'designation' => $designation, 'user_info' => $user_info, 'idtype_master' => $idtype_master, 'examinfo' => $examinfo, 'medium' => $medium, 'center' => $center, 'caiib_subjects' => $caiib_subjects, 'compulsory_subjects' => $compulsory_subjects, 'benchmark_disability_info' => $benchmark_disability_info, 'elective' => $elective,'showOptForJaiib'=>$showOptForJaiib,'selectedoptVal'=>$this->session->userdata('selectedoptVal'), 'keepElectiveSelected'      => $keepElectiveSelected,'showExemptionOption'=>$showExemptionOption,'old_bcbf_institute_data'=>$old_bcbf_institute_data// priyanka d- 20-03-23
			); 
			// echo '<pre>'; print_r($data); exit;
			//priyanka d- added ,'showOptForJaiib'=>$showOptForJaiib -17-feb-23
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			
		}
		
		public function iibf_fedai_eligible_api($exam_code=0, $exam_period=0,$member_no=0)
		{
			$api_res_flag = 'error';
			$api_res_msg = '';
			
			// $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/500060559";
			// $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/510125082";
			// $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/500066883";
			// $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/500060559";
			
			// $api_url= "http://10.10.233.76:8093/fedaieligibleapi/getFedaiEligible/1009/811/".$member_no;
			
			$api_url =  "http://10.10.233.76:8093/fedaieligibleapi/getFedaiEligible/".$exam_code."/".$exam_period."/".$member_no;  //NEW API ADDED BY GAURAV ON 2024-05-27          
			
			// echo $api_url; exit;
			$string = preg_replace('/\s+/', '+', $api_url);
			$x = curl_init($string);
			curl_setopt($x, CURLOPT_HEADER, 0);    
			curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
			curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
			
			$result = curl_exec($x);      
			if(curl_errno($x)) //CURL ERROR
			{
				$api_res_msg = curl_error($x);
			}
			else
			{
				$api_res_flag = 'success';
				$api_res_msg = $result;
			}
			curl_close($x);
			
			$api_result_arr = array();
			$api_result_arr['api_res_flag']     = $api_res_flag;
			$api_result_arr['api_res_response'] = $api_res_msg;
			// print_r($api_result_arr); exit;    
			return $api_result_arr;
		}
		
		public function check_fedai_member($ExamCode = '', $exam_period = '', $regnumber = '')
        {
            $arr_fedai_eligible   = [];
            $AssociateInstituteId = '';
            $ButtonDisable        = true;

            if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '') 
            {
                $arr_fedai_eligible = $this->master_model->check_fedai_eligible($ExamCode,$exam_period,$regnumber);
                // print_r($arr_fedai_eligible); exit;
                $arr_response = [];
                if ($arr_fedai_eligible['api_res_flag'] == 'success') 
                {
                    $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true);
                    if (count($arr_response)>0) 
                    {
                        $AssociateInstituteId = $arr_response[0][7];
                    }
                    else
                    {
                        $this->db->where("isactive",'1');
                        $this->db->where("regnumber",$regnumber);
                        $memberinfo = $this->master_model->getRecords('member_registration');
                        $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
                    }
                }
                else
                {
                    $this->db->where("isactive",'1');
                    $this->db->where("regnumber",$regnumber);
                    $memberinfo = $this->master_model->getRecords('member_registration');
                    $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
                }

                $sel_institute_data  = [];
                if ( $AssociateInstituteId != '' || $AssociateInstituteId != null ) 
                {
                     $this->master_model->updateRecord('member_registration', array('associatedinstitute' => $AssociateInstituteId), array('regnumber' => $regnumber));

                    $this->db->where('fedai_institution_master.institution_delete', '0');
                    $this->db->where('fedai_institution_master.institude_id', $AssociateInstituteId);
                    $sel_institute_data = $this->master_model->getRecords('fedai_institution_master', '', '', array('name' => 'asc'));
                    
                    if ( count($sel_institute_data) < 1) 
                    {
                        $this->db->where('institution_master.institution_delete', '0');
                        $this->db->where('institution_master.institude_id', $AssociateInstituteId);
                        $sel_institute_data = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));
                        
                        $ButtonDisable = false;
                        $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
                    }
                }
                else
                {
                    $ButtonDisable = false;
                    $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
                }
            }
            return $ButtonDisable;       
        }    

		
		##------------------ Set applied exam value in session for logged in user(PRAFULL)---------------##
		/*public function setExamSession()
			{
			$outputphoto1=$outputsign1=$photo_name=$sign_name='';
			if($this->session->userdata('examinfo'))
			{
			$this->session->unset_userdata('examinfo');
			}
			
			//Generate dynamic photo
			if($_POST["hiddenphoto"]!='')
			{
			$input = $_POST["hiddenphoto"];
			//$tmp_nm = rand(0,100);
			$tmp_nm = 'p_'.$this->session->userdata('mregnumber_applyexam').'.jpg';
			$outputphoto = getcwd()."/uploads/photograph/".$tmp_nm;
			$outputphoto1 = base_url()."uploads/photograph/".$tmp_nm;
			file_put_contents($outputphoto, file_get_contents($input));
			$photo_name = $tmp_nm;
			}
			
			// generate dynamic id proof
			if($_POST["hiddenscansignature"]!='')
			{
			$inputsignature = $_POST["hiddenscansignature"];
			//$tmp_signnm = rand(0,100);
			$tmp_signnm = 's_'.$this->session->userdata('mregnumber_applyexam').'.jpg';
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
			'selSubName'=>$_POST['selSubName'],
			'placeofwork'=>$_POST['placeofwork'],
			'state_place_of_work'=>$_POST['state_place_of_work'],
			'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
			'elected_exam_mode'=>$_POST['elected_exam_mode']
			);
			//return false;
			$this->session->set_userdata('examinfo',$user_data);
			//redirect(base_url().'Home/preview');
			logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
			return 'true';
		}*/
		
		##------------------ Preview for applied exam,for logged in user(PRAFULL)---------------##
		public function preview()
		{
			// IP whitelist condition
	        if( $this->get_client_ip1()!='115.124.115.75' && $this->get_client_ip1()!='182.73.101.70' )
			{
				$excodes = array(220,8,11,19,78,79,151,153,156,158,163,165,166,119,157);
				
				if(in_array($this->session->userdata('examcode'),$excodes)) {
					echo'Dipcert Exam will be start soon';exit;
				}
			}  
	        
			//Allowed member for different data
			$subject_arr1 = $this->session->userdata['examinfo']['subject_arr'];
			if (count($subject_arr1) > 0) {
				foreach ($subject_arr1 as $k => $v) {
					$flag = allowed_examdate($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'), $v['date']);
					if ($flag == 1) {
						redirect(base_url() . 'Applyexam/info');
					}
				}
			}
			
			$this->chk_session->Mem_checklogin_external_user();
			$sub_flag     = 1;
			$sub_capacity = 1;
			//echo $this->session->userdata['examinfo']['selCenterName'];exit;
			$compulsory_subjects = array();
			if (!$this->session->userdata('examinfo')) {
				redirect(base_url() . 'home/dashboard/');
			}
			//check exam acivation
			$check_exam_activation = check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
			if ($check_exam_activation == 0) {
				redirect(base_url() . 'Applyexam/accessdenied/');
			}
			
			//START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
			$decode_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
			$check_valid_exam_flag = $this->check_valid_exam_for_member($decode_exam_code);
			if($check_valid_exam_flag == 1){
				//$this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile");
				redirect(base_url() . 'Applyexam/access_denied_invalid_exam');
			} 
			//END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023 
			
			/* START: Check Member for Fedai Institute API */
            $ExamCode  = base64_decode($this->session->userdata['examinfo']['excd']);
            $regnumber = $this->session->userdata('mregnumber_applyexam');
            $exam_period = $this->session->userdata['examinfo']['eprid'];
            if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '')
            {
                $ButtonDisable = $this->check_fedai_member($ExamCode,$exam_period,$regnumber);
                if (!$ButtonDisable) {
                    $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.'); 
                    redirect(base_url() . 'Applyexam/comApplication/');    
                }
            }
            /* END: Check Member for Fedai Institute API */

			############check capacity is full or not ##########
			$subject_arr = $this->session->userdata['examinfo']['subject_arr'];
			if (count($subject_arr) > 0) {
				$msg          = '';
				$sub_flag     = 1;
				$sub_capacity = 1;
				foreach ($subject_arr as $k => $v) {
					foreach ($subject_arr as $j => $val) {
						if ($k != $j) {
							//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
							if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
								$sub_flag = 0;
							}
						}
					}
					$capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
					if ($capacity <= 1) {
						$total_admit_count=getLastseat(base64_decode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['selCenterName'],$v['venue'],$v['date'],$v['session_time']);
						if($total_admit_count > 0)
						{
							$msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
							$msg =$msg .' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
						}
					}
					if ($msg != '') {
						$this->session->set_flashdata('error', $msg);
						redirect(base_url() . 'Applyexam/comApplication');
					}
				}
			}
			if ($sub_flag == 0) {
				$this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
				redirect(base_url() . 'Applyexam/comApplication');
			}
			
			$cookieflag = 1;
			//$this->chk_session->checkphoto();
			//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
			$valcookie = $this->session->userdata('mregnumber_applyexam');
			if ($valcookie) {
				$regnumber    = $valcookie;
				$checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $regnumber, 'status' => '2', 'pay_type' => '2','exam_code'=>base64_decode($this->session->userdata['examinfo']['excd'])), '', array('id' => 'DESC'));
				if (count($checkpayment) > 0) {
					$endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
					$current_time = date("Y-m-d H:i:s");
					if (strtotime($current_time) <= strtotime($endTime)) {
						$cookieflag = 0;
						} else {
						delete_cookie('examid');
					}
					} else {
					delete_cookie('examid');
				}
				} else {
				delete_cookie('examid');
			}
			//End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
			
			if (!$this->session->userdata('examinfo')) {
				redirect(base_url());
			}
			//check for valid fee
			if ($this->session->userdata['examinfo']['fee'] == 0 || $this->session->userdata['examinfo']['fee'] == '') {
				$this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
				redirect(base_url() . 'Applyexam/comApplication/');
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

            $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata['examinfo']['excd'],$exam_date_arr);
			if (!$check) {
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
				$this->db->where('medium_master.exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
				$this->db->where('medium_delete', '0');
				$medium = $this->master_model->getRecords('medium_master');
				
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
				$this->db->where('exam_name', base64_decode($this->session->userdata['examinfo']['excd']));
				$this->db->where('center_code', $this->session->userdata['examinfo']['selCenterName']);
				$center = $this->master_model->getRecords('center_master', '', 'center_name');
				//echo $this->db->last_query();exit;
				$user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')));
				if (count($user_info) <= 0) {
					redirect(base_url());
				}
				$this->db->where('state_delete', '0');
				$states = $this->master_model->getRecords('state_master');
				
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
				$misc = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'misc_delete' => '0'));
				
				if ($cookieflag == 0) {
					$data = array('middle_content' => 'exam_apply_cms_msg');
					} else {
					$disability_value      = $this->master_model->getRecords('scribe_disability', array('is_delete' => 0));
					$scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('is_delete' => 0));
					
					/* Benchmark Disability */
					$benchmark_disability_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam')), 'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
					/* Benchmark Disability Close */
					
					$data = array('disability_value' => $disability_value,
					'scribe_sub_disability'          => $scribe_sub_disability, 'middle_content' => 'memapplyexam/exam_preview', 'user_info' => $user_info, 'medium' => $medium, 'center' => $center, 'misc' => $misc, 'states' => $states, 'compulsory_subjects' => $this->session->userdata['examinfo']['subject_arr'], 'benchmark_disability_info' => $benchmark_disability_info);
				}
				$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
				} else {
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
				$get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'misc_master.misc_delete' => '0'), 'exam_month');
				//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
				$month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
				$exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
				$message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
				$data             = array('middle_content' => 'memapplyexam/already_apply', 'check_eligibility' => $message);
				$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			}
		}
		
		public function info()
		{
			$message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->session->userdata['examinfo']['excd']);
			
			$data = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			
		}
		
		//Show acknowlodgement to to user after transaction succeess
		public function savedetails()
		{
			$this->chk_session->Mem_checklogin_external_user();
			if (($this->session->userdata('examinfo') == '')) {
				redirect(base_url());
			}
			$exam_code  = base64_decode($this->session->userdata['examinfo']['excd']);
			$today_date = date('Y-m-d');
			$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
			$this->db->where('elg_mem_o', 'Y');
			$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete", '0');
			$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $this->session->userdata('mregnumber_applyexam')));
			
			$this->db->where('medium_delete', '0');
			$this->db->where('exam_code', $exam_code);
			$this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
			$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
			
			$this->db->where('exam_name', $exam_code);
			$this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
			$this->db->where("center_delete", '0');
			$center = $this->master_model->getRecords('center_master');
			if (count($applied_exam_info) <= 0) {
				redirect(base_url());
			}
			
			$user_data = array('regid' => $this->session->userdata('mregid_applyexam'),
			'regnumber'                => $this->session->userdata('mregnumber_applyexam'),
			'firstname'                => $this->session->userdata('mfirstname_applyexam'),
			'middlename'               => $this->session->userdata('mmiddlename_applyexam'),
			'lastname'                 => $this->session->userdata('mlastname_applyexam'),
			'memtype'                  => $this->session->userdata('memtype'),
			'timer'                    => $this->session->userdata('mtimer_applyexam'),
			'password'                 => $this->session->userdata('mpassword_applyexam'));
			$this->session->set_userdata($user_data);
			$data = array('middle_content' => 'memapplyexam/exam_applied_success_withoutpay', 'medium' => $medium, 'center' => $center, 'applied_exam_info' => $applied_exam_info);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			
		}
		
		//##---------check mobile number alredy exist or not on edit page(Prafull)-----------##
		public function editmobile()
		{
			$mobile = $_POST['mobile'];
			$regid  = $_POST['regid'];
			if ($mobile != "" && $regid != "") {
				$prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $regid, 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));
				if ($prev_count == 0) {echo 'ok';} else {echo 'exists';}
				} else {
				echo 'error';
			}
		}
		
		##---------check pincode/zipcode alredy exist or not (prafull)-----------##
		public function checkpin()
		{
			$statecode = $_POST['statecode'];
			$pincode   = $_POST['pincode'];
			if ($statecode != "") {
				$this->db->where("$pincode BETWEEN start_pin AND end_pin");
				$prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
				//echo $this->db->last_query();
				if ($prev_count == 0) {echo 'false';} else {echo 'true';}
				} else {
				echo 'false';
			}
		}
		
		//##---------check mail alredy exist or not on edit page(Prafull)-----------##
		public function editemailduplication()
		{
			$email = $_POST['email'];
			$regid = $_POST['regid'];
			if ($email != "" && $regid != "") {
				$prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $regid, 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));
				if ($prev_count == 0) {echo 'ok';} else {echo 'exists';}
				} else {
				echo 'error';
			}
		}
		##------------------Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
		public function Msuccess()
		{
			$this->chk_session->Mem_checklogin_external_user();
			$photoname = $singname = '';
			if (($this->session->userdata('examinfo') == '')) {
				redirect(base_url());
			}
			// echo "<pre>"; print_r($this->session->userdata('examinfo')); exit;
			// CHECK DUPLICATE EXAM DATE APPLICATION CODE ADDED BY ANIL 30 SEP 2024
            if($this->session->userdata('examinfo')){
                $last_query = $current_url_with_query = '';
                $exam_date_arr = $subject_arr_for_examdate = array();
                $subject_arr_for_examdate = isset($this->session->userdata['examinfo']['subject_arr']) ? $this->session->userdata['examinfo']['subject_arr'] : array();
                if(count($subject_arr_for_examdate) > 0){
                    foreach ($subject_arr_for_examdate as $k => $v) 
                    {
                      $exam_date_arr[] = $v['date']; 
                    }
                }  
                if(count($exam_date_arr) > 0){

                    $this->db->where(" ( ((member_exam.institute_id IS NULL OR member_exam.institute_id = '' OR member_exam.institute_id = '0') AND admit_card_details.remark = '1') OR (member_exam.institute_id IS NOT NULL AND member_exam.institute_id != '' AND member_exam.institute_id != '0') ) "); 
                    $this->db->where('member_exam.bulk_isdelete', '0');
                    $this->db->where_in('admit_card_details.exam_date', $exam_date_arr);

                    $this->db->join('member_exam', 'member_exam.id = admit_card_details.mem_exam_id', 'inner');

                    $applied_exam_info = $this->master_model->getRecords('admit_card_details', array('admit_card_details.mem_mem_no' => $this->session->userdata('mregnumber_applyexam')), 'admit_card_details.exm_cd,admit_card_details.exm_prd,admit_card_details.remark,admit_card_details.mem_mem_no,admit_card_details.exam_date,admit_card_details.time,admit_card_details.created_on,admit_card_details.modified_on,admit_card_details.record_source,member_exam.institute_id,member_exam.bulk_isdelete');
                    //echo $this->db->last_query();die;
                    
                    $admit_card_details_data = isset($applied_exam_info) && count($applied_exam_info) > 0 ? $applied_exam_info : array();
                    $session_data['user_all_data'] = isset($this->session->userdata) ? $this->session->userdata : array();

                    $last_query = $this->db->last_query(); 
                    $current_url_with_query = current_url(true); //echo $current_url_with_query;

                    $insert_dup_application_data['page_title'] = "Controller: ".$this->router->fetch_class()." & Function: ".$this->router->fetch_method();
                    $insert_dup_application_data['url'] = $current_url_with_query;
                    $insert_dup_application_data['description'] = $last_query;
                    $insert_dup_application_data['member_no'] = $this->session->userdata('mregnumber_applyexam');
                    $insert_dup_application_data['exam_code'] = base64_decode($this->session->userdata['examinfo']['excd']);
                    $insert_dup_application_data['exam_period'] = $this->session->userdata['examinfo']['eprid'];
                    $insert_dup_application_data['session_data'] = serialize($session_data);
                    $insert_dup_application_data['admit_card_details_data'] = serialize($admit_card_details_data);
                   
                    $inser_id = $this->master_model->insertRecord('check_dup_application_data', $insert_dup_application_data, true);

                    if(isset($applied_exam_info) && count($applied_exam_info) > 0){
                        if(isset($inser_id) && $inser_id > 0){
                            $this->master_model->updateRecord('check_dup_application_data', array('duplicate_application' => 1), array('id' => $inser_id));
                        } 
                        redirect(base_url() . 'Applyexam/accessdenied_already_apply');
                    }
                } 
            }

            // START: OLD BCBF EXTRA CHECK ADDED BY ANIL
            $exCode = base64_decode($this->session->userdata['examinfo']['excd']);
            $examCd_Arr = array(1046,1047); 
            if(in_array($exCode, $examCd_Arr)){
                $this->db->select('member_exam.*');
                $this->db->where('member_exam.pay_status', '1');
                $this->db->where('member_exam.exam_code', $exCode);
                $duplicate_applied_exam_chk = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $this->session->userdata['examinfo']['eprid'], 'member_exam.regnumber' => $this->session->userdata('mregnumber_applyexam')));

                $session_data['user_all_data'] = isset($this->session->userdata) ? $this->session->userdata : array();
                $last_query = $this->db->last_query(); 
                $current_url_with_query = current_url(true); //echo $current_url_with_query;
                $insert_dup_oldbcbf_application_data['page_title'] = "Controller: ".$this->router->fetch_class()." & Function: ".$this->router->fetch_method();
                $insert_dup_oldbcbf_application_data['url'] = $current_url_with_query;
                $insert_dup_oldbcbf_application_data['description'] = $last_query;
                $insert_dup_oldbcbf_application_data['member_no'] = $this->session->userdata('mregnumber_applyexam');
                $insert_dup_oldbcbf_application_data['exam_code'] = base64_decode($this->session->userdata['examinfo']['excd']);
                $insert_dup_oldbcbf_application_data['exam_period'] = $this->session->userdata['examinfo']['eprid'];
                $insert_dup_oldbcbf_application_data['session_data'] = serialize($session_data);
                $insert_dup_oldbcbf_application_data['admit_card_details_data'] = serialize($duplicate_applied_exam_chk);
               
                $inser_id_oldbcbf = $this->master_model->insertRecord('check_dup_application_data', $insert_dup_oldbcbf_application_data, true);

                if(isset($duplicate_applied_exam_chk) && count($duplicate_applied_exam_chk) > 0)
                {
                    if(isset($inser_id_oldbcbf) && $inser_id_oldbcbf > 0){
                        $this->master_model->updateRecord('check_dup_application_data', array('duplicate_application' => 1), array('id' => $inser_id_oldbcbf));
                    } 
                    redirect(base_url() . 'Applyexam/accessdenied_already_apply');
                }
                //echo $this->db->last_query(); die;
            }    
            // END: OLD BCBF EXTRA CHECK ADDED BY ANIL
            
            // CHECK DUPLICATE EXAM DATE APPLICATION CODE ADDED BY ANIL 30 SEP 2024
			
			if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65  || base64_decode($this->session->userdata['examinfo']['excd']) == 11)) {
				if (isset($this->session->userdata['examinfo']['el_subject'][0]) && $this->session->userdata['examinfo']['el_subject'][0] == 'N' && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeJaiib') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeDBF') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeSOB') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeCaiib') && base64_decode($this->session->userdata['examinfo']['excd']) != 65 && base64_decode($this->session->userdata['examinfo']['excd']) != 11) {
					unset($this->session->userdata['examinfo']['el_subject'][0]);
				}
				if ($this->session->userdata['examinfo']['el_subject'] == 'N') {
					$el_subject_cnt = 0;
					} else {
					$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
				}
				} else {
					$el_subject_cnt = 0;
					if($this->session->userdata['examinfo']['elearning_flag'] == "Y"){
	                    if (isset($this->session->userdata['examinfo']['el_subject']) && $this->session->userdata['examinfo']['el_subject'] == 'N') {
	                      $el_subject_cnt = 0;
	                    }else{
	                      $el_subject_cnt = isset($this->session->userdata['examinfo']['el_subject']) ? count($this->session->userdata['examinfo']['el_subject']) : 0;
	                    } //priyanka d >>27-june-24 >> this field was saving as 0 even e-learning selected
	                }
				}
			
			if (isset($_POST['btnPreview'])) {
				$amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
				// echo $this->config->item('examCodeSOB'); exit;
				if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65 || base64_decode($this->session->userdata['examinfo']['excd']) == 11)) {
					
					$el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
					
					$total_elearning_amt = $el_amount * $el_subject_cnt;
					$amount              = $amount + $total_elearning_amt;
					
				}
				// echo $amount; exit;
				/* START: Check Member for Fedai Institute API */
                $ExamCode    = base64_decode($this->session->userdata['examinfo']['excd']);
                $regnumber   = $this->session->userdata('mregnumber_applyexam');
                $exam_period = $this->session->userdata['examinfo']['eprid'];
                if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '')
                {
                    $ButtonDisable = $this->check_fedai_member($ExamCode,$exam_period,$regnumber);
                    if (!$ButtonDisable) {
                        $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.'); 
                        redirect(base_url() . 'Applyexam/comApplication/');    
                    }
                }
                /* END: Check Member for Fedai Institute API */

                /* START: Check OLD BCBF Required Fields for exam code 1046,1047 */
                $sess_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
                if($sess_exam_code == 101 || $sess_exam_code == 1046 || $sess_exam_code == 1047){

                	if($this->session->userdata['examinfo']['ippb_emp_id'] == ""){
                		$this->session->set_flashdata('error', 'Bank BC ID No field is required.'); 
                        redirect(base_url() . 'Applyexam/comApplication/'); 
                	}else if($this->session->userdata['examinfo']['name_of_bank_bc'] == ""){
                		$this->session->set_flashdata('error', 'Name of Bank field is required.'); 
                        redirect(base_url() . 'Applyexam/comApplication/'); 
                	}else if($this->session->userdata['examinfo']['date_of_commenc_bc'] == ""){
                		$this->session->set_flashdata('error', 'Date of commencement of operations/joining as BC is required.'); 
                        redirect(base_url() . 'Applyexam/comApplication/'); 
                	}else if($this->session->userdata['examinfo']['bank_bc_id_card_filename'] == ""){
                		$this->session->set_flashdata('error', 'Bank BC ID Card is required.'); 
                        redirect(base_url() . 'Applyexam/comApplication/'); 
                	} 
                }
                /* END: Check OLD BCBF Required Fields for exam code 1046,1047 */


				//priyanka d =22-feb-23
				$optFlg	=	'N';
				if(isset($this->session->userdata['examinfo']['optFlg']))
				$optFlg	=	$this->session->userdata['examinfo']['optFlg'];
				
				$inser_array = array('regnumber' => $this->session->userdata('mregnumber_applyexam'),
				'exam_code'                      => base64_decode($this->session->userdata['examinfo']['excd']),
				'exam_mode'                      => $this->session->userdata['examinfo']['optmode'],
				'exam_medium'                    => $this->session->userdata['examinfo']['medium'],
				'exam_period'                    => $this->session->userdata['examinfo']['eprid'],
				'exam_center_code'               => $this->session->userdata['examinfo']['txtCenterCode'],
				'exam_fee'                       => $amount,
				'elected_sub_code'               => $this->session->userdata['examinfo']['selected_elect_subcode'],
				'place_of_work'                  => $this->session->userdata['examinfo']['placeofwork'],
				'state_place_of_work'            => $this->session->userdata['examinfo']['state_place_of_work'],
				'pin_code_place_of_work'         => $this->session->userdata['examinfo']['pincode_place_of_work'],
				'scribe_flag'                    => $this->session->userdata['examinfo']['scribe_flag'],
				'scribe_flag_PwBD'               => $this->session->userdata['examinfo']['scribe_flag_d'],
				'disability'                     => $this->session->userdata['examinfo']['disability_value'],
				'sub_disability'                 => $this->session->userdata['examinfo']['Sub_menue_disability'],
				'created_on'                     => date('y-m-d H:i:s'),
				'elearning_flag'                 => $this->session->userdata['examinfo']['elearning_flag'],
				// 'institute_id'                   => $this->session->userdata['examinfo']['selinstitute'],
				'sub_el_count'                   => $el_subject_cnt,
				'optFlg'                         => $optFlg //priyanka d -22-feb-23
				);
				
				if ($inser_id = $this->master_model->insertRecord('member_exam', $inser_array, true)) {

					//priyanka d >>31-jan >> skippedadmitcard so inserting vendor details in new table
					if(in_array(base64_decode($this->session->userdata['examinfo']['excd']),$this->config->item('skippedAdmitCardForExams'))) {
						$curr_center_info = $this->master_model->getRecords('center_master', array('center_code'=>$this->session->userdata['examinfo']['selCenterName'],'exam_name' =>base64_decode($this->session->userdata['examinfo']['excd'])));
						
						$exam_register_vendor = array('regnumber' => $this->session->userdata('mregnumber_applyexam'),
							'ref_id'                      => $inser_id,
							'vendor_code'                    => $curr_center_info[0]['vendor_code'],
							'vendor'                    =>  $curr_center_info[0]['vendor'],
							'center_code'                    =>  $this->session->userdata['examinfo']['selCenterName'],
						);
						$this->master_model->insertRecord('exam_register_vendor', $exam_register_vendor, true);
					}
					if (isset($ExamCode) && $ExamCode == 1009)
                	{
						$regnumber = $this->session->userdata('mregnumber_applyexam');
	                    $AssociateInstituteId = $this->session->userdata['examinfo']['selinstitute'];
                	
                    	$this->master_model->updateRecord('member_registration', array('associatedinstitute' => $AssociateInstituteId), array('regnumber' => $regnumber));
					}
					/* User Log Activities : Bhushan */
					$log_title   = "Member exam apply details - Insert Applyexam.php";
					$log_message = serialize($inser_array);
					$rId         = $this->session->userdata('regid');
					$regNo       = $this->session->userdata('mregnumber_applyexam');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
					
					//echo $this->session->userdata['examinfo']['fee'];
					$this->session->userdata['examinfo']['insdet_id'] = $inser_id;
					//$data['insdet_id'] =$inser_id;
					//$this->session->set_userdata('examinfo', $data);
					$update_array = array();
					
					// Re-set previous image update flags
					$prev_edited_on     = '';
					$prev_photo_flg     = "N";
					$prev_signature_flg = "N";
					$prev_id_flg        = "N";
					$prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam')), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
					if (count($prev_edited_on_qry)) {
						$prev_edited_on     = $prev_edited_on_qry[0]['images_editedon'];
						$prev_photo_flg     = $prev_edited_on_qry[0]['photo_flg'];
						$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
						$prev_id_flg        = $prev_edited_on_qry[0]['id_flg'];
						if ($prev_edited_on != date('Y-m-d')) {
							$this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N'), array('regid' => $this->session->userdata('mregid_applyexam')));
						}
					}
					
					//update an array for images
					$photo_flg = '';
					if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$photo_flg = 'N';} else { $photo_flg = $prev_photo_flg;}
					
					$signature_flg = '';
					if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$signature_flg = 'N';} else { $signature_flg = $prev_signature_flg;}
					
					if ($this->session->userdata['examinfo']['photo'] != '') {
						$update_array = array_merge($update_array, array("scannedphoto" => $this->session->userdata['examinfo']['photo']));
						$photo_name   = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'scannedphoto');
						$photoname    = $photo_name[0]['scannedphoto'];
						$photo_flg    = 'Y';
					}
					if ($this->session->userdata['examinfo']['signname'] != '') {
						$update_array  = array_merge($update_array, array("scannedsignaturephoto" => $this->session->userdata['examinfo']['signname']));
						$sing_name     = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'scannedsignaturephoto');
						$singname      = $sing_name[0]['scannedsignaturephoto'];
						$signature_flg = 'Y';
					}
					
					if ($prev_edited_on != date('Y-m-d') && ($photo_flg == 'Y' || $signature_flg == 'Y')) {
						$update_array['photo_flg']       = $photo_flg;
						$update_array['signature_flg']   = $signature_flg;
						$update_array['images_editedon'] = date('Y-m-d H:i:s');
						$update_array['images_editedby'] = 'Candidate';
					}
					
					$email_mbl_flg = 0;
					//check if email is unique
					$check_email = $this->master_model->getRecordCount('member_registration', array('email' => $this->session->userdata['examinfo']['email'], 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));
					if ($check_email == 0) {
						$update_array  = array_merge($update_array, array("email" => $this->session->userdata['examinfo']['email']));
						$email_mbl_flg = 1;
					}
					// check if mobile is unique
					$check_mobile = $this->master_model->getRecordCount('member_registration', array('mobile' => $this->session->userdata['examinfo']['mobile'], 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));
					
					if ($check_mobile == 0) {
						$update_array  = array_merge($update_array, array("mobile" => $this->session->userdata['examinfo']['mobile']));
						$email_mbl_flg = 1;
					}

					$sess_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
                    if($sess_exam_code == 101 || $sess_exam_code == 1046 || $sess_exam_code == 1047){
                        $update_array = array_merge($update_array, array("ippb_emp_id" => $this->session->userdata['examinfo']['ippb_emp_id']));
                        $update_array = array_merge($update_array, array("name_of_bank_bc" => $this->session->userdata['examinfo']['name_of_bank_bc']));
                        $update_array = array_merge($update_array, array("date_of_commenc_bc" => $this->session->userdata['examinfo']['date_of_commenc_bc'])); 
                        if ($this->session->userdata['examinfo']['bank_bc_id_card_filename'] != '') {
                            $update_array = array_merge($update_array, array("bank_bc_id_card" => $this->session->userdata['examinfo']['bank_bc_id_card_filename']));
                            $photo_name   = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('nmregid'), 'regnumber' => $this->session->userdata('nmregnumber')), 'bank_bc_id_card');
                            $bank_bc_id_card_filename = $photo_name[0]['bank_bc_id_card'];
                        }   
                    }

					if (count($update_array) > 0) {
						$edited = '';
						foreach ($update_array as $key => $val) {
							$edited .= strtoupper($key) . " = " . strtoupper($val) . " && ";
						}
						
						if ($email_mbl_flg == 1) {
							$update_array['editedon'] = date('Y-m-d H:i:s');
							$update_array['editedby'] = "Candidate";
						}
						
						$prevData  = array();
						$user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam'), 'isactive' => '1'));
						if (count($user_info)) {
							$prevData = $user_info[0];
						}
						
						$desc['updated_data'] = $update_array;
						$desc['old_data']     = $prevData;
						
						$this->master_model->updateRecord('member_registration', $update_array, array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')));
						
						log_profile_user($log_title = "Profile updated successfully", $edited, 'data', $this->session->userdata('mregid_applyexam'), $this->session->userdata('mregnumber_applyexam'));
						
						logactivity($log_title = "Member update profile during exam apply", $log_message = serialize($desc));
						
					}
					
					if ($this->config->item('exam_apply_gateway') == 'sbi') {
						redirect(base_url() . 'Applyexam/sbi_make_payment/');
						} else {
						redirect(base_url() . 'Applyexam/make_payment/');
					}
				}
				} else {
				redirect(base_url());
			}
		}
		
		// BillDesk payment gateway
		public function make_payment()
		{
			
			if (isset($_POST['processPayment']) && $_POST['processPayment']) {
				
				
				$regno      = $this->session->userdata('mregnumber_applyexam'); //$this->session->userdata('regnumber');
				$MerchantID = $this->config->item('bd_MerchantID');
				$SecurityID = $this->config->item('bd_SecurityID');
				
				$checksum_key = $this->config->item('bd_ChecksumKey');
				
				$pg_return_url = base_url() . "Applyexam/pg_response";
				
				//$amount = trim($this->session->userdata['examinfo']['fee']); // Exam fee//$this->config->item('dup_id_card_fee');
				$amount = '1';
				// Create transaction
				$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "billdesk",
				'date'             => date('Y-m-d h:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
				'description'      => $this->session->userdata['examinfo']['exname'], //"Duplicate ID card request. Reason:".$this->session->userdata('desc'),
				'status'           => '2',
				'exam_code'        => base64_decode($this->session->userdata['examinfo']['excd']),
				);
				
				$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
				//$pt_id = "DP9878280".$pt_id;
				
				$update_data = array(
				'receipt_no' => $pt_id,
				);
				//print_r($update_data); exit;
				$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
				$MerchantOrderNo    = "IIBFEXAM" . $pt_id; //"DP98782802";  // TO DO : need to change
				$MerchantCustomerID = $regno;
				
				$custom_field        = "iibfexam";
				$data["pg_form_url"] = $this->config->item('bd_pg_form_url'); // SBI ePay form URL
				/*
					Format:            requestparameter=MerchantID|CustomerID|NA|TxnAmount|NA|NA|NA|CurrencyType|NA|TypeField1|SecurityID|NA|NA|TypeField2|AdditionalInfo1|AdditionalInfo2|AdditionalInfo3|AdditionalInfo4|AdditionalInfo5|NA|NA|RU|Checksum
					Ex.
					requestparameter=IIBF|2138759|NA|500.00|NA|NA|NA|INR|NA|R|iibf|NA|NA|F|iibfexam|500081141|148201701|NA|NA|NA|NA|http://abc.somedomain.com|2387462372
				*/
				$member_exam_id   = $this->session->userdata['examinfo']['insdet_id'];
				$requestparameter = $MerchantID . "|" . $MerchantOrderNo . "|NA|" . $amount . "|NA|NA|NA|INR|NA|R|" . $SecurityID . "|NA|NA|F|" . $custom_field . "|" . $MerchantCustomerID . "|" . $member_exam_id . "|NA|NA|NA|NA|" . $pg_return_url;
				
				// Generate checksum for request parameter
				$req_param        = $requestparameter . "|" . $checksum_key;
				$checksum         = crc32($req_param);
				$requestparameter = $requestparameter . "|" . $checksum;
				$data["msg"]      = $requestparameter;
				
				$this->load->view('pg_bd_form', $data);
				} else {
				$this->load->view('pg_bd/make_payment_page');
			}
		}
		
		public function pg_response()
		{
			//$_REQUEST['msg'] = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";
			
			//    $_REQUEST['msg'] = "IIBF|2138195|HHMP4897894246|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 12:55:48|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|1435616898";
			
			if (isset($_REQUEST['msg'])) {
				//echo "<pre>";
				//print_r($_REQUEST);
				//echo "<BR> Response : ".$_REQUEST['msg'];
				
				// validate checksum
				preg_match_all("/(.*)\|([0-9]*)$/", $_REQUEST['msg'], $result);
				//print_r($result);
				$res_checksum         = $result[2][0];
				$msg_without_Checksum = $result[1][0];
				
				//$common_string = "sRKUUgdDrMGL";
				$checksum_key = $this->config->item('bd_ChecksumKey');
				$string_new   = $msg_without_Checksum . "|" . $checksum_key;
				$checksum     = crc32($string_new);
				
				$pg_res = explode("|", $msg_without_Checksum); //print_r($pg_res); exit;
				
				// add payment responce in log
				$pg_response = "msg=" . $_REQUEST['msg'];
				$this->log_model->logtransaction("billdesk", $pg_response, $pg_res[14]);
				
				if ($res_checksum == $checksum) {
					if ($pg_res[16] == "iibfexam") {
						$MerchantOrderNo = filter_var($pg_res[1], FILTER_SANITIZE_NUMBER_INT); //$responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
						$transaction_no  = $pg_res[2];
						$payment_status  = 2;
						switch ($pg_res[14]) {
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
						
						if ($payment_status == 1) {
							
							// Handle transaction success case
							$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
							if (count($get_user_regnum) > 0) {
								$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber,usrpassword,email');
							}
							
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));
							
							$update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $pg_res[24], 'auth_code' => $pg_res[14]);
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
							
							//Query to get user details
							$this->db->join('state_master', 'state_master.state_code=member_registration.state');
							$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
							$result = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
							
							//Query to get exam details
							$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
							$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code');
							$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							
							if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';} else { $mode = '';}
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
							$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
							//Query to get Medium
							$this->db->where('exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
							$this->db->where('exam_period', $exam_info[0]['exam_period']);
							$this->db->where('medium_code', $exam_info[0]['exam_medium']);
							$this->db->where('medium_delete', '0');
							$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
							
							//Query to get Payment details
							$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $this->session->userdata('mregnumber_applyexam')), 'transaction_no,date,amount');
							
							$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'apply_exam_transaction_success'));
							$newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
							$newstring2       = str_replace("#REG_NUM#", "" . $this->session->userdata('mregnumber_applyexam') . "", $newstring1);
							$newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
							$newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
							$newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
							$newstring6       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
							$newstring7       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
							$newstring8       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
							$newstring9       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
							$newstring10      = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
							$newstring11      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
							$newstring12      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
							$newstring13      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
							$newstring14      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
							$newstring15      = str_replace("#INSTITUDE#", "" . $result[0]['name'] . "", $newstring14);
							$newstring16      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring15);
							$newstring17      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring16);
							$newstring18      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring17);
							$newstring19      = str_replace("#MODE#", "" . $mode . "", $newstring18);
							$newstring20      = str_replace("#PLACE_OF_WORK#", "" . $result[0]['office'] . "", $newstring19);
							$newstring21      = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring20);
							$final_str        = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring21);
							
							$info_arr = array('to' => $result[0]['email'],
							'from'                 => $emailerstr[0]['from'],
							'subject'              => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('mregnumber_applyexam'),
							'message'              => $final_str,
							);
							
							$user_data = array('regid' => $this->session->userdata('mregid_applyexam'),
							'regnumber'                => $this->session->userdata('mregnumber_applyexam'),
							'firstname'                => $this->session->userdata('mfirstname_applyexam'),
							'middlename'               => $this->session->userdata('mmiddlename_applyexam'),
							'lastname'                 => $this->session->userdata('mlastname_applyexam'),
							'memtype'                  => $this->session->userdata('memtype'),
							'timer'                    => $this->session->userdata('mtimer_applyexam'),
							'password'                 => $this->session->userdata('mpassword_applyexam'));
							$this->session->set_userdata($user_data);
							//To Do---Transaction email to user    currently we using failure emailer
							if ($this->Emailsending->mailsend($info_arr)) {
								redirect(base_url() . 'Applyexam/details/' . base64_encode($MerchantOrderNo) . '/' . $this->session->userdata['examinfo']['excd']);
							}
							} else if ($payment_status == 0) {
							// Handle transaction fail case
							$update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $pg_res[24], 'auth_code' => $pg_res[14]);
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
							
							$result = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'firstname,middlename,lastname,email,mobile');
							
							//Query to get Payment details
							$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $this->session->userdata('mregnumber_applyexam')), 'transaction_no,date,amount');
							
							$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
							$newstring1       = str_replace("#application_num#", "" . $this->session->userdata('mregnumber_applyexam') . "", $emailerstr[0]['emailer_text']);
							$newstring2       = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
							$newstring3       = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
							$final_str        = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
							
							$info_arr = array('to' => $result[0]['email'],
							'from'                 => $emailerstr[0]['from'],
							'subject'              => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('mregnumber_applyexam'),
							'message'              => $final_str,
							);
							//To Do---Transaction email to user    currently we using failure emailer
							if ($this->Emailsending->mailsend($info_arr)) {
								redirect(base_url() . 'Applyexam/fail/' . base64_encode($MerchantOrderNo));
							}
							
							//echo 'transaction fail';exit;
						}
					}
					///echo "<BR>Checksum validated successfully<br>";
					//echo "SUCCESS:".$pg_res[2];
					} else {
					//echo "<BR>Checksum validation unsuccessful<br>";
					//echo "INVALID:".$pg_res[2];
				}
				// Redirect to success/failure
				} else {
				die("Please try again...");
			}
		}
		
		##------------------Exam appky with SBI Payment Gate-way(PRAFULL)---------------##
		public function sbi_make_payment()
		{
			$exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
			/*if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1009){
				die('Please wait for 1 hour');
			}*/
			
			$this->chk_session->Mem_checklogin_external_user();
			$cgst_rate           = $sgst_rate           = $igst_rate           = $tax_type           = '';
			$cgst_amt            = $sgst_amt            = $igst_amt            = '';
			$cs_total            = $igst_total            = '';
			$getstate            = $getcenter            = $getfees            = array();
			$valcookie           = applyexam_get_cookie();
			$total_el_amount     = 0;
			$el_subject_cnt      = 0;
			$total_elearning_amt = 0;
			## New elarning columns code
			$total_el_base_amount = 0;
			$total_el_gst_amount  = 0;
			$total_el_cgst_amount = 0;
			$total_el_sgst_amount = 0;
			$total_el_igst_amount = 0;
			if ($valcookie) {
				redirect('http://iibf.org.in/');
			}
			if (isset($_POST['processPayment']) && $_POST['processPayment']) {

				$checkpayment = $this->master_model->getRecords('payment_transaction', array('pay_type' => 2,'ref_id' => $this->session->userdata['examinfo']['insdet_id']));
                //echo $this->db->last_query();exit; count($checkpayment);exit;//
                if (count($checkpayment) > 0) {
                   $this->session->set_flashdata('error', 'Wait your transaction is under process!..');
                   redirect(base_url() . 'Applyexam/comApplication');
                } //>>priyanka d >> prevent double payment againts same exam application >> 19-nov-24
                
				//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
				$pg_name = 'billdesk'; //$this->input->post('pg_name');
				
				$checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $this->session->userdata('mregnumber_applyexam'), 'status' => '2', 'pay_type' => '2'), '', array('id' => 'DESC'));
				if (count($checkpayment) > 0) {
					$endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
					$current_time = date("Y-m-d H:i:s");
					if (strtotime($current_time) <= strtotime($endTime)) {
						$this->session->set_flashdata('error', 'Wait your transaction is under process!.');
						redirect(base_url() . 'Applyexam/comApplication');
					}
				}
				
				$sub_flag = 1;
				############check capacity is full or not
				$subject_arr = $this->session->userdata['examinfo']['subject_arr'];
				if (count($subject_arr) > 0) {
					$msg          = '';
					$sub_capacity = 1;
					foreach ($subject_arr as $k => $v) {
						foreach ($subject_arr as $j => $val) {
							if ($k != $j) {
								//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
								if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
									$sub_flag = 0;
								}
							}
						}
						$capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
						if ($capacity <= 1) {
							$total_admit_count=getLastseat(base64_decode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['selCenterName'],$v['venue'],$v['date'],$v['session_time']);
							if($total_admit_count > 0)
							{
								$msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
								$msg =$msg .' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
							}
						}
						if ($msg != '') {
							$this->session->set_flashdata('error', $msg);
							redirect(base_url() . 'Applyexam/comApplication');
						}
					}
				}
				if ($sub_flag == 0) {
					$this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
					redirect(base_url() . 'Applyexam/comApplication');
				}
				
				$regno = $this->session->userdata('mregnumber_applyexam'); //$this->session->userdata('regnumber');
				include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key          = $this->config->item('sbi_m_key');
				$merchIdVal   = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				
				$pg_success_url = base_url() . "Applyexam/sbitranssuccess";
				$pg_fail_url    = base_url() . "Applyexam/sbitransfail";
				
				if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65 || base64_decode($this->session->userdata['examinfo']['excd']) == 11)) {
					if ($this->session->userdata['examinfo']['el_subject'] == 'N') {
						$el_subject_cnt = 0;
						} else {
						$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
					}
					} else {
					$el_subject_cnt = 0;
				}
				
				if ($this->config->item('sb_test_mode')) {
					$amount = $this->config->item('exam_apply_fee');
					} else {
					//$amount=$this->session->userdata['examinfo']['fee'];
					$amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
					
					if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65 || base64_decode($this->session->userdata['examinfo']['excd']) == 11)) {
						
						$el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
						
						//$total_elearning_amt = $el_amount * $el_subject_cnt;
						//$amount              = $amount + $total_elearning_amt;
						## New elarning columns code
						$total_el_base_amount = $el_subject_cnt;
						$total_el_cgst_amount = $el_subject_cnt;
						$total_el_sgst_amount = $el_subject_cnt;
						$total_el_igst_amount = $el_subject_cnt;
					}
				}
				
				if ($amount == 0 || $amount == '') {
					$this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!!');
					redirect(base_url() . 'Applyexam/comApplication/');
				}
				//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
				
				//Ordinary member Apply exam
				//    Ref1 = orderid
				//    Ref2 = iibfexam
				//    Ref3 = member reg num
				//    Ref4 = exam_code + exam year + exam month ex (101201602)
				$yearmonth = $this->master_model->getRecords('misc_master', array('exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'exam_period' => $this->session->userdata['examinfo']['eprid']), 'exam_month');
				
				if (base64_decode($this->session->userdata['examinfo']['excd']) == 340 || base64_decode($this->session->userdata['examinfo']['excd']) == 3400) {
					$exam_code = 34;
					} else if (base64_decode($this->session->userdata['examinfo']['excd']) == 580 || base64_decode($this->session->userdata['examinfo']['excd']) == 5800) {
					$exam_code = 58;
					} else if (base64_decode($this->session->userdata['examinfo']['excd']) == 1600 || base64_decode($this->session->userdata['examinfo']['excd']) == 16000) {
					$exam_code = 160;
					} else if (base64_decode($this->session->userdata['examinfo']['excd']) == 200) {
					$exam_code = 20;
					} else if (base64_decode($this->session->userdata['examinfo']['excd']) == 1770 || base64_decode($this->session->userdata['examinfo']['excd']) == 17700) {
					$exam_code = 177;
					} else if (base64_decode($this->session->userdata['examinfo']['excd']) == 1750) {
					$exam_code = 175;
					} else {
					$exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
				}
				$ref4 = ($exam_code) . $yearmonth[0]['exam_month'];
				
				$gateway = 'sbiepay';
				if ($pg_name == 'billdesk') {
					$gateway = 'billdesk';
				}
				
				$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => $gateway,
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
				'description'      => $this->session->userdata['examinfo']['exname'], //"Duplicate ID card request. Reason:".$this->session->userdata('desc'),
				'status'           => '2',
				'exam_code'        => base64_decode($this->session->userdata['examinfo']['excd']),
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'          => 'IIBF_EXAM_O',
				//'pg_other_details'=>$custom_field
				);
				
				$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
				
				$MerchantOrderNo = sbi_exam_order_id($pt_id);
				
				// payment gateway custom fields -
				$custom_field          = $MerchantOrderNo . "^iibfexam^" . $this->session->userdata('mregnumber_applyexam') . "^" . $ref4;
				$custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $this->session->userdata('mregnumber_applyexam') . "-" . $ref4;
				
				// update receipt no. in payment transaction -
				$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
				$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
				
				//set invoice details(Prafull)
				$getcenter = $this->master_model->getRecords('center_master', array('exam_name' => base64_decode($this->session->userdata['examinfo']['excd']), 'center_code' => $this->session->userdata['examinfo']['txtCenterCode'], 'exam_period' => $this->session->userdata['examinfo']['eprid'], 'center_delete' => '0'));
				if (count($getcenter) > 0) {
					//get state code,state name,state number.
					$getstate = $this->master_model->getRecords('state_master', array('state_code' => $getcenter[0]['state_code'], 'state_delete' => '0'));
					
					//call to helper (fee_helper)
					$getfees = getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
					
				}
				if ($getcenter[0]['state_code'] == 'MAH') {
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					
					if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {
						//set an total amount
						if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65  || base64_decode($this->session->userdata['examinfo']['excd']) == 11)) {
							$cs_total        = $amount;
							$total_el_amount = $total_elearning_amt;
							$amount_base     = $getfees[0]['fee_amount'];
							$cgst_amt        = $getfees[0]['cgst_amt'];
							$sgst_amt        = $getfees[0]['sgst_amt'];
							## New elarning columns code
							$total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
							$total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
							$total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
							$total_el_gst_amount  = $total_el_cgst_amount + $total_el_sgst_amount;
							} else {
							$cs_total        = $getfees[0]['elearning_cs_amt_total'];
							$total_el_amount = 0;
							$amount_base     = $getfees[0]['elearning_fee_amt'];
							
							$cgst_amt             = $getfees[0]['elearning_cgst_amt'];
							$sgst_amt             = $getfees[0]['elearning_sgst_amt'];
							$total_el_base_amount = 0;
							$total_el_gst_amount  = 0;
						}
						} else {
						//set an amount as per rate
						$cgst_amt = $getfees[0]['cgst_amt'];
						$sgst_amt = $getfees[0]['sgst_amt'];
						//set an total amount
						$cs_total             = $getfees[0]['cs_tot'];
						$amount_base          = $getfees[0]['fee_amount'];
						$total_el_base_amount = 0;
						$total_el_gst_amount  = 0;
					}
					$tax_type = 'Intra';
					} else {
					if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {
						
						$igst_rate = $this->config->item('igst_rate');
						if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65 || base64_decode($this->session->userdata['examinfo']['excd']) == 11)) {
							$igst_total      = $amount;
							$total_el_amount = $total_elearning_amt;
							$amount_base     = $getfees[0]['fee_amount'];
							$igst_amt        = $getfees[0]['igst_amt'];
							## New elarning columns code
							$total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
							$total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
							$total_el_gst_amount  = $total_el_igst_amount;
							} else {
							$igst_total           = $getfees[0]['elearning_igst_amt_total'];
							$total_el_amount      = 0;
							$amount_base          = $getfees[0]['elearning_fee_amt'];
							$igst_amt             = $getfees[0]['elearning_igst_amt'];
							$total_el_base_amount = 0;
							$total_el_gst_amount  = 0;
						}
						} else {
						
						$igst_rate   = $this->config->item('igst_rate');
						$igst_amt    = $getfees[0]['igst_amt'];
						$igst_total  = $getfees[0]['igst_tot'];
						$amount_base = $getfees[0]['fee_amount'];
						## Code added on 11 Oct 2021
						$cgst_rate            = $cgst_amt            = $sgst_rate            = $sgst_amt            = $cs_total            = '';
						$total_el_base_amount = 0;
						$total_el_gst_amount  = 0;
					}
					$tax_type = 'Inter';
				}
				if ($getstate[0]['exempt'] == 'E') {
					$cgst_rate = $sgst_rate = $igst_rate = '';
					$cgst_amt  = $sgst_amt  = $igst_amt  = '';
				}
				
				$gst_no = '0';
				/*if($this->session->userdata['examinfo']['gstin_no']!='')
					{
					$gst_no=$this->session->userdata['examinfo']['gstin_no'];
				}*/
				## Code added on 11 Oct 2021
				$fee_details = array('state' => $getcenter[0]['state_code'], 'fee_amt' => $amount_base,
				'total_el_amount'            => $total_el_amount,
				'cgst_rate'                  => $cgst_rate,
				'cgst_amt'                   => $cgst_amt,
				'sgst_rate'                  => $sgst_rate,
				'sgst_amt'                   => $sgst_amt,
				'igst_rate'                  => $igst_rate,
				'igst_amt'                   => $igst_amt,
				'cs_total'                   => $cs_total,
				'igst_total'                 => $igst_total);
				$log_title   = "Exam invoice data from applyexam cntrlr before insert array";
				$log_message = serialize($fee_details);
				$rId         = $this->session->userdata('mregnumber_applyexam');
				$regNo       = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				$invoice_insert_array = array('pay_txn_id' => $pt_id,
				'receipt_no'                               => $MerchantOrderNo,
				'exam_code'                                => base64_decode($this->session->userdata['examinfo']['excd']),
				'center_code'                              => $getcenter[0]['center_code'],
				'center_name'                              => $getcenter[0]['center_name'],
				'state_of_center'                          => $getcenter[0]['state_code'],
				'member_no'                                => $this->session->userdata('mregnumber_applyexam'),
				'app_type'                                 => 'O',
				'exam_period'                              => $this->session->userdata['examinfo']['eprid'],
				'service_code'                             => $this->config->item('exam_service_code'),
				'qty'                                      => '1',
				'state_code'                               => $getstate[0]['state_no'],
				'state_name'                               => $getstate[0]['state_name'],
				'tax_type'                                 => $tax_type,
				'fee_amt'                                  => $amount_base,
				'total_el_amount'                          => $total_el_amount,
				'total_el_base_amount'                     => $total_el_base_amount,
				'total_el_gst_amount'                      => $total_el_gst_amount,
				'cgst_rate'                                => $cgst_rate,
				'cgst_amt'                                 => $cgst_amt,
				'sgst_rate'                                => $sgst_rate,
				'sgst_amt'                                 => $sgst_amt,
				'igst_rate'                                => $igst_rate,
				'igst_amt'                                 => $igst_amt,
				'cs_total'                                 => $cs_total,
				'igst_total'                               => $igst_total,
				'exempt'                                   => $getstate[0]['exempt'],
				'created_on'                               => date('Y-m-d H:i:s'));
				$inser_id    = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true);
				$log_title   = "Exam invoice data from applyexam cntrlr inser_id = '" . $inser_id . "'";
				$log_message = serialize($invoice_insert_array);
				$rId         = $this->session->userdata('mregnumber_applyexam');
				$regNo       = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				//insert into admit card table
				//################get userdata###########
				$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'isactive' => '1'));
				
				//get associate institute details
				$institute_id     = '';
				$institution_name = '';
				if ($user_info[0]['associatedinstitute'] != '') {
					$institution_master = $this->master_model->getRecords('institution_master', array('institude_id' => $user_info[0]['associatedinstitute']));
					if (count($institution_master) > 0) {
						$institute_id     = $institution_master[0]['institude_id'];
						$institution_name = $institution_master[0]['name'];
					}
				}
				//############check Gender########
				if ($user_info[0]['gender'] == 'male') {$gender = 'M';} else { $gender = 'F';}
				//########prepare user name########
				$username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
				$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
				
				//###########get State##########
				$states     = $this->master_model->getRecords('state_master', array('state_code' => $user_info[0]['state'], 'state_delete' => '0'));
				$state_name = '';
				if (count($states) > 0) {
					$state_name = $states[0]['state_name'];
				}
				//##############Examination Mode###########
				if ($this->session->userdata['examinfo']['optmode'] == 'ON') {
					$mode = 'Online';
					} else {
					$mode = 'Offline';
				}
				
				$sub_el_flg = 'N';
				
				if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
					foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
						$this->db->group_by('subject_code');
						$compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'subject_delete' => '0', 'exam_period' => $this->session->userdata['examinfo']['eprid'], 'subject_code' => $k), 'subject_description');
						$get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v['venue'], 'exam_date' => $v['date'], 'session_time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
						
						if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
							
							if ($this->session->userdata['examinfo']['el_subject'] != 'N') {
								if (array_key_exists($k, $this->session->userdata['examinfo']['el_subject'])) {
									$sub_el_flg = 'Y';
									} else {
									$sub_el_flg = 'N';
								}
							}
							
						}
						else{
                            if($this->session->userdata['examinfo']['elearning_flag'] == "Y"){
                                if (isset($this->session->userdata['examinfo']['el_subject']) && $this->session->userdata['examinfo']['el_subject'] != 'N') {
                                    if (array_key_exists($k, $this->session->userdata['examinfo']['el_subject'])) {
                                        $sub_el_flg = 'Y';
                                    }else{
                                        $sub_el_flg = 'N';
                                    }
                                } //priyanka d >>27-june-24 >> this field was saving as 0 even e-learning selected
                            }
                        }
						$check_last_seat_available=preventUser( base64_decode($this->session->userdata['examinfo']['excd']),$getcenter[0]['center_code'],$v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['eprid']);
						if($check_last_seat_available <= 0)
						{
							$msg ='There is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
							$this->session->set_flashdata('error', $msg);
							redirect(base_url() . 'Applyexam/comApplication');    
						}
						
						$admitcard_insert_array = array(
						'mem_exam_id'      => $this->session->userdata['examinfo']['insdet_id'],
						'center_code'      => $getcenter[0]['center_code'],
						'center_name'      => $getcenter[0]['center_name'],
						'mem_type'         => $this->session->userdata('memtype'),
						'mem_mem_no'       => $this->session->userdata('mregnumber_applyexam'),
						'g_1'              => $gender,
						'mam_nam_1'        => $userfinalstrname,
						'mem_adr_1'        => $user_info[0]['address1'],
						'mem_adr_2'        => $user_info[0]['address2'],
						'mem_adr_3'        => $user_info[0]['address3'],
						'mem_adr_4'        => $user_info[0]['address4'],
						'mem_adr_5'        => $user_info[0]['district'],
						'mem_adr_6'        => $user_info[0]['city'],
						'mem_pin_cd'       => $user_info[0]['pincode'],
						'state'            => $state_name,
						'exm_cd'           => base64_decode($this->session->userdata['examinfo']['excd']),
						'exm_prd'          => $this->session->userdata['examinfo']['eprid'],
						'sub_cd '          => $k,
						'sub_dsc'          => $compulsory_subjects[0]['subject_description'],
						'sub_el_flg'       => $sub_el_flg,
						'm_1'              => $this->session->userdata['examinfo']['medium'],
						'inscd'            => $institute_id,
						'insname'          => $institution_name,
						'venueid'          => $get_subject_details[0]['venue_code'],
						'venue_name'       => $get_subject_details[0]['venue_name'],
						'venueadd1'        => $get_subject_details[0]['venue_addr1'],
						'venueadd2'        => $get_subject_details[0]['venue_addr2'],
						'venueadd3'        => $get_subject_details[0]['venue_addr3'],
						'venueadd4'        => $get_subject_details[0]['venue_addr4'],
						'venueadd5'        => $get_subject_details[0]['venue_addr5'],
						'venpin'           => $get_subject_details[0]['venue_pincode'],
						'exam_date'        => $get_subject_details[0]['exam_date'],
						'time'             => $get_subject_details[0]['session_time'],
						'mode'             => $mode,
						'scribe_flag'      => $this->session->userdata['examinfo']['scribe_flag'],
						'scribe_flag_PwBD' => $this->session->userdata['examinfo']['scribe_flag_d'],
						'disability'       => $this->session->userdata['examinfo']['disability_value'],
						'sub_disability'   => $this->session->userdata['examinfo']['Sub_menue_disability'],
						'vendor_code'      => $get_subject_details[0]['vendor_code'],
						'remark'           => 2,
						'created_on'       => date('Y-m-d H:i:s'));
						//echo '<pre>';
						//print_r($admitcard_insert_array);
						$inser_id    = $this->master_model->insertRecord('admit_card_details', $admitcard_insert_array);
						$log_title   = "Admit card data from Applyexam cntrlr";
						$log_message = serialize($admitcard_insert_array);
						$rId         = $this->session->userdata('mregnumber_applyexam');
						$regNo       = $this->session->userdata('mregnumber_applyexam');
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
					
					##code added to verify if master tables has the raw entries - 2021-10-06
					$marchant_id = $MerchantOrderNo;
					$exam_code   = base64_decode($this->session->userdata['examinfo']['excd']);
					$member_no   = $this->session->userdata('mregnumber_applyexam');
					$ref_id      = $this->session->userdata['examinfo']['insdet_id'];
					
					$payment_raw = $this->master_model->getRecordCount('payment_transaction', array('receipt_no' => $marchant_id, 'exam_code' => $exam_code, 'member_regnumber' => $member_no));
					
					$exam_invoice_raw = $this->master_model->getRecordCount('exam_invoice', array('receipt_no' => $marchant_id, 'exam_code' => $exam_code, 'member_no' => $member_no));
					
					$admit_card_raw = $this->master_model->getRecordCount('admit_card_details', array('mem_exam_id' => $ref_id, 'exm_cd' => $exam_code, 'mem_mem_no' => $member_no));
					
					if ($payment_raw == 0 || $exam_invoice_raw == 0 || $admit_card_raw == 0) {
						$this->session->set_flashdata('error', 'Something went wrong!!');
						redirect(base_url() . 'Applyexam/comApplication');
					}
					
					############check for missing subject############
					$this->db->where('app_category !=', 'R');
					$this->db->where('app_category !=', '');
					$this->db->where('exam_status !=', 'V');
					$this->db->where('exam_status !=', 'P');
					$this->db->where('exam_status !=', 'D');
					
					/*$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'member_no' => $this->session->userdata('mregnumber_applyexam'), 'eligible_period' => $this->session->userdata['examinfo']['eprid'], 'institute_id' => '0'));*/
					
					$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'member_no' => $this->session->userdata('mregnumber_applyexam'), 'eligible_period' => $this->session->userdata['examinfo']['eprid']));
					
					if($this->get_client_ip1()=='115.124.115.75') {
						//  echo $this->db->last_query();
					}
					$treatAsFresher=0; //priyanka d- 24-01-23
					if(count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category'] == 'R')
					$treatAsFresher=1;
					else if(isset($this->session->userdata['examinfo']['optval']) && $this->session->userdata['examinfo']['optval']==1)
					$treatAsFresher=1;
					
					if ($treatAsFresher==1) { // //priyanka d- 24-01-23
						if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
							$count = 0;
							foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
								$check_admit_card_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata('mregnumber_applyexam'), 'exm_cd' => base64_decode($this->session->userdata['examinfo']['excd']), 'sub_cd' => $k, 'venueid' => $v['venue'], 'exam_date' => $v['date'], 'time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
								if (count($check_admit_card_details) > 0) {
									$count++;
								}
							}
						}
						if (count($this->session->userdata['examinfo']['subject_arr']) != $count) {
							$log_title   = "Fresh Member subject missing applyexam cntrlr";
							$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
							$rId         = $this->session->userdata('mregnumber_applyexam');
							$regNo       = $this->session->userdata('mregnumber_applyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							delete_cookie('examid');
							$this->session->set_flashdata('error', 'Something went wrong!!');
							redirect(base_url() . 'Applyexam/comApplication');
						}
						} else {
						$count = 0;
						if (count($check_eligibility_for_applied_exam) == count($this->session->userdata['examinfo']['subject_arr'])) {
							if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
								foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
									$check_admit_card_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata('mregnumber_applyexam'), 'exm_cd' => base64_decode($this->session->userdata['examinfo']['excd']), 'sub_cd' => $k, 'venueid' => $v['venue'], 'exam_date' => $v['date'], 'time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
									if (count($check_admit_card_details) > 0) {
										$count++;
									}
								}
							}
						}
						if($this->get_client_ip1()=='115.124.115.75') {
							/* echo'check_eligibility_for_applied_exam=<pre>';print_r($check_eligibility_for_applied_exam);
								echo'count=';print_r($count);
								exit;
							*/
						}
						if (count($check_eligibility_for_applied_exam) != $count) {
							$log_title   = "Existing Member subject missing  applyexam cntrlr";
							$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
							$rId         = $this->session->userdata('mregnumber_applyexam');
							$regNo       = $this->session->userdata('mregnumber_applyexam');
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							delete_cookie('examid');
							$this->session->set_flashdata('error', 'Something went wrong!!');
							redirect(base_url() . 'Applyexam/comApplication');
						}
					}
					############END check for missing subject############
					} else {
					if (base64_decode($this->session->userdata['examinfo']['excd']) != 101 && base64_decode($this->session->userdata['examinfo']['excd']) != 1046 && base64_decode($this->session->userdata['examinfo']['excd']) != 1047) {
						$this->session->set_flashdata('Error', 'Something went wrong!!');
						redirect(base_url() . 'Applyexam/comApplication');
					}
				}
				//set cookie for Apply Exam
				applyexam_set_cookie($this->session->userdata['examinfo']['insdet_id']);
				//$update_data = array('receipt_no' => $pt_id);
				//    $this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
				$MerchantCustomerID  = $regno;
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
				
				$EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
				
				//echo $EncryptTrans;
				//exit;
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				
				$EncryptTrans = $aes->encrypt($EncryptTrans);
				
				$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				if ($pg_name == 'sbi') {
					exit();
					$this->load->view('pg_sbi_form', $data);
					} elseif ($pg_name == 'billdesk') {
					
					$full_name_1 = strtolower(preg_replace('/\s+/', '_', $user_info[0]['firstname']));
					
					$new_invoice_id = $pt_id;
					$billdesk_res   = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'applyexam/handle_billdesk_response', '', '', '', $custom_field_billdesk);
					
					if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
						$data['bdorderid']      = $billdesk_res['bdorderid'];
						$data['token']          = $billdesk_res['token'];
						$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
						$data['returnUrl']      = $billdesk_res['returnUrl'];
						$this->load->view('pg_billdesk/pg_billdesk_form', $data);
						} else {
						$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
						redirect(base_url() . 'Applyexam/fail/' . base64_encode($MerchantOrderNo));
					}
				}
				} else {
				$data['show_billdesk_option_flag'] = 1;
				$this->load->view('pg_sbi/make_payment_page', $data);
			}
		}
		
		public function handle_billdesk_response()
		{
			
			$selected_invoice_id = $attachpath = $invoiceNumber = '';
			
			if (isset($_REQUEST['transaction_response'])) {
				
				$response_encode        = $_REQUEST['transaction_response'];
				$bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
				$responsedata           = $bd_response['payload'];
				$attachpath             = $invoiceNumber             = '';
				$MerchantOrderNo        = $responsedata['orderid'];
				$transaction_no         = $responsedata['transactionid'];
				$transaction_error_type = $responsedata['transaction_error_type'];
				$transaction_error_desc = $responsedata['transaction_error_desc'];
				$bankid                 = $responsedata['bankid'];
				$txn_process_type       = $responsedata['txn_process_type'];
				$merchIdVal             = $responsedata['mercid'];
				$Bank_Code              = $responsedata['bankid'];
				$encData                = $_REQUEST['transaction_response'];
				$auth_status            = $responsedata['auth_status'];
				
				$this->db->limit(1);
				$this->db->order_by('id', 'DESC');
				$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');
				
				$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
				
				if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum[0]['status'] == 2) {
					
					//Query to get user details
					$this->db->join('state_master', 'state_master.state_code=member_registration.state');
					$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
					$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,institution_master.name');
					
					//Query to get exam details
					$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
					
					########## Generate Admit card and allocate Seat #############
					if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
						$exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $get_user_regnum[0]['ref_id']));
						############check capacity is full or not ##########
						//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
						if (count($exam_admicard_details) > 0) {
							$msg          = '';
							$sub_flag     = 1;
							$sub_capacity = 1;
							foreach ($exam_admicard_details as $row) {
								$capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
								if ($capacity == 0) {
									#########get message if capacity is full##########
									#########get message if capacity is full##########
									$log_title   = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($exam_admicard_details);
									$rId         = $get_user_regnum[0]['ref_id'];
									$regNo       = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
									$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
									$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
									
									$this->refund_after_capacity_full->make_refund($MerchantOrderNo);
									
									redirect(base_url() . 'Applyexam/refund/' . base64_encode($MerchantOrderNo));
								}
							}
						}
						if (count($exam_admicard_details) > 0 && $capacity > 0) {
							
							######### payment Transaction ############
							$update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
							$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
							/* if($this->db->affected_rows())
							{ */
							$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');
							if ($get_payment_status[0]['status'] == 1) {
								if (count($get_user_regnum) > 0) {
									$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber,usrpassword,email');
								}
								
								// admit card gen
								$password = random_password();
								foreach ($exam_admicard_details as $row) {
									$get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $row['venueid'],
									'exam_date'                                                                               => $row['exam_date'],
									'session_time'                                                                            => $row['time'],
									'center_code'                                                                             => $row['center_code']));
									
									$admit_card_details = $this->master_model->getRecords('admit_card_details', array('venueid' => $row['venueid'], 'exam_date' => $row['exam_date'], 'time' => $row['time'], 'mem_exam_id' => $get_user_regnum[0]['ref_id'], 'sub_cd' => $row['sub_cd']));
									
									//echo $this->db->last_query().'<br>';
									$seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
									
									if ($seat_number != '') {
										$final_seat_number = $seat_number;
										$update_data       = array('pwd' => $password, 'seat_identification' => $final_seat_number, 'remark' => 1, 'modified_on' => date('Y-m-d H:i:s'));
										$this->master_model->updateRecord('admit_card_details', $update_data, array('admitcard_id' => $admit_card_details[0]['admitcard_id']));
										} else {
										$admit_card_details = $this->master_model->getRecords('admit_card_details', array('admitcard_id' => $admit_card_details[0]['admitcard_id'], 'remark' => 1));
										if (count($admit_card_details) > 0) {
											$log_title   = "Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
											$log_message = serialize($exam_admicard_details);
											$rId         = $admit_card_details[0]['admitcard_id'];
											$regNo       = $get_user_regnum[0]['member_regnumber'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);
											} else {
											$log_title   = "Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
											$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
											$rId         = $get_user_regnum[0]['member_regnumber'];
											$regNo       = $get_user_regnum[0]['member_regnumber'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);
											redirect(base_url() . 'Applyexam/refund/' . base64_encode($MerchantOrderNo));
										}
									}
								}
							}
						}
						#########update member Exam##############
						if ($get_payment_status[0]['status'] == 1) {
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));
							$log_title   = "Apply exam membver exam update query:" . $get_user_regnum[0]['member_regnumber'];
							$log_message = '';
							$rId         = $get_user_regnum[0]['ref_id'];
							$regNo       = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							} else {
							$log_title   = "Apply exam membver exam update query fail:" . $get_user_regnum[0]['member_regnumber'];
							$log_message = $get_user_regnum[0]['ref_id'];
							$rId         = $get_user_regnum[0]['ref_id'];
							$regNo       = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
						}
						
						if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';} else { $mode = '';}
						if ($exam_info[0]['examination_date'] != '0000-00-00') {
							$exam_period_date = date('d-M-Y', strtotime($exam_info[0]['examination_date']));
							} else {
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
							$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
						}
						//Query to get Medium
						$this->db->where('exam_code', $exam_info[0]['exam_code']);
						$this->db->where('exam_period', $exam_info[0]['exam_period']);
						$this->db->where('medium_code', $exam_info[0]['exam_medium']);
						$this->db->where('medium_delete', '0');
						$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
						
						$this->db->where('state_delete', '0');
						$states = $this->master_model->getRecords('state_master', array('state_code' => $exam_info[0]['state_place_of_work']), 'state_name');
						
						//Query to get Payment details
						$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount,id');
						
						$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
						if ($exam_info[0]['place_of_work'] != '' && $exam_info[0]['state_place_of_work'] != '' && $exam_info[0]['pin_code_place_of_work'] != '') {
							//get Elective Subeject name for CAIIB Exam
							if ($exam_info[0]['elected_sub_code'] != 0 && $exam_info[0]['elected_sub_code'] != '') {
								$elective_sub_name_arr = $this->master_model->getRecords('subject_master', array('subject_code' => $exam_info[0]['elected_sub_code'], 'subject_delete' => 0), 'subject_description');
								
								if (count($elective_sub_name_arr) > 0) {
									$elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
								}
							}
							
							$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_exam_enrollment_nofee_elective'));
							if($exam_info[0]['exam_code']==$this->config->item('examCodeJaiib') || $exam_info[0]['exam_code']==$this->config->item('examCodeCaiib')  ) {
								$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'jaiib_caiib_member_exam_enrollment_nofee_elective'));
							}
							$newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
							$newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
							$newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
							$newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
							$newstring5       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring4);
							$newstring6       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring5);
							$newstring7       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring6);
							$newstring8       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring7);
							$newstring9       = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring8);
							$newstring10      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring9);
							$newstring11      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring10);
							$newstring12      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring11);
							$newstring13      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring12);
							$newstring14      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring13);
							$newstring15      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring14);
							$newstring16      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring15);
							$newstring17      = str_replace("#ELECTIVE_SUB#", "" . $elective_subject_name . "", $newstring16);
							$newstring18      = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring17);
							$newstring19      = str_replace("#PLACE_OF_WORK#", "" . strtoupper($exam_info[0]['place_of_work']) . "", $newstring18);
							$newstring20      = str_replace("#STATE_PLACE_OF_WORK#", "" . $states[0]['state_name'] . "", $newstring19);
							$newstring21      = str_replace("#PINCODE_PLACE_OF_WORK#", "" . $exam_info[0]['pin_code_place_of_work'] . "", $newstring20);
							$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
							if (count($elern_msg_string) > 0) {
								foreach ($elern_msg_string as $row) {
									$arr_elern_msg_string[] = $row['exam_code'];
								}
								if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
									$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
									} else {
									$newstring22 = str_replace("#E-MSG#", '', $newstring21);
								}
								} else {
								$newstring22 = str_replace("#E-MSG#", '', $newstring21);
							}
							$final_str = str_replace("#MODE#", "" . $mode . "", $newstring22);
							} else {
							$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'apply_exam_transaction_success'));
							if($exam_info[0]['exam_code']==$this->config->item('examCodeJaiib') || $exam_info[0]['exam_code']==$this->config->item('examCodeCaiib')  ) {
								$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'jaiib_caiib_apply_exam_transaction_success'));
							}
							$newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
							$newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
							$newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
							$newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
							$newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
							$newstring6       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
							$newstring7       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
							$newstring8       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
							$newstring9       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
							$newstring10      = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
							$newstring11      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
							$newstring12      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
							$newstring13      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
							$newstring14      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
							$newstring15      = str_replace("#INSTITUDE#", "" . $result[0]['name'] . "", $newstring14);
							$newstring16      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring15);
							$newstring17      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring16);
							$newstring18      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring17);
							$newstring19      = str_replace("#MODE#", "" . $mode . "", $newstring18);
							$newstring20      = str_replace("#PLACE_OF_WORK#", "" . $result[0]['office'] . "", $newstring19);
							$newstring21      = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring20);
							$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
							if (count($elern_msg_string) > 0) {
								foreach ($elern_msg_string as $row) {
									$arr_elern_msg_string[] = $row['exam_code'];
								}
								if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
									$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
									} else {
									$newstring22 = str_replace("#E-MSG#", '', $newstring21);
								}
								} else {
								$newstring22 = str_replace("#E-MSG#", '', $newstring21);
							}
							$final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring22);

							if($exam_info[0]['exam_code']==78) {
                                $final_str.= '<a  style="margin: 31%;" href="https://iibf.esdsconnect.com/click_count?exam=78"><img  src="https://iibf.esdsconnect.com/uploads/78.jpg"></a>';
                            }
                            if($exam_info[0]['exam_code']==79) {
                                $final_str.= '<a  style="margin: 31%;" href="https://iibf.esdsconnect.com/click_count?exam=79"><img  src="https://iibf.esdsconnect.com/uploads/79.jpg"></a>';
                            }
                            if($exam_info[0]['exam_code']==1002) {
                                $final_str.= '<a  style="margin: 31%;" href="https://iibf.esdsconnect.com/click_count?exam=1002"><img  src="https://iibf.esdsconnect.com/uploads/1002.jpg"></a>';
                            }
                            if($exam_info[0]['exam_code']==1004) {
                                $final_str.= '<a  style="margin: 31%;" href="https://iibf.esdsconnect.com/click_count?exam=1004"><img  src="https://iibf.esdsconnect.com/uploads/1004.jpg"></a>';
                            }
                            if($exam_info[0]['exam_code']==1019) {
                                $final_str.= '<a  style="margin: 31%;" href="https://iibf.esdsconnect.com/click_count?exam=1019"><img  src="https://iibf.esdsconnect.com/uploads/1019.jpg"></a>';
                            }
						}
						$info_arr = array('to' => $result[0]['email'],
						'from'                 => $emailerstr[0]['from'],
						//  'cc'                    =>'iibfdevp@esds.co.in',
						'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
						'message'              => $final_str,
						);
						
						//get invoice
						$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));
						//echo $this->db->last_query();exit;
						if (count($getinvoice_number) > 0) {
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
							$invoiceNumber = '';
							if ($get_payment_status[0]['status'] == 1 && $capacity > 0) {
								$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
								
								$log_title   = "Apply exam exam_invoice number generate:" . $getinvoice_number[0]['invoice_id'];
								$log_message = '';
								$rId         = $get_user_regnum[0]['ref_id'];
								$regNo       = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								} else {
								
								$log_title   = "Apply exam exam_invoice number generate fail:" . $getinvoice_number[0]['invoice_id'];
								$log_message = $getinvoice_number[0]['invoice_id'];
								$rId         = $get_user_regnum[0]['ref_id'];
								$regNo       = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
							}
							
							if ($invoiceNumber) {
								$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
							}
							//}
							
							if ($get_payment_status[0]['status'] == 1) {
								$update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
								$this->db->where('pay_txn_id', $payment_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
								$attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
								
								$log_title   = "Apply exam exam_invoice update query:" . $getinvoice_number[0]['invoice_id'];
								$log_message = '';
								$rId         = $get_user_regnum[0]['ref_id'];
								$regNo       = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								} else {
								
								$log_title   = "Apply exam exam_invoice update query Fail:" . $getinvoice_number[0]['invoice_id'];
								$log_message = $get_user_regnum[0]['ref_id'];
								$rId         = $get_user_regnum[0]['ref_id'];
								$regNo       = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
							}
							
							$update_data_me = array('pay_status' => '1');
							$this->master_model->updateRecord('member_exam', $update_data_me, array('id' => $get_user_regnum[0]['ref_id']));
							
						}
						##############Get Admit card#############
						$admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
						
						if ($attachpath != '') {
							$files          = array($attachpath, $admitcard_pdf);
							$skipped_admitcard_examcodes = $this->config->item('skippedAdmitCardForExams');
							//priyanka d >>27-dec-24 >> by default selecting venue for jaiib/caiiib as we don't have to create admitcard from filled form now >> exam_cd
                            if($exam_info[0]['exam_code']!=null && in_array($exam_info[0]['exam_code'],$skipped_admitcard_examcodes)) {
                                $files = array(
                                    $attachpath,
                                   
                                );
                            }
							
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring  = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
							$sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
							$sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
							
							
							//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'LSy_cIwGg', $exam_info[0]['exam_code']);
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'],$emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']);// Added on 04 Oct 2023 by Sagar M & Anil S
							
							
							$this->Emailsending->mailsend_attch($info_arr, $files);
							
							//$this->Emailsending->mailsend($info_arr);
						}
						} else if ($exam_info[0]['exam_code'] == 101 && $exam_info[0]['exam_code'] == 1046 && $exam_info[0]['exam_code'] == 1047) {
						
						$update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');
						
						$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
						if ($this->db->affected_rows()) {
							######update member_exam######
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));
							if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';} else { $mode = '';}
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
							$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
							//Query to get Medium
							$this->db->where('exam_code', $exam_info[0]['exam_code']);
							$this->db->where('exam_period', $exam_info[0]['exam_period']);
							$this->db->where('medium_code', $exam_info[0]['exam_medium']);
							$this->db->where('medium_delete', '0');
							$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
							//Query to get Payment details
							$payment_info     = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount,id');
							$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$decpass          = $aes->decrypt(trim($result[0]['usrpassword']));
							$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'non_member_apply_exam_transaction_success'));

							if(in_array($exam_info[0]['exam_code'], array(1046,1047))){
	                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'oldbcbf_non_member_apply_exam_transaction_success'));
	                        }

							$newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
							$newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
							$newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
							$newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
							$newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
							$newstring6       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
							$newstring7       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
							$newstring8       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
							$newstring9       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
							$newstring10      = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
							$newstring11      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
							$newstring12      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
							$newstring13      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
							$newstring14      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
							$newstring15      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
							$newstring16      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
							$newstring17      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
							$newstring18      = str_replace("#MODE#", "" . $mode . "", $newstring17);
							$newstring19      = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring18);
							$newstring20      = str_replace("#PASS#", "" . $decpass . "", $newstring19);
							$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
							if (count($elern_msg_string) > 0) {
								foreach ($elern_msg_string as $row) {
									$arr_elern_msg_string[] = $row['exam_code'];
								}
								if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
									$newstring21 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring20);
									} else {
									$newstring21 = str_replace("#E-MSG#", '', $newstring20);
								}
								} else {
								$newstring21 = str_replace("#E-MSG#", '', $newstring20);
							}

							if(in_array($exam_info[0]['exam_code'], array(1046,1047))){
	                            $subject_master_details = $this->master_model->getRecords('subject_master', array('exam_code' => $exam_info[0]['exam_code'], 'exam_period' => $exam_info[0]['exam_period'], 'group_code' => 'C'), 'exam_date'); 
	                            if($subject_master_details[0]['exam_date'] != "" && $subject_master_details[0]['exam_date'] != "0000-00-00"){
	                              $exam_date_full = date("d-M-Y", strtotime($subject_master_details[0]['exam_date'])); 
	                              $newstring21 = str_replace("#EXAM_DATE_FULL#", "" . $exam_date_full . "", $newstring21);
	                            } 
	                        }

							$final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring21);
							$info_arr  = array('to' => $result[0]['email'],
							'from'                  => $emailerstr[0]['from'],
							'subject'               => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
							'message'               => $final_str,
							);
							//get invoice
							$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));
							//echo $this->db->last_query();exit;
							if (count($getinvoice_number) > 0) {
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
								if ($invoiceNumber) {
									$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
								}
								//}
								$update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
								$this->db->where('pay_txn_id', $payment_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
								$attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
								##############Get Admit card#############
								if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
									$admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
								}
							}
							if ($attachpath != '') {
								$files          = array($attachpath);
								$sms_newstring  = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
								$sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
								$sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
								$sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
								
								// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
								//$this->Emailsending->mailsend($info_arr);
								$this->Emailsending->mailsend_attch($info_arr, $files);
							}
							} else {
							$log_title   = "B2B Update fail:" . $get_user_regnum[0]['member_regnumber'];
							$log_message = serialize($update_data);
							$rId         = $MerchantOrderNo;
							$regNo       = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
						}
					}
					
					//Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type . " - " . $transaction_error_desc.'-B2B-ApplyExamctrl-success-log-'.$MerchantOrderNo);
					
					//Main Code
					$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
					$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					$user_data = array('regid' => $this->session->userdata('mregid_applyexam'),
					'regnumber'                => $this->session->userdata('mregnumber_applyexam'),
					'firstname'                => $this->session->userdata('mfirstname_applyexam'),
					'middlename'               => $this->session->userdata('mmiddlename_applyexam'),
					'lastname'                 => $this->session->userdata('mlastname_applyexam'),
					'memtype'                  => $this->session->userdata('memtype'),
					'timer'                    => $this->session->userdata('mtimer_applyexam'),
					'password'                 => $this->session->userdata('mpassword_applyexam'));
					$this->session->set_userdata($user_data);
					$temp_user_data = array('mregid_applyexam' => '',
					'mregnumber_applyexam'                     => '',
					'mfirstname_applyexam'                     => '',
					'mmiddlename_applyexam'                    => '',
					'mlastname_applyexam'                      => '',
					'mtimer_applyexam'                         => '',
					'mpassword_applyexam'                      => '');
					foreach ($temp_user_data as $key => $val) {
						$this->session->unset_userdata($key);
					}
					redirect(base_url() . 'Applyexam/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));
					
				} 
				elseif ($auth_status == '0002') {
					
					if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {
						
						$update_data = array('transaction_no' => $transaction_no, 'status' => '2', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
						$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						//Query to get Payment details
						$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');
						//Query to get user details
						$this->db->join('state_master', 'state_master.state_code=member_registration.state');
						$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
						$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,   pincode,state_master.state_name,institution_master.name');
						//Query to get exam details
						$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
						
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
						$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
						
						$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
						$newstring1       = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
						$newstring2       = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
						$newstring3       = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
						$final_str        = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
						
						$info_arr = array('to' => $result[0]['email'],
						'from'                 => $emailerstr[0]['from'],
						'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
						'message'              => $final_str,
						);
						//send sms to Ordinary Member
						$sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
						$sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
						
						
						//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
						/*$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg', $exam_info[0]['exam_code']);
						$this->Emailsending->mailsend($info_arr);*/
						
						
						//Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type . " - " . $transaction_error_desc.'-B2B-ApplyExamctrl-pending-log-'.$MerchantOrderNo);
					}
					redirect(base_url() . 'Applyexam/pending/' . base64_encode($MerchantOrderNo)); 
				}
				else {
					if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {
						
						$update_data = array('transaction_no' => $transaction_no, 'status' => '0', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
						$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						//Query to get Payment details
						$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');
						//Query to get user details
						$this->db->join('state_master', 'state_master.state_code=member_registration.state');
						$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
						$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
						//Query to get exam details
						$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
						
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
						$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
						
						$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
						$newstring1       = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
						$newstring2       = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
						$newstring3       = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
						$final_str        = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
						
						$info_arr = array('to' => $result[0]['email'],
						'from'                 => $emailerstr[0]['from'],
						'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
						'message'              => $final_str,
						);
						//send sms to Ordinary Member
						$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
						$sms_newstring = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
						$sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
						
						
						//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);                        
						//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg', $exam_info[0]['exam_code']);
						$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'],$emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']);// Added on 04 Oct 2023 by Sagar M & Anil S
						
						$this->Emailsending->mailsend($info_arr);
						
						//Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type . " - " . $transaction_error_desc.'-B2B-ApplyExamctrl-fail-log-'.$MerchantOrderNo);
					}
					redirect(base_url() . 'Applyexam/fail/' . base64_encode($MerchantOrderNo));
					
				}
				} else {
				//unsucess
				die("Please try again...");
			}
		}
		
		public function sbitranssuccess()
		{
			exit();
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData         = $aes->decrypt($_REQUEST['encData']);
			$attachpath      = $invoiceNumber      = $admitcard_pdf      = '';
			$responsedata    = explode("|", $encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no  = $responsedata[1];
			if (isset($_REQUEST['merchIdVal'])) {
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code'])) {
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData'])) {
				$encData = $_REQUEST['pushRespData'];
			}
			
			$elective_subject_name = '';
			//Sbi B2B callback
			//check sbi payment status with MerchantOrderNo
			$q_details = sbiqueryapi($MerchantOrderNo);
			if ($q_details) {
				if ($q_details[2] == "SUCCESS") {
					$this->db->limit(1);
					$this->db->order_by('id', 'DESC');
					$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');
					
					//check user payment status is updated by b2b or not
					if ($get_user_regnum[0]['status'] == 2) {
						
						//Query to get user details
						$this->db->join('state_master', 'state_master.state_code=member_registration.state');
						$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
						$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,institution_master.name');
						
						//Query to get exam details
						$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
						
						########## Generate Admit card and allocate Seat #############
						if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
							$exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $get_user_regnum[0]['ref_id']));
							############check capacity is full or not ##########
							//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
							if (count($exam_admicard_details) > 0) {
								$msg          = '';
								$sub_flag     = 1;
								$sub_capacity = 1;
								foreach ($exam_admicard_details as $row) {
									$capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
									if ($capacity == 0) {
										#########get message if capacity is full##########
										#########get message if capacity is full##########
										$log_title   = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
										$log_message = serialize($exam_admicard_details);
										$rId         = $get_user_regnum[0]['ref_id'];
										$regNo       = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
										$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
										redirect(base_url() . 'Applyexam/refund/' . base64_encode($MerchantOrderNo));
									}
								}
							}
							if (count($exam_admicard_details) > 0 && $capacity > 0) {
								
								######### payment Transaction ############
								$update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
								$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
								/* if($this->db->affected_rows())
								{ */
								$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');
								if ($get_payment_status[0]['status'] == 1) {
									if (count($get_user_regnum) > 0) {
										$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber,usrpassword,email');
									}
									
									// admit card gen
									$password = random_password();
									foreach ($exam_admicard_details as $row) {
										$get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $row['venueid'],
										'exam_date'                                                                               => $row['exam_date'],
										'session_time'                                                                            => $row['time'],
										'center_code'                                                                             => $row['center_code']));
										
										$admit_card_details = $this->master_model->getRecords('admit_card_details', array('venueid' => $row['venueid'], 'exam_date' => $row['exam_date'], 'time' => $row['time'], 'mem_exam_id' => $get_user_regnum[0]['ref_id'], 'sub_cd' => $row['sub_cd']));
										
										//echo $this->db->last_query().'<br>';
										$seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
										
										if ($seat_number != '') {
											$final_seat_number = $seat_number;
											$update_data       = array('pwd' => $password, 'seat_identification' => $final_seat_number, 'remark' => 1, 'modified_on' => date('Y-m-d H:i:s'));
											$this->master_model->updateRecord('admit_card_details', $update_data, array('admitcard_id' => $admit_card_details[0]['admitcard_id']));
											} else {
											$admit_card_details = $this->master_model->getRecords('admit_card_details', array('admitcard_id' => $admit_card_details[0]['admitcard_id'], 'remark' => 1));
											if (count($admit_card_details) > 0) {
												$log_title   = "Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($exam_admicard_details);
												$rId         = $admit_card_details[0]['admitcard_id'];
												$regNo       = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												} else {
												$log_title   = "Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
												$rId         = $get_user_regnum[0]['member_regnumber'];
												$regNo       = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												redirect(base_url() . 'Applyexam/refund/' . base64_encode($MerchantOrderNo));
											}
										}
									}
								}
							}
							#########update member Exam##############
							if ($get_payment_status[0]['status'] == 1) {
								$update_data = array('pay_status' => '1');
								$this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));
								$log_title   = "Apply exam membver exam update query:" . $get_user_regnum[0]['member_regnumber'];
								$log_message = '';
								$rId         = $get_user_regnum[0]['ref_id'];
								$regNo       = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								} else {
								$log_title   = "Apply exam membver exam update query fail:" . $get_user_regnum[0]['member_regnumber'];
								$log_message = $get_user_regnum[0]['ref_id'];
								$rId         = $get_user_regnum[0]['ref_id'];
								$regNo       = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}
							
							if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';} else { $mode = '';}
							if ($exam_info[0]['examination_date'] != '0000-00-00') {
								$exam_period_date = date('d-M-Y', strtotime($exam_info[0]['examination_date']));
								} else {
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
								$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
							}
							//Query to get Medium
							$this->db->where('exam_code', $exam_info[0]['exam_code']);
							$this->db->where('exam_period', $exam_info[0]['exam_period']);
							$this->db->where('medium_code', $exam_info[0]['exam_medium']);
							$this->db->where('medium_delete', '0');
							$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
							
							$this->db->where('state_delete', '0');
							$states = $this->master_model->getRecords('state_master', array('state_code' => $exam_info[0]['state_place_of_work']), 'state_name');
							
							//Query to get Payment details
							$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount,id');
							
							$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
							if ($exam_info[0]['place_of_work'] != '' && $exam_info[0]['state_place_of_work'] != '' && $exam_info[0]['pin_code_place_of_work'] != '') {
								//get Elective Subeject name for CAIIB Exam
								if ($exam_info[0]['elected_sub_code'] != 0 && $exam_info[0]['elected_sub_code'] != '') {
									$elective_sub_name_arr = $this->master_model->getRecords('subject_master', array('subject_code' => $exam_info[0]['elected_sub_code'], 'subject_delete' => 0), 'subject_description');
									
									if (count($elective_sub_name_arr) > 0) {
										$elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
									}
								}
								
								$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_exam_enrollment_nofee_elective'));
								$newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
								$newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
								$newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
								$newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
								$newstring5       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring4);
								$newstring6       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring5);
								$newstring7       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring6);
								$newstring8       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring7);
								$newstring9       = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring8);
								$newstring10      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring9);
								$newstring11      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring10);
								$newstring12      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring11);
								$newstring13      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring12);
								$newstring14      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring13);
								$newstring15      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring14);
								$newstring16      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring15);
								$newstring17      = str_replace("#ELECTIVE_SUB#", "" . $elective_subject_name . "", $newstring16);
								$newstring18      = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring17);
								$newstring19      = str_replace("#PLACE_OF_WORK#", "" . strtoupper($exam_info[0]['place_of_work']) . "", $newstring18);
								$newstring20      = str_replace("#STATE_PLACE_OF_WORK#", "" . $states[0]['state_name'] . "", $newstring19);
								$newstring21      = str_replace("#PINCODE_PLACE_OF_WORK#", "" . $exam_info[0]['pin_code_place_of_work'] . "", $newstring20);
								$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
								if (count($elern_msg_string) > 0) {
									foreach ($elern_msg_string as $row) {
										$arr_elern_msg_string[] = $row['exam_code'];
									}
									if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
										$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
										} else {
										$newstring22 = str_replace("#E-MSG#", '', $newstring21);
									}
									} else {
									$newstring22 = str_replace("#E-MSG#", '', $newstring21);
								}
								$final_str = str_replace("#MODE#", "" . $mode . "", $newstring22);
								} else {
								$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'apply_exam_transaction_success'));
								$newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
								$newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
								$newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
								$newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
								$newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
								$newstring6       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
								$newstring7       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
								$newstring8       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
								$newstring9       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
								$newstring10      = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
								$newstring11      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
								$newstring12      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
								$newstring13      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
								$newstring14      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
								$newstring15      = str_replace("#INSTITUDE#", "" . $result[0]['name'] . "", $newstring14);
								$newstring16      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring15);
								$newstring17      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring16);
								$newstring18      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring17);
								$newstring19      = str_replace("#MODE#", "" . $mode . "", $newstring18);
								$newstring20      = str_replace("#PLACE_OF_WORK#", "" . $result[0]['office'] . "", $newstring19);
								$newstring21      = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring20);
								$elern_msg_string = $this->master_model->getRecords('elearning_examcode');
								if (count($elern_msg_string) > 0) {
									foreach ($elern_msg_string as $row) {
										$arr_elern_msg_string[] = $row['exam_code'];
									}
									if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
										$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
										} else {
										$newstring22 = str_replace("#E-MSG#", '', $newstring21);
									}
									} else {
									$newstring22 = str_replace("#E-MSG#", '', $newstring21);
								}
								$final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring22);
							}
							$info_arr = array('to' => $result[0]['email'],
							'from'                 => $emailerstr[0]['from'],
							'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
							'message'              => $final_str,
							);
							
							//get invoice
							$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));
							//echo $this->db->last_query();exit;
							if (count($getinvoice_number) > 0) {
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
								$invoiceNumber = '';
								if ($get_payment_status[0]['status'] == 1 && $capacity > 0) {
									$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
									
									$log_title   = "Apply exam exam_invoice number generate:" . $getinvoice_number[0]['invoice_id'];
									$log_message = '';
									$rId         = $get_user_regnum[0]['ref_id'];
									$regNo       = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
									} else {
									
									$log_title   = "Apply exam exam_invoice number generate fail:" . $getinvoice_number[0]['invoice_id'];
									$log_message = $getinvoice_number[0]['invoice_id'];
									$rId         = $get_user_regnum[0]['ref_id'];
									$regNo       = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
								}
								
								if ($invoiceNumber) {
									$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
								}
								//}
								
								if ($get_payment_status[0]['status'] == 1) {
									$update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
									$this->db->where('pay_txn_id', $payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
									$attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									
									$log_title   = "Apply exam exam_invoice update query:" . $getinvoice_number[0]['invoice_id'];
									$log_message = '';
									$rId         = $get_user_regnum[0]['ref_id'];
									$regNo       = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
									} else {
									
									$log_title   = "Apply exam exam_invoice update query Fail:" . $getinvoice_number[0]['invoice_id'];
									$log_message = $get_user_regnum[0]['ref_id'];
									$rId         = $get_user_regnum[0]['ref_id'];
									$regNo       = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
								}
								
								$update_data_me = array('pay_status' => '1');
								$this->master_model->updateRecord('member_exam', $update_data_me, array('id' => $get_user_regnum[0]['ref_id']));
								
							}
							if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
								##############Get Admit card#############
								$admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
							}
							if ($attachpath != '') {
								$files          = array($attachpath, $admitcard_pdf);
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring  = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
								$sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
								$sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
								$sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
								
								
								//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'C-48OSQMg', $exam_info[0]['exam_code']);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'],$emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']);// Added on 04 Oct 2023 by Sagar M & Anil S
								
								
								$this->Emailsending->mailsend_attch($info_arr, $files);
								//$this->Emailsending->mailsend($info_arr);
							}
						}
						/* }
							else
							{
							$log_title ="B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
							$log_message = serialize($update_data);
							$rId = $MerchantOrderNo;
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
						} */
						
						//Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					}
				}
			} //End of check sbi payment status with MerchantOrderNo
			///End of SBICALL Back
			
			//Main Code
			$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
			$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			$user_data = array('regid' => $this->session->userdata('mregid_applyexam'),
			'regnumber'                => $this->session->userdata('mregnumber_applyexam'),
			'firstname'                => $this->session->userdata('mfirstname_applyexam'),
			'middlename'               => $this->session->userdata('mmiddlename_applyexam'),
			'lastname'                 => $this->session->userdata('mlastname_applyexam'),
			'memtype'                  => $this->session->userdata('memtype'),
			'timer'                    => $this->session->userdata('mtimer_applyexam'),
			'password'                 => $this->session->userdata('mpassword_applyexam'));
			$this->session->set_userdata($user_data);
			$temp_user_data = array('mregid_applyexam' => '',
			'mregnumber_applyexam'                     => '',
			'mfirstname_applyexam'                     => '',
			'mmiddlename_applyexam'                    => '',
			'mlastname_applyexam'                      => '',
			'mtimer_applyexam'                         => '',
			'mpassword_applyexam'                      => '');
			foreach ($temp_user_data as $key => $val) {
				$this->session->unset_userdata($key);
			}
			redirect(base_url() . 'Applyexam/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));
			
		}
		
		public function sbitransfail()
		{
			exit();
			if (isset($_REQUEST['encData'])) {
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encData         = $aes->decrypt($_REQUEST['encData']);
				$responsedata    = explode("|", $encData);
				$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
				$transaction_no  = $responsedata[1];
				//SBICALL Back B2B
				$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');
				if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {
					if (isset($_REQUEST['merchIdVal'])) {
						$merchIdVal = $_REQUEST['merchIdVal'];
					}
					if (isset($_REQUEST['Bank_Code'])) {
						$Bank_Code = $_REQUEST['Bank_Code'];
					}
					if (isset($_REQUEST['pushRespData'])) {
						$encData = $_REQUEST['pushRespData'];
					}
					
					$update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
					$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
					//Query to get Payment details
					$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');
					//Query to get user details
					$this->db->join('state_master', 'state_master.state_code=member_registration.state');
					$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
					$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
					//Query to get exam details
					$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
					
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
					$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
					
					$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
					$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
					$newstring1       = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
					$newstring2       = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
					$newstring3       = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
					$final_str        = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
					
					$info_arr = array('to' => $result[0]['email'],
					'from'                 => $emailerstr[0]['from'],
					'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
					'message'              => $final_str,
					);
					//send sms to Ordinary Member
					$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
					$sms_newstring = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
					
					
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
					//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'Jw6bOIQGg', $exam_info[0]['exam_code']);
					$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'],$emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']);// Added on 04 Oct 2023 by Sagar M & Anil S
					
					
					$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				//End Of SBICALL Back
				redirect(base_url() . 'Applyexam/fail/' . base64_encode($MerchantOrderNo));
				} else {
				die("Please try again...");
			}
		}
		
		//Show acknowlodgement to to user after transaction succeess
		public function details($order_no = null, $excd = null)
		{
			if (!isset($this->session->userdata['examinfo'])) {
				redirect(base_url());
			}
			//payment detail
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('regnumber')));
			
			$today_date = date('Y-m-d');
			$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,exam_master.ebook_flag');
			$this->db->where('elg_mem_o', 'Y');
			$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->where("misc_master.misc_delete", '0');
			$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($excd), 'regnumber' => $this->session->userdata('regnumber'), 'pay_status' => '1'));
			if (count($applied_exam_info) <= 0) {
				redirect(base_url() . 'Home/dashboard');
			}
			$this->db->where('medium_delete', '0');
			$this->db->where('exam_code', base64_decode($excd));
			$this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
			$medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
			$this->db->where('exam_name', base64_decode($excd));
			$this->db->where("center_delete", '0');
			$this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
			$center = $this->master_model->getRecords('center_master');
			
			//get state details
			$this->db->where('state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			
			if (count($applied_exam_info) <= 0) {
				redirect(base_url());
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
				'pincode_place_of_work'=>'',
				'elected_exam_mode'=>''
				);
				$this->session->unset_userdata('examinfo',$user_data);
			*/
			$data = array('middle_content' => 'memapplyexam/exam_applied_success', 'medium' => $medium, 'center' => $center, 'applied_exam_info' => $applied_exam_info, 'payment_info' => $payment_info, 'states' => $states);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
		}
		
		//Show acknowlodgement to to user after transaction Failure
		public function fail($order_no = null)
		{
			//payment detail
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('mregnumber_applyexam')));
			if (count($payment_info) <= 0) {
				redirect(base_url());
			}
			$data = array('middle_content' => 'memapplyexam/exam_applied_fail', 'payment_info' => $payment_info);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			
		}
		
		//Show acknowlodgement to to user after transaction Failure
		public function pending($order_no = null)
		{
			//payment detail
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('mregnumber_applyexam')));
			if (count($payment_info) <= 0) {
				redirect(base_url());
			}
			$data = array('middle_content' => 'memapplyexam/exam_applied_pending', 'payment_info' => $payment_info);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			
		}
		
		##------- check eligible user----------##
		public function checkusers($examcode = null)
		{
			$flag = 0;
			if ($examcode != null) {
				$exam_code = array(33, 47, 51, 52);
				if (in_array($examcode, $exam_code)) {
					$this->db->where_in('eligible_master.exam_code', $exam_code);
					$valid_member_list = $this->master_model->getRecords('eligible_master', array('eligible_period' => '802', 'member_type' => 'O'), 'member_no');
					if (count($valid_member_list) > 0) {
						foreach ($valid_member_list as $row) {
							$memberlist_arr[] = $row['member_no'];
						}
						
						if (in_array($this->session->userdata('mregnumber_applyexam'), $memberlist_arr)) {
							$flag = 1;
							} else {
							$flag = 0;
						}
						} else {
						$flag = 0;
					}
					} else {
					$flag = 1;
				}
			}
			return $flag;
			
		}
		
		//call back for checkpin
		public function check_checkpin($pincode, $statecode)
		{
			if ($statecode != "" && $pincode != '') {
				$this->db->where("$pincode BETWEEN start_pin AND end_pin");
				$prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
				//echo $this->db->last_query();
				if ($prev_count == 0) {
					$str = 'Please enter Valid Pincode';
					$this->form_validation->set_message('check_checkpin', $str);
					return false;} else {
					$this->form_validation->set_message('error', "");
				}
				
				{return true;}
				} else {
				$str = 'Pincode/State field is required.';
				$this->form_validation->set_message('check_checkpin', $str);
				return false;
			}
		}
		
		public function check_mobileduplication($mobile)
		{
			if ($mobile != "") {
				$prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $this->session->userdata('mregid_applyexam'), 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));
				//echo $this->db->last_query();
				
				if ($prev_count == 0) {
					return true;
					} else {
					$str = 'The entered Mobile no already exist';
					$this->form_validation->set_message('check_mobileduplication', $str);
					return false;
				}
				} else {
				return false;
			}
		}
		public function check_emailduplication($email)
		{
			if ($email != "") {
				$prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $this->session->userdata('mregid_applyexam'), 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));
				
				if ($prev_count == 0) {
					return true;
					} else {
					$str = 'The entered email ID already exist';
					$this->form_validation->set_message('check_emailduplication', $str);
					return false;
				}
				} else {
				return false;
			}
		}

		public function check_bank_bc_id_no_duplication($ippb_emp_id, $name_of_bank_bc)
        {
            if ($ippb_emp_id != "" && $ippb_emp_id != "0" && $name_of_bank_bc != '') {
                $this->db->where("name_of_bank_bc",$name_of_bank_bc);
                $this->db->where("ippb_emp_id",$ippb_emp_id);
                $this->db->where("regnumber != ",$this->session->userdata('mregnumber_applyexam')); 
                $prev_count = $this->master_model->getRecordCount('member_registration', array('isactive' => '1'));
                //print_r($this->session->userdata('mregnumber_applyexam'));//die;
                //echo $this->db->last_query();die;
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
        public function check_date_of_joining_bc_validation($date_of_commenc_bc, $exam_date_exist)
        {
            if ($date_of_commenc_bc != "" && $exam_date_exist != '') {
                  
                  $jdate = date("Y-m-d",strtotime($date_of_commenc_bc));  
                  $ninemonthDate = date("Y-m-d",strtotime("+9 month", strtotime($jdate)));  
                  $beforeninemonthDate = date("Y-m-d",strtotime("-9 month", strtotime($exam_date_exist)));  
                  $chk_date = "2024-03-31";  
                  //$check_start_date = "2023-07-01";
      			  $check_start_date = "1964-01-01";
                  $check_end_date = "2024-03-31"; 
                  //echo $jdate." > ".$chk_date;
                  //echo "<br>".$ninemonthDate." > ".$exam_date_exist;die;
                  /*if($jdate > $chk_date)
                  {
                    $str = 'Date of joining should not be greater than 31-March-2024';
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false;
                  }
                  else if( $jdate < $beforeninemonthDate )
                  {
                    $str = 'Commencement of operations / joining as BC to be within 9 months from the date of examination.';
                    //$str = 'Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '.date("d-M-Y",strtotime($exam_date_exist)).', your Date of Joining should be on or before '.date("d-M-Y",strtotime($beforeninemonthDate)).'.';
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false; 
                  }*/
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
                  else if( $jdate > $exam_date_exist )
                  {
                    $str = 'Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.';
                    /*$str = 'Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '.date("d-M-Y",strtotime($exam_date_exist)).', your Date of Joining should be on or before '.date("d-M-Y",strtotime($beforeninemonthDate)).'.';*/
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
        function empidproofphoto_upload()
        {
            if ($_FILES['empidproofphoto']['size'] != 0) {
                return true;
            } else {
                $this->form_validation->set_message('empidproofphoto_upload', "No Employee Id proof file selected");
                return false;
            }
        }
		
		######### if seat allocation full show message#######
		public function refund($order_no = null)
		{
			//payment detail
			//$this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
			//$this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no)));
			
			if (count($payment_info) <= 0) {
				redirect(base_url());
			}
			
			$this->db->where('remark', '2');
			$admit_card_refund = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $payment_info[0]['ref_id']));
			if (count($admit_card_refund) > 0) {
				$update_data = array('remark' => 3);
				$this->master_model->updateRecord('admit_card_details', $update_data, array('mem_exam_id' => $payment_info[0]['ref_id']));
			}
			
			$exam_name = $this->master_model->getRecords('exam_master', array('exam_code' => $payment_info[0]['exam_code']));
			
			##adding below code for processing the refund process - added by chaitali on 2021-09-17
			$insert_data = array('receipt_no' => base64_decode($order_no), 'transaction_no' => $payment_info[0]['transaction_no'], 'refund' => '0', 'created_on' => date('Y-m-d'), 'email_flag' => '0', 'sms_flag' => '0');
			$this->master_model->insertRecord('exam_payment_refund', $insert_data);
			//echo $this->db->last_query(); die;
			## ended insert code
			
			$data = array('middle_content' => 'memapplyexam/member_refund', 'payment_info' => $payment_info, 'exam_name' => $exam_name);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
			
		}
		
		/* Get scribe drop down*/
		public function getsub_menue()
		{
			$deptid = $this->input->post('deptid');
			// Code for fetching department Dropdown
			$scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('code' => $deptid, 'is_delete' => '0'));
			
			// EOF Code fetching department Dropdown
			$department_dropdown = $search_department = '';
			if (!empty($scribe_sub_disability)) {
				$department_dropdown .= "<select class='form-control' id='Sub_menue' name='Sub_menue'>";
				
				$department_dropdown .= "<option value=''>--Select--</option>";
				
				foreach ($scribe_sub_disability as $dkey => $dValue) {
					$deptid    = $dValue['sub_code'];
					$dept_name = $dValue['sub_disability'];
					
					$department_dropdown .= "<option value=" . $dValue['sub_code'] . ">" . $dept_name . "</option>";
				}
				$department_dropdown .= "</select>";
				echo $department_dropdown;
				} else {
				echo $department_dropdown = "";
			}
		}
		
		public function set_jaiib_elsub_cnt()
		{
			$subject_cnt_arr = array('subject_cnt' => $_POST['subject_cnt']);
			$this->session->set_userdata($subject_cnt_arr);
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
			$this->session->set_userdata('selectedoptVal_examcode',$this->session->userdata('examcode'));
			echo $_GET['optVal'];
			
		}
		
		//Function to check valid exam for member
		public function check_valid_exam_for_member($decode_exam_code){
			// Benchmark Disability Check
			$user_info = $this->master_model->getRecords('member_registration', array(
			'regnumber' => $this->session->userdata('mregnumber_applyexam'), 
			//'regid'     => $this->session->userdata('regid'),
			//'regnumber' => $this->session->userdata('regnumber'),
			'isactive'  => '1',
			), 'associatedinstitute');
			
			
			//START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
			$valid_ExamCode_InstituteCode_arr = $this->config->item('VALID_EXAMCODE_INSTITUTECODE_ARR');  
			$associatedinstitute = (isset($user_info) && $user_info[0]['associatedinstitute'] != "" ? $user_info[0]['associatedinstitute'] : '');
			if(count($valid_ExamCode_InstituteCode_arr) > 0){
				foreach($valid_ExamCode_InstituteCode_arr as $res_code){
					$inst_code_arr = $res_code["inst_code_arr"];
					$exam_code_arr = $res_code["exam_code_arr"]; 
					//$decode_exam_code = base64_decode($this->input->get('excode2')); 
					if(in_array($decode_exam_code,$exam_code_arr)){ 
						if(!in_array($associatedinstitute,$inst_code_arr)){
							//$this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile");
							//redirect(base_url() . 'Home/examlist');
							return 1;
							//redirect(base_url() . 'Home/accessdenied/');
						}
					}  
				}
			}

			$valid_ExamCode_InstituteCode_KARUR_BANK_arr = $this->config->item('VALID_EXAMCODE_INSTITUTECODE_ARR_KARUR_BANK');  
			$associatedinstitute = (isset($user_info) && $user_info[0]['associatedinstitute'] != "" ? $user_info[0]['associatedinstitute'] : '');
			if(count($valid_ExamCode_InstituteCode_KARUR_BANK_arr) > 0){
				foreach($valid_ExamCode_InstituteCode_KARUR_BANK_arr as $res_code){
					$inst_code_arr = $res_code["inst_code_arr"];
					$exam_code_arr = $res_code["exam_code_arr"]; 
					//$decode_exam_code = base64_decode($this->input->get('excode2')); 
					if(in_array($decode_exam_code,$exam_code_arr)){ 
						if(!in_array($associatedinstitute,$inst_code_arr)){
							//$this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile");
							//redirect(base_url() . 'Home/examlist');
							return 1;
							//redirect(base_url() . 'Home/accessdenied/');
						}
					}  
				}
			}
			//END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
		}
		
		public function access_denied_invalid_exam()
		{
			$decode_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
			if(empty($decode_exam_code)){
				$decode_exam_code = $this->session->userdata('examcode');
			}
			if(empty($decode_exam_code)){
				$decode_exam_code = base64_decode($this->input->get('ExId'));
			}

			if(in_array($decode_exam_code,array(1031,1032))){
                $message = '<div style="color:#F00;font-size: 20px;">This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile.</div>';
            }else if(in_array($decode_exam_code,array(1062,1063,1064,1065,1066,1067,1068,1069))){
            	$message = '<div style="color:#F00;font-size: 20px;">This certificate course is applicable for Karur Vysya Bank staff only. In case you have changed your organisation to Karur Vysya Bank, kindly update the bank name in your membership profile.</div>'; 
            }else{
            	$message = '<div style="color:#F00;font-size: 20px;">This certificate course is applicable only for specific staff. You are not eligible to apply for this course.</div>'; 
            } 
			$data    = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
			$this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
		}

		public function accessdenied_not_old_bcbf_mem()
        {
            $message = '<div style="color:#F00">You are not eligible to register for the selected examination. <strong>For any queries contact zonal office</strong>.</div>';
            $data    = array('middle_content' => 'nonmember/not_eligible', 'check_eligibility' => $message);
            $this->load->view('nonmember/nm_common_view', $data);
        }

		/* START : Access denied due to exam recovery by Pooja mane 2025-02-06*/
		public function Recovery()
		{
			$msg  = '<li>Please pay Recovery amount of Exam registration in order to apply for the exam. <a href="' . base_url() . 'ExamRecovery/" target="new">click here</a> </li>';
			$data = array('middle_content' => 'member_notification', 'msg' => $msg);
			$this->load->view('nonmember/nm_common_view', $data);
		}
		/* END : Access denied due to exam recovery by Pooja mane 2025-02-06*/
		
        function get_client_ip2() {
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
            $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'misc_master.misc_delete' => '0'), 'exam_month');
            //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
            $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
            $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
            $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.period. Hence you need not apply for the same.';  
            $data    = array('middle_content' => 'memapplyexam/not_eligible', 'check_eligibility' => $message);
            $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
        }

	}
