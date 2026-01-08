<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpayment extends CI_Controller {

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
	 *
	custom_examinvoice_send_mail * So any other public methods not prefixed with an underscore will
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
		 //$this->load->helper('bulk_admitcard_helper');
		 $this->load->helper('custom_contact_classes_invoice_helper');
		 $this->load->helper('custom_admitcard_helper');
		 //$this->load->helper('bulk_check_helper');
		 //$this->load->helper('bulk_seatallocation_helper');
		 $this->load->helper('bulk_invoice_helper');
		 $this->load->helper('bulk_admitcard_helper');
		 $this->load->helper('custom_invoice_helper');
		 $this->load->helper('blended_invoice_custom_helper');
		 $this->load->helper('bulk_calculate_tds_discount_helper');
		 $this->load->helper('bulk_proforma_invoice_helper');
		 $this->load->helper('getregnumber_helper');
		
	} 
	
	
public function cs2c_invoice_settelment(){
	
	$query="SELECT c.receipt_no FROM `payment_transaction` `a` LEFT JOIN `exam_invoice` `c` ON a.receipt_no = c.receipt_no WHERE c.created_on <= NOW() - INTERVAL 30 MINUTE AND a.pay_type = 2 AND a.status != 1 AND a.status != 3 and c.invoice_no != '' and c.transaction_no != '' and c.exam_period = '121' AND c.app_type IN ('O') AND c.app_type != 'Z'  ORDER BY `c`.`created_on` DESC  ";

	$record = $this->db->query($query);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$this->db->where('receipt_no',$c_row['receipt_no']);
			$this->db->where('transaction_no !=','');
			$this->db->where('invoice_no !=','');
			$this->db->where('invoice_image !=','');
			$this->db->where('exam_period',121);
			$invoice = $this->Master_model->getRecords('exam_invoice','','invoice_id,transaction_no,member_no,pay_txn_id');
			
			$this->db->where('receipt_no',$c_row['receipt_no']);
			//$this->db->where('status',2);
			$this->db->order_by("id", "desc"); 
			$this->db->limit(1); 
			$payment = $this->Master_model->getRecords('payment_transaction','','status,id,ref_id');
			
			$payment_update_remark = array('status'=>'1','transaction_no'=>$invoice[0]['transaction_no'],'transaction_details'=>'SUCCESS','callback'=>'c_S2S');
			$payment_where = array('id'=>$payment[0]['id'],'receipt_no'=>$c_row['receipt_no']);
			$this->master_model->updateRecord('payment_transaction',$payment_update_remark,$payment_where);
			
			$this->db->where('id',$payment[0]['ref_id']);
			$member_exam = $this->Master_model->getRecords('member_exam','','pay_status,id');
			
			if($member_exam[0]['pay_status'] == 0){
				$member_exam_update_remark = array('pay_status'=>'1');
				$member_exam_remark = array('id'=>$payment[0]['ref_id']);
				$this->master_model->updateRecord('member_exam',$member_exam_update_remark,$member_exam_remark); 
			}
			
			$invoice_insert_array = array('receipt_no'=>$c_row['receipt_no'],'member_exam_id'=>$payment[0]['ref_id']);
			$this->master_model->insertRecord('cs2c_invoice_settelment', $invoice_insert_array);
		}
	}
	
}
	

public function member_settlement_new_121(){  

	

	$Settel = array();  
	$missing = array();
	$capacity_full = array();
	
	$this->db->where('admit_update',0);
	$this->db->limit(25);
	$member_exam = $this->Master_model->getRecords('cs2c_invoice_settelment','','member_exam_id,id');
	
	
	foreach($member_exam as $rec){ 
		// chk how much row has seat number
		$this->db->where('remark',1);
		$this->db->where('mem_exam_id',$rec['member_exam_id']);
		$sql1 = $this->master_model->getRecords('admit_card_details');
		
		
		if(count($sql1) <= 0){
			
			
			
			$this->db->where('mem_exam_id',$rec['member_exam_id']);
			$this->db->group_by('sub_cd,mem_exam_id');
			$admit_card_details = $this->master_model->getRecords('admit_card_details');
			
			
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
			
			if(!empty($admit_card_details))
			{
				
				
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
				
							$venue_capacity=$venue_capacity[0]['session_capacity']+500;  
							if(!empty($venue_capacity))
							{
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
											$this->master_model->updateRecord('cs2c_invoice_settelment', array('admit_update'=>1),array('id'=>$rec['id']));
										}else
										{
											
										}
									}
								}else
								{
									
								}
							}else
							{
								
							}
						}else
						{
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date']));
								
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
											$this->master_model->updateRecord('cs2c_invoice_settelment', array('admit_update'=>1),array('id'=>$rec['id']));
										}else
										{
											
										}
									}
								}else
								{
									
								}
							}else
							{
								
							}
						}}
					}
				}
		} 
		else{
			
			$this->master_model->updateRecord('cs2c_invoice_settelment', array('admit_update'=>3),array('id'=>$rec['id']));
		}
	} // end of for
}


public function member_exam_not_update(){ 
	
	$query="SELECT c.id,c.created_on FROM `payment_transaction` `a` LEFT JOIN `member_exam` `c` ON a.ref_id = c.id WHERE a.date <= NOW() - INTERVAL 60 MINUTE AND a.status = 1 AND c.pay_status = 0 and c.exam_period = '121'  ";
	
	$record = $this->db->query($query); 
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			$this->db->where('ref_id',$c_row['id']);
			$this->db->where('status',1);
			$this->db->order_by("id", "desc"); 
			$this->db->limit(1); 
			$payment = $this->Master_model->getRecords('payment_transaction','','status,id,ref_id,receipt_no');
			
			//echo $this->db->last_query(); 
			//exit;
			
			$this->db->where('receipt_no',$payment[0]['receipt_no']);
			$exam_invoice = $this->Master_model->getRecords('exam_invoice','','transaction_no,invoice_no');
			
			if($exam_invoice[0]['transaction_no'] != '' && $exam_invoice[0]['invoice_no'] != ''){
			
			if(count($payment) > 0){
				$this->db->where('id',$payment[0]['ref_id']);
				$member_exam = $this->Master_model->getRecords('member_exam','','pay_status,id');
				
				if($member_exam[0]['pay_status'] == 0){
					$member_exam_update_remark = array('pay_status'=>'1');
					$member_exam_remark = array('id'=>$payment[0]['ref_id']);
					$this->master_model->updateRecord('member_exam',$member_exam_update_remark,$member_exam_remark); 
					
					$invoice_insert_array = array('receipt_no'=>$payment[0]['receipt_no'],'member_exam_id'=>$payment[0]['ref_id']);
					$this->master_model->insertRecord('cs2c_invoice_settelment', $invoice_insert_array);
					
				}
			}
			
			}
			
		}
	}
}


function random_password($length = 6){
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	$password = substr( str_shuffle( $chars ), 0, $length );
	return $password;
}	


public function no_payment_caiib(){ 

	$arr = array(902736331);
	
		for($i=0;$i < sizeof($arr); $i++){
			$responsedata = sbiqueryapi('902737232');
			$receipt_no='902737232';
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
			echo '<pre>';
			print_r($responsedata);
			exit;	
			$resp_array = array('receipt_no'	=> $arr[$i],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment', $resp_array);
			
		}
	
}

public function no_payment(){ 

	$arr = array();
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE `exam_code` IN (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').") AND `date` LIKE '%2021-03-08%' AND `pay_type` = 2 AND `status` = 2 ";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment', $resp_array);
			
		}
	}
}


public function no_payment_one(){  

	$arr = array();
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE `exam_code` IN (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').") AND `date` LIKE '%2021-03-18%' AND `pay_type` = 2 AND `status` = 2 "; 
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment', $resp_array);
			
		}
	}
}

public function no_payment_two(){ 

	
	 $sql = "SELECT receipt FROM `receipt_number` WHERE  `status` = 0 "; 
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt']);
			$receipt_no=$c_row['receipt'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
			$resp_array = array('receipt_no'	=> $c_row['receipt'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			if($this->master_model->insertRecord('no_payment_round2', $resp_array))
			{
				$payment_update_remark = array('status'=>1);
				$payment_where = array('receipt'=>$c_row['receipt']);
				$this->master_model->updateRecord('receipt_number',$payment_update_remark,$payment_where);
			}
			
			
			
		}
	}
}
	
	
	public function no_payment_four(){ 

	
	 $sql = "SELECT receipt FROM `receipt_number` WHERE  `status` = 0  ORDER BY id DESC"; 
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt']);
			$receipt_no=$c_row['receipt'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
			$resp_array = array('receipt_no'	=> $c_row['receipt'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			if($this->master_model->insertRecord('no_payment_round2', $resp_array))
			{
				$payment_update_remark = array('status'=>1);
				$payment_where = array('receipt'=>$c_row['receipt']);
				$this->master_model->updateRecord('receipt_number',$payment_update_remark,$payment_where);
			}
			
			
			
		}
	}
}

	
	public function no_payment_three(){ 

	
	 $sql = "SELECT receipt FROM `receipt_number_march` WHERE  `status` = 0 "; 
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt']);
			$receipt_no=$c_row['receipt'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
			$resp_array = array('receipt_no'	=> $c_row['receipt'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			if($this->master_model->insertRecord('no_payment_round2', $resp_array))
			{
				$payment_update_remark = array('status'=>1);
				$payment_where = array('receipt'=>$c_row['receipt']);
				$this->master_model->updateRecord('receipt_number_march',$payment_update_remark,$payment_where);
			}
			
			
			
		}
	}
}
	
	
	public function no_payment_five(){ 

	
	 $sql = "SELECT receipt FROM `receipt_number_march` WHERE  `status` = 0 ORDER BY id DESC"; 
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt']);
			$receipt_no=$c_row['receipt'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
			$resp_array = array('receipt_no'	=> $c_row['receipt'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			if($this->master_model->insertRecord('no_payment_round2', $resp_array))
			{
				$payment_update_remark = array('status'=>1);
				$payment_where = array('receipt'=>$c_row['receipt']);
				$this->master_model->updateRecord('receipt_number_march',$payment_update_remark,$payment_where);
			}
			
			
			
		}
	}
}
	
	
	public function no_payment_six(){ 

	$arr = array();
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE `exam_code` IN (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').") AND `date` LIKE '%2021-03%' AND `pay_type` = 2 AND `status` = 0 ORDER BY receipt_no DESC";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_status_zero', $resp_array);
			
		}
	}
}
	
	
	public function no_payment_seven(){ 

	$arr = array();
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE `exam_code` IN (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').") AND `date` LIKE '%2021-02%' AND `pay_type` = 2 AND `status` = 0 ORDER BY receipt_no DESC  ";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_status_zero', $resp_array);
			
		}
	}
}
	
	
	
	public function no_payment_8(){ 

	$arr = array();
	
	//$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `date` LIKE '%2021-04-04%' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 0,1000";
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 6001,7000";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}
	
	
	public function no_payment_9(){ 

	$arr = array();
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 8001,9000";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}
	
		public function no_payment_10(){ 

	$arr = array();
	
$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 9001,10000";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S', 
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}


public function no_payment_11(){ 

	$arr = array();
	
$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 3001,4000";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}


public function no_payment_12(){ 

	$arr = array();
	
$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 4001,5000";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}

public function no_payment_13(){ 

	$arr = array();
	
$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no DESC LIMIT 5001,6000";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}



public function no_payment_14(){ 

	$arr = array();
	
$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `pg_flag` ='iibfregn' AND date>'2021-02-23' AND (`status` = 0  OR `status` = 2 )ORDER BY receipt_no ASC";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_register', $resp_array);
			
		}
	}
}


	public function get_status()
	{
			$receipt_1 = $this->Master_model->getRecords('receipt_number');
		
			$this->db->where('status','1');
			$receipt_1_searched = $this->Master_model->getRecords('receipt_number');
		
			$this->db->where('status','0');
			$receipt_1_remain = $this->Master_model->getRecords('receipt_number');
		//------------------------------------------------------------------------------------------------------//		
			$receipt_2 = $this->Master_model->getRecords('receipt_number_march');
			
			$this->db->where('status','1');
			$receipt_2_searched = $this->Master_model->getRecords('receipt_number_march');
		
			$this->db->where('status','0');
			$receipt_2_remain = $this->Master_model->getRecords('receipt_number_march');
		//------------------------------------------------------------------------------------------------------//		
			$receipt_3 =count($receipt_1) + count($receipt_2);
			$receipt_3_searched=count($receipt_1_searched) + count($receipt_2_searched);
			$receipt_3_remain=count($receipt_1_remain) + count($receipt_2_remain);
		
			$this->db->where('txn_status','SUCCESS');
			$Total_success = $this->Master_model->getRecords('no_payment_round2');
		//------------------------------------------------------------------------------------------------------//		
			echo 'Total Record to get SBI API Status from 24-feb-2021 TO 10th -March-2021 ='.$receipt_3;
			echo '<br>';
			echo 'Total Record searched ='.$receipt_3_searched;
			echo '<br>';
			echo 'Total Remaining record to be search ='.$receipt_3_remain;
			echo '<br>';
			//echo 'Total Success at SBI END NO response at ESDS END ='.count($Total_success);
		echo 'Total Success at SBI END NO response at ESDS END =3055';
		
	} 
	
public function no_payment_chaitali() {  	 
$arr = array(812294204,812294205,812304757,903207183,903207333,903207380,903207408,903207423,903207428,903207566,903207679,903207700,903207702,903207803,903207824,903207829,903207865,903207897,903276118,903276135,903276145,903276147,903276174,903276201,903276204,903276209,903276251,903276260,903310104,903310293,903313083,903313801,903319270,903326071,903326103,903328071,903329056);

 $size = count($arr);		$success_rec = array();		for($i=0;$i<$size;$i++){						$responsedata = sbiqueryapi($arr[$i]);			$receipt_no=$c_row['receipt_no'];			$encData=implode('|',$responsedata);			$resp_data = json_encode($responsedata);	echo '<pre>';	print_r($resp_data);			if($responsedata[2] == 'SUCCESS')			{				array_push($success_rec,$responsedata[6]);			}			/* $resp_array = array('receipt_no'	=> $arr[$i],								'txn_status' 	=> $responsedata[2],								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',								'response_data' => $resp_data,								'remark' 		=> '',								'resp_date' 	=> date('Y-m-d H:i:s'),								);			$this->master_model->insertRecord('no_payment', $resp_array); */					}print_r($success_rec);	} 
public function no_payment_other_exam(){ 

	$arr = array();
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE `exam_code` NOT IN ($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'),991) AND `date` LIKE '%2021-03-18%' AND `pay_type` = 2 AND `status` = 2 ";  
	
	$record = $this->db->query($sql);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_other_exam', $resp_array);
			
		}
	}
}
 

public function no_payment_registration(){ 

	$arr = array(); 
	
	$sql = "SELECT receipt_no FROM `payment_transaction` WHERE  `date` LIKE '%2021-03-18%' AND `pay_type` = 1 AND `status` = 2 ";   
	
	$record = $this->db->query($sql); 
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
								'txn_status' 	=> $responsedata[2],
								'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark' 		=> '',
								'resp_date' 	=> date('Y-m-d H:i:s'),
								);
			$this->master_model->insertRecord('no_payment_registration', $resp_array);
			
		}
	}
}

}


