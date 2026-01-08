<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dbfuser_check_sm extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
	  //	$this->chk_session->chk_member_session();
	  $this->chk_session->Check_mult_session();
	}
	
	public function sbi_make_payment()
	{
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$total_el_amount = 0;
		$el_subject_cnt = 0;
		$total_elearning_amt = 0;
		## New elarning columns code
		$total_el_base_amount = 0;
		$total_el_gst_amount = 0;
		$total_el_cgst_amount = 0;
		$total_el_sgst_amount = 0;
		$total_el_igst_amount = 0;
		$getstate=$getcenter=$getfees=array();
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
			//redirect('http://iibf.org.in/');
			echo 'redirect : http://iibf.org.in'; exit;
		}
		
		//if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$session_arr = Array
(
    '__ci_last_regenerate' => '1637594100',
    'userlogincaptcha' => 'CQHFW',
    'regcaptcha' => 'LBJPW',
    'dbf_memberdata' => Array
        (
            'regno' => '2770107',
            'password' => 'ELBk6ftM',
            'email' => 'tmanir15@gmail.com',
            'exam_fee' => '4513.5',
            'exam_desc' => 'DIPLOMA IN BANKING &amp; FINANCE(DB&amp;F)',
            'excode' => $this->config->item('examCodeDBF'),
            'member_exam_id' => '6132489',
        ),

    'dbfmemregcaptcha' => '7kdhp',
    'subject_cnt' => '3',
    'enduserinfo' => Array
        (
            'firstname' => 'Manikandan',
            'sel_namesub' => 'Mr.',
            'addressline1' => 'Door no 3',
            'addressline2' => 'Road No 5',
            'addressline3' => 'V Nagar',
            'addressline4' => 'Rasipuram',
            'city' => 'Namakkal',
            'code' => '7kdhp',
            'district' => 'Namakkal',
            'dob' => '1996-03-06',
            'eduqual' => '',
            'eduqual1' => '',
            'eduqual2' => '51',
            'eduqual3' => '',
            'email' => 'tmanir15@gmail.com',
            'gender' => 'male',
            'idNo' => 'DHJPM5369F',
            'idproof' => '5',
            'lastname' => 'T',
            'middlename' => '',
            'mobile' => '9976482860',
            'nameoncard' => '',
            'optedu' => 'G',
            'optnletter' => 'optnletter',
            'phone' => '',
            'pincode' => '637408',
            'state' => 'TAM',
            'stdcode' => '',
            'scannedphoto' => 'https://iibf.esdsconnect.com/uploads/photograph/non_mem_photo_163755337446.jpg',
            'scannedsignaturephoto' => 'https://iibf.esdsconnect.com/uploads/scansignature/non_mem_sign_16375533745.jpg',
            'idproofphoto' => 'https://iibf.esdsconnect.com/uploads/idproof/non_mem_idproof_163755337413.jpeg',
            'photoname' => 'non_mem_photo_163755337446.jpg',
            'signname' => 'non_mem_sign_16375533745.jpg',
            'idname' => 'non_mem_idproof_163755337413.jpeg',
            'selCenterName' => '477',
            'txtCenterCode' => '477',
            'optmode' => 'ON',
            'exid' => 'NDI=',
            'mtype' => 'DB',
            'memtype' => 'DB',
            'eprid' => '221',
            'rrsub' => '',
            'excd' => $this->config->item('examCodeDBF'),
            'exname' =>  'DIPLOMA IN BANKING &amp; FINANCE(DB&amp;F)',
            'fee' => '4513.5',
            'medium' => 'E',
            'aadhar_card' => '876550176481',
            'grp_code' => 'B1_1',
            'subject_arr' => Array
                (
                    '121' => Array
                        (
                            'venue' => '636309',
                            'date' => '2022-01-08',
                            'session_time' => '2:00 PM',
                            'subject_name' => 'Principles & Practices of Banking'
                        ),

                    '122' => Array
                        (
                            'venue' => '636309',
                            'date' => '2022-01-09',
                            'session_time' => '2:00 PM',
                            'subject_name' => 'Accounting & Finance for Bankers'
                        ),

                    '123' => Array
                        (
                            'venue' => '636309',
                            'date' => '2022-01-22',
                            'session_time' => '2:00 PM',
                            'subject_name' => 'Legal & Regulatory Aspects of Banking'
                        )

                ),

            'scribe_flag' => 'N',
            'scribe_flag_d' => 'N',
            'disability_value' =>'', 
            'Sub_menue_disability' => '',
            'createdon' => '2021-11-22 21:26:14',
            'benchmark_disability' => 'N',
            'scanned_vis_imp_cert' => '',
            'vis_imp_cert_name' => '',
            'scanned_orth_han_cert' => '',
            'orth_han_cert_name' => '',
            'scanned_cer_palsy_cert' => '',
            'cer_palsy_cert_name' => '',
            'visually_impaired' => 'N',
            'orthopedically_handicapped' => 'N',
            'cerebral_palsy' => 'N',
            'elearning_flag' => 'N'
        ),

    'examinfo' => Array
        (
            'el_subject' => Array
                (
                    '121' => 'Y',
                    '122' => 'Y',
                    '123' => 'Y'
                )

        )

);
			echo '<br>================= Session Array ===================<br>';
			echo '<pre>'; print_r($session_arr); echo '<pre>'; 
			echo '<br>=======================================================<br>';
			$_SESSION = $session_arr;
			
			
			echo '<br>regno : '.$regno = $this->session->userdata['dbf_memberdata']['regno']; 
			
			/* $log_title = "DBF User Log - All Session data - 1";
			$log_message = serialize($_SESSION);
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo); */			
			
			$exam_desc= $this->session->userdata['dbf_memberdata']['exam_desc'];
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$pg_success_url = base_url()."Dbfuser/sbitranssuccess";
			$pg_fail_url    = base_url()."Dbfuser/sbitransfail";
			if(isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 &&($this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeJaiib') || $this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeDBF') || $this->session->userdata['enduserinfo']['excd'] ==$this->config->item('examCodeSOB')))
			{
				if($this->session->userdata['examinfo']['el_subject'] == 'N')
				{
					$el_subject_cnt = 0;
				}
				else
				{
					$el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
				}
			}
			else
			{
				$el_subject_cnt = 0;
			}
			echo '<br> el_subject_cnt : '.$el_subject_cnt; 
			
			if($this->config->item('sb_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				//$amount=$this->session->userdata['enduserinfo']['fee'];
				$amount=$this->getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'DB',$this->session->userdata['enduserinfo']['elearning_flag']);
				echo '<br>amount : '.$amount;
				
				if(isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 &&($this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeJaiib') || $this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeDBF') || $this->session->userdata['enduserinfo']['excd']) ==$this->config->item('examCodeSOB'))
				{
					$el_amount=$this->get_el_ExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],$this->session->userdata['enduserinfo']['excd'],$this->session->userdata['enduserinfo']['grp_code'],'DB',$this->session->userdata['enduserinfo']['elearning_flag']);
					
					echo '<br>el_amount : '.$el_amount;
					
					$total_elearning_amt = $el_amount * $el_subject_cnt;
					$amount = $amount + $total_elearning_amt;
					## New elarning columns code
					$total_el_base_amount = $el_subject_cnt;
					$total_el_cgst_amount = $el_subject_cnt;
					$total_el_sgst_amount = $el_subject_cnt;
					$total_el_igst_amount = $el_subject_cnt;  
				}
			}
			
			echo '<br>Amount : '.$amount;
			echo '<br>total_elearning_amt : '.$total_elearning_amt;
			echo '<br>total_el_base_amount : '.$total_el_base_amount;
			echo '<br>total_el_cgst_amount : '.$total_el_cgst_amount;
			echo '<br>total_el_sgst_amount : '.$total_el_sgst_amount;
			echo '<br>total_el_igst_amount : '.$total_el_igst_amount;
			//exit;
			
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
			//With Registration DBF
			//Non memeber / DBF Apply exam
			//Ref1 = orderid
			//Ref2 = iibfexam
			//Ref3 = orderid
			//Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid']),'exam_month');
			if($this->session->userdata['enduserinfo']['excd']==340 || $this->session->userdata['enduserinfo']['excd']==3400)
			{
				$exam_code=34;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==580 || $this->session->userdata['enduserinfo']['excd']==5800)
			{
				$exam_code=58;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==1600 || $this->session->userdata['enduserinfo']['excd']==16000)
			{
				$exam_code=160;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==200)
			{
				$exam_code=20;		
			}else if($this->session->userdata['enduserinfo']['excd']==1770 || $this->session->userdata['enduserinfo']['excd']==17700)
			{
				$exam_code=177;		
			}
			else if($this->session->userdata['enduserinfo']['excd']==1750)
			{
				$exam_code=175;		
			}
			else
			{
				$exam_code=$this->session->userdata['enduserinfo']['excd'];
			}
			$ref4=$exam_code.$yearmonth[0]['exam_month'];
			// Create transaction
			$member_exam_id=$this->session->userdata['dbf_memberdata']['member_exam_id'];
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $member_exam_id,
				'description'      => $exam_desc,
				'status'           => '2',
				'exam_code'        => $this->session->userdata['dbf_memberdata']['excode'],
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>'IIBF_EXAM_DB',
				//'pg_other_details'=>$custom_field
			);
			echo '<br><br>================= Payment Array ===================';
			echo '<br> Insert Data : payment_transaction';
			echo '<pre>'; print_r($insert_data); echo '</pre>'; 
			echo '<br>=======================================================<br>';
			//$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			$pt_id = '5427224';
			
			/* $log_title = "DBF User Log - payment_transaction insert data - 2";
			$log_message = serialize($insert_data);
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo); */
			
			//set cookie for Apply Exam
			applyexam_set_cookie($regno);
			$MerchantOrderNo = '903030139'; //sbi_exam_order_id($pt_id);
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^".$MerchantOrderNo."^".$ref4;
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			//$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			echo '<br><br>=======================================================';
			echo '<br>Update : payment_transaction';
			echo '<pre>'; print_r($update_data); echo '</pre>';
			echo '<br>=======================================================';
			
			$MerchantCustomerID = $regno;
			//set invoice details(Pawan)
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd'],'center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'center_delete'=>'0'));
			if(count($getcenter) > 0)
			{ 
				//get state code,state name,state number.
				$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				//call to helper (fee_helper)
				$getfees=getExamFeedetails($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],$this->session->userdata['enduserinfo']['memtype'],$this->session->userdata['enduserinfo']['elearning_flag']);
			}
			
			echo '<br> state_code : '.$getcenter[0]['state_code']; 
			
			$el_flag_new = 'N';
			if($this->session->userdata['enduserinfo']['elearning_flag'] == 'Y') { $el_flag_new = 'Y'; }
			else
			{
				if(isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0)
				{
					foreach($this->session->userdata['examinfo']['el_subject'] as $el_sub_res_new)
					{
						if($el_sub_res_new == "Y") { $el_flag_new = 'Y'; }
					}
				}
			}
			
			if($getcenter[0]['state_code']=='MAH')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$this->config->item('cgst_rate');
				$sgst_rate=$this->config->item('sgst_rate');
				
				//if($this->session->userdata['enduserinfo']['elearning_flag'] == 'Y')
				if($el_flag_new == 'Y')
				{
					if(isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 &&($this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeJaiib') || $this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeDBF') || $this->session->userdata['enduserinfo']['excd'] ==$this->config->item('examCodeSOB')))
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
					}else{
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
				$tax_type='Intra';
			}			
			else
			{
				//if($this->session->userdata['enduserinfo']['elearning_flag'] == 'Y')
				if($el_flag_new == 'Y')
				{
					$igst_rate=$this->config->item('igst_rate');
					if(isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 &&($this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeJaiib') || $this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeDBF') || $this->session->userdata['enduserinfo']['excd'] ==$this->config->item('examCodeSOB'))){
						 $igst_total=$amount;
						 $total_el_amount = $total_elearning_amt;
						 $amount_base = $getfees[0]['fee_amount'];
						 $igst_amt=$getfees[0]['igst_amt'];
						## New elarning columns code
						 $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
						 $total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
						 $total_el_gst_amount = $total_el_igst_amount;						 
					}else{
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
					$total_el_base_amount = 0;
					$total_el_gst_amount = 0;
				}   
				$tax_type='Inter';
			}
			
			if($getstate[0]['exempt']=='E')
 			{
 				 $cgst_rate=$sgst_rate=$igst_rate='';	
 				 $cgst_amt=$sgst_amt=$igst_amt='';	
 			}
			
			$invoice_insert_array=array('pay_txn_id'=>$pt_id,
													'receipt_no'=>$MerchantOrderNo,
													'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'state_of_center'=>$getcenter[0]['state_code'],
													'member_no'=>$regno,
													'app_type'=>'O',
													'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
													'service_code'=>$this->config->item('exam_service_code'),
													'qty'=>'1',
													'state_code'=>$getstate[0]['state_no'],
													'state_name'=>$getstate[0]['state_name'],
													'tax_type'=>$tax_type,
													'fee_amt'=>$amount_base,
													'total_el_amount'=>$total_el_amount,
													'total_el_base_amount'=>$total_el_base_amount,
													'total_el_gst_amount'=>$total_el_gst_amount,
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
			//$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);
			
			echo '<br><br>======================== exam_invoice ===============================';
			echo '<br> Insert : exam_invoice';
			echo '<pre>'; print_r($invoice_insert_array); echo '</pre>';
			$inser_id = '3508759'; 
			echo '<br><br>=======================================================';exit;
			
			//if exam invocie entry skip
			if($inser_id==''){
				//$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);
			}
			
			/* $log_title = "DBF User Log - exam_invoice insert data - 3";
			$log_message = serialize($invoice_insert_array); 
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo); */
			
			/* $log_title = "Exam invoice data from dbfuser cntrlr inser_id = '".$inser_id."'";
			$log_message = serialize($invoice_insert_array);
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo); */
			
			//insert into admit card table
			//################get userdata###########
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$regno));
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
			if($this->session->userdata['enduserinfo']['optmode']=='ON')
			{
				$mode='Online';
			}
			else
			{
				$mode='Offline';
			}	
			$sub_el_flg = 'N';
			if(!empty($this->session->userdata['enduserinfo']['subject_arr']))
			{
					foreach($this->session->userdata['enduserinfo']['subject_arr'] as $k=>$v)
					{
																$this->db->group_by('subject_code');
							$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'subject_delete'=>'0','group_code'=>'C','exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'subject_code'=>$k),'subject_description');
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['enduserinfo']['selCenterName']));
						if(isset($this->session->userdata['examinfo']['el_subject']) &&($this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeJaiib') || $this->session->userdata['enduserinfo']['excd'] == $this->config->item('examCodeDBF') || $this->session->userdata['enduserinfo']['excd'] ==$this->config->item('examCodeSOB'))){
							if($this->session->userdata['examinfo']['el_subject'] != 'N'){
								if (array_key_exists($k,$this->session->userdata['examinfo']['el_subject'])){
									$sub_el_flg = 'Y';
								}else{
									$sub_el_flg = 'N';
								}
							}
						}
						$admitcard_insert_array=array('mem_exam_id'=>$member_exam_id,
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'mem_type'=>$this->session->userdata['enduserinfo']['memtype'],
													'mem_mem_no'=>$regno,
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
													'exm_cd'=>$this->session->userdata['enduserinfo']['excd'],
													'exm_prd'=>$this->session->userdata['enduserinfo']['eprid'],
													'sub_cd '=>$k,
													'sub_dsc'=>$compulsory_subjects[0]['subject_description'],
													'sub_el_flg'=>$sub_el_flg,
													'm_1'=>$this->session->userdata['enduserinfo']['medium'],
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
													'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
													'scribe_flag_PwBD' => $this->session->userdata['enduserinfo']['scribe_flag_d'],
													'disability' => $this->session->userdata['enduserinfo']['disability_value'],
													'sub_disability' => $this->session->userdata['enduserinfo']['Sub_menue_disability'],
													'vendor_code'=>$get_subject_details[0]['vendor_code'],
													'remark'=>2,
													'created_on'=>date('Y-m-d H:i:s'));
						//$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
						echo '<br>Insert : admit_card_details';
					}
				}
			else
			{
				$this->session->set_flashdata('Error','Something went wrong!!');
				//redirect(base_url().'Dbfuser/preview/');
				echo '<br> Redirect : '.base_url().'Dbfuser/preview/';
			}
			//$custom_field = "^iibfexam^^";
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
			//$this->load->view('pg_sbi_form',$data);
		}		
	}
	
	public function sbitranssuccess()
	{
		//Delete Cookie
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}	//Cookie Deleted
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$encData = $aes->decrypt($_REQUEST['encData']);
		$responsedata = explode("|",$encData);
		$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
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
		// Handle transaction success case 
		$q_details = sbiqueryapi($MerchantOrderNo);
		if ($q_details)
		{
			if ($q_details[2] == "SUCCESS")
			{
				// Handle transaction success case 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
				if($get_user_regnum[0]['status']==2)
				{
					######### payment Transaction ############
					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
					$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
					if($this->db->affected_rows())
					{
						$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
					if($get_payment_status[0]['status']==1)
					{
					############check capacity is full or not ##########
					$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
					//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
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
								$this->db->trans_start(); 
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$this->db->trans_complete();
								$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
							}
						}
					}
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id=$get_user_regnum[0]['member_regnumber'];
					//$applicationNo = generate_dbf_reg_num();
					$applicationNo = generate_DBF_memreg($reg_id); 
					######### payment Transaction ############
					$this->db->trans_start();
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					$this->db->trans_complete();
					######update member_exam######
					if($get_payment_status[0]['status']==1){
						$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
						$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
						$log_title ="DBFUSER member exam Update :".$applicationNo;
						$log_message = '';
						$rId = $applicationNo;
						$regNo = $applicationNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}else{
						$log_title ="DBFUSER member exam Update fail :".$applicationNo;
						$log_message = $applicationNo;
						$rId = $applicationNo;
						$regNo = $applicationNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}
					##########Update Member Exam#############
					if($get_payment_status[0]['status']==1){
						$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
						$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
						$log_title ="DBFUSER member registration Update :".$applicationNo;
						$log_message = '';
						$rId = $applicationNo;
						$regNo = $applicationNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}else{
						$log_title ="DBFUSER member registration fail :".$applicationNo;
						$log_message = $applicationNo;
						$rId = $applicationNo;
						$regNo = $applicationNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					//Query to get exam details	
				   $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					########## Generate Admit card and allocate Seat #############
					if(count($exam_admicard_details) > 0)
					{
						$password=random_password();
						foreach($exam_admicard_details as $row)
						{
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'=>$row['center_code']));
							$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
							//echo $this->db->last_query().'<br>';
							$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
							if($seat_number!='')
							{
								$final_seat_number = $seat_number;
								$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
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
								$log_title ="Fail user seat allocation id:".$applicationNo;
								$log_message = serialize($exam_admicard_details);
								$rId = $applicationNo;
								$regNo = $applicationNo;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
							}
							}
						}
					}	
					else
					{
						redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
					}
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					//Query to get Medium	
					$this->db->where('exam_code',$exam_code);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img');	
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					$visually_file = 'v_'.$applicationNo.'.jpg';
					$orthopedically_file = 'o_'.$applicationNo.'.jpg';
					$cerebral_file = 'c_'.$applicationNo.'.jpg';	
					if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					if(@ rename("./uploads/disability/".$result[0]['vis_imp_cert_img'],"./uploads/disability/".$visually_file))
					{	$upd_files['vis_imp_cert_img'] = $visually_file;	}
					if(@ rename("./uploads/disability/".$result[0]['orth_han_cert_img'],"./uploads/disability/".$orthopedically_file))
					{	$upd_files['orth_han_cert_img'] = $orthopedically_file;	}
					if(@ rename("./uploads/disability/".$result[0]['cer_palsy_cert_img'],"./uploads/disability/".$cerebral_file))
					{	$upd_files['cer_palsy_cert_img'] = $cerebral_file;	}
					if(count($upd_files)>0)
					{
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
					}
					else
					{
						$upd_files['scannedphoto'] = $photo_file;
						$upd_files['scannedsignaturephoto'] = $sign_file;	
						$upd_files['idproofphoto'] = $proof_file;
						$upd_files['vis_imp_cert_img'] = $visually_file;
						$upd_files['orth_han_cert_img'] = $orthopedically_file;	
						$upd_files['cer_palsy_cert_img'] = $cerebral_file;
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
						$log_title ="DBF member PICS MANUAL PICS Update :".$reg_id;
						$log_message = serialize($upd_files);
						$rId = $reg_id;
						$regNo = $reg_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
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
									//'to'=>'raajpardeshi@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
											'message'=>$final_str
										);
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
					$invoiceNumber = '';
					if($get_payment_status[0]['status']==1){
						$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
						$log_title ="DBF exam invoice number generate :".$getinvoice_number[0]['invoice_id'];
						$log_message = '';
						$rId = $getinvoice_number[0]['invoice_id'];
						$regNo = $getinvoice_number[0]['invoice_id'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}else{
						$log_title ="DBF exam invoice number generate fail :".$getinvoice_number[0]['invoice_id'];
						$log_message = $getinvoice_number[0]['invoice_id'];
						$rId = $getinvoice_number[0]['invoice_id'];
						$regNo = $getinvoice_number[0]['invoice_id'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
					}
					//}
					if($get_payment_status[0]['status']==1){
						$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
						$this->db->where('pay_txn_id',$payment_info[0]['id']);
						$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
						$log_title ="DBF exam invoice update :".$MerchantOrderNo;
						$log_message = '';
						$rId = $MerchantOrderNo;
						$regNo = $MerchantOrderNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
						$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
					}else{
						$log_title ="DBF exam invoice update fail :".$MerchantOrderNo;
						$log_message = $MerchantOrderNo;
						$rId = $MerchantOrderNo;
						$regNo = $MerchantOrderNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
						##############Get Admit card#############
						$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);	
					}	
					if($attachpath!='')
					{	
						$files=array($attachpath,$admitcard_pdf);			
						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'mUr3FSwGR',$exam_info[0]['exam_code']);	
						$this->Emailsending->mailsend_attch($info_arr,$files);
					}
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
		}
		redirect(base_url().'Dbfuser/acknowledge/'.base64_encode($MerchantOrderNo));
	}
	
	public function sbitransfail()
	{
		//Delete Cookie
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}//Cookie Deleted
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
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
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
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
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
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg',$exam_info[0]['exam_code']);
					$this->Emailsending->mailsend($info_arr);
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
				//Main Code
				redirect(base_url());
			//}
		//echo 'transaction fail';exit;
		}
		else
		{
			die("Please try again...");
		}
	}
	
	//function for payment
	public function make_payment()
	{
		if(!$this->session->userdata('dbf_memberdata'))
		{
			redirect(base_url());
		}
			$regno = $this->session->userdata['dbf_memberdata']['regno'];
			$exam_desc= $this->session->userdata['dbf_memberdata']['exam_desc'];
			$MerchantID = $this->config->item('bd_MerchantID');
			$SecurityID = $this->config->item('bd_SecurityID');
			$checksum_key = $this->config->item('bd_ChecksumKey');
			$pg_return_url = base_url()."Dbfuser/pg_response";
			$member_exam_id=$this->session->userdata['dbf_memberdata']['member_exam_id'];
			//$amount= $this->session->userdata['dbf_memberdata']['exam_fee'];
			$amount ='1';
			//$MerchantOrderNo = generate_order_id("bd_exam_order_id");
			// Create transaction
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "billdesk",
				'date'             => date('Y-m-d h:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $regno,
				'description'      => $exam_desc,
				'status'           => '2',
				'exam_code'        => $this->session->userdata['dbf_memberdata']['excode'],
				//'receipt_no'       => $MerchantOrderNo
			);
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			$MerchantOrderNo = bd_exam_order_id($pt_id);
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			$MerchantCustomerID = $regno;
			$custom_field = "iibfexam";
			$data["pg_form_url"] = $this->config->item('bd_pg_form_url'); // SBI ePay form URL
			/*
			Format:			requestparameter=MerchantID|CustomerID|NA|TxnAmount|NA|NA|NA|CurrencyType|NA|TypeField1|SecurityID|NA|NA|TypeField2|AdditionalInfo1|AdditionalInfo2|AdditionalInfo3|AdditionalInfo4|AdditionalInfo5|NA|NA|RU|Checksum
			Ex.	
requestparameter=IIBF|2138759|NA|500.00|NA|NA|NA|INR|NA|R|iibf|NA|NA|F|iibfexam|500081141|148201701|NA|NA|NA|NA|http://abc.somedomain.com|2387462372
			*/
			$member_exam_id=$this->session->userdata['dbf_memberdata']['member_exam_id'];
			$requestparameter = $MerchantID."|".$MerchantOrderNo."|NA|".$amount."|NA|NA|NA|INR|NA|R|".$SecurityID."|NA|NA|F|".$custom_field."|".$MerchantCustomerID."|".$member_exam_id."|NA|NA|NA|NA|".$pg_return_url;
			// Generate checksum for request parameter
			$req_param = $requestparameter."|".$checksum_key;
			$checksum = crc32($req_param);
			$requestparameter = $requestparameter . "|".$checksum;
			$data["msg"] = $requestparameter;
			$this->load->view('pg_bd_form',$data);
	}
	
	public function pg_response()
	{
		//$_REQUEST['msg'] = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";
		//	$_REQUEST['msg'] = "IIBF|2138195|HHMP4897894246|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 12:55:48|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|1435616898";
		if (isset($_REQUEST['msg']))
		{
			//echo "<pre>";
			//print_r($_REQUEST);
			//echo "<BR> Response : ".$_REQUEST['msg'];
			// validate checksum
			preg_match_all("/(.*)\|([0-9]*)$/", $_REQUEST['msg'],$result);
			//print_r($result);
			$res_checksum = $result[2][0];
			$msg_without_Checksum = $result[1][0];
			//$common_string = "sRKUUgdDrMGL";
			$checksum_key = $this->config->item('bd_ChecksumKey');
			$string_new=$msg_without_Checksum."|".$checksum_key;
			$checksum = crc32($string_new);
			$pg_res = explode("|",$msg_without_Checksum);   //print_r($pg_res); exit;
			// add payment responce in log
			$pg_response = "msg=".$_REQUEST['msg'];
			$this->log_model->logtransaction("billdesk", $pg_response, $pg_res[14]);
			if ($res_checksum == $checksum)
			{
				if($pg_res[16] == "iibfexam")
				{
					$MerchantOrderNo = filter_var($pg_res[1], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
					$transaction_no  = $pg_res[2];
					$payment_status = 2;
					switch ($pg_res[14])
					{
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
					if($payment_status==1)
					{
						// Handle transaction success case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code');
						$exam_code=$get_user_regnum[0]['exam_code'];
						$reg_id=$get_user_regnum[0]['ref_id'];
						//To:Do change uniq application once iibf let us..
						//$last_count = $get_user_regnum[0]['ref_id']; 
						//$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
						//$randomNumber=mt_rand(0,9999);
						//$applicationNo = generate_dbf_reg_num(); //date('Y').$randomNumber.$last_count;	
						$applicationNo = generate_DBF_memreg($reg_id);
						$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
						$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
						$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7]);
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
						$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
						//Query to get exam details	
					   $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
						$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
						if($exam_info[0]['exam_mode']=='ON')
						{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
						{$mode='Offline';}
						else{$mode='';}
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
						//Query to get Medium	
						$this->db->where('exam_code',$exam_code);
						$this->db->where('exam_period',$exam_info[0]['exam_period']);
						$this->db->where('medium_code',$exam_info[0]['exam_medium']);
						$this->db->where('medium_delete','0');
						$medium=$this->master_model->getRecords('medium_master','','medium_description');
						//Query to get Payment details	
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount');
						//Query to get user details
						$this->db->join('state_master','state_master.state_code=member_registration.state');
						//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
						$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
						$upd_files = array();
						$photo_file = 'p_'.$applicationNo.'.jpg';
						$sign_file = 's_'.$applicationNo.'.jpg';
						$proof_file = 'pr_'.$applicationNo.'.jpg';
						if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
						{	$upd_files['scannedphoto'] = $photo_file;	}
						if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
						{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
						if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
						{	$upd_files['idproofphoto'] = $proof_file;	}
						if(count($upd_files)>0)
						{
							$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
						}
						$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
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
						$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
						$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
						$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
						$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
						$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring19);
						$info_arr=array('to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
												'message'=>$final_str
											);
						//send sms					
						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'mUr3FSwGR',$exam_info[0]['exam_code']);						
						//To Do---Transaction email to user	currently we using failure emailer 					
						if($this->Emailsending->mailsend($info_arr))
						{
							redirect(base_url().'Dbfuser/acknowledge/'.base64_encode($applicationNo).'/'.base64_encode($MerchantOrderNo));
							//redirect(base_url().'Home/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);
						}
							else
						{
							echo 'Error while sending email';
							//$this->session->set_flashdata('error','Error while sending email !!');
							//redirect(base_url('register/preview/'));
					}
			}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7]);
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['ref_id']),'firstname,middlename,lastname,email,mobile');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							$info_arr=array(	'to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'].' '.$this->session->userdata('regnumber'),
														'message'=>$final_str
													);
							// send SMS
							$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg',$exam_info[0]['exam_code']);	
							//To Do---Transaction email to user	currently we using failure emailer 					
							if($this->Emailsending->mailsend($info_arr))
							{
								redirect(base_url());
							}
							//echo 'transaction fail';exit;
						}
					}
				///echo "<BR>Checksum validated successfully<br>";
				//echo "SUCCESS:".$pg_res[2];
			}
			else
			{
				//echo "<BR>Checksum validation unsuccessful<br>";
				//echo "INVALID:".$pg_res[2];
			}
			// Redirect to success/failure
		}
		else
		{
			die("Please try again...");	
		}
	}
	
	//validate captcha
	public function ajax_check_captcha()
	{
		$code=$_POST['code'];
		// check if captcha is set -
		if ($code == '' || $_SESSION["dbfmemregcaptcha"] != $code)
		{
			$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
			//$this->session->set_userdata("regcaptcha", rand(1, 100000));
			echo  'false';
		}
		else if ($_SESSION["dbfmemregcaptcha"] == $code)
		{
			//$this->session->unset_userdata("nonmemlogincaptcha");
			// $this->session->set_userdata("mycaptcha", rand(1,100000));
			echo 'true';
		}
	}
	
	//Thank you message to end user
	public function acknowledge($MerchantOrderNo=NULL)
	{
		$decpass='';
		$data=array();
		//Query to get Payment details	
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'transaction_no,date,amount,exam_code,status,member_regnumber');
		if(count($payment_info) <= 0)
		{redirect(base_url());}
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,exam_master.ebook_flag');
		if(count($payment_info) <= 0 || count($exam_info)<=0)
		{redirect(base_url());}
		//Query to get Medium	
		$this->db->where('exam_code',$payment_info[0]['exam_code']);
		$this->db->where('exam_period',$exam_info[0]['exam_period']);
		$this->db->where('medium_code',$exam_info[0]['exam_medium']);
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		//Query to get user details
		$this->db->join('state_master','state_master.state_code=member_registration.state');
		//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
		$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,regnumber,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,regid,isactive,regnumber,registrationtype');	
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		if(count($result) > 0)
		{
		if($result[0]['isactive']==1)
		{
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
			$mysqltime=date("H:i:s");
			$user_data=array('dbregid'=>$result[0]['regid'],
										'dbregnumber'=>$result[0]['regnumber'],
										'dbfirstname'=>$result[0]['firstname'],
										'dbmiddlename'=>$result[0]['middlename'],
										'dblastname'=>$result[0]['lastname'],
										'dbtimer'=>base64_encode($mysqltime),
										'memtype'=>$result[0]['registrationtype'],
										'dbpassword'=>base64_encode($decpass));
			$this->session->set_userdata($user_data);
			$sess = $this->session->userdata();
	 }
		}
		$data=array('application_number'=>$payment_info[0]['member_regnumber'],
		'password'=>$decpass,'payment_info'=>$payment_info,'exam_info'=>$exam_info,'medium'=>$medium,'result'=>$result);
		$this->load->view('dbf/profile_thankyou',$data);
	}
	
	//Generate PDF
	public function pdf()
	{
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata['memberdata']['regno']),'regnumber,usrpassword');
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
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
						Your application saved successfully.<br><br><strong>Your Membership No is</strong> '.$user_info[0]['regnumber'].' <strong>and Your password is </strong>'.$decpass.'<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
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
	
	//Set register user data in session
	//public function setsession()
    //{
		/*$enduserinfo = $this->session->userdata('enduserinfo');
		if(count($enduserinfo))
		{
			$this->session->unset_userdata('enduserinfo');
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
		$tmp_nm = strtotime($date).rand(0,100);
		$outputphoto = getcwd()."/uploads/photograph/non_mem_photo_".$tmp_nm.".jpg";
		$outputphoto1 = base_url()."uploads/photograph/non_mem_photo_".$tmp_nm.".jpg";
		file_put_contents($outputphoto, file_get_contents($input));
		// generate dynamic scan signature
		$inputsignature = $_POST["hiddenscansignature"];
		$tmp_signnm = strtotime($date).rand(0,100);
		$outputsign = getcwd()."/uploads/scansignature/non_mem_sign_".$tmp_signnm.".jpg";
		$outputsign1 = base_url()."uploads/scansignature/non_mem_sign_".$tmp_signnm.".jpg";
		file_put_contents($outputsign, file_get_contents($inputsignature));
		// generate dynamic id proof
		$inputidproofphoto = $_POST["hiddenidproofphoto"];
		$tmp_inputidproof = strtotime($date).rand(0,100);
		$outputidproof = getcwd()."/uploads/idproof/non_mem_idproof_".$tmp_inputidproof.".jpg";
		$outputidproof1 = base_url()."uploads/idproof/non_mem_idproof_".$tmp_inputidproof.".jpg";
		file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
		$dob1= $_POST["dob1"];
		$dob = str_replace('/','-',$dob1);
		$dateOfBirth = date('Y-m-d',strtotime($dob));
		$user_data=array(	'firstname'			=>$_POST["firstname"],
									'sel_namesub'		=>$_POST["sel_namesub"],
									'addressline1'		=>$_POST["addressline1"],
									'addressline2'		=>$_POST["addressline2"],
									'addressline3'		=>$_POST["addressline3"],
									'addressline4'		=>$_POST["addressline4"],
									'city'					=>$_POST["city"],	
									'code'					=>trim($_POST["code"]),
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
									'nameoncard'			=>$_POST["nameoncard"],	
									'optedu'				=>$_POST["optedu"],	
									'optnletter'			=>$_POST["optnletter"],	
									'phone'					=>$_POST["phone"],	
									'pincode'				=>$_POST["pincode"],	
									'state'					=>$_POST["state"],	
									'stdcode'				=>$_POST["stdcode"],
									'scannedphoto'		=>$outputphoto1,
									'scannedsignaturephoto'=>$outputsign1,
									'idproofphoto'		=>$outputidproof1,
									'photoname'			=>"non_mem_photo_".$tmp_nm.".jpg",
									'signname'				=>"non_mem_sign_".$tmp_signnm.".jpg",
									'idname'				=>"non_mem_idproof_".$tmp_inputidproof.".jpg",
									'selCenterName'	=>$_POST["selCenterName"],
									'txtCenterCode'		=>	$_POST["txtCenterCode"],
									'optmode'				=>$_POST["optmode"],
									'exid'					=>$_POST["exid"],
									'mtype'					=>$_POST["mtype"],
									'memtype'				=>$_POST["memtype"],
									'eprid'					=>$_POST["eprid"],
									'rrsub'					=>$_POST["rrsub"],
									'excd'					=>$_POST["excd"],
									'exname'				=>$_POST["exname"],
									'fee'						=>	$_POST["fee"],
									'medium'				=>$_POST['medium']);
		$this->session->set_userdata('enduserinfo',$user_data);
		//echo 'true';
		redirect(base_url().'Dbfuser/preview');
		//$data=array('middle_content'=>'preview_register');
		//$this->load->view('nm_common_view',$data);*/
	 //} 
	 //Preview of register form 
	 
	 public function preview()
    {
		if(!$this->session->userdata('enduserinfo'))
		{
			redirect(base_url());
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
			redirect(base_url().'Dbfuser/accessdenied/');
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
				 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					redirect(base_url().'Dbfuser/member/?Mtype='.$this->session->userdata['enduserinfo']['mtype'].'=&ExId='.$this->session->userdata['enduserinfo']['excd'].'');
				}
			}
		}
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			redirect(base_url().'Dbfuser/member/?Mtype='.$this->session->userdata['enduserinfo']['mtype'].'=&ExId='.$this->session->userdata['enduserinfo']['excd'].'');
		}
		//check exam activation
		$check_exam_activation=check_exam_activate($this->session->userdata['enduserinfo']['excd']);
		if($check_exam_activation==0)
		{
			redirect(base_url().'Dbfuser/accessdenied/');
		}
		//check for valid fee
		if($this->session->userdata['enduserinfo']['fee']==0 || $this->session->userdata['enduserinfo']['fee']=='')
		{
			//echo $this->session->userdata['enduserinfo']['fee'];exit;
			//$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
			redirect('http://iibf.org.in/');
		}
  		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		$institution_master=$this->master_model->getRecords('institution_master');
		$states=$this->master_model->getRecords('state_master');
		$designation=$this->master_model->getRecords('designation_master');
		$idtype_master=$this->master_model->getRecords('idtype_master');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$medium=$this->master_model->getRecords('medium_master',array('medium_master.exam_code'=>$this->session->userdata['enduserinfo']['excd']));
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$center=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd']));
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
		$this->db->like('misc_master.exam_code',$this->session->userdata['enduserinfo']['excd']);
		$exam_period=$this->master_model->getRecords('misc_master','','misc_master.exam_period'); 
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
		$misc=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$this->session->userdata['enduserinfo']['excd'],'misc_delete'=>'0'));
		$disability_value = $this->master_model->getRecords('scribe_disability', array('is_delete' =>0));
		$scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('is_delete' =>0));
		// benchmark disability
		$benchmark_disability_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('dbregnumber')),'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
		 $data=array('benchmark_disability_info' => $benchmark_disability_info,'disability_value' => $disability_value,
		'scribe_sub_disability' => $scribe_sub_disability,'middle_content'=>'dbf/dbf_mem_preview_register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'medium'=>$medium,'center'=>$center,'exam_period'=>$exam_period,'idtype_master'=>$idtype_master,'compulsory_subjects'=>$this->session->userdata['enduserinfo']['subject_arr'],'misc'=>$misc);
		$this->load->view('dbf/common_view_fullwidth',$data);
	 }
	 //Genereate random password function
	
	function generate_random_password($length = 8, $level = 2) // function to generate new password
	{
	list($usec, $sec) = explode(' ', microtime());
	srand((float) $sec + ((float) $usec * 100000));
	$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
	$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
	$password = "";
	$counter = 0;
	while ($counter < $length) {
	$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
	if (!strstr($password, $actChar)) {
        $password .= $actChar;
            $counter++;
        }
    }
    return $password;
}
	 //check mail alredy exist or not
	
	public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$where="( registrationtype='DB')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);}
			else
			{
				//$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				//$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				//$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered email ID / mobile no already exist';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	##---------check mobile number alredy exist or not for non member(prafull)-----------##
	 public function mobileduplication()
	{
		$mobile=$_POST['mobile'];
		if($mobile!="")
		{
			$where="( registrationtype='DB')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);
				}
			else
			{
				//$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				//$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				//$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered email ID / mobile no already exist';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	 
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
	
	##---------End user logout (Vrushali)-----------##//
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		//redirect(base_url().'Dbfuser/memlogin/?Extype=MQ==&Mtype=Tk0='); 
		redirect(base_url().'nonmem/'); 
	}
	
	##---------forget password (prafull)-----------##
	public function forgotpassword()
	{
		$data['page_title']='Forget Password';
		$data['pass_error']=$data['error']='';
		if(isset($_POST['btn_forget_pass']))
		{
			$this->form_validation->set_rules('non_memno','Registration No.','trim|required|xss_clean');
			if($this->form_validation->run())
			{
				$non_memno=$this->input->post('non_memno');
				$this->db->where('isactive','1');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$non_memno));
				if(count($result)>0)
				{
					//generate random password
					$password=$this->generate_random_password();
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					 $key = $this->config->item('pass_key');
					 $aes = new CryptAES();
					 $aes->set_key(base64_decode($key));
					 $aes->require_pkcs5();
					 $encPass = $aes->encrypt($password);
					// update a password in db
					$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass),array('regid'=>$result[0]['regid']));
					$log_arr=array('regnumber'=>$non_memno,'usrpassword'=>$encPass);
					logactivity($log_title ="Forgrt pass DB&F", $log_message = serialize($log_arr));
					if($query)
					{
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_forgetpass'));
							$newstring1 = str_replace("#application_num#", "".$result[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
							$newstring2= str_replace("#password#", "".$password."",  $newstring1);
							$newstring3= str_replace("#username#", "".$userfinalstrname."",  $newstring2);
							$final_str= str_replace("#url#", "".base_url()."",  $newstring3);
							$info_arr=array(
														'to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'].' '.$result[0]['regnumber'],
														'message'=>$final_str
													);
							if($this->Emailsending->mailsend($info_arr))
							{
								//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								 redirect(base_url().'Dbfuser/forgetack/');
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								 redirect(base_url().'Dbfuser/');
							}					
						}
					}
				else
				{
					 $this->session->set_flashdata('error_message','Invalid Membership/Registration No!');
					 redirect(base_url().'Dbfuser/forgotpassword/');
				}
			}
		}
		$this->load->view('dbf/dbf_forgetpass',$data);
	}
	
	//### forget pass acknowledgment
	 public function forgetack()
	 {
		$this->load->view('nonmember/foergetpass_ack');	
	}
 	
	public function accessdenied()
	{
			$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
			$data=array('middle_content'=>'dbf/access-denied-registration','check_eligibility'=>$message);
			$this->load->view('dbf/common_view_fullwidth',$data);
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
		$data=array('payment_info'=>$payment_info,'exam_name'=>$exam_name);
		$this->load->view('dbf/dbfuser_refund',$data);
	}
	
	/* Get scribe drop down*/
    public function getsub_menue() {
        $deptid = $this->input->post('deptid');
        // Code for fetching department Dropdown
       $scribe_sub_disability=$this->master_model->getRecords('scribe_sub_disability',array('code'=>$deptid ,'is_delete'=>'0'));
        // EOF Code fetching department Dropdown
        $department_dropdown = $search_department = '';
        if (!empty($scribe_sub_disability)) {
            $department_dropdown.= "<select class='form-control' id='Sub_menue' name='Sub_menue'>";
                $department_dropdown.= "<option value=''>--Select--</option>";
            foreach ($scribe_sub_disability as $dkey => $dValue) {
                $deptid = $dValue['sub_code'];
                $dept_name = $dValue['sub_disability'];
                $department_dropdown.= "<option value=" . $dValue['sub_code'] . ">" . $dept_name . "</option>";
            }
            $department_dropdown.= "</select>";
            echo $department_dropdown;
        } else {
            echo $department_dropdown = "";
        }
    }
	
	public function setdbf(){ 
		$subject_cnt_arr = array('subject_cnt'=>$_POST['subject_cnt']);
		$this->session->set_userdata($subject_cnt_arr);
	}
	
	
	
	
	function getExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
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
				
				 $today_date='2021-11-22';//date('Y-m-d');
				 //$today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						if($elearning_flag == 'Y'){
							if(isset($CI->session->userdata['examinfo']['el_subject']) && count($CI->session->userdata['examinfo']['el_subject']) > 0 &&(base64_decode($excd) == $this->config->item('examCodeJaiib') || base64_decode($excd) == $this->config->item('examCodeDBF') || base64_decode($excd) ==$this->config->item('examCodeSOB') || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65)){
								 $fee=$getfees[0]['cs_tot'];
							}else{
								$fee=$getfees[0]['elearning_cs_amt_total'];
							}
						}else{
							$fee=$getfees[0]['cs_tot'];
						}
					}
					else
					{
						if($elearning_flag == 'Y'){
							
							if(isset($CI->session->userdata['examinfo']['el_subject']) && count($CI->session->userdata['examinfo']['el_subject']) > 0 &&(base64_decode($excd) == $this->config->item('examCodeJaiib') || base64_decode($excd) == $this->config->item('examCodeDBF') || base64_decode($excd) ==$this->config->item('examCodeSOB') || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65) ){
								$fee=$getfees[0]['igst_tot'];
							}else{
								$fee=$getfees[0]['elearning_igst_amt_total'];
							}
						}else{
							$fee=$getfees[0]['igst_tot'];
						}
					}
				}
			}
		}
		return $fee;
	}
	
	// Get fee only for JAIIB multiple subject elearning selection
	function get_el_ExamFee($centerCode = NULL, $eprid =NULL ,$excd =NULL ,$grp_code=NULL,$memcategory=NULL,$elearning_flag=NULL)
	{
		$fee=0;
		$CI = & get_instance();
		//$centerCode= $_POST['centerCode'];
		//$eprid=$_POST['eprid'];
		//	$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
	
		if($centerCode !=NULL  && $eprid !=NULL  && $excd !=NULL  && $grp_code !=NULL  && $memcategory !=NULL )
		{
			$getstate=$CI->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			//echo $CI->db->last_query();exit;
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
				
				 $today_date='2021-11-22';//date('Y-m-d');
				 //$today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
				//echo $CI->db->last_query();exit;
				if(count($getfees) <=0)
				{
					$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>$excd,'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code));
				}
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
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
		}
		return $fee;
	}
	
	
}