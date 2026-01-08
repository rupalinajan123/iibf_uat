<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	18-06-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class AdmitCard_mail extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->model('master_model');	
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->helper('custom_admitcard_helper');
		$this->load->helper('invoice_helper');
		$this->load->helper('fee_helper');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
}

/* CUSTOM ADMIT SM FUNCTION */

function random_password($length = 6)
				{
					$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
					$password = substr( str_shuffle( $chars ), 0, $length );
					return $password;
				}
			
				
public function member_settlement_new()
	{   
	
	$member_no = array(510455154);				
		
	$mem_exam_id = array(7018594); // member exam table primary key OR admit_card_detail mem_exam_id
	$exam_code = $this->config->item('examCodeJaiib');  
	$exam_prd = 123; 
	$password = $this->random_password(); 
	
	//check in admit card  table
	$this->db->where_in('mem_mem_no',$member_no);
	$this->db->where_in('mem_exam_id',$mem_exam_id);
	$this->db->group_by('sub_cd,mem_exam_id');
	$admit_card_details=$this->master_model->getRecords('admit_card_details',array('exm_cd'=>$exam_code,
		'exm_prd'=>$exam_prd)); 
		
	//echo $this->db->last_query(); exit;
	
	/********Password Code********/
	
	
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
	echo 'here';
	if(!empty($admit_card_details))
	{echo 'here123123';
		echo 'Total recode found in admit card table :<br>';
		echo count($admit_card_details);
		// print_r($admit_card_details);
		//exit;
		foreach($admit_card_details as $val)
		{
			if($val['seat_identification']=='')
			{
				echo "prt--";
			//get the  seat number from the seat allocation table 2
				$this->db->order_by("seat_no", "desc"); 
				$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
				if(!empty($seat_allocation))
				{
			//check venue_capacity
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'institute_code'=>'0',
						'exam_date'=>$val['exam_date']));
		//echo  $this->db->last_query(); exit;
					
					$venue_capacity=$venue_capacity[0]['session_capacity']+20;
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



	/* function added by Pratibha*/
public function getResponse()
{
	include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
	$key = $this->config->item('sbi_m_key');
	
	$aes = new CryptAES();
	$aes->set_key(base64_decode($key));
	$aes->require_pkcs5();
				
	$query='SELECT receipt_no FROM payment_transaction WHERE date(date) BETWEEN "2020-04-01" AND "2021-03-31" and status = 1 and amount>2000 and gateway = "sbiepay" AND `paymode` IS NULL';
	$crnt_day_txn_qry = $this->db->query($query);
	if ($crnt_day_txn_qry->num_rows())
	{
		foreach ($crnt_day_txn_qry->result_array() as $c_row)
		{
			echo "<br/> Receipt_no =>".$c_row['receipt_no'];
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			//echo "<pre>";
			//print_r($responsedata);
			if($responsedata[2] == 'SUCCESS')
			{
				if($responsedata[6] != "")
				{
									
	$update_data = array('paymode'=>$responsedata[12]);
	$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$c_row['receipt_no']));			
					echo "<br/> Update =>".$this->db->last_query();
				
				}
			}
		}//foreach
	}//if
} 
public function elearning_exam_invoice_settle()
{
	$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		
	//$receipt_no = array(902856296); 
	//902856663,902856631,902856296,902856831 - setlled
	$receipt_no = array(902856479,902856581,902856613,902856637,902856942,902856761,902856947,902856935,902856189,902856703,902856503);
	foreach($receipt_no as $receipt_no)
	{
	$this->db->where_in('receipt_no',$receipt_no);
	$elearning_exam_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>18,'status'=>1));
	//echo $this->db->last_query(); die;
	if(!empty($elearning_exam_data))
	{
		foreach($elearning_exam_data as $res)
		{
			 $member_no = $res['member_regnumber'];
			//get state name 
			$get_mem_state_code = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_no,'isactive'=>'1'),'state,registrationtype');
			//print_r($get_mem_state_code); die;
			$state_name = $get_mem_state_code[0]['state'];
			//get sate code 
			$getstate = $this->master_model->getRecords('state_master',array('state_code'=>$state_name,'state_delete'=>'0'));
			//get group code
			$getgrpcode = $this->master_model->getRecords('eligible_master',array('exam_code'=>$res['exam_code'],'member_no'=>$member_no),'app_category');
			//echo $this->db->last_query();
			//print_r($getstate[0]['state_no']); die;
			$eprid = '999';
			$center_code = '999';
			$app_category = 'B1_1';
			//call to helper (fee_helper)
			
				$getfees=getExamFeedetails_custom($center_code,$eprid,$res['exam_code'],$app_category,$get_mem_state_code[0]['registrationtype']);
			//print_r($getfees); die;
			$mem_state_code = "MAH";
			if(count($get_mem_state_code) > 0){
				$mem_state_code = $get_mem_state_code[0]['state'];
			}
			//get state code,state name,state number.
			$getstate = $this->master_model->getRecords('state_master',array('state_code'=>$mem_state_code,'state_delete'=>'0'));
			if($mem_state_code == 'MAH'){
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$this->config->item('cgst_rate');
				$sgst_rate=$this->config->item('sgst_rate');
				//set an amount as per rate
				$cgst_amt=$getfees[0]['cgst_amt'];
				$sgst_amt=$getfees[0]['sgst_amt'];
				 //set an total amount
				$cs_total=$getfees[0]['cs_tot'];
				$tax_type='Intra';
			}else{
				$igst_rate=$this->config->item('igst_rate'); 
				$igst_amt=$getfees[0]['igst_amt'];
				$igst_total=$getfees[0]['igst_tot']; 
				$tax_type='Inter';
			}
			
			/* End to Get Member State Code for GST Calculations : Bhushan 8/April/2019 */
			
			if($getstate[0]['exempt']=='E')
			{
				 $cgst_rate=$sgst_rate=$igst_rate='';	
				 $cgst_amt=$sgst_amt=$igst_amt='';	
			}	
			
			
			$invoice_insert_array=array('pay_txn_id'=>$res['id'],
													'receipt_no'=>$res['receipt_no'],
													'transaction_no'=>$res['transaction_no'],
													'exam_code'=>$res['exam_code'],
													'center_code'=>'999',
													'center_name'=>'SELF PACED LEARNING',
													'state_of_center'=>$get_mem_state_code[0]['state'],
													'member_no'=>$member_no,
													'app_type'=>'L',
													'exam_period'=>'999',
													'service_code'=>'999799',
													'qty'=>'1',
													'state_code'=>$getstate[0]['state_no'],
													'state_name'=>$getstate[0]['state_name'],
													'tax_type'=>$tax_type,
													'fee_amt'=>$getfees[0]['fee_amount'],
													'cgst_rate'=>$cgst_rate,
													'cgst_amt'=>$cgst_amt,
													'sgst_rate'=>$sgst_rate,
													'sgst_amt'=>$sgst_amt,
													'igst_rate'=>$igst_rate,
													'igst_amt'=>$igst_amt,
													'cs_total'=>$cs_total,
													'igst_total'=>$igst_total,
													'exempt'=>$getstate[0]['exempt'],													//static just for unit testing added by chaitali
 													'date_of_invoice'=>$res['date'],
													'created_on'=>$res['date'],
													'modified_on'=>date('Y-m-d H:i:s'));
			 $last_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
			 $invoice_id = $this->db->insert_id();
			 //echo $this->db->last_query();
			 $invoiceNumber =generate_elearning_exam_invoice_number($invoice_id);
						if($invoiceNumber)
						{
							$invoiceNumber='SPL/21-22/'.$invoiceNumber;
						}
					
					$update_data = array('invoice_no' => $invoiceNumber);
					$this->db->where('pay_txn_id',$res['id']);
					$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$res['receipt_no'],'invoice_id'=>$invoice_id));
				
					$attachpath=genarate_elearning_exam_invoice($invoice_id);
		}
	}
	
	}
	
}

													
public function config_elearning()
{
	$invoice_id = '2522530';
	$this->db->where_in('invoice_id',$invoice_id);
	$invoice_data = $this->Master_model->getRecords('exam_invoice',array('app_type'=>'L'));
	//echo $this->db->last_query(); die;
	if(!empty($invoice_data))
	{
		foreach($invoice_data as $res)
		{
			$pid = $res['pay_txn_id'];
			$receipt_no = $res['receipt_no'];
			$invoiceNumber =generate_elearning_exam_invoice_number($invoice_id);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;
						}
					
					$update_data = array('invoice_no' => $invoiceNumber);
					$this->db->where('pay_txn_id',$pid);
					$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$receipt_no));
					$attachpath=genarate_elearning_exam_invoice($invoice_id);
		}
	}
	
}													
													
													
public function data_insert()
{	
	$receipt_no = array(902576576,902576660,902577078,902577529,902583290,902583508,902585728,902586306,902586454,902586751,902587003,902587369,902588526,902589188,902589384,902589701,902591140,902593476,902593996,902595442,902596935,902597566,902600030,902600340,902601256,902602747,902602942,902603012,902604437,902604819,902606299,902606674,902606720,902606748,902607129,902608610,902608624,902608678,902608888,902609016,902609286,902609586,902610213,902610549,902611287,902613025,902614668,902614802,902615153,902615292,902615399,902616078,902616282,902625438,902625521,902625548,902625630,902625638,902625815,902625814,902626171,902626527,902626551,902626678,902627135,902627308,902627313,902627796,902629056,902629215,902629294,902629472,902630058,902630694,902636899,902636947,902636987,902637013,902637117,902637142,902637366,902637414,902637446,902637543,902637625,902637745,902637749,902637792,902637864,902637990,902638031,902638054,902638116,902638148,902638228,902638240,902638320,902638324,902638413,902638654,902639395,902641042,902641159,902641261,902642652,902642674,902642785,902643295,902643308,902643551,902643568,902643575,902643635,902643776,902643884,902643929,902643934,902643942,902644023,902644151,902644152,902644161,902644197,902644211,902644235,902644385,902644416,902644434,902644535,902644555,902644599,902644626,902644702,902644717,902644733,902645744,902645930,902646385,902646499,902646749,902646841,902647047,902647208,902648844,902649187,902649419,902649695,902649702,902649793,902649867,902649870,902650105,902650366,902650413,902650516,902650541,902650600,902650734,902650781,902650847,902650852,902650899,902650917,902651034,902651071,902651249,902651314,902651494,902651523,902651577,902651880,902651904,902652179,902652204,902652245,902652303,902652371,902652453,902652464,902652494,902652511,902652521,902652539,902652582,902652605,902652746,902652897,902652913,902652932,902652940,902652962,902653061,902653072,902653101,902653425,902653440,902653476,902653536,902653544,902653622,902653659,902653684,902653719,902653777,902653789,902653792,902653910,902653924,902653932,902653962,902654014,902654060,902654067,902654094,902654103,902654147,902654152,902654184,902654326,902654319,902654320,902654396,902654410,902654431,902654441,902654474,902654498,902654552,902654643,902654679,902654696,902654721,902654746,902654791,902655073,902655140,902655457,902655593,902655757,902656201,902656703,902656887,902656900,902657159,902657162,902657192,902657197,902657261,902585700,902586797,902601249,902602294,902604487,902608827,902611196,902612282,902614483,902616370,902620308,902626059,902632187,902632553,902633893,902633940,902635006,902636884,902637097,902637169,902637288,902637622,902637850,902637980,902638111,902638412,902640354,902642666,902642763,902643892,902644299,902646575,902646733,902647625,902650057,902650580,902650596,902650863,902651187,902651214,902651229,902651463,902651495,902651761,902652138,902652499,902652989,902653459,902653677,902653765,902653953,902653958,902653994,902654350,902654354,902654907,902655012,902655046,902655659,902657048);
	
	foreach($receipt_no as $receipt_no)
	{
		//print_r($mem); die;
			$insert_data = array(
			
			'receipt_no' =>$receipt_no);
			
			$this->master_model->insertRecord('refund_one', $insert_data);
	}
			
		
}
public function member_reg_mails()
{
				$final_str = '';
				//$this->db->limit(500);
				$regnumber_data = $this->Master_model->getRecords('member_send_chaitali',array('email_sent_status'=>'0'));
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
							'subject'=>'IIBF:Membership Registration',
							'message'=>$final_str
				); 
				if($this->Emailsending->mailsend_attch($info_arr,''))
				{
					 
					$update_data = array('email_sent_status' => '1',
					'describtion'=>$final_str);
				    $this->master_model->updateRecord('member_send_chaitali', $update_data,array('regid'=>$res['regid']));
					
				}
					}
				}
				
}




public function member_sms()

{

	$sms_data = array();

	$this->db->distinct('receipt_no');  

	$this->db->where('sms_sent_status','0');

	$this->db->limit(300);

    $sms_data = $this->Master_model->getRecords('member_send_chaitali','');

	if(!empty($sms_data))

	{

		foreach($sms_data as $res)

		{ 

			$mobile = $res['mobile']; 

				/* SMS Sending Code */

				$sms_newstring ='Dear Candidate,
Refund process has been initiated today and you will receive the amount within 10 days.
Inconvenience caused is regretted.

Regards,
IIBF Team';

			   

			 $this->send_sms_jaiib($mobile, $sms_newstring);

		   

			 $update_data = array('sms_sent_status' =>'1');

			 $this->master_model->updateRecord('member_send_chaitali', $update_data,array('regid'=>$res['regid']));

		}

	}

}


public function mem_update()
{
	$mem = array(2504324,2505554,2507693,2509775,2518853,2519806,2521711,2522111,2522463,2522486,2522655,2522678,2522686,2522736,2522866,2522981,2523043,2523727,2523939,2524107,2524121,2524122,2524127,2524157,2524164,2524178,2524204);
	$this->db->where_in('regid', $mem);
	$new_mem_reg = $this->Master_model->getRecords('config_NM_memreg','');
	if(!empty($new_mem_reg))
	{
		foreach($new_mem_reg as $res)
		{
			$regnumber = $res['NM_regnumber'];
			$id = $res['regid'];
			$update_data = array('regnumber'=>$regnumber ,'isactive'=>'1');
			$this->master_model->updateRecord('member_registration',$update_data,array('regid'=>$id));
			//echo $this->db->last_query();

		}
	}
	
	//echo $this->db->last_query(); die;

}
 
public function mem_invoice_update()
{
	$rec = array(902855856,902855861,902855864,902855865,902855866,902855878,902855896,902855898,902855901,902855903,902855910,902855912,902855914,902855917,902855918,902855922,902855925,902855929,902855930,902855944,902855947,902855948,902855949,902855951,902855952,902855955,902855959,902855966,902855975,902855976,902855981,902855982,902855986,902855993,902855998,902855999,902856000,902856001,902856005,902856007,902856009,902856010,902856014,902856016,902856019,902856023,902856024,902856029,902856032,902856035,902856036,902856040,902856043,902856047,902856052,902856054,902856056,902856057,902856062,902856065,902856066,902856067,902856068,902856069,902856070,902856071,902856072,902856073,902856074,902856079,902856080,902856081,902856082,902856089,902856090,902856093,902856094,902856096,902856100,902856104,902856107,902856108,902856109,902856110,902856115,902856121,902856125,902856126,902856132,902856137,902856141,902856143,902856146,902856162,902856166,902856169,902856171,902856174,902856175,902856176,902856179,902856185,902856190,902856191,902856198,902856199,902856200,902856201,902856214,902856222,902856223,902856224,902856226,902856230,902856231,902856233,902856236,902856238,902856242,902856250,902856255,902856256,902856259,902856262,902856265,902856267,902856269,902856276,902856277,902856278,902856279,902856280,902856282,902856285,902856291,902856297,902856302,902856307,902856311,902856312,902856313,902856314,902856319,902856320,902856325,902856326,902856330,902856337,902856338,902856339,902856342,902856344,902856345,902856348,902856352,902856353,902856355,902856356,902856357,902856358,902856360,902856361,902856364,902856372,902856375,902856376,902856377,902856389,902856390,902856395,902856403,902856404,902856406,902856407,902856408,902856412,902856415,902856419,902856423,902856424,902856425,902856427,902856428,902856433,902856437,902856439,902856441,902856443,902856446,902856452,902856454,902856455,902856458,902856461,902856462,902856464,902856466,902856468,902856473,902856478,902856483,902856486,902856489,902856490,902856491,902856492,902856495,902856496,902856498,902856499,902856500,902856502,902856505,902856506,902856509,902856516,902856517,902856524,902856525,902856531,902856537,902856539,902856546,902856547,902856561,902856562,902856566,902856567,902856568,902856571,902856573,902856576,902856580,902856582,902856584,902856585,902856588,902856590,902856592,902856594,902856595,902856596,902856599,902856602,902856603,902856605,902856607,902856611,902856620,902856621,902856623,902856624,902856625,902856630,902856632,902856634,902856638,902856640,902856644,902856647,902856648,902856649,902856650,902856653,902856654,902856656);
	$this->db->where_in('receipt_no', $rec);
	$invoice_data = $this->Master_model->getRecords('payment_transaction ',array('transaction_no !='=>'','pay_type'=>'20','status'=>'1'));
	if(!empty($invoice_data))
	{
		foreach($invoice_data as $res)
		{
			//$regnumber = $res['member_no'];
			$transaction_no = $res['transaction_no'];
			$receipt_no = $res['receipt_no'];
			
			$update_data = array('transaction_no'=>$transaction_no);
			$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$receipt_no));
			
			//echo $this->db->last_query();
		}
	}
	//echo $this->db->last_query(); die;
}
public function exam_invoice_update()
{
	$rec = array(903523399,903523421);
	$this->db->where_in('receipt_no', $rec);
	$invoice_data = $this->Master_model->getRecords('exam_invoice',array('invoice_no !='=>'','app_type'=>'O'));
	if(!empty($invoice_data))
	{
		foreach($invoice_data as $res)
		{
			$regnumber = $res['member_no'];
			$transaction_no = $res['transaction_no'];
			$receipt_no = $res['receipt_no'];
			
			$update_data = array('transaction_no'=>$transaction_no,'status'=>'1');
			//,'transaction_no'=>$transaction_no,'status'=>'1'
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$receipt_no));
			
			echo $this->db->last_query(); die;
		}
	}
	//echo $this->db->last_query(); die;
}
public function mem_exam_update()
{
	$id = array(5557684,5558576,5558989,5558658,5550575,5557523,5544415,5515608,5546224,5550501,5551651,5558473,5551326,5547267,5533236,5548876,5505383,5492724,5486959,5558890,5558653,5558980,5526662,5558771,5558787,5558688,5558810,5551313,5552949,5551439,5550671,5552667,5558586,5552081,5551419,5556839,5552217);
	$this->db->where_in('ref_id', $id);
	$invoice_data = $this->Master_model->getRecords('payment_transaction ',array('status ='=>'1','pay_type'=>'2'));
	if(!empty($invoice_data))
	{
		foreach($invoice_data as $res)
		{
			$regnumber = $res['member_regnumber'];
			//$transaction_no = $res['transaction_no'];
			$id = $res['ref_id'];
			
			$update_data = array('regnumber'=>$regnumber,'pay_status'=>'1');
			//,'transaction_no'=>$transaction_no,'status'=>'1'
			$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$id));
			
			//echo $this->db->last_query();
		}
	}
	//echo $this->db->last_query(); die;
}
public function cpd_invoice_update()
{
	$select = 'c.receipt_no ,c.transaction_no , c.ref_id , c.date,b.invoice_id , c.id';
	$this->db->join('exam_invoice b','b.receipt_no = c.receipt_no','LEFT');
	$this->db->join('cpd_registration p','c.ref_id = p.id','LEFT');
	$this->db->where('b.invoice_no','');
	$payment_details = $this->Master_model->getRecords('payment_transaction c',array('c.pay_type' =>'9','c.status' =>'1','p.pay_status'=>'1'),$select);
	
	if($payment_details != 0)
	{
		foreach($payment_details as $payres)
		{
			
			$invoiceNumber = $payres['invoice_id'];
			$transaction_no = $payres['transaction_no'];
			$MerchantOrderNo = $payres['receipt_no'];
			$date            = $payres['date'];
			$invoiceNumber = generate_cpd_invoice_number($invoiceNumber);
			
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('CPD_invoice_no_prefix').$invoiceNumber;
												}
												
				$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>$date,'modified_on'=>$date);
				
				$this->db->where('pay_txn_id',$payres['id']);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
			   $attachpath = genarate_cpd_invoice($payres['invoice_id']);
				echo '<br>'.$payres['ref_id'];
			
			
		}
		
	}
	
	
	
}

public function dra_date_update()
{
	$select = 'ac.admitcard_id, ac.mem_exam_id, me.id, ac.exam_date, me.exam_date  AS ExamDate2';
	$this->db->where_in('ac.exm_cd',array('45','57'));
	$this->db->where('ac.exam_date !=','me.exam_date',false);
	$this->db->join('dra_member_exam me' ,'me.id = ac.mem_exam_id','Inner',false);
	$dra_exam_data = $this->Master_model->getRecords('dra_admit_card_details ac','',$select );
	// array('ac.remark' => 1)
	//echo $this->db->last_query(); echo count($dra_exam_data);  die;
	if(!empty($dra_exam_data))
	{
		foreach($dra_exam_data as $res)
		{
			$id = $res['mem_exam_id']; 
			$exam_date = $res['exam_date'];
			$this->db->limit(1);
			$update =array('exam_date' => $exam_date);
			$this->master_model->updateRecord('dra_member_exam',$update,array('id' => $id));
			echo count($id);
		}
	}
	
	
	
}

public function blended_mail_chaitali()
{
	$current_date = date('Y-m-d');
	//echo $current_date;
	$this->db->where('program_activation_delete','0');
    $end_date = $this->Master_model->getRecords('blended_program_activation_master','','program_reg_to_date');
	 echo $this->db->last_query();    
}

public function jaiib_mailsend()
{
				$final_str = '';
				$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'jaiib_caiib_member_exam_enrollment_nofee_elective'));
				echo $emailerstr[0]['emailer_text'];exit;
						
					       $info_arr=array('to'=>'iibfdevp@esds.co.in',
									//'to'=>'chaitali.jadhav@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'jaiib',
							'message'=>$emailerstr[0]['emailer_text']
				); 
				if($this->Emailsending->mailsend_attch($info_arr,''))
				{
					 
					echo'ok';
				}
					
				
				
			
}

public function blended_info()
{	//echo '***'; die;
 	//$prg_code = array('');
	//$mem = array(100042200);
	$select = 'member_number , email, contact_no , program_code';
	$this->db->where('DATE(create_date) >=', '2023-02-08');
	$this->db->where('DATE(create_date) <=', '2023-02-08'); 
	//$this->db->where_in('member_number' ,$mem);
	$member_data = $this->Master_model->getRecords('blended_eligible_master',array('program_code'=>'CCP'),$select);
	//echo $this->db->last_query(); die;
	if(!empty($member_data))
	{	
		foreach($member_data as $mem)
		{
			$insert_data = array(
			'member_no' =>$mem['member_number'],
			'email' =>$mem['email'],
			'contact_no' =>$mem['contact_no'],
			'program_code' =>$mem['program_code'],
			'email_sent_status' => '0' );
			
			$this->master_model->insertRecord('blended_mail_send', $insert_data);
		
		}
	}
	
}
public function test_mail()
{
	
				
						$final_str = 'Hello&nbsp';
						
						$final_str.= '<br/><br/>';  
						$final_str.= 'Thanks and Regards';
						$final_str.= '<br/>';
						$final_str.= 'IIBF TEAM'; 
						
						$info_arr=array('to'=>'esdstech@gmail.com', 
					'from'=>'logs@iibf.esdsconnect.com',
					'subject'=>'IIBF: Testing Email',
					'message'=>'Hi'
						); 
					$mail = $this->Emailsending->mailsend($info_arr);
			
				
					
					
			
					
				
}
public function blended_mailsend()
{
				$this->db->limit(500);
				//$this->db->where_in('','0');
				$regnumber_data = $this->Master_model->getRecords('blended_mail_send  ',array('email_sent_status'=>'0'));
				//echo $this->db->last_query(); die;
				if(!empty($regnumber_data))
				{
					foreach($regnumber_data as $res)
					{	
						$final_str = 'Hello&nbsp;'.$res['member_no'].', <br/><br/>';
						$final_str.= '<span style="color:red;">This is a General Reminder for Certified Credit Professional (CCP) candidates. If already applied or completed training, pl ignore the mail.';   
						 
						$final_str.= '<br/><br/>';
						$final_str.= 'Candidates receiving this direct intimation are eligible to apply.</span>';   
						$final_str.= '<br/><br/>';
						$final_str.= 'Dear Candidate,';
						$final_str.= '<br/><br/>';
						$final_str.= 'Greetings from IIBF!!';
						$final_str.= '<br/><br/>';
						$final_str.= 'We have announced Virtual Mode Training for Certified Credit Professional Course to be conducted from 15th to 17th February 2023';
						$final_str.= '<br/><br/>';
						$final_str.='<table border="1"><tr><th><b>Sr.No.</b></th><th colspan="2"><b>Certified Credit Professional Course Virtual Mode Training (Level 2)</b></th></tr>
						<tbody>
						<tr><td>1</td>
						<td>Training Period</td>
						<td>15.02.2023 to 17.02.2023</td></tr>

						<tr><td>2</td>
						<td>Training Time</td>
						<td>10 AM to 5 PM (Tentative)</td></tr>


						<tr><td>3</td>
						<td>Training Fees</td>
						<td>1st Attempt - Free<br>
						2nd Attempt – Rs 1000 + 18% GST
						</td></tr>

						<tr><td>4</td>
						<td>Last Date to Apply</td>
						<td>
						13th February 2023
						</td></tr>

						</tbody>
						</table>
						';
						/*$final_str.= 'Training Period :-11.10.2022 to 13.10.2022';
						$final_str.= '<br/>';
						$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
						$final_str.= '<br/>';
						$final_str.= 'Training Fees :-1st Attempt - 0 <br/> 2nd Attempt �  Rs 1000 + 18% GST';
						$final_str.= '<br/>';
						$final_str.= 'Last Date to Apply :- 9th October 2022';*/
						$final_str.= '<br/><br/>';
						$final_str.= 'Notification Link:<a href= "https://www.iibf.org.in/PostExamCCO2017.asp?ccono=155"> -     https://www.iibf.org.in/PostExamCCO2017.asp?ccono=155</a>';
						$final_str.= '<br/><br/>';
						$final_str.= 'Kindly apply before Last date<span style="color:red;"> (13th February 2023)</span>';
						//$final_str.= '<br/><br/>';
						//$final_str.= 'Note - Candidates whose results declared before announcement of this Training can apply. Candidates who have given Exam but results not declared on website, will not be eligible for this training. They will be eligible for subsequent trainings.
//Announcement date � 19-10-2020';
						$final_str.= '<br/><br/>';  
						$final_str.= 'Thanks and Regards';
						$final_str.= '<br/>';
						$final_str.= 'Vratesh Manjardekar'; 
						$final_str.= '<br/>';
						$final_str.= 'Training Department, IIBF'; 
						$final_str.= '<br/>';
						$final_str.= 'Leadership Centre, 3rd Floor. Tower 1, Commercial II,'; 
						$final_str.= '<br/>';
						$final_str.= 'Kohinoor City, Off LBS Marg, Kirol Road, Kurla (W), '; 
						$final_str.= '<br/>';
						$final_str.= 'Mumbai - 400070 '; 
						$final_str.= '<br/>';
						$final_str.= 'Landline – 022 6850 7042 '; 
						
					//	echo $final_str;exit;
						$info_arr=array('to'=>$res['email'],
							//'to'=>'chaitali.jadhav@esds.co.in', 
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF: Blended Training',
					'message'=>$final_str
				); 
				if($this->Emailsending->mailsend_attch($info_arr,''))
				{
					//echo "Mail send to => ".$res['member_no'];
				 	//echo "<br/>"; 
					$update_data = array('email_sent_status' => '1');
						 $this->master_model->updateRecord('blended_mail_send', $update_data,array('member_no'=>$res['member_no']));
					
				}
					}
				}
				
			
}


public function exam_count()
{
	$from_date = '2020-10-01';
	$to_date = '2020-10-29';
	if(!empty($from_date))
	{
		//payment count of current date
			$this->db->where('DATE(date) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('pay_type','2');
			$this->db->where('transaction_no !=','');
			//$this->db->group_by('exam_code');
			//$this->db->get('payment_transaction', 20);
		    $pay_count = $this->Master_model->getRecordCount('payment_transaction',array('status'=>'1'));
	    
 		//exam invoice count 
		    $this->db->where('DATE(date_of_invoice) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('app_type','O');
			$this->db->where('transaction_no !=','');
			$this->db->where('invoice_no !=','');
			//$this->db->group_by('exam_code');
			//$this->db->get('payment_transaction', 20);
		    $invoice_count = $this->Master_model->getRecordCount('exam_invoice','');
		
		//member application Paid
		    $this->db->where('DATE(created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('free_paid_flg','P');
			$this->db->where('institute_id ','0');
			$this->db->where('exam_period !=','999');
			$this->db->where('exam_code !=','191');
			//$this->db->group_by('exam_code');
			//$this->db->get('payment_transaction', 20);
		    $member_app_paid = $this->Master_model->getRecordCount('member_exam',array('pay_status'=>'1'));
			
		//admit card count Paid
			$this->db->where('DATE(created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('free_paid_flg','P');
			$this->db->where('seat_identification != ','');
			//$this->db->where('exam_period !=','999');
			$this->db->where('exm_cd !=','1015');
			//$this->db->group_by('exam_code');
			//$this->db->get('payment_transaction', 20);
		    $admit_card_paid = $this->Master_model->getRecordCount('admit_card_details',array('remark'=>'1'));
			
		//member application Free
			$this->db->where('DATE(created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('free_paid_flg','F');
			$this->db->where('institute_id ','0');
			$this->db->where('exam_period !=','999');
			$this->db->where('exam_code !=','191');
			//$this->db->group_by('exam_code');
			//$this->db->get('payment_transaction', 20);
		    $member_app_free = $this->Master_model->getRecordCount('member_exam',array('pay_status'=>'1'));
		
		//admit card count Free
			$this->db->where('DATE(created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');
			$this->db->where('free_paid_flg','F');
			$this->db->where('seat_identification != ','');
			//$this->db->where('exam_period !=','999');
			//$this->db->where('exam_code !=','191');
			//$this->db->group_by('exam_code');
			//$this->db->get('payment_transaction', 20);
		    $admit_card_free = $this->Master_model->getRecordCount('admit_card_details',array('remark'=>'1'));	
			//echo $this->db->last_query(); die;
			if($pay_count >= 0 && $invoice_count >= 0 && $member_app_paid >= 0 && $admit_card_paid >=0 && $member_app_free >=0 && $admit_card_free >=0)
			{
				$final_str = 'Hello <br/><br/>';

				$final_str.= 'Kindly find the Count from '.$from_date. ' To ' .$to_date ;   
				$final_str.= '<br/><br/>';
				$final_str.= 'Payment Count:- '.$pay_count;
				$final_str.= '<br/><br/>';
				$final_str.= 'Invoice Count:- '.$invoice_count;
				$final_str.= '<br/><br/>';
				$final_str.= 'Exam App(Paid):- '.$member_app_paid;
				$final_str.= '<br/><br/>';
				$final_str.= 'Admit Card(Paid):- '.$admit_card_paid;
				$final_str.= '<br/><br/>';
				$final_str.= 'Exam App(Free):- '.$member_app_free;
				$final_str.= '<br/><br/>';
				$final_str.= 'Admit Card(Free):- '.$admit_card_free;
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
					
				$info_arr=array(//'to'=>$new_mem_reg['email'],
							'to'=>'chaitali.jadhav@esds.co.in',
							'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF:Exam Count',
					'message'=>$final_str
				); 
				$this->Emailsending->mailsend_attch($info_arr,'');
					echo "Mail send to => chaitali.jadhav@esds.co.in";
					echo "<br/>"; 
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

}
