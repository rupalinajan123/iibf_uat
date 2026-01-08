<?php defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Vision extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
		$this->load->helper('vision_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
		exit;
    }
	
    public function index() {
		$var_errors = '';
		$states = $this->master_model->getRecords('state_master');
		
		if (isset($_POST['btnSubmit'])) {
			
			$this->form_validation->set_rules('namesub','Name','trim|required|xss_clean');
			$this->form_validation->set_rules('fname', 'Firstname', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('gender','Select Gender','trim|required|xss_clean');
			$this->form_validation->set_rules('email_id','Email ID','trim|required|valid_email|xss_clean|callback_check_emailduplication');
			$this->form_validation->set_rules('contact_no','Contact Number','trim|required|numeric|max_length[10]|xss_clean|callback_check_mobileduplication');
			$this->form_validation->set_rules('address_1','Address 1','trim|max_length[30]|required|xss_clean|callback_address1');
			$this->form_validation->set_rules('address_2','Address 2','trim|max_length[30]|xss_clean|callback_address2');
			$this->form_validation->set_rules('address_3','Address 3','trim|max_length[30]|xss_clean|callback_address3');
			$this->form_validation->set_rules('address_4','Address 4','trim|max_length[30]|xss_clean|callback_address4');
			$this->form_validation->set_rules('district','District','trim|required|max_length[20]|xss_clean');
			$this->form_validation->set_rules('city','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
			$this->form_validation->set_rules('state','State','trim|required|xss_clean');
			if($this->input->post('state')!='')
			{
				$state=$this->input->post('state');
			}
			else{
				$state = '';
			}
			$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
			
			$start_date = date('d-M-Y',strtotime('first day of +1 month'));
			
			if($this->form_validation->run()==TRUE){
				$vision_data=array(
						'namesub'=>$_POST["namesub"],
						'fname'=>$_POST["fname"],
						'mname'=>$_POST["mname"],
						'lname'=>$_POST["lname"],
						'gender' => $_POST['gender'],
						'email_id'=>$_POST["email_id"],
						'contact_no'=>$_POST["contact_no"],
						'address_1'=>$_POST["address_1"],	
						'address_2'=>$_POST["address_2"],
						'address_3'=>$_POST["address_3"],	
						'address_4'=>$_POST["address_4"],
						'district'	=>$_POST["district"],	
						'city'	=>$_POST["city"],
						'state'=>$_POST["state"],
						'pincode'=>$_POST["pincode"],	
						'subscription_fees'=>$this->config->item('vision_fee_amt'),
						'pay_status'=>0,
						'subscription_from_date'=>date('Y-m-d',strtotime('first day of +1 month')),
						'created_on' => date("Y-m-d H:i:s"),
						'from_to_date' => date('Y-m-d', strtotime('+1 years', strtotime($start_date)))
						);
					
				$this->session->set_userdata('vision_info',$vision_data);
				
				$this->form_validation->set_message('error', "");
				redirect(base_url().'vision/preview');
			}
			
		}
		
		// code to create captcha
		$this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array('img_path' => './uploads/applications/', 'img_url' => base_url().'uploads/applications/',);
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];
		
		// genarate subscription range (y-m-d)
		$start_date = date('d-M-Y',strtotime('first day of +1 month'));
		$end_date = date('d-M-Y', strtotime('+1 years', strtotime($start_date)));
		$subscription_range = $start_date." to ".$end_date;
		
		$data = array('middle_content' => 'vision','states'=>$states,'var_errors' => $var_errors,'image' => $cap['image'],'subscription_range'=>$subscription_range);
        $this->load->view('renewal_common_view', $data);
	}
	
	public function preview(){
		if(!$this->session->userdata('vision_info'))
		{
			redirect(base_url());
		}
		
		$data=array('middle_content'=>'preview_vision');
		$this->load->view('common_view_fullwidth',$data);
		
	}
	
	public function addrecord(){
		if(!$this->session->userdata['vision_info'])
		{
			redirect(base_url());
		}
		$insert_info = array(
							'namesub' => $this->session->userdata['vision_info']['namesub'],
							'fname' => $this->session->userdata['vision_info']['fname'],
							'mname' => $this->session->userdata['vision_info']['mname'],
							'lname' => $this->session->userdata['vision_info']['lname'],
							'gender' => $this->session->userdata['vision_info']['gender'],
							'email_id'=>$this->session->userdata['vision_info']['email_id'],
							'contact_no'=>$this->session->userdata['vision_info']['contact_no'],
							'address_1'=>$this->session->userdata['vision_info']['address_1'],
							'address_2'=>$this->session->userdata['vision_info']['address_2'],
							'address_3'=>$this->session->userdata['vision_info']['address_3'],
							'address_4'=>$this->session->userdata['vision_info']['address_4'],
							'district'=>$this->session->userdata['vision_info']['district'],
							'city'=>$this->session->userdata['vision_info']['city'],
							'state'=>$this->session->userdata['vision_info']['state'],
							'pincode'=>$this->session->userdata['vision_info']['pincode'],
							'subscription_fees'=>$this->config->item('vision_fee_amt'),
							'subscription_from_date'=>$this->session->userdata['vision_info']['subscription_from_date'],
							'created_on' => date("Y-m-d H:i:s")
						);
						
						
						
		if($last_id = $this->master_model->insertRecord('iibf_vision',$insert_info,true))
		{
			$upd_files = array();
			$pt_array = array('vision_id'=>$last_id,);
			$this->session->set_userdata('vision_memberdata', $pt_array);
			
			if(isset($this->session->userdata['vision_memberdata']['vision_id']) && $this->session->userdata['vision_memberdata']['vision_id'] !='' && $this->session->userdata['vision_memberdata']['vision_id'] > 0){
				redirect(base_url()."vision/make_payment");
			}else{
				$this->session->set_flashdata('error','Error while during subscription.please try again!');
				redirect(base_url().'vision'); 
			}
		}
		else
		{
			$this->session->set_flashdata('error','Error while during subscription.please try again!');
			redirect(base_url().'vision');
		}
	}
	
	public function make_payment(){
		$regno = $this->session->userdata['vision_memberdata']['vision_id'];
		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$pg_success_url = base_url()."Vision/sbitranssuccess";
			$pg_fail_url    = base_url()."Vision/sbitransfail";
			
			$insert_data = array(
					'gateway'     => "sbiepay",
					'amount'      => $this->config->item('vision_fee_amt'),
					'date'        => date('Y-m-d H:i:s'),
					'ref_id'	  =>  $regno,	
					'description' => "IIBF Vision Subscription",
					'pay_type'    => 7,
					'status'      => 2,
					'pg_flag'	  =>'iibfbq',
				);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction',$insert_data,true);
			$MerchantOrderNo = reg_sbi_order_id($pt_id);
			$custom_field = $regno."^iibfregn^iibfbq^".$MerchantOrderNo;
			$amount = $this->config->item('vision_fee_amt');
				
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			$getstate = $this->master_model->getRecords('state_master', array('state_code' => $this->session->userdata['vision_info']['state'], 'state_delete' => '0'));
			
			$invoice_insert_array = array('pay_txn_id'=>$pt_id,
										  'receipt_no'=>$MerchantOrderNo,
										  'app_type'=>'V',
										  'service_code'=>$this->config->item('vision_service_code'),
										  'qty'=>'1',
										  'state_code'=>$getstate[0]['state_no'],
										  'state_name'=>$getstate[0]['state_name'],
										  'tax_type'=>'Inter',
										  'fee_amt'=>$this->config->item('vision_fee_amt'),
										  'igst_total'=>$amount,
										  'exempt'=>$getstate[0]['exempt'],
										  'created_on'=>date("Y-m-d H:i:s")
										 );
											
		    $inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
			 
			$MerchantCustomerID = $regno;
            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
            $data["merchIdVal"] = $merchIdVal;
			
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
	
	public function sbitranssuccess(){
		if (isset($_REQUEST['encData'])){
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			
			
			
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			$attachpath=$invoiceNumber='';
			if (isset($_REQUEST['merchIdVal'])){
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code'])){
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData'])){
				$encData = $_REQUEST['pushRespData'];
			}
			//Sbi B2B callback check sbi payment status with MerchantOrderNo 
			$q_details = sbiqueryapi($MerchantOrderNo);
			
			
			
			if($q_details){ 
				if($q_details[2] == "SUCCESS"){ 
					$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
					if($get_user_regnum_info[0]['status']==2){ 
						$reg_id=$get_user_regnum_info[0]['ref_id'];
						$this->db->trans_start();
						$update_data = array(
											'transaction_no' => $transaction_no,
											'status' => 1,
											'transaction_details' => $responsedata[2]." - ".$responsedata[7],
											'auth_code' => '0300',
											'bankcode' => $responsedata[8],
											'paymode' => $responsedata[5],
											'callback'=>'B2B'
											);
										
											
						
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						$this->db->trans_complete();
						
						// genarate subscription number
						$session_vision_id = $reg_id;
						$subscription_number = genarate_subscription_number($session_vision_id,'V');
						
						$start_date = date('Y-m-d',strtotime('first day of +1 month'));
						$end_date = date('Y-m-d', strtotime('+1 years', strtotime($start_date)));
						
						$update_bank_data = array(
												'pay_status' => 1,
												'modified_on' => date("Y-m-d H:i:s"),
												'subscription_from_date' => $start_date,
												'subscription_to_date' => $end_date ,
												'subscription_no' => $subscription_number
												);
												
						$this->master_model->updateRecord('iibf_vision',$update_bank_data,array('vision_id'=>$reg_id));
						
						$log_title ="vision subscription number generate>>".$this->db->last_query();
						$log_message = serialize($update_bank_data);
						$rId = '1';
						$regNo = '1';
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						
						$subscription_range = date('d-M-Y',strtotime($start_date)).' TO '.date('d-M-Y',strtotime($end_date));
						
						// email to user
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'vision'));
						
						$newstring1 = str_replace("#NO#", "". $subscription_number."",  $emailerstr[0]['emailer_text']);
						$final_str= str_replace("#DATE#", "". $subscription_range ."", $newstring1);
						$user_email = $this->session->userdata['vision_info']['email_id'];
						
						//Manage Log
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
						
						if(count($emailerstr) > 0){
						//to user	
							$info_arr=array('to'=>$user_email,
											//'to'=>'ztest2500@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
											);
							//to client
									$client_arr=array(
											//'to'=>'vahitha@iibf.org.in,cd@iibf.org.in',
											'to'=>'cd@iibf.org.in,kavan@iibf.org.in',
											//'to'=>'ztest2500@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
											);					
							// genarate invoice
							$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
							if(count($getinvoice_number) > 0){
								$invoiceNumber = generate_vision_invoice_number($getinvoice_number[0]['invoice_id']);
								if($invoiceNumber){
									$invoiceNumber=$this->config->item('vision_no_prefix').$invoiceNumber;
								}
								
								$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
								$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$attachpath=genarate_vision_invoice($getinvoice_number[0]['invoice_id'],$session_vision_id);
							}
							
							
							
							if($attachpath!=''){
										//send to client
								$this->Emailsending->mailsend_attch($client_arr,$attachpath);
								//send to user
								if($this->Emailsending->mailsend_attch($info_arr,$attachpath)){
									redirect(base_url().'Vision/acknowledge/');
								}else{
									redirect(base_url().'Vision/acknowledge/');
								}
							}else{
								redirect(base_url().'Vision/acknowledge/');
							}
						}
					}
				}
			}
		}else{
			die("Please try again...");
		}
	}
	
	public function sbitransfail(){
		if(isset($_REQUEST['encData'])){
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			
			$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
			
			if($get_user_regnum_info[0]['status']!=0 && $get_user_regnum_info[0]['status']==2){
				if (isset($_REQUEST['merchIdVal'])){
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code'])){
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData'])){
					$encData = $_REQUEST['pushRespData'];
				}
				
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
				$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
				
				$start_date = date('Y-m-d',strtotime('first day of +1 month'));
				$end_date = date('Y-m-d', strtotime('+1 years', strtotime($start_date)));
				
				$update_bank_data = array(
											'pay_status' => 0,
											'subscription_no' => ''
										);
												
				$this->master_model->updateRecord('iibf_vision',$update_bank_data,array('vision_id'=>$this->session->userdata['vision_memberdata']['vision_id']));
				
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);	
				
			}
			//Sbi fail code without callback
			echo "Transaction failed";
			echo "<script>
				(function (global) {
				
					if(typeof (global) === 'undefined')
					{
						throw new Error('window is undefined');
					}
				
					var _hash = '!';
					var noBackPlease = function () {
						global.location.href += '#';
				
						// making sure we have the fruit available for juice....
						// 50 milliseconds for just once do not cost much (^__^)
						global.setTimeout(function () {
							global.location.href += '!';
						}, 50);
					};
					
					// Earlier we had setInerval here....
					global.onhashchange = function () {
						if (global.location.hash !== _hash) {
							global.location.hash = _hash;
						}
					};
				
					global.onload = function () {
						
						noBackPlease();
				
						// disables backspace on page except on input fields and textarea..
						document.body.onkeydown = function (e) {
							var elm = e.target.nodeName.toLowerCase();
							if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
								e.preventDefault();
							}
							// stopping event bubbling up the DOM tree..
							e.stopPropagation();
						};
						
					};
				
				})(window);
				</script>";

			exit;
			
		}else{
			die("Please try again...");
		}
	}
	
	public function acknowledge(){
		$data=array();
		
		if($this->session->userdata('vision_memberdata')==''){
			redirect(base_url());
		}
		if($this->session->userdata('vision_info')){
			$this->session->unset_userdata('vision_info');
		}
		
		$user_info=$this->master_model->getRecords('iibf_vision',array('vision_id'=>$this->session->userdata['vision_memberdata']['vision_id']),'subscription_no,subscription_from_date,subscription_to_date');
		
		$from_date = date('d-M-Y',strtotime($user_info[0]['subscription_from_date']));
		$to_date   = date('d-M-Y',strtotime($user_info[0]['subscription_to_date']));
		
		$valid_period = $from_date.' TO '.$to_date;
		
		$data=array('middle_content'=>'vision_thankyou','subscription_number'=>$user_info[0]['subscription_no'],'valid_period'=>$valid_period);
		
		
		$this->load->view('common_view_fullwidth',$data);
	}
	
	public function ajax_check_captcha() {
        $code = $_POST['code'];
        // check if captcha is set -
        if ($code == '' || $_SESSION["regcaptcha"] != $code) {
            $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
            echo 'false';
        } else if ($_SESSION["regcaptcha"] == $code) {
            echo 'true';
        }
    }
	
	public function mobileduplication() {
        $mobile = $_POST['mobile'];
        if ($mobile != "") {
			
			$result = $this->master_model->getRecords('iibf_vision',array('contact_no'=>$mobile,'pay_status'=>1),'vision_id,subscription_to_date',array('vision_id'=>'desc')); 
			
            $prev_count = $this->master_model->getRecordCount('iibf_vision', array('contact_no' => $mobile,'pay_status'=>1));
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok','str'=>'');
                echo json_encode($data_arr);
            } else {
				
				
				$this->db->where('subscription_to_date <',date('Y-m-d')); 
				$prev_count1=$this->master_model->getRecordCount('iibf_vision',array('vision_id'=>$result[0]['vision_id'],'contact_no'=>$mobile,'pay_status'=>'1'));
				
				if($prev_count1==1){
					$data_arr = array('ans' => 'ok','str'=>'');
                	echo json_encode($data_arr);
				}else{
					$data_arr = array('ans' => 'exists','str'=>'Your subscription is valid till '.date("d-m-Y", strtotime($result[0]['subscription_to_date'])).', kindly renew your subscription once it is expired');
                	echo json_encode($data_arr);
				}
            }
        } else {
            echo 'error';
        }
    }
	
	public function emailduplication() {
        $email = $_POST['email'];
        if ($email != "") {
			
			$result = $this->master_model->getRecords('iibf_vision',array('email_id'=>$email,'pay_status'=>1),'vision_id,subscription_to_date',array('vision_id'=>'desc')); 
			
            $prev_count = $this->master_model->getRecordCount('iibf_vision', array('email_id' => $email,'pay_status'=>1));
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok','str'=>'');
                echo json_encode($data_arr);
            } else {
				
				$this->db->where('subscription_to_date <',date('Y-m-d')); 
				$prev_count2=$this->master_model->getRecordCount('iibf_vision',array('vision_id'=>$result[0]['vision_id'],'email_id'=>$email,'pay_status'=>'1'));
				
				if($prev_count2==1){
					$data_arr = array('ans' => 'ok','str'=>'');
                	echo json_encode($data_arr);
				}else{
					
					$data_arr = array('ans' => 'exists','str'=>'Your subscription is valid till '.date("d-m-Y", strtotime($result[0]['subscription_to_date'])).', kindly renew your subscription once it is expired');
                	echo json_encode($data_arr);
				}
            }
        } else {
            echo 'error';
        }
    }
	
	public function checkpin(){
		$statecode=$_POST['statecode'];
		$pincode=$_POST['pincode'];
		if($statecode!="")
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			if($prev_count==0){
				echo 'false';
			}
			else{
				echo 'true';
			}
		}
		else{
			echo 'false';
		}
	} 
	
	public function generatecaptchaajax() {
        $this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array('img_path' => './uploads/applications/', 'img_url' => base_url() . 'uploads/applications/',);
        $cap = create_captcha($vals);
        $data = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data;
    }
	
	public function check_emailduplication($email)
	{
		if($email!="")
		{
			
			$result = $this->master_model->getRecords('iibf_vision',array('email_id'=>$email,'pay_status'=>1),'vision_id,subscription_to_date',array('vision_id'=>'desc'));
			
			$prev_count=$this->master_model->getRecordCount('iibf_vision',array('email_id'=>$email,'pay_status'=>1));
			if($prev_count==0)
			{	
				return true;	
			}
			else
			{
				
				$this->db->where('subscription_to_date <',date('Y-m-d')); 
				$prev_count3=$this->master_model->getRecordCount('iibf_vision',array('vision_id'=>$result[0]['vision_id'],'email_id'=>$email,'pay_status'=>'1'));
				
				if($prev_count3==1){
					return true;
				}else{
					$str='Your subscription is valid till '.date("d-m-Y", strtotime($result[0]['subscription_to_date'])).', kindly renew your subscription once it is expired ';
					$this->form_validation->set_message('check_emailduplication', $str); 
					return false;
				}
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
			$result = $this->master_model->getRecords('iibf_vision',array('contact_no'=>$mobile,'pay_status'=>1),'vision_id,subscription_to_date',array('vision_id'=>'desc'));
			
			
			$prev_count=$this->master_model->getRecordCount('iibf_vision',array('contact_no'=>$mobile,'pay_status'=>1));
			if($prev_count==0)
			{
				return true;
				}
			else
			{
				
				$this->db->where('subscription_to_date <',date('Y-m-d')); 
				$prev_count4=$this->master_model->getRecordCount('iibf_vision',array('vision_id'=>$result[0]['vision_id'],'contact_no'=>$mobile,'pay_status'=>'1'));
				
				
				if($prev_count4==1){
					return true;
				}else{
					$str='Your subscription is valid till '.date("d-m-Y", strtotime($result[0]['subscription_to_date'])).', kindly renew your subscription once it is expired ';
					$this->form_validation->set_message('check_mobileduplication', $str); 
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}
    
	public function address1($addressline1) 
	{
		if ( !preg_match('/^[a-z0-9 .,-]+$/i',$addressline1) )
		{
			$this->form_validation->set_message('address_1', "Please enter valid address");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function address2($addressline2) 
	{
		if ( !preg_match('/^[a-z0-9 .,-]+$/i',$addressline2) )
		{
			$this->form_validation->set_message('address_2', "Please enter valid address");
			return false;
		}
		else
		{
			return true;
		}
	}
	public function address3($addressline3) 
	{
		if ( !preg_match('/^[a-z0-9 .,-]+$/i',$addressline3) )
		{
			$this->form_validation->set_message('address_3', "Please enter valid address");
			return false;
		}
		else
		{
			return true;
		}
	}
	public function address4($addressline4) 
	{
		if ( !preg_match('/^[a-z0-9 .,-]+$/i',$addressline4) )
		{
			$this->form_validation->set_message('address_4', "Please enter valid address");
			return false;
		}
		else
		{
			return true;
		}
	}
	
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
}
