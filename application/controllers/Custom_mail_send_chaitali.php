<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	18-06-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_mail_send_chaitali extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->model('Emailsending');
		 $this->load->helper('custom_admitcard_helper');
		// $this->load->helper('custom_cpd_invoice_helper');
		$this->load->model('Master_model');
		$this->load->model('log_model');
		$this->load->model('Emailsending');
		$this->load->library('upload');
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->helper('blended_invoice_helper');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
}
public function index()
{
	$mem = array(2503441,2503458,2503468,2503493,2503510,2503511,2507652,2518864,2522185,2522726,2523109,2527354);
	$select = 'regid , email, mobile';
	$this->db->where_in('regid' ,$mem);
	$member_data = $this->Master_model->getRecords('member_registration','',$select);
	//echo $this->db->last_query(); die;
	if(!empty($member_data))
	{	
		foreach($member_data as $mem)
		{
			//print_r($mem); die;
			$insert_data = array(
			'pay_type' =>'2',
			'regnumber' =>$mem['regid'],
			'email' =>$mem['email'],
			'mobile' =>$mem['mobile'],
			'email_sent_status' => '0',
			'sms_sent_status' => '0');
			
			$this->master_model->insertRecord('mail_send_chaitali_zero_pay', $insert_data);
		
		}
	}
}

public function feedback_details()
	{
		$data = '';
		//$exam_code_arr = array();
	//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
	//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
	/* $date = new DateTime('LAST DAY OF PREVIOUS MONTH');
            $to_date =  $date->format('Y-m-d');
            $from_date = date("Y-m-", strtotime($to_date))."01"; 
	 */		
			$csv = "Member no, Exam,Which one of the following streams have you studied in graduate/post graduate level?, Please indicate your work experience, Had you studied the IIBF books published by Macmillan for the examination?, Did you register and went through the e-learning provided by IIBF?, Did you register and went through the IIBF mock test?, Did you go through the subject updates provided on IIBF website?, Please indicate the time spent by you in preparing for the exam., How did you find the examination?,Which subject did you find difficult? ,In which type of questions did you face difficulty?,What type of additional pedagogical support you require from IIBF?,Do you feel that the time allotted for the exam is sufficient?,Your suggestions:,\n";
				
				$query = $this->db->query("SELECT * FROM `feedback_form`");
				$result = $query->result_array(); 
				//echo $this->db->last_query(); die;
				foreach($result as $record)
				{
					$csv.= $record['regnumber'].",".$record['email'].",".$record['streams'].",".$record['years'].",".$record['macmillan'].",".$record['e-learning'].",".$record['mock'].",".$record['subject'].",".$record['time'].",".$record['exam'].",".$record['subject_difficult'].",".$record['questions'].",".$record['support'].",".$record['examtime'].",".$record['suggestions']."\n";
				}
				$filename = "feedback_details_".date("YmdHis").".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            $csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);	
			
	}

public function refund_cron()
{
	$yesterday = date('Y-m-d', strtotime("- 1 day"));
	//$this->db->where('created_on',$yesterday);
	//$refund_details = $this->Master_model->getRecords('cron_auto_refund_log c');
	$csv = "receipt_no,transaction_no,response,refund,created_on\n";
	$refund_details = $this->db->query("SELECT receipt_no,transaction_no,response, refund ,created_on from cron_auto_refund_log WHERE refund = '1' AND DATE(created_on) ='$yesterday'");
	$result = $refund_details->result_array(); 
		//print_r($result); die;
		foreach($result as $record)
		{
			 $csv.= $record['receipt_no'].','.$record['transaction_no'].','.$record['response'].','.$record['refund'].','.$record['created_on']."\n";
		}
			//Refund count
			$refund_details = $this->Master_model->getRecordCount('cron_auto_refund_log c', array('refund'=>'1','DATE(created_on)'=>$yesterday));
			//echo $this->db->last_query(); die;
				$filename = $yesterday."refund_details.csv";
				//$filename = $from_date."payment_status.csv";
				$path = "uploads/payment_status/".$filename ."";
				$csv_handler = fopen($path, 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);
						
				$final_str = 'Hello Team, <br/><br/>';
				$final_str.= 'Please find attached Refund data sheet';   
				$final_str.= '&nbsp;';
				$final_str.= 'Of:- '.$yesterday;
				$final_str.= '<br/><br/>';
				$final_str.= 'Total member refund Count:- '.$refund_details;
				$final_str.= '<br/><br/>';
				$final_str.= 'Thanks & Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				$attachpath = $path;
				
				
							
				$info_arr=array('to'=>'iibfdevp@esds.co.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: Refund Report - '.$yesterday,
								'message'=>$final_str
							); 			 
						
				$files=array($attachpath);
						
				if($this->mailsend_attch_cpd($info_arr,$files))
			{
				
			}
												
			
			

}

function random_password($length = 6)
				{
					$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
					$password = substr( str_shuffle( $chars ), 0, $length );
					return $password;
				}
public function member_settlement_new()
{   
	
	
	
	$date = date('Y-m-d'); 
	$ref_id = array(5951108);
	$select = 'c.regnumber, c.exam_code,a.date , a.transaction_no , a.receipt_no , a.ref_id';
	$this->db->join('payment_transaction a','a.ref_id = c.id','LEFT');
	$this->db->join('exam_invoice b','b.receipt_no = a.receipt_no','LEFT');
	//$this->db->where('c.pay_status !=','1');
	$this->db->where('a.receipt_no !=','');
	//$this->db->where('a.date',$date);   
	$this->db->where_in('c.id',$ref_id);
	$can_exam_data = $this->Master_model->getRecords('member_exam c',array('pay_type'=>2,'status'=>1),$select);
	//echo $this->db->last_query(); exit; 
	
	//echo $id = $res['ref_id']; die; 
	if(!empty($can_exam_data))
	{
		foreach($can_exam_data as $res)
		{
			
								
			$password = $this->random_password();
			 $mem_exam_id = $res['ref_id'];
			$member_no = $res['regnumber'];
			$exam_code = $res['exam_code'];
			$exam_prd = '777';//$res['exam_period'];
			
			/* $update_mem = array('pay_status'=>'1');
			$this->master_model->updateRecord('member_exam', $update_mem,array('id'=>$mem_exam_id));
			 */
			$update_ad = array('mem_mem_no'=>$member_no);
			$this->master_model->updateRecord('admit_card_details', $update_ad,array('mem_exam_id'=>$mem_exam_id));
			
			echo $this->db->last_query();
			
			//check in admit card  table 
			$this->db->group_by('sub_cd,mem_exam_id');
			$admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$mem_exam_id,'mem_mem_no'=>$member_no,'exm_cd'=>$exam_code,
				'exm_prd'=>$exam_prd)); 
				
				echo $this->db->last_query(); 
			
	
	if(!empty($admit_card_details))
	{
		foreach($admit_card_details as $val)
		{
			if($val['pwd']!='')
			{
				$password =$val['pwd'];
			}	
		}
	}
	if($password == '')
	{
		$password = $this->random_password(); 
	}
	/********End of Password Code********/
	//echo 'here';
	if(!empty($admit_card_details))
	{
		echo 'Total recode found in admit card table :<br>';
		echo count($admit_card_details);
		//exit;
		foreach($admit_card_details as $val)
		{
			if($val['seat_identification']=='')
			{
			//get the  seat number from the seat allocation table 2
				$this->db->order_by("seat_no", "desc"); 
				$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
				if(!empty($seat_allocation))
				{
			//check venue_capacity
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
		//echo  $this->db->last_query();  
					
					$venue_capacity=$venue_capacity[0]['session_capacity']+200;
					if(!empty($venue_capacity))
					{
		   //if(count($seat_allocation)<=$venue_capacity)
						if($seat_allocation[0]['seat_no']<=$venue_capacity)
						{
							$seat_no=$seat_allocation[0]['seat_no'];
				//inset new recode with append  seat number
							$seat_no=$seat_no+1;
							if($seat_no<10)
							{
								$seat_no='00'.$seat_no;
							}
							elseif($seat_no>10 && $seat_no<100)
							{
								$seat_no='0'.$seat_no;
							}
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
							if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
							{	
				//inset new recode with append  seat number
			//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			//$password = substr( str_shuffle( $chars ), 0, 6 );
			
								
			
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
									'seat_identification' => $seat_no,
									'modified_on'=>$val['created_on'],
									'admitcard_image'=>$admitcard_image,
									'pwd' => $password, 
									'remark'=>1,
								);
								if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
								{
									echo '<br>Recode updated sucessfully in admit card<br>';
								}else
								{
									echo '<br>Recode Not updated sucessfully in admit card<br>';
								}
							}
						}else
						{
							echo '<br>Capacity has been full<br>';
						}
					}else
					{
						echo '<br>Venue not present in venue master123<br>';
					}
				}else
				{
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
						echo $this->db->last_query();
					if(!empty($venue_capacity))
					{
						if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity'])
						{
			//inset new recode with oo1
							$seat_no='001';
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
							if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
							{
								echo 'Seat alloation primary key :<br>';
								echo $inser_id;
					//update the admit card table :
			//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			//$password = substr( str_shuffle( $chars ), 0, 6 );
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
									'seat_identification' => $seat_no,
									'modified_on'=>$val['created_on'],
									'admitcard_image'=>$admitcard_image,
									'pwd' => $password, 
									'remark'=>1,
								);
								if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
								{
									echo 'Recode updated sucessfully in admit card<br>';
									echo count($admitcard_image);
								}else
								{
									echo 'Recode Not updated sucessfully in admit card<br>';
								}
							}
						}else
						{
							echo '<br>Capacity has been full<br>';
						}
					}else
					{
						echo '<br>Venue not present in venue master234<br>';
					}
				}}
			}
		}
			
		}
	}
	else{
		echo 'no record';
	}
	    
	
	
	}
	
public function member_admitcard_mail()
{
	$ref_id = array(5914517,5914530,5914535,5914571,5914578,5912636,5912709,5912980,5913213,5913662,5913786,5913859,5913870,5913979,5914097,5914233,5914457,5914525);
	$this->db->join('member_registration','admit_card_details.mem_mem_no = member_registration.regnumber', 'LEFT');
	$this->db->where_in('admit_card_details.mem_exam_id', $ref_id);
	$exam_admicard_details = $this->master_model->getRecords('admit_card_details',array('remark'=>'1'),'member_registration.email,mam_nam_1,sub_dsc,exam_date,time,regnumber');
	
	//echo $this->db->last_query(); exit;
	 if(!empty($exam_admicard_details))
	{
		foreach($exam_admicard_details as $res)
		{
			 $name = $res['mam_nam_1'];
			$email = $res['email'];
			$sub = $res['sub_dsc'];
			$exam_date = $res['exam_date'];
			$time = $res['time'];
			$regnumber = $res['regnumber'];
			
				$final_str = 'Hello '.$name; 
				$final_str.='<br/><br/>';
				$final_str.= 'Your Admit card of the Exam is present in your profile.';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Exam Name:- '.$sub; 
				$final_str.= '<br/><br/>';
				$final_str.= 'Exam Date:- '.$exam_date;
				$final_str.= '<br/><br/>';
				$final_str.= 'Time:- '.$time;
				$final_str.= '<br/><br/>';  
				$final_str.= 'Thanks & Regards';
				$final_str.= '<br/><br/>';  
				$final_str.= 'IIBF TEAM';
				
				$info_arr=array('to'=>$email,
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF:Exam '.$sub,
								'message'=>$final_str
						); 
							
				$this->Emailsending->mailsend_attch_cpd($info_arr,'');
				
				$insert = array('register_num'=>$regnumber,'email'=>$email ,'email_flag'=>'1');
				$inser_id=$this->master_model->insertRecord('settle_mail_users_416',$insert); 
				
		}
	} 
				
}
public function mailsend_attch_cpd($info_arr,$path)
	    {
						   $this->setting_smtp();
						   //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
							//$this->email->initialize($config);
							//$this->email->from($info_arr['from'],"iibf.com"); 
							$this->email->from('logs@iibf.esdsconnect.com',"IIBF"); 
							$this->email->to($info_arr['to']);
							$this->email->reply_to('noreply@iibf.org.in', 'IIBF');
							//$this->email->cc('chaitali.jadhav@esds.co.in');	// CC email added by Bhagwan Sahane, on 03-06-2017
							//$this->email->cc('iibfdevp@esds.co.in');	// CC email added by Bhagwan Sahane, on 03-06-2017
							$this->email->subject($info_arr['subject']);
							$this->email->message($info_arr['message']); 
							if(is_array($path)) 
							{
								foreach($path as $row)
								{
									$this->email->attach($row);
								}
							}
							else
							{
								if($path!=NULL || $path!='')
								{
									$this->email->attach($path);
								}
							}
							if($this->email->send())
							{
			 					//$this->email->print_debugger();
								//	echo $this->email->print_debugger();
								$this->email->clear(TRUE);
								return true;
							}
							
		}

// SMTP email setting here
	public function setting_smtp()
	{
		$permission=TRUE;
		
		if($permission==TRUE)
		{
			$config['protocol']    	= 'SMTP';
			//$config['smtp_host']    = 'iibf.esdsconnect.com';
			$config['smtp_host']    = '115.124.123.26';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '10';
			$config['smtp_user']    = 'logs@iibf.esdsconnect.com';
			$config['smtp_pass']    = 'logs@IiBf!@#';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not  
			$this->email->initialize($config);	
		}
	}		
public function cpd_data_custom()
{
	$data = '';
	/* $from_date =date('Y-m-d', strtotime("- 1 week"));  
	$to_date =date('Y-m-d', strtotime("- 1 day")) ;  */
	
	$from_date =date('Y-m-d', strtotime("2021-09-03"));  
	$to_date =date('Y-m-d', strtotime("2021-09-09")) ; 
	
	if(!empty($from_date))
	{
			$select = 'DISTINCT(a.id)';
			$this->db->join('payment_transaction b','b.ref_id=a.id AND b.member_regnumber=a.member_no','LEFT');
			
			if($from_date!='' && $to_date!='')
			{
				$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1                AND pay_type = 9 AND pay_status = "1" ');
			}
			else if($from_date!='' & $to_date=='')
			{
				
				$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1                AND pay_type = 9 AND pay_status = "1" ');
			}
				$data = $this->UserModel->getRecordCount("cpd_registration a", '', '',$select);
				
	}
	if(!empty($data))
	{
			 $csv = 			                                  "Member_No,Invoice_No,Namesub,Firstname,Middlename,Lastname,Email,Mobile,Address1,Address2,Address3,Address4,District,City,State,Pincode,Fee,Fee_amt,cgst_rate,cgst_amt,sgst_rate,sgst_amt,cs_total,igst_rate,igst_amt,igst_total,occupied ,Date\n";
			
		
			$query = $this->db->query("SELECT            DISTINCT(cpd_registration.member_no),exam_invoice.invoice_no,`namesub`,`firstname`,`middlename`,`lastname`,`email`,`mobile`,`address1`,`address2`,`address3`,`address4`,`district`,`city`,`state`,`pincode`,fee,`fee_amt`,`cgst_rate`,`cgst_amt`,`sgst_rate`,`sgst_amt`,`cs_total`,`igst_rate`,`igst_amt`,`igst_total`,DATE_FORMAT(payment_transaction.date,'%Y-%m-%d') AS date FROM `cpd_registration` LEFT JOIN exam_invoice ON exam_invoice.member_no = cpd_registration.member_no LEFT JOIN payment_transaction on payment_transaction.receipt_no = exam_invoice.receipt_no WHERE cpd_registration.pay_status = 1 AND payment_transaction.pay_type = 9 AND payment_transaction.status = 1 AND payment_transaction.ref_id = cpd_registration.id AND DATE(payment_transaction.date) BETWEEN ('$from_date') AND ('$to_date') ");
			
			//echo $this->db->last_query(); die;
			 
		$result = $query->result_array(); 
		//print_r($result); die;
		foreach($result as $record)
		{
			 $csv.= $record['member_no'].','.$record['invoice_no'].','.$record['namesub'].',"'.$record['firstname'].'","'.$record['middlename'].'","'.$record['lastname'].'",'.$record['email'].','.$record['mobile'].',"'.$record['address1'].'","'.$record['address2'].'","'.$record['address2'].'","'.$record['address3'].'","'.$record['address4'].'",'.$record['district'].','.$record['city'].','.$record['state'].','.$record['pincode'].','.$record['fee'].','.$record['fee_amt'].','.$record['cgst_rate'].','.$record['cgst_amt'].','.$record['sgst_rate'].','.$record['sgst_amt'].','.$record['cs_total'].','.$record['igst_rate'].','.$record['igst_amt'].','.$record['igst_total'].','.$record['date']."\n";
		}
		
				$filename = date("Ymd")."cpd_registration.csv";
				$path = "uploads/cpd_report/".$filename ."";
				$csv_handler = fopen($path, 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);
				
				$final_str = 'Hello Ma`am, <br/><br/>';
				$final_str.= 'Please find attached CPD data sheet';   
				$final_str.= '&nbsp;';
				$final_str.= 'From:- '.$from_date;
				$final_str.= '&nbsp;';
				$final_str.= 'To:- '.$to_date;
				$final_str.= '<br/><br/>';
				$final_str.= 'Thanks & Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				$attachpath = $path;
				
				/* $info_arr=array('to'=>'soumya@iibf.org.in,je.aca2@iibf.org.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: CPD Report - '.date("Y-m-d"),
								'message'=>$final_str
							);  */
							
				$info_arr=array('to'=>'soumya@iibf.org.in,je.aca2@iibf.org.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: CPD Report - '.$from_date. 'to' .$to_date,
								'message'=>$final_str
							); 			 
						
				$files=array($attachpath);
						
				if($this->Emailsending->mailsend_attch_cpdsheet($info_arr,$files))
			{
				
			}
												
			}
			else
			{ 
				
					$final_str = 'Hello Ma`am, <br/><br/>';
					$final_str.= 'There was no CPD registration ';   
					$final_str.= '&nbsp;';
					$final_str.= 'From:- '.$from_date;
					$final_str.= '&nbsp;';
					$final_str.= 'To:- '.$to_date;
					$final_str.= '<br/><br/>';
					$final_str.= 'Thanks & Regards,';
					$final_str.= '<br/>';
					$final_str.= 'IIBF TEAM'; 
					
					
					/* $info_arr=array('to'=>'soumya@iibf.org.in,je.aca2@iibf.org.in',
									'from'=>'noreply@iibf.org.in',
									'subject'=>'IIBF: CPD Report - '.date("Y-m-d"),
									'message'=>$final_str
								); */
					 $info_arr=array('to'=>'soumya@iibf.org.in,je.aca2@iibf.org.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: CPD Report From:- 2021-09-03 To:- 2021-09-09',
								'message'=>$final_str
							); 	 			
						
			$this->Emailsending->mailsend_attch_cpdsheet($info_arr,'');
				
					
			  }
}
/* public function contact_class_mail()
{
	echo "in function"; 
	$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' =>'902702759','status' => 1));
	$mem_no =  $payment_info[0]['member_regnumber'];
	$member       = $this->db->query("SELECT *
															FROM contact_classes_registration
															WHERE pay_status = 1 AND 	contact_classes_id = '3376'");
									$memtype      = $member->result_array(); 
									//get center name
									$this->db->where('center_code', $memtype[0]['center_code']);
									$center_info = $this->master_model->getRecords('contact_classes_center_master');
									
									$user_info  = $this->master_model->getRecords('contact_classes_Subject_registration', array('member_no' => $mem_no,'center_code'=>$memtype[0]['center_code'],'contact_classes_regid'=>$memtype[0]['contact_classes_id']));
									// email to user
									$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'contactclasses'));
									
									$selfstr1  = str_replace("#regnumber#", "" . $mem_no . "", $emailerstr[0]['emailer_text']);
									$selfstr2  = str_replace("#program_name#", "" . $user_info[0]['program_name'] . "", $selfstr1);
									$selfstr3  = str_replace("#center_name#", "" . $center_info[0]['center_name'] . "", $selfstr2);
									$selfstr4  = str_replace("#venue_name#", "" . $user_info[0]['venue_name'] . "", $selfstr3);
									//$selfstr5 = str_replace("#start_date#", "".$reg_info[0]['start_date']."",  $selfstr4);
									//$selfstr6 = str_replace("#end_date#", "".$reg_info[0]['end_date']."",  $selfstr5);
									$selfstr7  = str_replace("#name#", "" . $memtype[0]['namesub'] . " " . $memtype[0]['firstname'] . " " . $memtype[0]['middlename'] . " " . $memtype[0]['lastname'], $selfstr4);
									$selfstr8  = str_replace("#address1#", "" . $memtype[0]['address1'] . "", $selfstr7);
									$selfstr9  = str_replace("#address2#", "" . $memtype[0]['address2'] . "", $selfstr8);
									$selfstr10 = str_replace("#address3#", "" . $memtype[0]['address3'] . "", $selfstr9);
									$selfstr11 = str_replace("#address4#", "" . $memtype[0]['address4'] . "", $selfstr10);
									
									$selfstr12 = str_replace("#district#", "" . $memtype[0]['district'] . "", $selfstr11);
									$selfstr13 = str_replace("#city#", "" . $memtype[0]['city'] . "", $selfstr12);
									$selfstr14 = str_replace("#state#", "" . $memtype[0]['state'] . "", $selfstr13);
									$selfstr15 = str_replace("#pincode#", "" . $memtype[0]['pincode'] . "", $selfstr14);
									$selfstr19 = str_replace("#email#", "" . $memtype[0]['email'] . "", $selfstr15);
									$selfstr20 = str_replace("#mobile#", "" . $memtype[0]['mobile'] . "", $selfstr19);
									
									$selfstr29     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $selfstr20);
									$selfstr30     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $selfstr29);
									$selfstr31     = str_replace("#STATUS#", "Transaction Successful", $selfstr30);
									$final_selfstr = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $selfstr31);
									
									
									//	$newstring1 = str_replace("#NO#", "". $subscription_number."",  $emailerstr[0]['emailer_text']);
									//	$final_str= str_replace("#DATE#",  $emailerstr[0]['emailer_text']);
									  $info_arr = array('to'=>$memtype[0]['email'],
										//  'to' => 'kyciibf@gmail.com',
										'from' => $emailerstr[0]['from'],
										'subject' => $emailerstr[0]['subject'],
										'message' => $final_selfstr
									);
									
									if ($user_info[0]['zone_code'] == 'NZ') {
									$client_arr = array(
									'to'=>'sanjay@iibf.org.in,mkbhatia@iibf.org.in,iibfdevp@esds.co.in', //sanjay@iibf.org.in,mkbhatia@iibf.org.in,
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr
									);
									print_r($client_arr);
									} elseif ($user_info[0]['zone_code'] == 'EZ') {
									$client_arr = array(
									'to'=>'iibfez@iibf.org.in',
									//  'to' => 'kyciibf@gmail.com',
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr 
									);
									}elseif ($user_info[0]['zone_code'] == 'SZ') {
									$client_arr = array(
									//	'to'=>'kyciibf@gmail.com',
									//  'to'=>'vratesh@iibf.org.in,sriram@iibf.org.in',
									'to'=>'sriram@iibf.org.in,priya@iibf.org.in,govindarajanr@iibf.org.in,iibfsz@iibf.org.in',
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr
									);
									}
									elseif ($user_info[0]['zone_code'] == 'CO') {
									$client_arr = array(
									//	'to'=>'kyciibf@gmail.com', 
									'to'=>'training@iibf.org.in,vratesh@iibf.org.in',
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr
									);
									}
									
									
									echo $attachpath = 'https://iibf.esdsconnect.com/uploads/contact_classes_invoice/user/NZ/TUNZ_20-21_00024.jpg';
									
									if ($attachpath != '') {
										   
											if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
											  
											   $this->Emailsending->mailsend_attch($client_arr, $attachpath);
											   
											}
									}
						
									
}


public function jaiib_mailsend_settled()
{ 
				$final_str = '';
				$this->db->limit(500);
				$regnumber_data = $this->Master_model->getRecords('mail_send_chaitali_round2',array('email_sent_status'=>'0','pay_type'=>'2'));
				if(!empty($regnumber_data))
				{
						$final_str.= 'Dear Candidate,';
						$final_str.= '<br/><br/>';
						$final_str.= 'This is regarding your JAIIB exam application for May-2021.';
						$final_str.= '<br/><br/>';
						$final_str.= 'We have received your exam fees and your application is under processing..';
						$final_str.= '<br/><br/>';
						$final_str.= 'We will try to accommodate you at the same centre/venue or nearest centre/venue based on availability.';
						$final_str.= '<br/><br/>';
						$final_str.= 'Admit letter will be emailed to the registered email id with IIBF and the same will also be available for download in your profile by 10-Apr-2021';
						$final_str.= '<br/><br/>';
						
						
						$final_str.= 'Regards';
						$final_str.= '<br/>';
						$final_str.= 'IIBF TEAM'; 
					foreach($regnumber_data as $res)
					{	
					       $info_arr=array(
									'to'=>$res['email'],
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Regarding your JAIIB exam application for May-2021. ' .$res['regnumber'],
							'message'=>$final_str
				); 
				if($this->Emailsending->mailsend_attch($info_arr,''))
				{
					 
					$update_data = array('email_sent_status' => '1',
					'describtion'=>$final_str);
				    $this->master_model->updateRecord('mail_send_chaitali_round2', $update_data,array('regnumber'=>$res['regnumber']));
					
				}
					}
				}
				
			
}
public function jaiib_mailsend()
{ 
				$final_str = '';
				$this->db->DISTINCT('email');
				$this->db->limit(500);
				$regnumber_data = $this->Master_model->getRecords('mail_send_chaitali_zero_pay',array('email_sent_status'=>'0','pay_type'=>'2'));
				//echo $this->db->last_query(); die;
				if(!empty($regnumber_data))
				{
						$final_str.= 'Dear Candidate,';
						$final_str.= '<br/><br/>';
						$final_str.= 'Refund process has been initiated today and you will receive the amount within 10 days.
Inconvenience caused is regretted.';
						$final_str.= '<br/><br/>';
						
						
						$final_str.= 'Regards';
						$final_str.= '<br/>';
						$final_str.= 'IIBF TEAM'; 
					foreach($regnumber_data as $res)
					{	
					       $info_arr=array(
									'to'=>$res['email'],
							'from'=>'noreply@iibf.org.in',
							'subject'=>'IIBF:Exam Refund ' .$res['regnumber'],
							'message'=>$final_str
				); 
				if($this->Emailsending->mailsend_attch($info_arr,''))
				{
					 
					$update_data = array('email_sent_status' => '1',
					'describtion'=>$final_str);
				    $this->master_model->updateRecord('mail_send_chaitali_zero_pay', $update_data,array('regnumber'=>$res['regnumber']));
					
				}
					}
				}
				
			
}
public function jaiib_sms()

{

	$sms_data = array();

	$this->db->distinct('regnumber');  

	$this->db->where('sms_sent_status','0');

	$this->db->limit(500);

    $sms_data = $this->Master_model->getRecords('mail_send_chaitali_zero_pay','');

	if(!empty($sms_data))

	{

		foreach($sms_data as $res)

		{ 

			$mobile = $res['mobile']; 

				

				$sms_newstring ='Dear Candidate,
Refund process has been initiated today and you will receive the amount within 10 days.
Inconvenience caused is regretted.

Regards,
IIBF Team';

			   

			 $this->send_sms_jaiib($mobile, $sms_newstring);

		   

			 $update_data = array('sms_sent_status' =>'1');

			 $this->master_model->updateRecord('mail_send_chaitali_zero_pay', $update_data,array('regnumber'=>$res['regnumber']));

		}

	}

}

public function send_sms_jaiib($mobile=NULL,$text=NULL)

{



	if($mobile!=NULL && $text!=NULL)

	{

	

		$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$mobile."&text=".urlencode($text)."&senderid=IIBFNM&route_id=2&Unicode=0";

		

		$string = preg_replace('/\s+/', '', $url);

		

		$x = curl_init($string);

		

		curl_setopt($x, CURLOPT_HEADER, 0);	

		

		curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);

		

		curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			

		

		$reply = curl_exec($x); 

		//print_r($reply); die;

		curl_close($x);

		$res = $this->sms_balance_notify_jaiib($reply);

	}



}


public function sms_balance_notify_jaiib($html)

{

	$this->load->library('email');

	

	$sms_balance = 0;



	//$html = file_get_contents('sms_api_reply.php'); //get the html returned from the following url

	

	$dom = new DOMDocument();

	

	libxml_use_internal_errors(TRUE); // disable libxml errors

	

	if(!empty($html)){ // if any html is actually returned

	

		$dom->loadHTML($html);

		

		libxml_clear_errors(); // remove errors for yucky html

		

		$dom_xpath = new DOMXPath($dom);

	

		// get all the h2's with an id

		$dom_row = $dom_xpath->query('//span[@id="Label6"]');

	

		if($dom_row->length > 0){

			foreach($dom_row as $row){

				$sms_balance_str = $row->nodeValue;

				//echo $sms_balance_str;

			}

			

			$sms_balance = (int) trim(str_replace("Your current balance is : ", "", $sms_balance_str));

			

			// check current sms balance

			if($sms_balance == 1000 || $sms_balance == 500 || $sms_balance == 300 || $sms_balance == 100)

			{

				// send email notification

				$from_name = 'IIBF';

				$from_email = 'noreply@iibf.org.in';

				$subject = 'SMS Balance Alert';

				

				// email receipient list -

				//$recipient_list = array('bhagwan.sahane@esds.co.in', 'shruti.samdani@esds.co.in', 'prafull.tupe@esds.co.in');

				

				$recipient_list = array('iibfdevp@esds.co.in');

				

				$message = 'Your current balance is : ' . $sms_balance;

				

				$config = array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);

				

				$this->email->initialize($config);

				$this->email->from($from_email, $from_name);

				$this->email->to($recipient_list);

				$this->email->subject($subject);

				$this->email->message($message);

				if($this->email->send())

				{

					//echo 'Email Sent.';

					

					//$this->email->print_debugger();

					//echo $this->email->print_debugger();

					

					return true;

				}

				else

				{

					//echo 'Email Not Sent.';

					

					return false;	

				}

			}

		}

	}

}
 */
}