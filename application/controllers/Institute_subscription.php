<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Institute_subscription extends CI_Controller 
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
			$this->load->model('billdesk_pg_model');
			$this->load->model('Captcha_model');
			$this->chk_session->Check_mult_session();	
		}
		
		public function index() { redirect(site_url('institute_subscription/login')); }		
		
		public function login()//START : LOGIN
		{
			//START : check session already started for user
			$login_type = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_TYPE');
			$login_institute_no = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_INSTITUTE_NO');
			$login_invoice_no = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_INVOICE_NO');
			
			if(isset($login_type) && $login_type == 'INSTITUTE_SUBSCRIPTION' && isset($login_institute_no) && $login_institute_no != '' && isset($login_invoice_no) && $login_invoice_no != '')
			{
				redirect(site_url('institute_subscription/dashboard'));
			}
			else
			{
				$this->unset_session_data();
			}
			//END : check session already started for user
			
			$data=array();
			$data['error']='';
						
			if(isset($_POST) && count($_POST) > 0)
			{  
				$this->form_validation->set_rules('institute_no', 'Institute No.', 'trim|required|xss_clean',array('required' => 'Please select the %s'));
				$this->form_validation->set_rules('invoice_no', 'Invoice No', 'trim|required|callback_validate_login[1]|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('code', 'Code', 'trim|required|callback_validate_captcha_code[1]|xss_clean',array('required' => 'Please enter the %s'));
				
				if($this->form_validation->run() == TRUE)
				{
					$institute_no = $this->security->xss_clean(trim($this->input->post('institute_no')));
					$invoice_no = $this->security->xss_clean(trim($this->input->post('invoice_no')));
					
					$user_data = array('INSTITUTE_SUBSCRIPTION_LOGIN_TYPE'=>'INSTITUTE_SUBSCRIPTION', 'INSTITUTE_SUBSCRIPTION_LOGIN_INSTITUTE_NO'=>$institute_no, 'INSTITUTE_SUBSCRIPTION_LOGIN_INVOICE_NO'=>$invoice_no);
					$this->session->set_userdata($user_data);
					
					redirect(site_url('institute_subscription/dashboard'));
				}
			}
			
			$captcha_img = $this->Captcha_model->generate_captcha_img('INSTITUTE_SUBSCRIPTION_CAPTCHA');
			$data['image'] = $captcha_img;
			
			$this->load->view('institute_subscription/login',$data);
		}//END : LOGIN
				
		public function generatecaptchaajax()//START : GENERATE CAPTCHA CODE AJAX
		{
			echo $captcha_img = $this->Captcha_model->generate_captcha_img('INSTITUTE_SUBSCRIPTION_CAPTCHA');
		}//END : GENERATE CAPTCHA CODE AJAX	
		
		public function validate_captcha_code($code_str="", $type="0")//START : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE // 0 => Ajax, 1=>Server
		{
			$session_name = 'INSTITUTE_SUBSCRIPTION_CAPTCHA';
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
		
		public function validate_login($str="", $type="0")//START : LOGIN VALIDATION FOR SERVER SIDE AND CLIENT SIDE // type = 0 => Ajax, 1=>Server
		{
			$error_msg = 'Invalid request';
			$flag = 'error';
			$return_val = FALSE;
			
			$institute_no = $invoice_no = "";
			if(isset($_POST) && count($_POST) > 0)
			{
				$institute_no = $this->security->xss_clean(trim($this->input->post('institute_no')));
				$invoice_no = $this->security->xss_clean(trim($this->input->post('invoice_no')));
				
				if($institute_no == "" || $invoice_no == "")
				{
					//$flag = 'success';
					//$error_msg = "";
					//$return_val = TRUE;
				}
				else if($institute_no != "" && $invoice_no != "")
				{				
					$response_res = $this->validate_login_by_api($institute_no,$invoice_no);
					if($response_res['flag'] == 'success')
					{
						$flag = 'success';
						$error_msg = "";
						$return_val = TRUE;
					}
					else
					{
						$error_msg = $response_res['error_msg'];
					}
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
				$this->form_validation->set_message('validate_login', $error_msg); 
				return $return_val;  
			}
		}//END : LOGIN VALIDATION FOR SERVER SIDE AND CLIENT SIDE		
		
		function validate_login_by_api($institute_no=0,$invoice_no=0)
		{
			$res_institute_no = $res_invoice_no = $res_institute_name = $res_subscription_base_amount = $res_subscription_gst_amount = $res_subscription_year = $res_invoice_pdf = '';
			
			$flag = 'error';
			$error_msg = "Invalid combination of Institute No & Invoice No";
			
			$response = $this->master_model->institute_subscription_api_curl($institute_no,$invoice_no);
      $response_res = json_decode($response,true);
			//echo '<pre>'; print_r($response_res); echo '</pre>'; exit;
						
			if(count($response_res) > 0)
			{
				$response_flag = '';
				if(isset($response_res['response'])) { $response_flag = $response_res['response']; }
				
        if($response_flag == 'success')
				{
          $response_msg_str = '';
					if(isset($response_res['response_msg'])) { $response_msg_str = $response_res['response_msg']; }
					
					if($response_msg_str != "")
					{
						$response_msg_arr = json_decode($response_msg_str,true);
						$response_msg_arr = $response_msg_arr[0];
            //echo '<pre>'; print_r($response_msg_arr); echo '</pre>'; exit;
						
						if(count($response_msg_arr) > 0)
						{
							if(isset($response_msg_arr[0])) { $res_institute_no = $response_msg_arr[0]; }
							if(isset($response_msg_arr[1])) { $res_invoice_no = $response_msg_arr[1]; }
							if(isset($response_msg_arr[2])) { $res_institute_name = $response_msg_arr[2]; }								
							if(isset($response_msg_arr[3])) { $res_subscription_base_amount = $response_msg_arr[3]; }								
							if(isset($response_msg_arr[4])) { $res_subscription_gst_amount = $response_msg_arr[4]; }								
							if(isset($response_msg_arr[5])) { $res_subscription_year = $response_msg_arr[5]; }								
							if(isset($response_msg_arr[6])) { $res_invoice_pdf = $response_msg_arr[6]; }								
							
							//echo $res_institute_no.' == '.$institute_no.' && '.$res_invoice_no.' == '.$invoice_no.' && '.$res_amount.' > 0';
							
							if($res_institute_no == $institute_no && $res_invoice_no == $invoice_no && $res_subscription_base_amount > 0 && $res_subscription_gst_amount > 0)
							{
								$flag = 'success';
								$error_msg = "";
							}
						}
					}					
				}
				else { if(isset($response_res['response_msg'])) { $error_msg = $response_res['response_msg']; } }
			}
						
			$result['flag'] = $flag;
			$result['institute_no'] = $res_institute_no;
			$result['invoice_no'] = $res_invoice_no;
			$result['institute_name'] = $res_institute_name;
			$result['subscription_base_amount'] = $res_subscription_base_amount;
			$result['subscription_gst_amount'] = $res_subscription_gst_amount;
			$result['subscription_year'] = $res_subscription_year;
			$result['invoice_pdf'] = $res_invoice_pdf;
			$result['error_msg'] = $error_msg;
			return $result;
		}
		
		public function dashboard()//START : DASHBOARD
		{
			$institute_data = $this->after_login_institute_data(); 
			//echo '<pre>'; print_r($institute_data); echo '</pre>';  exit;
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $invoice_pdf = $institute_data['invoice_pdf'];
      
      $data['last_payment_data'] = $last_payment_data = $this->after_login_check_payment_data();
						
			/* $this->send_mail_common('success', $institute_no, 'INSTITUTE SUBSCRIPTION', $amount, '123456', date('Y-m-d H:i:s'));
			$this->send_mail_common('fail', $institute_no, 'INSTITUTE SUBSCRIPTION', $amount, '123456', date('Y-m-d H:i:s')); */
			
			if (isset($_POST['institute_subscription']))
			{	        
        $this->form_validation->set_rules('subscription_base_amount', 'Subscription Base Amount', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('subscription_gst_amount', 'Subscription GST Amount ', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));
				
        $this->form_validation->set_rules('is_it_tds_applicable', 'IT TDS option', 'trim|required|xss_clean',array('required' => 'Please select the %s'));
        if($_POST['is_it_tds_applicable'] == 'yes') 
        { 
          $this->form_validation->set_rules('it_tds_percentage_rate', 'IT TDS Percentage', 'trim|required|xss_clean',array('required' => 'Please select the %s')); 
          $this->form_validation->set_rules('it_tds_percentage_amount', 'IT TDS Amount', 'trim|required|xss_clean',array('required' => 'Please select the %s')); 
        }

        $this->form_validation->set_rules('is_gst_tds_applicable', 'GST TDS option', 'trim|required|xss_clean',array('required' => 'Please select the %s'));
        if($_POST['is_gst_tds_applicable'] == 'yes') 
        { 
          $this->form_validation->set_rules('gst_tds_percentage_rate', 'GST TDS Percentage', 'trim|required|xss_clean',array('required' => 'Please select the %s')); 
          $this->form_validation->set_rules('gst_tds_percentage_amount', 'GST TDS Amount', 'trim|required|xss_clean',array('required' => 'Please select the %s')); 
        }

        if($this->form_validation->run() == TRUE) 
				{	
          //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
          //if need to check member validation in esds database, then code goes here
					
					$posted_subscription_base_amount = $this->security->xss_clean(trim($this->input->post('subscription_base_amount')));
					$posted_subscription_gst_amount = $this->security->xss_clean(trim($this->input->post('subscription_gst_amount')));
					$posted_final_paid_amount = $this->security->xss_clean(trim($this->input->post('final_paid_amount')));
          
          $is_it_tds_applicable = $this->security->xss_clean(trim($this->input->post('is_it_tds_applicable')));
          $posted_it_tds_percentage_rate = $this->security->xss_clean(trim($this->input->post('it_tds_percentage_rate')));
          $posted_it_tds_percentage_amount = $this->security->xss_clean(trim($this->input->post('it_tds_percentage_amount')));

          $is_gst_tds_applicable = $this->security->xss_clean(trim($this->input->post('is_gst_tds_applicable')));
          $posted_gst_tds_percentage_rate = $this->security->xss_clean(trim($this->input->post('gst_tds_percentage_rate')));
          $posted_gst_tds_percentage_amount = $this->security->xss_clean(trim($this->input->post('gst_tds_percentage_amount')));
          
          $calculated_final_paid_amount = $subscription_base_amount + $subscription_gst_amount;
          $calculated_it_tds_percentage_amount = $calculated_gst_tds_percentage_amount = 0;
          
          if($is_it_tds_applicable == 'yes') 
          {
            $calculated_it_tds_percentage_amount = $subscription_base_amount * ($posted_it_tds_percentage_rate / 100);
            $calculated_final_paid_amount = $calculated_final_paid_amount -  $calculated_it_tds_percentage_amount;
          }

          if($is_gst_tds_applicable == 'yes') 
          {
            $calculated_gst_tds_percentage_amount = $subscription_base_amount * ($posted_gst_tds_percentage_rate / 100);
            $calculated_final_paid_amount = $calculated_final_paid_amount -  $calculated_gst_tds_percentage_amount;
          }
          
          if( $subscription_base_amount > 0 && $subscription_base_amount == $posted_subscription_base_amount && 
              $subscription_gst_amount > 0 && $subscription_gst_amount == $posted_subscription_gst_amount &&
              $calculated_it_tds_percentage_amount == $posted_it_tds_percentage_amount &&
              $calculated_gst_tds_percentage_amount == $posted_gst_tds_percentage_amount && 
              $calculated_final_paid_amount > 0 && $calculated_final_paid_amount == $posted_final_paid_amount )
					{
						//echo '<pre>'; print_r($_POST); echo '</pre>'; exit;

            //checked for application in payment process and prevent user to apply exam on the same time(Prafull)
            $this->check_last_payment($institute_no);            
						
						$add_rec['institute_no'] = $institute_no;
						$add_rec['invoice_no'] = $invoice_no;
						$add_rec['institute_name'] = $institute_name;
						$add_rec['subscription_base_amount'] = $subscription_base_amount;
						$add_rec['subscription_gst_amount'] = $subscription_gst_amount;
            
						$add_rec['is_it_tds_applicable'] = $is_it_tds_applicable;
						$add_rec['it_tds_percentage_rate'] = $posted_it_tds_percentage_rate;
						$add_rec['it_tds_percentage_amount'] = $posted_it_tds_percentage_amount;

						$add_rec['is_gst_tds_applicable'] = $is_gst_tds_applicable;
						$add_rec['gst_tds_percentage_rate'] = $posted_gst_tds_percentage_rate;
						$add_rec['gst_tds_percentage_amount'] = $posted_gst_tds_percentage_amount;

						$add_rec['amount_to_be_paid'] = $calculated_final_paid_amount;
            $add_rec['subscription_year'] = $subscription_year;
						$add_rec['invoice_pdf'] = $invoice_pdf;
						$add_rec['created_on'] = date('y-m-d H:i:s');
						$subscription_id = $this->master_model->insertRecord('institute_subscription', $add_rec, true);
						$this->session->userdata['INSTITUTE_SUBSCRIPTION_ID'] = $subscription_id; 
							
						redirect(site_url('institute_subscription/make_payment'));								
					}
					else
					{
            $this->session->set_flashdata('error','Invalid fee selection');
						redirect(site_url('institute_subscription/dashboard'));
					}
				} 
			}			
			
			$data['act_id'] = $data['sub_act_id'] = 'dashboard';
			$this->load->view('institute_subscription/dashboard',$data);
		}//END : DASHBOARD

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/
    public function valid_tds_amount($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      //CURRENTLY NOT IN USE
      /* $tds_amount = $str;
      $subscription_amount = $this->security->xss_clean($this->input->post('amount')); 
      if($tds_amount > 0 && $tds_amount < $subscription_amount)
      {
        if(is_numeric($tds_amount))
        {
          return TRUE;
        }
        else
        {
          $this->form_validation->set_message('valid_tds_amount','Please enter the valid TDS amount between 1 to '.($subscription_amount - 1));
          return false;
        }         
      }
      else
      {
        $this->form_validation->set_message('valid_tds_amount','Please enter the TDS amount between 1 to '.($subscription_amount - 1));
        return false;
      } */      
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/
		
		function after_login_institute_data()
		{
			$login_institute_no = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_INSTITUTE_NO');
			$login_invoice_no = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_INVOICE_NO');
			$institute_data = $this->validate_login_by_api($login_institute_no,$login_invoice_no);
			//echo '<pre>'; print_r($institute_data); echo '</pre>';  exit;
			
			if(count($institute_data) > 0 && isset($institute_data['flag']) && $institute_data['flag'] == 'success' && isset($institute_data['institute_no']) && $institute_data['institute_no'] == $login_institute_no && isset($institute_data['invoice_no']) && $institute_data['invoice_no'] == $login_invoice_no && isset($institute_data['subscription_base_amount']) && $institute_data['subscription_base_amount'] > 0 && isset($institute_data['subscription_gst_amount']) && $institute_data['subscription_gst_amount'] > 0)
			{
				$data['institute_no'] = $institute_data['institute_no'];
				$data['invoice_no'] = $institute_data['invoice_no'];
				$data['institute_name'] = $institute_data['institute_name'];
				
        //$data['amount'] = $institute_data['amount'];
        /* $amount = $institute_data['amount'];
        $data['subscription_base_amount'] = $base_amount = $amount / (1 + (18 / 100));
        $data['subscription_gst_amount'] = $amount - $base_amount; */

        $data['subscription_base_amount'] = $institute_data['subscription_base_amount'];
        $data['subscription_gst_amount'] = $institute_data['subscription_gst_amount'];

				$data['subscription_year'] = $institute_data['subscription_year'];
				$data['invoice_pdf'] = $institute_data['invoice_pdf'];
				
				return $data;				
			}
			else
			{
				redirect(site_url('institute_subscription/logout'));
			}
		}
		
		function after_login_check_payment_data($enc_receipt_no=0)
		{
			$this->db->limit(1);
			$this->db->order_by('is.subscription_id','DESC');
			
			if($enc_receipt_no == '0')
			{
				$login_institute_no = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_INSTITUTE_NO');
				$login_invoice_no = $this->session->userdata('INSTITUTE_SUBSCRIPTION_LOGIN_INVOICE_NO');
				$this->db->where('is.institute_no', $login_institute_no);
				$this->db->where('is.invoice_no', $login_invoice_no);
				$this->db->where('pt.status', '1');
			}
			else
			{
				$receipt_no = base64_decode($enc_receipt_no);
				$this->db->where('pt.receipt_no', $receipt_no);
			}
			
			$this->db->join('payment_transaction pt', 'pt.member_regnumber = is.institute_no AND pt.ref_id = is.subscription_id');
			$last_payment_data = $this->master_model->getRecords('institute_subscription is',array('pt.pay_type' => '24'), 'is.subscription_id, is.institute_no, is.invoice_no, is.institute_name, is.subscription_year, is.invoice_pdf, is.subscription_base_amount, is.subscription_gst_amount, is.is_it_tds_applicable, is.it_tds_percentage_rate, is.it_tds_percentage_amount, is.is_gst_tds_applicable, is.gst_tds_percentage_rate, is.gst_tds_percentage_amount, is.amount_to_be_paid, pt.gateway, pt.amount, pt.date, pt.transaction_no, pt.receipt_no, pt.status AS PaymentStatus');
			return $last_payment_data;
		}
		
		public function logout()//START : LOGOUT
		{
			$this->unset_session_data();
			redirect(site_url('institute_subscription/login'));
		}//END : LOGOUT
		
		function unset_session_data() //START : UNSET SESSION DATA
		{
			$user_data = array('INSTITUTE_SUBSCRIPTION_LOGIN_TYPE'=>'', 'INSTITUTE_SUBSCRIPTION_LOGIN_INSTITUTE_NO'=>'', 'INSTITUTE_SUBSCRIPTION_LOGIN_INVOICE_NO'=>'');
			$this->session->set_userdata($user_data);
		}//END : UNSET SESSION DATA
		
		public function make_payment()
		{
			$institute_data = $this->after_login_institute_data(); 
			
      $data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];

      $institute_last_record = $this->master_model->getRecords('institute_subscription',array('institute_no' => $institute_no, 'invoice_no' => $invoice_no, 'institute_name' => $institute_name, 'subscription_base_amount' => $subscription_base_amount, 'subscription_gst_amount' => $subscription_gst_amount,  'subscription_year' => $subscription_year), 'amount_to_be_paid', array('subscription_id'=>'DESC'), '0', '1');

      if(count($institute_last_record) > 0)
      {			
        $data['amount'] = $amount = $institute_last_record[0]['amount_to_be_paid'];

        $last_payment_data = $this->after_login_check_payment_data();
        if(count($last_payment_data) > 0 && date("Y-m-d", strtotime($last_payment_data[0]['date'])) == date("Y-m-d"))
        {
          $this->session->set_flashdata('error', 'You have already paid the subscription amount');
          redirect(site_url('institute_subscription/dashboard'));
        }
              
        $subscription_id = $this->session->userdata('INSTITUTE_SUBSCRIPTION_ID');			
              
        if(isset($_POST['processPayment']) && $_POST['processPayment'])
        {
          $pg_name = 'sbi';
          if(isset($_POST['pg_name']) && $_POST['pg_name'] != "")
          {
            $pg_name = $this->input->post('pg_name');
          }
          
          $institute_subscription_pay_type = "24";
          $pg_flag = 'IIBF_INST_SUB';
          
          //checked for application in payment process and prevent user to apply exam on the same time(Prafull)
          $this->check_last_payment($institute_no);				
          
          if($amount == 0 || $amount == '')
          {
            $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
            redirect(site_url('institute_subscription/dashboard'));
          }
          
          // $MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
          // Ordinary member Apply exam
          //	Ref1 = orderid
          //	Ref2 = iibfexam
          //	Ref3 = member reg num
          //	Ref4 = exam_code + exam year + exam month ex (101201602)
          $ref4 = $institute_no.date("Ym");
                
          // Create transaction
          $add_data['member_regnumber'] = $institute_no;
          $add_data['amount'] = $amount;
          $add_data['gateway'] = "sbiepay";
          $add_data['date'] = date('Y-m-d H:i:s');
          $add_data['pay_type'] = $institute_subscription_pay_type;
          $add_data['ref_id'] = $subscription_id;
          $add_data['description'] = 'INSTITUTE SUBSCRIPTION';
          $add_data['status'] = '2';
          $add_data['exam_code'] = '';
          $add_data['pg_flag'] = $pg_flag;
          $pt_id = $this->master_model->insertRecord('payment_transaction', $add_data, true);
          
          $MerchantOrderNo = sbi_exam_order_id($pt_id);
          // payment gateway custom fields - 
          $custom_field = $MerchantOrderNo . "^IIBF_INST_SUB^" . $institute_no . "^" . $ref4;
          $custom_field_billdesk = $MerchantOrderNo . "-IIBF_INST_SUB-" . $institute_no . "-" . $ref4;
          
          // update receipt no. in payment transaction -
          $update_data['receipt_no']= $MerchantOrderNo;
          $update_data['pg_other_details'] = $custom_field;
          $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));       
          
          if ($pg_name == 'sbi')
          {
            include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $merchIdVal = $this->config->item('sbi_merchIdVal');
            $AggregatorId = $this->config->item('sbi_AggregatorId');
            $pg_success_url = site_url("institute_subscription/sbitranssuccess");
            $pg_fail_url = site_url("institute_subscription/sbitransfail");
            
            // set cookie for Apply Exam
            applyexam_set_cookie($subscription_id);
            $MerchantCustomerID = $institute_no;
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
          elseif ($pg_name == 'billdesk') 
          { 
            $update_payment_data = array('gateway' =>'billdesk');
            $this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
            
            $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $institute_no, $institute_no, '', 'institute_subscription/handle_billdesk_response', '', '', '', $custom_field_billdesk);
                      
            //echo '<pre>'; print_r($billdesk_res); exit;
            if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') 
            {
              $data['bdorderid'] = $billdesk_res['bdorderid'];
              $data['token'] = $billdesk_res['token'];
              $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
              $data['returnUrl'] = $billdesk_res['returnUrl'];
              $data['ins_subscription_pg_flag'] = 'IIBF_INST_SUB';
              $this->load->view('pg_billdesk/pg_billdesk_form', $data);
            }
            else
            {
              $this->session->set_flashdata('error','Transaction failed...!');
              redirect(site_url('institute_subscription/dashboard'));
            }
          }
        }
        else
        {
          $data['show_billdesk_option_flag'] = 1;
          $this->load->view('pg_sbi/make_payment_page',$data);
        }
      }
      else
      {
        $this->session->set_flashdata('error','Invalid fee selection');
        redirect(site_url('institute_subscription/dashboard'));
      }
		}

    public function check_last_payment($institute_no=0)
    {
      $institute_subscription_pay_type = '24';
      //checked for application in payment process and prevent user to apply exam on the same time(Prafull)
      $checkpayment = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$institute_no,'status'=>'2','pay_type'=>$institute_subscription_pay_type),'',array('id'=>'DESC'));
      if(count($checkpayment) > 0)
      {
        $endTime = date("Y-m-d H:i:s",strtotime("+1 minutes",strtotime($checkpayment[0]['date'])));
        $current_time= date("Y-m-d H:i:s");
        if(strtotime($current_time)<=strtotime($endTime))
        {
          $this->session->set_flashdata('error','Please wait for 1 minute as your transaction is being processed.');
          redirect(site_url('institute_subscription/dashboard'));
        }
      }
    }
		
		public function handle_billdesk_response()
    {
			/* ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL); */
						
			$institute_data = $this->after_login_institute_data(); 
			//echo '<pre>'; print_r($institute_data); echo '</pre>';  exit;
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			$subscription_id = $this->session->userdata('INSTITUTE_SUBSCRIPTION_ID');
			
			if (isset($_REQUEST['transaction_response'])) 
			{
				$response_encode = $_REQUEST['transaction_response'];
				$bd_response = $this->billdesk_pg_model->verify_res($response_encode);
				$responsedata = $bd_response['payload'];
				
				$MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
				$transaction_no  = $responsedata['transactionid'];
				$merchIdVal = $responsedata['mercid'];
				$Bank_Code = $responsedata['bankid'];
				$encData = $_REQUEST['transaction_response'];
				
				$transaction_error_type = $responsedata['transaction_error_type'];				
				if($transaction_error_type == "success")
        {
          $payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');					
					
					if($payment_data[0]['status'] == 2)//IF payment status is pending
					{
						// ######## payment Transaction ############
						$update_data['transaction_no'] = $transaction_no;
						$update_data['status'] = 1;
						$update_data['transaction_details'] = $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'];
						$update_data['auth_code'] = '0300';
						$update_data['bankcode'] = $responsedata['bankid'];
						$update_data['paymode'] = $responsedata['txn_process_type'];
						$update_data['callback'] = 'B2B';						
						$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
						
						// ######## Insert Log ############
						$query_update_payment_tra = $this->db->last_query();
						$log_title = "Institute_subscription.php ctrl query_update_payment_tra :" . $query_update_payment_tra;
						$log_message = serialize($update_data);
						$rId = $payment_data[0]['member_regnumber'];
						$regNo = $payment_data[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
						
						$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status, date');
							
						if($get_payment_status[0]['status'] == 0)
						{
							redirect(base_url() . 'institute_subscription/refund/' . base64_encode($MerchantOrderNo));
						}
					}
					
					// Query to get Payment details
					$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $payment_data[0]['member_regnumber']), 'transaction_no, date, amount, id, member_regnumber, description');
										
					$this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
						
					// Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata['transaction_error_type']);
					$this->session->set_flashdata('success','Your transactions is successful.');
					redirect(site_url('institute_subscription/success/'. base64_encode($MerchantOrderNo)));
				}
				else
				{
					$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo) , 'member_regnumber,ref_id,status');
				
					if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2)
					{
						$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => 0,
						'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
						'auth_code' => 0399,
						'bankcode' => $responsedata['bankid'],
						'paymode' => $responsedata['txn_process_type'],						
						'callback' => 'B2B'
						);					
						$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						
						// Query to get Payment details
						$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no, date, amount, id, member_regnumber, description');
						
						$this->send_mail_common('fail', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
						
						// Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
					}
														
					redirect(site_url('institute_subscription/fail/'. base64_encode($MerchantOrderNo)));
				}
			}
			else 
			{
				die("Please try again...");
			}
    }    
				
		public function sbitranssuccess()
		{
			$institute_data = $this->after_login_institute_data(); 
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|", $encData);
			$MerchantOrderNo = $responsedata[0];
			$transaction_no = $responsedata[1];
			
			if (isset($_REQUEST['merchIdVal'])) { $merchIdVal = $_REQUEST['merchIdVal']; }
			if (isset($_REQUEST['Bank_Code'])) { $Bank_Code = $_REQUEST['Bank_Code']; }
			if (isset($_REQUEST['pushRespData'])) { $encData = $_REQUEST['pushRespData']; }			
			
			// Sbi B2B callback
			// check sbi payment status with MerchantOrderNo
			$q_details = sbiqueryapi($MerchantOrderNo);
			if($q_details)
			{
				if ($q_details[2] == "SUCCESS")
				{
					$payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');					
					
					if($payment_data[0]['status'] == 2)//IF payment status is pending
					{
						// ######## payment Transaction ############
						$update_data['transaction_no'] = $transaction_no;
						$update_data['status'] = 1;
						$update_data['transaction_details'] = $responsedata[2] . " - " . $responsedata[7];
						$update_data['auth_code'] = '0300';
						$update_data['bankcode'] = $responsedata[8];
						$update_data['paymode'] = $responsedata[5];
						$update_data['callback'] = 'B2B';						
						$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
						
						// ######## Insert Log ############
						$query_update_payment_tra = $this->db->last_query();
						$log_title = "Institute_subscription.php ctrl query_update_payment_tra :" . $query_update_payment_tra;
						$log_message = serialize($update_data);
						$rId = $payment_data[0]['member_regnumber'];
						$regNo = $payment_data[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						
						$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status, date');
							
						if($get_payment_status[0]['status'] == 0)
						{
							redirect(base_url() . 'institute_subscription/refund/' . base64_encode($MerchantOrderNo));
						}
					}
					
					// Query to get Payment details
					$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $payment_data[0]['member_regnumber']), 'member_regnumber, transaction_no, date, amount, id');
					
					$this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
					
					// Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					$this->session->set_flashdata('success','Your transactions is successful.');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Error occurred.');
			}
			redirect(site_url('institute_subscription/success/'. base64_encode($MerchantOrderNo)));
		}
		
		public function success($order_no = NULL)
		{
			$institute_data = $this->after_login_institute_data(); 
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			// payment detail
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no) ,
			'member_regnumber' => $institute_no));
			if (count($payment_info) <= 0)
			{
				redirect(base_url('institute_subscription/dashboard'));
			}
			
			$data['payment_info'] = $payment_info;
			$data['act_id'] = $data['sub_act_id'] = 'dashboard';
			$this->load->view('institute_subscription/exam_applied_success',$data); 
		}
				
		public function sbitransfail()
		{
			$institute_data = $this->after_login_institute_data(); 
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
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
					'member_regnumber' => $get_user_regnum[0]['member_regnumber']) , 'member_regnumber, transaction_no,date,amount, ref_id');
					
					$this->send_mail_common('fail', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
										
					// Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				// End Of SBICALL Back
								
				redirect(site_url('institute_subscription/fail/'. base64_encode($MerchantOrderNo)));
			}
			else { die("Please try again..."); }
		}
		
		public function fail($order_no = NULL)
		{
			$institute_data = $this->after_login_institute_data(); 
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			// payment detail
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no) ,
			'member_regnumber' => $institute_no));
			if (count($payment_info) <= 0)
			{
				redirect(base_url('institute_subscription/dashboard'));
			}
			
			$data['payment_info'] = $payment_info;
			$data['act_id'] = $data['sub_act_id'] = 'dashboard';
			$this->load->view('institute_subscription/exam_applied_fail',$data);
		}
		
		function send_mail_common($mail_type='',$member_regnumber=0, $exam_name='', $amount=0, $transaction_no='', $payment_date='', $attachpath='')
		{
			$institute_data = $this->after_login_institute_data(); 
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			$username = $institute_name;
			$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
			
			$info_arr['to'] = array('iibfdevp@esds.co.in', 'logs@iibf.esdsconnect.com');
			$info_arr['from'] = "logs@iibf.esdsconnect.com";
			$info_arr['cc'] = "iibfdevp@esds.co.in";
			$info_arr['subject'] = 'Institute Subscription Payment Acknowledgement : '.$member_regnumber;
						
			$final_str = '';
			if($mail_type == 'success')
			{
				$top_tr = '<tr>
											<td colspan="2"><p style="margin: 10px 0;">Dear '.$userfinalstrname.'<br><br>Your transaction has been success.</p></td>
										</tr>';
				$transaction_status = 'Success';
			}
			else if($mail_type == 'fail')
			{
				$top_tr = '<tr>
											<td colspan="2">
												<p style="margin: 10px 0;">Dear '.$userfinalstrname.'<br><br>Please note that your transaction has failed. However kindly note down your transaction ID No. for future correspondence.</p>
											</td>
										</tr>';
				$transaction_status = 'Fail';				
			}
			
			$final_str = '<div style="max-width:600px; width:100%; margin:20px auto;">
												<table style="width:100%; background:#FFFFCC;" cellspacing="5" cellpadding="5" border="1">
													<tbody style="">
														
														<tr><td colspan="2"><h2 style="margin: 10px 0; text-align: center; ">Transaction Details</h2></td></tr>
														
														'.$top_tr.'
														
														<tr style="">
															<td><p style=""><strong style="">Institute No : </strong></p></td><td>'.$member_regnumber.'</td>
														</tr>
														
														<tr style="">
															<td><p style=""><strong style="">Institute Name : </strong></p></td><td>'.$userfinalstrname.'</td>
														</tr>
																			
														<tr style="">
															<td><p style=""><strong style="">Amount : </strong></p></td><td>'.$amount.'</td>
														</tr>
																			
														<tr style="">
															<td><p style=""><strong style="">Transaction Status : </strong></p></td><td>'.$transaction_status.'</td>
														</tr>
														
														<tr>
															<td><p><strong>Transaction ID:</strong></p></td><td>'.$transaction_no.'</td>
														</tr>
														
														<tr>
															<td><p><strong>Transaction Date :</strong> </p></td><td>'.date('Y-m-d H:i:s A', strtotime($payment_date)).'</td>
														</tr>
													</tbody>
												</table>	
												<p>Yours truly,<br>IIBF Team</p>
											</div>';
			$info_arr['message'] = $final_str;
			
			//$response = $this->Emailsending->mailsend($info_arr);
			$response = $this->Emailsending->mailsend_attch($info_arr, array());
			//echo '<br>'.$mail_type." >> ".$response;
		}
		
		public function refund($order_no = 0)
		{
			$institute_data = $this->after_login_institute_data(); 
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			//echo base64_encode($order_no);
			if($order_no == '0') { redirect(site_url('institute_subscription/logout')); }
			
			$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no)));
			
			if (count($payment_info) <= 0)
			{
				redirect(base_url('institute_subscription/dashboard'));
			}
			
			##adding below code for processing the refund process - added by chaitali on 2021-09-17						
			$insert_data = array('receipt_no'=>base64_decode($order_no),'transaction_no'=>$payment_info[0]['transaction_no'],'refund'=>'0','created_on'=>date('Y-m-d'),'email_flag'=>'0','sms_flag'=>'0');				
			$this->master_model->insertRecord('exam_payment_refund',$insert_data);			
			//echo $this->db->last_query(); die;		
			## ended insert code
			
			$data = array('payment_info' => $payment_info, 'exam_name' => $institute_name);
			$this->load->view('institute_subscription/member_refund', $data);
		}
		
		function payment_history()
		{
			$institute_data = $this->after_login_institute_data(); 
			//echo '<pre>'; print_r($institute_data); echo '</pre>';  exit;
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			//$data['amount'] = $amount = $institute_data['amount'];
			//$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			//$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			$this->db->order_by('is.subscription_id','DESC');
			$this->db->join('payment_transaction pt', 'pt.member_regnumber = is.institute_no AND pt.ref_id = is.subscription_id');
			$payment_history_data = $this->master_model->getRecords('institute_subscription is',array('is.institute_no'=> $institute_no, 'is.invoice_no'=> $invoice_no, 'pt.pay_type' => '24'), 'is.subscription_id, is.institute_no, is.invoice_no, is.subscription_year, is.subscription_base_amount, is.subscription_gst_amount, is.is_it_tds_applicable, is.it_tds_percentage_rate, is.it_tds_percentage_amount, is.is_gst_tds_applicable, is.gst_tds_percentage_rate, is.gst_tds_percentage_amount, pt.gateway, pt.amount, pt.date AS PaymentDate, pt.transaction_no, pt.receipt_no, pt.status AS PaymentStatus');
			
			$data['payment_history_data'] = $payment_history_data;
			$data['act_id'] = $data['sub_act_id'] = 'payment_history';
			$this->load->view('institute_subscription/payment_history',$data);
		}
		
		function receipt($enc_receipt_no=0)
		{
			$institute_data = $this->after_login_institute_data(); 
			//echo '<pre>'; print_r($institute_data); echo '</pre>';  exit;
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $institute_data['invoice_pdf'];
			
			$data['payment_data'] = $payment_data = $this->after_login_check_payment_data($enc_receipt_no);
			
			if(count($payment_data) == 0)
			{
				redirect(site_url('institute_subscription/payment_history'));
			}
			$this->load->view('institute_subscription/receipt',$data);
		}
		
		function invoice($enc_receipt_no=0)
		{
			$institute_data = $this->after_login_institute_data(); 
			//echo '<pre>'; print_r($institute_data); echo '</pre>';  exit;
			
			$data['institute_no'] = $institute_no = $institute_data['institute_no'];
			$data['invoice_no'] = $invoice_no = $institute_data['invoice_no'];
			$data['institute_name'] = $institute_name = $institute_data['institute_name'];
			$data['subscription_base_amount'] = $subscription_base_amount = $institute_data['subscription_base_amount'];
			$data['subscription_gst_amount'] = $subscription_gst_amount = $institute_data['subscription_gst_amount'];
			$data['subscription_year'] = $subscription_year = $institute_data['subscription_year'];
			$data['invoice_pdf'] = $invoice_pdf = $institute_data['invoice_pdf'];
			
			if($enc_receipt_no == 0)
			{
				if($invoice_pdf == '') { redirect(site_url('institute_subscription/payment_history')); }
			}
			else
			{
				$data['payment_data'] = $payment_data = $this->after_login_check_payment_data($enc_receipt_no);				
				if(count($payment_data) == 0) { redirect(site_url('institute_subscription/payment_history')); }
				
				$invoice_pdf = $payment_data['invoice_pdf'];
			}	

      $this->show_invoice_pdf($invoice_pdf, $invoice_no);
		}

    //===================== ADMIN CODE START HERE ======================================
    public function admin_login()//START : ADMIN LOGIN
		{
      //USE STATIC USERNAME AND PASSWORD AS BELOW
      //USERNAME : institute_admin
      //PASSWORD : $Admin@institute2023#

			//START : check session already started for ADMIN
			$admin_login_type = $this->session->userdata('INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_TYPE');
      $admin_login_username = $this->session->userdata('INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_USERNAME');				
			
			if(isset($admin_login_type) && $admin_login_type == 'INSTITUTE_SUBSCRIPTION_ADMIN' && isset($admin_login_username) && $admin_login_username == 'institute_admin')
			{
				redirect(site_url('institute_subscription/admin_dashboard'));
			}
			else
			{
				$this->unset_admin_session_data();
			}
			//END : check session already started for ADMIN
			
			$data=array();
			$data['error']='';
						
			if(isset($_POST) && count($_POST) > 0)
			{  
				$this->form_validation->set_rules('institute_admin_username', 'Username', 'trim|required|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('institute_admin_password', 'password', 'trim|required|callback_validate_login_admin[1]|xss_clean',array('required' => 'Please enter the %s'));
				$this->form_validation->set_rules('code', 'Code', 'trim|required|callback_validate_captcha_code_admin[1]|xss_clean',array('required' => 'Please enter the %s'));
				
				if($this->form_validation->run() == TRUE)
				{
					$institute_admin_username = $this->security->xss_clean(trim($this->input->post('institute_admin_username')));
					$institute_admin_password = $this->security->xss_clean(trim($this->input->post('institute_admin_password')));

          if($institute_admin_username == 'institute_admin' && $institute_admin_password == '$Admin@institute2023#') 
          {					
					  $admin_session_data['INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_TYPE'] = 'INSTITUTE_SUBSCRIPTION_ADMIN';
            $admin_session_data['INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_USERNAME'] = $institute_admin_username;
					  $this->session->set_userdata($admin_session_data);
					
					  redirect(site_url('institute_subscription/admin_dashboard'));
          }
          else
          {
            $this->session->set_flashdata('error','Please enter correct username & Password');
            redirect(site_url('institute_subscription/admin_login'));
          }
				}
			}
			
			$captcha_img = $this->Captcha_model->generate_captcha_img('INSTITUTE_SUBSCRIPTION_ADMIN_CAPTCHA');
			$data['image'] = $captcha_img;
			
			$this->load->view('institute_subscription/admin_login',$data);
		}//END : LOGIN

    function unset_admin_session_data() //START : UNSET SESSION DATA
		{
			$admin_session_data = array('INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_TYPE'=>'', 'INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_USERNAME'=>'');
			$this->session->set_userdata($admin_session_data);
		}//END : UNSET SESSION DATA

    public function admin_logout()//START : LOGOUT
		{
			$this->unset_admin_session_data();
			redirect(site_url('institute_subscription/admin_login'));
		}//END : LOGOUT

    public function generate_captcha_ajax_admin()//START : GENERATE CAPTCHA CODE AJAX
		{
			echo $this->Captcha_model->generate_captcha_img('INSTITUTE_SUBSCRIPTION_ADMIN_CAPTCHA');
		}//END : GENERATE CAPTCHA CODE AJAX	
		
		public function validate_captcha_code_admin($code_str="", $type="0")//START : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE // 0 => Ajax, 1=>Server
		{
			$session_name = 'INSTITUTE_SUBSCRIPTION_ADMIN_CAPTCHA';
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
						$this->form_validation->set_message('validate_captcha_code_admin', 'Please enter correct code'); return FALSE;  
					} 
				}				
			} 
			else 
			{ 
				if($type == '0') { echo "false"; } 
				else if($type == '1') 
				{ 
					$this->form_validation->set_message('validate_captcha_code_admin', 'Please enter correct code'); return FALSE;  
				} 
			}
		}//END : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE

    public function validate_login_admin($str="", $type="0")//START : LOGIN VALIDATION FOR SERVER SIDE AND CLIENT SIDE // type = 0 => Ajax, 1=>Server
		{
			$error_msg = 'Invalid request';
			$flag = 'error';
			$return_val = FALSE;
			
			$institute_no = $invoice_no = "";
			if(isset($_POST) && count($_POST) > 0)
			{
				$institute_admin_username = $this->security->xss_clean(trim($this->input->post('institute_admin_username')));
        $institute_admin_password = $this->security->xss_clean(trim($this->input->post('institute_admin_password')));
				
				if($institute_admin_username == "" || $institute_admin_password == "")
				{
					//$flag = 'success';
					//$error_msg = "";
					//$return_val = TRUE;
				}
				else if($institute_admin_username != "" && $institute_admin_password != "")
				{				
					if($institute_admin_username == 'institute_admin' && $institute_admin_password == '$Admin@institute2023#') 
					{
						$flag = 'success';
						$error_msg = "";
						$return_val = TRUE;
					}
					else
					{
						$error_msg = 'Please enter correct username & Password';
					}
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
				$this->form_validation->set_message('validate_login_admin', $error_msg); 
				return $return_val;  
			}
		}//END : LOGIN VALIDATION FOR SERVER SIDE AND CLIENT SIDE

    public function admin_dashboard()//START : DASHBOARD
		{
			$this->validate_admin_all_pages_after_login(); 
      
      $this->db->order_by('is.subscription_id','DESC');
			$this->db->join('payment_transaction pt', 'pt.member_regnumber = is.institute_no AND pt.ref_id = is.subscription_id');
			$payment_history_data = $this->master_model->getRecords('institute_subscription is',array('pt.pay_type' => '24'), 'is.subscription_id, is.institute_name, is.institute_no, is.invoice_no, is.subscription_year, is.subscription_base_amount, is.subscription_gst_amount, is.is_it_tds_applicable, is.it_tds_percentage_rate, is.it_tds_percentage_amount, is.is_gst_tds_applicable, is.gst_tds_percentage_rate, is.gst_tds_percentage_amount, pt.gateway, pt.amount, pt.date AS PaymentDate, pt.transaction_no, pt.receipt_no, pt.status AS PaymentStatus');
			
			$data['payment_history_data'] = $payment_history_data;
			
			$data['act_id'] = $data['sub_act_id'] = 'dashboard';
			$this->load->view('institute_subscription/admin_dashboard',$data);
		}//END : DASHBOARD

    public function validate_admin_all_pages_after_login()
    {
      $admin_login_type = $this->session->userdata('INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_TYPE');
      $admin_login_username = $this->session->userdata('INSTITUTE_SUBSCRIPTION_ADMIN_LOGIN_USERNAME');				
			
			if(!isset($admin_login_type) || $admin_login_type != 'INSTITUTE_SUBSCRIPTION_ADMIN' || !isset($admin_login_username) || $admin_login_username != 'institute_admin')
			{
				redirect(site_url('institute_subscription/admin_logout'));
			}
    }

    function admin_invoice($enc_receipt_no=0)
		{
      $this->validate_admin_all_pages_after_login(); 

      if($enc_receipt_no == '0')
			{
				redirect(site_url('institute_subscription/admin_dashboard'));
			}
			else
			{
        $receipt_no = base64_decode($enc_receipt_no);

        $this->db->join('institute_subscription is', 'is.institute_no = pt.member_regnumber AND pt.ref_id = is.subscription_id');
        $institute_data = $this->master_model->getRecords('payment_transaction pt',array('pt.receipt_no' => $receipt_no, 'pt.pay_type' => '24'), 'pt.id, pt.amount, pt.date, pt.transaction_no, pt.receipt_no, pt.status, is.subscription_id, is.institute_no, is.invoice_no, is.institute_name, is.subscription_year, is.invoice_pdf, pt.gateway, pt.amount');

        if(count($institute_data) > 0)
        {
          $institute_no = $institute_data[0]['institute_no'];
          $invoice_pdf = $institute_data[0]['invoice_pdf'];
          $invoice_no = $institute_data[0]['invoice_no'];
          $this->show_invoice_pdf($invoice_pdf, $invoice_no);			
        }
        else
        {
          redirect(site_url('institute_subscription/admin_dashboard'));
        }
			}	
		}

    function show_invoice_pdf($invoice_pdf='', $invoice_no='')
    {
      $file_cont=base64_decode($invoice_pdf);
			header('Content-Type: application/pdf');
			header('Content-Length:'.strlen($file_cont));
			
			header('Content-disposition: inline; filename=institue_'.$invoice_no.'.pdf');
			//file_put_contents('../public_html/uploads/put_file/testppp.pdf',$file);
			//header('Content-Type: application/pdf');
			header('Content-Transfer-Encoding: Binary');
			//	header('Content-disposition: inline; filename=testppp.pdf');
			//echo base64_decode($file);
			echo $file_cont;
    }

    function admin_receipt($enc_receipt_no=0)
		{
      $this->validate_admin_all_pages_after_login(); 

      if($enc_receipt_no == '0')
			{
				redirect(site_url('institute_subscription/admin_dashboard'));
			}
			else
			{
        $receipt_no = base64_decode($enc_receipt_no);

        $this->db->join('institute_subscription is', 'is.institute_no = pt.member_regnumber AND pt.ref_id = is.subscription_id');
        $institute_data = $this->master_model->getRecords('payment_transaction pt',array('pt.receipt_no' => $receipt_no, 'pt.pay_type' => '24'), 'pt.id, pt.amount, pt.date, pt.transaction_no, pt.receipt_no, pt.status AS PaymentStatus, is.subscription_id, is.institute_no, is.invoice_no, is.institute_name, is.subscription_year, is.invoice_pdf, pt.gateway, pt.amount');

        if(count($institute_data) > 0)
        {
          $data['payment_data'] = $institute_data;
          $this->load->view('institute_subscription/receipt',$data);
        }
        else 
        {
          redirect(site_url('institute_subscription/admin_dashboard'));
        }
      }			
		}
	}	