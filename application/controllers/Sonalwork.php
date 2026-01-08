<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Sonalwork extends CI_Controller {

	
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
		
	} 
	
	public function update_memmber_inst()
	{
		// inst_mem_update Table
		$this->db->limit('500');
		$inst_mem_update_arr = $this->master_model->getRecords('inst_mem_update',array('done'=>'0'),'member_no,inst_id');

		foreach($inst_mem_update_arr as $k => $v)
		{
			$institution_master_arr = $this->master_model->getRecords('institution_master',array('institude_id'=>$v['inst_id']),'institude_id');
			
			if(count($institution_master_arr) > 0)
			{
				$member_no = $v['member_no'];
				$inst_id = $v['inst_id'];
				
				$update_arr = array('associatedinstitute' => $inst_id);
				
				$this->master_model->updateRecord('member_registration',$update_arr,array('regnumber'=>$member_no));
				
				$this->master_model->updateRecord('inst_mem_update',array('done' => '1'),array('member_no'=>$member_no));
			}
		}
	} 
	
	public function dynamic_invoice_generation_sonal(){exit;
		$complete_arr = array();  
		$r_cat = array();
		$aug_insert = array();  
		$receipt_no_arr = array(901833190,901834322,901834324,901835662,901835735,901839332,901840023,901840327,901840638,901841071,901842007,901842319,901842569,901843219,901843626,901843816,901844372,901845014,901845079,901845163,901845224,901845270,901845360,901845383,901848545,901833087,901833347,901833387,901833495,901833555,901833890,901834135,901834235,901834856,901835018,901835485,901836631,901836842,901837631,901837784,901837980,901838216,901839103,901839412,901839537,901839626,901839828,901840406,901840516,901841015,901842016,901842106,901842635,901843080,901843177,901843205,901843329,901844842,901845076,901845089,901845113,901845167,901845219,901845239,901845351,901845933,901846949,901847680,901847770,901848079,901848139,901848245,901848389,901848794,901849663,901849677,901850313,901850476,901841534,901836261,901833538,901834343,901834839,901843463,901845249,901845437,901833567,901840477,901838967,901833380,901835051,901845289,901839082,901834240,901835519,901840150,901838415);     
		$sizearr = sizeof($receipt_no_arr);  
		for($i=0;$i<$sizearr;$i++){ 
			$payment = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no_arr[$i]),'member_regnumber,transaction_no,id,amount,ref_id,date,receipt_no');
			
			$member = $this->master_model->getRecords('member_exam',array('id'=>$payment[0]['ref_id']),'id,exam_code,exam_period,exam_center_code,created_on,modified_on');
			
			$registration = $this->master_model->getRecords('member_registration',array('regnumber'=>$payment[0]['member_regnumber']),'registrationtype');
			
			$this->db->where('exam_code',$member[0]['exam_code']);
			$this->db->where('eligible_period',$member[0]['exam_period']);
			$this->db->where('member_no',$payment[0]['member_regnumber']);
			$eligible = $this->master_model->getRecords('eligible_master','','app_category');
			/*echo '<pre>';
			print_r($eligible);
			echo '<br/>';*/
			
			if($eligible){
				if($eligible[0]['app_category'] == 'R'){
					$this->db->where('group_code','B1_1');
				}else{
					$this->db->where('group_code',$eligible[0]['app_category']);
				}
			}else{
				$this->db->where('group_code','B1_1');
			}
			$this->db->where('exam_code',$member[0]['exam_code']);
			$this->db->where('exam_period',$member[0]['exam_period']);
			$this->db->where('member_category',$registration[0]['registrationtype']);
			$ex = explode(" ",$payment[0]['date']);
			$pay_date=$ex[0];
			$this->db->where("'$pay_date' BETWEEN fr_date AND to_date");
			$fee = $this->master_model->getRecords('fee_master','','fee_amount,sgst_amt,cgst_amt,igst_amt,cs_tot,igst_tot');
			
			/*echo '>>>'. $this->db->last_query();
			echo '<pre>';
			print_r($fee);*/
			//exit;
			
			$this->db->where('exam_name',$member[0]['exam_code']);
			$this->db->where('exam_period',$member[0]['exam_period']);
			$this->db->where('center_code',$member[0]['exam_center_code']);
			$center = $this->master_model->getRecords('center_master','','center_name,state_code,state_description');
			
			//echo $this->db->last_query();
			//echo '<br/>';
			
			$state = $this->master_model->getRecords('state_master',array('state_code'=>$center[0]['state_code']),'state_no,exempt');
			
			/*echo $this->db->last_query();
			echo '<br/>';
			echo '>>'. $state[0]['state_no'];
			echo '<br/>';*/
			
			if($state[0]['state_no'] == 27){
				$cgst_rate = 9.00;
				$cgst_amt = $fee[0]['cgst_amt'];
				$sgst_rate = 9.00;
				$sgst_amt = $fee[0]['sgst_amt'];
				$cs_total = $fee[0]['cs_tot'];
				$igst_rate = 0.00;
				$igst_amt = 0.00;
				$igst_total = 0.00;
				$disc_rate = 0.00;
				$disc_amt = 0.00;
				$tds_amt = 0.00;
				$tax_type = 'Intra';
			}else{
				$cgst_rate = 0.00;
				$cgst_amt = 0.00;
				$sgst_rate = 0.00;
				$sgst_amt = 0.00;
				$cs_total = 0.00;
				$igst_rate = 18.00;
				$igst_amt = $fee[0]['igst_amt'];
				$igst_total = $fee[0]['igst_tot'];
				$disc_rate = 0.00;
				$disc_amt = 0.00;
				$tds_amt = 0.00;
				$tax_type = 'Inter';
			}
			
			$insert_arr = array(
								'exam_code' => $member[0]['exam_code'],
								'exam_period' => $member[0]['exam_period'],
								'center_code' => $member[0]['exam_center_code'],
								'center_name' => $center[0]['center_name'],
								'state_of_center' => $center[0]['state_code'],
								'member_no' => $payment[0]['member_regnumber'],
								'pay_txn_id' => $payment[0]['id'],
								'receipt_no' => $payment[0]['receipt_no'],
								'transaction_no' => $payment[0]['transaction_no'],
								'gstin_no' => '',
								'service_code' => 999294,
								'qty' => 1,
								'fresh_fee' => 0.00,
								'rep_fee' => 0.00,
								'fresh_count' => 0,
								'rep_count' => 0,
								'cess' => 0.00,
								'institute_code' => 0,
								'institute_name' => '',
								'state_code' => $state[0]['state_no'],
								'state_name' => $center[0]['state_description'],
								'invoice_no' => '',
								'invoice_image' => '',
								'fee_amt' => $fee[0]['fee_amount'],
								'cgst_rate' => $cgst_rate,
								'cgst_amt' => $cgst_amt,
								'sgst_rate' => $sgst_rate,
								'sgst_amt' => $sgst_amt,
								'cs_total' => $cs_total,
								'igst_rate' => $igst_rate,
								'igst_amt' => $igst_amt,
								'igst_total' => $igst_total,
								'disc_rate' => $disc_rate,
								'disc_amt' => $disc_amt,
								'tds_amt' => $tds_amt,
								'date_of_invoice' => $member[0]['modified_on'],
								'created_on' => $member[0]['created_on'],
								'modified_on' => $member[0]['modified_on'],
								'tax_type' => $tax_type,
								'app_type' => 'O',
								'exempt' => $state[0]['exempt']
								);
			/*echo '<pre>';
			print_r($insert_arr);
			echo '<br/>';
			exit;*/ 
			
			$exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no_arr[$i]),'invoice_id');
			
			/*echo 'here'.$eligible[0]['app_category'];
			exit;*/
			
			if(count($eligible) > 0){
			
			if($eligible[0]['app_category']!=''){
				if($exam_invoice[0]['invoice_id'] == ''){
					$last_id = $this->master_model->insertRecord('exam_invoice',$insert_arr,true);
					if($last_id > 0){
						$config_inset_arr = array(
													'invoice_id' => $last_id,
													'created_date' => $member[0]['modified_on']
												);
						$config_last_id = $this->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true);
						$invoice_no = 'EX/19-20/'.$config_last_id;
						$invoice_image = $payment[0]['member_regnumber'].'_EX_21-22_'.$config_last_id.'.jpg';
						$update_arr = array(
											'invoice_no' => $invoice_no,
											'invoice_image' => $invoice_image
										);
						$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$last_id));
					}
				}else{
					echo $payment[0]['receipt_no'].'Dupicate entry';
					echo '<br/>'; 
				}
			}else{
					$r_cat[] = $payment[0]['receipt_no']; 
					$aug_insert = array('receipt_no'=>$receipt_no_arr[$i],'exm_cd'=>$member[0]['exam_code']);
					$this->master_model->insertRecord('aug_invoice',$aug_insert,true);
					
			}
			}else{
				if($exam_invoice[0]['invoice_id'] == ''){
					$last_id = $this->master_model->insertRecord('exam_invoice',$insert_arr,true);
					if($last_id > 0){
						$config_inset_arr = array(
													'invoice_id' => $last_id,
													'created_date' => $member[0]['modified_on']
												);
						$config_last_id = $this->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true);
						$invoice_no = 'EX/19-20/'.$config_last_id;
						$invoice_image = $payment[0]['member_regnumber'].'_EX_21-22_'.$config_last_id.'.jpg';
						$update_arr = array(
											'invoice_no' => $invoice_no,
											'invoice_image' => $invoice_image
										);
						$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$last_id));
					}
				}else{
					echo $payment[0]['receipt_no'].'Dupicate entry';
					echo '<br/>'; 
				}
			}
			$complete_arr[] = $payment[0]['receipt_no'];
		} 
		
		echo '<pre>';
		print_r($r_cat);
		echo '<br/>';
		
		echo '<pre>';
		print_r($complete_arr);
		//8007701593
	}
	
	public function jaiib_duplicate_invoice(){ exit;
		$this->db->where('generate_flag',0);
		//$this->db->limit(50,0);
		$settel = $this->master_model->getRecords('jaiib_dup_invoice');
		foreach($settel as $res){
			echo $res['invoice_id'];
			echo '<br/>';
			echo $res['reciept_no'];
			echo '<br/>'; 
			$payment = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$res['reciept_no']),'member_regnumber,transaction_no,id,amount,ref_id,date,receipt_no');
			
			$member = $this->master_model->getRecords('member_exam',array('id'=>$payment[0]['ref_id']),'id,exam_code,exam_period,exam_center_code,created_on,modified_on');
			
			$registration = $this->master_model->getRecords('member_registration',array('regnumber'=>$payment[0]['member_regnumber']),'registrationtype');
			
			$exam_invoice = $this->master_model->getRecords('exam_invoice',array('invoice_id'=>$res['invoice_id']),'invoice_image,invoice_no');
			
			$this->db->where('exam_code',$member[0]['exam_code']);
			$this->db->where('eligible_period',$member[0]['exam_period']);
			$this->db->where('member_no',$payment[0]['member_regnumber']);
			$eligible = $this->master_model->getRecords('eligible_master','','app_category');
			/*echo '<pre>';
			print_r($eligible);
			echo '<br/>';*/
			
			if($eligible){
				if($eligible[0]['app_category'] == 'R'){
					$this->db->where('group_code','B1_1');
				}else{
					$this->db->where('group_code',$eligible[0]['app_category']);
				}
			}else{
				$this->db->where('group_code','B1_1');
			}
			$this->db->where('exam_code',$member[0]['exam_code']);
			$this->db->where('exam_period',$member[0]['exam_period']);
			$this->db->where('member_category',$registration[0]['registrationtype']);
			$ex = explode(" ",$payment[0]['date']);
			$pay_date=$ex[0];
			$this->db->where("'$pay_date' BETWEEN fr_date AND to_date");
			$fee = $this->master_model->getRecords('fee_master','','fee_amount,sgst_amt,cgst_amt,igst_amt,cs_tot,igst_tot');
			
			/*echo '>>>'. $this->db->last_query();
			echo '<pre>';
			print_r($fee);*/
			//exit;
			
			$this->db->where('exam_name',$member[0]['exam_code']);
			$this->db->where('exam_period',$member[0]['exam_period']);
			$this->db->where('center_code',$member[0]['exam_center_code']);
			$center = $this->master_model->getRecords('center_master','','center_name,state_code,state_description');
			
			//echo $this->db->last_query();
			//echo '<br/>';
			
			$state = $this->master_model->getRecords('state_master',array('state_code'=>$center[0]['state_code']),'state_no,exempt');
			
			/*echo $this->db->last_query();
			echo '<br/>';
			echo '>>'. $state[0]['state_no'];
			echo '<br/>';*/
			
			if($state[0]['state_no'] == 27){
				$cgst_rate = 9.00;
				$cgst_amt = $fee[0]['cgst_amt'];
				$sgst_rate = 9.00;
				$sgst_amt = $fee[0]['sgst_amt'];
				$cs_total = $fee[0]['cs_tot'];
				$igst_rate = 0.00;
				$igst_amt = 0.00;
				$igst_total = 0.00;
				$disc_rate = 0.00;
				$disc_amt = 0.00;
				$tds_amt = 0.00;
				$tax_type = 'Intra';
			}else{
				$cgst_rate = 0.00;
				$cgst_amt = 0.00;
				$sgst_rate = 0.00;
				$sgst_amt = 0.00;
				$cs_total = 0.00;
				$igst_rate = 18.00;
				$igst_amt = $fee[0]['igst_amt'];
				$igst_total = $fee[0]['igst_tot'];
				$disc_rate = 0.00;
				$disc_amt = 0.00;
				$tds_amt = 0.00;
				$tax_type = 'Inter';
			}
			
			$image_name = $exam_invoice[0]['invoice_image'];
			$image_name_part = explode("_",$image_name);
			
			$updated_image_name = $payment[0]['member_regnumber']."_".$image_name_part[1]."_".$image_name_part[2]."_".$image_name_part[3]."_".$image_name_part[4];
			
			/*echo '<br/>';
			echo '<pre>';
			print_r($image_name_part);
			exit;*/
			
			$update_arr = array(
								'exam_code' => $member[0]['exam_code'],
								'exam_period' => $member[0]['exam_period'],
								'center_code' => $member[0]['exam_center_code'],
								'center_name' => $center[0]['center_name'],
								'state_of_center' => $center[0]['state_code'],
								'member_no' => $payment[0]['member_regnumber'],
								'pay_txn_id' => $payment[0]['id'],
								'receipt_no' => $payment[0]['receipt_no'],
								'transaction_no' => $payment[0]['transaction_no'],
								'gstin_no' => '',
								'service_code' => 999294,
								'qty' => 1,
								'fresh_fee' => 0.00,
								'rep_fee' => 0.00,
								'fresh_count' => 0,
								'rep_count' => 0,
								'cess' => 0.00,
								'institute_code' => 0,
								'institute_name' => '',
								'state_code' => $state[0]['state_no'],
								'state_name' => $center[0]['state_description'],
								'invoice_no' => $exam_invoice[0]['invoice_no'],
								'invoice_image' => $updated_image_name,
								'fee_amt' => $fee[0]['fee_amount'],
								'cgst_rate' => $cgst_rate,
								'cgst_amt' => $cgst_amt,
								'sgst_rate' => $sgst_rate,
								'sgst_amt' => $sgst_amt,
								'cs_total' => $cs_total,
								'igst_rate' => $igst_rate,
								'igst_amt' => $igst_amt,
								'igst_total' => $igst_total,
								'disc_rate' => $disc_rate,
								'disc_amt' => $disc_amt,
								'tds_amt' => $tds_amt,
								'date_of_invoice' => $member[0]['modified_on'],
								'created_on' => $member[0]['created_on'],
								'modified_on' => $member[0]['modified_on'],
								'tax_type' => $tax_type,
								'app_type' => 'O',
								'exempt' => $state[0]['exempt']
								);
								
								
			    $this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$res['invoice_id']));
				
				
				$u_arr = array('generate_flag'=>1);				
				$this->master_model->updateRecord('jaiib_dup_invoice',$u_arr,array('invoice_id'=>$res['invoice_id'],'reciept_no'=>$res['reciept_no']));			
				
			
		}
	}

	/*public function insert_data()
		{
			$insert=array('pay_txn_id'=> '3938427','receipt_no'=> '901993804','exam_code'=> '177','center_code'=> '530','center_name'=> 'KANPUR','state_of_center'=> 'UTT','member_no'=> '801392291','app_type'=> 'O','exam_period'=> '907','service_code'=> '999294','qty'=> 1,'state_code'=> '09','state_name'=> 'UTTAR PRADESH','tax_type'=> 'Inter','fee_amt'=> '1700','cgst_rate'=> ,'cgst_amt'=> ,'sgst_rate'=> ,'sgst_amt'=> ,'igst_rate'=> '18','igst_amt'=> '306','cs_total'=> ,'igst_total'=> '2006','exempt'=> 'NE','gstin_no'=> ,'created_on'=> '2019-10-01 12:41:51');
echo $last_id=$this->master_model->insertRecord('exam_invoice',$insert,true);
echo $this->db->last_query();
			
		}*/
	 
}