<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	08-01-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Cpdcron extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->model('Emailsending');
		 $this->load->helper('custom_admitcard_helper');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
}
public function remote_admit_card_details()
{	//echo '***'; die;
	$exam_codes_arr = array('1002','1003','1004');
	//$member_no = array(510465562,510358194,510295016,510341435);
	$select = 'a.regnumber,c.email,c.mobile,a.exam_code,a.exam_period';
	$this->db->where('DATE(a.created_on) >=', '2020-06-09');
	$this->db->where('DATE(a.created_on) <=', '2020-06-15');
	$this->db->where_in('a.exam_code',$exam_codes_arr);
	//$this->db->where_in('a.regnumber',$member_no);
	$this->db->join('member_exam a','c.regnumber=a.regnumber','LEFT');
	$member_data = $this->Master_model->getRecords('member_registration c',array('isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
	//echo $this->db->last_query(); die;		
	if(!empty($member_data))
	{
		//print_r($member_data); die;
		foreach($member_data as $member)
		{
			$insert_data = array(
			'member_no' => $member['regnumber'],
			'email'     => $member['email'],
			'mobile'    => $member['mobile'],
			'exam_code' => $member['exam_code'],
			'exam_period' => $member['exam_period'],
			'image_gen_status' => '0',
			'email_sent_status' => '0'
			);
			//print_r($insert_data); die;
			$this->master_model->insertRecord('remote_admit_card_sending', $insert_data);	
		}
	}
}

public function admit_image_gen()
{
	 			//$select = array('a.member_no,a.exam_code,a.exam_period');
				$this->db->limit(10);
				$this->db->where_in('a.image_gen_status','0');
				$regnumber_data = $this->Master_model->getRecords('remote_admit_card_sending a ',array('email_sent_status'=>'0'));
				
				//echo $this->db->last_query(); die;
				//print_r($regnumber_data); die;
				if(!empty($regnumber_data))
				{
					foreach($regnumber_data as $regnumber)
					{
						$exam_period = $regnumber['exam_period'];
						$exam_code =  $regnumber['exam_code']; 
						$member_array = $regnumber['member_no']; //array(510074823,510465562,510358194,510295016,510341435);//        
						//$arr_size = sizeof($member_array);  
							//echo $member_array; die;
							 $path = genarate_admitcard_custom_new($member_array,$exam_code,$exam_period);  
							if(!empty($path))
							{	
								//update image flag
										$update_flag = array('image_gen_status' => '1');
										$this->master_model->updateRecord('remote_admit_card_sending', $update_flag,array('member_no'=>$regnumber['member_no']));
								
							}
					
						
					}
				}
			
}
public function remote_admit_card_send()
{ 
	$this->db->where('image_gen_status','1');
	$regnumber_data = $this->Master_model->getRecords('remote_admit_card_sending ',array('email_sent_status'=>'0'));
	if(!empty($regnumber_data))
	{
		foreach($regnumber_data as $regnumber_data)
		{
			$mem_mem_no = $regnumber_data['member_no'];											
			$this->db->distinct('a.mem_mem_no');    
			$select = 'c.member_no,a.admitcard_image,a.exm_cd';
			$this->db->where('remark',1);
			$this->db->where('exm_prd','777');
			$this->db->where('admitcard_image !=','');
			$this->db->join('remote_admit_card_sending c', 'a.mem_mem_no = c.member_no');
			$admit_card_data = $this->master_model->getRecords('admit_card_details a' , array('mem_mem_no'=>$mem_mem_no,'image_gen_status'=>'1'),$select); 
			if(!empty($admit_card_data))
			{
				foreach($admit_card_data as $admit_card_data)
				{	
					$this->db->where('exam_code',$admit_card_data['exm_cd']);
					$exam_name = $this->master_model->getRecords('exam_master','','description');
					$final_str = 'Hello Sir/Madam <br/><br/>';
					$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
					$final_str.= '<br/><br/>';
					$final_str.= 'Regards,';
					$final_str.= '<br/>';
					$final_str.= 'IIBF TEAM'; 
					  
					$attachpath = "uploads/admitcardpdf/".$admit_card_data['admitcard_image'];  
					$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$mem_mem_no,'isactive'=>'1'),'email,mobile');   
					$info_arr=array('to'=>$email[0]['email'],
									'from'=>'noreply@iibf.org.in',
									'subject'=>'Admit Card Letter',
									'message'=>$final_str
								); 
					$files=array($attachpath);
					if($this->Emailsending->mailsend_attch($info_arr,$files))
					{
						//echo "Mail send to ==> ".$mem_mem_no;
						//echo "<br/>"; 
						 $update_data = array('email_sent_status' => '1',
						 					   'image_gen_status' => '2');
						 $this->master_model->updateRecord('remote_admit_card_sending', $update_data,array('member_no'=>$mem_mem_no));
					}
				}
			}
		}
	}			
}
public function blended_count()
{
	$data = '';
	//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
	//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
	$date = new DateTime('LAST DAY OF PREVIOUS MONTH');
            $to_date =  $date->format('Y-m-d');
            $from_date = date("Y-m-", strtotime($to_date))."01";
	if($from_date!='' && $to_date!='')
	{
			//Count for VC fee 0 
			$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('training_type','VC');
			$this->db->where('fee','0');
			$this->db->where('application_flag','TRG');
			$vc_nofee_count = $this->Master_model->getRecordCount('blended_registration',array('pay_status'=>'1'));
			
			//Count for VC paid fees
			$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('a.training_type','VC');
			$this->db->where('a.fee !=','0');
			$this->db->where('a.application_flag','TRG');
			$this->db->join('payment_transaction b','b.ref_id = a.blended_id','LEFT');
			$vc_fee_count = $this->Master_model->getRecordCount('blended_registration a',array('pay_status'=>'1','status'=>'1','pay_type'=>'10'));
			//echo $this->db->last_query(); die;
			//Count for VC paid fees	
			
			$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');	
			$this->db->where('a.training_type','VP');		
			$this->db->where('a.fee !=','0');	
			$this->db->where('a.application_flag','TRG');		
			$this->db->join('payment_transaction b','b.ref_id = a.blended_id','LEFT');	
			$vp_fee_count = $this->Master_model->getRecordCount('blended_registration a',array('pay_status'=>'1','status'=>'1','pay_type'=>'10'));	
			
			//echo $this->db->last_query(); die;
			//Count for PC paid
			$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('a.training_type','PC');
			$this->db->where('a.fee !=','0');
			$this->db->where('a.application_flag','TRG');
			$this->db->join('payment_transaction b','b.ref_id = a.blended_id','LEFT');
			$pc_fee_count = $this->Master_model->getRecordCount('blended_registration a',array('pay_status'=>'1','status'=>'1','pay_type'=>'10'));
			
			//Count for exam_trg
			$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('training_type','VC');
			$this->db->where('fee','0');
			$this->db->where('application_flag','EXTRG');
			$pc_nofee_examcount = $this->Master_model->getRecordCount('blended_registration',array('pay_status'=>'1'));
			//echo $this->db->last_query(); die;
			
			if($vc_nofee_count >= 0 && $vc_fee_count >= 0 && $pc_fee_count >= 0 && $pc_nofee_examcount >=0 && $vp_fee_count >= 0)
			{
				
			$final_str = 'Hello Team, <br/><br/>';

			$final_str.= 'Kindly find the Blended Member Count from '.$from_date. ' To ' .$to_date ;   
			$final_str.= '<br/><br/>';
			$final_str.= 'VC Member Count(NoPaid):- '.$vc_nofee_count;
			$final_str.= '<br/><br/>';
			$final_str.= 'VC Member Count(Paid):- '.$vc_fee_count;
			$final_str.= '<br/><br/>';					
			$final_str.= 'VP Member Count(Paid):- '.$vp_fee_count;		
			$final_str.= '<br/><br/>';
			$final_str.= 'PC Member Count(Paid):- '.$pc_fee_count;
			$final_str.= '<br/><br/>';
			$final_str.= 'PC ExamTrg Count(NoPaid):- '.$pc_nofee_examcount;
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
				
			$info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in,iibfdevp@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>"; 
				echo $final_str; 
		}
			
	}
		
}
public function mem_count()
{ 

	$data = ''; 
	$from_date = '2020-04-01';
	$to_date ='2021-03-31'; 
	//$from_date = date('Y-m-d', strtotime("- 1 week"));  
	//$to_date = date('Y-m-d', strtotime("- 1 day")) ; 
	/* $date = new DateTime('LAST DAY OF PREVIOUS MONTH');
           $to_date =  $date->format('Y-m-d');
           $from_date = date("Y-m-", strtotime($to_date))."01"; */
	if($from_date!='' && $to_date!='')
	{
		$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
		$this->db->where('registrationtype','O');
		$this->db->where('regnumber != ','');
		$mem_o_type_count = $this->Master_model->getRecordCount('member_registration',array('isactive'=>'1','isdeleted'=>'0','is_renewal'=>'0'));
		//echo $this->db->last_query(); die;
		$this->db->where('DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
		$this->db->where('registrationtype','O');
		$this->db->where('regnumber != ','');
		$ren_mem_o_type_count = $this->Master_model->getRecordCount('member_registration',array('isactive'=>'1','isdeleted'=>'0','is_renewal'=>'1'));
		//echo $this->db->last_query(); die; 
		//echo $mem_o_type_count; die;
		if($mem_o_type_count > 0 || $ren_mem_o_type_count > 0 )
		{ 
			$final_str = 'Hello Sir <br/><br/>';

			$final_str.= 'Kindly find the ordinary Member Count and Renewal Count from '.$from_date. ' To ' .$to_date ;   
			$final_str.= '<br/><br/>';
			$final_str.= 'Ordinary Member Count:- '.$mem_o_type_count;
			$final_str.= '<br/><br/>';
			$final_str.= 'Renewal Member Count:- '.$ren_mem_o_type_count;
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
				
			$info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'chaitali.jadhav@esds.co.in',
				'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Ordinary Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>"; 
		}
	}
	
}

public function cpd_data()
{
	$data = '';
	$from_date =date('Y-m-d', strtotime("- 1 week"));  
	$to_date =date('Y-m-d', strtotime("- 1 day")) ; 
	
	//$from_date =date('Y-m-d', strtotime("2021-08-13"));  
	//$to_date =date('Y-m-d', strtotime("2021-08-19")) ; 
	
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
				
				$info_arr=array('to'=>'soumya@iibf.org.in,je.aca7@iibf.org.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: CPD Report - '.$from_date. 'to' .$to_date,
								'message'=>$final_str
							); 
							
			/* 	$info_arr=array('to'=>'pratibha.purkar@esds.co.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: CPD Report From:- 2021-08-13 To:- 2021-08-19',
								'message'=>$final_str
							); 			 */
						
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
					
					
					$info_arr=array('to'=>'soumya@iibf.org.in,je.aca7@iibf.org.in',
									'from'=>'noreply@iibf.org.in',
									'subject'=>'IIBF: CPD Report - '.$from_date. 'to' .$to_date,
									'message'=>$final_str
								);
					/* $info_arr=array('to'=>'pratibha.purkar@esds.co.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: CPD Report From:- 2021-08-13 To:- 2021-08-19',
								'message'=>$final_str
							); 	 */				
						
			$this->Emailsending->mailsend_attch_cpdsheet($info_arr,'');
				
					
			  }
}
}