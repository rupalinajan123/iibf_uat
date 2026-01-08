<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_admit extends CI_Controller {

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
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		 parent::__construct(); 
		 //load mPDF library
		 //$this->load->library('m_pdf');
		 $this->load->model('Master_model');
		 $this->load->library('email');
		 $this->load->model('Emailsending');
	     //$this->load->model('Emailsending_123');
		 //$this->load->helper('admitcard_helper');
	     $this->load->helper('custom_invoice_helper');
		 $this->load->helper('custom_admitcard_helper');
		 
		
		$this->load->model('log_model');
		$this->load->model('Emailsending');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	} 
	
	// to check genaration of invoice
	public function chkinvoice(){
		genarate_reg_invoice(121);	
	}
	//to create bulk or DRA login password
	public function create_pass()
	{
		//Generate password
			$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			$password = substr( str_shuffle( $chars ), 0, 8 ); 
			echo '</br>';
			echo $password;
			$str = $password;
			//$str = 'GIY2CTJX';
			$pass = md5($str);
			echo '</br>';
			echo $pass;
			//exit;*/
	}
	public function caiib_email_sending()
	{
		//echo 'in';
		$this->db->where('email_status',0);
		$this->db->limit(25,0);
		//$this->db->where_in('member_number','100032348,510089242,510300347,510303327');
		$member_data = $this->master_model->getRecords('caiib_email_refund');
		//print_r($member_data);exit;
		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'caiib_emailer_refund'));
		$sms_template_id = 'NA';
		foreach($member_data as $member)
		{
		    $this->db->where('regnumber',$member['member_number']);
			$user = $this->master_model->getRecords('member_registration','','email');
			
			$final_str = $emailerstr[0]['emailer_text'];
			//echo $user[0]['email'];exit;
			//$info_arr = array('to'=>'21bhavsartejasvi@gmail.com','from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
			$info_arr = array('to'=>$user[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);

			
			//$attachpath = custome_genarate_duplicatecert_invoice('14185');
			if($this->Emailsending->mailsend($info_arr)) //,$attachpath
			{
				$update_data = array('email_status' =>1);
				$this->master_model->updateRecord('caiib_email_refund',$update_data,array('member_number'=>$member['member_number']));
				 echo $member['member_number'].">>mail send successfully";
				 echo '</br>';
			}
			else
			{
				echo "Error while mail sending";
			}
		
			
		}
	}
	// to check genaration of admitcard 
	public function admitcard_ch(){
		
		$this->db->where('is_mail_send',0);
		$this->db->limit(25,0);
	    $member_data = $this->master_model->getRecords('admit_card_details_instruc_ch');
		
		foreach($member_data as $member)
		{
		    $path = genarate_admitcard_instruction_ch($member['mem_mem_no'],$member['exm_cd']);
			if(!empty($path))
			{
				$final_str = 'Please find attached admit letter with revised instructions. Please refer attached admit letter for exam and ignore previous received admit letter.';
				
				$this->db->where('regnumber',$member['mem_mem_no']);
				$user = $this->master_model->getRecords('member_registration','','email');
				
				
				
				$info_arr = array('to'=>$user[0]['email'],'from'=>'noreply@iibf.org.in','subject'=>'Exam enrollment admit card','message'=>$final_str);

				 $attachpath = 'https://iibf.esdsconnect.com/'.$path;
				 //$attachpath = 'http://iibf.teamgrowth.net/'.$path;
					if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
					{
						$update_data = array('is_mail_send' =>1);
						$this->master_model->updateRecord('admit_card_details_instruc_ch',$update_data,array('mem_mem_no'=>$member['mem_mem_no']));
						 echo $member['mem_mem_no'].">>mail send successfully";
						 echo '</br>';
					}
					else
					{
						echo "Error while mail sending";
					}
			}
		}
	}  
	// to check genaration of admitcard 
	public function admitcard(){
	
	    //echo 'in</br>';
	   // $member_id = '510165873';
		$member_id = $this->uri->segment(3);
		$exam_code = $this->uri->segment(4);
		//$member_id = $this->uri->segment(3);
		if(!empty($member_id))
		{
			echo $member_id,'</br>';
			//get invoice image path
			$query = $this->db->query("SELECT member_no,receipt_no,transaction_no,invoice_no,invoice_image FROM `exam_invoice` WHERE `member_no` LIKE ".$member_id." AND exam_code = ".$exam_code." AND exam_period = 803 AND `transaction_no` != '' AND invoice_no != '' AND invoice_image != '' AND exam_invoice.exam_code !='' ");
			$invoice_data = $query->result_array();
			
	//	echo $this->db->last_query();exit; 
			
			if(!empty($invoice_data))
			{ 
				//echo '<pre>',print_r($invoice_data),'</pre>';
				$invoice_path = $invoice_data[0]['invoice_image'];
				if(!empty($invoice_path))
				{
					$invoice_path = 'https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$invoice_path;
				}
			}
			echo 'receipt_no :',$invoice_data[0]['receipt_no'] ,'</br>'; //'receipt_no = ',
			echo  $invoice_path ,'</br>'; //'Invoive Path = ',
			
			$path = genarate_admitcard_custom($member_id,$exam_code); 
			if(!empty($path))
			{
				$pdf_path = 'https://iibf.esdsconnect.com/'.$path;  
			}
			echo $pdf_path; //'Admit_card pdf = ',
		}
	}  
	
	// get old member photo and signature
	public function getphotosig(){
		
		echo $p = get_img_name(500095033,'p');  
		echo "<br/>"; 
		echo $s = get_img_name(500095033,'s'); 
	}
	public function get_count_jaiib_pdfs()
	{
	$exam_code = $this->uri->segment(3);
	$mask = $exam_code.'_217_*.*';
	echo 'File Name :',$mask,'</br>';
	$files = glob('uploads/admitcardpdf/'.$mask);
	echo 'Number of file caiib count :',count($files);
	
	}
	public function replace_capacity()
	{
		//$this->db->limit(1);			    
		$venue_details = $this->master_model->getRecords('venue_802_replace_05_06_2018',array('is_done'=>0));
	    $cnt = 1;  
		foreach($venue_details as $venue_details_rec){
			$old_venue_detail = $this->master_model->getRecords('venue_master',array('center_code'=>$venue_details_rec['Center_code'],'venue_code'=>$venue_details_rec['Venue_code'],'exam_date'=>$venue_details_rec['Exam_date'],'session_time'=>$venue_details_rec['Session_time']));
			//,'vendor_code'=>$venue_details_rec['vendor_code']
			//echo '<pre>',print_r($old_venue_detail),'</pre>';exit;
			foreach($old_venue_detail as $old_venue_detail_rec){
			
				$old_capacity = $old_venue_detail_rec['session_capacity'];
				$new_capacity = $venue_details_rec['Session_capacity'];
				$update_data = array('session_capacity'=>$new_capacity);
				
				$this->master_model->updateRecord('venue_master',$update_data,array('venue_master_id'=>$old_venue_detail_rec['venue_master_id']));		
				
				$update_data_new = array('is_done'=>1,'old_capacity'=>$old_capacity);
				
				$done = $this->master_model->updateRecord('venue_802_replace_05_06_2018',$update_data_new,array('id'=>$venue_details_rec['id']));
				echo '<br>';
				echo 'updated',print_r($done);
				echo $cnt;  
				
			}
			
			$cnt++;
		}
		
	}
    public function send_custom_invoice_mail() 
	{
		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
		$sms_template_id = 'DPDoOIwMR';
		if(count($emailerstr) > 0)
		{
			$final_str = $emailerstr[0]['emailer_text'];
			$info_arr = array('to'=>'21bhavsartejasvi@gmail.com','from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);

		}
	    $attachpath = 'https://iibf.esdsconnect.com/uploads/reginvoice/user/510379312_M_18-19_000001.jpg';
		if($attachpath!='')
		{
			if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
			{
                 echo "mail send successfully";
		    }
			else
			{
				echo "Error while mail sending";
			}
		}	
	    
	}
	
	
	public function DRA_wrong_fee_counts()
	{
	
	//dra_member_exam.pay_status = 1 AND dra_member_exam.exam_period = 183 AND dra_member_exam.exam_fee = '1770.00'
		$dra_mem_exam = $this->master_model->getRecords('dra_member_exam',array('pay_status'=>1,'exam_period'=>183));
		if(!empty($dra_mem_exam))
		{
			foreach($dra_mem_exam as $row)
			{
				$this->db->limit(0,2);
				$dra_mem = $this->master_model->getRecords('dra_members',array('regid'=>$row['regid']),array('regid','regnumber'));
				
				foreach($dra_mem as $row2)
				{
					$this->db->limit(0,2);
					$dra_eligibel = $this->master_model->getRecords('dra_eligible_master',array('member_no'=>$row2['regnumber'],'app_category'=>'S1'),'member_no');
				}
			}
			
		}
		echo '<pre>',print_r($dra_eligibel),'</pre>';
	}
	
	public function admit_card_generation_118()
	{
		//$this->db->limit(1);
		//$data = $this->master_model->getRecords('jaiib_admit_card_generation_10_04_2018',array('is_done'=>0));
		$query = $this->db->query("SELECT * FROM `admit_card_details` WHERE `exm_cd` = ".$this->config->item('examCodeDBF')." AND `exm_prd` = 118 AND `remark` = 1 AND `admitcard_image` = '' GROUP BY mem_mem_no");
		$data = $query->result_array();
		foreach($data as $row)
		{ 	
	        //echo '<pre>',print_r($row),'</br>';
			$member_no = $row['mem_mem_no'];
			//$member_no = $row['regnumber'];
			//$member_no = '700018133';
			echo $member_no,'</br>';//exit;
			//$member_no = '510013945';
			$get_succ_memexm_id = $this->master_model->getRecords('member_exam',array('regnumber'=>$member_no,'exam_code'=>$this->config->item('examCodeDBF'),'exam_period'=>118,'pay_status'=>1));
			
			foreach($get_succ_memexm_id as $memexm_id)
			{
				
				if($memexm_id['pay_status'] == 1)
				{
					
					$r_id = $row['id'];                
					$mem_exm_id = $memexm_id['id'];
					echo @$r_id,') mem_exm_id : ',$mem_exm_id;
					//echo '</br>';exit;
					$data_rs = genarate_admitcard_custom_118($member_no,$r_id,$mem_exm_id);
					echo '</br>';
					print_r($data_rs); 
					echo '</br>';
				}
			}
			
			//echo '<pre>',print_r($get_succ_memexm_id),'</pre>';exit;
			
		} 
	
		
	}
	public function custom_chkinvoice(){
		/*$arr = array(900726829,900727188,900725953);
		for($i=0;$i<=2;$i++){
			echo $path = custom_genarate_exam_invoice($arr[$i]);
			echo "<br/>";
		}*/
		echo $path = custom_genarate_exam_invoice(900725666);
	}
	public function invoice_generation_118()
	{         
		exit;
		$arr = array(900727582,900727604,900727787,900730121,900730589,900731872);
		$cnt = 1; 
		for($i=0;$i<=9;$i++){ 
		    echo "cnt:",$cnt,'<br/>';
			echo $path = custom_genarate_exam_invoice_118($arr[$i]);
			echo "<br/>";
			$cnt++;
		}   
		 
		//echo $path = april_custom_genarate_exam_invoice('900725800');
	}
	public function admit_card_generation_caiib()
	{
	 $random_arr = array(510158085);
	 
	 $cnt = 1;
	 foreach($random_arr as $data)
	 {
		$member_no = $data;
		echo '</br>',$cnt,') member_no',$member_no,'</br>';
		$exam_code = 72;
		$exam_period = 118;
		$data_rs = genarate_custom_admitcard($member_no,$exam_code,$exam_period);
		$cnt++;
	}
	 
	}
	public function arr_count()
	{
	$arr1 = array(500015746,500037539,500042098,500059756,500059915,500064217,500068168,500075541,500077133,500112301,500116654,500121853,500125200,500135851,500148128,500148942,500151064,500158882,500169256,500175554,500181237,500185186,500191211,500209578,500212752,500213813,510014481,510018151,510021867,510023205,510026904,510028368,510030103,510031458,510036539,510039067,510044990,510048041,510048257,510053070,510054902,510057864,510071341,510085747,510086226,510088414,510088812,510094372,510095842,510097979,510103911,510104296,510107459,510111636,510112599,510114969,510114996,510116266,510117099,510118929,510122143,510125926,510132108,510134048,510135959,510146985,510149767,510160892,510172368,510182668,510184002,510186445,510187581,510188873,510191292,510192526,510193612,510198870,510200182,510205136,510205233,510205991,510206229,510213143,510215375,510218601,510220351,510220735,510221006,510221538,510221579,510222691,510223712,510226648,510226766,510234464,510239188,510247674,510247991,510251809,510254983,510261114,510265346,510266457,510266796,510268877,510269410,510271404,510271630,510274972,510278081,510278938,510279530,510280414,510282001,510282671,510288379,510290553,510290676,510294724,510296848,510296941,510300917,510301804,510302733,510303255,510303981,510304967,510306524,510307376,510307949,510308183,510309466,510310088,510312596,10313537,510316991,510323604,510334646,510337477,510339219,510340207,510340801,510343680,510345352,510345359,510348617,510350436,510360001,500176925,510214993,510158085);
	echo count($arr1);
	}
	
	// send mail with transaction detail, admit card pfd, and exam invoice
	public function custom_mail_sending()
	{
	
	    //exit;
		//member_exam_id
		$random_arr = array(3082407);
		
		//, 
		$final_str = 'Please check your admit card PDF and exam invoice';
		$cnt = 1;
		foreach($random_arr as $data) 
		{	
			$this->db->select('member_exam.id,member_exam.regnumber,admit_card_details.exm_cd,admit_card_details.exm_prd,admit_card_details.remark,admit_card_details.admitcard_image,payment_transaction.receipt_no,payment_transaction.transaction_no,exam_invoice.invoice_image');//,exam_invoice.invoice_image
			$this->db->from('member_exam');
			$this->db->join('admit_card_details','admit_card_details.mem_exam_id = member_exam.id');
			$this->db->join('payment_transaction','payment_transaction.ref_id = member_exam.id');
			$this->db->join('exam_invoice','exam_invoice.pay_txn_id = payment_transaction.id');
			$this->db->where(array('member_exam.id'=>$data,'admit_card_details.remark'=>1,'member_exam.pay_status'=>1,'payment_transaction.status'=>1,'exam_invoice.invoice_image!=' => '','admit_card_details.admitcard_image!='=>''));
			//$this->db->where(array('member_exam.id'=>$data,'admit_card_details.remark'=>1,'member_exam.pay_status'=>1,'payment_transaction.status'=>1,'admit_card_details.admitcard_image!='=>''));
			$this->db->group_by('member_exam.id');
			$query = $this->db->get();
			$result = $query->result();
			//echo $this->db->last_query(); 
			//echo '<pre>',print_r($result),'</pre>';exit; 
			if($result)
			{
			//echo '<pre>',print_r($result[0]),'</pre>';exit;
			
				$member_no = $result[0]->regnumber;
				$reciept_no = $result[0]->receipt_no;
				$admit_card = 'https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$result[0]->admitcard_image;
				$invoice_image = 'https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$result[0]->invoice_image;
				echo $cnt.') - '.$data.'</br>'.$member_no.'</br>'.$reciept_no.'</br>'.$admit_card.'</br>'.$invoice_image; //.'     '.$invoice_image
				echo '</br>';
				$responce = $this->admit_card_sending($reciept_no,$admit_card,$invoice_image);//,$invoice_image
			}
			$cnt++;
		}
	
	}
	public function admit_card_sending($reciept_no,$admit_card_path,$invoice_image)//,$invoice_image
	{
	
		$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE gateway = 'sbiepay' AND status = 1 AND (pay_type = '2') AND receipt_no IN (".$reciept_no.")");
		 
		 //echo $this->db->last_query(); exit; 
		if ($pt_query->num_rows())
		{
			$cnt=1; 
			foreach ($pt_query->result_array() as $row)
			{
				//print_r($row); exit;
				$receipt_no = $row['receipt_no'];  // order_no
				$reg_no = $row['ref_id'];  // order_no   
				
				$q_details = $this->cron_sbiqueryapi($MerchantOrderNo = $receipt_no);
                //print_r($q_details);
				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						
						if ($row['pay_type'] == 2)
						{
						$this->update_exam_transaction($MerchantOrderNo, $reg_no, $q_details,$admit_card_path,$invoice_image);//,$invoice_image
							echo $cnt.'<br>';
						}
						if ($row['pay_type'] == 1)
						{
						$this->update_exam_transaction($MerchantOrderNo, $reg_no, $q_details,$admit_card_path,$invoice_image);//,$invoice_image
							echo $cnt.'<br>';
						}
					}
					else if ($q_details[2] == "FAIL")
					{
						
						if ($row['pay_type'] == 2)
						{
						$this->update_exam_transaction($MerchantOrderNo, $reg_no, $q_details,$admit_card_path,$invoice_image);//,$invoice_image
						}
					}
					
					// add query responce in log
					$pg_response = "SBI transaction query responce: ".implode("|", $q_details);
					//$this->log_dv_transaction("sbiepay", $pg_response, $q_details[2]);
					//sleep(1);
				}
				$cnt++;
			}
		}
	
	}
	
	// SBI ePay API for query transaction
	private function cron_sbiqueryapi($MerchantOrderNo = NULL)
	{
		if($MerchantOrderNo!=NULL)
		{
		$merchIdVal = $this->config->item('sbi_merchIdVal');
		$AggregatorId = $this->config->item('sbi_AggregatorId');
		$atrn  = "";

		$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
		
		//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
		$service_url = $this->config->item('sbi_status_query_api');
		$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;

		$ch = curl_init();       
		curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		
		if($result)
		{
			$response_array = explode("|", $result);
			
			return $response_array;
		}
		else
		{
			return 0;
		}
		}
		else
		{
			return 0;
		}
		//print_r($response_array);
		//var_dump($result);   
	}

	private function update_exam_transaction($MerchantOrderNo, $test, $q_details,$admit_card_path,$invoice_image)//,$invoice_image
	{
		if ($q_details[2] == "SUCCESS")
		{
		
			$responsedata = $q_details;
			//print_r($responsedata);
			$cust=explode('^',$responsedata[5]);
			$responsedata[5]=$cust['1'];
			if($responsedata[5]=='iibfexam')
			{
				if($cust['2']!='iibfdra')
				{
					//$MerchantOrderNo = $responsedata[6]; 
					$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
					$responsedata[5]=$get_pg_flag[0]['pg_flag'];
				}
				else
				{
					$responsedata[5]=$cust['2'];
				}
			}
			echo " test ".$responsedata[5]; //exit; 
			if($responsedata[5] == "IIBF_EXAM_O") 
			{
			//	sleep(1);
//				$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
				
			if($payment_status==1)
			{	
				$elective_subject_name='';
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
				
				if($get_user_regnum[0]['status']==1)
				{
					if(count($get_user_regnum) > 0)
					{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
					}
		
					$update_data = array('pay_status' => '1');
					//$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
				
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
					
					/*echo $this->db->last_query();
					echo '</br><pre>',print_r($exam_info),'</pre>';
					exit;*/  
					if(count($exam_info) <= 0)
					{
						$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));

					}
	
					if($exam_info[0]['exam_mode']=='ON')  
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					if($exam_info[0]['examination_date']!='0000-00-00')  
					{
						$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
					}
					else if($exam_info[0]['exam_code']!=990)
					{
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					}
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
						
						$sms_template_id = 'P6tIFIwGR';

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
						$final_str = str_replace("#MODE#", "".$mode."",$newstring21);
				 }
				else
				{
					
					if($exam_info[0]['exam_code']==990)
					{
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
						$sms_template_id = 'S8OmhSQGg';
						$final_str = $emailerstr[0]['emailer_text'];
					}
					else
					{
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
						$sms_template_id = 'C-48OSQMg';
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
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
					}
				} 
				$info_arr=array(//'to'=>$result[0]['email'],
										'to'=>'raajpardeshi@gmail.com',
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str 
									);
				
				//get invoice	
				
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
			if(count($getinvoice_number) > 0)
			{
				if($getinvoice_number[0]['state_of_center']=='JAM')
				{
					//$invoiceNumber = generate_exam_invoice_number_jammu($MerchantOrderNo);
					/*if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
					}*/
				}
				
				else
				{
					if($exam_info[0]['exam_code']!=990)
					{
						//$attachpath=custom_genarate_exam_invoice($MerchantOrderNo);  
					}
					else
					{
						/*$invoiceNumber =generate_DISA_invoice_number($MerchantOrderNo);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;
						}*/
					}
					//$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				
				echo '<pre>in'; 
				print_r($info_arr);
				//$this->db->where('pay_txn_id',$payment_info[0]['id']);
				//$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				//$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);
			
			    }	 
			//$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/510134187_EX_18-19_006429.jpg';   
			//$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/21_118_510134187.pdf';  	
			
			$attachpath=$invoice_image;
			$admitcard_pdf=$admit_card_path;
			 
			echo $attachpath,'</br>';      
			echo $admitcard_pdf,'</br>';
			 
			if($admitcard_pdf!='') 
			{ 		     
				/*if($exam_info[0]['exam_code']==990)
				{
					$sms_final_str = $emailerstr[0]['sms_text'];
				} 
				else
		 		{
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
				}*/
			
				$files=array($attachpath,$admitcard_pdf);//$attachpath,
				//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				//$this->master_model->send_sms(8605933957,$sms_final_str);	
				$this->Emailsending->mailsend_attch($info_arr,$files);
			    //echo $this->email->print_debugger();
				echo '<BR>Successfully Mail Send123!!';
				//$this->Emailsending->mailsend($info_arr);
			}
				
				}
			}
			else
			{
				echo 'in else';exit;
			}
				
				}
			}
			else if($responsedata[5] == "IIBF_EXAM_NM")
			{
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
				
			if($payment_status==1)
			{
				// Handle transaction success case 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
				if($get_user_regnum[0]['status']==1)
				{
					if(count($get_user_regnum) > 0)
				{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
				}
				$update_data = array('pay_status' => '1');
				//$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
				$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
				//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
				//Query to get user details
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
			
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
				
				if(count($exam_info) <= 0)
				{
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
				}
				
				//echo $this->db->last_query();exit;
			
				if($exam_info[0]['exam_mode']=='ON')
				{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
				{$mode='Offline';}
				else{$mode='';}
				//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				
				if($exam_info[0]['examination_date']!='0000-00-00')
				{
					$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
				}
				else
				{
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				}

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
				
				if($exam_info[0]['exam_code']==990)
				{
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
					$sms_template_id = 'S8OmhSQGg';
					$final_str = $emailerstr[0]['emailer_text'];
				}
				else
				{
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
				$sms_template_id = 'P6tIFIwGR';
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
				$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
				$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
				$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
				$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
				$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
				$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
				$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
				}
			
				$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str
									);
									
				//get invoice	
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
			if(count($getinvoice_number) > 0)
			{
				if($getinvoice_number[0]['state_of_center']=='JAM')
				{
					$invoiceNumber = custom_genarate_exam_invoice_jk($getinvoice_number[0]['invoice_id']);
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
					}
				}
				else
				{
					$invoiceNumber =custom_genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
					}
				}
				//$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'));
				//$this->db->where('pay_txn_id',$payment_info[0]['id']);
				//$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				$attachpath=custom_genarate_exam_invoice($MerchantOrderNo);
			}					
			if($attachpath!='')
			{	
				if($exam_info[0]['exam_code']==990)
				{
					$sms_final_str = $emailerstr[0]['sms_text'];
				}
				else
				{
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
				}
				
				// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);	
				$this->Emailsending->mailsend_attch($info_arr,$attachpath);
				//$this->Emailsending->mailsend($info_arr);
			}
			
			echo '<pre>';
			print_r($info_arr);
			  }
			}
			else if($payment_status==0)
			{
				// Handle transaction 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
				
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
				//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
				
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
				
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
		
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
				$sms_template_id = 'Jw6bOIQGg';
				$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
				$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
				$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
				
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				
				$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
				$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);	
				//echo $info_arr;
				if ($this->Emailsending->mailsend($info_arr))
				{
						$info_arr1=array('to'=>'21bhavsartejasvi@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
										
						$this->Emailsending->mailsend($$info_arr1);
						
					echo '<BR>Successfully Mail Send!!';
				}
			}
			
			}
			else if($responsedata[5] == "IIBF_EXAM_DB")
			{
				echo 'in';
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
					
				if($payment_status==1)
				{
					// Handle transaction success case 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code');
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id=$get_user_regnum[0]['ref_id'];
					//$applicationNo = generate_dbf_reg_num(); 
					$applicationNo = $get_user_regnum[0]['member_regnumber'];
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
					//$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					//$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
				
					//Query to get exam details	
				    $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				 
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					
					if(@$exam_info[0]['examination_date']!='0000-00-00' && @$exam_info[0]['examination_date']!='')
					{  
						$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
					}
					else
					{
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
						//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);	
					}
				
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
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$applicationNo),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
				
				
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
						//$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
					}
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$sms_template_id = 'P6tIFIwGR';
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
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
				
						$info_arr=array('to'=>$result[0]['email'], 
										//'to'=>'21bhavsartejasvi@gmail.com',
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str 
									);
				    print_r($info_arr);		
					//$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/700020276_EX_18-19_037960.jpg';
			 		//$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/42_118_700020276.pdf';
					$attachpath=$invoice_image;
			        $admitcard_pdf=$admit_card_path;
			 
					echo $attachpath,'</br>';
					echo $admitcard_pdf;
					
					if($attachpath!='')
					{ 		
						if($exam_info[0]['exam_code']==990)
						{
							$sms_final_str = $emailerstr[0]['sms_text'];
						}  
						else
						{
							$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
							$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						}
					
						$files=array($attachpath,$admitcard_pdf);
						// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);	
						$this->Emailsending->mailsend_attch($info_arr,$files);
						echo '<BR>Successfully Mail Send!!';
						//$this->Emailsending->mailsend($info_arr);
					}
				}
				else if($payment_status==0)
				{
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
					
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['ref_id']),'firstname,middlename,lastname,email,mobile');
					
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
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
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/
				}
			}		
			else if($responsedata[5] == "IIBF_EXAM_DB_EXAM")
			{
			echo 'in';
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
					
				if($payment_status==1)
				{	
					// Handle transaction success case 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
					if($get_user_regnum[0]['status']==1)
					{
						
						if(count($get_user_regnum) > 0)
					{
						$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
					}
					
					$update_data = array('pay_status' => '1');
					//$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
					
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					
					
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					//Query to get Medium	
					$this->db->where('exam_code',$exam_info[0]['exam_code']);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
			
			
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
				
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$sms_template_id = 'P6tIFIwGR';
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
					$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
					$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
					$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
					$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
					$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
					$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
				
					$info_arr=array(//'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'to'=>'21bhavsartejasvi@gmail.com',
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str  
										);
					echo '<pre>'; 
					print_r($info_arr);
                 
					//$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/700020276_EX_18-19_037960.jpg';
			 		//$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/42_118_700020276.pdf';
					
					$attachpath=$invoice_image;
					$admitcard_pdf=$admit_card_path;
			 
                    echo $attachpath;
                    echo $admitcard_pdf;
					
					 
					if($attachpath!='')
					{ 		
						if($exam_info[0]['exam_code']==990)
						{
							$sms_final_str = $emailerstr[0]['sms_text'];
						} 
						else
						{
							$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
							$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						}
					
						$files=array($attachpath,$admitcard_pdf);
						// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);	
						$this->Emailsending->mailsend_attch($info_arr,$files);
						echo '<BR>Successfully Mail Send!!';
						//$this->Emailsending->mailsend($info_arr);
					}
					
					
					/*$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;*/
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/
					
					}
				}
				else if($payment_status==0)
				{
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
					
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
						
				   // Handle transaction 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
					$sms_template_id = 'Jw6bOIQGg';
					$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
					
					$info_arr=array(	'to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
					
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/

				}
			}
		}
	
	}
	//center wise DRA count
	public function centerstat(){
		
		$exam_code = 45;
		$exam_period = '720';
		
		$result=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');
		
		foreach($result as $record){
			$reg = $this->master_model->getRecords('dra_member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));//, "examination_date"=>2018-05-12
			
			$insert_array = array(
								'exam_code' =>$exam_code,
								'center_code'=>$record['center_code'],
								'center_name'=>$record['center_name'],
								'exam_period'=>$exam_period,
								'register_count'=>sizeof($reg)
							); 
							
							
							
			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);
							
		}
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "DRA_centerwise_counts_45_184.csv";
		$query = "SELECT center_code,center_name,register_count FROM center_stat";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
		
	}
	
	public function send_mail(){
	//exit;
		$receipt_array = array(901068283);
		//
		
		$this->db->where_in('receipt_no',$receipt_array);
		$sql = $this->master_model->getRecords('exam_invoice','','invoice_id,invoice_image,member_no');
		print_r($sql);
		$final_str = 'Please check your admit card PDF and exam invoice';
		$cnt = 1;
		foreach($sql as $rec){
			$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];
		//$attachpath = "https://iibf.esdsconnect.com/uploads/examinvoice/user/700021461_EX_18-19_189012.jpg";
			$pdfpath = 'https://iibf.esdsconnect.com/uploads/admitcardpdf/60_218_510020760.pdf';
			//$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 
			//echo ">>".$email[0]['email'];
			//exit;
			$info_arr=array('to'=>$email[0]['email'],
						    //'to'=>'21bhavsartejasvi@gmail.com',  
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Exam Enrolment Acknowledgement',
							'message'=>$final_str
						);   
						
			
			$files=array($attachpath,$pdfpath);
			
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				echo $this->email->print_debugger();
				echo "Mail send successfully to => ".$rec['invoice_id'];
				echo '<pre>info_arr',print_r($info_arr),'</pre>';
				echo '<pre>files',print_r($files),'</pre>';
				echo "<br/>"; 
				echo $cnt; 
			}
			$cnt++;
		}
		
	}
	//new member bulk admit card with  pass and mem_reg no
	public function bulk_custom_admit_send_non_member()
	{
	    //exit;
		
		/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
		if(count($emailerstr) > 0)
		{}*/
		//$final_str = $emailerstr[0]['emailer_text'];
		//3046912,3047966,3047985,3048025
		$mem_no = array(801218171);
	
		//'510384609','510384619','510384661','510384616'
		
		
		foreach($mem_no as $data)
		{
		
			$member_info_new = $this->master_model->getRecords('member_registration',array('regnumber'=>$data));
			
			
			//Query to get exam details	
			$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$data,'member_exam.exam_period'=>805),'member_exam.id,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
			//get admit card pdf 
			$admit_card = $this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$exam_info[0]['id']),'admit_card_details.admitcard_image');
			
			$username=$member_info_new[0]['firstname'].' '.$member_info_new[0]['middlename'].' '.$member_info_new[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			
			$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
			
			//Query to get Medium	
			$this->db->where('exam_code','1770');
			$this->db->where('exam_period',$exam_info[0]['exam_period']);
			$this->db->where('medium_code',$exam_info[0]['exam_medium']);
			$this->db->where('medium_delete','0');
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			
			if($exam_info[0]['exam_mode']=='ON')
			{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
			{$mode='Offline';}
			
		/*	if(in_array($member_info_new[0]['regid'],$new_mem_regid))
			{}*/
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));
				
				//$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'new_non_member_bulk'));
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
				$sms_template_id = 'P6tIFIwGR';
				$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#REG_NUM#", "".$data."",$newstring1);
				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
				$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
				//$newstring4 = str_replace("#AMOUNT#","".$payment_info[0]['amount']."",$newstring4);
				$newstring6 = str_replace("#ADD1#", "".$member_info_new[0]['address1']."",$newstring4);
				$newstring7 = str_replace("#ADD2#", "".$member_info_new[0]['address2']."",$newstring6);
				$newstring8 = str_replace("#ADD3#", "".$member_info_new[0]['address3']."",$newstring7);
				$newstring9 = str_replace("#ADD4#", "".$member_info_new[0]['address4']."",$newstring8);
				$newstring10 = str_replace("#DISTRICT#", "".$member_info_new[0]['district']."",$newstring9);
				$newstring11 = str_replace("#CITY#", "".$member_info_new[0]['city']."",$newstring10);
				$newstring12 = str_replace("#STATE#", "".@$member_info_new[0]['state_name']."",$newstring11);
				$newstring13 = str_replace("#PINCODE#", "".$member_info_new[0]['pincode']."",$newstring12);
				$newstring14 = str_replace("#EMAIL#", "".$member_info_new[0]['email']."",$newstring13);
				$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
				$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
				$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
				$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
				//$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
				$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring18);
				//$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
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
						//$final_str_pdf = $newstring21;
						$final_str_pdf = 'PFA';
						
				//$final_str_pdf = $newstring20;
				
			
				echo '<pre>',print_r($final_str_pdf),'</pre>';
				echo '<pre>',print_r('https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$admit_card[0]['admitcard_image']),'</pre>';
				
				
				$admit_card='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$admit_card[0]['admitcard_image'];
				$files_pdf=array($admit_card);
				$info_arr_pdf=array('to'=>$member_info_new[0]['email'],
										//'to'=>'21bhavsartejasvi@gmail.com',
										'from'=>'noreply@iibf.org.in',
										'subject'=>'Bulk Exam application', 
										'message'=>$final_str_pdf
										);
				echo '<pre>',print_r($member_info_new[0]['email']),'</pre>';
				$this->Emailsending->mailsend_attch($info_arr_pdf,$files_pdf);
						
			
			//exit;
		} 
	  

	}
	public function bulk_send_admit_card_member()
	{
		$mem_no = array(500046511,510032875,500019908,510095915,510355052,510315485,500023682,500051165,7504054,510062806,500087257,500194727,510318777,510014458,500047873,500163135,510353090,510350099,500083966,510366708,510350989,500031837,801206510,500200045,500195110,500204854,500121233,801206511,500043724,500096984,500055932,500027809,510272601,500195174,500122335,500117873,510358975,510267251,510106051,510381273,500038749,500165134,510016825,510363914,500151957,500157390,510383278,500081411,510265103,500046865,500159455,500009644,510174318,500120317,500041369,500066039,500092086,500027556,510052154,500039997,500004718,510051729,500105429,400014311,500083692);
		
		
		foreach($mem_no as $data)
		{
		
			$member_info_new = $this->master_model->getRecords('member_registration',array('regnumber'=>$data));
			
			
			//Query to get exam details	
			$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$data,'member_exam.exam_period'=>802),'member_exam.id,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
			//get admit card pdf 
			$admit_card = $this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$exam_info[0]['id']),'admit_card_details.admitcard_image');
			
			$username=$member_info_new[0]['firstname'].' '.$member_info_new[0]['middlename'].' '.$member_info_new[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			
			$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
			
			//Query to get Medium	
			$this->db->where('exam_code','20');
			$this->db->where('exam_period',$exam_info[0]['exam_period']);
			$this->db->where('medium_code',$exam_info[0]['exam_medium']);
			$this->db->where('medium_delete','0');
			$medium=$this->master_model->getRecords('medium_master','','medium_description');
			
			if($exam_info[0]['exam_mode']=='ON')
			{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
			{$mode='Offline';}
			
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'old_non_member_bulk'));
						$sms_template_id = 'P6tIFIwGR';
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$data."",$newstring1);
						$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
						$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
						//$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
						$newstring6 = str_replace("#ADD1#", "".$member_info_new[0]['address1']."",$newstring4);
						$newstring7 = str_replace("#ADD2#", "".$member_info_new[0]['address2']."",$newstring6);
						$newstring8 = str_replace("#ADD3#", "".$member_info_new[0]['address3']."",$newstring7);
						$newstring9 = str_replace("#ADD4#", "".$member_info_new[0]['address4']."",$newstring8);
						$newstring10 = str_replace("#DISTRICT#", "".$member_info_new[0]['district']."",$newstring9);
						$newstring11 = str_replace("#CITY#", "".$member_info_new[0]['city']."",$newstring10);
						$newstring12 = str_replace("#STATE#", "".@$member_info_new[0]['state_name']."",$newstring11);
						$newstring13 = str_replace("#PINCODE#", "".$member_info_new[0]['pincode']."",$newstring12);
						$newstring14 = str_replace("#EMAIL#", "".$member_info_new[0]['email']."",$newstring13);
						$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
						$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
						$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
						$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
						//$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
						$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring18);
						//$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
						
						#-----------------------------------------E-learning msg ---------------------------------------------------------#	
							$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
						if(count($elern_msg_string) > 0)
						{
							foreach($elern_msg_string as $row)
							{
								$arr_elern_msg_string[]=$row['exam_code'];
							}
							if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
							{
								$newstring21 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'),$newstring20);		
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
						
					$final_str_pdf = $newstring21;
					
					echo '<pre>',print_r($final_str_pdf),'</pre>';
				echo '<pre>',print_r('https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$admit_card[0]['admitcard_image']),'</pre>';
				
				
		$admit_card='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$admit_card[0]['admitcard_image'];
				$files_pdf=array($admit_card);
				$info_arr_pdf=array('to'=>$member_info_new[0]['email'],
										//'to'=>'21bhavsartejasvi@gmail.com',
										'from'=>'noreply@iibf.org.in',
										'subject'=>'Bulk Exam application', 
										'message'=>$final_str_pdf
										);
				echo '<pre>',print_r($member_info_new[0]['email']),'</pre>';
				$this->Emailsending->mailsend_attch($info_arr_pdf,$files_pdf);
						
			
			//exit;
		}
	}
	
	public function get_flag()
	{
		$exam_month_year = $this->master_model->getRecords('dra_misc_master',array('exam_code'=>45,'exam_period'=>186));
		$ref4 = '45'.$exam_month_year[0]['exam_month'];
		print_r($ref4);
		//45201811
	}
	public function dra_centerstat()
	{
		
		$exam_code = 57;
		$exam_period = '719'; 
		
		$result=$this->master_model->getRecords('dra_center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');

		foreach($result as $record){
			$reg = $this->master_model->getRecords('dra_member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));//, "examination_date"=>2018-05-12
			echo $this->db->last_query(); die;
			$insert_array = array(
								'exam_code' =>$exam_code,
								'center_code'=>$record['center_code'],
								'center_name'=>$record['center_name'],
								'exam_period'=>$exam_period,
								'register_count'=>sizeof($reg)
							); 
							
							
							
			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);
							
		}
		
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "DRA_centerwise_counts_".$exam_code."_".$exam_period."_.csv";
		$query = "SELECT * FROM center_stat"; 
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
		
	}

	public function mail_send_dynamic()
	{
		// member exam id
		$random_arr = array(3249473);
		 
		//, 
		$final_str = 'Please check your admit card PDF and exam invoice';
		$cnt = 1;
		foreach($random_arr as $data) 
		{	
			$this->db->select('member_exam.id,member_exam.regnumber,admit_card_details.exm_cd,admit_card_details.exm_prd,admit_card_details.remark,admit_card_details.admitcard_image,payment_transaction.receipt_no,payment_transaction.transaction_no,exam_invoice.invoice_image');//,exam_invoice.invoice_image
			$this->db->from('member_exam');
			$this->db->join('admit_card_details','admit_card_details.mem_exam_id = member_exam.id');
			$this->db->join('payment_transaction','payment_transaction.ref_id = member_exam.id');
			$this->db->join('exam_invoice','exam_invoice.pay_txn_id = payment_transaction.id');
			$this->db->where(array('member_exam.id'=>$data,'admit_card_details.remark'=>1,'member_exam.pay_status'=>1,'payment_transaction.status'=>1,'exam_invoice.invoice_image!=' => '','admit_card_details.admitcard_image!='=>''));
			//$this->db->where(array('member_exam.id'=>$data,'admit_card_details.remark'=>1,'member_exam.pay_status'=>1,'payment_transaction.status'=>1,'admit_card_details.admitcard_image!='=>''));
			$this->db->group_by('member_exam.id');
			$query = $this->db->get();
			$result = $query->result();
			//echo $this->db->last_query(); 
			//echo '<pre>',print_r($result),'</pre>';exit; 
			if($result)
			{
			//echo '<pre>',print_r($result[0]),'</pre>';exit;
				
				$member_no = $result[0]->regnumber;
				$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_no,'isactive'=>'1'),'email'); 
				$reciept_no = $result[0]->receipt_no;
				$admit_card = 'https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$result[0]->admitcard_image;
				$invoice_image = 'https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$result[0]->invoice_image;
				echo $cnt.') - '.$data.'</br>'.$member_no.'</br>'.$reciept_no.'</br>'.$admit_card.'</br>'.$invoice_image; //.'     '.$invoice_image
				echo '</br>';
				
				//$responce = $this->admit_card_sending($reciept_no,$admit_card,$invoice_image);
				//,$invoice_image
				
						$info_arr=array('to'=>$email[0]['email'],
									//'to'=>'raajpardeshi@gmail.com',  
									'from'=>'noreply@iibf.org.in',
									'subject'=>'Exam Enrolment Acknowledgement',
									'message'=>$final_str
								);   
								
					
					$files=array($admit_card,$invoice_image);
					
					if($this->Emailsending->mailsend_attch($info_arr,$files)){
						echo $this->email->print_debugger();
						echo "Mail send successfully to => ".$member_no;
						echo '<pre>info_arr',print_r($info_arr),'</pre>';
						echo '<pre>files',print_r($files),'</pre>';
						echo "<br/>"; 
						echo $cnt; 
					}
			}
			$cnt++;
		}
	}
	public function custom_mail_nm_registration()
	{
		//exit;
		//Query to get exam details	
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>801231501),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
		
		
		//Query to get Payment details	
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>901118216,'member_regnumber'=>801231501),'transaction_no,date,amount,id');
		//--------------------
		$result = $this->master_model->getRecords('member_registration',array('regnumber'=>801231501,'isactive'=>'1'));
		
		if($result)
		{
			echo 'in';
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
						$sms_template_id = 'P6tIFIwGR';
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					
					$mode='Online';
					
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

					
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$result[0]['regnumber']."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
					$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
					$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
					$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
					$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
					$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
					$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
					$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
					$newstring12 = str_replace("#STATE#", "UTTAR PRADESH",$newstring11);
					$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
					$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
					$newstring15 = str_replace("#MEDIUM#", "HINDI",$newstring14);
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
											//'to'=>'21bhavsartejasvi@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				    
					$attachpath = 'https://iibf.esdsconnect.com/uploads/examinvoice/user/801231501_EX_18-19_251467.jpg';
					$files=array($attachpath);
					
					
					if($this->Emailsending->mailsend_attch($info_arr,$files)){
						echo $this->email->print_debugger();
						echo "Mail send successfully to => ".$result[0]['email'];
						echo '<pre>info_arr',print_r($info_arr),'</pre>';
						echo '<pre>files',print_r($files),'</pre>';
						echo "<br/>"; 
						
					}
		}
		
										
	}
	// to check genaration of custom invoice
	/*public function custom_chkinvoice(){
	
	echo 'in';
		$arr = array(900915785); 
		for($i=0;$i<=1;$i++){
			echo $path = custom_genarate_exam_invoice($arr[$i]);
			echo "<br/>";
		}
		//echo $path = custom_genarate_exam_invoice(900912006);
	}*/
	
	public function check_double_members(){

        $this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		
        $this->db->select('dra_member_exam.regid as d_regid,dra_member_exam.pay_status,dra_member_exam.exam_period,dra_members.regid,dra_members.regnumber');
		$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid','left');
		$memberData = $this->master_model->getRecords('dra_member_exam',array('dra_member_exam.pay_status!='=>1));

	  
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "DRA_pending_members_.csv";
		$data = $this->dbutil->csv_from_result($memberData, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
	}
}
